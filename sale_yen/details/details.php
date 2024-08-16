<?php
session_start();
include '../connect.php';

$range = isset($_GET['range']) ? $_GET['range'] : '';

$valid_ranges = ['ไม่มีข้อมูล', '1-50', '51-100', '101-150', '151-199', '200-10000'];
if (!in_array($range, $valid_ranges)) {
    die("Invalid range specified.");
}

$provinceFilter = isset($_GET['province']) ? $_GET['province'] : '';
$saleFilter = isset($_GET['sale']) ? $_GET['sale'] : '';

$strSQL = "SELECT * FROM view WHERE ";

switch ($range) {
    case 'ไม่มีข้อมูล':
        $strSQL .= "V_Peak_month = 0";
        break;
    case '1-50':
        $strSQL .= "V_Peak_month BETWEEN 1 AND 50";
        break;
    case '51-100':
        $strSQL .= "V_Peak_month BETWEEN 51 AND 100";
        break;
    case '101-150':
        $strSQL .= "V_Peak_month BETWEEN 101 AND 150";
        break;
    case '151-199':
        $strSQL .= "V_Peak_month BETWEEN 151 AND 199";
        break;
    case '200-10000':
        $strSQL .= "V_Peak_month BETWEEN 200 AND 10000";
        break;
    default:
        die("Invalid range specified.");
}

if (!empty($provinceFilter)) {
    $strSQL .= " AND V_Province = ?";
}

if (!empty($saleFilter)) {
    $strSQL .= " AND V_Sale = ?";
}

$stmt = $objConnect->prepare($strSQL);

if (!$stmt) {
    die("Prepare failed: " . $objConnect->error);
}

if (!empty($provinceFilter) && !empty($saleFilter)) {
    $stmt->bind_param("ss", $provinceFilter, $saleFilter);
} elseif (!empty($provinceFilter)) {
    $stmt->bind_param("s", $provinceFilter);
} elseif (!empty($saleFilter)) {
    $stmt->bind_param("s", $saleFilter);
}

$result = $stmt->execute();

if (!$result) {
    die("Query execution failed: " . $stmt->error);
}

$result = $stmt->get_result();

if (!$result) {
    die("Result retrieval failed: " . $stmt->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="../../js/logout.js"></script>
</head>
<body class="bgcolor">
    <nav role="navigation" class="primary-navigation">
        <ul>
            <li><a href="#"><?php echo $_SESSION['name']; ?></a></li>
            <li><a href="../board.php">dashboard</a></li>
            <li><a href="../insert_data-yen.php">เพิ่มข้อมูล</a></li>
            <li><a href="#">รายการ &dtrif;</a>
                <ul class="dropdown">
                    <li><a href="../data_view-sale.php">ดูข้อมูลทั้งหมด</a></li>
                    <!-- <li><a href="list_view.php" target="_blank">รายชื่อหน่วยงาน</a></li> -->
                    <li><a href="../sale_name.php">เลือกดูทีมฝ่ายขาย</a></li>
                </ul>
            </li>
            <li><a onclick="confirmLogout()">ลงชื่ออก</a></li>
        </ul>
    </nav>

    <div class="container content-color">
        <h2>หน่วยงานที่ค่า Peak : <?php echo htmlspecialchars($range); ?></h2>
        <form method="GET" action="">
            <input type="hidden" name="range" value="<?php echo htmlspecialchars($range); ?>">
            <div class="form-group">
                <label for="province">จังหวัด:</label>
                <select name="province" id="province" class="form-control">
                    <option value="">--เลือกจังหวัด--</option>
                    <?php
                    $provinces = $objConnect->query("SELECT DISTINCT V_Province FROM view");
                    while ($row = $provinces->fetch_assoc()): ?>
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
                    <?php
                    $sales = $objConnect->query("SELECT DISTINCT V_Sale FROM view");
                    while ($row = $sales->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['V_Sale']); ?>" <?php if ($saleFilter == $row['V_Sale']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['V_Sale']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">กรอง</button>
        </form>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Peak ต่อเดือน</th>
                    <th>Peak ต่อปี</th>
                    <th>ID</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>ทีมฝ่ายขาย</th>
                    <th>ค่าไฟต่อปี</th>
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
                    <td><?php echo htmlspecialchars($row['V_Sale']); ?></td>
                    <td><?php echo ($row["V_Electric_per_year"] == 0) ? 'N/A' : number_format($row["V_Electric_per_year"], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include '../../buttom.html'; ?>
    <?php include '../../back.html'; ?>
</body>
</html>
