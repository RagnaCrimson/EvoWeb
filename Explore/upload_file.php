<?php
include '../connect.php';

function uploadImage($objConnect) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "file/";
        
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            return;
        }

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            return;
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $v_id = 1;
            $stmt = $objConnect->prepare("INSERT INTO uploads (v_id, filename) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("is", $v_id, $fileName);
                if ($stmt->execute()) {
                    echo "The file " . htmlspecialchars($fileName) . " has been uploaded and saved.";
                } else {
                    echo "Error saving the file to the database.";
                }
                $stmt->close();
            } else {
                echo "Failed to prepare SQL statement.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    uploadImage($objConnect); 
}

$objConnect->close();
?>
