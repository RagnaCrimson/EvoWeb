<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="css/form.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
</head>
<body class="bgcolor">
<?php include 'header.php'; ?>
<div style="margin-bottom: 30px; margin-top: 30px;"><h1>เพิ่มข้อมูล</h1></div>
    <form action="upload.php" method="POST" onsubmit="showSuccessPopup()" enctype="multipart/form-data">
        <div class="row">
            <div class="field">
                <label for="V_Name">ชื่อหน่วยงาน :</label>
                <input type="text" id="V_Name" name="V_Name" required>
            </div>
            <div class="field">
                <label for="V_Province">จังหวัด :</label>
                <input type="text" id="V_Province" name="V_Province" required>
            </div>
            <div class="field">
                <label for="V_District">อำเภอ :</label>
                <input type="text" id="V_District" name="V_District">
            </div>
            <div class="field">
                <label for="V_SubDistrict">ตำบล :</label>
                <input type="text" id="V_SubDistrict" name="V_SubDistrict">
            </div>
        </div>

        <div class="row">
            <div class="field half-width">
                <label for="V_ExecName">ชื่อผู้บริหาร :</label>
                <input type="text" id="V_ExecName" name="V_ExecName">
            </div>
            <div class="field">
                <label for="V_ExecPhone">เบอร์ผู้บริหาร :</label>
                <input type="text" id="V_ExecPhone" name="V_ExecPhone">
            </div>
            <div class="field">
                <label for="V_ExecMail">อีเมลผู้บริหาร :</label>
                <input type="text" id="V_ExecMail" name="V_ExecMail">
            </div>
        </div>

        <div class="row">
            <div class="field half-width">
                <label for="V_CoordName1">ชื่อผู้ประสานงาน 1 :</label>
                <input type="text" id="V_CoordName1" name="V_CoordName1">
            </div>
            <div class="field">
                <label for="V_CoordPhone1">เบอร์ผู้ประสานงาน 1 :</label>
                <input type="text"id="V_CoordPhone1" name="V_CoordPhone1">
            </div>
            <div class="field">
                <label for="V_CoordMail1">อีเมลผู้ประสานงาน 1 :</label>
                <input type="text" id="V_CoordMail1" name="V_CoordMail1">
            </div>
        </div>

        <div class="row">
            <div class="field half-width">
                <label for="V_CoordName2">ชื่อผู้ประสานงาน 2 :</label>
                <input type="text" id="V_CoordName2" name="V_CoordName2">
            </div>
            <div class="field">
                <label for="V_CoordPhone2">เบอร์ผู้ประสานงาน 2 :</label>
                <input type="text"id="V_CoordPhone2" name="V_CoordPhone2">
            </div>
            <div class="field">
                <label for="V_CoordMail2">อีเมลผู้ประสานงาน 2 :</label>
                <input type="text" id="V_CoordMail2" name="V_CoordMail2">
            </div>
        </div>

        <!-- ========================= -->
        <h3>ค่า PEAK ของแต่ละเดือน</h3>
        <div class="h-row">
            <?php for ($i = 1; $i <= 12; $i++) : ?>
                <div class="h-field">
                    <label class="h-label" for="P_M<?php echo $i; ?>">ระบุเดือน <?php echo $i; ?> :</label>
                    <input type="date" id="P_M<?php echo $i; ?>" name="P_M<?php echo $i; ?>">
                    <label class="h-label" for="P_<?php echo $i; ?>">เดือน <?php echo $i; ?> :</label>
                    <input type="number" step="any" placeholder="000.00" id="P_<?php echo $i; ?>" name="P_<?php echo $i; ?>">
                </div>
            <?php endfor; ?>
        </div>

        <h3>ค่าไฟ ของแต่ละเดือน</h3>
        <div class="h-row">
            <?php for ($i = 1; $i <= 12; $i++) : ?>
                <div class="h-field">
                    <label class="h-label" for="B_M<?php echo $i; ?>">ระบุเดือน <?php echo $i; ?> :</label>
                    <input type="date" id="B_M<?php echo $i; ?>" name="B_M<?php echo $i; ?>">
                    <label class="h-label" for="B_<?php echo $i; ?>">เดือน <?php echo $i; ?> :</label>
                    <input type="number" step="any" placeholder="000.00" id="B_<?php echo $i; ?>" name="B_<?php echo $i; ?>">
                </div>
            <?php endfor; ?>
        </div>

        
        <div class="row">
            <div class="field half-width">
                <label for="V_Electric_per_year">ค่าใช้ไฟฟ้าต่อปี(บาท) :</label>
                <input type="number" step="any" placeholder="000.00" id="V_Electric_per_year" name="V_Electric_per_year">
            </div>
            <div class="field half-width">
                <label for="V_Electric_per_month">ค่าใช้ไฟฟ้าต่อเดือน(บาท) :</label>
                <input type="number" step="any" placeholder="000.00" id="V_Electric_per_month" name="V_Electric_per_month">
            </div>
        </div>
        <div class="row">
            <div class="field half-width">
                <label for="V_Peak_year">ค่า PEAK ต่อปี (KW) :</label>
                <input type="number" step="any" placeholder="000.00" id="V_Peak_year" name="V_Peak_year">
            </div>
            <div class="field half-width">
                <label for="V_Peak_month">ค่า PEAK ต่อเดือน (KW) :</label>
                <input type="number" step="any" placeholder="000.00" id="V_Peak_month" name="V_Peak_month">
            </div>
        </div>
        
        <div class="row">
            <div class="field full-width">
                <label for="V_comment">หมายเหตุ :</label>
                <textarea id="V_comment" name="V_comment"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="field">
                <label for="V_Sale">ทีมฝ่ายขาย :</label>
                <input type="text" id="V_Sale" name="V_Sale">
            </div>
            <div class="field">
                <label for="V_Date">วันที่ได้รับเอกสาร :</label>
                <input type="date" id="V_Date" name="V_Date">
            </div>
            <div class="field">
                <label for="T_Status">สถานะ :</label>
                <select id="T_Status" name="T_Status" class="form-control" required>
                    <option value="">-- เลือกสถานะ --</option>
                    <option value="นำส่งการไฟฟ้า">นำส่งการไฟฟ้า</option>
                    <option value="ตอบรับ">ตอบรับ</option>
                    <option value="ส่งมอบงาน">ส่งมอบงาน</option>
                    <option value="ไม่ผ่าน">ไม่ผ่าน</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="field half-width">
                <label for="file" class="form-label">Select file</label>
                <input type="file" class="form-control" accept="application/pdf" name="file" id="file" required>
            </div>
        </div>
        <div class="row center">
            <button type="submit" value="Submit">Submit</button>
            <button type="reset">Reset</button>
        </div>
    </form>
    <?php include 'back.html'; ?>
    
    <script>
    // Function to calculate and update peak values
    function updatePeakValues() {
        let totalPeakYear = 0;
        let totalPeakMonth = 0;

        // Loop through each month's peak input field
        for (let i = 1; i <= 12; i++) {
            let peakValue = parseFloat(document.getElementById('P_' + i).value) || 0; // Get the value, default to 0 if empty or NaN
            totalPeakYear += peakValue; // Add to yearly total
        }

        totalPeakMonth = totalPeakYear / 12; // Calculate average monthly peak

        // Update the fields with the calculated values
        document.getElementById('V_Peak_year').value = totalPeakYear.toFixed(2); // Update yearly peak
        document.getElementById('V_Peak_month').value = totalPeakMonth.toFixed(2); // Update monthly peak
    }

    // Attach event listeners to each month's peak input field
    document.addEventListener('DOMContentLoaded', function() {
        for (let i = 1; i <= 12; i++) {
            document.getElementById('P_' + i).addEventListener('input', updatePeakValues);
        }
    });
    function updateElectricValues() {
        let totalElectricYear = 0;
        let totalElectricMonth = 0;

        // Loop through each month's electric input field
        for (let i = 1; i <= 12; i++) {
            let electricValue = parseFloat(document.getElementById('B_' + i).value) || 0; // Get the value, default to 0 if empty or NaN
            totalElectricYear += electricValue; // Add to yearly total
        }

        totalElectricMonth = totalElectricYear / 12; // Calculate average monthly electric

        // Update the fields with the calculated values
        document.getElementById('V_Electric_per_year').value = totalElectricYear.toFixed(2); // Update yearly electric
        document.getElementById('V_Electric_per_month').value = totalElectricMonth.toFixed(2); // Update monthly electric
    }

    // Attach event listeners to each month's electric input field
    document.addEventListener('DOMContentLoaded', function() {
        for (let i = 1; i <= 12; i++) {
            document.getElementById('B_' + i).addEventListener('input', updateElectricValues);
        }
    });
</script>

</body>
</html>
