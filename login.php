<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db";

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
    
    if ($username == 'insert') {
        $redirectUrl = 'insert_user/insert_data.php';
    } elseif ($username == 'eet01') {
        $redirectUrl = 'sale_yen/insert_data-yen.php';
    } else {
        $redirectUrl = 'dashboard.php';
    }
    
    $response = array('success' => true, 'redirectUrl' => $redirectUrl);
} else {
    $response = array('success' => false, 'message' => 'Login failed. Please check your username and password.');
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>