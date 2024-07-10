<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$view_id = isset($_GET['view_id']) ? intval($_GET['view_id']) : 0;

if ($view_id < 2) {
    die("Invalid ID provided.");
}

$sql_view = "SELECT V_Name, V_Sale, V_comment FROM view WHERE V_ID = $view_id";
$result_view = $conn->query($sql_view);

if ($result_view->num_rows == 0) {
    die("No record found in view table for ID $view_id");
}

$view = $result_view->fetch_assoc();

$sql_task = "SELECT T_Status FROM task WHERE T_ID = $view_id";
$result_task = $conn->query($sql_task);

if ($result_task->num_rows == 0) {
    die("No record found in task table for ID $view_id");
}

$task = $result_task->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bgcolor">
    <?php include 'header.php'; ?>

    <div class="container">
        <div><h1>อัพเดตสถานะ</h1></div>
        <form method="post" action="update_data.php">
            <div class="card-body">
            <input type="hidden" name="view_id" value="<?php echo $view_id; ?>">
            <p>
                <label for="V_Name">ชื่อหน่วยงาน:</label>
                <input type="text" id="V_Name" name="V_Name" value="<?php echo htmlspecialchars($view['V_Name']); ?>" disabled>
            </p>
            <p>
                <label for="V_Sale">ทีมฝ่ายขาย:</label>
                <input type="text" id="V_Sale" name="V_Sale" value="<?php echo htmlspecialchars($view['V_Sale']); ?>" disabled>
            </p>
            <p>
                <label for="V_comment">หมายเหตุ :</label>
                <input type="text" class="form-control" id="V_comment" name="V_comment"  value="<?php echo htmlspecialchars($view['V_comment']); ?>">
            </p>
            <p>
                <label for="T_Status">สถานะ :</label>
                    <select id="T_Status" name="T_Status" class="form-control" required>
                        <option value="">-- เลือกสถานะ --</option>
                        <option value="นำส่งการไฟฟ้า" <?php if ($task['T_Status'] === 'นำส่งการไฟฟ้า') echo 'selected'; ?>>นำส่งการไฟฟ้า</option>
                        <option value="ตอบรับ" <?php if ($task['T_Status'] === 'ตอบรับ') echo 'selected'; ?>>ตอบรับ</option>
                        <option value="ส่งมอบงาน" <?php if ($task['T_Status'] === 'ส่งมอบงาน') echo 'selected'; ?>>ส่งมอบงาน</option>
                        <option value="ไม่ผ่าน" <?php if ($task['T_Status'] === 'ไม่ผ่าน') echo 'selected'; ?>>ไม่ผ่าน</option>
                    </select>
            </p>
                <div class="center">
                    <input type="submit" class="btn btn-info btn-lg" value="Update">
                    <button class="btn btn-default btn-lg"><a href="status_view.php">Cancel</a></button>
                </div>   
            </div>
        </form>
</body>
</html>
