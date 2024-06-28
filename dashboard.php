<?php
include 'connect.php';

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

// Get count of V_Electric_per_year in specific ranges
$strSQL_count_electric_1_10000 = "SELECT COUNT(*) AS count_electric_1_10000 FROM view WHERE V_Electric_per_year BETWEEN 1 AND 10000";
$result_count_electric_1_10000 = $objConnect->query($strSQL_count_electric_1_10000);
$row_count_electric_1_10000 = $result_count_electric_1_10000->fetch_assoc();
$count_electric_1_10000 = $row_count_electric_1_10000['count_electric_1_10000'];

$strSQL_count_electric_10001_30000 = "SELECT COUNT(*) AS count_electric_10001_30000 FROM view WHERE V_Electric_per_year BETWEEN 10001 AND 30000";
$result_count_electric_10001_30000 = $objConnect->query($strSQL_count_electric_10001_30000);
$row_count_electric_10001_30000 = $result_count_electric_10001_30000->fetch_assoc();
$count_electric_10001_30000 = $row_count_electric_10001_30000['count_electric_10001_30000'];

$strSQL_count_electric_30001_50000 = "SELECT COUNT(*) AS count_electric_30001_50000 FROM view WHERE V_Electric_per_year BETWEEN 30001 AND 50000";
$result_count_electric_30001_50000 = $objConnect->query($strSQL_count_electric_30001_50000);
$row_count_electric_30001_50000 = $result_count_electric_30001_50000->fetch_assoc();
$count_electric_30001_50000 = $row_count_electric_30001_50000['count_electric_30001_50000'];

$strSQL_count_electric_50001_100000 = "SELECT COUNT(*) AS count_electric_50001_100000 FROM view WHERE V_Electric_per_year BETWEEN 50001 AND 100000";
$result_count_electric_50001_100000 = $objConnect->query($strSQL_count_electric_50001_100000);
$row_count_electric_50001_100000 = $result_count_electric_50001_100000->fetch_assoc();
$count_electric_50001_100000 = $row_count_electric_50001_100000['count_electric_50001_100000'];

$strSQL_count_electric_100001_200000 = "SELECT COUNT(*) AS count_electric_100001_200000 FROM view WHERE V_Electric_per_year BETWEEN 100001 AND 200000";
$result_count_electric_100001_200000 = $objConnect->query($strSQL_count_electric_100001_200000);
$row_count_electric_100001_200000 = $result_count_electric_100001_200000->fetch_assoc();
$count_electric_100001_200000 = $row_count_electric_100001_200000['count_electric_100001_200000'];

$strSQL_count_electric_200001_10000000 = "SELECT COUNT(*) AS count_electric_200001_10000000 FROM view WHERE V_Electric_per_year BETWEEN 200001 AND 10000000";
$result_count_electric_200001_10000000 = $objConnect->query($strSQL_count_electric_200001_10000000);
$row_count_electric_200001_10000000 = $result_count_electric_200001_10000000->fetch_assoc();
$count_electric_200001_10000000 = $row_count_electric_200001_10000000['count_electric_200001_10000000'];

// Get count of V_Electric_per_month in specific ranges
$strSQL_count_electric_month_1_10000 = "SELECT COUNT(*) AS count_electric_month_1_10000 FROM view WHERE V_Electric_per_month BETWEEN 1 AND 10000";
$result_count_electric_month_1_10000 = $objConnect->query($strSQL_count_electric_month_1_10000);
$row_count_electric_month_1_10000 = $result_count_electric_month_1_10000->fetch_assoc();
$count_electric_month_1_10000 = $row_count_electric_month_1_10000['count_electric_month_1_10000'];

$strSQL_count_electric_month_10001_30000 = "SELECT COUNT(*) AS count_electric_month_10001_30000 FROM view WHERE V_Electric_per_month BETWEEN 10001 AND 30000";
$result_count_electric_month_10001_30000 = $objConnect->query($strSQL_count_electric_month_10001_30000);
$row_count_electric_month_10001_30000 = $result_count_electric_month_10001_30000->fetch_assoc();
$count_electric_month_10001_30000 = $row_count_electric_month_10001_30000['count_electric_month_10001_30000'];

$strSQL_count_electric_month_30001_50000 = "SELECT COUNT(*) AS count_electric_month_30001_50000 FROM view WHERE V_Electric_per_month BETWEEN 30001 AND 50000";
$result_count_electric_month_30001_50000 = $objConnect->query($strSQL_count_electric_month_30001_50000);
$row_count_electric_month_30001_50000 = $result_count_electric_month_30001_50000->fetch_assoc();
$count_electric_month_30001_50000 = $row_count_electric_month_30001_50000['count_electric_month_30001_50000'];

$strSQL_count_electric_month_50001_100000 = "SELECT COUNT(*) AS count_electric_month_50001_100000 FROM view WHERE V_Electric_per_month BETWEEN 50001 AND 100000";
$result_count_electric_month_50001_100000 = $objConnect->query($strSQL_count_electric_month_50001_100000);
$row_count_electric_month_50001_100000 = $result_count_electric_month_50001_100000->fetch_assoc();
$count_electric_month_50001_100000 = $row_count_electric_month_50001_100000['count_electric_month_50001_100000'];

$strSQL_count_electric_month_100001_200000 = "SELECT COUNT(*) AS count_electric_month_100001_200000 FROM view WHERE V_Electric_per_month BETWEEN 100001 AND 200000";
$result_count_electric_month_100001_200000 = $objConnect->query($strSQL_count_electric_month_100001_200000);
$row_count_electric_month_100001_200000 = $result_count_electric_month_100001_200000->fetch_assoc();
$count_electric_month_100001_200000 = $row_count_electric_month_100001_200000['count_electric_month_100001_200000'];

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
                <p>224 แห่ง</p>
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
                    window.location.href = `details/details_task.php?status=${encodeURIComponent(label)}`;
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
            labels: ['0-99', '100-199', '200-299', '300-5000'],
            datasets: [{
                label: 'Total Peak per Month',
                data: [
                    <?php echo $count_0_99; ?>,
                    <?php echo $count_100_199; ?>,
                    <?php echo $count_200_299; ?>,
                    <?php echo $count_300_5000; ?>
                ],
                backgroundColor: ['#ea4335', '#fbbc05', '#4285f4', '#34a853']
            }]
        },
        options: {
            responsive: true,
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const segmentIndex = elements[0].index;
                    const label = newDoughnutChart.data.labels[segmentIndex];
                    window.location.href = `details/details.php?range=${label}`;
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

    const electricYearLabels = ['1-10,000', '10,001-30,000', '30,001-50,000', '50,001-100,000', '100,001-200,000', '200,001-10,000,000'];
    const electricYearCounts = [
        <?php echo $count_electric_1_10000; ?>,
        <?php echo $count_electric_10001_30000; ?>,
        <?php echo $count_electric_30001_50000; ?>,
        <?php echo $count_electric_50001_100000; ?>,
        <?php echo $count_electric_100001_200000; ?>,
        <?php echo $count_electric_200001_10000000; ?>
    ];

    const ctxElectricYear = document.getElementById('electric-year-chart').getContext('2d');
    const electricYearChart = new Chart(ctxElectricYear, {
        type: 'pie',
        data: {
            labels: electricYearLabels,
            datasets: [{
                label: 'Total Electric per Year',
                data: electricYearCounts,
                backgroundColor: ['#34a853', '#50C878', '#008b85', '#509ce4',  '#085298', '#0b2c4b']
            }]
        },
        options: {
            responsive: true,
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const segmentIndex = elements[0].index;
                    const label = electricYearChart.data.labels[segmentIndex];
                    window.location.href = `details/details_electric.php?range=${encodeURIComponent(label)}`;
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'ค่าใช้ไฟฟ้าต่อปี'
                }
            }
        }
    });

    // Chart data for V_Electric_per_month
    const electricMonthLabels = ['1-10,000', '10,001-30,000', '30,001-50,000', '50,001-100,000', '100,001-200,000', '200,001-10,000,000'];
    const electricMonthCounts = [
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
                backgroundColor: ['#9fb8d2', '#7e93ba', '#4a5f90', '#284377', '#1b2f55', '#0b2c4b']
            }]
        },
        options: {
            responsive: true,
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const segmentIndex = elements[0].index;
                    const label = electricMonthChart.data.labels[segmentIndex];
                    window.location.href = `details/details_electric.php?range=${encodeURIComponent(label)}`;
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'ค่าใช้ไฟฟ้าต่อเดือน'
                }
            }
        }
    });
</script>
</body>
</html>