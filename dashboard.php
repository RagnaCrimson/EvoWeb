<?php
include 'connect.php'; 

// Fetch data for the dashboard
// Sample data retrieval
// Replace these with your actual queries
$new_wins = 230; // Fetch the actual value from your database
$trial_win_rate = 9.86; // Fetch the actual value from your database
$new_mrr = 25690; // Fetch the actual value from your database
$page_views = [55, 30, 15]; // Fetch the actual values from your database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .dashboard {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
        }
        .card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card h2 {
            margin: 0 0 10px;
        }
        .card p {
            font-size: 24px;
            margin: 0;
        }
        .chart-container {
            grid-column: span 2;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .pie-chart, .bar-chart {
            height: 400px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="dashboard">
        <div class="card">
            <h2>New Wins</h2>
            <p><?php echo $new_wins; ?></p>
        </div>
        <div class="card">
            <h2>Trial Win Rate</h2>
            <p><?php echo $trial_win_rate; ?>%</p>
        </div>
        <div class="card">
            <h2>New MRR</h2>
            <p>$<?php echo number_format($new_mrr); ?></p>
        </div>
        <div class="card">
            <h2>Page Views</h2>
            <p><?php echo array_sum($page_views); ?></p>
        </div>
        <div class="chart-container">
            <canvas id="pie-chart" class="pie-chart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="bar-chart" class="bar-chart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxPie = document.getElementById('pie-chart').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Organic Search', 'Referral', 'Direct'],
                datasets: [{
                    label: 'Page Views',
                    data: [<?php echo implode(', ', $page_views); ?>],
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe']
                }]
            },
            options: {
                responsive: true
            }
        });

        const ctxBar = document.getElementById('bar-chart').getContext('2d');
        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['New Wins', 'Trial Win Rate', 'New MRR'],
                datasets: [{
                    label: 'Values',
                    data: [<?php echo $new_wins; ?>, <?php echo $trial_win_rate; ?>, <?php echo $new_mrr; ?>],
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
