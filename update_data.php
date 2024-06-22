<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$view_id = isset($_POST['view_id']) ? intval($_POST['view_id']) : 0;
$V_comment = isset($_POST['V_comment']) ? $conn->real_escape_string($_POST['V_comment']) : '';
$T_Status = isset($_POST['T_Status']) ? $conn->real_escape_string($_POST['T_Status']) : '';

if ($view_id < 2) {
    die("Invalid ID provided.");
}

$sql_update_view = "UPDATE view SET V_comment = '$V_comment' WHERE V_ID = $view_id";
if ($conn->query($sql_update_view) === FALSE) {
    die("Error updating view table: " . $conn->error);
}

$sql_update_task = "UPDATE task SET T_Status = '$T_Status' WHERE T_ID = $view_id";
if ($conn->query($sql_update_task) === FALSE) {
    die("Error updating task table: " . $conn->error);
}

$conn->close();

header("Location: status_view.php");
exit();
?>
