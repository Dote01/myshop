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
    <title><?php echo $role_name; ?> - Transportation Management - MyShop</title>
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
            font-size: 16px;
            text-align: center;
            color: #333;
        }
        .widget h4 {
            font-size: 1.4em;
            color: #4CAF50;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .widget p {
            margin-bottom: 10px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">MyShop</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li class="dropdown">
                    <a href="#">Transport</a>
                    <div class="dropdown-content">
                        <a href="transportation.php">Transportation Management</a>
                        <!-- Add other dropdown items as needed -->
                    </div>
                </li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="dashboard">
        <div class="dashboard-container">
            <h2>Transportation Management</h2>
            <p>Welcome to the Transportation Management Dashboard. Here you can manage and track all your transportation requests and logistics.</p>
            
            <!-- Example of a feature widget -->
            <div class="widget">
                <h4>Pending Requests</h4>
                <p>View and manage pending transportation requests from users and retailers.</p>
                <a href="pending_requests.php" class="summary-link">View Pending Requests</a>
            </div>
            
            <div class="home-buttons">
                <a href="new_request.php" class="home-button">Create New Request</a>
                <a href="request_history.php" class="home-button">Request History</a>
                <a href="logistics_summary.php" class="home-button">Logistics Summary</a>
            </div>

            <!-- Example summary section -->
            <div class="dashboard-summary">
                <div class="summary-card">
                    <h3>Total Requests</h3>
                    <ul class="summary-list">
                        <li>Total Requests: 150</li>
                        <li>Completed Requests: 120</li>
                        <li>Pending Requests: 30</li>
                    </ul>
                </div>
                <div class="summary-card">
                    <h3>Recent Activity</h3>
                    <ul class="summary-list">
                        <li>New request from User123</li>
                        <li>Request completed for Retailer456</li>
                        <li>New logistics update available</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 MyShop. All rights reserved.</p>
            <div class="social-media">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">LinkedIn</a>
            </div>
            <div class="quick-links">
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms.php">Terms of Service</a>
            </div>
            <div class="newsletter">
                <p>Subscribe to our newsletter:</p>
                <form action="subscribe.php" method="post">
                    <input type="email" name="email" placeholder="Enter your email" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </footer>
</body>
</html>
