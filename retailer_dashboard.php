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
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #004d40;
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo h1 {
            font-size: 1.8em;
            margin: 0;
            font-weight: 700;
            background: -webkit-linear-gradient(left, #00796b, #004d40);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
        }

        nav ul li {
            position: relative;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1em;
        }

        nav ul li a i {
            font-size: 1.3em;
        }

        nav ul li a:hover {
            background-color: #00796b;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #00796b;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            padding: 10px;
            min-width: 200px;
        }

        .dropdown-menu a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .dropdown-menu a:hover {
            background-color: #004d40;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dashboard-content {
            padding: 20px;
            max-width: 1200px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .dashboard-heading {
            font-size: 2em;
            color: #004d40;
            margin-bottom: 10px;
            font-weight: 700;
            border-bottom: 3px solid #00796b;
            padding-bottom: 10px;
        }

        .dashboard-intro {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 20px;
        }

        .dashboard-links {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .dashboard-link {
            display: inline-block;
            padding: 15px 25px;
            background-color: #004d40;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-weight: 500;
        }

        .dashboard-link:hover {
            background-color: #00796b;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .dashboard-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .summary-card {
            flex: 1;
            background-color: #fafafa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .summary-card h3 {
            font-size: 1.4em;
            color: #004d40;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .progress-bar {
            background-color: #eee;
            border-radius: 10px;
            overflow: hidden;
            height: 8px;
            margin-bottom: 10px;
        }

        .progress {
            height: 100%;
            background-color: #004d40;
            border-radius: 10px;
            transition: width 0.4s;
        }

        .summary-link {
            color: #00796b;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-top: 10px;
        }

        .summary-link:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #004d40;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: auto;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.2);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
            font-size: 0.9em;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include 'dashboard_header.php'; ?>

<div class="dashboard-content">
    <h1 class="dashboard-heading">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <p class="dashboard-intro">Here’s a quick overview of your dashboard.</p>

    <div class="dashboard-links">
        <a href="pending_orders.php" class="dashboard-link">Pending Orders</a>
        <a href="verified_orders.php" class="dashboard-link">Completed Orders</a>
        <a href="product.php" class="dashboard-link">View Products</a>
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
            <a href="terms.php">Terms of Service</a>
            <a href="contact.php">Contact Us</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.js"></script>
<script>
    function createGradient(ctx, color1, color2) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    }

    function fetchData() {
        return fetch('data.php')
            .then(response => response.json())
            .then(data => {
                return {
                    labels: data.labels,
                    datasets: [{
                        label: 'Sales',
                        data: data.data,
                        borderColor: '#4CAF50',
                        backgroundColor: createGradient(ctx, 'rgba(76, 175, 80, 0.2)', 'rgba(0, 150, 136, 0.2)'),
                        borderWidth: 2,
                        pointBackgroundColor: '#4CAF50',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#4CAF50'
                    }]
                };
            });
    }

    const ctx = document.getElementById('salesChart').getContext('2d');

    fetchData().then(data => {
        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: '#eee'
                        },
                        ticks: {
                            color: '#333',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#eee'
                        },
                        ticks: {
                            color: '#333',
                            font: {
                                weight: 'bold'
                            },
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#333',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#333',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(tooltipItem) {
                                return 'Sales: $' + tooltipItem.raw;
                            }
                        }
                    }
                },
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear',
                        from: 0.5,
                        to: 0,
                        loop: true
                    }
                }
            }
        });
    });
    
</script>
</body>
</html>
