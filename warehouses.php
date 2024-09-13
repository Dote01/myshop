
<?php
session_start();
require_once 'config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Retailer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch available products in the warehouse
$stmt = $conn->prepare("SELECT p.id, p.name, p.description, w.total_capacity, w.remaining_capacity FROM warehouse w JOIN products p ON w.product_id = p.id WHERE w.remaining_capacity > 0");
$stmt->execute();
$warehouse_products = $stmt->get_result();

include 'partials/header.php';
?>

<div class="warehouse-container">
    <h2>Warehouse Products</h2>
    <p>Select products to add to your stock. The available capacity in the warehouse is displayed below.</p>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Description</th>
                <th>Total Capacity (m³)</th>
                <th>Remaining Capacity (m³)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $warehouse_products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_capacity']); ?> m³</td>
                    <td><?php echo htmlspecialchars($row['remaining_capacity']); ?> m³</td>
                    <td>
                        <form method="POST" action="add_to_stock.php">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="remaining_capacity" value="<?php echo $row['remaining_capacity']; ?>">
                            <input type="number" name="quantity" placeholder="Quantity" min="1" max="<?php echo $row['remaining_capacity']; ?>" required>
                            <button type="submit" class="btn">Add to Stock</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'partials/footer.php'; ?>

<!-- CSS -->
<style>
    .warehouse-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
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
        padding: 10px 15px;
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .btn:hover {
        background-color: #218838;
    }
</style>
