<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูล</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/form.css">
    <script src="js/script.js"></script>
</head>
<body class="bgcolor">
    <?php include 'edit_process.php'; ?>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1 class="center">แก้ไขข้อมูล</h1>
        <form method="post" enctype="multipart/form-data">      
            <div class="row">
                <div class="field">
                    <label for="name">ชื่อหน่วยงาน :</label>
                    <input type="text" class="form-control" id="name" name="data[V_Name]" value="<?php echo htmlspecialchars($row_edit['V_Name']); ?>">
                </div>
                <div class="field">
                    <label for="province">จังหวัด :</label>
                    <input type="text" class="form-control" id="province" name="data[V_Province]" value="<?php echo htmlspecialchars($row_edit['V_Province']); ?>">
                </div>
                <div class="field">
                    <label for="district">ตำบล :</label>
                    <input type="text" class="form-control" id="district" name="data[V_District]" value="<?php echo htmlspecialchars($row_edit['V_District']); ?>">
                </div>
                <div class="field">
                    <label for="subdistrict">อำเภอ :</label>
                    <input type="text" class="form-control" id="subdistrict" name="data[V_SubDistrict]" value="<?php echo htmlspecialchars($row_edit['V_SubDistrict']); ?>">
                </div>
            </div>

            <div class="row">
                <div class="field half-width">
                    <label for="V_location">ตำแหน่ง GPS Link google map หรือ พิกัด :</label>
                    <input type="text" class="form-control" id="V_location" name="data[V_location]" value="<?php echo htmlspecialchars($row_edit['V_location']); ?>">
                </div>
            </div>

            <div class="row">
                <div class="field half-width">
                    <label for="ExecName">ชื่อผู้บริหาร :</label>
                    <input type="text" class="form-control" id="ExecName" name="data[V_ExecName]" value="<?php echo htmlspecialchars($row_edit['V_ExecName']); ?>">
                </div>
                <div class="field">
                    <label for="ExecPhone">เบอร์ผู้บริหาร :</label>
                    <input type="text" class="form-control" id="ExecPhone" name="data[V_ExecPhone]" value="<?php echo htmlspecialchars($row_edit['V_ExecPhone']); ?>">
                </div>
                <div class="field">
                    <label for="ExecMail">อีเมลผู้บริหาร :</label>
                    <input type="text" class="form-control" id="ExecMail" name="data[V_ExecMail]" value="<?php echo htmlspecialchars($row_edit['V_ExecMail']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="field half-width">
                    <label for="CoordName1">ชื่อผู้ประสานงาน :</label>
                    <input type="text" class="form-control" id="CoordName1" name="data[V_CoordName1]" value="<?php echo htmlspecialchars($row_edit['V_CoordName1']); ?>">                
                </div>
                <div class="field">
                    <label for="CoordPhone1">เบอร์โทรผู้ประสานงาน :</label>
                    <input type="text" class="form-control" id="CoordPhone1" name="data[V_CoordPhone1]" value="<?php echo htmlspecialchars($row_edit['V_CoordPhone1']); ?>">                
                </div>
                <div class="field">
                    <label for="CoordMail1">อีเมลผู้ประสานงาน :</label>
                    <input type="text" class="form-control" id="CoordMail1" name="data[V_CoordMail1]" value="<?php echo htmlspecialchars($row_edit['V_CoordMail1']); ?>">                
                </div>
            </div>

            <div class="row">
                <h3>ค่า PEAK และค่าไฟของแต่ละเดือน</h3>
            </div>
            <div class="row">
                <div class="field">
                    <label for="serial_number">รหัสการไฟฟ้า :</label>
                    <input type="text"id="serial_number" name="data[serial_number]" maxlength="10" class="form-control" value="<?php echo htmlspecialchars($row_edit['serial_number']); ?>">
                </div>
                <div class="field">
                    <label for="CA_code">หมายเลขผู้ใช้ไฟฟ้า :</label>
                    <input type="text"id="CA_code" name="data[CA_code]" maxlength="12" class="form-control" value="<?php echo htmlspecialchars($row_edit['CA_code']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <label class="h-label" for="M_12">ระบุเดือน :</label>
                    <input type="date" id="B_M12" name="data[B_M12]" class="form-control" value="<?php echo htmlspecialchars($row_edit['B_M12']); ?>">
                </div>     
                <div class="field"> 
                    <label class="h-label" for="V_Peak_month">ค่าไฟ :</label>
                    <input type="number" step="any" placeholder="ค่าไฟ 000.00" id="V_Peak_month" name="data[V_Peak_month]" class="form-control" value="<?php echo htmlspecialchars($row_edit['V_Peak_month']); ?>">
                </div>
                <div class="field"> 
                    <label class="h-label" for="V_Electric_per_month">peak :</label>
                    <input type="number" step="any" placeholder="ค่า Peak 000.00" id="V_Electric_per_month" name="data[V_Electric_per_month]" class="form-control" value="<?php echo htmlspecialchars($row_edit['V_Electric_per_month']); ?>">
                </div>
            </div>

            <div class="row">
                <div class="field half-width">
                    <label for="V_Peak_year">ค่า PEAK ต่อปี (KW) :</label>
                    <input type="number" step="any" class="form-control" placeholder="000.00" id="V_Peak_year" name="data[V_Peak_year]" value="<?php echo htmlspecialchars($row_edit['V_Peak_year']); ?>">
                </div>
                <div class="field half-width">
                    <label for="V_Peak_month">ค่า PEAK ต่อเดือน (KW) :</label>
                    <input type="number" step="any" placeholder="000.00" id="V_Peak_month" name="data[V_Peak_month]" value="<?php echo htmlspecialchars($row_edit['V_Peak_month']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="field half-width">
                    <label for="V_Electric_per_year">ค่าใช้ไฟฟ้าต่อปี(บาท) :</label>
                    <input type="number" step="any" placeholder="000.00" id="V_Electric_per_year" name="data[V_Electric_per_year]" value="<?php echo htmlspecialchars($row_edit['V_Electric_per_year']); ?>">
                </div>
                <div class="field half-width">
                    <label for="V_Electric_per_month">ค่าใช้ไฟฟ้าต่อเดือน(บาท) :</label>
                    <input type="number" step="any" placeholder="000.00" id="V_Electric_per_month" name="data[V_Electric_per_month]" value="<?php echo htmlspecialchars($row_edit['V_Electric_per_month']); ?>">
                </div>
            </div>

            
            <div class="row">
                <div class="field full-width">
                    <label for="comment">หมายเหตุ :</label>
                    <textarea class="form-control" id="comment" name="data[V_comment]"><?php echo htmlspecialchars($row_edit['V_comment']); ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <label for="sale">ทีมฝ่ายขาย :</label>
                    <input type="text" class="form-control" id="sale" name="data[V_Sale]" value="<?php echo htmlspecialchars($row_edit['V_Sale']); ?>">
                </div>
                <div class="field">
                    <label for="date">วันที่ได้รับเอกสาร :</label>
                    <input type="date" class="form-control" id="date" name="data[V_Date]" value="<?php echo htmlspecialchars($row_edit['V_Date']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="field half-width">
                    <label for="file" class="form-label">ไฟล์ PDF: <?php echo basename($row_edit['filename']); ?></label>
                    <input type="file" class="form-control" accept="application/pdf" name="file" id="file">
                </div>
            </div>
            <div class="row center">
                <button type="submit" name="submit" class="btn btn-success">Submit</button>
                <button class="btn btn-default"><a href="data_view.php">Cancel</a></button>
            </div>
        </form>
    </div>
</body>
</html>
