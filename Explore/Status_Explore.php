<?php
include '../connect.php';

$query = "
    SELECT view.*, task.T_Status, files.filename
    FROM view 
    LEFT JOIN task ON view.V_ID = task.T_ID
    LEFT JOIN files ON view.V_ID = files.id
";

$stmt = $objConnect->prepare($query);

if (!$stmt) {
    die("Error preparing the query: " . $objConnect->error);
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query execution failed: " . $stmt->error);
}

if ($result->num_rows === 0) {
    echo "No data found.";
}

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
        <img src="../Explore/file/QRCode.png" width="200" height="200" >

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ชื่อ</th>
                    <th>สถานะ</th>
                    <th>ชื่อไฟล์</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['V_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['T_Status']); ?></td>
                        <td>
                            <?php if ($row['filename']) { ?>
                                <a href="../uploads/<?php echo htmlspecialchars($row['filename']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($row['filename']); ?>
                                </a>
                            <?php } else { ?>
                                No file uploaded
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>อัพโหลดไฟล์รูปภาพ</h3>
        <form action="upload_file.php" method="post" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/*" required>
            <input type="submit" value="Upload Image" class="btn btn-primary">
        </form>

        <?php include '../back.html'; ?>
    </div>
</body>
</html>
