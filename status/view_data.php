<?php
include '../connect.php';

$viewId = isset($_POST['view_id']) ? intval($_POST['view_id']) : 0;

if ($viewId > 0) {
    // Fetch data for the view
    $strSQL = "
        SELECT view.*, 
               (SELECT T_Status 
                FROM task 
                WHERE V_ID = view.V_ID 
                ORDER BY T_Date DESC 
                LIMIT 1) AS T_Status,
               (SELECT T_Status2 
                FROM task 
                WHERE V_ID = view.V_ID 
                ORDER BY T_Date DESC 
                LIMIT 1) AS T_Status2,
               (SELECT T_Date2 
                FROM task 
                WHERE V_ID = view.V_ID 
                ORDER BY T_Date DESC 
                LIMIT 1) AS T_Date2
        FROM view
        WHERE view.V_ID = ?";
    $stmt = $objConnect->prepare($strSQL);
    if ($stmt === false) {
        die("Error preparing the SQL statement: " . $objConnect->error);
    }
    $stmt->bind_param("i", $viewId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "<h3>ชื่อหน่วยงาน: " . htmlspecialchars($row['V_Name']) . "</h3>";
        echo "<p>จังหวัด : " . htmlspecialchars($row['V_Province']) . "</p>";
        echo "<p>ทีมฝ่ายขาย : " . htmlspecialchars($row['V_Sale']) . "</p>";
        echo "<h4><b>Status: " . htmlspecialchars($row['T_Status']) . "</b></h4>";
        echo "<p>Date: " . htmlspecialchars($row['V_Date']) . "</p>";
        echo "<h4><b>Status-2: " . htmlspecialchars($row['T_Status2']) . "</b></h4>";
        echo "<p>Date-2: " . htmlspecialchars($row['T_Date2']) . "</p>";

        // Form to update T_Status2
        ?>
        <form method="POST" action="status/update_task.php">
            <input type="hidden" name="view_id" value="<?php echo htmlspecialchars($viewId); ?>">
            <div class="form-group">
                <label for="status2">Update Status:</label>
                <select id="status2" name="status2" class="form-control" required>
                    <option value="">เลือกสถานะ</option>
                    <option value="ออกแบบ">ออกแบบ</option>
                    <option value="สำรวจ">สำรวจ</option>
                    <option value="ไม่ผ่าน">ไม่ผ่าน</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
        </form>
        <?php
    } else {
        echo "No data found.";
    }
    $stmt->close();
} else {
    echo "Invalid ID.";
}

$objConnect->close();
?>
