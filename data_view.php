<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_db";

try {
    $objConnect = new mysqli($servername, $username, $password, $dbname);

    if ($objConnect->connect_error) {
        throw new Exception("Connection failed: " . $objConnect->connect_error);
    }

    $objConnect->set_charset("utf8");

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $rows_per_page = 20;
    $offset = ($page - 1) * $rows_per_page;

    $search = isset($_GET['search']) ? $objConnect->real_escape_string($_GET['search']) : '';

    if ($search) {
        $stmt_total = $objConnect->prepare("SELECT COUNT(*) AS total FROM view WHERE V_Name LIKE CONCAT('%', ?, '%') OR V_Province LIKE CONCAT('%', ?, '%')");
        $stmt_total->bind_param("ss", $search, $search);
    } else {
        $stmt_total = $objConnect->prepare("SELECT COUNT(*) AS total FROM view");
    }

    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $row_total = $result_total->fetch_assoc();
    $total_rows = $row_total['total'];

    $total_pages = ceil($total_rows / $rows_per_page);

    if ($search) {
        $stmt_data = $objConnect->prepare("SELECT view.*, files.filename FROM view LEFT JOIN files ON view.V_ID = files.ID WHERE view.V_Name LIKE CONCAT('%', ?, '%') OR view.V_Province LIKE CONCAT('%', ?, '%') LIMIT ?, ?");
        $stmt_data->bind_param("ssii", $search, $search, $offset, $rows_per_page);
    } else {
        $stmt_data = $objConnect->prepare("SELECT view.*, files.filename FROM view LEFT JOIN files ON view.V_ID = files.ID LIMIT ?, ?");
        $stmt_data->bind_param("ii", $offset, $rows_per_page);
    }

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
<body class="bgcolor">
    <?php include 'header.php'; ?>
    
    <div id="View" class="tabcontent">
        <div style="margin-top: 50px; margin-bottom: 30px;">
            <h1>รายการข้อมูลการทั้งหมด</h1>
        </div>

        <div class="container">
            <nav class="navbar navbar-light bg-light" style="text-align: center;">
                <form class="form-inline" method="GET" action="">
                    <input type="text" style="text-align: center;" name="search" placeholder="พิพม์เพื่อค้นหา" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">ค้นหา</button>
                </form>
            </nav>

            <div class="card-container">
                <?php
                if ($resultdatastore_db->num_rows > 0) {
                    $sequence = $offset + 1;
                    while($row = $resultdatastore_db->fetch_assoc()) {
                        ?>
                        <div class="card">
                            <div class="card-header">
                                <p><b>ลำดับ : <?php echo $sequence; ?></b></p><?php echo htmlspecialchars($row["V_Name"]); ?>
                            </div>
                            <div class="card-body">
                                <div class="left">
                                    <p>ID : <?php echo htmlspecialchars($row["V_ID"]); ?></p>
                                    <p>จังหวัด : <?php echo htmlspecialchars($row["V_Province"]); ?></p>
                                    <p>อำเภอ : <?php echo htmlspecialchars($row["V_District"]); ?></p>
                                    <p>ตำบล : <?php echo htmlspecialchars($row["V_SubDistrict"]); ?></p><br>
                                    <p><b>ชื่อผู้บริหาร : </b><?php echo htmlspecialchars($row["V_ExecName"]); ?></p>
                                    <p>เบอร์โทร : <?php echo htmlspecialchars($row["V_ExecPhone"]); ?></p>
                                    <p>Email : <?php echo htmlspecialchars($row["V_ExecMail"]); ?></p><br>
                                    <p>ทีมฝ่ายขาย : <?php echo htmlspecialchars($row["V_Sale"]); ?></p>
                                    <p>วันที่รับเอกสาร : <?php echo date('d-m-Y', strtotime($row["V_Date"])); ?></p>
                                    <p><u>หมายเหตุ</u> : <?php echo htmlspecialchars($row["V_comment"]); ?></p>
                                </div>
                                <div class="right">
                                    <p>ชื่อผู้ประสานงาน : <?php echo htmlspecialchars($row["V_CoordName1"]); ?></p>
                                    <p>เบอร์โทร : <?php echo htmlspecialchars($row["V_CoordPhone1"]); ?></p>
                                    <p>Email : <?php echo htmlspecialchars($row["V_CoordMail1"]); ?></p><br>
                                    <p>ชื่อผู้ประสานงาน : <?php echo htmlspecialchars($row["V_CoordName2"]); ?></p>
                                    <p>เบอร์โทร : <?php echo htmlspecialchars($row["V_CoordPhone2"]); ?></p>
                                    <p>Email : <?php echo htmlspecialchars($row["V_CoordMail2"]); ?></p><br>
                                    <p>ค่าไฟ/ปี : <b><?php echo ($row["V_Electric_per_year"] == 0) ? 'N/A' : number_format($row["V_Electric_per_year"], 2); ?> บาท</b></p>
                                    <p>ค่าไฟ/เดือน : <b><?php echo ($row["V_Electric_per_month"] == 0) ? 'N/A' : number_format($row["V_Electric_per_month"], 2); ?> บาท</b></p><br>
                                    <p>การใช้ไฟ/ปี : <b><?php echo ($row["V_Peak_year"] == 0) ? 'N/A' : number_format($row["V_Peak_year"], 2); ?></b></p>
                                    <p>การใช้ไฟ/เดือน : <b><?php echo ($row["V_Peak_month"] == 0) ? 'N/A' : number_format($row["V_Peak_month"], 2); ?></b></p>
                                    <?php if (!empty($row["filename"])): ?>
                                        <a href="uploads/<?php echo htmlspecialchars($row["filename"]); ?>" class="btn btn-info btn-lg" target="_blank">PDF File</a>
                                    <?php else: ?>
                                        <p>No file uploaded</p>
                                    <?php endif; ?>
                                    <a href="edit.php?id=<?php echo urlencode($row['V_Name']); ?>" class="btn btn-warning btn-lg">Edit</a>
                                    <a href="graph.php?id=<?php echo urlencode($row['V_ID']); ?>" class="btn btn-primary btn-lg">กราฟ</a>
                                </div>
                            </div>
                        </div>
                        <?php
                        $sequence++;
                    }
                } else {
                    echo "<p>ไม่มีข้อมูลรายการ</p>";
                }
                ?>

                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li>
                                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="<?php if ($i == $page) echo 'active'; ?>"><a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a></li>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages): ?>
                            <li>
                                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
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
