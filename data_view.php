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

// Get the current page number from the URL, default is 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$rows_per_page = 20; // Number of rows to display per page

// Calculate the offset for the SQL LIMIT clause
$offset = ($page - 1) * $rows_per_page;

// Get the total number of rows in the table
$result_total = $objConnect->query("SELECT COUNT(*) AS total FROM view");
$row_total = $result_total->fetch_assoc();
$total_rows = $row_total['total'];

// Calculate total pages
$total_pages = ceil($total_rows / $rows_per_page);

// Fetch the data for the current page
$strSQL_datastore_db = "SELECT * FROM view LIMIT $offset, $rows_per_page";
$resultdatastore_db = $objConnect->query($strSQL_datastore_db);

if (!$resultdatastore_db) {
    die("Query failed: " . $objConnect->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashbord Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Arvo&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

        <div id="View" class="tabcontent">
            <div style="margin-top: 50px; margin-bottom: 50px;"><h1>รายการข้อมูลการทั้งหมด</h1></div>
            <table id="data" class="table table-striped">
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>อำเภอ</th>
                    <th>ตำบล</th>
                    <th>ชื่อผู้บริหาร</th>
                    <th>เบอร์โทร</th>
                    <th>E-mail</th>
                    <th>ชื่อผู้ประสานงาน 1</th>
                    <th>เบอร์โทรผู้ประสานงาน 1</th>
                    <th>E-mail ผู้ประสานงาน 1</th>
                    <th>ชื่อผู้ประสานงาน 2</th>
                    <th>เบอร์โทรผู้ประสานงาน 2</th>
                    <th>E-mail ผู้ประสานงาน 2</th>
                    <th>ทีมฝ่ายขาย</th>
                    <th>วันที่รับเอกสาร</th>
                    <th>การใช้ไฟ/ปี</th>
                    <th>การใช้ไฟ/เดือน</th>
                    <th>หมายเหตุ</th>
                    <th>Actions</th>
                </tr>
                <?php
                if ($resultdatastore_db->num_rows > 0) {
                    $sequence = $offset + 1; // Initialize sequence number for the current page
                    while($row = $resultdatastore_db->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $sequence++; ?></td>
                            <td><?php echo $row["V_Name"]; ?></td>
                            <td><?php echo $row["V_Province"]; ?></td>
                            <td><?php echo $row["V_District"]; ?></td>
                            <td><?php echo $row["V_SubDistrict"]; ?></td>
                            <td><?php echo $row["V_ExecName"]; ?></td>
                            <td><?php echo $row["V_ExecPhone"]; ?></td>
                            <td><?php echo $row["V_ExecMail"]; ?></td>
                            <td><?php echo $row["V_CoordName1"]; ?></td>
                            <td><?php echo $row["V_CoordPhone1"]; ?></td>
                            <td><?php echo $row["V_CoordMail1"]; ?></td>
                            <td><?php echo $row["V_CoordName2"]; ?></td>
                            <td><?php echo $row["V_CoordPhone2"]; ?></td>
                            <td><?php echo $row["V_CoordMail2"]; ?></td>
                            <td><?php echo $row["V_Sale"]; ?></td>
                            <td><?php echo $row["V_Date"]; ?></td>
                            <td><?php echo $row["V_Electric_per_year"]; ?></td>
                            <td><?php echo $row["V_Electric_per_month"]; ?></td>
                            <td><?php echo $row["V_comment"]; ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['V_Name']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='20'>ไม่มีข้อมูลรายการ</td></tr>";
                }
                ?>
            </table>

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

</body>
</html>
