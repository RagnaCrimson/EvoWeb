<?php
require '../vendor/autoload.php'; // Load Composer dependencies

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "datastore_db";

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch specific data
$sql = "SELECT V_ID, V_Name, V_Province, V_Sale FROM view";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set column headers
$headers = ['V_ID', 'V_Name', 'V_Province', 'V_Sale'];
$sheet->fromArray($headers, NULL, 'A1');

// Write data to spreadsheet
$sheet->fromArray($data, NULL, 'A2');

// Write file to disk
$writer = new Xlsx($spreadsheet);
$filename = 'Excel/exported_data.xlsx';
$writer->save($filename);

// Provide download link
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
readfile($filename);
exit;
