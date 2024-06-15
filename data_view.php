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
    <title>Header</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Arvo&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="js/logout.js"></script>
</head>

    <?php
        include 'header.php';
    ?>

</head>

<body>
    <h1 class="center" style="margin-top: 50px; margin-bottom: 50px">ข้อมูลการใช้ไฟฟ้าของหน่วยงาน</h1>
    <div id="View" class="tabcontent">
        <table id="data" class="table table-striped">
            <tr>
                <th>ลำดับ</th>
                <th>ชื่อหน่วยงาน</th>
                <th>จังหวัด</th>
                <th>อำเภอ</th>
                <th>ตำบล</th>
                <th>ชื่อผู้บริหาร</th>
                <th>เบอร์โทร</th>
                <th>E-mail</th>
                <th>ชื่อผู้ประสานงาน 1</th>
                <th>เบอร์โทรผู้ประสานงาน 1</th>
                <th>E-mail ผู้ประสานงาน 1</th>
                <th>ชื่อผู้ประสานงาน 2</th>
                <th>เบอร์โทรผู้ประสานงาน 2</th>
                <th>E-mail ผู้ประสานงาน 2</th>
                <th>ทีมฝ่ายขาย</th>
                <th>วันที่รับเอกสาร</th>
                <th>การใช้ไฟ/ปี</th>
                <th>การใช้ไฟ/เดือน</th>
                <th>หมายเหตุ</th>
                <!-- <th>Actions</th> -->
            </tr>
                <?php
                if ($resultdatastore_db->num_rows > 0) {
                    $sequence = 1; // Initialize sequence number
                    while($row = $resultdatastore_db->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $sequence++; ?></td>
                            <td><?php echo $row["V_Name"]; ?></td>
                            <td><?php echo $row["V_Province"]; ?></td>
                            <td><?php echo $row["V_District"]; ?></td>
                            <td><?php echo $row["V_SubDistrict"]; ?></td>
                            <td><?php echo $row["V_Exec_name"]; ?></td>
                            <td><?php echo $row["V_Exec_phone"]; ?></td>
                            <td><?php echo $row["V_Exec_mail"]; ?></td>
                            <td><?php echo $row["V_Coord_name1"]; ?></td>
                            <td><?php echo $row["V_Coord_phone1"]; ?></td>
                            <td><?php echo $row["V_Coord_mail1"]; ?></td>
                            <td><?php echo $row["V_Coord_name2"]; ?></td>
                            <td><?php echo $row["V_Coord_phone2"]; ?></td>
                            <td><?php echo $row["V_Coord_mail2"]; ?></td>
                            <td><?php echo $row["V_Sale"]; ?></td>
                            <td><?php echo $row["V_Date"]; ?></td>
                            <td><?php echo $row["V_Electric_per_year"]; ?></td>
                            <td><?php echo $row["V_Electric_per_month"]; ?></td>
                            <td><?php echo $row["V_comment"]; ?></td>
                            <!-- <td>
                                <a href="edit.php?id=<?php echo $row['V_Name']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete.php?id=<?php echo $row['V_Name']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td> -->
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='17'>ไม่มีข้อมูลรายการ</td></tr>";
                }
                ?>
            </table>
        </div>
</body>
</html>