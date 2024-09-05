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
    <title><?php echo $role_name; ?> Dashboard - MyShop</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f2f4f7;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header Styles */
        header {
            background: linear-gradient(to right, #4CAF50, #2e8b57);
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 2px solid #2e8b57;
        }
        .logo {
            font-size: 1.8em;
            font-weight: bold;
            letter-spacing: 1px;
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
            padding: 12px 24px;
            border-radius: 12px;
            transition: background-color 0.3s, color 0.3s;
            display: block;
            font-size: 16px;
        }
        nav ul li a:hover {
            background-color: #45a049;
            color: #fff;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 220px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
            top: 100%;
            left: 0;
        }
        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Dashboard Styles */
        .dashboard {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .dashboard-container {
            max-width: 960px;
            width: 100%;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border: 3px solid #4CAF50;
            animation: fadeInUp 1s ease-out;
            text-align: center;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .dashboard-container h2 {
            font-size: 2.4em;
            color: #4CAF50;
            margin-bottom: 20px;
            font-weight: 700;
        }
        .dashboard-container p {
            font-size: 1.2em;
            color: #666;
            margin-bottom: 20px;
            font-weight: 300;
        }
        .home-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .home-button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #2c6b59;
            color: #fff;
            text-decoration: none;
            border-radius: 12px;
            font-size: 18px;
            transition: background-color 0.4s, transform 0.4s, box-shadow 0.4s;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            font-weight: 500;
        }
        .home-button:hover {
            background-color: #1f5d49;
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .dashboard-summary {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .summary-card {
            flex: 1;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: left;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
        }
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        .summary-card h3 {
            font-size: 1.6em;
            color: #2c6b59;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .summary-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .summary-list li {
            margin: 10px 0;
        }
        .summary-link {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            transition: background-color 0.4s, transform 0.4s, box-shadow 0.4s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-weight: 500;
        }
        .summary-link:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: auto;
            position: relative;
            border-top: 2px solid #4CAF50;
        }
        .footer-content {
            padding: 0 20px;
        }
        .footer-content p {
            margin: 10px 0;
        }
        .social-media a, .legal a, .quick-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 12px;
            font-size: 14px;
        }
        .social-media a:hover, .legal a:hover, .quick-links a:hover {
            text-decoration: underline;
        }
        .newsletter form {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .newsletter input {
            padding: 10px;
            border-radius: 4px;
            border: none;
            margin-right: 10px;
            flex: 1;
            font-size: 14px;
        }
        .newsletter button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .newsletter button:hover {
            background-color: #45a049;
        }

        /* Additional Styles for Advanced Features */
        .widget {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .chart-container {
            margin-top: 20px;
            position: relative;
            height: 300px;
        }
    </style>
    <!-- Include Chart.js for interactive charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">MyShop</div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li class="dropdown">
                    <a href="#">Manage Orders</a>
                    <div class="dropdown-content">
                        <a href="pending_orders.php">Pending Orders</a>
                        <a href="verified_orders.php">Verified Orders</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#">Market</a>
                    <div class="dropdown-content">
                        <a href="buyers.php">Buyers</a>
                        <a href="sellers.php">Sellers</a>
                    </div>
                </li>
                <li><a href="transportation.php">Transportation</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Section -->
    <div class="dashboard">
        <div class="dashboard-container">
            <h2><?php echo $role_name; ?> Dashboard</h2>
            <p>Welcome, <?php echo htmlspecialchars($username); ?>! Here’s an overview of your activities and available features:</p>
            
            <!-- Dashboard Widgets -->
            <div class="widget">
                <h3>Sales Overview</h3>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <div class="home-buttons">
                <a href="orders.php" class="home-button">Manage Orders</a>
                <a href="market.php" class="home-button">User Market</a>
                <a href="transportation.php" class="home-button">Transportation Activity</a>
            </div>

            <div class="dashboard-summary">
                <!-- Orders Summary -->
                <div class="summary-card">
                    <h3>Orders</h3>
                    <ul class="summary-list">
                        <li><a href="pending_orders.php" class="summary-link">Pending Orders</a></li>
                        <li><a href="verified_orders.php" class="summary-link">Verified Orders</a></li>
                    </ul>
                </div>

                <!-- User Market Summary -->
                <div class="summary-card">
                    <h3>User Market</h3>
                    <ul class="summary-list">
                        <li><a href="buyers.php" class="summary-link">Buyers</a></li>
                        <li><a href="sellers.php" class="summary-link">Sellers</a></li>
                    </ul>
                </div>

                <!-- Transportation Activity Summary -->
                <div class="summary-card">
                    <h3>Transportation Activity</h3>
                    <p>Track your shipments and get directions easily.</p>
                    <a href="transportation.php" class="summary-link">Track Shipments</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 MyShop. All rights reserved.</p>
            <div class="social-media">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">Instagram</a>
            </div>
            <div class="legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
            <div class="newsletter">
                <form action="subscribe.php" method="POST">
                    <input type="email" name="email" placeholder="Subscribe to our newsletter" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </footer>

    <script>
        // Sample data for sales chart
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Sales',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
