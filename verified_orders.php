<?php
session_start();
require_once 'configs.php';

// Ensure user is either a retailer or a buyer
if ($_SESSION['role'] !== 'retailer' && $_SESSION['role'] !== 'buyer') {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch verified orders
$sql = "SELECT orders.order_id, products.name, orders.quantity, orders.total_price, orders.order_date 
        FROM orders 
        JOIN products ON orders.product_id = products.product_id 
        WHERE orders.status = 'verified' AND (orders.retailer_id = ? OR orders.buyer_id = ?) 
        ORDER BY orders.order_date DESC";

// Execute the query

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verified Orders - MyShop</title>
    <link rel="stylesheet" href="styles.css">
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
            max-width: 1200px;
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

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .action-link {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        .action-link:hover {
            text-decoration: underline;
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
    </style>
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
            <h2>Verified Orders</h2>
            <p>Below is the list of all verified orders.</p>
            
            <!-- Verified Orders Table -->
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_id']); ?></td>
                            <td><?= htmlspecialchars($order['product_name']); ?></td>
                            <td><?= htmlspecialchars($order['quantity']); ?></td>
                            <td>$<?= htmlspecialchars(number_format($order['total_price'], 2)); ?></```php
                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($order['order_date']))); ?></td>
                            <td>Verified</td>
                            <td>
                                <a href="order_details.php?id=<?= htmlspecialchars($order['order_id']); ?>" class="action-link">View Details</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No verified orders available at the moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
            <div class="quick-links">
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms.php">Terms of Service</a>
            </div>
            <div class="newsletter">
                <form action="subscribe.php" method="post">
                    <input type="email" name="email" placeholder="Subscribe to our newsletter" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </footer>
</body>
</html>
