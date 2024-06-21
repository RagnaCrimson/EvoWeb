<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "datastore_db";

$objConnect = new mysqli($server, $username, $password, $database);

if ($objConnect->connect_error) {
    die("Connection failed: " . $objConnect->connect_error);
}

mysqli_query($objConnect, "SET NAMES utf8");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $strSQL_edit = "SELECT view.*, task.T_Status FROM view
                    LEFT JOIN task ON view.V_Name = task.T_ID
                    WHERE view.V_Name = '$id'";
    $result_edit = $objConnect->query($strSQL_edit);
    
    if (!$result_edit) {
        die("Query failed: " . $objConnect->error);
    }
    
    $row_edit = $result_edit->fetch_assoc();
} else {
    echo "No ID specified.";
    exit;
}

if(isset($_POST['submit'])) {
    $newData = $_POST['data']; 

    $vComment = $objConnect->real_escape_string($newData['V_comment']);
    $tStatus = $objConnect->real_escape_string($newData['T_Status']);
    
    $strSQL_update = "UPDATE view
                      LEFT JOIN task ON view.V_Name = task.T_ID
                      SET view.V_comment = '$vComment', task.T_Status = '$tStatus'
                      WHERE view.V_Name = '$id'";

    if ($objConnect->query($strSQL_update) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $objConnect->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/card_style.css">
</head>
<body class="bgcolor">
    <?php include 'header.php'; ?>
    <div class="container">
        <h2 class="center">Edit Data</h2>
        <form method="post">      
            <div class="card-body">
                <div class="left">
                    <div class="form-group">
                        <label for="name">ชื่อหน่วยงาน :</label>
                        <p class="form-control-static"><?php echo htmlspecialchars($row_edit['V_Name']); ?></p>
                    </div>
                    <div class="form-group">
                        <label for="comment">หมายเหตุ :</label>
                        <input type="text" class="form-control" id="Ecomment" name="data[V_comment]" value="<?php echo $row_edit['V_comment']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="T_Status">สถานะ :</label>
                        <select id="T_Status" name="data[T_Status]" class="form-control" required>
                            <option value="">-- เลือกสถานะ --</option>
                            <option value="นำส่งการไฟฟ้า" <?php if ($row_edit['T_Status'] === 'นำส่งการไฟฟ้า') echo 'selected'; ?>>นำส่งการไฟฟ้า</option>
                            <option value="ตอบรับ" <?php if ($row_edit['T_Status'] === 'ตอบรับ') echo 'selected'; ?>>ตอบรับ</option>
                            <option value="ส่งมอบงาน" <?php if ($row_edit['T_Status'] === 'ส่งมอบงาน') echo 'selected'; ?>>ส่งมอบงาน</option>
                            <option value="ไม่ผ่าน" <?php if ($row_edit['T_Status'] === 'ไม่ผ่าน') echo 'selected'; ?>>ไม่ผ่าน</option>
                        </select>
                    </div>            
                </div>
            </div>
            <div class="center">
                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                <a href="data_view.php" class="btn btn-default">Back</a>
            </div>
        </form>
        <?php include 'back.html'; ?>
    </div>
</body>
</html>
