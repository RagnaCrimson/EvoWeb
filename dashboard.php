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

// display SUM peak year
$strSQL_sum_peak_year = "SELECT SUM(V_Peak_year) AS total_peak_per_year FROM view";
$result_sum_peak_year = $objConnect->query($strSQL_sum_peak_year);

if (!$result_sum_peak_year) {
    die("Query failed: " . $objConnect->error);
}

$row_sum_peak_year = $result_sum_peak_year->fetch_assoc();
$total_peak_per_year = $row_sum_peak_year['total_peak_per_year'];

// display SUM peak month
$strSQL_sum_peak_month = "SELECT SUM(V_Peak_month) AS total_peak_per_month FROM view";
$result_sum_peak_month = $objConnect->query($strSQL_sum_peak_month);

if (!$result_sum_peak_month) {
    die("Query failed: " . $objConnect->error);
}

$row_sum_peak_month = $result_sum_peak_month->fetch_assoc();
$total_peak_per_month = $row_sum_peak_month['total_peak_per_month'];


$total_rows = isset($_SESSION['total_rows']) ? $_SESSION['total_rows'] : 0;

$new_wins = 100000000; 
$page_views = [162, 21, 6]; 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bgcolor">
    <?php include 'header.php'; ?>

    <div class="container">
      <div class="dashboard">
          <div class="card">
              <h2>รวมจำนวนไฟฟ้ากิโลวัต Kw</h2>
              <p><?php echo number_format($total_peak_per_year); ?></p>
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
              <h2>หน่วยงานที่เข้าร่วมทั้งหมด</h2>
              <p>189 แห่ง</p>
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
