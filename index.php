<?php
include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $required_fields = ['V_Name', 'V_Province', 'V_District', 'V_SubDistrict', 'V_ExecName', 'V_ExecPhone', 'V_ExecMail', 'V_CoordName1', 'V_CoordPhone1', 'V_CoordMail1', 'V_CoordName2', 'V_CoordPhone2', 'V_CoordMail2', 'V_Sale', 'V_Date', 'V_Electric_per_year', 'V_Electric_per_month', 'V_comment', 'T_Status'];
    $missing_fields = array_diff($required_fields, array_keys($_POST));

    if (!empty($missing_fields)) {
        die("Some POST variables are missing: " . implode(', ', $missing_fields));
    }

    $name = htmlspecialchars($_POST['V_Name']);
    $province = htmlspecialchars($_POST['V_Province']);
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

    $sql_datastore_db = "INSERT INTO view (V_Name, V_Province, V_District, V_SubDistrict, V_ExecName, V_ExecPhone, V_ExecMail, 
        V_CoordName1, V_CoordPhone1, V_CoordMail1, V_CoordName2, V_CoordPhone2, V_CoordMail2, V_Sale, V_Date, 
        V_Electric_per_year, V_Electric_per_month, V_comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_datastore_db = $objConnect->prepare($sql_datastore_db);
    if ($stmt_datastore_db === false) {
        die("Error preparing statement for datastore_db: " . $objConnect->error);
    }
    $stmt_datastore_db->bind_param("ssssssssssssssssss", $name, $province, $district, $sub_district, $exec_name, $exec_phone, $exec_mail, 
        $coord_name1, $coord_phone1, $coord_mail1, $coord_name2, $coord_phone2, $coord_mail2, $sale, $date, 
        $electric_per_year, $electric_per_month, $comment);

    if ($stmt_datastore_db->execute()) {
        $last_id = $stmt_datastore_db->insert_id;

        $sql_task = "INSERT INTO task (T_Status) VALUES (?)";
        $stmt_task = $objConnect->prepare($sql_task);
        if ($stmt_task === false) {
            die("Error preparing statement for task: " . $objConnect->error);
        }
        $stmt_task->bind_param("s", $status);

        if ($stmt_task->execute()) {
            echo "<script>alert('Data inserted successfully.');</script>"; // Show a JavaScript alert
        } else {
            echo "Error: " . $sql_task . "<br>" . $stmt_task->error;
        }

        $stmt_task->close();
    } else {
        echo "Error: " . $sql_datastore_db . "<br>" . $stmt_datastore_db->error;
    }

    $stmt_datastore_db->close();
    $objConnect->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashbord Admin</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Arvo&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="js/script.js"></script>
</head>
<body>
<!-- ================= Header ===================== -->

  <?php
    include 'header.php';
  ?>

  <div class="center">
    <h1 style="margin-top: 50px; margin-bottom: 50px;">เพิ่มข้อมูล</h1>
  </div>

  <!-- =============== Left side ================== -->
   <div class="container">
    <form action="index.php" method="POST" onsubmit="showSuccessPopup()">
    <div class="con">

      <div class="left">
        <label for="V_Name">ชื่อหน่วยงาน :</label>
        <input type="text" id="V_Name" name="V_Name" require><br><br>

        <label for="V_Province">จังหวัด :</label>
        <input type="text" id="V_Province" name="V_Province" require><br><br>

        <label for="V_District">อำเภอ:</label>
        <input type="text" id="V_District" name="V_District"><br><br>

        <label for="V_SubDistrict">ตำบล :</label>
        <input type="text" id="V_SubDistrict" name="V_SubDistrict"><br><br>

        <label for="V_ExecName">ชื่อผู้บริหาร :</label>
        <input type="text" id="V_ExecName" name="V_ExecName"><br><br>

          <label for="V_ExecPhone">เบอร์ผู้บริหาร :</label>
          <input type="text" id="V_ExecPhone" name="V_ExecPhone"><br><br>

          <label for="V_ExecMail">อีเมลผู้บริหาร:</label>
          <input type="text" id="V_ExecMail" name="V_ExecMail"><br><br>

          <label for="V_CoordName1">ชื่อผู้ประสานงาน 1:</label>
          <input type="text" id="V_CoordName1" name="V_CoordName1"><br><br>

          <label for="V_CoordPhone1">เบอร์ผู้ประสานงาน 1:</label>
          <input type="text" id="V_CoordPhone1" name="V_CoordPhone1"><br><br>

          <label for="V_CoordMail1">อีเมลผู้ประสานงาน 1:</label>
          <input type="text" id="V_CoordMail1" name="V_CoordMail1"><br><br>
      </div>
      
    <!-- ============== Right side ================= -->
    
      <div class="right">
          <label for="V_CoordName2">ชื่อผู้ประสานงาน 2:</label>
          <input type="text" id="V_CoordName2" name="V_CoordName2"><br><br>

          <label for="V_CoordPhone2">เบอร์ผู้ประสานงาน 2:</label>
          <input type="text" id="V_CoordPhone2" name="V_CoordPhone2"><br><br>

          <label for="V_CoordMail2">อีเมลผู้ปนะสานงาน 2:</label>
          <input type="text" id="V_CoordMail2" name="V_CoordMail2"><br><br>

          <label for="V_Sale">ทีมฝ่ายขาย :</label>
          <input type="text" id="V_Sale" name="V_Sale"><br><br>

          <label for="V_Date">วันที่ได้รับเอกสาร :</label>
          <input type="text" id="V_Date" name="V_Date"><br><br>

          <label for="V_Electric_per_year">ค่าใช้ไฟฟ้าต่อปี :</label>
          <input type="text" id="V_Electric_per_year" name="V_Electric_per_year"><br><br>

          <label for="V_Electric_per_month">ค่าใช้ไฟฟ้าต่อเดือน :</label>
          <input type="text" id="V_Electric_per_month" name="V_Electric_per_month"><br><br>

          <label for="V_comment">หมายเหตุ :</label>
          <input type="text" id="V_comment" name="V_comment"><br><br>

          <label for="T_Status">สถานะ :</label>
            <select id="T_Status" name="T_Status">
              <option value="">-- เลือกสถานะ --</option>
              <option value="นำส่งการไฟฟ้า">นำส่งการไฟฟ้า</option>
              <option value="ตอบรับ">ตอบรับ</option>
              <option value="ส่งมอบงาน">ส่งมอบงาน</option>
              <option value="ไม่ผ่าน">ไม่ผ่าน</option>
            </select><br><br>

        <button type="submit">เพิ่มข้อมูล</button>
        <button type="button">ยกเลิก</button>
      </div>
    </div>
  </div>
  </form>

  <?php

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

  }

  ?>

</body>
</html>