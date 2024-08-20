<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "datastore_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

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
    $result = $conn->query($sql);

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
<html>
<head>
    <meta charset="utf-8">
    <title>devbanban</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
                    <a href="?act=excel" class="btn btn-primary">Export to Excel</a>
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
                        // Fetch data for HTML table
                        $sql = "SELECT V_ID, V_Name, V_Province, V_District, V_SubDistrict, V_Sale FROM view";
                        $result = $conn->query($sql);

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
                            echo "<tr><td colspan='4'>No data found</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
