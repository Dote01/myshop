<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Retailer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current stock data
$stmt = $conn->prepare("SELECT p.name, sm.stock_level FROM stock_management sm JOIN products p ON sm.product_id = p.id WHERE sm.retailer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stock_data = $stmt->get_result();

// Calculate stock capacity
$capacity_stmt = $conn->prepare("SELECT stock_capacity FROM users WHERE id = ?");
$capacity_stmt->bind_param("i", $user_id);
$capacity_stmt->execute();
$capacity_result = $capacity_stmt->get_result()->fetch_assoc();
$stock_capacity = $capacity_result['stock_capacity'];

// Calculate used stock
$used_capacity_stmt = $conn->prepare("SELECT SUM(stock_level) AS used_capacity FROM stock_management WHERE retailer_id = ?");
$used_capacity_stmt->bind_param("i", $user_id);
$used_capacity_stmt->execute();
$used_capacity_result = $used_capacity_stmt->get_result()->fetch_assoc();
$used_capacity = $used_capacity_result['used_capacity'];

$remaining_capacity = $stock_capacity - $used_capacity;

include 'dashboard_header.php';
?>

<div class="stock-container">
    <h2>Stock Overview</h2>

    <div class="stock-info">
        <p>Total Capacity: <?php echo htmlspecialchars($stock_capacity); ?> m³</p>
        <p>Used Capacity: <?php echo htmlspecialchars($used_capacity); ?> m³</p>
        <p>Remaining Capacity: <?php echo htmlspecialchars($remaining_capacity); ?> m³</p>
    </div>

    <h3>Current Stock</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Stock Level</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stock_data->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['stock_level']); ?> units</td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="buy_stock.php" class="btn">Buy More Stock</a>
</div>

<?php include 'dashboard_footer.php'; ?>

<!-- CSS for stock page -->
<style>
    .stock-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }
    .stock-info p {
        font-size: 1.2em;
        margin: 10px 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
    }
    th {
        background-color: #4CAF50;
        color: white;
    }
    .btn {
        display: inline-block;
        padding: 12px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        transition: background-color 0.3s;
    }
    .btn:hover {
        background-color: #0056b3;
    }
</style>
