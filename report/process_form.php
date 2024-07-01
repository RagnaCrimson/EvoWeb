<?php
require_once('../vendor/autoload.php'); 

class MYPDF extends TCPDF {
    public $admin_name = '';

    public function setAdminName($admin_name) {
        $this->admin_name = $admin_name;
    }

    public function Header() {
        $this->SetFont('prompt', 'B', 16);
        $this->Cell(0, 0, 'บริษัท อีโวลูชั่น เอ็นเจอจี เท็ค จำกัด', 0, 1, 'C');
        $this->Ln(5);
        $this->SetFont('prompt', '', 12);
        $this->Cell(0, 0, 'ผู้ทำรายงาน ', 0, 1, 'C');
        $this->Ln(5);
        $this->Cell(0, 0, 'รายงานสถานะการจัดส่งเอกสาร', 0, 1, 'C');
        $this->Ln(10);

        $this->SetFont('prompt', 'B', 14);
        $this->Cell(30, 10, 'ลำดับ', 1, 0, 'C');
        // $this->Cell(30, 10, 'เลขที่ใบเสร็จ', 1, 0, 'C');
        $this->Cell(30, 10, 'วันที่', 1, 0, 'C');
        // $this->Cell(30, 10, 'หมายเลขพ้อย', 1, 0, 'C');
        $this->Cell(50, 10, 'ชื่อหน่วยงาน', 1, 0, 'C');
        $this->Cell(30, 10, 'สถานะ', 1, 0, 'C');
        // $this->Cell(50, 10, 'รายการ', 1, 0, 'C');
        // $this->Cell(30, 10, 'ประเภท', 1, 0, 'C');
        // $this->Cell(30, 10, 'จำนวนเงิน', 1, 0, 'C');
        // $this->Cell(20, 10, 'รวม', 1, 0, 'C');
        $this->Ln();
    }

    public function Footer() {
        $this->SetY(-50);
        $this->SetFont('prompt', '', 12);

        $this->Cell(0, 10, 'ผู้จัดทำ........................................................', 0, 1, 'L');
        $this->Ln(5);
        $this->Cell(0, 10, 'วันที่..........................................................', 0, 1, 'L');

        $this->SetY(-35);
        $this->Cell(0, 10, 'ผู้จัดการ.....................................................', 0, 1, 'L');
        $this->Ln(5);
        $this->Cell(0, 10, 'วันที่..........................................................', 0, 1, 'L');

        $this->SetY(-20);
        $this->Cell(0, 10, 'ผู้ตรวจสอบ................................................', 0, 1, 'L');
        $this->Ln(5);
        $this->Cell(0, 10, 'วันที่..........................................................', 0, 1, 'L');
    }
}

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
$pdf->SetFont('prompt', '', 12);

$pdf->SetMargins(10, 8, 10);
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
    $pdf->SetFont('prompt', '', 12);
    while ($row = $result->fetch_assoc()) {
        if ($pdf->GetY() > 180) {
            $pdf->AddPage();
            $pdf->SetY(50);
        }
        $pdf->Cell(30, 10, $row['V_Sale'], 1, 0, 'L');
        // $pdf->Cell(30, 10, $row['V_ReceiptNumber'], 1, 0, 'L');
        $pdf->Cell(30, 10, date('d-m-Y', strtotime($row['V_Date'])), 1, 0, 'L');
        // $pdf->Cell(30, 10, $row['V_PointNumber'], 1, 0, 'L');
        $pdf->Cell(50, 10, $row['V_Name'], 1, 0, 'L');
        $pdf->Cell(30, 10, $row['T_Status'], 1, 0, 'L');
        // $pdf->Cell(50, 10, $row['V_Item'], 1, 0, 'L');
        // $pdf->Cell(30, 10, $row['V_Type'], 1, 0, 'L');
        // $pdf->Cell(30, 10, $row['V_Amount'], 1, 0, 'L');
        // $pdf->Cell(20, 10, $row['V_Total'], 1, 0, 'L');
        $pdf->Ln();
    }

    $pdf->Output('sales_report.pdf', 'I');
} else {
    echo "No data available for the selected criteria.";
}

$conn->close();
?>
