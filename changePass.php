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

$oldpassword = $_POST['oldpassword'];
$newpassword = $_POST['newpassword'];
$confirmnewpassword = $_POST['confirmnewpassword'];
$username = $_SESSION['username'];

// Verify old password
$sql_verify = "SELECT * FROM admin WHERE UserName='$username' AND Password='$oldpassword'";
$result_verify = $conn->query($sql_verify);

if ($result_verify->num_rows > 0) {
    // Old password is correct, proceed with changing password
    if ($newpassword == $confirmnewpassword) {
        $sql_update = "UPDATE admin SET Password='$newpassword' WHERE UserName='$username'";
        if ($conn->query($sql_update) === TRUE) {
            $response = array('success' => true, 'message' => 'Password updated successfully.');
        } else {
            $response = array('success' => false, 'message' => 'Error updating password: ' . $conn->error);
        }
    } else {
        $response = array('success' => false, 'message' => 'New passwords do not match.');
    }
} else {
    $response = array('success' => false, 'message' => 'Incorrect old password.');
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
