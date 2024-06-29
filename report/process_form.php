<?php
require_once('../vendor/autoload.php'); // Adjust path as per your autoload location for TCPDF

// Create new PDF document
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Sales Report');
$pdf->SetSubject('Sales Report');
$pdf->SetKeywords('TCPDF, PDF, report, sales');

// Set default font
$pdf->SetFont('prompt', '', 12); // Use 'prompt' as the font family name for Prompt-Regular.ttf

// Add Thai fonts (adjust paths as per your setup)
$pdf->AddFont('prompt', '', dirname(__FILE__).'/fonts/Prompt-Regular.ttf', true);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for form inputs
$sales_team = isset($_POST['sales_team']) ? $conn->real_escape_string($_POST['sales_team']) : '';
$task_status = isset($_POST['task_status']) ? $conn->real_escape_string($_POST['task_status']) : '';
$start_date = isset($_POST['start_date']) ? $conn->real_escape_string($_POST['start_date']) : '';
$end_date = isset($_POST['end_date']) ? $conn->real_escape_string($_POST['end_date']) : '';

// Build SQL query
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

// Execute query
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Check if data exists
if ($result->num_rows > 0) {
    // Output PDF content
    $pdf->SetFont('prompt', 'B', 14); // Example: Use bold Thai font

    // Output table headers
    $pdf->Cell(30, 10, 'Sale', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Date', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Name', 1, 0, 'C');
    // Add more cells as needed for your table headers

    $pdf->Ln();

    // Output table data
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(30, 10, $row['V_Sale'], 1, 0, 'L');
        $pdf->Cell(30, 10, date('d-m-Y', strtotime($row['V_Date'])), 1, 0, 'L');
        $pdf->Cell(40, 10, $row['V_Name'], 1, 0, 'L');
        // Add more cells as needed for your table data
        $pdf->Ln();
    }

    // Close PDF document
    $pdf->Output('sales_report.pdf', 'I');
} else {
    // No data found
    echo "No data available for the selected criteria.";
}

// Close MySQL connection
$conn->close();
?>
