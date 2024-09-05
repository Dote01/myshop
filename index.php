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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Basic Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f4f7;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header Styles */
        header {
            background: #007BFF;
            color: #fff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 1.8em;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        nav ul li {
            position: relative;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 25px;
            display: block;
            transition: background-color 0.3s, color 0.3s, transform 0.3s;
        }

        nav ul li a:hover {
            background-color: #0056b3;
            color: #fff;
            transform: translateY(-3px);
        }

        .search-bar {
            border: none;
            padding: 10px;
            border-radius: 25px;
            margin-left: 20px;
            width: 200px;
        }

        /* Main Content */
        .dashboard {
            flex: 1;
            padding: 40px 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            background-color: #ffffff;
        }

        .dashboard-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-left: 4px solid #007BFF;
            width: 100%;
            max-width: 350px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        .dashboard-card h3 {
            font-size: 1.6em;
            color: #007BFF;
            margin-bottom: 10px;
        }

        .dashboard-card p {
            color: #666;
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 30px 20px;
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: center;
        }

        .footer-section {
            flex: 1;
            min-width: 200px;
            margin: 15px;
        }

        .footer-section h4 {
            margin-bottom: 15px;
            font-size: 1.2em;
        }

        .footer-section a {
            color: #fff;
            text-decoration: none;
            margin: 5px 0;
            display: block;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #007BFF;
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
                <li><a href="analysis.php"><i class="fas fa-chart-line"></i> Analysis</a></li>
                <li><a href="news.php"><i class="fas fa-newspaper"></i> News</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <?php if ($role_id == 2): ?>
                    <li><a href="create_discussion.php"><i class="fas fa-comments"></i> Discussion</a></li>
                <?php elseif ($role_id == 3): ?>
                    <li><a href="manage_users.php"><i class="fas fa-users-cog"></i> Manage Users</a></li>
                <?php endif; ?>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <input type="text" class="search-bar" placeholder="Search...">
    </header>

    <!-- Main Dashboard Section -->
    <div class="dashboard">
        <div class="dashboard-card">
            <h3>Manage Products</h3>
            <p>Access and manage your product listings.</p>
        </div>
        <div class="dashboard-card">
            <h3>View Sales</h3>
            <p>Analyze your sales performance and trends.</p>
        </div>
        <!-- Add more dashboard cards as needed -->
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>Follow Us</h4>
                <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
                <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
            </div>
            <div class="footer-section">
                <h4>Legal</h4>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="#about">About Us</a>
                <a href="#contact">Contact Us</a>
                <a href="#faq">FAQ</a>
            </div>
        </div>
    </footer>
</body>
</html>
