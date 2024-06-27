<?php
session_start();

include 'connect.php';

$range = isset($_GET['range']) ? $_GET['range'] : '';

$valid_ranges = ['0-100', '100-199', '200-300', '300+'];
if (!in_array($range, $valid_ranges)) {
    die("Invalid range specified.");
}

if ($range === '0-100') {
    $strSQL = "SELECT * FROM view WHERE V_Peak_month BETWEEN 0 AND 100";
} elseif ($range === '100-199') {
    $strSQL = "SELECT * FROM view WHERE V_Peak_month BETWEEN 100 AND 199";
} elseif ($range === '200-300') {
    $strSQL = "SELECT * FROM view WHERE V_Peak_month BETWEEN 200 AND 300";
} elseif ($range === '300+') {
    $strSQL = "SELECT * FROM view WHERE V_Peak_month > 300";
}

$result = $objConnect->query($strSQL);

if (!$result) {
    die("Query failed: " . $objConnect->error);
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
            <li><a href="/evo/dashboard.php">Dashboard</a>
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
        <h2>Details for Range: <?php echo htmlspecialchars($range); ?></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Province</th>
                    <th>District</th>
                    <th>SubDistrict</th>
                    <th>Peak Month</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($row = $result->fetch_assoc()): 
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['V_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Province']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_District']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_SubDistrict']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Peak_month']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
