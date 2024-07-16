<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db";

$objConnect = new mysqli($servername, $username, $password, $dbname);

if ($objConnect->connect_error) {
    die("Connection failed: " . $objConnect->connect_error);
}

$objConnect->set_charset("utf8");
?>
