<!DOCTYPE html>
<html>
<head>
  <title>ยังไม่มีชื่อ</title>
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
    <h1>เพิ่มข้อมูล</h1>
  </div>

  <!-- =============== Left side ================== -->
  <div class="container">

    <div class="left">
      <label for="name">ชื่อโรงเรียน </label>
      <input type="text" id="name">

      <label for="province">จังหวัด :</label>
      <input type="text" id="province">

      <label for="dicstic">อำเภอ :</label>
      <input type="text" id="dicstic">

      <label for="Sub_Distric">ตำบล :</label>
      <input type="text" id="Sub_Distric">

      <label for="ExecName">ชื่อผู้บริหาร :</label>
      <input type="text" id="ExecName">

      <label for="ExecPhone">เบอร์โทร :</label>
      <input type="text" id="ExecPhone">

      <label for="ExecMail">อีเมล :</label>
      <input type="text" id="ExecMail">

      <label for="CoordName1">ชื่อผู้ประสานงาน 1 :</label>
      <input type="text" id="CoordName1">

      <label for="CoordPhon1">เบอร์โทรผู้ประสานงาน 1 :</label>
      <input type="text" id="CoordPhone1">

      <label for="CoordMail1">อีเมลผู้ประสานงาน 1 :</label>
      <input type="text" id="CoordMail1">
    </div>
    
  <!-- ============== Right side ================= -->
   
    <div class="right">
      <label for="CoordName2">ชื่อผู้ประสานงาน 2 :</label>
      <input type="text" id="CoordName1">

      <label for="CoordPhon2">เบอร์โทรผู้ประสานงาน 2 :</label>
      <input type="text" id="CoordPhone2">

      <label for="Sale">ทีมฝ่ายขาย :</label>
      <input type="text" id="Sale">

      <label for="Date">วันที่รับเอกสาร :</label>
      <input type="text" id="date">

      <label for="ElectricYear">ข้อมูลการใช้ไฟฟ้าต่อปี :</label>
      <input type="text" id="ElectricYear">
      
      <label for="ElectricMonth">ข้อมูลการใช้ไฟฟ้าต่อเดือน :</label>
      <input type="text" id="ElectricMonth">
      
      <label for="notes">หมายเหตุ:</label>
      <textarea id="notes"></textarea> <br>

      <button type="button">เพิ่มข้อมูล</button>
      <button type="button">ยกเลิก</button>
    </div>
  </div>

</body>
</html>