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

$objConnect->set_charset("utf8");

$stmt = $objConnect->prepare("SELECT * FROM view");
$stmt->execute();
$resultdatastore_db = $stmt->get_result();

if ($resultdatastore_db->num_rows > 0) {
    while ($row = $resultdatastore_db->fetch_assoc()) {
        echo $row['column_name']; 
    }
} else {
    echo "No results found.";
}

// Close connection
$stmt->close();
$objConnect->close();
?>
