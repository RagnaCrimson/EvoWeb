<?php
include 'connect.php';

$v_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($v_id <= 0) {
    die("Invalid V_ID parameter");
}

$sql = "SELECT P_Month, P_1, P_2, P_3, P_4, P_5, P_6, P_7, P_8, P_9, P_10, P_11, P_12 FROM peak WHERE P_ID = ?";
$stmt = $objConnect->prepare($sql);
$stmt->bind_param("i", $v_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $objConnect->error);
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $peak_data = array(
        $row['P_1'], $row['P_2'], $row['P_3'], $row['P_4'], 
        $row['P_5'], $row['P_6'], $row['P_7'], $row['P_8'], 
        $row['P_9'], $row['P_10'], $row['P_11'], $row['P_12']
    );
    $p_month = $row["P_Month"];
} else {
    $peak_data = array_fill(0, 12, 0);
    $p_month = "No data";
}

$objConnect->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Peak Data</title>
    <link rel="stylesheet" href="css/card_style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bgcolor">
    <?php include 'header.php'; ?>

    <div style="margin-top: 50px;">
        <h1>ข้อมูล Peak แบบกราฟ</h1>
    </div>

    <div class="container">
        <div class="git-center">
            <div class="chart-container">
                <canvas id="myChart" width="1000" height="500"></canvas>
            </div>
        </div>
    </div>

    <h1><?php echo htmlspecialchars($p_month); ?></h1>
    <div class="git-center">
        <a href="data_view.php" class="btn btn-primary btn-lg">Back</a>
        <a href="graph_update.php" class="btn btn-primary btn-lg">Edit</a>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Month 1', 'Month 2', 'Month 3', 'Month 4', 'Month 5', 'Month 6', 'Month 7', 'Month 8', 'Month 9', 'Month 10', 'Month 11', 'Month 12'],
                datasets: [{
                    label: 'Peak Data',
                    data: <?php echo json_encode($peak_data); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <?php include 'back.html'; ?>
</body>
</html>
