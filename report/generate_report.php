<?php
include '../connect.php';

$branch = $_POST['branch'];
$date = $_POST['date'];
$status = $_POST['status'];

// Build your query based on form inputs
$sql = "SELECT * FROM view WHERE branch='$branch' AND date='$date'";

if ($status != 'all') {
    $sql .= " AND status='$status'";
}

$result = $objConnect->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/evo/css/report.css">
</head>
<body class="bgcolor">
    <?php include '../header.php'; ?>
    <div class="container">
        <h2>Generated Report</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                    <th>Column 3</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['column1']}</td>
                                <td>{$row['column2']}</td>
                                <td>{$row['column3']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$objConnect->close();
?>
