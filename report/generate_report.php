<?php
include '../connect.php';

// Function to generate PDF from data array
function generatePDF($data) {
    require('../fpdf/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, 'PDF Report from Database');
    $pdf->Ln();

    // Header row
    $pdf->Cell(40, 10, 'View ID');
    $pdf->Cell(40, 10, 'View Name');
    $pdf->Cell(40, 10, 'Province');
    $pdf->Ln();

    // Data rows
    foreach ($data as $row) {
        $pdf->Cell(40, 10, $row['V_ID']);
        $pdf->Cell(40, 10, $row['V_Name']);
        $pdf->Cell(40, 10, $row['V_Province']);
        $pdf->Ln();
    }

    $pdf->Output();
}

// Check if the "View" button is clicked
if(isset($_POST['view'])){
    $selected_team = $_POST['branch'];
    $selected_date = $_POST['date'];

    // Fetch data from the database based on selected criteria
    $sql = "SELECT * FROM `view` WHERE `V_Sale` = '$selected_team' AND `V_Date` = '$selected_date'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Store fetched data in an array
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Display data in a table
        echo "<table border='1'>
                <tr>
                    <th>View ID</th>
                    <th>View Name</th>
                    <th>Province</th>
                </tr>";
        foreach ($data as $row) {
            echo "<tr>
                    <td>" . $row["V_ID"] . "</td>
                    <td>" . $row["V_Name"] . "</td>
                    <td>" . $row["V_Province"] . "</td>
                </tr>";
        }
        echo "</table>";

        // Generate PDF button
        echo '<form action="" method="post">
                <input type="hidden" name="team" value="'.$selected_team.'">
                <input type="hidden" name="date" value="'.$selected_date.'">
                <button type="submit" name="generate_pdf" class="btcolor">Generate PDF</button>
              </form>';
    } else {
        echo "No records found.";
    }
}

// Generate PDF if "Generate PDF" button is clicked
if(isset($_POST['generate_pdf'])){
    $selected_team = $_POST['team'];
    $selected_date = $_POST['date'];

    // Fetch data from the database based on selected criteria
    $sql = "SELECT * FROM `view` WHERE `V_Sale` = '$selected_team' AND `V_Date` = '$selected_date'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Store fetched data in an array
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Generate PDF from data array
        generatePDF($data);
    } else {
        echo "No records found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add your meta tags, stylesheets, and scripts -->
</head>
<body class="bgcolor">
    <?php include '../header.php'; ?>
    <div class="card-center">
        <div class="container">
            <h2>รายงานตามวันที่</h2>
            <form action="" method="post">
                <!-- Your form elements -->
                <div class="button-group">
                    <button type="submit" name="view" class="btcolor">View</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Add your script for flatpickr and other custom scripts -->
</body>
</html>
