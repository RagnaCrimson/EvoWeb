<?php
include '../connect.php';

$status_labels = [];
$status_counts = [];

$strSQL_status = "SELECT T_Status, COUNT(*) as status_count FROM task GROUP BY T_Status";
$result_status = $objConnect->query($strSQL_status);

if (!$result_status) {
    die("Query failed: " . $objConnect->error);
}

while ($row_status = $result_status->fetch_assoc()) {
    $status_labels[] = $row_status['T_Status'];
    $status_counts[] = $row_status['status_count'];
}

// Convert PHP arrays to JSON for use in JavaScript
$status_labels_json = json_encode($status_labels, JSON_UNESCAPED_UNICODE);
$status_counts_json = json_encode($status_counts);

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

// Get count of total_peak_per_month in range 0
$strSQL_count_0 = "SELECT COUNT(*) AS count_0 FROM view WHERE V_Peak_month = 0";
$result_count_0 = $objConnect->query($strSQL_count_0);
$row_count_0 = $result_count_0->fetch_assoc();
$count_0 = $row_count_0['count_0'];

// Get count of total_peak_per_month in range 1-50
$strSQL_count_1_50 = "SELECT COUNT(*) AS count_1_50 FROM view WHERE V_Peak_month BETWEEN 1 AND 50";
$result_count_1_50 = $objConnect->query($strSQL_count_1_50);
$row_count_1_50 = $result_count_1_50->fetch_assoc();
$count_1_50 = $row_count_1_50['count_1_50'];

// Get count of total_peak_per_month in range 51-100
$strSQL_count_51_100 = "SELECT COUNT(*) AS count_51_100 FROM view WHERE V_Peak_month BETWEEN 51 AND 100";
$result_count_51_100 = $objConnect->query($strSQL_count_51_100);
$row_count_51_100 = $result_count_51_100->fetch_assoc();
$count_51_100 = $row_count_51_100['count_51_100'];

// Get count of total_peak_per_month in range 101-150
$strSQL_count_101_150 = "SELECT COUNT(*) AS count_101_150 FROM view WHERE V_Peak_month BETWEEN 101 AND 150";
$result_count_101_150 = $objConnect->query($strSQL_count_101_150);
$row_count_101_150 = $result_count_101_150->fetch_assoc();
$count_101_150 = $row_count_101_150['count_101_150'];

// Get count of total_peak_per_month in range 151-199
$strSQL_count_151_199 = "SELECT COUNT(*) AS count_151_199 FROM view WHERE V_Peak_month BETWEEN 151 AND 199";
$result_count_151_199 = $objConnect->query($strSQL_count_151_199);
$row_count_151_199 = $result_count_151_199->fetch_assoc();
$count_151_199 = $row_count_151_199['count_151_199'];

// Get count of total_peak_per_month in range 200-1000
$strSQL_count_200_10000 = "SELECT COUNT(*) AS count_200_10000 FROM view WHERE V_Peak_month BETWEEN 200 AND 10000";
$result_count_200_10000 = $objConnect->query($strSQL_count_200_10000);
$row_count_200_10000 = $result_count_200_10000->fetch_assoc();
$count_200_10000 = $row_count_200_10000['count_200_10000'];

// ============== V_Electric_per_year ================

// Get count of V_Electric_per_year in range 0
$strSQL_count_electric_0 = "SELECT COUNT(*) AS count_electric_0 FROM view WHERE V_Electric_per_year = 0";
$result_count_electric_0 = $objConnect->query($strSQL_count_electric_0);
$row_count_electric_0 = $result_count_electric_0->fetch_assoc();
$count_electric_0 = $row_count_electric_0['count_electric_0'];

// Get count of V_Electric_per_year in ranges 1-100,000
$strSQL_count_electric_1_100000 = "SELECT COUNT(*) AS count_electric_1_100000 FROM view WHERE V_Electric_per_year BETWEEN 1 AND 100000";
$result_count_electric_1_100000 = $objConnect->query($strSQL_count_electric_1_100000);
$row_count_electric_1_100000 = $result_count_electric_1_100000->fetch_assoc();
$count_electric_1_100000 = $row_count_electric_1_100000['count_electric_1_100000'];

// Get count of V_Electric_per_year in ranges 100,001-200,000
$strSQL_count_electric_100001_200000 = "SELECT COUNT(*) AS count_electric_100001_200000 FROM view WHERE V_Electric_per_year BETWEEN 100001 AND 200000";
$result_count_electric_100001_200000 = $objConnect->query($strSQL_count_electric_100001_200000);
$row_count_electric_100001_200000 = $result_count_electric_100001_200000->fetch_assoc();
$count_electric_100001_200000 = $row_count_electric_100001_200000['count_electric_100001_200000'];

// Get count of V_Electric_per_year in ranges 200,001-500,000
$strSQL_count_electric_200001_500000 = "SELECT COUNT(*) AS count_electric_200001_500000 FROM view WHERE V_Electric_per_year BETWEEN 200001 AND 500000";
$result_count_electric_200001_500000 = $objConnect->query($strSQL_count_electric_200001_500000);
$row_count_electric_200001_500000 = $result_count_electric_200001_500000->fetch_assoc();
$count_electric_200001_500000 = $row_count_electric_200001_500000['count_electric_200001_500000'];

// Get count of V_Electric_per_year in ranges 500,001-1,000,000
$strSQL_count_electric_500001_1000000 = "SELECT COUNT(*) AS count_electric_500001_1000000 FROM view WHERE V_Electric_per_year BETWEEN 500001 AND 1000000";
$result_count_electric_500001_1000000 = $objConnect->query($strSQL_count_electric_500001_1000000);
$row_count_electric_500001_1000000 = $result_count_electric_500001_1000000->fetch_assoc();
$count_electric_500001_1000000 = $row_count_electric_500001_1000000['count_electric_500001_1000000'];

// Get count of V_Electric_per_year in ranges 1,000,001-90,000,000
$strSQL_count_electric_1000001_90000000 = "SELECT COUNT(*) AS count_electric_1000001_90000000 FROM view WHERE V_Electric_per_year BETWEEN 1000001 AND 90000000";
$result_count_electric_1000001_90000000 = $objConnect->query($strSQL_count_electric_1000001_90000000);
$row_count_electric_1000001_90000000 = $result_count_electric_1000001_90000000->fetch_assoc();
$count_electric_1000001_90000000 = $row_count_electric_1000001_90000000['count_electric_1000001_90000000'];

// =========== V_Electric_per_month ================

// Get count of V_Electric_per_year in range 0
$strSQL_count_electric_month_0 = "SELECT COUNT(*) AS count_electric_month_0 FROM view WHERE V_Electric_per_month = 0";
$result_count_electric_month_0 = $objConnect->query($strSQL_count_electric_month_0);
$row_count_electric_month_0 = $result_count_electric_month_0->fetch_assoc();
$count_electric_month_0 = $row_count_electric_month_0['count_electric_month_0'];

// Get count of V_Electric_per_month in ranges 1-10,000
$strSQL_count_electric_month_1_10000 = "SELECT COUNT(*) AS count_electric_month_1_10000 FROM view WHERE V_Electric_per_month BETWEEN 1 AND 10000";
$result_count_electric_month_1_10000 = $objConnect->query($strSQL_count_electric_month_1_10000);
$row_count_electric_month_1_10000 = $result_count_electric_month_1_10000->fetch_assoc();
$count_electric_month_1_10000 = $row_count_electric_month_1_10000['count_electric_month_1_10000'];

// Get count of V_Electric_per_month in ranges 10,001-30,000
$strSQL_count_electric_month_10001_30000 = "SELECT COUNT(*) AS count_electric_month_10001_30000 FROM view WHERE V_Electric_per_month BETWEEN 10001 AND 30000";
$result_count_electric_month_10001_30000 = $objConnect->query($strSQL_count_electric_month_10001_30000);
$row_count_electric_month_10001_30000 = $result_count_electric_month_10001_30000->fetch_assoc();
$count_electric_month_10001_30000 = $row_count_electric_month_10001_30000['count_electric_month_10001_30000'];

// Get count of V_Electric_per_month in ranges 30,001-50,000
$strSQL_count_electric_month_30001_50000 = "SELECT COUNT(*) AS count_electric_month_30001_50000 FROM view WHERE V_Electric_per_month BETWEEN 30001 AND 50000";
$result_count_electric_month_30001_50000 = $objConnect->query($strSQL_count_electric_month_30001_50000);
$row_count_electric_month_30001_50000 = $result_count_electric_month_30001_50000->fetch_assoc();
$count_electric_month_30001_50000 = $row_count_electric_month_30001_50000['count_electric_month_30001_50000'];

// Get count of V_Electric_per_month in ranges 50,001-100,000
$strSQL_count_electric_month_50001_100000 = "SELECT COUNT(*) AS count_electric_month_50001_100000 FROM view WHERE V_Electric_per_month BETWEEN 50001 AND 100000";
$result_count_electric_month_50001_100000 = $objConnect->query($strSQL_count_electric_month_50001_100000);
$row_count_electric_month_50001_100000 = $result_count_electric_month_50001_100000->fetch_assoc();
$count_electric_month_50001_100000 = $row_count_electric_month_50001_100000['count_electric_month_50001_100000'];

// Get count of V_Electric_per_month in ranges 100,001-200,000
$strSQL_count_electric_month_100001_200000 = "SELECT COUNT(*) AS count_electric_month_100001_200000 FROM view WHERE V_Electric_per_month BETWEEN 100001 AND 200000";
$result_count_electric_month_100001_200000 = $objConnect->query($strSQL_count_electric_month_100001_200000);
$row_count_electric_month_100001_200000 = $result_count_electric_month_100001_200000->fetch_assoc();
$count_electric_month_100001_200000 = $row_count_electric_month_100001_200000['count_electric_month_100001_200000'];

// Get count of V_Electric_per_month in ranges 200,001-10,000,000
$strSQL_count_electric_month_200001_10000000 = "SELECT COUNT(*) AS count_electric_month_200001_10000000 FROM view WHERE V_Electric_per_month BETWEEN 200001 AND 10000000";
$result_count_electric_month_200001_10000000 = $objConnect->query($strSQL_count_electric_month_200001_10000000);
$row_count_electric_month_200001_10000000 = $result_count_electric_month_200001_10000000->fetch_assoc();
$count_electric_month_200001_10000000 = $row_count_electric_month_200001_10000000['count_electric_month_200001_10000000'];

$total_rows = isset($_SESSION['total_rows']) ? $_SESSION['total_rows'] : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View The Chart</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bgcolor">
    <nav role="navigation" class="primary-navigation">
        <ul>
            <li><a href="#">Welcome Guest!</a></li>
            <li><a href="guest_view.php">Dashboard</a></li>
        </ul>
    </nav>
    <div class="container">
        <div class="dashboard">
            <div class="card">
                <h2>รวมจำนวนไฟฟ้ากิโลวัต Kw</h2>
                <p><?php echo number_format($total_electric_per_year); ?></p>
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
                <p>324 แห่ง</p>
            </div>
            <div class="chart-container">
                <canvas id="pie-chart" class="pie-chart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="new-doughnut-chart" class="doughnut-chart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="electric-year-chart" class="pie-chart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="electric-month-chart" class="pie-chart"></canvas>
            </div>
        </div>
    </div>

<script>
    const statusLabels = <?php echo $status_labels_json; ?>;
    const statusCounts = <?php echo $status_counts_json; ?>;

    const ctxPie = document.getElementById('pie-chart').getContext('2d');
    const pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                label: 'สถานะงาน',
                data: statusCounts,
                backgroundColor: [
                    '#b6ddea','#12725c', '#898989',  '#0b6165', 
                    '#C70039', '#581845', '#FFC300'
                ]
            }]
        },
        options: {
            responsive: true,
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const segmentIndex = elements[0].index;
                    const label = pieChart.data.labels[segmentIndex];
                    window.location.href = `details_task.php?status=${encodeURIComponent(label)}`;
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'สถานะงาน'
                }
            }
        }
    });

    const ctxNewDoughnut = document.getElementById('new-doughnut-chart').getContext('2d');
    const newDoughnutChart = new Chart(ctxNewDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['0', '1-50', '51-100', '101-150', '151-199', '200-10000'],
            datasets: [{
                label: 'Total Peak per Month',
                data: [
                    <?php echo $count_0; ?>,
                    <?php echo $count_1_50; ?>,
                    <?php echo $count_51_100; ?>,
                    <?php echo $count_101_150; ?>,
                    <?php echo $count_151_199; ?>,
                    <?php echo $count_200_10000; ?>
                ],
                backgroundColor: ['#ea4335', '#fbbc05', '#4285f4', '#34a853', '#FF5733', '#8E44AD']
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
                title: {
                    display: true,
                    text: 'ค่า Peak ต่อเดือน'
                },
                datalabels: {
                    formatter: (value, context) => {
                        if (context.dataIndex === 0) {
                            return 'ค่า Peak ต่อเดือน';
                        }
                        return '';
                    },
                    color: '#000',
                    font: {
                        weight: 'bold',
                        size: 16
                    },
                    textAlign: 'center',
                    textAnchor: 'middle'
                }
            }
        }
    });


    const electricYearLabels = ['0', '1-100000', '100001-200000', '200001-500000', '500001-1000000', '1000001-90000000'];
    const electricYearCounts = [
        <?php echo $count_electric_0; ?>,
        <?php echo $count_electric_1_100000; ?>,
        <?php echo $count_electric_100001_200000; ?>,
        <?php echo $count_electric_200001_500000; ?>,
        <?php echo $count_electric_500001_1000000; ?>,
        <?php echo $count_electric_1000001_90000000; ?>
    ];

    const ctxElectricYear = document.getElementById('electric-year-chart').getContext('2d');
    const electricYearChart = new Chart(ctxElectricYear, {
        type: 'pie',
        data: {
            labels: electricYearLabels,
            datasets: [{
                label: 'Total Electric per Year',
                data: electricYearCounts,
                backgroundColor: ['#40B5AD', '#008080', '#4682B4', '#509ce4',  '#085298', '#0b2c4b']
            }]
        },
        options: {
            responsive: true,
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const segmentIndex = elements[0].index;
                    const label = electricYearChart.data.labels[segmentIndex];
                    window.location.href = `details_electric_year.php?range=${encodeURIComponent(label)}`;
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'ค่าไฟฟ้าต่อปี'
                }
            }
        }
    });

    const electricMonthLabels = ['0' , '1-10000', '10001-30000', '30001-50000', '50001-100000', '100001-200000', '200001-10000000'];
    const electricMonthCounts = [
        <?php echo $count_electric_month_0; ?>,
        <?php echo $count_electric_month_1_10000; ?>,
        <?php echo $count_electric_month_10001_30000; ?>,
        <?php echo $count_electric_month_30001_50000; ?>,
        <?php echo $count_electric_month_50001_100000; ?>,
        <?php echo $count_electric_month_100001_200000; ?>,
        <?php echo $count_electric_month_200001_10000000; ?>
    ];

    const ctxElectricMonth = document.getElementById('electric-month-chart').getContext('2d');
    const electricMonthChart = new Chart(ctxElectricMonth, {
        type: 'pie',
        data: {
            labels: electricMonthLabels,
            datasets: [{
                label: 'Total Electric per Month',
                data: electricMonthCounts,
                backgroundColor: ['#adbccc', '#9fb8d2', '#7e93ba', '#4a5f90', '#284377', '#1b2f55', '#0b2c4b']
            }]
        },
        options: {
            responsive: true,
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const segmentIndex = elements[0].index;
                    const label = electricMonthChart.data.labels[segmentIndex];
                    window.location.href = `details_electric_month.php?range=${encodeURIComponent(label)}`;
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'ค่าไฟฟ้าต่อเดือน'
                }
            }
        }
    });
</script>
<?php include '../back.html'; ?>
</body>
</html>