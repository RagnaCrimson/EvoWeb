<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="icon" type="image/jpg" href="./img/logo-eet.jpg">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-doughnutlabel@1.0.0"></script>
    <script src="js/script.js"></script>
</head>
<body class="bgcolor">
    <?php include 'header.php'; ?>
    <?php include 'sort_by.php'; ?>

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
                <p id="total-count"></p>
            </div>
            <div class="chart-container">
                <canvas id="pie-chart" class="doughnut-chart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="efficiency-chart" class="doughnut-chart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="new-doughnut-chart" class="doughnut-chart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="electric-year-chart" class="doughnut-chart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="electric-month-chart" class="doughnut-chart"></canvas>
            </div>

        </div>
    </div>

<script>
    Chart.register(ChartDataLabels);

    const statusLabels = <?php echo $status_labels_json; ?>;
    const statusCounts = <?php echo $status_counts_json; ?>;

    const ctxPie = document.getElementById('pie-chart').getContext('2d');
    const pieChart = new Chart(ctxPie, {
        type: 'doughnut',
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
                },
                datalabels: {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    textAlign: 'center',
                    textAnchor: 'middle'
                }
            }
        }
    });

    const ctxNewDoughnut = document.getElementById('new-doughnut-chart').getContext('2d');
    const newDoughnutChart = new Chart(ctxNewDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['ไม่มีข้อมูล', '1-50', '51-100', '101-150', '151-199', '200-10000'],
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
            cutout: '50%',
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
                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                        const percentage = (value / total * 100).toFixed(2) + '%';
                        return percentage;
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    anchor: 'center', 
                    align: 'center', 
                    padding: {
                        bottom: 5
                    },
                    display: (context) => {
                        return context.dataset.data[context.dataIndex] > 20; // Display only if value is greater than 0
                    }
                }
            }
        }
    });

    const electricYearLabels = ['ไม่มีข้อมูล', '1-100000', '100001-200000', '200001-500000', '500001-1000000', '1000001-90000000'];
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
        type: 'doughnut',
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
                    window.location.href = `details/details_electric_year.php?range=${encodeURIComponent(label)}`;
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'ค่าไฟฟ้าต่อปี'
                },
                datalabels: {
                    formatter: (value, context) => {
                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                        const percentage = (value / total * 100).toFixed(2) + '%';
                        return percentage;
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    textAlign: 'center',
                    textAnchor: 'middle'
                }
            }
        }
    });

    const electricMonthLabels = ['ไม่มีข้อมูล' , '1-10000', '10001-30000', '30001-50000', '50001-100000', '100001-200000', '200001-10000000'];
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
        type: 'doughnut',
        data: {
            labels: electricMonthLabels,
            datasets: [{
                label: 'Total Electric per Month',
                data: electricMonthCounts,
                backgroundColor: ['#450a80', '#2b0057', '#005682', '#e52b50', '#ffbf00', '#8c0404', '#50c878']
            }]
        },
        options: {
            responsive: true,
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const segmentIndex = elements[0].index;
                    const label = electricMonthChart.data.labels[segmentIndex];
                    window.location.href = `details/details_electric_month.php?range=${encodeURIComponent(label)}`;
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'ค่าไฟฟ้าต่อเดือน'
                },
                datalabels: {
                    formatter: (value, context) => {
                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                        const percentage = (value / total * 100).toFixed(2) + '%';
                        return percentage;
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    textAlign: 'center',
                    textAnchor: 'middle'
                }
            }
        }
    });

    const efficiencyLabels = ['ไม่มีศักยภาพ', 'มีศักยภาพ'];
    const efficiencyCounts = [
        <?php echo $count_electric_month_ineffective; ?>,
        <?php echo $count_electric_month_effective; ?>
    ];

    const ctxEfficiency = document.getElementById('efficiency-chart').getContext('2d');
    const efficiencyChart = new Chart(ctxEfficiency, {
        type: 'doughnut',
        data: {
            labels: efficiencyLabels,
            datasets: [{
                label: 'Efficiency',
                data: efficiencyCounts,
                backgroundColor: ['#C70039', '#2ecc71']
            }]
        },
        options: {
            responsive: true,
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const segmentIndex = elements[0].index;
                    const range = efficiencyLabels[segmentIndex];
                    window.location.href = `details/details_effective.php?range=${encodeURIComponent(range)}`;
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'กราฟแสดงประสิทธิภาพ'
                },
                datalabels: {
                    formatter: (value, context) => {
                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                        const percentage = (value / total * 100).toFixed(2) + '%';
                        return percentage;
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    textAlign: 'center',
                    textAnchor: 'middle'
                },
                doughnutlabel: {
                    labels: [
                    {
                        text: '550',
                        font: {
                        size: 20,
                        weight: 'bold',
                        },
                    },
                    {
                        text: 'total',
                    },
                    ],
                },
            }
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
    fetch('total_count.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-count').textContent = data.total_count + ' แห่ง';
        })
        .catch(error => console.error('Error fetching data:', error));
});

</script>
<?php include 'back.html'; ?>
</body>
</html>

