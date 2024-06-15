<?php
include 'connect.php';

mysqli_query($objConnect, "SET NAMES utf8");

$strSQL_datastore_db = "
    SELECT view.*, task.T_Status 
    FROM view 
    LEFT JOIN task ON view.V_ID = task.T_ID";
$resultdatastore_db = $objConnect->query($strSQL_datastore_db);

if (!$resultdatastore_db) {
    die("Query failed: " . $objConnect->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data View</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Arvo&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div id="View" class="tabcontent">
            <div style="margin-bottom: 50px;"><h1>รายการติดตามสถานะ</h1></div>
            <table id="data" class="table table-striped">
                <tr>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>ชื่อผู้บริหาร</th>
                    <th>การใช้ไฟ/ปี</th>
                    <th>การใช้ไฟ/เดือน</th>
                    <th>หมายเหตุ</th>
                    <th>File</th>
                    <th>สถานะ</th>
                </tr>
                <?php while ($row = $resultdatastore_db->fetch_assoc()): ?>
                    <tr>     
                        <td><?php echo $row["V_Name"]; ?></td>
                        <td><?php echo $row["V_Province"]; ?></td>
                        <td><?php echo $row["V_ExecName"]; ?></td>
                        <td><?php echo $row["V_Electric_per_year"]; ?></td>
                        <td><?php echo $row["V_Electric_per_month"]; ?></td>
                        <td><?php echo $row["V_comment"]; ?></td>
                        <td><?php echo $row["V_File"]; ?></td>
                        <td><?php echo $row["T_Status"]; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
