<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $V_Name = $_POST['V_Name'];
    $V_Province = $_POST['V_Province'];
    $V_District = $_POST['V_District'];
    $V_SubDistrict = $_POST['V_SubDistrict'];
    $V_ExecName = $_POST['V_ExecName'];
    $V_ExecPhone = $_POST['V_ExecPhone'];
    $V_ExecMail = $_POST['V_ExecMail'];
    $V_CoordName = $_POST['V_CoordName'];
    $V_CoordPhone = $_POST['V_CoordPhone'];
    $V_CoordMail = $_POST['V_CoordMail'];
    $V_Sale = $_POST['V_Sale'];
    $V_Date = $_POST['V_Date'];
    $V_Electric_per_year = $_POST['V_Electric_per_year'];
    $V_Electric_per_month = $_POST['V_Electric_per_month'];
    $V_Comment = $_POST['V_Comment'];
    $V_File = $_FILES['V_File']['name'];
    $T_Status = $_POST['T_Status'];

    if (!empty($V_File)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($V_File);
        if (!move_uploaded_file($_FILES['V_File']['tmp_name'], $target_file)) {
            die("Error uploading file");
        }
    }

    $stmt = $objConnect->prepare("UPDATE view_info SET 
        V_Name=?, 
        V_Province=?, 
        V_District=?, 
        V_SubDistrict=?, 
        V_ExecName=?, 
        V_ExecPhone=?, 
        V_ExecMail=?, 
        V_CoordName=?, 
        V_CoordPhone=?, 
        V_CoordMail=?, 
        V_Sale=?, 
        V_Date=?, 
        V_Electric_per_year=?, 
        V_Electric_per_month=?, 
        V_Comment=?, 
        V_File=?, 
        T_Status=? 
        WHERE V_Name=?");

    $stmt->bind_param("ssssssssssssssssss", $V_Name, $V_Province, $V_District, $V_SubDistrict, $V_ExecName, $V_ExecPhone, $V_ExecMail, $V_CoordName, $V_CoordPhone, $V_CoordMail, $V_Sale, $V_Date, $V_Electric_per_year, $V_Electric_per_month, $V_Comment, $V_File, $T_Status, $V_Name);

    if ($stmt->execute()) {
        echo "Record updated successfully";
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $objConnect->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Dashbord Admin</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container tabcontent">
        <h1>Edit Data</h1>
        <form action="edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo isset($row['V_Name']) ? $row['V_Name'] : ''; ?>">
            <div class="form-group">
                <label for="V_Name">ชื่อหน่วยงาน:</label>
                <input type="text" class="form-control" id="V_Name" name="V_Name" value="<?php echo isset($row['V_Name']) ? $row['V_Name'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="V_Province">จังหวัด:</label>
                <input type="text" class="form-control" id="V_Province" name="V_Province" value="<?php echo isset($row['V_Province']) ? $row['V_Province'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_District">อำเภอ:</label>
                <input type="text" class="form-control" id="V_District" name="V_District" value="<?php echo isset($row['V_District']) ? $row['V_District'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_SubDistrict">ตำบล:</label>
                <input type="text" class="form-control" id="V_SubDistrict" name="V_SubDistrict" value="<?php echo isset($row['V_SubDistrict']) ? $row['V_SubDistrict'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_ExecName">ชื่อผู้บริหาร:</label>
                <input type="text" class="form-control" id="V_ExecName" name="V_ExecName" value="<?php echo isset($row['V_ExecName']) ? $row['V_ExecName'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_ExecPhone">เบอร์โทรผู้บริหาร:</label>
                <input type="text" class="form-control" id="V_ExecPhone" name="V_ExecPhone" value="<?php echo isset($row['V_ExecPhone']) ? $row['V_ExecPhone'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_ExecMail">E-mail ผู้บริหาร:</label>
                <input type="email" class="form-control" id="V_ExecMail" name="V_ExecMail" value="<?php echo isset($row['V_ExecMail']) ? $row['V_ExecMail'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_CoordName">ชื่อผู้ประสานงาน:</label>
                <input type="text" class="form-control" id="V_CoordName" name="V_CoordName" value="<?php echo isset($row['V_CoordName']) ? $row['V_CoordName'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_CoordPhone">เบอร์โทรผู้ประสานงาน:</label>
                <input type="text" class="form-control" id="V_CoordPhone" name="V_CoordPhone" value="<?php echo isset($row['V_CoordPhone']) ? $row['V_CoordPhone'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_CoordMail">E-mail ผู้ประสานงาน:</label>
                <input type="email" class="form-control" id="V_CoordMail" name="V_CoordMail" value="<?php echo isset($row['V_CoordMail']) ? $row['V_CoordMail'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_Sale">ทีมฝ่ายขาย:</label>
                <input type="text" class="form-control" id="V_Sale" name="V_Sale" value="<?php echo isset($row['V_Sale']) ? $row['V_Sale'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_Date">วันที่รับเอกสาร:</label>
                <input type="date" class="form-control" id="V_Date" name="V_Date" value="<?php echo isset($row['V_Date']) ? $row['V_Date'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_Electric_per_year">การใช้ไฟ/ปี:</label>
                <input type="number" class="form-control" id="V_Electric_per_year" name="V_Electric_per_year" value="<?php echo isset($row['V_Electric_per_year']) ? $row['V_Electric_per_year'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_Electric_per_month">การใช้ไฟ/เดือน:</label>
                <input type="number" class="form-control" id="V_Electric_per_month" name="V_Electric_per_month" value="<?php echo isset($row['V_Electric_per_month']) ? $row['V_Electric_per_month'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="V_Comment">ความคิดเห็น:</label>
                <textarea class="form-control" id="V_Comment" name="V_Comment"><?php echo isset($row['V_Comment']) ? $row['V_Comment'] : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="V_File">ไฟล์:</label>
                <input type="file" class="form-control" id="V_File" name="V_File">
            </div>
            <div class="form-group">
                <label for="T_Status">สถานะ:</label>
                <input type="text" class="form-control" id="T_Status" name="T_Status" value="<?php echo isset($row['T_Status']) ? $row['T_Status'] : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
