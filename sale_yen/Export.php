<?php
include 'connect.php';

// Capture filter values from the URL parameters
$saleFilter = isset($_GET['sale']) ? $conn->real_escape_string($_GET['sale']) : '';
$provinceFilter = isset($_GET['province']) ? $conn->real_escape_string($_GET['province']) : '';

// Prepare for CSV export
if (isset($_GET['act']) && $_GET['act'] == 'excel') {
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; filename=export.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Open output stream
    $output = fopen('php://output', 'w');

    fprintf($output, "\xEF\xBB\xBF");

    // Capture filter values
    $saleFilter = isset($_GET['sale']) ? $conn->real_escape_string($_GET['sale']) : '';
    $provinceFilter = isset($_GET['province']) ? $conn->real_escape_string($_GET['province']) : '';

    // Prepare the SQL query based on filters
    $sql = "
        SELECT V_ID, V_Name, V_Province, V_District, V_SubDistrict, V_Sale 
        FROM view 
        WHERE (V_Sale = ? OR ? = '')
        AND (V_Province = ? OR ? = '')";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
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
    $conn->close();
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Filtered Export to Excel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="icon" type="image/jpg" href="../img/logo-eet.jpg">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'header-sale.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <br /><br /><br />
                
                <p>
                    <!-- Pass current filters to the export link -->
                    <a href="?act=excel&sale=<?= htmlspecialchars($saleFilter) ?>&province=<?= htmlspecialchars($provinceFilter) ?>" class="btn btn-primary">Export to Excel</a>
                </p>
                
                <table border="1" class="table table-hover">
                    <thead>
                        <tr class="info">
                            <th>ID</th>
                            <th>ชื่อหน่วยงาน</th>
                            <th>จังหวัด</th>
                            <th>อำเภอ</th>
                            <th>ตำบล</th>
                            <th>ทีมฝ่ายขาย</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Prepare the SQL query based on filters for displaying in the table
                        $sql = "
                            SELECT V_ID, V_Name, V_Province, V_District, V_SubDistrict, V_Sale 
                            FROM view 
                            WHERE (V_Sale = ? OR ? = '')
                            AND (V_Province = ? OR ? = '')";

                        $stmt = $conn->prepare($sql);
                        if (!$stmt) {
                            die("Prepare failed: " . $conn->error);
                        }
                        $stmt->bind_param("ssss", $saleFilter, $saleFilter, $provinceFilter, $provinceFilter);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['V_ID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['V_Name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['V_Province']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['V_District']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['V_SubDistrict']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['V_Sale']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No data found</td></tr>";
                        }

                        $stmt->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
