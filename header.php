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
    <title>Header</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="/evo/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Arvo&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="/evo/js/logout.js"></script>
</head>
<body>
    <nav role="navigation" class="primary-navigation">
        <ul>
            <li><a href="#"><?php echo $_SESSION['name']; ?></a></li>
            <li><a href="dashboard.php">Dashboard</a>
            <li><a href="#">รายการ &dtrif;</a>
                <ul class="dropdown">
                    <li><a href="/evo/data_view.php">ดูข้อมูลทั้งหมด</a></li>
                    <li><a href="/evo/index.php">เพิ่มข้อมูล</a></li>
                    <li><a href="/evo/status_view.php">ดูสถานะ</a></li>
                </ul>
            </li>
            <li><a href="#">Report &dtrif;</a>
                <ul class="dropdown">
                    <li><a href="reportday.php">เลือกวันที่</a></li>
                    <li><a href="#">ตามสถานะ</a></li>
                    <li><a href="#">บริษัทผู้รับเหมา</a></li>
                </ul>
            </li>
            <li><a onclick="confirmLogout()">ลงชื่ออก</a></li>
        </ul>
    </nav>
</body>
</html>
