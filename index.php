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
    <form action="insert.php" method="POST">
    <div class="con">

      <div class="left">
        <label for="V_Name">ชื่อหน่วยงาน :</label>
        <input type="text" id="V_Name" name="V_Name"><br><br>

        <label for="V_Province">จังหวัด :</label>
        <input type="text" id="V_Province" name="V_Province"><br><br>

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

</body>
</html>