<?php
include 'connect.php';

mysqli_query($objConnect, "SET NAMES utf8");

if (isset($_POST['submit'])) {
    $data = $_POST['data'];
    $V_ID = $_POST['V_ID'];

    // Check if V_ID is set
    if (!isset($V_ID)) {
        die("V_ID is not set.");
    }

    $strSQL_update_view = "UPDATE view SET V_Name = ?, V_Province = ?, V_District = ?, V_SubDistrict = ?, V_ExecName = ?, V_ExecPhone = ?, V_ExecMail = ?, V_CoordName1 = ?, V_CoordPhone1 = ?, V_CoordMail1 = ?, V_Sale = ?, V_Date = ?, V_Electric_per_month = ?, V_Peak_month = ?, V_comment = ?, V_location = ? WHERE V_ID = ?";
    $stmt_update_view = mysqli_prepare($objConnect, $strSQL_update_view);

    if (!$stmt_update_view) {
        die("Error preparing statement: " . mysqli_error($objConnect));
    }

    mysqli_stmt_bind_param($stmt_update_view, "sssssssssssssssss", 
        $data['V_Name'], $data['V_Province'], $data['V_District'], $data['V_SubDistrict'], 
        $data['V_ExecName'], $data['V_ExecPhone'], $data['V_ExecMail'], $data['V_CoordName1'], 
        $data['V_CoordPhone1'], $data['V_CoordMail1'], $data['V_Sale'], $data['V_Date'], 
        $data['V_Electric_per_month'], $data['V_Peak_month'], $data['V_comment'], 
        $data['V_location'], $V_ID
    );
    mysqli_stmt_execute($stmt_update_view);
    mysqli_stmt_close($stmt_update_view);

    // Update peak table
    $strSQL_update_peak = "UPDATE peak SET serial_number = ?, CA_code = ? WHERE V_ID = ?";
    $stmt_update_peak = mysqli_prepare($objConnect, $strSQL_update_peak);

    if (!$stmt_update_peak) {
        die("Error preparing statement: " . mysqli_error($objConnect));
    }

    mysqli_stmt_bind_param($stmt_update_peak, "ssi", $data['serial_number'], $data['CA_code'], $V_ID);
    mysqli_stmt_execute($stmt_update_peak);
    mysqli_stmt_close($stmt_update_peak);

    // Update bill table
    $strSQL_update_bill = "UPDATE bill SET B_M12 = ? WHERE V_ID = ?";
    $stmt_update_bill = mysqli_prepare($objConnect, $strSQL_update_bill);

    if (!$stmt_update_bill) {
        die("Error preparing statement: " . mysqli_error($objConnect));
    }

    mysqli_stmt_bind_param($stmt_update_bill, "si", $data['B_M12'], $V_ID);
    mysqli_stmt_execute($stmt_update_bill);
    mysqli_stmt_close($stmt_update_bill);

    // Handle file upload
    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file']['tmp_name'];
        $filename = basename($_FILES['file']['name']);
        $target = 'uploads/' . $filename;
        
        if (move_uploaded_file($file, $target)) {
            // Update files table
            $strSQL_update_file = "UPDATE files SET filename = ? WHERE id = ?";
            $stmt_update_file = mysqli_prepare($objConnect, $strSQL_update_file);

            if (!$stmt_update_file) {
                die("Error preparing statement: " . mysqli_error($objConnect));
            }

            mysqli_stmt_bind_param($stmt_update_file, "si", $filename, $V_ID);
            mysqli_stmt_execute($stmt_update_file);
            mysqli_stmt_close($stmt_update_file);
        } else {
            echo "Error uploading file.";
        }
    }

    echo "Data updated successfully.";
    header("Location: data_view.php");
    exit;
}
?>
