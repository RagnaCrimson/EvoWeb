<?php
include 'connect.php';

mysqli_query($objConnect, "SET NAMES utf8");

$search = isset($_GET['search']) ? $objConnect->real_escape_string($_GET['search']) : '';

$saleFilter = isset($_GET['sale']) ? $objConnect->real_escape_string($_GET['sale']) : '';
$statusFilter = isset($_GET['status']) ? $objConnect->real_escape_string($_GET['status']) : '';
$provinceFilter = isset($_GET['province']) ? $objConnect->real_escape_string($_GET['province']) : '';

$rows_per_page = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $rows_per_page;

if ($search) {
    $strSQL_datastore_db = "
        SELECT view.*, task.T_Status, files.filename 
        FROM view 
        LEFT JOIN task ON view.V_ID = task.T_ID
        LEFT JOIN files ON view.V_ID = files.id
        WHERE (view.V_Name LIKE CONCAT('%', ?, '%') 
        OR view.V_Province LIKE CONCAT('%', ?, '%') 
        OR task.T_Status LIKE CONCAT('%', ?, '%'))
        AND (view.V_Sale = ? OR ? = '')
        AND (task.T_Status = ? OR ? = '')
        AND (view.V_Province = ? OR ? = '')";
    $stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);
    $stmt_datastore_db->bind_param("ssssssss", $search, $search, $search, $saleFilter, $saleFilter, $statusFilter, $statusFilter, $provinceFilter, $provinceFilter);
} else {
    $strSQL_datastore_db = "
        SELECT view.*, task.T_Status, files.filename 
        FROM view 
        LEFT JOIN task ON view.V_ID = task.T_ID
        LEFT JOIN files ON view.V_ID = files.id
        WHERE (view.V_Sale = ? OR ? = '')
        AND (task.T_Status = ? OR ? = '')
        AND (view.V_Province = ? OR ? = '')";
    $stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);
    $stmt_datastore_db->bind_param("ssssss", $saleFilter, $saleFilter, $statusFilter, $statusFilter, $provinceFilter, $provinceFilter);
}

$stmt_datastore_db->execute();
$resultdatastore_db = $stmt_datastore_db->get_result();

if (!$resultdatastore_db) {
    die("Query failed: " . $objConnect->error);
}

// SUM per year
$strSQL_sum = "SELECT SUM(V_Electric_per_year) AS total_electric_per_year FROM view";
$result_sum = $objConnect->query($strSQL_sum);

if (!$result_sum) {
    die("Query failed: " . $objConnect->error);
}

$row_sum = $result_sum->fetch_assoc();
$total_electric_per_year = $row_sum['total_electric_per_year'];

// SUM per month
$strSQL_sum_month = "SELECT SUM(V_Electric_per_month) AS total_electric_per_month FROM view";
$result_sum_month = $objConnect->query($strSQL_sum_month);
if (!$result_sum_month) {
    die("Query failed: " . $objConnect->error);
}
$row_sum_month = $result_sum_month->fetch_assoc();
$total_electric_per_month = $row_sum_month['total_electric_per_month'];

// SUM peak year
$strSQL_sum_peak_year = "SELECT SUM(V_Peak_year) AS total_peak_per_year FROM view";
$result_sum_peak_year = $objConnect->query($strSQL_sum_peak_year);
if (!$result_sum_peak_year) {
    die("Query failed: " . $objConnect->error);
}
$row_sum_peak_year = $result_sum_peak_year->fetch_assoc();
$total_peak_per_year = $row_sum_peak_year['total_peak_per_year'];

// SUM peak month
$strSQL_sum_peak_month = "SELECT SUM(V_Peak_month) AS total_peak_per_month FROM view";
$result_sum_peak_month = $objConnect->query($strSQL_sum_peak_month);
if (!$result_sum_peak_month) {
    die("Query failed: " . $objConnect->error);
}
$row_sum_peak_month = $result_sum_peak_month->fetch_assoc();
$total_peak_per_month = $row_sum_peak_month['total_peak_per_month'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bgcolor">
    <?php include 'header.php'; ?>

    <div class="container">
        <div id="View" class="tabcontent">
            <div style="margin-bottom: 50px;"><h1>รายการติดตามสถานะ</h1></div>
            <table id="data" class="table table-striped">
                <nav class="navbar navbar-light bg-light" style="text-align: center;">
                    <form class="form-inline" method="GET" action="">
                        <input type="text" style="text-align: center;" name="search" placeholder="พิมพ์เพื่อค้นหา" class="form-control">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </form>
                </nav>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>ค่าไฟ/ปี</th>
                    <th>ค่าไฟ/เดือน</th>
                    <th>การใช้ไฟ/ปี</th>
                    <th>การใช้ไฟ/เดือน</th>
                    <th>File PDF</th>
                    <th>ทีมฝ่ายขาย</th>
                    <th>สถานะ</th>
                    <th>แก้ไข</th>
                </tr>
                <?php  
                $total_rows = 0; 
                if ($resultdatastore_db->num_rows > 0) {
                    $sequence = 1;
                    while ($row = $resultdatastore_db->fetch_assoc()) {
                        $total_rows++; 
                        ?>
                        <tr>     
                            <td><?php echo $sequence++; ?></td>
                            <td><?php echo htmlspecialchars($row["V_Name"]); ?></td>
                            <td><?php echo htmlspecialchars($row["V_Province"]); ?></td>
                            <td><?php echo ($row["V_Electric_per_year"] == 0) ? 'N/A' : number_format($row["V_Electric_per_year"], 2); ?></td>
                            <td><?php echo ($row["V_Electric_per_month"] == 0) ? 'N/A' : number_format($row["V_Electric_per_month"], 2); ?></td>
                            <td><?php echo ($row["V_Peak_year"] == 0) ? 'N/A' : number_format($row["V_Peak_year"], 2); ?></td>
                            <td><?php echo ($row["V_Peak_month"] == 0) ? 'N/A' : number_format($row["V_Peak_month"], 2); ?></td>
                            <td>
                                <?php if (!empty($row["filename"])): ?>
                                    <a href="uploads/<?php echo htmlspecialchars($row["filename"]); ?>" target="_blank"><?php echo htmlspecialchars($row["filename"]); ?></a>
                                <?php else: ?>
                                    No file
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row["V_Sale"]); ?></td>
                            <td><?php echo htmlspecialchars($row["T_Status"]); ?></td>
                            <td><a href="edit_status.php?view_id=<?php echo urlencode($row['V_ID']); ?>" class="btn btn-info btn-lg">Update</a></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='11'>ไม่มีข้อมูลรายการ</td></tr>";
                }
                ?>
                <tr>
                    <td colspan="3"><strong>รวมยอดค่าใช้จ่ายทั้งหมด</strong></td>
                    <td><strong><?php echo number_format($total_electric_per_year); ?></strong></td>
                    <td><strong><?php echo number_format($total_electric_per_month); ?></strong></td>
                    <td><strong><?php echo number_format($total_peak_per_year); ?></strong></td>
                    <td><strong><?php echo number_format($total_peak_per_month); ?></strong></td>
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <td colspan="7"><strong>Total Rows</strong></td>
                    <td><strong><?php echo $total_rows; ?></strong></td>
                </tr>
            </table>
        </div>
    </div>
    <?php include 'back.html'; ?>
</body>
</html>
