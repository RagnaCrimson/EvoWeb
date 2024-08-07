<?php
include '../connect.php';

$viewId = isset($_POST['view_id']) ? intval($_POST['view_id']) : 0;

if ($viewId > 0) {
    $strSQL = "
        SELECT view.*, 
               (SELECT T_Status 
                FROM task 
                WHERE V_ID = view.V_ID 
                ORDER BY T_Date DESC 
                LIMIT 1) AS T_Status
        FROM view
        WHERE view.V_ID = ?";
    $stmt = $objConnect->prepare($strSQL);
    $stmt->bind_param("i", $viewId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "<h3>ชื่อหน่วยงาน: " . htmlspecialchars($row['V_Name']) . "</h3>";
        echo "<p>จังหวัด : " . htmlspecialchars($row['V_Province']) . "</p>";
        echo "<p>ทีมฝ่ายขาย : " . htmlspecialchars($row['V_Sale']) . "</p>";
        echo "<p><b>Status: " . htmlspecialchars($row['T_Status']) . "</b></p>";
        echo "<p>Date: " . htmlspecialchars($row['V_Date']) . "</p>";
    } else {
        echo "No data found.";
    }
} else {
    echo "Invalid ID.";
}
?>
