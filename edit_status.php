<?php
include 'connect.php';

$id = '';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $strSQL_edit = "SELECT * FROM view WHERE V_Name = '$id'";
    $result_edit = $objConnect->query($strSQL_edit);
    
    if (!$result_edit) {
        die("Query failed: " . $objConnect->error);
    }
    
    $row_edit = $result_edit->fetch_assoc();
} else {
    echo "No ID specified.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $newData = $_POST['data'];
    $vComment = $objConnect->real_escape_string($newData['V_comment']);

    $strSQL_update_view = "UPDATE view SET 
                      V_comment = '$vComment'
                      WHERE V_Name = '$id'";

    if ($objConnect->query($strSQL_update_view) === TRUE) {
        echo "Record in view table updated successfully<br>";
    } else {
        echo "Error updating record in view table: " . $objConnect->error . "<br>";
    }

    $statusArray = isset($newData['T_Status']) ? $newData['T_Status'] : array();

    foreach ($statusArray as $status) {
        $status = $objConnect->real_escape_string($status);

        $strSQL_update_other = "UPDATE task SET 
                          status = '$status'
                          WHERE V_Name = '$id'";

        if ($objConnect->query($strSQL_update_other) === TRUE) {
            echo "Record in task updated successfully for status: $status<br>";
        } else {
            echo "Error updating record in task for status: $status - " . $objConnect->error . "<br>";
        }
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
                    <!-- Other form fields -->
                    <div class="form-group">
                        <label for="comment">หมายเหตุ :</label>
                        <input type="text" class="form-control" id="Ecomment" name="data[V_comment]" value="<?php echo htmlspecialchars($row_edit['V_comment']); ?>">
                    </div>
                    <div class="form-group">
                        <label>สถานะ :</label><br>
                        <input type="checkbox" id="status1" name="data[T_Status][]" value="นำส่งการไฟฟ้า">
                        <label for="status1">นำส่งการไฟฟ้า</label><br>
                        <input type="checkbox" id="status2" name="data[T_Status][]" value="ตอบรับ">
                        <label for="status2">ตอบรับ</label><br>
                        <input type="checkbox" id="status3" name="data[T_Status][]" value="ส่งมอบงาน">
                        <label for="status3">ส่งมอบงาน</label><br>
                        <input type="checkbox" id="status4" name="data[T_Status][]" value="ไม่ผ่าน">
                        <label for="status4">ไม่ผ่าน</label><br>
                    </div>   
                </div>
            </div>
            <div class="center">
                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                <a href="status_view.php" class="btn btn-default">Back</a>
            </div>
        </form>
        <?php include 'back.html'; ?>
    </div>
</body>
</html>

