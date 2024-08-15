<?php
include 'connect.php'; 

mysqli_query($objConnect, "SET NAMES utf8");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Retrieve data from view table
    $strSQL_edit = "SELECT * FROM view WHERE V_Name = '$id'";
    $result_edit = mysqli_query($objConnect, $strSQL_edit);
    
    if (!$result_edit) {
        die("Query failed: " . mysqli_error($objConnect));
    }
    
    $row_edit = mysqli_fetch_assoc($result_edit);
    $V_ID = isset($row_edit['V_ID']) ? $row_edit['V_ID'] : null;
    
    // Retrieve additional data from peak table
    $strSQL_peak = "SELECT serial_number, CA_code FROM peak WHERE V_ID = '$V_ID'";
    $result_peak = mysqli_query($objConnect, $strSQL_peak);
    $row_peak = mysqli_fetch_assoc($result_peak);
    
    // Retrieve additional data from bill table
    $strSQL_bill = "SELECT B_M12 FROM bill WHERE V_ID = '$V_ID'";
    $result_bill = mysqli_query($objConnect, $strSQL_bill);
    $row_bill = mysqli_fetch_assoc($result_bill);
    
    // Retrieve filename from files table
    $strSQL_file = "SELECT filename FROM files WHERE V_ID = '$V_ID'";
    $result_file = mysqli_query($objConnect, $strSQL_file);
    $row_file = mysqli_fetch_assoc($result_file);
    
    // Ensure arrays are not null
    $row_peak = $row_peak ? $row_peak : [];
    $row_bill = $row_bill ? $row_bill : [];
    $row_file = $row_file ? $row_file : [];
    
    // Merge data from different tables
    $row_edit = array_merge($row_edit ? $row_edit : [], $row_peak ? $row_peak : [], $row_bill ? $row_bill : [], $row_file ? $row_file : []);
} else {
    echo "No ID specified.";
    exit;
}

if (isset($_POST['submit'])) {
    $newData = $_POST['data']; 

    $vName = mysqli_real_escape_string($objConnect, $newData['V_Name']);
    $vProvince = mysqli_real_escape_string($objConnect, $newData['V_Province']);
    $vDistrict = mysqli_real_escape_string($objConnect, $newData['V_District']);
    $vSubDistrict = mysqli_real_escape_string($objConnect, $newData['V_SubDistrict']);
    $vExecName = mysqli_real_escape_string($objConnect, $newData['V_ExecName']);
    $vExecPhone = mysqli_real_escape_string($objConnect, $newData['V_ExecPhone']);
    $vExecMail = mysqli_real_escape_string($objConnect, $newData['V_ExecMail']);
    $vCoordName1 = mysqli_real_escape_string($objConnect, $newData['V_CoordName1']);
    $vCoordPhone1 = mysqli_real_escape_string($objConnect, $newData['V_CoordPhone1']);
    $vCoordMail1 = mysqli_real_escape_string($objConnect, $newData['V_CoordMail1']);
    $vCoordName2 = mysqli_real_escape_string($objConnect, $newData['V_CoordName2']);
    $vCoordPhone2 = mysqli_real_escape_string($objConnect, $newData['V_CoordPhone2']);
    $vCoordMail2 = mysqli_real_escape_string($objConnect, $newData['V_CoordMail2']);
    $vSale = mysqli_real_escape_string($objConnect, $newData['V_Sale']);
    $vDate = mysqli_real_escape_string($objConnect, $newData['V_Date']);
    $vElectricPerYear = mysqli_real_escape_string($objConnect, $newData['V_Electric_per_year']);
    $vElectricPerMonth = mysqli_real_escape_string($objConnect, $newData['V_Electric_per_month']);
    $vPeakYear = mysqli_real_escape_string($objConnect, $newData['V_Peak_year']);
    $vPeakMonth = mysqli_real_escape_string($objConnect, $newData['V_Peak_month']);
    $vComment = mysqli_real_escape_string($objConnect, $newData['V_comment']);
    
    // File upload handling
    $uploadOk = 1;
    $targetDir = "uploads/";
    $filename = '';
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {
        $targetFile = $targetDir . basename($_FILES["file"]["name"]);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        if ($fileType != "pdf") {
            echo "Sorry, only PDF files are allowed.";
            $uploadOk = 0;
        }
        
        if ($_FILES["file"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
                $filename = basename($_FILES["file"]["name"]);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update view table
    $strSQL_update = "UPDATE view SET 
                      V_Name = '$vName',
                      V_Province = '$vProvince',
                      V_District = '$vDistrict',
                      V_SubDistrict = '$vSubDistrict',
                      V_ExecName = '$vExecName',
                      V_ExecPhone = '$vExecPhone',
                      V_ExecMail = '$vExecMail',
                      V_CoordName1 = '$vCoordName1',
                      V_CoordPhone1 = '$vCoordPhone1',
                      V_CoordMail1 = '$vCoordMail1',
                      V_CoordName2 = '$vCoordName2',
                      V_CoordPhone2 = '$vCoordPhone2',
                      V_CoordMail2 = '$vCoordMail2',
                      V_Sale = '$vSale',
                      V_Date = '$vDate',
                      V_Electric_per_year = '$vElectricPerYear',
                      V_Electric_per_month = '$vElectricPerMonth',
                      V_Peak_year = '$vPeakYear',
                      V_Peak_month = '$vPeakMonth',
                      V_comment = '$vComment'
                      WHERE V_Name = '$id'";

    if (mysqli_query($objConnect, $strSQL_update)) {
        echo '<script>
            alert("Record updated successfully.");
            window.location.href = "data_view.php"; // Redirect to data_view.php
        </script>';
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($objConnect);
    }

    // Update peak table
    $sql_peak = "UPDATE peak SET 
                 serial_number = ?, 
                 CA_code = ? 
                 WHERE V_ID = ?";
    
    $stmt_peak = mysqli_prepare($objConnect, $sql_peak);
    if ($stmt_peak === false) {
        die("Error preparing statement for peak table: " . mysqli_error($objConnect));
    }

    $numb = isset($_POST['serial_number']) ? $_POST['serial_number'] : ''; 
    $ca_code = isset($_POST['CA_code']) ? $_POST['CA_code'] : ''; 

    // Bind parameters
    mysqli_stmt_bind_param($stmt_peak, "ssi", $numb, $ca_code, $V_ID);
    mysqli_stmt_execute($stmt_peak);
    mysqli_stmt_close($stmt_peak);

    // Update bill table
    $sql_bill = "UPDATE bill SET 
                 B_M12 = ? 
                 WHERE V_ID = ?";
    
    $stmt_bill = mysqli_prepare($objConnect, $sql_bill);
    if ($stmt_bill === false) {
        die("Error preparing statement for bill table: " . mysqli_error($objConnect));
    }

    $b_m12 = isset($_POST['B_M12']) ? $_POST['B_M12'] : ''; 

    // Bind parameters
    mysqli_stmt_bind_param($stmt_bill, "di", $b_m12, $V_ID);
    mysqli_stmt_execute($stmt_bill);
    mysqli_stmt_close($stmt_bill);

    // Update files table only if a new file was uploaded
    if ($filename) {
        $sql_file = "UPDATE files SET filename = ? WHERE V_ID = ?";
        
        $stmt_file = mysqli_prepare($objConnect, $sql_file);
        if ($stmt_file === false) {
            die("Error preparing statement for files table: " . mysqli_error($objConnect));
        }

        // Bind parameters
        mysqli_stmt_bind_param($stmt_file, "si", $filename, $V_ID);
        mysqli_stmt_execute($stmt_file);
        mysqli_stmt_close($stmt_file);
    }

    mysqli_close($objConnect);

    echo "Data updated successfully!";
}
?>