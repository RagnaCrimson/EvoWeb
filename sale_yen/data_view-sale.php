<?php
include '../connect.php';

mysqli_query($objConnect, "SET NAMES utf8");

$search = isset($_GET['search']) ? $objConnect->real_escape_string($_GET['search']) : '';
$saleFilter = isset($_GET['sale']) ? $objConnect->real_escape_string($_GET['sale']) : '';
$statusFilter = isset($_GET['status']) ? $objConnect->real_escape_string($_GET['status']) : '';
$provinceFilter = isset($_GET['province']) ? $objConnect->real_escape_string($_GET['province']) : '';

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
    if ($stmt_datastore_db === false) {
        die("Prepare failed: " . $objConnect->error);
    }
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
    if ($stmt_datastore_db === false) {
        die("Prepare failed: " . $objConnect->error);
    }
    $stmt_datastore_db->bind_param("ssssss", $saleFilter, $saleFilter, $statusFilter, $statusFilter, $provinceFilter, $provinceFilter);
}

if (!$stmt_datastore_db->execute()) {
    die("Execute failed: " . $stmt_datastore_db->error);
}

$resultdatastore_db = $stmt_datastore_db->get_result();

if ($resultdatastore_db === false) {
    die("Getting result set failed: " . $stmt_datastore_db->error);
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

// Prepare for CSV export
if (isset($_GET['act']) && $_GET['act'] == 'excel') {
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=export.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Open output stream
    $output = fopen('php://output', 'w');

    // Add BOM to indicate UTF-8 encoding
    fprintf($output, "\xEF\xBB\xBF");

    $sql = "SELECT V_ID, V_Name, V_Province, V_District, V_SubDistrict, V_Sale FROM view";
    $result = $objConnect->query($sql);

    // Output the column headers
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
    $conn->close();
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
            <div style="margin-bottom: 50px;"><h1>รายชื่อหน่วยงาน</h1></div>
            <p><a href="?act=excel" class="btn btn-primary">Export to Excel</a></p>
            <table id="data" class="table table-striped">
                <tr>
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
                    echo "<tr><td colspan='11'>ไม่มีข้อมูลรายการ</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>

    <?php include '../buttom.html'; ?>
    <?php include '../back.html'; ?>
</body>
</html>
