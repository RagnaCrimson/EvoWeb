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

if(isset($_POST['submit'])) {
    $newData = $_POST['data']; 

    $vName = $objConnect->real_escape_string($newData['V_Name']);
    $vProvince = $objConnect->real_escape_string($newData['V_Province']);
    $vDistrict = $objConnect->real_escape_string($newData['V_District']);
    $vSubDistrict = $objConnect->real_escape_string($newData['V_SubDistrict']);
    $vExecName = $objConnect->real_escape_string($newData['V_ExecName']);
    $vExecPhone = $objConnect->real_escape_string($newData['V_ExecPhone']);
    $vExecMail = $objConnect->real_escape_string($newData['V_ExecMail']);
    $vCoordName1 = $objConnect->real_escape_string($newData['V_CoordName1']);
    $vCoordPhone1 = $objConnect->real_escape_string($newData['V_CoordPhone1']);
    $vCoordMail1 = $objConnect->real_escape_string($newData['V_CoordMail1']);
    $vCoordName2 = $objConnect->real_escape_string($newData['V_CoordName2']);
    $vCoordPhone2 = $objConnect->real_escape_string($newData['V_CoordPhone2']);
    $vCoordMail2 = $objConnect->real_escape_string($newData['V_CoordMail2']);
    $vSale = $objConnect->real_escape_string($newData['V_Sale']);
    $vDate = $objConnect->real_escape_string($newData['V_Date']);
    $vElectricPerYear = $objConnect->real_escape_string($newData['V_Electric_per_year']);
    $vElectricPerMonth = $objConnect->real_escape_string($newData['V_Electric_per_month']);
    $vComment = $objConnect->real_escape_string($newData['V_comment']);
    
    $strSQL_update = "UPDATE view SET 
                      V_Name = '$vName',
                      V_Province = '$vProvince',
                      V_District = '$vDistrict',
                      V_SubDistrict = '$vSubDistrict',
                      V_ExecName = '$vExecName',
                      V_ExecPhone = '$vExecPhone',
                      V_ExecMail = '$vExecMail',
                      V_CoordName1 = '$vCoordName1',
                      V_CoordPhone1 = '$vCoordPhone1',
                      V_CoordMail1 = '$vCoordMail1',
                      V_CoordName2 = '$vCoordName2',
                      V_CoordPhone2 = '$vCoordPhone2',
                      V_CoordMail2 = '$vCoordMail2',
                      V_Sale = '$vSale',
                      V_Date = '$vDate',
                      V_Electric_per_year = '$vElectricPerYear',
                      V_Electric_per_month = '$vElectricPerMonth',
                      V_comment = '$vComment'
                      WHERE V_Name = '$id'";

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
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h2>Edit Data</h2>
        <form method="post">

        <div class="left">
            <div class="form-group">
                <label for="name">ชื่อหน่วยงาน :</label>
                <input type="text" class="form-control" id="name" name="data[V_Name]" value="<?php echo $row_edit['V_Name']; ?>">
            </div>
            <div class="form-group">
                <label for="province">จังหวัด :</label>
                <input type="text" class="form-control" id="province" name="data[V_Province]" value="<?php echo $row_edit['V_Province']; ?>">
            </div>
            <div class="form-group">
                <label for="distric">ตำบล :</label>
                <input type="text" class="form-control" id="distric" name="data[V_District]" value="<?php echo $row_edit['V_District']; ?>">
            </div>
            <div class="form-group">
                <label for="V_SubDistrict">อำเภอ :</label>
                <input type="text" class="form-control" id="province" name="data[V_SubDistrict]" value="<?php echo $row_edit['V_SubDistrict']; ?>">
            </div>
            <div class="form-group">
                <label for="ExecName">ชื่อผู้บริหาร :</label>
                <input type="text" class="form-control" id="ExecName" name="data[V_ExecName]" value="<?php echo $row_edit['V_ExecName']; ?>">
            </div>
            <div class="form-group">
                <label for="ExecPhone">เบอร์ผู้บริหาร :</label>
                <input type="text" class="form-control" id="ExecPhone" name="data[V_ExecPhone]" value="<?php echo $row_edit['V_ExecPhone']; ?>">
            </div>
            <div class="form-group">
                <label for="ExecMail">อีเมลผู้บริหาร :</label>
                <input type="text" class="form-control" id="ExecMail" name="data[V_ExecMail]" value="<?php echo $row_edit['V_ExecMail']; ?>">
            </div>
            <div class="form-group">
                <label for="CoordName1">ชื่อผู้ประสานงาน 1 :</label>
                <input type="text" class="form-control" id="CoordName1" name="data[V_CoordName1]" value="<?php echo $row_edit['V_CoordName1']; ?>">
            </div>
            <div class="form-group">
                <label for="CoordPhone1">เบอร์โทรผู้ประสานงาน 1 :</label>
                <input type="text" class="form-control" id="CoordPhone1" name="data[V_CoordPhone1]" value="<?php echo $row_edit['V_CoordPhone1']; ?>">
            </div>
            <div class="form-group">
                <label for="CoordMail1">อีเมลผู้ประสานงาน 1 :</label>
                <input type="text" class="form-control" id="CoordMail1" name="data[V_CoordMail1]" value="<?php echo $row_edit['V_CoordMail1']; ?>">
            </div>

        <div class="right">
            <div class="form-group">
                <label for="CoordName2">ชื่อผู้ประสานงาน 2 :</label>
                <input type="text" class="form-control" id="CoordName2" name="data[V_CoordName2]" value="<?php echo $row_edit['V_CoordName2']; ?>">
            </div>
            <div class="form-group">
                <label for="CoordPhone2">เบอร์ผู้ประสานงาน 2 :</label>
                <input type="text" class="form-control" id="CoordPhone2" name="data[V_CoordPhone2]" value="<?php echo $row_edit['V_CoordPhone2']; ?>">
            </div>
            <div class="form-group">
                <label for="CoordMail2">อีเมลผู้ประสานงาน 2 :</label>
                <input type="text" class="form-control" id="CoordMail2" name="data[V_CoordMail2]" value="<?php echo $row_edit['V_CoordMail2']; ?>">
            </div>
            <div class="form-group">
                <label for="sale">ทีมฝ่ายขาย :</label>
                <input type="text" class="form-control" id="sale" name="data[V_Sale]" value="<?php echo $row_edit['V_Sale']; ?>">
            </div>
            <div class="form-group">
                <label for="Date">วันที่ได้รับเอกสาร :</label>
                <input type="text" class="form-control" id="Date" name="data[V_Date]" value="<?php echo $row_edit['V_Date']; ?>">
            </div>
            <div class="form-group">
                <label for="Electric_per_year">ค่าใช้ไฟฟ้าต่อปี :</label>
                <input type="number" step="any" class="form-control" id="Electric_per_year" name="data[V_Electric_per_year]" value="<?php echo $row_edit['V_Electric_per_year']; ?>">
            </div>
            <div class="form-group">
                <label for="Electric_per_month">ค่าใช้ไฟฟ้าต่อเดือน :</label>
                <input type="number" step="any" class="form-control" id="Electric_per_month" name="data[V_Electric_per_month]" value="<?php echo $row_edit['V_Electric_per_month']; ?>">
            </div>
            <div class="form-group">
                <label for="comment">หมายเหตุ :</label>
                <input type="text" class="form-control" id="Ecomment" name="data[V_comment]" value="<?php echo $row_edit['V_comment']; ?>">
            </div>
        </div>

            <button type="submit" name="submit" class="btn btn-primary">Update</button>
            <a href="data_view.php" class="btn btn-default">Back</a>
        </form>
        <?php include 'back.html'; ?>
    </div>
</body>
</html>
