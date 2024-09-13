<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ensure the user is a retailer
$sql = "SELECT role_name FROM roles WHERE role_id = (SELECT role_id FROM users WHERE user_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user_role = $result->fetch_assoc()['role_name'];

if ($user_role != 'retailer') {
    echo "Unauthorized access.";
    exit();
}

// Fetch sales data
$sql = "SELECT p.name, SUM(o.quantity) AS total_quantity, SUM(o.total_price) AS total_sales
        FROM orders o
        JOIN products p ON o.product_id = p.product_id
        WHERE p.retailer_id = ?
        GROUP BY p.name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$sales = $stmt->get_result();
?>

<?php include 'dashboard_header.php'; ?>

<div class="sales-container">
    <h2>View Sales</h2>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity Sold</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sale = $sales->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sale['name']); ?></td>
                    <td><?php echo htmlspecialchars($sale['total_quantity']); ?></td>
                    <td><?php echo htmlspecialchars($sale['total_sales']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'dashboard_footer.php'; ?>
