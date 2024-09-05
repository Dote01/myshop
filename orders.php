<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

require_once 'configs.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role_id = $_SESSION['role'];
$total_stock_capacity = 1000; // Example total capacity, could be fetched from database or set per retailer

// Initialize stock values
$used_stock = 0; // Default value, replace with actual logic if available
$remaining_stock = $total_stock_capacity - $used_stock;

// Fetch products from the database
$stmt = $pdo->prepare("SELECT * FROM products WHERE retailer_id = ?");
$stmt->execute([$user_id]);
$products = $stmt->fetchAll();

// Handle purchasing of products
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buy_product_id'])) {
    $product_id = $_POST['buy_product_id'];
    $purchase_quantity = $_POST['purchase_quantity'];

    // Update stock and product ownership
    $stmt = $pdo->prepare("UPDATE products SET quantity = quantity + ? WHERE id = ? AND retailer_id = ?");
    $stmt->execute([$purchase_quantity, $product_id, $user_id]);

    // Update remaining stock
    $used_stock += $purchase_quantity;
    $remaining_stock = $total_stock_capacity - $used_stock;
    
    header("Location: buyers.php?success=Product purchased successfully!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retailer Dashboard - MyShop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
        .dashboard-content {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .dashboard-heading {
            font-size: 2.4em;
            color: #4CAF50;
            margin-bottom: 20px;
            font-weight: 700;
        }
        .dashboard-intro {
            font-size: 1.2em;
            color: #666;
            margin-bottom: 20px;
            font-weight: 300;
        }
        .dashboard-summary {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            width: 100%;
        }
        .summary-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: left;
            transition: transform 0.3s, box-shadow 0.3s;
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

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: #fff;
            font-weight: 700;
        }
        td {
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #4CAF50;
            outline: none;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            display: inline-block;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #4CAF50;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .btn-success {
            background-color: #2e8b57;
        }
        .btn-success:hover {
                background-color: #26734d;
            }
        </style>
    </head>
    <body>
        <?php include 'dashboard_header.php'; ?>

        <div class="dashboard-content">
            <div class="dashboard-heading">Welcome, <?php echo htmlspecialchars($username); ?>!</div>
            <div class="dashboard-intro">
                You have a total stock capacity of <?php echo $total_stock_capacity; ?> units. 
                Currently, you have <?php echo $remaining_stock; ?> units remaining.
            </div>
            
            <div class="dashboard-summary">
                <div class="summary-card">
                    <h3>Total Products</h3>
                    <p><?php echo count($products); ?></p>
                </div>
                <div class="summary-card">
                    <h3>Remaining Stock</h3>
                    <p><?php echo $remaining_stock; ?> units</p>
                </div>
            </div>
            
            <h2>Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="buy_product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                    <input type="number" name="purchase_quantity" min="1" max="<?php echo htmlspecialchars($remaining_stock); ?>" placeholder="Quantity" required>
                                    <button type="submit" class="btn btn-primary">Buy</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
    </html>
