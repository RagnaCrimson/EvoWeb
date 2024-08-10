<?php
include 'connect.php';

$strSQL_total_count = "SELECT COUNT(*) AS total_count FROM view";
$result_total_count = $objConnect->query($strSQL_total_count);

if (!$result_total_count) {
    die("Query failed: " . $objConnect->error);
}

$row_total_count = $result_total_count->fetch_assoc();
$total_count = $row_total_count['total_count'];

echo json_encode(['total_count' => $total_count], JSON_UNESCAPED_UNICODE);
?>
