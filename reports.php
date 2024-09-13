<?php
require_once 'config.php';

session_start(); // Ensure session is started

if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Example data
$reportData = [
    'sales' => [
        'total' => 150000,
        'byCategory' => [
            'Electronics' => 50000,
            'Clothing' => 30000,
            'Home & Kitchen' => 20000,
            'Books' => 10000,
            'Others' => 5000
        ]
    ],
    'customers' => [
        'total' => 800,
        'byRegion' => [
            'North America' => 300,
            'Europe' => 200,
            'Asia' => 150,
            'South America' => 100,
            'Others' => 50
        ]
    ],
    'orders' => [
        'total' => 1000,
        'byStatus' => [
            'Completed' => 800,
            'Pending' => 150,
            'Cancelled' => 50
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background: linear-gradient(to right, #004d40, #00796b);
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo h1 {
            font-size: 2em;
            margin: 0;
            font-weight: 700;
            background: -webkit-linear-gradient(left, #00796b, #004d40);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        section {
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .report-heading {
            font-size: 2.2em;
            color: #004d40;
            margin-bottom: 20px;
            font-weight: 700;
            border-bottom: 3px solid #00796b;
            padding-bottom: 10px;
        }

        .chart-container {
            margin: 30px 0;
        }

        .chart-container canvas {
            max-width: 100%;
            border-radius: 10px;
        }

        .metrics {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            flex: 1 1 calc(33% - 20px);
            background-color: #fafafa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .metric-card h3 {
            font-size: 1.6em;
            color: #004d40;
            margin-bottom: 10px;
        }

        .metric-card p {
            font-size: 1.2em;
            color: #333;
            margin: 0;
        }
    </style>
</head>
<body>
    <?php include 'dashboard_header.php'; ?>

    <section>
        <div class="report-heading">Reports Overview</div>

        <div class="metrics">
            <div class="metric-card">
                <h3>Total Sales</h3>
                <p>$<?php echo number_format($reportData['sales']['total'], 2); ?></p>
            </div>
            <div class="metric-card">
                <h3>Total Customers</h3>
                <p><?php echo number_format($reportData['customers']['total']); ?></p>
            </div>
            <div class="metric-card">
                <h3>Total Orders</h3>
                <p><?php echo number_format($reportData['orders']['total']); ?></p>
            </div>
        </div>

        <div class="chart-container">
            <h2>Sales by Category</h2>
            <canvas id="salesCategoryChart"></canvas>
        </div>

        <div class="chart-container">
            <h2>Customers by Region</h2>
            <canvas id="customersRegionChart"></canvas>
        </div>

        <div class="chart-container">
            <h2>Orders by Status</h2>
            <canvas id="ordersStatusChart"></canvas>
        </div>
    </section>

    <?php include 'dashboard_footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    // Sample data and labels
    let salesCategories = <?php echo json_encode(array_keys($reportData['sales']['byCategory'])); ?>;
    let salesData = <?php echo json_encode(array_values($reportData['sales']['byCategory'])); ?>;
    
    let customerRegions = <?php echo json_encode(array_keys($reportData['customers']['byRegion'])); ?>;
    let customerData = <?php echo json_encode(array_values($reportData['customers']['byRegion'])); ?>;
    
    let orderStatuses = <?php echo json_encode(array_keys($reportData['orders']['byStatus'])); ?>;
    let orderData = <?php echo json_encode(array_values($reportData['orders']['byStatus'])); ?>;

    // Chart for Sales by Category
    const ctxSalesCategory = document.getElementById('salesCategoryChart').getContext('2d');
    const salesCategoryChart = new Chart(ctxSalesCategory, {
        type: 'pie',
        data: {
            labels: salesCategories,
            datasets: [{
                label: 'Sales by Category',
                data: salesData,
                backgroundColor: ['#00796b', '#004d40', '#009688', '#004d40', '#003d33'],
            }]
        },
        options: {
            responsive: true
        }
    });

    // Chart for Customers by Region
    const ctxCustomersRegion = document.getElementById('customersRegionChart').getContext('2d');
    const customersRegionChart = new Chart(ctxCustomersRegion, {
        type: 'bar',
        data: {
            labels: customerRegions,
            datasets: [{
                label: 'Customers by Region',
                data: customerData,
                backgroundColor: '#004d40',
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Chart for Orders by Status
    const ctxOrdersStatus = document.getElementById('ordersStatusChart').getContext('2d');
    const ordersStatusChart = new Chart(ctxOrdersStatus, {
        type: 'doughnut',
        data: {
            labels: orderStatuses,
            datasets: [{
                label: 'Orders by Status',
                data: orderData,
                backgroundColor: ['#00796b', '#004d40', '#009688'],
            }]
        },
        options: {
            responsive: true
        }
    });

    // Function to simulate updating data for Sales by Category
    function updateSalesCategoryChart() {
        salesData = salesData.map(value => Math.floor(Math.random() * 10000) + 5000);
        salesCategoryChart.data.datasets[0].data = salesData;
        salesCategoryChart.update();
    }

    // Function to simulate updating data for Customers by Region
    function updateCustomersRegionChart() {
        customerData = customerData.map(value => Math.floor(Math.random() * 100) + 20);
        customersRegionChart.data.datasets[0].data = customerData;
        customersRegionChart.update();
    }

    // Function to simulate updating data for Orders by Status
    function updateOrdersStatusChart() {
        orderData = orderData.map(value => Math.floor(Math.random() * 100) + 10);
        ordersStatusChart.data.datasets[0].data = orderData;
        ordersStatusChart.update();
    }

    // Simulate chart updates every 5 seconds
    setInterval(() => {
        updateSalesCategoryChart();
        updateCustomersRegionChart();
        updateOrdersStatusChart();
    }, 5000);
});
</script>
