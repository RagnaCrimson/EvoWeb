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


// Get count of total_peak_per_month in range 0-99
$strSQL_count_0_99 = "SELECT COUNT(*) AS count_0_99 FROM view WHERE V_Peak_month BETWEEN 0 AND 99";
$result_count_0_99 = $objConnect->query($strSQL_count_0_99);
$row_count_0_99 = $result_count_0_99->fetch_assoc();
$count_0_99 = $row_count_0_99['count_0_99'];

// Get count of total_peak_per_month in range 100-199
$strSQL_count_100_199 = "SELECT COUNT(*) AS count_100_199 FROM view WHERE V_Peak_month BETWEEN 100 AND 199";
$result_count_100_199 = $objConnect->query($strSQL_count_100_199);
$row_count_100_199 = $result_count_100_199->fetch_assoc();
$count_100_199 = $row_count_100_199['count_100_199'];

// Get count of total_peak_per_month in range 200-299
$strSQL_count_200_299 = "SELECT COUNT(*) AS count_200_299 FROM view WHERE V_Peak_month BETWEEN 200 AND 299";
$result_count_200_299 = $objConnect->query($strSQL_count_200_299);
$row_count_200_299 = $result_count_200_299->fetch_assoc();
$count_200_299 = $row_count_200_299['count_200_299'];

// Get count of total_peak_per_month in range 300-5000
$strSQL_count_300_5000 = "SELECT COUNT(*) AS count_300_5000 FROM view WHERE V_Peak_month BETWEEN 300 AND 5000";
$result_count_300_5000 = $objConnect->query($strSQL_count_300_5000);
$row_count_300_5000 = $result_count_300_5000->fetch_assoc();
$count_300_5000 = $row_count_300_5000['count_300_5000'];

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
                <p>224 แห่ง</p>
            </div>
            <div class="chart-container">
                <canvas id="pie-chart" class="pie-chart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="new-doughnut-chart" class="doughnut-chart"></canvas>
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
        const ctxNewDoughnut = document.getElementById('new-doughnut-chart').getContext('2d');
        const newDoughnutChart = new Chart(ctxNewDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['0-99', '100-199', '200-299', '300-5000'],
            datasets: [{
                label: 'Total Peak per Month',
                data: [
                    <?php echo $count_0_99; ?>,
                    <?php echo $count_100_199; ?>,
                    <?php echo $count_200_299; ?>,
                    <?php echo $count_300_5000; ?>
                ],
                backgroundColor: ['#FF5733', '#FFC300', '#36a2eb', '#50C878']
            }]
        },
        options: {
            responsive: true,
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const segmentIndex = elements[0].index;
                    const label = newDoughnutChart.data.labels[segmentIndex];
                    window.location.href = `details.php?range=${label}`;
                }
            },
            plugins: {
                datalabels: {
                    formatter: (value, context) => {
                        if (context.dataIndex === 0) {
                            return 'Peak Month Total';
                        }
                        return '';
                    },
                    color: '#000',
                    font: {
                        weight: 'bold',
                        size: 16
                    },
                    align: 'center',
                    anchor: 'center'
                }
            }
        }
    });
    </script>
</body>
</html>