<?php
require('../fpdf/fpdf.php');
require('../fpdf/font/angsa.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sales_team = isset($_POST['sales_team']) ? $conn->real_escape_string($_POST['sales_team']) : '';
    $task_status = isset($_POST['task_status']) ? $conn->real_escape_string($_POST['task_status']) : '';
    $start_date = isset($_POST['start_date']) ? $conn->real_escape_string($_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? $conn->real_escape_string($_POST['end_date']) : '';

    $query = "SELECT view.*, task.T_Status 
              FROM view 
              LEFT JOIN task ON view.V_ID = task.T_ID 
              WHERE 1=1";

    if (!empty($sales_team)) {
        $query .= " AND view.V_Sale = '$sales_team'";
    }
    if (!empty($task_status)) {
        $query .= " AND task.T_Status = '$task_status'";
    }
    if (!empty($start_date) && !empty($end_date)) {
        $query .= " AND view.V_Date BETWEEN '$start_date' AND '$end_date'";
    }

    $query .= " ORDER BY view.V_Date ASC";

    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    if (isset($_POST['view'])) {
        if ($result->num_rows > 0) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Results</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/evo/css/process_report.css">
</head>
<body>

<div class="container-fluid">
    <h2 class="text-center my-4">Report Results</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Sale</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Province</th>
                    <th>Exec Name</th>
                    <th>Exec Phone</th>
                    <th>Electric Per Year (บาท)</th>
                    <th>Electric Per Month (บาท)</th>
                    <th>Peak Year</th>
                    <th>Peak Month</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['V_Sale'] ?></td>
                        <td><?= date('d-m-Y', strtotime($row['V_Date'])) ?></td>
                        <td><?= $row['V_Name'] ?></td>
                        <td><?= $row['V_SubDistrict'] ?></td>
                        <td><?= $row['V_ExecName'] ?></td>
                        <td><?= $row['V_ExecPhone'] ?></td>
                        <td><?= ($row["V_Electric_per_year"] == 0) ? 'N/A' : number_format($row["V_Electric_per_year"], 2) ?></td>
                        <td><?= ($row["V_Electric_per_month"] == 0) ? 'N/A' : number_format($row["V_Electric_per_month"], 2) ?></td>
                        <td><?= ($row["V_Peak_year"] == 0) ? 'N/A' : number_format($row["V_Peak_year"], 2) ?></td>
                        <td><?= ($row["V_Peak_month"] == 0) ? 'N/A' : number_format($row["V_Peak_month"], 2) ?></td>
                        <td><?= $row['T_Status'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
<?php
        } else {
            echo "<p>No data available.</p>";
        }
    } elseif (isset($_POST['pdf'])) {
        if ($result->num_rows > 0) {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->AddFont('angsa', '', 'angsa.php');
            $pdf->SetFont('angsa', '', 16);
            $pdf->Cell(0, 10, 'Report Results', 0, 1, 'C');

            $pdf->SetFont('angsa', '', 16);
            $header = array('Sale', 'Date', 'Name', 'Province', 'Exec Name', 'Exec Phone', 'Electric Per Year (บาท)', 'Electric Per Month (บาท)', 'Peak Year', 'Peak Month', 'Status');
            foreach ($header as $col) {
                $pdf->Cell(30, 7, $col, 1);
            }
            $pdf->Ln();

            $pdf->SetFont('angsa', '', 16);
            while ($row = $result->fetch_assoc()) {
                $pdf->Cell(30, 6, $row['V_Sale'], 1);
                $pdf->Cell(30, 6, date('d-m-Y', strtotime($row['V_Date'])), 1);
                $pdf->Cell(30, 6, $row['V_Name'], 1);
                $pdf->Cell(30, 6, $row['V_SubDistrict'], 1);
                $pdf->Cell(30, 6, $row['V_ExecName'], 1);
                $pdf->Cell(30, 6, $row['V_ExecPhone'], 1);
                $pdf->Cell(30, 6, ($row["V_Electric_per_year"] == 0) ? 'N/A' : number_format($row["V_Electric_per_year"], 2), 1);
                $pdf->Cell(30, 6, ($row["V_Electric_per_month"] == 0) ? 'N/A' : number_format($row["V_Electric_per_month"], 2), 1);
                $pdf->Cell(30, 6, ($row["V_Peak_year"] == 0) ? 'N/A' : number_format($row["V_Peak_year"], 2), 1);
                $pdf->Cell(30, 6, ($row["V_Peak_month"] == 0) ? 'N/A' : number_format($row["V_Peak_month"], 2), 1);
                $pdf->Cell(30, 6, $row['T_Status'], 1);
                $pdf->Ln();
            }

            $pdf_data = $pdf->Output('', 'S');
            echo '<script>';
            echo 'var pdfWindow = window.open("", "_blank");';
            echo 'pdfWindow.document.write("<embed width=\'100%\' height=\'100%\' src=\'data:application/pdf;base64,' . base64_encode($pdf_data) . '\' type=\'application/pdf\'/>");';
            echo '</script>';
        } else {
            echo "<p>No data available to generate PDF.</p>";
        }
    }
}
?>
