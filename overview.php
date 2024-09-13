<?php
require_once 'configs.php';

session_start(); // Ensure session is started

if (!isset($_SESSION['user_id']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Establish a database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch total orders
$result = $mysqli->query("SELECT COUNT(*) as total_orders FROM orders");
if ($result) {
    $totalOrdersRow = $result->fetch_assoc();
    $totalOrders = $totalOrdersRow['total_orders'] ?? 0;
} else {
    die("Error fetching total orders: " . $mysqli->error);
}

// Fetch total revenue
$result = $mysqli->query("SELECT SUM(total_price) as total_revenue FROM orders");
if ($result) {
    $totalRevenueRow = $result->fetch_assoc();
    $totalRevenue = $totalRevenueRow['total_revenue'] ?? 0;
} else {
    die("Error fetching total revenue: " . $mysqli->error);
}

// Fetch recent activity
$recentActivity = [];
$result = $mysqli->query("SELECT date, activity FROM activity_log ORDER BY date DESC LIMIT 10");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recentActivity[] = $row;
    }
} else {
    die("Error fetching recent activity: " . $mysqli->error);
}

// Close the connection
$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">
    <style>
        /* Your CSS styles here */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        section {
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .overview-heading {
            font-size: 2.2em;
            color: #004d40;
            margin-bottom: 20px;
            font-weight: 700;
            border-bottom: 3px solid #00796b;
            padding-bottom: 10px;
        }

        .overview-metrics {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            flex: 1;
            background-color: #fafafa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
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

        .chart-container {
            margin: 30px 0;
        }

        .chart-container canvas {
            max-width: 100%;
            border-radius: 10px;
        }

        .recent-activity {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .recent-activity h2 {
            font-size: 1.8em;
            color: #004d40;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .recent-activity li {
            background-color: #fff;
            border-radius: 10px;
            padding: 10px;
            margin: 8px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <?php include 'dashboard_header.php'; ?>

    <section>
        <div class="overview-heading">Website Overview</div>
        <div class="overview-metrics">
            <div class="metric-card">
                <h3>Total Orders</h3>
                <p><?php echo number_format($totalOrders); ?></p>
            </div>
            <div class="metric-card">
                <h3>Total Revenue</h3>
                <p>$<?php echo number_format($totalRevenue, 2); ?></p>
            </div>
        </div>

        <div class="chart-container">
            <h2>Revenue Overview</h2>
            <canvas id="revenueChart"></canvas>
        </div>

        <div class="chart-container">
            <h2>Order Trends</h2>
            <canvas id="ordersChart"></canvas>
        </div>

        <div class="recent-activity">
            <h2>Recent Activity</h2>
            <ul>
                <?php foreach ($recentActivity as $activity): ?>
                    <li><?php echo htmlspecialchars($activity['date'] . ': ' . $activity['activity']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <?php include 'dashboard_footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', (event) => {
    // Handle click event on Manage Orders button
    const manageOrdersButton = document.querySelector('a[href="orders.php"]');
    manageOrdersButton.addEventListener('click', (event) => {
        alert('Navigating to Manage Orders');
        // Uncomment the following line if you need to prevent default navigation
        // event.preventDefault();
    });

    // Sample data and labels (you may replace this with actual data fetching)
    var revenueLabels = ['January', 'February', 'March', 'April', 'May', 'June'];
    var revenueData = [10000, 15000, 12000, 17000, 16000, 18000];

    var ordersLabels = ['January', 'February', 'March', 'April', 'May', 'June'];
    var ordersData = [30, 45, 40, 60, 55, 70];

    // Create Revenue Chart
    var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Revenue',
                data: revenueData,
                borderColor: 'rgba(0, 121, 107, 1)',
                backgroundColor: 'rgba(0, 121, 107, 0.2)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(0, 121, 107, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.1)' },
                    ticks: { maxRotation: 45, minRotation: 0 }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.1)' },
                    ticks: {
                        callback: function(value) { return `$${value.toLocaleString()}`; }
                    }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return `Revenue: $${tooltipItem.raw.toLocaleString()}`;
                        }
                    }
                }
            }
        }
    });

    // Create Orders Chart
    var ctxOrders = document.getElementById('ordersChart').getContext('2d');
    var ordersChart = new Chart(ctxOrders, {
        type: 'bar',
        data: {
            labels: ordersLabels,
            datasets: [{
                label: 'Orders',
                data: ordersData,
                backgroundColor: 'rgba(0, 77, 64, 0.6)',
                borderColor: 'rgba(0, 77, 64, 1)',
                borderWidth: 1,
                barPercentage: 0.8
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.1)' },
                    ticks: { maxRotation: 45, minRotation: 0 }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.1)' }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return `Orders: ${tooltipItem.raw}`;
                        }
                    }
                }
            }
        }
    });

    // Function to simulate updating data
    function updateCharts() {
        // Simulate new data (you would replace this with actual data retrieval)
        var newRevenueDataPoint = Math.floor(Math.random() * 10000) + 5000;
        var newOrdersDataPoint = Math.floor(Math.random() * 50) + 20;

        // Update revenue data
        revenueData.shift(); // Remove the oldest data point
        revenueData.push(newRevenueDataPoint); // Add new data point
        revenueLabels.shift(); // Remove the oldest label
        revenueLabels.push(getNextMonth(revenueLabels[revenueLabels.length - 1])); // Add new label

        // Update orders data
        ordersData.shift(); // Remove the oldest data point
        ordersData.push(newOrdersDataPoint); // Add new data point
        ordersLabels.shift(); // Remove the oldest label
        ordersLabels.push(getNextMonth(ordersLabels[ordersLabels.length - 1])); // Add new label

        // Update the charts with new data
        revenueChart.update();
        ordersChart.update();
    }

    // Function to get the next month label
    function getNextMonth(currentMonth) {
        var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var index = months.indexOf(currentMonth);
        return months[(index + 1) % months.length];
    }

    // Update the charts every 5 seconds
    setInterval(updateCharts, 5000);
});
</script>
