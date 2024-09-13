<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ensure the user is a retailer
// Fetch available products in warehouse
$sql = "SELECT w.warehouse_id, p.name, w.stock, w.supplier 
        FROM warehouses w 
        JOIN products p ON w.product_id = p.product_id";
$warehouse_products = $conn->query($sql);

// Handle ordering stock
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_stock'])) {
    $warehouse_id = $_POST['warehouse_id'];
    $quantity = $_POST['quantity'];
    $retailer_id = $_SESSION['user_id'];

    // Check stock availability
    $sql = "SELECT stock FROM warehouses WHERE warehouse_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $warehouse_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $available_stock = $result->fetch_assoc()['stock'];

    if ($available_stock >= $quantity) {
        // Update warehouse stock
        $new_stock = $available_stock - $quantity;
        $sql = "UPDATE warehouses SET stock = ? WHERE warehouse_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_stock, $warehouse_id);
        $stmt->execute();

        // Add stock to retailer's products
        $sql = "UPDATE products SET stock = stock + ? WHERE product_id = 
                (SELECT product_id FROM warehouses WHERE warehouse_id = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $quantity, $warehouse_id);

        if ($stmt->execute()) {
            $message = "Stock ordered successfully!";
        } else {
            $message = "Error ordering stock.";
        }
    } else {
        $message = "Not enough stock available.";
    }
}
?>

<?php include 'dashboard_header.php'; ?>

<div class="order-stock-container">
    <h2>Order Stock</h2>

    <?php if (isset($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="order_stock.php" method="POST">
        <label for="warehouse_id">Product:</label>
        <select id="warehouse_id" name="warehouse_id" required>
            <?php while ($product = $warehouse_products->fetch_assoc()): ?>
                <option value="<?php echo $product['warehouse_id']; ?>">
                    <?php echo htmlspecialchars($product['name'] . " (Available: " . $product['stock'] . ")"); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>

        <button type="submit" name="order_stock">Order Stock</button>
    </form>
</div>

<?php include 'dashboard_footer.php'; ?>

<style>
    .order-stock-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .order-stock-container h2 {
        color: #4CAF50;
        margin-bottom: 20px;
    }

    .order-stock-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .order-stock-container label {
        font-weight: bold;
    }

    .order-stock-container select,
    .order-stock-container input[type="number"] {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%;
        box-sizing: border-box;
    }

    .order-stock-container button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .order-stock-container button:hover {
        background-color: #45a049;
    }

    .message {
        padding: 10px;
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 4px;
        margin-bottom: 20px;
    }
</style>
