<?php
include 'connect.php';

mysqli_query($objConnect, "SET NAMES utf8");

$search = isset($_GET['search']) ? $objConnect->real_escape_string($_GET['search']) : '';
$saleFilter = isset($_GET['sale']) ? $objConnect->real_escape_string($_GET['sale']) : '';
$statusFilter = isset($_GET['status']) ? $objConnect->real_escape_string($_GET['status']) : '';
$provinceFilter = isset($_GET['province']) ? $objConnect->real_escape_string($_GET['province']) : '';

if ($search) {
    $strSQL_datastore_db = "
        SELECT view.*, task.T_Status, files.filename 
        FROM view 
        LEFT JOIN task ON view.V_ID = task.T_ID
        LEFT JOIN files ON view.V_ID = files.id
        WHERE (view.V_Name LIKE CONCAT('%', ?, '%') 
        OR view.V_Province LIKE CONCAT('%', ?, '%') 
        OR task.T_Status LIKE CONCAT('%', ?, '%'))
        AND (view.V_Sale = ? OR ? = '')
        AND (task.T_Status = ? OR ? = '')
        AND (view.V_Province = ? OR ? = '')";
    
    $stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);
    if ($stmt_datastore_db === false) {
        die("Prepare failed: " . $objConnect->error);
    }

    $stmt_datastore_db->bind_param("sssssssss", $search, $search, $search, $saleFilter, $saleFilter, $statusFilter, $statusFilter, $provinceFilter, $provinceFilter);
} else {
    $strSQL_datastore_db = "
        SELECT view.*, task.T_Status, files.filename 
        FROM view 
        LEFT JOIN task ON view.V_ID = task.T_ID
        LEFT JOIN files ON view.V_ID = files.id
        WHERE (view.V_Sale = ? OR ? = '')
        AND (task.T_Status = ? OR ? = '')
        AND (view.V_Province = ? OR ? = '')";
    
    $stmt_datastore_db = $objConnect->prepare($strSQL_datastore_db);
    if ($stmt_datastore_db === false) {
        die("Prepare failed: " . $objConnect->error);
    }

    $stmt_datastore_db->bind_param("ssssss", $saleFilter, $saleFilter, $statusFilter, $statusFilter, $provinceFilter, $provinceFilter);
}

if (!$stmt_datastore_db->execute()) {
    die("Execute failed: " . $stmt_datastore_db->error);
}

$resultdatastore_db = $stmt_datastore_db->get_result();

if ($resultdatastore_db === false) {
    die("Getting result set failed: " . $stmt_datastore_db->error);
}

$total_rows = $resultdatastore_db->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        th.sortable {
            cursor: pointer;
        }
    </style>
    <script>
        $(document).ready(function() {
            // View button functionality
            $('.view-btn').click(function() {
                var viewId = $(this).data('id');
                $('#modal-body').load('status/view_data.php', { view_id: viewId }, function() {
                    $('#myModal').modal('show');
                });
            });

            // Sort functionality
            var sortOrder = 'asc'; // Default sort order
            $('#sortSale').click(function() {
                var table = $('#data');
                var rows = table.find('tr:gt(1)').toArray().sort(compareRows);
                if (sortOrder === 'asc') {
                    rows = rows.reverse();
                    sortOrder = 'desc';
                } else {
                    sortOrder = 'asc';
                }
                $.each(rows, function(index, row) {
                    table.append(row);
                });
            });

            function compareRows(a, b) {
                var valA = $(a).find('td').eq(8).text().toUpperCase();
                var valB = $(b).find('td').eq(8).text().toUpperCase();
                return valA.localeCompare(valB);
            }
        });
    </script>
</head>
<body class="bgcolor">
    <?php include 'header.php'; ?>

    <div class="container">
        <div id="View" class="tabcontent">
            <div style="margin-bottom: 50px;"><h1>รายการติดตามสถานะ</h1></div>
            <table id="data" class="table table-striped">
                <nav class="navbar navbar-light bg-light" style="text-align: center;">
                    <form class="form-inline" method="GET" action="">
                        <input type="text" style="text-align: center;" name="search" placeholder="พิมพ์เพื่อค้นหา" class="form-control">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </form>
                </nav>
                <tr>
                    <strong>จำนวนทั้งหมด <?php echo $total_rows; ?> หน่วยงาน</strong>
                    <th>ลำดับ</th>
                    <th>ชื่อหน่วยงาน</th>
                    <th>จังหวัด</th>
                    <th>ค่าไฟ/ปี</th>
                    <th>ค่าไฟ/เดือน</th>
                    <th>การใช้ไฟ/ปี</th>
                    <th>การใช้ไฟ/เดือน</th>
                    <th>File PDF</th>
                    <th class="sortable" id="sortSale">ทีมฝ่ายขาย</th>
                    <th>ดูรายละเอียด</th>
                </tr>
                
                <?php  
                $total_rows = 0; 
                if ($resultdatastore_db->num_rows > 0) {
                    $sequence = 1;
                    while ($row = $resultdatastore_db->fetch_assoc()) {
                        $total_rows++; 
                        ?>
                        <tr>     
                            <td><?php echo $sequence++; ?></td>
                            <td><?php echo htmlspecialchars($row["V_Name"]); ?></td>
                            <td><?php echo htmlspecialchars($row["V_Province"]); ?></td>
                            <td><?php echo ($row["V_Electric_per_year"] == 0) ? 'N/A' : number_format($row["V_Electric_per_year"], 2); ?></td>
                            <td><?php echo ($row["V_Electric_per_month"] == 0) ? 'N/A' : number_format($row["V_Electric_per_month"], 2); ?></td>
                            <td><?php echo ($row["V_Peak_year"] == 0) ? 'N/A' : number_format($row["V_Peak_year"], 2); ?></td>
                            <td><?php echo ($row["V_Peak_month"] == 0) ? 'N/A' : number_format($row["V_Peak_month"], 2); ?></td>
                            <td>
                                <?php if (!empty($row["filename"])): ?>
                                    <a href="uploads/<?php echo htmlspecialchars($row["filename"]); ?>" target="_blank"><?php echo htmlspecialchars($row["filename"]); ?></a>
                                <?php else: ?>
                                    No file
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row["V_Sale"]); ?></td>
                            <td><button class="btn btn-info btn-lg view-btn" data-id="<?php echo urlencode($row['V_ID']); ?>">View</button></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='11'>ไม่มีข้อมูลรายการ</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>

    <!-- Popup -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title center">ข้อมูลสถานะ</h4>
                </div>
                <div id="modal-body" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'buttom.html'; ?>
    <?php include 'back.html'; ?>
</body>
</html>

