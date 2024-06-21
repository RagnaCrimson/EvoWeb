<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "datastore_db";

$objConnect = new mysqli($server, $username, $password, $database);

if ($objConnect->connect_error) {
    die("Connection failed: " . $objConnect->connect_error);
}

mysqli_query($objConnect, "SET NAMES utf8");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $strSQL_edit = "SELECT * FROM view WHERE V_Name = '$id'";
    $result_edit = $objConnect->query($strSQL_edit);
    
    if (!$result_edit) {
        die("Query failed: " . $objConnect->error);
    }
    
    $row_edit = $result_edit->fetch_assoc();
} else {
    echo "No ID specified.";
    exit;
}

if (isset($_POST['submit'])) {
    $newData = $_POST['data']; 

    $vComment = $objConnect->real_escape_string($newData['V_comment']);

    $stmt = $objConnect->prepare("UPDATE view SET V_comment = ? WHERE V_Name = ?");
    $stmt->bind_param("ss", $vComment, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/card_style.css">
</head>
<body class="bgcolor">
    <?php include 'header.php'; ?>
    <div class="container">
        <h2 class="center">Edit Data</h2>
        <form method="post">      
            <div class="card-body">
                <div class="left">
                    <div class="form-group">
                        <label for="name">ชื่อหน่วยงาน :</label>
                        <p class="form-control-static"><?php echo htmlspecialchars($row_edit['V_Name']); ?></p>
                    </div>
                    <div class="form-group">
                        <label for="comment">หมายเหตุ :</label>
                        <input type="text" class="form-control" id="Ecomment" name="data[V_comment]" value="<?php echo $row_edit['V_comment']; ?>">
                    </div>            
                </div>
            </div>
            <div class="center">
                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                <a href="data_view.php" class="btn btn-default">Back</a>
            </div>
        </form>

            <?php include 'back.html'; ?>
    </div>
</body>
</html>
