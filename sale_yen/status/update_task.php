<?php
include '../connect.php';

$viewId = isset($_POST['view_id']) ? intval($_POST['view_id']) : 0;
$status2 = isset($_POST['status2']) ? $_POST['status2'] : '';

if ($viewId > 0 && !empty($status2)) {
    $strSQL = "
        UPDATE task 
        SET T_Status = ?, T_Date2 = NOW() 
        WHERE V_ID = ? 
        ORDER BY T_Date DESC 
        LIMIT 1";
    
    $stmt = $objConnect->prepare($strSQL);

    if ($stmt === false) {
        die("Error preparing the SQL statement: " . $objConnect->error);
    }

    $stmt->bind_param("si", $status2, $viewId);

    if ($stmt->execute()) {
        header("Location: ../status_view.php");
        exit();
    } else {
        echo "Error updating status: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid data.";
}

$objConnect->close();
?>
