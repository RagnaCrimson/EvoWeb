<?php
$servername = "localhost";
$username = "evoluti1_evo";
$password = "uzVztKVrghrZhu4n7LHF";
$dbname = "evoluti1_evo";

$objConnect = new mysqli($servername, $username, $password, $dbname);

if ($objConnect->connect_error) {
    die("Connection failed: " . $objConnect->connect_error);
}

$objConnect->set_charset("utf8");
?>
