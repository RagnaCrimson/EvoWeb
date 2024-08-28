<?php
include 'connect.php';

mysqli_query($objConnect, "SET NAMES utf8");

$saleFilter = isset($_GET['sale']) ? $objConnect->real_escape_string($_GET['sale']) : '';
$provinceFilter = isset($_GET['province']) ? $objConnect->real_escape_string($_GET['province']) : '';

// Fetch distinct provinces
$sql_provinces = "SELECT DISTINCT V_Province FROM view";
$result_provinces = $objConnect->query($sql_provinces);
$provinces = [];
while ($row = $result_provinces->fetch_assoc()) {
    $provinces[] = $row['V_Province'];
}

// Fetch distinct sales
$sql_sales = "SELECT DISTINCT V_Sale FROM view";
$result_sales = $objConnect->query($sql_sales);
$sales = [];
while ($row = $result_sales->fetch_assoc()) {
    $sales[] = $row['V_Sale'];
}

// Fetch distinct statuses
$sql_status = "SELECT DISTINCT T_Status FROM task";
$result_status = $objConnect->query($sql_status);
$statuses = [];
while ($row = $result_status->fetch_assoc()) {
    $statuses[] = $row['T_Status'];
}


// Prepare the SQL query based on filters
$strSQL_datastore_db = "
    SELECT view.*, task.T_Status, files.filename 
    FROM view 
    LEFT JOIN task ON view.V_ID = task.T_ID
    LEFT JOIN files ON view.V_ID = files.id
    WHERE (view.V_Sale = ? OR ? = '')
    AND (view.V_Province = ? OR ? = '')";

$stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);
if ($stmt_datastore_db === false) {
    die("Prepare failed: " . $objConnect->error);
}
$stmt_datastore_db->bind_param("ssss", $saleFilter, $saleFilter, $provinceFilter, $provinceFilter);

if (!$stmt_datastore_db->execute()) {
    die("Execute failed: " . $stmt_datastore_db->error);
}

$resultdatastore_db = $stmt_datastore_db->get_result();

if ($resultdatastore_db === false) {
    die("Getting result set failed: " . $stmt_datastore_db->error);
}

$total_rows = $resultdatastore_db->num_rows;

if (isset($_GET['act']) && $_GET['act'] == 'excel') {
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=export.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Open output stream
    $output = fopen('php://output', 'w');

    fprintf($output, "\xEF\xBB\xBF");

    // Capture filter values
    $saleFilter = isset($_GET['sale']) ? $objConnect->real_escape_string($_GET['sale']) : '';
    $provinceFilter = isset($_GET['province']) ? $objConnect->real_escape_string($_GET['province']) : '';

    // Prepare the SQL query based on filters
    $sql = "
        SELECT V_ID, V_Name, V_Province, V_District, V_SubDistrict, V_Sale 
        FROM view 
        WHERE (V_Sale = ? OR ? = '')
        AND (V_Province = ? OR ? = '')";

    $stmt = $objConnect->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $objConnect->error);
    }
    $stmt->bind_param("ssss", $saleFilter, $saleFilter, $provinceFilter, $provinceFilter);
    $stmt->execute();
    $result = $stmt->get_result();

    fputcsv($output, ['ID', 'ชื่อหน่วยงาน', 'จังหวัด', 'อำเภอ', 'ตำบล', 'ทีมฝ่ายขาย']);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['V_ID'],
                $row['V_Name'],
                $row['V_Province'],
                $row['V_District'],
                $row['V_SubDistrict'],
                $row['V_Sale']
            ]);
        }
    } else {
        fputcsv($output, ['No data found']);
    }

    fclose($output);
    $stmt->close();
    $objConnect->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale View</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/jpg" href="../img/logo-eet.jpg">
</head>
<body class="bgcolor">
    <?php include 'header-sale.php'; ?>

    <div class="container">
        <div id="View" class="tabcontent">
            <div style="margin-bottom: 50px;">
                <h1>รายชื่อหน่วยงาน</h1>
            </div>
            
             <!-- Filter Form -->
             <form method="get" action="" class="filter-form">
                <div class="form-group">
                    <label for="province">จังหวัด :</label>
                    <select id="province" name="province" class="form-control">
                        <option value="">ทั้งหมด</option>
                        <?php
                        sort($provinces);
                        foreach ($provinces as $province) {
                            echo "<option value=\"$province\"" . ($provinceFilter == $province ? ' selected' : '') . ">$province</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sale">ทีมฝ่าบขาย :</label>
                    <select id="sale" name="sale" class="form-control">
                        <option value="">ทั้งหมด</option>
                        <?php
                        sort($sales);
                        foreach ($sales as $sale) {
                            echo "<option value=\"$sale\"" . ($saleFilter == $sale ? ' selected' : '') . ">$sale</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">สถานะ :</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">ทั้งหมด</option>
                        <?php
                        sort($statuses);
                        foreach ($statuses as $status) {
                            echo "<option value=\"$status\"" . ($statusFilter == $status ? ' selected' : '') . ">$status</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="?act=excel&sale=<?php echo urlencode($saleFilter); ?>&province=<?php echo urlencode($provinceFilter); ?>" class="btn btn-primary">Export to Excel</a>
                </div>
            </form>

            <table id="data" class="table table-striped">
                <tr>
                    <strong>จำนวนทั้งหมด <?php echo $total_rows; ?> หน่วยงาน</strong>
                    <th>ลำดับ</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>อำเภอ</th>
                    <th>ตำบล</th>
                    <th>ค่าไฟ/เดือน</th>
                    <th>การใช้ไฟ/เดือน</th>
                    <th>File PDF</th>
                    <th>ทีมฝ่ายขาย</th>
                    <th>อัปเดตสถานะ</th>
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
                            <td><?php echo htmlspecialchars($row["V_District"]); ?></td>
                            <td><?php echo htmlspecialchars($row["V_SubDistrict"]); ?></td>
                            <td><?php echo ($row["V_Electric_per_month"] == 0) ? 'N/A' : number_format($row["V_Electric_per_month"], 2); ?></td>
                            <td><?php echo ($row["V_Peak_month"] == 0) ? 'N/A' : number_format($row["V_Peak_month"], 2); ?></td>
                            <td>
                                <?php if (!empty($row["filename"])): ?>
                                    <a href="../uploads/<?php echo htmlspecialchars($row["filename"]); ?>" target="_blank"><?php echo htmlspecialchars($row["filename"]); ?></a>
                                <?php else: ?>
                                    No file
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row["V_Sale"]); ?></td>
                            <td><button class="btn btn-info btn-lg view-btn" data-id="<?php echo urlencode($row['V_ID']); ?>">View</button></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='10'>ไม่มีข้อมูลรายการ</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>

    <?php include '../buttom.html'; ?>
    <?php include '../back.html'; ?>
</body>
</html>
