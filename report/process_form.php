<?php
require_once('../vendor/autoload.php'); 

$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Sales Report');
$pdf->SetSubject('Sales Report');
$pdf->SetKeywords('TCPDF, PDF, report, sales');

$pdf->AddFont('prompt', '', dirname(__FILE__).'/fonts/Prompt-Regular.ttf', true);
$pdf->SetFont('prompt', '', 12);

$pdf->SetMargins(10, 8, 10);
$pdf->SetFooterMargin(8);
$pdf->SetAutoPageBreak(TRUE, 10);

$pdf->AddPage();

$pdf->SetFont('prompt', 'B', 16);
$pdf->Cell(0, 0, 'รายงานการนำส่งการไฟฟ้า', 0, 1, 'C');
$pdf->Ln(10); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

if ($result->num_rows > 0) {
    $pdf->SetFont('prompt', 'B', 14);

    $pdf->Cell(30, 10, 'Sale', 1, 0, 'C');
    $pdf->Cell(35, 10, 'Date', 1, 0, 'C');
    $pdf->Cell(120, 10, 'Name', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Status', 1, 0, 'C');
    $pdf->Ln();

    $pdf->SetFont('prompt', '', 12);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(30, 10, $row['V_Sale'], 1, 0, 'L');
        $pdf->Cell(35, 10, date('d-m-Y', strtotime($row['V_Date'])), 1, 0, 'L');
        $pdf->Cell(120, 10, $row['V_Name'], 1, 0, 'L');
        $pdf->Cell(40, 10, $row['T_Status'], 1, 0, 'L');
        $pdf->Ln();
    }

    $pdf->Output('sales_report.pdf', 'I');
} else {
    echo "No data available for the selected criteria.";
}

$conn->close();
?>
