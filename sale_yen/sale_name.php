<?php
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
    <link rel="icon" type="image/jpg" href="../img/logo-eet.jpg">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/logout.js"></script>
</head>
<body class="bgcolor">
<?php include 'header-sale.php'; ?>

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
                    <option value="คุณตา(สตึก)">คุณตา(สตึก)</option>
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



