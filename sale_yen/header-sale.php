<?php
session_start();

$server = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db";

$objConnect = new mysqli($server, $username, $password, $dbname);

if ($objConnect->connect_error) {
    die("Connection failed: " . $objConnect->connect_error);
}

require_once 'session.php';

check_login();

$username = $_SESSION['username'];
$sql = "SELECT Name FROM admin WHERE UserName='$username'";
$result = $objConnect->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['name'] = $row['Name'];
} else {
    echo "Name not found.";
}

$objConnect->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Sale View</title>
    <link rel="icon" type="image/jpg" href="../img/logo-eet.jpg">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Arvo&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/logout.js"></script>
</head>
<body>
    <nav role="navigation" class="primary-navigation">
        <ul>
            <li><a href="#"><?php echo $_SESSION['name']; ?></a></li>
            <li><a href="board.php">dashboard</a></li>
            <li><a href="insert_data-yen.php">เพิ่มข้อมูล</a></li>
            <li><a href="#">รายการ &dtrif;</a>
                <ul class="dropdown">
                    <li><a href="data_view-sale.php">ดูข้อมูลทั้งหมด</a></li>
                    <li><a href="list_view.php">รายละเอียดหน่วยงาน</a></li>
                    <li><a href="sale_name.php">เลือกดูทีมฝ่ายขาย</a></li>
                    <!-- <li><a href="Export">Excel File</a></li> -->
                </ul>
            </li>
            <li><a onclick="confirmLogout()">ลงชื่ออก</a></li>
        </ul>
    </nav>
</body>
</html>