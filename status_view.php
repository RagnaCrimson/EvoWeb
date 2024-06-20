<?php
include 'connect.php';

mysqli_query($objConnect, "SET NAMES utf8");

$search = isset($_GET['search']) ? $objConnect->real_escape_string($_GET['search']) : '';

if ($search) {
    $strSQL_datastore_db = "
        SELECT view.*, task.T_Status, files.filename 
        FROM view 
        LEFT JOIN task ON view.V_ID = task.T_ID
        LEFT JOIN files ON view.V_ID = files.id
        WHERE view.V_Name LIKE CONCAT('%', ?, '%') OR view.V_Province LIKE CONCAT('%', ?, '%') OR task.T_Status LIKE CONCAT('%', ?, '%')";
    $stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);
    $stmt_datastore_db->bind_param("sss", $search, $search, $search);
} else {
    $strSQL_datastore_db = "
        SELECT view.*, task.T_Status, files.filename 
        FROM view 
        LEFT JOIN task ON view.V_ID = task.T_ID
        LEFT JOIN files ON view.V_ID = files.id";
    $stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);
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
$_SESSION['total_electric_per_year'] = $total_electric_per_year;
// ==========

// SUM per month
$strSQL_sum_month = "SELECT SUM(V_Electric_per_month) AS total_electric_per_month FROM view";
$result_sum_month = $objConnect->query($strSQL_sum_month);
if (!$result_sum_month) {
    die("Query failed: " . $objConnect->error);
}
$row_sum_month = $result_sum_month->fetch_assoc();
$total_electric_per_month = $row_sum_month['total_electric_per_month'];
$_SESSION['total_electric_per_month'] = $total_electric_per_month;
// ==========

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

                <form action="upload.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select file</label>
                        <input type="file" class="form-control" name="file" id="file" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload file</button>
                </form><br><br>

                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>ชื่อผู้บริหาร</th>
                    <th>การใช้ไฟ/ปี</th>
                    <th>การใช้ไฟ/เดือน</th>
                    <th>File PDF</th>
                    <th>สถานะ</th>
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
                            <td><?php echo htmlspecialchars($row["V_ExecName"]); ?></td>
                            <td><?php echo number_format($row["V_Electric_per_year"], 2); ?></td>
                            <td><?php echo number_format($row["V_Electric_per_month"], 2); ?></td>
                            <td>
                                <?php if (!empty($row["filename"])): ?>
                                    <a href="uploads/<?php echo htmlspecialchars($row["filename"]); ?>" target="_blank"><?php echo htmlspecialchars($row["filename"]); ?></a>
                                <?php else: ?>
                                    No file
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row["T_Status"]); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='8'>ไม่มีข้อมูลรายการ</td></tr>";
                }
                ?>
                <tr>
                    <td colspan="4"><strong>รวมยอดค่าใช้จ่ายทั้งหมด</strong></td>
                        <td><strong><?php echo number_format($total_electric_per_year); ?></strong></td>
                        <td><strong><?php echo number_format($total_electric_per_month); ?></strong></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="7"><strong>Total Rows</strong></td>
                    <td ><strong><?php echo $total_rows; ?></strong></td>
                    <?php $_SESSION['total_rows'] = $total_rows; ?>
                </tr>
            </table>
        </div>
    </div>
    <?php include 'back.html'; ?>
</body>
</html>
