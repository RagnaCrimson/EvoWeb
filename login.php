<?php
session_start();

$servername = "localhost";
$username = "evoluti1_evo";
$password = "uzVztKVrghrZhu4n7LHF";
$dbname = "evoluti1_evo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admin WHERE UserName='$username' AND Password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['username'] = $username; 
    $response = array('success' => true);
} else {
    $response = array('success' => false, 'message' => 'Login failed. Please check your username and password.');
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
