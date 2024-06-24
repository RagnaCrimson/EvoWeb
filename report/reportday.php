<?php
include '../connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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
            <h2>รายงานตามวันที่</h2>
                <label for="branch">ทีมฝ่ายขาย</label>
                <select id="branch">
                    <option value="">-- เลือก --</option>
                    <option value="รายงาน">พิเย็น</option>
                    <option value="รายงาน">ดร.อี๊ด</option>
                    <option value="รายงาน">VJK</option>
                </select>
                <label for="date">เลือกวันที่</label>
                <input type="date" id="date" class="flatpickr" placeholder="เลือกวันที่">
                <div class="radio-group">
                    <label>
                        <input type="radio" name="status" value="ทั้งหมด" checked>
                        ทั้งหมด
                    </label>
                    <label>
                        <input type="radio" name="status" value="ทีมฝ่ายขาย">
                        ทีมฝ่ายขาย
                    </label>
                    <label>
                        <input type="radio" name="status" value="สถานะงาน">
                        สถานะงาน
                    </label>
                </div>
                <div class="button-group">
                    <button type="submit" name="view" class="btcolor">View</button>
                    <button type="submit" name="pdf" class="btcolor">PDF</button>
                </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#date", {
                dateFormat: "Y-m-d",
                locale: "th"
            });
        });
    </script>
</body>
</html>



