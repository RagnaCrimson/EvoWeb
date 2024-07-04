<?php
require_once('../vendor/autoload.php'); 

class MYPDF extends TCPDF {
    public $admin_name = '';

    public function setAdminName($admin_name) {
        $this->admin_name = $admin_name;
    }

    public function Header() {
        $this->SetY(10);
        $this->SetX(0);
        $this->SetFont('prompt', 'B', 14);
        $this->Cell(0, 0, 'บริษัท อีโวลูชั่น เอ็นเตอร์จี เท็ค จำกัด', 0, 1, 'C');
        $this->Ln(5);

        $this->SetX(0);
        $this->SetFont('prompt', '', 10);
        $this->Cell(0, 0, 'ผู้ทำรายงาน: ' . $_SESSION['name'], 0, 1, 'C');
        $this->Ln(5);

        $this->SetX(0);
        $this->Cell(0, 0, 'รายงานสถานะการจัดส่งเอกสาร', 0, 1, 'C');
        $this->Ln(17);

        $this->SetFont('prompt', 'B', 12);
        $this->SetY(42);
        $this->Cell(25, 8, 'ลำดับ', 1, 0, 'C');
        $this->Cell(30, 8, 'วันที่', 1, 0, 'C');
        $this->Cell(90, 8, 'ชื่อหน่วยงาน', 1, 0, 'C');
        $this->Cell(45, 8, 'สถานะ', 1, 0, 'C');
        $this->Ln();
    }

    public function Footer() {
        $this->SetY(-40);
        $this->SetFont('prompt', '', 10);

        $this->SetX(60);
        $this->Cell(0, 10, 'ผู้จัดทำ........................................................', 0, 1, 'L');
        $this->Ln(5);
        $this->SetY(-30);
        $this->SetX(60);
        $this->Cell(0, 10, 'วันที่..........................................................', 0, 1, 'L');

        $this->SetY(-40);
        $this->SetX(120);
        $this->Cell(0, 10, 'ผู้จัดการ.....................................................', 0, 1, 'L');
        $this->Ln(10);
        $this->SetY(-30);
        $this->SetX(120);
        $this->Cell(0, 10, 'วันที่..........................................................', 0, 1, 'L');

        $this->SetY(-40);
        $this->SetX(180);
        $this->Cell(0, 10, 'ผู้ตรวจสอบ................................................', 0, 1, 'L');
        $this->Ln(15);
        $this->SetY(-30);
        $this->SetX(180);
        $this->Cell(0, 10, 'วันที่..........................................................', 0, 1, 'L');
    }
}
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$admin_name = '';
$admin_query = "SELECT name FROM admin LIMIT 1";
$admin_result = $conn->query($admin_query);
if ($admin_result && $admin_result->num_rows > 0) {
    $admin_row = $admin_result->fetch_assoc();
    $admin_name = $admin_row['name'];
}

$pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->setAdminName($admin_name);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Sales Report');
$pdf->SetSubject('Sales Report');
$pdf->SetKeywords('TCPDF, PDF, report, sales');

$pdf->AddFont('prompt', '', dirname(__FILE__).'/fonts/Prompt-Regular.ttf', true);
$pdf->SetFont('prompt', '', 10);

$pdf->SetMargins(50, 8, 10);
$pdf->SetFooterMargin(8);
$pdf->SetAutoPageBreak(TRUE, 20);

$pdf->AddPage();

$pdf->SetY(50);

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
    $pdf->SetFont('prompt', '', 10);
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        if ($pdf->GetY() > 160) {  // ปรับค่าตางรางตรงนี้
            $pdf->AddPage();
            $pdf->SetY(50);
        }
        $pdf->Cell(25, 8, $counter++, 1, 0, 'C');
        $pdf->Cell(30, 8, date('d-m-Y', strtotime($row['V_Date'])), 1, 0, 'C');
        $pdf->Cell(90, 8, $row['V_Name'], 1, 0, 'L');
        $pdf->Cell(45, 8, $row['T_Status'], 1, 0, 'C');
        $pdf->Ln();
    }

    $pdf->Output('sales_report.pdf', 'I');
} else {
    echo "No data available for the selected criteria.";
}

$conn->close();
?>