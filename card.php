<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "datastore_db";

try {
    $objConnect = new mysqli($server, $username, $password, $database);

    if ($objConnect->connect_error) {
        throw new Exception("Connection failed: " . $objConnect->connect_error);
    }

    $objConnect->set_charset("utf8");

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $rows_per_page = 20;
    $offset = ($page - 1) * $rows_per_page;

    $stmt_total = $objConnect->prepare("SELECT COUNT(*) AS total FROM view");
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $row_total = $result_total->fetch_assoc();
    $total_rows = $row_total['total'];

    $total_pages = ceil($total_rows / $rows_per_page);

    $stmt_data = $objConnect->prepare("SELECT * FROM view LIMIT ?, ?");
    $stmt_data->bind_param("ii", $offset, $rows_per_page);
    $stmt_data->execute();
    $resultdatastore_db = $stmt_data->get_result();

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/card_style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div id="View" class="tabcontent">
        <div style="margin-top: 50px; margin-bottom: 50px;">
            <h1>รายการข้อมูลการทั้งหมด</h1>
        </div>
        <div class="card-container">
            <?php
            if ($resultdatastore_db->num_rows > 0) {
                $sequence = $offset + 1;
                while($row = $resultdatastore_db->fetch_assoc()) {
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <?php echo htmlspecialchars($row["V_Name"]); ?>
                        </div>
                        <div class="card-body">
                            <div class="left">
                                <p><b>ลำดับ : <?php echo $sequence++; ?></b></p>
                                <p>จังหวัด : <?php echo htmlspecialchars($row["V_Province"]); ?></p>
                                <p>อำเภอ : <?php echo htmlspecialchars($row["V_District"]); ?></p>
                                <p>ตำบล : <?php echo htmlspecialchars($row["V_SubDistrict"]); ?></p><br>
                                <p>ชื่อผู้บริหาร : <?php echo htmlspecialchars($row["V_ExecName"]); ?></p>
                                <p>เบอร์โทร : <?php echo htmlspecialchars($row["V_ExecPhone"]); ?></p>
                                <p>Email : <?php echo htmlspecialchars($row["V_ExecMail"]); ?></p><br>
                                <p>ทีมฝ่ายขาย : <?php echo htmlspecialchars($row["V_Sale"]); ?></p>
                                <p>วันที่รับเอกสาร : <?php echo htmlspecialchars($row["V_Date"]); ?></p>
                            </div>
                            <div class="right">
                                <p>ชื่อผู้ประสานงาน 1 : <?php echo htmlspecialchars($row["V_CoordName1"]); ?></p>
                                <p>เบอร์โทรผู้ประสานงาน 1 : <?php echo htmlspecialchars($row["V_CoordPhone1"]); ?></p>
                                <p>Email ผู้ประสานงาน 1 : <?php echo htmlspecialchars($row["V_CoordMail1"]); ?></p><br>
                                <p>ชื่อผู้ประสานงาน 2 : <?php echo htmlspecialchars($row["V_CoordName2"]); ?></p>
                                <p>เบอร์โทรผู้ประสานงาน 2 : <?php echo htmlspecialchars($row["V_CoordPhone2"]); ?></p>
                                <p>Email ผู้ประสานงาน 2 : <?php echo htmlspecialchars($row["V_CoordMail2"]); ?></p><br>
                                <p>การใช้ไฟ/ปี : <?php echo htmlspecialchars($row["V_Electric_per_year"]); ?></p>
                                <p>การใช้ไฟ/เดือน : <?php echo htmlspecialchars($row["V_Electric_per_month"]); ?></p>
                                <p><u>หมายเหตุ</u> : <?php echo htmlspecialchars($row["V_comment"]); ?></p>
                                <a href="edit.php?id=<?php echo urlencode($row['V_Name']); ?>" class="btn btn-warning btn-sm">Edit</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>ไม่มีข้อมูลรายการ</p>";
            }
            ?>

            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li>
                            <a href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="<?php if ($i == $page) echo 'active'; ?>"><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <li>
                            <a href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    <?php include 'back.html'; ?>
</body>
</html>
