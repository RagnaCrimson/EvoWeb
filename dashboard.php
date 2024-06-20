<?php
include 'connect.php'; 

// display SUM electric_per_year
$strSQL_sum_year = "SELECT SUM(V_Electric_per_year) AS total_electric_per_year FROM view";
$result_sum_year = $objConnect->query($strSQL_sum_year);

if (!$result_sum_year) {
    die("Query failed: " . $objConnect->error);
}

$row_sum_year = $result_sum_year->fetch_assoc();
$total_electric_per_year = $row_sum_year['total_electric_per_year'];

// display SUM electric_per_month
$strSQL_sum_month = "SELECT SUM(V_Electric_per_month) AS total_electric_per_month FROM view";
$result_sum_month = $objConnect->query($strSQL_sum_month);

if (!$result_sum_month) {
    die("Query failed: " . $objConnect->error);
}

$row_sum_month = $result_sum_month->fetch_assoc();
$total_electric_per_month = $row_sum_month['total_electric_per_month'];

// Sample data for the dashboard
$new_wins = 100000000; 
$page_views = [165, 21, 6]; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<body class="bgcolor">
    <?php include 'header.php'; ?>

    <div class="container">
      <div class="dashboard">
          <div class="card">
              <h2>รวมจำนวนไฟฟ้ากิโลวัต Kw</h2>
              <p><?php echo number_format($new_wins); ?></p>
          </div>
          <div class="card">
              <h2>รวมค่าใช้ไฟฟ้าต่อปี</h2>
              <p><?php echo number_format($total_electric_per_year); ?> บาท</p>
          </div>
          <div class="card">
              <h2>รวมค่าใช้ไฟฟ้าต่อเดือน</h2>
              <p><?php echo number_format($total_electric_per_month); ?> บาท</p>
          </div>
          <div class="card">
              <h2>จำนวนหน่วยงานที่เข้าร่วม</h2>
              <p><?php echo array_sum($page_views); ?></p>
          </div>
          <div class="chart-container">
              <canvas id="pie-chart" class="pie-chart"></canvas>
          </div>
          <div class="chart-container">
              <canvas id="bar-chart" class="bar-chart"></canvas>
          </div>
      </div>
    </div>

    <script>
        const ctxPie = document.getElementById('pie-chart').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['ส่งการไฟฟ้า', 'ตอบรับ', 'ไม่ผ่าน'],
                datasets: [{
                    label: 'Page Views',
                    data: [<?php echo implode(', ', $page_views); ?>],
                    backgroundColor: ['#50C878', '#36a2eb', '#FF5733']
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
                labels: ['ต่อเดือน', 'ต่อปี'],
                datasets: [{
                    label: 'อัตราการใช้ไฟ',
                    data: [<?php echo $total_electric_per_month; ?>, <?php echo $total_electric_per_year; ?>],
                    backgroundColor: ['#FF5733', '#36a2eb']
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
