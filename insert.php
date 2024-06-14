<?php
session_start();

$server = "localhost";
$username = "root";
$password = "";
$database = "datastore_db";

$objConnect = new mysqli($server, $username, $password, $database);

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

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $sub_district = $_POST['sub_district'];
    $exec_name = $_POST['exec_name'];
    $exec_phone = $_POST['exec_phone'];
    $exec_mail = $_POST['exec_mail'];
    $coord_name1 = $_POST['coord_name1'];
    $coord_phone1 = $_POST['coord_phone1'];
    $coord_mail1 = $_POST['coord_mail1'];
    $coord_name2 = $_POST['coord_name2'];
    $coord_phone2 = $_POST['coord_phone2'];
    $sale = $_POST['sale'];
    $date = $_POST['date'];
    $electric_year = $_POST['electric_year'];
    $electric_month = $_POST['electric_month'];
    $notes = $_POST['notes'];

    $insert_sql = "INSERT INTO `view` (Name, Province, District, Sub_District, ExecName, ExecPhone, ExecMail, CoordName1, CoordPhon1, CoordMail1, CoordName2, CoordPhon2, Sale, Date, ElectricYear, ElectricMonth, Notes) 
    VALUES ('$name', '$province', '$district', '$sub_district', '$exec_name', '$exec_phone', '$exec_mail', '$coord_name1', '$coord_phone1', '$coord_mail1', '$coord_name2', '$coord_phone2', '$sale', '$date', '$electric_year', '$electric_month', '$notes')";

    if ($objConnect->query($insert_sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $insert_sql . "<br>" . $objConnect->error;
    }
}

$objConnect->close();
?>
