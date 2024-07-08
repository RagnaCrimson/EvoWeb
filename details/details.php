<?php
session_start();
include '../connect.php';

$range = isset($_GET['range']) ? $_GET['range'] : '';

$valid_ranges = ['0', '1-50', '51-100', '101-150', '151-199', '200-10000'];
if (!in_array($range, $valid_ranges)) {
    die("Invalid range specified.");
}

switch ($range) {
    case '0':
        $strSQL = "SELECT * FROM view WHERE V_Peak_month = 0 ORDER BY V_Peak_month DESC";
        break;
    case '1-50':
        $strSQL = "SELECT * FROM view WHERE V_Peak_month BETWEEN 1 AND 50 ORDER BY V_Peak_month DESC";
        break;
    case '51-100':
        $strSQL = "SELECT * FROM view WHERE V_Peak_month BETWEEN 51 AND 100 ORDER BY V_Peak_month DESC";
        break;
    case '101-150':
        $strSQL = "SELECT * FROM view WHERE V_Peak_month BETWEEN 101 AND 150 ORDER BY V_Peak_month DESC";
        break;
    case '151-199':
        $strSQL = "SELECT * FROM view WHERE V_Peak_month BETWEEN 151 AND 199 ORDER BY V_Peak_month DESC";
        break;
    case '200-10000':
        $strSQL = "SELECT * FROM view WHERE V_Peak_month BETWEEN 200 AND 10000 ORDER BY V_Peak_month DESC";
        break;
    default:
        die("Invalid range specified.");
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
            <li><a onclick="confirmLogout()">ลงชื่อออก</a></li>
        </ul>
    </nav>

    <div class="container content-color">
        <h2>หน่วยงานที่ค่า Peak : <?php echo htmlspecialchars($range); ?></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Peak ต่อเดือน</th>
                    <th>Peak ต่อปี</th>
                    <th>ID</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>ตำบล</th>
                    <th>อำเภอ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo ($row["V_Peak_month"] == 0) ? 'N/A' : number_format($row["V_Peak_month"], 2); ?></td>
                    <td><?php echo ($row["V_Peak_year"] == 0) ? 'N/A' : number_format($row["V_Peak_year"], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['V_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Province']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_District']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_SubDistrict']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include '../back.html'; ?>
</body>
</html>
