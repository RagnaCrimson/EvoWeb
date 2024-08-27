<?php
include '../connect.php';

$viewId = isset($_POST['view_id']) ? intval($_POST['view_id']) : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';
$saleFilter = isset($_POST['sale']) ? $_POST['sale'] : '';
$provinceFilter = isset($_POST['province']) ? $_POST['province'] : '';
$statusFilter = isset($_POST['status_filter']) ? $_POST['status_filter'] : '';

if ($viewId > 0 && !empty($status)) {
    // Fetch the most recent T_ID for the given V_ID
    $sqlGetTaskId = "
        SELECT T_ID
        FROM task
        WHERE V_ID = ? -- Filter by V_ID
        ORDER BY T_Date DESC
        LIMIT 1";
    
    $stmtGetTaskId = $objConnect->prepare($sqlGetTaskId);

    if ($stmtGetTaskId === false) {
        die("Error preparing the SQL statement: " . $objConnect->error);
    }

    $stmtGetTaskId->bind_param("i", $viewId);

    if (!$stmtGetTaskId->execute()) {
        die("Error executing the SQL statement: " . $stmtGetTaskId->error);
    }

    $resultGetTaskId = $stmtGetTaskId->get_result();

    if ($resultGetTaskId->num_rows > 0) {
        $task = $resultGetTaskId->fetch_assoc();
        $taskId = $task['T_ID'];

        $strSQL = "
            UPDATE task
            SET T_Status = ?, T_Date = NOW()
            WHERE T_ID = ?";
        
        $stmt = $objConnect->prepare($strSQL);

        if ($stmt === false) {
            die("Error preparing the SQL statement: " . $objConnect->error);
        }

        $stmt->bind_param("si", $status, $taskId);

        if ($stmt->execute()) {
            // Redirect to the filtered page
            $redirectUrl = "../status_view.php?sale=" . urlencode($saleFilter) . "&province=" . urlencode($provinceFilter) . "&status=" . urlencode($statusFilter);
            header("Location: " . $redirectUrl);
            exit();
        } else {
            echo "Error updating status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "No task found for the given view ID.";
    }

    $stmtGetTaskId->close();
} else {
    echo "Invalid data.";
}

$objConnect->close();
?>
