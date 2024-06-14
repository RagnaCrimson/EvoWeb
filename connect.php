<?php
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

if ($stmt === false) {
    die("Error preparing statement.");
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row['column_name']; 
    }
} else {
    echo "No results found.";
}

$stmt->close();
$objConnect->close();
?>
