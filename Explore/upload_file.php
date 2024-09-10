<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $V_ID = $_POST['V_ID'];
    $target_dir = "file/";
    $file_name = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($fileType == "pdf" || $fileType == "jpg" || $fileType == "jpeg" || $fileType == "png") {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $stmt = $objConnect->prepare("INSERT INTO uploads (V_ID, filename) VALUES (?, ?)");
            if (!$stmt) {
                die("Error preparing query: " . $objConnect->error);
            }

            $stmt->bind_param("is", $V_ID, $file_name);
            if ($stmt->execute()) {
                echo "File uploaded and record saved successfully!";
            } else {
                echo "Error saving record: " . $stmt->error;
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Invalid file format. Only PDF, JPG, JPEG, and PNG files are allowed.";
    }
}
?>
