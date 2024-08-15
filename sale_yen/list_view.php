<?php
include '../connect.php';
mysqli_query($objConnect, "SET NAMES utf8");

$saleFilter = isset($_GET['sale']) ? $objConnect->real_escape_string($_GET['sale']) : '';
$statusFilter = isset($_GET['status']) ? $objConnect->real_escape_string($_GET['status']) : '';
$provinceFilter = isset($_GET['province']) ? $objConnect->real_escape_string($_GET['province']) : '';

$strSQL_datastore_db = "
    SELECT view.*, task.T_Status, files.filename 
    FROM view 
    LEFT JOIN task ON view.V_ID = task.T_ID
    LEFT JOIN files ON view.V_ID = files.id
    WHERE (view.V_Sale = ? OR ? = '')
    AND (task.T_Status = ? OR ? = '')
    AND (view.V_Province = ? OR ? = '')
";

$stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);
$stmt_datastore_db->bind_param("ssssss", $saleFilter, $saleFilter, $statusFilter, $statusFilter, $provinceFilter, $provinceFilter);

$stmt_datastore_db->execute();
$resultdatastore_db = $stmt_datastore_db->get_result();

if (!$resultdatastore_db) {
    die("Query failed: " . $objConnect->error);
}

$total_rows = $resultdatastore_db->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List view for print</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        @media print {
            table {
                page-break-inside: auto;
            }
            thead {
                display: table-header-group;
            }
            tbody {
                display: table-row-group;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body class="bgcolor">
    <div class="container">
        <div id="View" class="tabcontent">
            <div><h1>รายการชื่อหน่วยงานทั้งหมด</h1></div>
            <table id="data" class="table table-striped">
                <tr>
                    <td col000span="7"><strong>จำนวนทั้งหมด</strong></td>
                    <td><strong><?php echo $total_rows; ?> หน่วยงาน</strong></td>
                </tr>
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อหน่วยงาน</th>
                        <th>จังหวัด</th>
                        <th>ทีมฝ่ายขาย</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                    if ($resultdatastore_db->num_rows > 0) {
                        $sequence = 1;
                        while ($row = $resultdatastore_db->fetch_assoc()) {
                            ?>
                            <tr>     
                                <td><?php echo $sequence++; ?></td>
                                <td><?php echo htmlspecialchars($row["V_Name"]); ?></td>
                                <td><?php echo htmlspecialchars($row["V_Province"]); ?></td>
                                <td><?php echo htmlspecialchars($row["V_Sale"]); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='6'>ไม่มีข้อมูลรายการ</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
