<?php
session_start();
include '../connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Team Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../css/report.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/logout.js"></script>
</head>
<body class="bgcolor">
    
    <nav role="navigation" class="primary-navigation">
        <ul>
            <li><a href="#"><?php echo $_SESSION['name']; ?></a></li>
            <li><a href="../dashboard.php">Dashboard</a></li>
            <li><a href="#">รายการ &dtrif;</a>
                <ul class="dropdown">
                    <li><a href="../data_view.php">ดูข้อมูลทั้งหมด</a></li>
                    <li><a href="../index.php">เพิ่มข้อมูล</a></li>
                    <li><a href="../status_view.php">ดูสถานะ</a></li>
                </ul>
            </li>
            <li><a href="#">Report &dtrif;</a>
                <ul class="dropdown">
                    <li><a href="../reportday.php">เลือกวันที่</a></li>
                    <li><a href="../report/allname.php" target="_blank">รายชื่อทั้งหมด</a></li>
                    <li><a href="../report/sale_name.php">เลือกทีมฝ่ายขาย</a></li>
                    <!-- <li><a href="#">ตามสถานะ</a></li>
                    <li><a href="#">บริษัทผู้รับเหมา</a></li> -->
                </ul>
            </li>
            <li><a onclick="confirmLogout()">ลงชื่ออก</a></li>
        </ul>
    </nav>

    <div class="card-center">
        <div class="container">
            <h2>ดูรายงาน</h2>
            <form action="report_sale_name.php" method="post" target="_blank">
                <label for="sales_team">ทีมฝ่ายขาย</label>
                <select id="sales_team" name="sales_team">
                    <option value="">-- เลือก --</option>
                    <option value="คุณพิเย็น">คุณพิเย็น</option>
                    <option value="ดร.อี๊ด">ดร.อี๊ด</option>
                    <option value="VJK">VJK</option>
                    <option value="VJ">VJ</option>
                    <option value="คุณชนินทร์">คุณชนินทร์</option>
                    <option value="คุณเรืองยศ">คุณเรืองยศ</option>
                    <option value="คุณปริม">คุณปริม</option>
                    <option value="ตา(สตึก)">ตา(สตึก)</option>
                    <option value="คุณอั๋น(สตึก)">คุณอั๋น(สตึก)</option>
                    <option value="คุณตา / อั๋น">คุณตา / อั๋น</option>
                </select>
                <div class="radio-group">
                    <div class="button-group">
                        <button type="submit" name="view" class="btcolor" target="_blank">Submit</button>
                    </div>
                </div>
            </form>
        </div>         
    </div>
</body>
</html>



