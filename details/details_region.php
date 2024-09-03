<?php
session_start();
include '../connect.php';

$region = isset($_GET['region']) ? $_GET['region'] : '';

if (empty($region)) {
    die("Invalid region specified.");
}

$strSQL_datastore_db = "
    SELECT view.*, task.T_Status, files.filename 
    FROM view 
    LEFT JOIN task ON view.V_ID = task.T_ID
    LEFT JOIN files ON view.V_ID = files.id
    WHERE view.V_Region = ?
    ORDER BY view.V_Province";

$stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);

if (!$stmt_datastore_db) {
    die("Prepare failed: " . $objConnect->error);
}

// Bind the parameter
$stmt_datastore_db->bind_param("s", $region);

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
    <title>Details by Region</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="../js/logout.js"></script>
</head>
<body class="bgcolor">
    <nav role="navigation" class="primary-navigation">
        <ul>
            <li><a href="#"><?php echo htmlspecialchars($_SESSION['name']); ?></a></li>
            <li><a href="../dashboard.php">Dashboard</a></li>
            <li><a href="#">รายการ &dtrif;</a>
                <ul class="dropdown">
                    <li><a href="../data_view.php">ดูข้อมูลทั้งหมด</a></li>
                    <li><a href="../index.php">เพิ่มข้อมูล</a></li>
                    <li><a href="../status_view.php">ดูสถานะ</a></li>
                </ul>
            </li>
            <li><a href="#">Report &dtrif;</a>
                <ul class="dropdown">
                    <li><a href="../report/allname.php" target="_blank">รายชื่อทั้งหมด</a></li>
                    <li><a href="../report/sale_name.php">เลือกทีมฝ่ายขาย</a></li>
                </ul>
            </li>
            <li><a onclick="confirmLogout()">ลงชื่อออก</a></li>
        </ul>
    </nav>

    <div class="container content-color">
        <h2>สถานะงานในภูมิภาค: <?php echo htmlspecialchars($region); ?></h2>
        <form method="GET" action="">
            <input type="hidden" name="region" value="<?php echo htmlspecialchars($region); ?>">
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
                    <th>ID</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>อำเภอ</th>
                    <th>ตำบล</th>
                    <th>ค่าไฟ</th>
                    <th>ทีมฝ่ายขาย</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['V_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Province']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_District']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_SubDistrict']); ?></td>
                    <td><?php echo ($row["V_Electric_per_month"] == 0) ? 'N/A' : number_format($row["V_Electric_per_month"], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Sale']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include '../buttom.html'; ?>
    <?php include '../back.html'; ?>
</body>
</html>
