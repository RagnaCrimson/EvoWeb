<?php
session_start();
include '../connect.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';

if (empty($status)) {
    die("Invalid status specified.");
}

$provinceFilter = isset($_GET['province']) ? $_GET['province'] : '';
$saleFilter = isset($_GET['sale']) ? $_GET['sale'] : '';

$strSQL_datastore_db = "
    SELECT view.*, task.T_Status, files.filename 
    FROM view 
    LEFT JOIN task ON view.V_ID = task.T_ID
    LEFT JOIN files ON view.V_ID = files.id
    WHERE task.T_Status = ?";

// Append additional conditions based on filters
if (!empty($provinceFilter)) {
    $strSQL_datastore_db .= " AND view.V_Province = ?";
}

if (!empty($saleFilter)) {
    $strSQL_datastore_db .= " AND view.V_Sale = ?";
}

$stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);

if (!$stmt_datastore_db) {
    die("Prepare failed: " . $objConnect->error);
}

// Bind parameters
if (!empty($provinceFilter) && !empty($saleFilter)) {
    $stmt_datastore_db->bind_param("sss", $status, $provinceFilter, $saleFilter);
} elseif (!empty($provinceFilter)) {
    $stmt_datastore_db->bind_param("ss", $status, $provinceFilter);
} elseif (!empty($saleFilter)) {
    $stmt_datastore_db->bind_param("ss", $status, $saleFilter);
} else {
    $stmt_datastore_db->bind_param("s", $status);
}

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
            <li><a href="#">Welcome Guest!</a></li>
            <li><a href="guest_view.php">Dashboard</a></li>
        </ul>
    </nav>

    <div class="container content-color">
        <h2>สถานะงานที่: <?php echo htmlspecialchars($status); ?></h2>
        <form method="GET" action="">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
            <div class="form-group">
                <label for="province">จังหวัด:</label>
                <select name="province" id="province" class="form-control">
                    <option value="">--เลือกจังหวัด--</option>
                    <!-- Populate options dynamically from database -->
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
                    <!-- Populate options dynamically from database -->
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
                    <th>สถานะ</th>
                    <th>ID</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>ทีมฝ่ายขาย</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['T_Status']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Province']); ?></td>
                    <td><?php echo htmlspecialchars($row['V_Sale']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include '../back.html'; ?>
</body>
</html>
