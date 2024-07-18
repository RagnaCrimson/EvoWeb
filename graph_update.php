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

    // Escape and sanitize all input fields
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

    // Handle P_Month and P_1 to P_12 fields
    $pMonth = $objConnect->real_escape_string($newData['P_Month']);
    $p1 = $objConnect->real_escape_string($newData['P_1']);
    $p2 = $objConnect->real_escape_string($newData['P_2']);
    $p3 = $objConnect->real_escape_string($newData['P_3']);
    $p4 = $objConnect->real_escape_string($newData['P_4']);
    $p5 = $objConnect->real_escape_string($newData['P_5']);
    $p6 = $objConnect->real_escape_string($newData['P_6']);
    $p7 = $objConnect->real_escape_string($newData['P_7']);
    $p8 = $objConnect->real_escape_string($newData['P_8']);
    $p9 = $objConnect->real_escape_string($newData['P_9']);
    $p10 = $objConnect->real_escape_string($newData['P_10']);
    $p11 = $objConnect->real_escape_string($newData['P_11']);
    $p12 = $objConnect->real_escape_string($newData['P_12']);
    
    // File upload handling
    $uploadOk = 1;
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
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

    // Update query with P_Month and P_1 to P_12
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
                      V_File = '$targetFile',
                      P_Month = '$pMonth',
                      P_1 = '$p1',
                      P_2 = '$p2',
                      P_3 = '$p3',
                      P_4 = '$p4',
                      P_5 = '$p5',
                      P_6 = '$p6',
                      P_7 = '$p7',
                      P_8 = '$p8',
                      P_9 = '$p9',
                      P_10 = '$p10',
                      P_11 = '$p11',
                      P_12 = '$p12'
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
    <title>Edit Graph Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/form.css">
</head>
<body class="bgcolor">
    <?php include 'header.php'; ?>
    <div class="container">
        <h2 class="center">Edit Graph Data</h2>
        <form method="post" enctype="multipart/form-data">      
            <div class="row">
                <div class="field half-width">
                    <label for="P_Month">เดือนที่ส่ง :</label>
                    <input type="number" step="any" class="form-control" id="P_Month" name="data[P_Month]" value="<?php echo htmlspecialchars($row_edit['P_Month']); ?>">                
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <label for="P_1">เดือน 1 :</label>
                    <input type="number" step="any" class="form-control" id="P_1" name="data[P_1]" value="<?php echo htmlspecialchars($row_edit['P_1']); ?>">
                </div>
                <div class="field">
                    <label for="P_2">เดือน 2 :</label>
                    <input type="number" step="any" class="form-control" id="P_2" name="data[P_2]" value="<?php echo htmlspecialchars($row_edit['P_2']); ?>">
                </div>
                <div class="field">
                    <label for="P_3">เดือน 3 :</label>
                    <input type="number" step="any" class="form-control" id="P_3" name="data[P_3]" value="<?php echo htmlspecialchars($row_edit['P_3']); ?>">
                </div>
                <div class="field">
                    <label for="P_4">เดือน 4 :</label>
                    <input type="number" step="any" class="form-control" id="P_4" name="data[P_4]" value="<?php echo htmlspecialchars($row_edit['P_4']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <label for="P_5">เดือน 5 :</label>
                    <input type="number" step="any" class="form-control" id="P_5" name="data[P_5]" value="<?php echo htmlspecialchars($row_edit['P_5']); ?>">
                </div>
                <div class="field">
                    <label for="P_6">เดือน 6 :</label>
                    <input type="number" step="any" class="form-control" id="P_6" name="data[P_6]" value="<?php echo htmlspecialchars($row_edit['P_6']); ?>">
                </div>
                <div class="field">
                    <label for="P_7">เดือน 7 :</label>
                    <input type="number" step="any" class="form-control" id="P_7" name="data[P_7]" value="<?php echo htmlspecialchars($row_edit['P_7']); ?>">
                </div>
                <div class="field">
                    <label for="P_8">เดือน 8 :</label>
                    <input type="number" step="any" class="form-control" id="P_8" name="data[P_8]" value="<?php echo htmlspecialchars($row_edit['P_8']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <label for="P_9">เดือน 9 :</label>
                    <input type="number" step="any" class="form-control" id="P_9" name="data[P_9]" value="<?php echo htmlspecialchars($row_edit['P_9']); ?>">
                </div>
                <div class="field">
                    <label for="P_10">เดือน 10 :</label>
                    <input type="number" step="any" class="form-control" id="P_10" name="data[P_10]" value="<?php echo htmlspecialchars($row_edit['P_10']); ?>">
                </div>
                <div class="field">
                    <label for="P_11">เดือน 11 :</label>
                    <input type="number" step="any" class="form-control" id="P_11" name="data[P_11]" value="<?php echo htmlspecialchars($row_edit['P_11']); ?>">
                </div>
                <div class="field">
                    <label for="P_12">เดือน 12 :</label>
                    <input type="number" step="any" class="form-control" id="P_12" name="data[P_12]" value="<?php echo htmlspecialchars($row_edit['P_12']); ?>">
                </div>
            </div>
            <div class="row center">
                <button type="submit" name="submit" class="btn btn-success">Submit</button>
                <button class="btn btn-default"><a href="graph.php?id=<?php echo urlencode($id); ?>">Cancel</a></button>
            </div>
        </form>
    </div>
</body>
</html>
