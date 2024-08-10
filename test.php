<?php
include 'connect.php'; 

mysqli_query($objConnect, "SET NAMES utf8");

$V_ID = '1'; 

// Fetch data from peak table
$queryPeak = "SELECT * FROM peak WHERE V_ID = ?";
$stmtPeak = $objConnect->prepare($queryPeak);
$stmtPeak->bind_param("s", $V_ID);
$stmtPeak->execute();
$resultPeak = $stmtPeak->get_result();
$peakData = $resultPeak->fetch_assoc();

// Fetch data from bill table
$queryBill = "SELECT * FROM bill WHERE V_ID = ?";
$stmtBill = $objConnect->prepare($queryBill);
$stmtBill->bind_param("s", $V_ID);
$stmtBill->execute();
$resultBill = $stmtBill->get_result();
$billData = $resultBill->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/form.css">
    <script src="js/script.js"></script>
</head>
<body class="bgcolor">
<div class="row">
    <div class="field">
        <label for="serial_number">รหัสการไฟฟ้า :</label>
        <input type="text" id="serial_number" name="serial_number" maxlength="10" value="<?php echo isset($peakData['serial_number']) ? $peakData['serial_number'] : ''; ?>">
    </div>
    <div class="field">
        <label for="CA_code">หมายเลขผู้ใช้ไฟฟ้า :</label>
        <input type="number" id="CA_code" name="CA_code" maxlength="12" value="<?php echo isset($peakData['CA_code']) ? $peakData['CA_code'] : ''; ?>">
    </div>
</div>
<div class="h-row" id="peakContainer">
    <div class="row set">
        <?php for ($i = 1; $i <= 12; $i++) : ?>
            <div class="h-field">
                <label class="h-label" for="M_<?php echo $i; ?>">เดือน <?php echo $i; ?> :</label>
                <input type="date" id="B_M<?php echo $i; ?>" name="B_M<?php echo $i; ?>" class="form-control" value="<?php echo isset($billData["B_M$i"]) ? $billData["B_M$i"] : ''; ?>">
                <input type="number" step="any" placeholder="ค่า Peak 000.00" id="P_<?php echo $i; ?>" name="P_<?php echo $i; ?>" class="form-control" value="<?php echo isset($peakData["P_$i"]) ? $peakData["P_$i"] : ''; ?>">
                <input type="number" step="any" placeholder="ค่าไฟ 000.00" id="B_<?php echo $i; ?>" name="B_<?php echo $i; ?>" class="form-control" value="<?php echo isset($billData["B_$i"]) ? $billData["B_$i"] : ''; ?>">
            </div>
        <?php endfor; ?>
    </div>
</div>
<br><br>

<?php
// Close the prepared statements
$stmtPeak->close();
$stmtBill->close();
?>

</body>
</html>
