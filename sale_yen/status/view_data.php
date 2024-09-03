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
           (SELECT T_Date
            FROM task 
            WHERE V_ID = view.V_ID 
            ORDER BY T_Date DESC 
            LIMIT 1) AS T_Date
    FROM view
    WHERE view.V_ID = $viewId";

    $result = $objConnect->query($strSQL);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "<h3>ชื่อหน่วยงาน: " . htmlspecialchars($row['V_Name']) . "</h3>";
        echo "<p>จังหวัด : " . htmlspecialchars($row['V_Province']) . "</p>";
        echo "<p>ทีมฝ่ายขาย : " . htmlspecialchars($row['V_Sale']) . "</p>";
        echo "<h4><b>Status for V_ID " . htmlspecialchars($row['V_ID']) . ": " . htmlspecialchars($row['T_Status']) . "</b></h4>";
        echo "<p>Date-: " . htmlspecialchars($row['T_Date']) . "</p>";

        // Form to update T_Status
        ?>
        <form method="POST" action="status/update_task.php">
            <input type="hidden" name="view_id" value="<?php echo htmlspecialchars($viewId); ?>">
            <input type="hidden" name="sale" value="<?php echo isset($_GET['sale']) ? htmlspecialchars($_GET['sale']) : ''; ?>">
            <input type="hidden" name="province" value="<?php echo isset($_GET['province']) ? htmlspecialchars($_GET['province']) : ''; ?>">
            <input type="hidden" name="status_filter" value="<?php echo isset($_GET['status']) ? htmlspecialchars($_GET['status']) : ''; ?>">
            <div class="form-group">
                <label for="status">Update Status:</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="">เลือกสถานะ</option>
                    <option value="นำส่งการไฟฟ้า">นำส่งการไฟฟ้า</option>
                    <option value="แก้ไขเอกสาร">แก้ไขเอกสาร</option>
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
} else {
    echo "Invalid ID.";
}

$objConnect->close();
?>
