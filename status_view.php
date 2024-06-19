<?php
include 'connect.php';

mysqli_query($objConnect, "SET NAMES utf8");

$strSQL_datastore_db = "
    SELECT view.*, task.T_Status, files.filename 
    FROM view 
    LEFT JOIN task ON view.V_ID = task.T_ID
    LEFT JOIN files ON view.V_ID = files.id";
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
    <title>Dashbord Admin</title>
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
                    <th>ลำดับ</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>ชื่อผู้บริหาร</th>
                    <th>การใช้ไฟ/ปี</th>
                    <th>การใช้ไฟ/เดือน</th>
                    <!-- <th>หมายเหตุ</th> -->
                    <th>File PDF</th>
                    <th>สถานะ</th>
                </tr>
                <?php  if ($resultdatastore_db->num_rows > 0) {
                    $sequence = 1; // Initialize sequence number
                    while($row = $resultdatastore_db->fetch_assoc()) {
                        ?>
                    <tr>     
                        <td><?php echo $sequence++; ?></td>
                        <td><?php echo htmlspecialchars($row["V_Name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["V_Province"]); ?></td>
                        <td><?php echo htmlspecialchars($row["V_ExecName"]); ?></td>
                        <td><?php echo htmlspecialchars($row["V_Electric_per_year"]); ?></td>
                        <td><?php echo htmlspecialchars($row["V_Electric_per_month"]); ?></td>
                        <!-- <td><?php echo htmlspecialchars($row["V_comment"]); ?></td> -->
                        <td>
                            <?php if (!empty($row["filename"])): ?>
                                <a href="uploads/<?php echo htmlspecialchars($row["filename"]); ?>" target="_blank"><?php echo htmlspecialchars($row["filename"]); ?></a>
                            <?php else: ?>
                                No file
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row["T_Status"]); ?></td>
                    </tr>
                    <?php
                    }
                } else {
                    echo "<tr><td colspan='17'>ไม่มีข้อมูลรายการ</td></tr>";
                }
                ?>
            </table>

            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="file" class="form-label">Select file</label>
                    <input type="file" class="form-control" name="file" id="file" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload file</button>
            </form>
        </div>
    </div>
</body>
</html>
