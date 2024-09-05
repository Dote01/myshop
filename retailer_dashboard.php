<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role_id = $_SESSION['role'];

$role_name = "";
switch ($role_id) {
    case 1:
        $role_name = "User";
        break;
    case 2:
        $role_name = "Retailer";
        break;
    case 3:
        $role_name = "Admin";
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($role_name); ?> Dashboard - MyShop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.css">
    <style>
        /* General and Other Styles Here */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Rest of your styles go here */
    </style>
</head>
<body>

<?php include 'dashboard_header.php'; ?>

<div class="dashboard-content">
    <h1 class="dashboard-heading">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <p class="dashboard-intro">Here’s a quick overview of your dashboard.</p>

    <div class="dashboard-links">
        <a href="pending_orders.php" class="dashboard-link">Pending Orders</a>
        <a href="completed_orders.php" class="dashboard-link">Completed Orders</a>
        <a href="products.php" class="dashboard-link">View Products</a>
        <a href="add_product.php" class="dashboard-link">Add Product</a>
    </div>

    <div class="dashboard-summary">
        <div class="summary-card">
            <h3>Order Summary</h3>
            <div class="progress-bar">
                <div class="progress" style="width: 75%;"></div>
            </div>
            <p>75% of your orders are completed.</p>
            <a href="orders.php" class="summary-link">View Details</a>
        </div>
        <div class="summary-card">
            <h3>Product Stats</h3>
            <div class="progress-bar">
                <div class="progress" style="width: 50%;"></div>
            </div>
            <p>50% of your products are in stock.</p>
            <a href="products.php" class="summary-link">View Details</a>
        </div>
    </div>
    
    <!-- Placeholder for advanced charts -->
    <div style="margin-top: 40px;">
        <h2>Sales Overview</h2>
        <canvas id="salesChart" width="400" height="200"></canvas>
    </div>
</div>

<footer>
    <div class="footer-content">
        <p>&copy; 2024 MyShop. All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact Us</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.js"></script>
<script>
    // Example of advanced chart with Chart.js
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Sales',
                data: [50, 60, 70, 80, 90, 100],
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.2)',
                borderWidth: 2
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
            },
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Sales: $' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
</script>
</body>
</html>
