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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header Styles */
        header {
            background: linear-gradient(to right, #4CAF50, #2e8b57);
            color: #fff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: box-shadow 0.3s;
        }

        .logo {
            font-size: 1.5em;
            font-weight: bold;
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
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            display: block;
        }

        nav ul li a:hover {
            background-color: #45a049;
            color: #fff;
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
            max-width: 800px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            padding: 40px;
            border: 4px solid;
            border-image: linear-gradient(to right, #4CAF50, #2e8b57) 1;
            animation: fadeInUp 1s ease-out;
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
            font-size: 2.2em;
            color: #4CAF50;
            margin-bottom: 20px;
            text-align: center;
        }

        .dashboard-container ul {
            list-style: none;
            padding: 0;
        }

        .dashboard-container ul li {
            margin-bottom: 15px;
        }

        .dashboard-container ul li a {
            display: block;
            text-decoration: none;
            color: #333;
            background: #e4e4e4;
            padding: 15px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .dashboard-container ul li a:hover {
            background-color: #d4d4d4;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-top: auto;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            z-index: -1;
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
            margin: 0 10px;
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
        }

        .newsletter button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .newsletter button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">
            MyShop
        </div>
        <nav>
            <ul>
                <li><a href="analysis.php">Analysis</a></li>
                <li><a href="news.php">News</a></li>
                <li><a href="profile.php">Profile</a></li>
                <?php if ($role_id == 2): ?>
                    <li><a href="create_discussion.php">Discussion</a></li>
                <?php elseif ($role_id == 3): ?>
                    <li><a href="manage_users.php">Manage Users</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <!-- Main Section -->
    <div class="dashboard">
        <div class="dashboard-container">
            <h2><?php echo $role_name; ?> Dashboard</h2>
            <ul>
                <?php if ($role_id == 2): ?>
                    <li><a href="manage_products.php">Manage Products</a></li>
                    <li><a href="view_sales.php">View Sales</a></li>
                    <li><a href="stock_management.php">Manage Your Stock</a></li>
                    <li><a href="order_stock.php">Order Stock</a></li>
                <?php elseif ($role_id == 3): ?>
                    <li><a href="manage_users.php">Manage Users</a></li>
                <?php else: ?>
                    <li><a href="view_profile.php">View Profile</a></li>
                <?php endif; ?>
            </ul>
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
            <div class="quick-links">
                <a href="#about">About Us</a>
                <a href="#contact">Contact Us</a>
                <a href="#faq">FAQ</a>
            </div>
            <div class="newsletter">
                <h3>Subscribe to our Newsletter</h3>
                <form action="#" method="post">
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </footer>
</body>
</html>
