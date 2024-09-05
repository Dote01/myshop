<?php include 'dashboard_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales & Engagement Analysis</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include external CSS for better organization -->
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6f7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Header Styles */
        header {
            background: linear-gradient(to right, #28a745, #218838);
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo img {
            height: 60px;
            transition: transform 0.3s;
        }

        .logo img:hover {
            transform: scale(1.1);
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        nav ul li a:hover {
            background-color: #218838;
            color: #fff;
        }

        /* Analysis Page Styles */
        .analysis-page-container {
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            margin: 20px;
        }

        .analysis-page-container h2 {
            margin-bottom: 20px;
            color: #28a745;
        }

        .chart-container {
            margin-bottom: 30px;
        }

        canvas {
            width: 100% !important;
            height: 400px !important;
        }

        .chart-controls {
            margin-bottom: 20px;
        }

        .chart-controls select {
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <!-- Analysis Page Content -->
    <div class="analysis-page-container">
        <h2>Sales & Engagement Analysis</h2>
        
        <div class="chart-controls">
            <label for="chartType">Choose chart type:</label>
            <select id="chartType">
                <option value="line" selected>Line</option>
                <option value="pie">Pie</option>
                <option value="bar">Bar</option>
                <option value="doughnut">Doughnut</option>
                <option value="candlestick">Candlestick</option>
            </select>
        </div>
        
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
        
        <div class="chart-container">
            <canvas id="engagementChart"></canvas>
        </div>
        
        <div class="chart-container">
            <canvas id="purchaseChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-financial@3.1.0/dist/chartjs-chart-financial.min.js"></script>
    <script>
        // Chart data
        const data = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Sales',
                data: [1200, 1500, 1800, 1600, 1400, 1700, 2000],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        const engagementData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Engagement',
                data: [300, 400, 500, 600, 700, 600, 500],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        const purchaseData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Purchases',
                data: [800, 1000, 1200, 1300, 1200, 1100, 1400],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Common options for non-financial charts
        const commonOptions = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Financial chart options for candlestick
        const candlestickOptions = {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'month'
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        };

        // Create charts with initial chart type
        let chartType = 'line';

        let salesChart = new Chart(document.getElementById('salesChart').getContext('2d'), {
            type: chartType,
            data: data,
            options: chartType === 'candlestick' ? candlestickOptions : commonOptions
        });

        let engagementChart = new Chart(document.getElementById('engagementChart').getContext('2d'), {
            type: chartType,
            data: engagementData,
            options: chartType === 'candlestick' ? candlestickOptions : commonOptions
        });

        let purchaseChart = new Chart(document.getElementById('purchaseChart').getContext('2d'), {
            type: chartType,
            data: purchaseData,
            options: chartType === 'candlestick' ? candlestickOptions : commonOptions
        });

        // Update charts based on selected chart type
        document.getElementById('chartType').addEventListener('change', function() {
            chartType = this.value;

            // Destroy existing charts
            salesChart.destroy();
            engagementChart.destroy();
            purchaseChart.destroy();

            // Create new charts based on selected chart type
            salesChart = new Chart(document.getElementById('salesChart').getContext('2d'), {
                type: chartType,
                data: data,
                options: chartType === 'candlestick' ? candlestickOptions : commonOptions
            });

            engagementChart = new Chart(document.getElementById('engagementChart').getContext('2d'), {
                type: chartType,
                data: engagementData,
                options: chartType === 'candlestick' ? candlestickOptions : commonOptions
            });

            purchaseChart = new Chart(document.getElementById('purchaseChart').getContext('2d'), {
                type: chartType,
                data: purchaseData,
                options: chartType === 'candlestick' ? candlestickOptions : commonOptions
            });
        });
    </script>
</body>
</html>
