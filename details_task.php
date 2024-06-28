<?php
session_start();
include 'connect.php';
mysqli_query($objConnect, "SET NAMES utf8");

$status = isset($_GET['status']) ? $_GET['status'] : '';

if (empty($status)) {
    die("Invalid status specified.");
}

$strSQL_datastore_db = "
    SELECT view.*, task.T_Status, files.filename 
    FROM view 
    LEFT JOIN task ON view.V_ID = task.T_ID
    LEFT JOIN files ON view.V_ID = files.id
    WHERE task.T_Status = ?";
$stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);

if (!$stmt_datastore_db) {
    die("Prepare failed: " . $objConnect->error);
}

$stmt_datastore_db->bind_param("s", $status);

$result = $stmt_datastore_db->execute();

if (!$result) {
    die("Query execution failed: " . $stmt_datastore_db->error);
}

$result = $stmt_datastore_db->get_result();

if (!$result) {
    die("Result retrieval failed: " . $stmt_datastore_db->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <link rel="stylesheet" href="/evo/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="/evo/js/logout.js"></script>
</head>
<body class="bgcolor">
    <nav role="navigation" class="primary-navigation">
        <ul>
            <li><a href="#"><?php echo $_SESSION['name']; ?></a></li>
            <li><a href="/evo/dashboard.php">Dashboard</a></li>
            <li><a href="#">รายการ &dtrif;</a>
                <ul class="dropdown">
                    <li><a href="/evo/data_view.php">ดูข้อมูลทั้งหมด</a></li>
                    <li><a href="/evo/index.php">เพิ่มข้อมูล</a></li>
                    <li><a href="/evo/status_view.php">ดูสถานะ</a></li>
                </ul>
            </li>
            <li><a href="#">Report &dtrif;</a>
                <ul class="dropdown">
                    <li><a href="/evo/report/reportday.php">เลือกวันที่</a></li>
                    <li><a href="#">ตามสถานะ</a></li>
                    <li><a href="#">บริษัทผู้รับเหมา</a></li>
                </ul>
            </li>
            <li><a onclick="confirmLogout()">ลงชื่ออก</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Details for Status: <?php echo htmlspecialchars($status); ?></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>สถานะ</th>
                    <th>ID</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>หมายเหตุ</th>
                    <th>Peak ต่อปี</th>
                    <th>Peak ต่อเดือน</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['T_Status']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Province']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_comment']); ?></td>
                    <td><?php echo ($row["V_Peak_year"] == 0) ? 'N/A' : number_format($row["V_Peak_year"], 2); ?></td>
                    <td><?php echo ($row["V_Peak_month"] == 0) ? 'N/A' : number_format($row["V_Peak_month"], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
