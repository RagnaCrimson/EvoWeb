<?php
include '../connect.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$province = isset($_GET['province']) ? $_GET['province'] : '';
$sale = isset($_GET['sale']) ? $_GET['sale'] : '';

$query = "
    SELECT view.*, task.T_Status, files.filename 
    FROM view 
    LEFT JOIN task ON view.V_ID = task.T_ID
    LEFT JOIN files ON view.V_ID = files.id
    WHERE task.T_Status = ? AND view.V_Province = ? AND view.V_Sale = ?
";

$stmt = $objConnect->prepare($query);

if (!$stmt) {
    die("Error preparing the query: " . $objConnect->error);
}

$stmt->bind_param("sss", $status, $province, $sale);
$stmt->execute();
$result = $stmt->get_result();

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details - สำรวจ</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="../js/logout.js"></script>
</head>
<body class="bgcolor">
    <?php include '../header.php'; ?>

    <div class="container content-color">
        <h2>รายละเอียดสถานะสำรวจ</h2>
        
        <?php if ($data): ?>
            <p><strong>ชื่อหน่วยงาน:</strong> <?php echo htmlspecialchars($data['V_Name']); ?></p>
            <p><strong>จังหวัด:</strong> <?php echo htmlspecialchars($data['V_Province']); ?></p>
            <p><strong>ทีมฝ่ายขาย:</strong> <?php echo htmlspecialchars($data['V_Sale']); ?></p>

            <?php if (!empty($data['filename'])): ?>
                <p><strong>ไฟล์ที่แนบมา:</strong></p>
                <img src="file/<?php echo htmlspecialchars($data['filename']); ?>" alt="Attached Image" class="img-responsive" style="max-width: 300px;">
            <?php else: ?>
                <p>ไม่มีไฟล์ที่แนบมา</p>
            <?php endif; ?>

            <h3>อัพโหลดไฟล์ PDF หรือรูปภาพ</h3>
            <form action="upload_file.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="V_ID" value="<?php echo $data['V_ID']; ?>">
                <div class="form-group">
                    <label for="file">เลือกไฟล์ (PDF หรือรูปภาพ):</label>
                    <input type="file" name="file" id="file" class="form-control" accept=".pdf, .jpg, .jpeg, .png">
                </div>
                <button type="submit" class="btn btn-primary">อัพโหลด</button>
            </form>
        <?php else: ?>
            <p>ไม่พบข้อมูลสำหรับสถานะนี้</p>
        <?php endif; ?>
    </div>

    <?php include '../back.html'; ?>
</body>
</html>
