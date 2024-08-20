<?php
include '../connect.php';
mysqli_query($objConnect, "SET NAMES utf8");

$sales_team = isset($_POST['sales_team']) ? $objConnect->real_escape_string($_POST['sales_team']) : '';

$strSQL = "
    SELECT view.*, task.T_Status, files.filename 
    FROM view 
    LEFT JOIN task ON view.V_ID = task.T_ID
    LEFT JOIN files ON view.V_ID = files.id
    WHERE (view.V_Sale = ? OR ? = '')
";

$stmt = $objConnect->prepare($strSQL);
$stmt->bind_param("ss", $sales_team, $sales_team);

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $objConnect->error);
}
$total_rows = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Team Report</title>
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
<body>
<body class="bgcolor">
    <div class="container">
        <div id="View" class="tabcontent">
        <div><h1>รายชื่อหน่วยงานของ <?php echo htmlspecialchars($sales_team); ?> </h1></div>
        <tr>
            <td col000span="7"><strong>จำนวนทั้งหมด</strong></td>
            <td><strong><?php echo $total_rows; ?> หน่วยงาน</strong></td>
        </tr>
        <table id="data"  class="table table-striped">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ID</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>ทีมฝ่ายขาย</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $sequence = 1;
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $sequence++; ?></td>
                            <td><?php echo htmlspecialchars($row["V_ID"]); ?></td>
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
</body>
</html>
