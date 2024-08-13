<?php
session_start();

include '../connect.php'; 

$range = isset($_GET['range']) ? $_GET['range'] : '';
$provinceFilter = isset($_GET['province']) ? $_GET['province'] : '';
$saleFilter = isset($_GET['sale']) ? $_GET['sale'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

$valid_ranges = [
    'ไม่มีข้อมูล',
    '1-10000',
    '10001-30000',
    '30001-50000',
    '50001-100000',
    '100001-200000',
    '200001-10000000',
    'ไม่มีศักยภาพ',
    'มีศักยภาพ' 
];

if (!in_array($range, $valid_ranges)) {
    die("Invalid range specified.");
}

switch ($range) {
    case 'ไม่มีข้อมูล':
        $monthCondition = "V_Electric_per_month = 0";
        break;
    case '1-10000':
        $monthCondition = "V_Electric_per_month BETWEEN 1 AND 10000";
        break;
    case '10001-30000':
        $monthCondition = "V_Electric_per_month BETWEEN 10001 AND 30000";
        break;
    case '30001-50000':
        $monthCondition = "V_Electric_per_month BETWEEN 30001 AND 50000";
        break;
    case '50001-100000':
        $monthCondition = "V_Electric_per_month BETWEEN 50001 AND 100000";
        break;
    case '100001-200000':
        $monthCondition = "V_Electric_per_month BETWEEN 100001 AND 200000";
        break;
    case '200001-10000000':
        $monthCondition = "V_Electric_per_month BETWEEN 200001 AND 10000000";
        break;
    case 'ไม่มีศักยภาพ':  // Ineffective Range
        $monthCondition = "V_Electric_per_month BETWEEN 0 AND 10000";
        break;
    case 'มีศักยภาพ':  // Effective Range
        $monthCondition = "V_Electric_per_month BETWEEN 10001 AND 1000000";
        break;
    default:
        die("Invalid range specified.");
}

$provinceCondition = $provinceFilter ? "AND V_Province = '$provinceFilter'" : '';
$saleCondition = $saleFilter ? "AND V_Sale = '$saleFilter'" : '';
$statusCondition = $statusFilter ? "AND T_Status = '$statusFilter'" : '';

$monthSQL = "
    SELECT v.*, t.T_Status 
    FROM view v 
    JOIN task t ON v.V_ID = t.T_ID 
    WHERE $monthCondition $provinceCondition $saleCondition $statusCondition 
    ORDER BY V_Electric_per_month DESC";

$monthResult = $objConnect->query($monthSQL);

if (!$monthResult) {
    die("Query failed: " . $objConnect->error);
}

$provinces = $objConnect->query("SELECT DISTINCT V_Province FROM view");
$sales = $objConnect->query("SELECT DISTINCT V_Sale FROM view");
$statuses = $objConnect->query("SELECT DISTINCT T_Status FROM task");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details Electric</title>
    <link rel="stylesheet" href="/evo/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="/evo/js/logout.js"></script>
</head>
<body class="bgcolor">
<nav role="navigation" class="primary-navigation">
        <ul>
            <li><a href="#"><?php echo $_SESSION['name']; ?></a></li>
            <li><a href="../dashboard.php">Dashboard</a>
            <li><a href="#">รายการ &dtrif;</a>
                <ul class="dropdown">
                    <li><a href="../data_view.php">ดูข้อมูลทั้งหมด</a></li>
                    <li><a href="../index.php">เพิ่มข้อมูล</a></li>
                    <li><a href="../status_view.php">ดูสถานะ</a></li>
                </ul>
            </li>
            <li><a href="#">Report &dtrif;</a>
                <ul class="dropdown">
                    <!--<li><a href="reportday.php">เลือกวันที่</a></li>-->
                    <li><a href="../report/allname.php" target="_blank">รายชื่อทั้งหมด</a></li>
                    <li><a href="../report/sale_name.php">เลือกทีมฝ่ายขาย</a></li>
                </ul>
            </li>
            <li><a onclick="confirmLogout()">ลงชื่ออก</a></li>
        </ul>
    </nav>

    <div class="container content-color">
        <h2>หน่วยงานที่ค่าใช้ไฟฟ้าต่อเดือน: <?php echo htmlspecialchars($range); ?></h2>
        <form method="GET" action="">
            <input type="hidden" name="range" value="<?php echo htmlspecialchars($range); ?>">
            <div class="form-group">
                <label for="province">จังหวัด:</label>
                <select name="province" id="province" class="form-control">
                    <option value="">--เลือกจังหวัด--</option>
                    <?php while ($row = $provinces->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['V_Province']); ?>" <?php if ($provinceFilter == $row['V_Province']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['V_Province']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sale">ทีมฝ่ายขาย:</label>
                <select name="sale" id="sale" class="form-control">
                    <option value="">--เลือกทีมฝ่ายขาย--</option>
                    <?php while ($row = $sales->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['V_Sale']); ?>" <?php if ($saleFilter == $row['V_Sale']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['V_Sale']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status">สถานะ:</label>
                <select name="status" id="status" class="form-control">
                    <option value="">--เลือกสถานะ--</option>
                    <?php while ($row = $statuses->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['T_Status']); ?>" <?php if ($statusFilter == $row['T_Status']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['T_Status']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">กรอง</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ค่าใช้ไฟฟ้าต่อเดือน</th>
                    <th>ID</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>ทีมฝ่ายขาย</th>
                    <th>ค่า PEAK/เดือน</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php $rowNumber = 1; ?>
                <?php while ($row = $monthResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $rowNumber++; ?></td>
                    <td><?php echo ($row["V_Electric_per_month"] == 0) ? 'N/A' : number_format($row["V_Electric_per_month"], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['V_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Province']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Sale']); ?></td>
                    <td><?php echo ($row["V_Peak_month"] == 0) ? 'N/A' : number_format($row["V_Peak_month"], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['T_Status']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include '../back.html'; ?>
</body>
</html>
