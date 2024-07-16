<?php
// Include the database connection script
include 'connect.php';

// Fetch data based on $_GET['id'] (ensure it's sanitized)
$id = $_GET['id'];

// Example query to fetch data
$sql = "SELECT V_Peak_year, V_Peak_month FROM view WHERE V_Name = '$id'";

$result = $objConnect->query($sql);

if (!$result) {
    die("Query failed: " . $objConnect->error);
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $peak_year_data = $row['V_Peak_year'];
    $peak_month_data = $row['V_Peak_month'];
} else {
    // Handle case where no data is found
    $peak_year_data = 0;
    $peak_month_data = 0;
}

// Close the database connection at the end of script execution
$objConnect->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Graph</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bgcolor">
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="dashboard">
            <div class="chart-container">
                <canvas id="myChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Peak Year', 'Peak Month'],
                datasets: [{
                    label: 'Electric Peak',
                    data: [
                        <?php echo json_encode($peak_year_data); ?>,
                        <?php echo json_encode($peak_month_data); ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
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
