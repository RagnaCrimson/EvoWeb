<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "datastore_db";

$objConnect = new mysqli($server, $username, $password, $database);

if ($objConnect->connect_error) {
    die("Connection failed: " . $objConnect->connect_error);
}

mysqli_query($objConnect, "SET NAMES utf8");

$strSQL_datastore_db = "SELECT * FROM view";
$resultdatastore_db = $objConnect->query($strSQL_datastore_db);
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
        <h1 class="text-center" style="margin-top: 5px; margin-bottom: 50px;">ข้อมูลการใช้ไฟฟ้าของหน่วยงาน</h1>
        <div class="row">
            <?php
            if ($resultdatastore_db->num_rows > 0) {
                while($row = $resultdatastore_db->fetch_assoc()) {
                    ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><b><?php echo htmlspecialchars($row["V_Name"]); ?></b></h5>
                                <p class="card-text"><strong>จังหวัด:</strong><b> <?php echo htmlspecialchars($row["V_Province"]); ?></b></p>
                                <p class="card-text"><strong>อำเภอ:</strong><b> <?php echo htmlspecialchars($row["V_District"]); ?></b></p>
                                <p class="card-text"><strong>ตำบล:</strong><b> <?php echo htmlspecialchars($row["V_SubDistrict"]); ?></b></p>
                                <p class="card-text"><strong>ชื่อผู้บริหาร:</strong><b> <?php echo htmlspecialchars($row["V_ExecName"]); ?></b></p>
                                <p class="card-text"><strong>เบอร์โทร:</strong><b> <?php echo htmlspecialchars($row["V_ExecPhone"]); ?></b></p>
                                <p class="card-text"><strong>E-mail:</strong><b> <?php echo htmlspecialchars($row["V_ExecMail"]); ?></b></p>
                                <p class="card-text"><strong>ชื่อผู้ประสานงาน 1:</strong><b> <?php echo htmlspecialchars($row["V_CoordName1"]); ?></b></p>
                                <p class="card-text"><strong>เบอร์โทรผู้ประสานงาน 1:</strong><b> <?php echo htmlspecialchars($row["V_CoordPhone1"]); ?></b></p>
                                <p class="card-text"><strong>E-mail ผู้ประสานงาน 1:</strong><b> <?php echo htmlspecialchars($row["V_CoordMail1"]); ?></b></p>
                                <p class="card-text"><strong>ชื่อผู้ประสานงาน 2:</strong><b> <?php echo htmlspecialchars($row["V_CoordName2"]); ?></b></p>
                                <p class="card-text"><strong>เบอร์โทรผู้ประสานงาน 2:</strong><b> <?php echo htmlspecialchars($row["V_CoordPhone2"]); ?></b></p>
                                <p class="card-text"><strong>E-mail ผู้ประสานงาน 2:</strong><b> <?php echo htmlspecialchars($row["V_CoordMail2"]); ?></b></p>
                                <p class="card-text"><strong>ทีมฝ่ายขาย:</strong><b> <?php echo htmlspecialchars($row["V_Sale"]); ?></b></p>
                                <p class="card-text"><strong>วันที่รับเอกสาร:</strong><b> <?php echo htmlspecialchars($row["V_Date"]); ?></b></p>
                                <p class="card-text"><strong>การใช้ไฟ/ปี:</strong><b> <?php echo htmlspecialchars($row["V_Electric_per_year"]); ?></b></p>
                                <p class="card-text"><strong>การใช้ไฟ/เดือน:</strong><b> <?php echo htmlspecialchars($row["V_Electric_per_month"]); ?></b></p>
                                <p class="card-text"><strong>หมายเหตุ:</strong><b> <?php echo htmlspecialchars($row["V_comment"]); ?></b></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='col-12'><p class='text-center'>ไม่มีข้อมูลรายการ</p></div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
