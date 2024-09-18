<?php
include '../connect.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($status) || empty($id)) {
    die("Invalid parameters specified.");
}

// Prepare SQL query
$strSQL_datastore_db = "
    SELECT view.*, task.T_Status, files.filename 
    FROM view 
    LEFT JOIN task ON view.V_ID = task.T_ID
    LEFT JOIN files ON view.V_ID = files.id
    WHERE task.T_Status = ? AND view.V_ID = ?";

$stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);

if (!$stmt_datastore_db) {
    die("Prepare failed: " . $objConnect->error);
}

$stmt_datastore_db->bind_param("ss", $status, $id);
$stmt_datastore_db->execute();
$view_data = $stmt_datastore_db->get_result()->fetch_assoc();

if (!$view_data) {
    die("Query failed: " . $stmt_datastore_db->error);
}

$v_name = $view_data['V_Name'];

// Handle file upload
$target_dir = "file/";

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $file_name = basename($_FILES['file']['name']);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($file_type !== 'pdf') {
        $upload_error = "Only PDF files are allowed.";
    } elseif (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        $stmt = $objConnect->prepare("INSERT INTO uploads (filename, V_ID) VALUES (?, ?)");
        if (!$stmt) {
            die("Prepare failed for uploads insert: " . $objConnect->error);
        }
        $stmt->bind_param("ss", $file_name, $id);
        if ($stmt->execute()) {
            $upload_success = "The file " . htmlspecialchars($file_name) . " has been uploaded successfully.";
        } else {
            $upload_error = "Failed to store file information in the database.";
        }
    } else {
        $upload_error = "Sorry, there was an error uploading your file.";
    }
} elseif (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $upload_error = "No file was uploaded or there was an error during the upload process.";
}

// Fetch the latest uploaded PDF for the specific V_ID
$latest_file = '';
$file_query = "SELECT filename FROM uploads WHERE V_ID = ? ORDER BY upload_date DESC LIMIT 1";
$stmt = $objConnect->prepare($file_query);
if (!$stmt) {
    die("Prepare failed for file fetch: " . $objConnect->error);
}
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $latest_file = $row['filename'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and View PDF</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="../js/logout.js"></script>
</head>
<body class="bgcolor">
    <?php include '../header.php'; ?>

    <div class="container content-color">
        <h2>เอกสารการออก<?php echo htmlspecialchars($status); ?> - <?php echo htmlspecialchars($v_name); ?></h2>

        <h3>อัพโหลดเอกสารออกสำรวจ</h3>
        <form action="Status_Explore.php?status=<?php echo urlencode($status); ?>&id=<?php echo urlencode($id); ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="file" accept="application/pdf" required>
            <input type="submit" value="Submit" class="btn btn-primary">
        </form>

        <?php if (!empty($latest_file)): ?>
            <h3>ดูไฟล์เอกสาร</h3>
            <iframe src="file/<?php echo htmlspecialchars($latest_file); ?>" style="width:100%; height:800px;" frameborder="0">
                This browser does not support PDFs. Please download the PDF to view it: <a href="file/<?php echo htmlspecialchars($latest_file); ?>">Download PDF</a>.
            </iframe>
        <?php else: ?>
            <p>No PDF files have been uploaded yet.</p>
        <?php endif; ?>

        <?php include '../back.html'; ?>
    </div>
</body>
</html>
