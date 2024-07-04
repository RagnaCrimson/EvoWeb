<?php
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $required_fields = ['V_Name', 'V_Province', 'V_ExecName', 'V_ExecPhone', 'V_ExecMail', 'V_CoordName1', 'V_CoordPhone1', 'V_CoordMail1', 'V_Sale', 'V_Date', 'V_Electric_per_year', 'V_Electric_per_month', 'V_comment', 'T_Status'];
    $missing_fields = array_diff($required_fields, array_keys($_POST));

    if (!empty($missing_fields)) {
        die("Please fill in all required fields: " . implode(', ', $missing_fields));
    }

    // Handle form fields
    $name = htmlspecialchars($_POST['V_Name']);
    $province = htmlspecialchars($_POST['V_Province']);
    $district = htmlspecialchars($_POST['V_District']);
    $sub_district = htmlspecialchars($_POST['V_SubDistrict']);
    $exec_name = htmlspecialchars($_POST['V_ExecName']);
    $exec_phone = htmlspecialchars($_POST['V_ExecPhone']);
    $exec_mail = htmlspecialchars($_POST['V_ExecMail']);
    $coord_name1 = htmlspecialchars($_POST['V_CoordName1']);
    $coord_phone1 = htmlspecialchars($_POST['V_CoordPhone1']);
    $coord_mail1 = htmlspecialchars($_POST['V_CoordMail1']);
    $coord_name2 = htmlspecialchars($_POST['V_CoordName2']);
    $coord_phone2 = htmlspecialchars($_POST['V_CoordPhone2']);
    $coord_mail2 = htmlspecialchars($_POST['V_CoordMail2']);
    $sale = htmlspecialchars($_POST['V_Sale']);
    $date = htmlspecialchars($_POST['V_Date']);
    $electric_per_year = htmlspecialchars($_POST['V_Electric_per_year']);
    $electric_per_month = htmlspecialchars($_POST['V_Electric_per_month']);
    $comment = htmlspecialchars($_POST['V_comment']);
    $status = htmlspecialchars($_POST['T_Status']);

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file is a PDF
    if($fileType != "pdf") {
        echo "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO view (V_Name, V_Province, V_District, V_SubDistrict, V_ExecName, V_ExecPhone, V_ExecMail, V_CoordName1, V_CoordPhone1, V_CoordMail1, V_CoordName2, V_CoordPhone2, V_CoordMail2, V_Sale, V_Date, V_Electric_per_year, V_Electric_per_month, V_comment, T_Status, file_path) 
            VALUES ('$name', '$province', '$district', '$sub_district', '$exec_name', '$exec_phone', '$exec_mail', '$coord_name1', '$coord_phone1', '$coord_mail1', '$coord_name2', '$coord_phone2', '$coord_mail2', '$sale', '$date', '$electric_per_year', '$electric_per_month', '$comment', '$status', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard Admin</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/card_style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="js/script.js"></script>
</head>

<body class="bgcolor">
    <?php include 'header.php'; ?>

    <div class="center">
      <h1 style="margin-top: 50px; margin-bottom: 50px;">เพิ่มข้อมูล</h1>
    </div>

    <div class="container">
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <div class="card-body">

                <div class="left">
                    <label for="V_Name">ชื่อหน่วยงาน :</label>
                    <input type="text" id="V_Name" name="V_Name" required><br><br>

                    <label for="V_Province">จังหวัด :</label>
                    <input type="text" id="V_Province" name="V_Province" required><br><br>

                    <label for="V_District">อำเภอ:</label>
                    <input type="text" id="V_District" name="V_District"><br><br>

                    <label for="V_SubDistrict">ตำบล :</label>
                    <input type="text" id="V_SubDistrict" name="V_SubDistrict"><br><br>

                    <label for="V_ExecName">ชื่อผู้บริหาร :</label>
                    <input type="text" id="V_ExecName" name="V_ExecName"><br><br>

                    <label for="V_ExecPhone">เบอร์ผู้บริหาร :</label>
                    <input type="text" id="V_ExecPhone" name="V_ExecPhone"><br><br>

                    <label for="V_ExecMail">อีเมลผู้บริหาร :</label>
                    <input type="text" id="V_ExecMail" name="V_ExecMail"><br><br>

                    <label for="V_CoordName1">ชื่อผู้ประสานงาน 1 :</label>
                    <input type="text" id="V_CoordName1" name="V_CoordName1"><br><br>

                    <label for="V_CoordPhone1">เบอร์ผู้ประสานงาน 1 :</label>
                    <input type="text" id="V_CoordPhone1" name="V_CoordPhone1"><br><br>

                    <label for="V_CoordMail1">อีเมลผู้ประสานงาน 1 :</label>
                    <input type="text" id="V_CoordMail1" name="V_CoordMail1"><br><br>
                </div>
                
                <div class="right">
                    <label for="V_CoordName2">ชื่อผู้ประสานงาน 2 :</label>
                    <input type="text" id="V_CoordName2" name="V_CoordName2"><br><br>

                    <label for="V_CoordPhone2">เบอร์ผู้ประสานงาน 2 :</label>
                    <input type="text" id="V_CoordPhone2" name="V_CoordPhone2"><br><br>

                    <label for="V_CoordMail2">อีเมลผู้ประสานงาน 2 :</label>
                    <input type="text" id="V_CoordMail2" name="V_CoordMail2"><br><br>

                    <label for="V_Sale">ทีมฝ่ายขาย :</label>
                    <input type="text" id="V_Sale" name="V_Sale"><br><br>

                    <label for="V_Date">วันที่ได้รับเอกสาร :</label>
                    <input type="date" id="V_Date" name="V_Date"><br><br>

                    <label for="V_Electric_per_year">ค่าใช้ไฟฟ้าต่อปี :</label>
                    <input type="number" step="any" placeholder="000.00" id="V_Electric_per_year" name="V_Electric_per_year"><br><br>

                    <label for="V_Electric_per_month">ค่าใช้ไฟฟ้าต่อเดือน :</label>
                    <input type="number" step="any" placeholder="000.00" id="V_Electric_per_month" name="V_Electric_per_month"><br><br>

                    <label for="V_Peak_year">ค่าใช้ไฟฟ้าต่อปี :</label>
                    <input type="number" step="any" placeholder="000.00" id="V_Peak_year" name="V_Peak_year"><br><br>

                    <label for="V_Peak_month">ค่าใช้ไฟฟ้าต่อเดือน :</label>
                    <input type="number" step="any" placeholder="000.00" id="V_Peak_month" name="V_Peak_month"><br><br>

                    <label for="V_comment">หมายเหตุ :</label>
                    <input type="text" id="V_comment" name="V_comment"><br><br>

                    <label for="T_Status">สถานะ :</label>
                    <select id="T_Status" name="T_Status" class="form-control" required>
                        <option value="">-- เลือกสถานะ --</option>
                        <option value="นำส่งการไฟฟ้า">นำส่งการไฟฟ้า</option>
                        <option value="ตอบรับ">ตอบรับ</option>
                        <option value="ส่งมอบงาน">ส่งมอบงาน</option>
                        <option value="ไม่ผ่าน">ไม่ผ่าน</option>
                    </select><br><br>

                    <div class="mb-3">
                        <label for="file" class="form-label">Select file</label>
                        <input type="file" class="form-control" name="file" id="file" required>
                    </div>

                    <div class="center">
                        <button type="submit" class="btn btn-primary btn-lg">เพิ่มข้อมูล</button>
                        <button type="button" class="btn btn-primary btn-lg">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php include 'back.html'; ?>
</body>
</html>
