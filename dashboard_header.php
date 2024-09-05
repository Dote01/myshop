<?php

require_once 'config.php';
// Check if session variables are set to avoid accessing undefined indexes
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$role_id = isset($_SESSION['role']) ? $_SESSION['role'] : null;

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
    <title><?php echo $role_name; ?> Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eaeaea;
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

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
            position: relative;
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
            background-color: #004d40;
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

        section.dashboard {
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .dashboard-heading {
            font-size: 2.2em;
            color: #004d40;
            margin-bottom: 20px;
            font-weight: 700;
            border-bottom: 3px solid #00796b;
            padding-bottom: 10px;
        }

        .dashboard-intro {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 20px;
            font-weight: 300;
        }

        .tab-container {
            display: flex;
            border-bottom: 2px solid #004d40;
            margin-bottom: 20px;
        }

        .tab-button {
            flex: 1;
            padding: 12px 20px;
            background-color: #004d40;
            color: #fff;
            text-align: center;
            cursor: pointer;
            border: none;
            border-radius: 5px 5px 0 0;
            transition: background-color 0.3s;
            font-size: 1em;
            font-weight: 500;
        }

        .tab-button:hover {
            background-color: #003d33;
        }

        .tab-content {
            display: none;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }

        .tab-content.active {
            display: block;
        }

        .dashboard-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .dashboard-links li {
            margin: 20px 0;
        }

        .dashboard-link {
            display: inline-block;
            padding: 14px 24px;
            background-color: #004d40;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-weight: 500;
        }

        .dashboard-link:hover {
            background-color: #003d33;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
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
            background-color: #fafafa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .summary-card h3 {
            font-size: 1.4em;
            color: #004d40;
            margin-bottom: 15px;
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

        .progress-text {
            font-size: 1em;
            color: #333;
            font-weight: 500;
        }

        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .activity-list li {
            background-color: #fff;
            border-radius: 10px;
            padding: 10px;
            margin: 8px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 1em;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <h1>MyShop - <?php echo $role_name; ?> Dashboard</h1>
        </div>
        <nav>
            <ul>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-home"></i> Home</a>
                    <div class="dropdown-menu">
                        <a href="orders.php">Orders</a>
                        <a href="markets.php">Markets</a>
                        <a href="discussion.php">Discussion</a>
                        <a href="news.php">News</a>
                        <a href="analysis.php">Analysis</a>
                        <a href="faq.php">FAQ</a>
                        <a href="stock_management.php">Stock Management</a>
                    </div>
                </li>
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <?php if ($role_id == 2): ?>
                <?php elseif ($role_id == 3): ?>
                    <li><a href="manage_users.php"><i class="fas fa-users-cog"></i> Manage Users</a></li>
                <?php endif; ?>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>
    <section class="dashboard">
        <div class="dashboard-heading">Welcome to Your Dashboard</div>
        <div class="dashboard-intro">
            Here you can manage your activities, view reports, and more.
        </div>
        <div class="tab-container">
            <button class="tab-button" data-tab="tab1">Overview</button>
            <button class="tab-button" data-tab="tab2">Reports</button>
            <button class="tab-button" data-tab="tab3">Settings</button>
        </div>
        <div class="tab-content" id="tab1">
            <h2>Overview</h2>
            <p>Here is an overview of your recent activities and updates.</p>
        </div>
        <div class="tab-content" id="tab2">
            <h2>Reports</h2>
            <p>Check out your detailed reports and analytics here.</p>
        </div>
        <div class="tab-content" id="tab3">
            <h2>Settings</h2>
            <p>Manage your account settings and preferences here.</p>
        </div>
    </section>
    <script>
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.querySelector(`#${tabId}`).classList.add('active');
            });
        });
    </script>
</body>
</html>
