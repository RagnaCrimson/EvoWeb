<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>testing</title>
    <style>
        canvas {
            display: block;
            margin: auto;
        }
    </style>
</head>
<body>
    <canvas id="myDoughnutChart" width="400" height="400"></canvas>
    <script>
    const ctx = document.getElementById('myDoughnutChart').getContext('2d');

    const myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Red', 'Blue', 'Yellow'],
            datasets: [{
                data: [10, 20, 30],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw;
                        }
                    }
                },
                legend: {
                    display: true // Show the legend
                },
                datalabels: {
                    display: false // Hide data labels
                },
                // Define custom plugin
                customTextPlugin: {
                    id: 'customTextPlugin',
                    beforeDraw: function(chart) {
                        const ctx = chart.ctx;
                        const chartArea = chart.chartArea;

                        // Set the text and font
                        ctx.save();
                        ctx.font = '30px Arial';
                        ctx.fillStyle = '#000';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        // Draw the text in the center of the doughnut
                        const centerX = (chartArea.left + chartArea.right) / 2;
                        const centerY = (chartArea.top + chartArea.bottom) / 2;
                        ctx.fillText('Center Text', centerX, centerY);

                        ctx.restore();
                    }
                }
            }
        },
        plugins: [Chart.registry.getPlugin('customTextPlugin')]
    });
    </script>
</body>
</html>
