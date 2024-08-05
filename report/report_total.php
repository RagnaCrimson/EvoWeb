<?php
include '../connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report PDF</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../css/report.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="bgcolor">
    <?php include '../header.php'; ?>
    <div class="card-center">
        <div class="container">
            <h2>ดูรายงาน</h2>
            <form action="process_total.php" method="post" target="_blank">
                <label for="sales_team">ทีมฝ่ายขาย</label>
                <select id="sales_team" name="sales_team">
                    <option value="">-- เลือก --</option>
                    <option value="คุณพิเย็น">คุณพิเย็น</option>
                    <option value="ดร.อี๊ด">ดร.อี๊ด</option>
                    <option value="VJK">VJK</option>
                    <option value="คุณชนินทร์">คุณชนินทร์</option>
                    <option value="คุณปริม">คุณปริม</option>
                </select>
                <div class="radio-group">
                    <div class="button-group">
                        <!-- <button type="submit" name="view" class="btcolor">View</button> -->
                        <button type="submit" name="pdf" class="btcolor">PDF</button>
                    </div>
                </div>
            </form>
        </div>         
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#start_date", {
            dateFormat: "Y-m-d",
            locale: "th"
        });
        flatpickr("#end_date", {
            dateFormat: "Y-m-d",
            locale: "th"
        });
    });
    </script>
</body>
</html>



