<?php
include 'connect.php'; 

mysqli_query($objConnect, "SET NAMES utf8");

if (isset($_GET['id'])) {
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

if (isset($_POST['submit'])) {
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
    $vPeakYear = $objConnect->real_escape_string($newData['V_Peak_year']);
    $vPeakMonth = $objConnect->real_escape_string($newData['V_Peak_month']);
    $vComment = $objConnect->real_escape_string($newData['V_comment']);
    
    $uploadOk = 1;
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
    
    if($fileType != "pdf") {
        echo "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }
    
    if ($_FILES["file"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";

    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

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
                      V_Peak_year = '$vPeakYear',
                      V_Peak_month = '$vPeakMonth',
                      V_comment = '$vComment',
                      V_File = '$targetFile'
                      WHERE V_Name = '$id'";

    if ($objConnect->query($strSQL_update) === TRUE) {
        echo '<script>
            alert("Record updated successfully.");
            window.location.href = "data_view.php"; // Redirect to data_view.php
        </script>';
        exit;
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
    <link rel="stylesheet" href="css/form.css">
</head>
<body class="bgcolor">
    <?php include 'header.php'; ?>
    <div class="container">
        <h2 class="center">Edit Data</h2>
        <form method="post" enctype="multipart/form-data">      
            <div class="row">
                <div class="field">
                    <label for="name">ชื่อหน่วยงาน :</label>
                    <input type="text" class="form-control" id="name" name="data[V_Name]" value="<?php echo htmlspecialchars($row_edit['V_Name']); ?>">
                </div>
                <div class="field">
                    <label for="province">จังหวัด :</label>
                    <input type="text" class="form-control" id="province" name="data[V_Province]" value="<?php echo htmlspecialchars($row_edit['V_Province']); ?>">
                </div>
                <div class="field">
                    <label for="district">ตำบล :</label>
                    <input type="text" class="form-control" id="district" name="data[V_District]" value="<?php echo htmlspecialchars($row_edit['V_District']); ?>">
                </div>
                <div class="field">
                    <label for="subdistrict">อำเภอ :</label>
                    <input type="text" class="form-control" id="subdistrict" name="data[V_SubDistrict]" value="<?php echo htmlspecialchars($row_edit['V_SubDistrict']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="field half-width">
                    <label for="ExecName">ชื่อผู้บริหาร :</label>
                    <input type="text" class="form-control" id="ExecName" name="data[V_ExecName]" value="<?php echo htmlspecialchars($row_edit['V_ExecName']); ?>">
                </div>
                <div class="field">
                    <label for="ExecPhone">เบอร์ผู้บริหาร :</label>
                    <input type="text" class="form-control" id="ExecPhone" name="data[V_ExecPhone]" value="<?php echo htmlspecialchars($row_edit['V_ExecPhone']); ?>">
                </div>
                <div class="field">
                    <label for="ExecMail">อีเมลผู้บริหาร :</label>
                    <input type="email" class="form-control" id="ExecMail" name="data[V_ExecMail]" value="<?php echo htmlspecialchars($row_edit['V_ExecMail']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="field half-width">
                    <label for="CoordName1">ชื่อผู้ประสานงาน 1 :</label>
                    <input type="text" class="form-control" id="CoordName1" name="data[V_CoordName1]" value="<?php echo htmlspecialchars($row_edit['V_CoordName1']); ?>">                
                </div>
                <div class="field">
                    <label for="CoordPhone1">เบอร์โทรผู้ประสานงาน 1 :</label>
                    <input type="text" class="form-control" id="CoordPhone1" name="data[V_CoordPhone1]" value="<?php echo htmlspecialchars($row_edit['V_CoordPhone1']); ?>">                
                </div>
                <div class="field">
                    <label for="CoordMail1">อีเมลผู้ประสานงาน 1 :</label>
                    <input type="email" class="form-control" id="CoordMail1" name="data[V_CoordMail1]" value="<?php echo htmlspecialchars($row_edit['V_CoordMail1']); ?>">                
                </div>
            </div>
            <div class="row">
                <div class="field half-width">
                    <label for="CoordName2">ชื่อผู้ประสานงาน 2 :</label>
                    <input type="text" class="form-control" id="CoordName2" name="data[V_CoordName2]" value="<?php echo htmlspecialchars($row_edit['V_CoordName2']); ?>">                
                </div>
                <div class="field">
                    <label for="CoordPhone2">เบอร์โทรผู้ประสานงาน 2 :</label>
                    <input type="text" class="form-control" id="CoordPhone2" name="data[V_CoordPhone2]" value="<?php echo htmlspecialchars($row_edit['V_CoordPhone2']); ?>">                
                </div>
                <div class="field">
                    <label for="CoordMail2">อีเมลผู้ประสานงาน 2 :</label>
                    <input type="email" class="form-control" id="CoordMail2" name="data[V_CoordMail2]" value="<?php echo htmlspecialchars($row_edit['V_CoordMail2']); ?>">                
                </div>
            </div>
            <div class="row">
                <div class="field half-width">
                    <label for="Electric_per_year">ค่าใช้ไฟฟ้าต่อปี :</label>
                    <input type="number" step="any" class="form-control" id="Electric_per_year" name="data[V_Electric_per_year]" value="<?php echo htmlspecialchars($row_edit['V_Electric_per_year']); ?>">                
                </div>
                <div class="field half-width">
                    <label for="Electric_per_month">ค่าใช้ไฟฟ้าต่อเดือน :</label>
                    <input type="number" step="any" class="form-control" id="Electric_per_month" name="data[V_Electric_per_month]" value="<?php echo htmlspecialchars($row_edit['V_Electric_per_month']); ?>">                
                </div>
            </div>
            <div class="row">
                <div class="field half-width">
                    <label for="Peak_year">ค่า PEAK ต่อปี :</label>
                    <input type="number" step="any" class="form-control" id="Peak_year" name="data[V_Peak_year]" value="<?php echo htmlspecialchars($row_edit['V_Peak_year']); ?>">                
                </div>
                <div class="field half-width">
                    <label for="Peak_month">ค่า PEAK ต่อเดือน :</label>
                    <input type="number" step="any" class="form-control" id="Peak_month" name="data[V_Peak_month]" value="<?php echo htmlspecialchars($row_edit['V_Peak_month']); ?>">                
                </div>
            </div>
            <div class="row">
                <div class="field full-width">
                    <label for="comment">หมายเหตุ :</label>
                    <textarea class="form-control" id="comment" name="data[V_comment]"><?php echo htmlspecialchars($row_edit['V_comment']); ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <label for="sale">ทีมฝ่ายขาย :</label>
                    <input type="text" class="form-control" id="sale" name="data[V_Sale]" value="<?php echo htmlspecialchars($row_edit['V_Sale']); ?>">
                </div>
                <div class="field">
                    <label for="date">วันที่ได้รับเอกสาร :</label>
                    <input type="date" class="form-control" id="date" name="data[V_Date]" value="<?php echo htmlspecialchars($row_edit['V_Date']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="field half-width">
                    <label for="file" class="form-label">Current File: <?php echo basename($row_edit['V_File']); ?></label>
                    <input type="file" class="form-control" accept="application/pdf" name="file" id="file">
                </div>
            </div>
            <div class="row center">
                <button type="submit" name="submit" class="btn btn-success">Submit</button>
                <button class="btn btn-default"><a href="data_view.php">Cancel</a></button>
            </div>
        </form>
    </div>
</body>
</html>
