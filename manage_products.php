<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit();
}

// Handle adding a new product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];
    $retailer_id = $_SESSION['user_id'];

    $sql = "INSERT INTO products (name, description, price, stock, category_id, retailer_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisi", $name, $description, $price, $stock, $category_id, $retailer_id);

    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Error adding product.";
    }
}

// Fetch retailer's products
$sql = "SELECT * FROM products WHERE retailer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$products = $stmt->get_result();
?>

<?php include 'dashboard_header.php'; ?>

<div class="manage-products-container">
    <h2>Manage Products</h2>

    <!-- Add New Product Form -->
    <form action="manage_products.php" method="POST">
        <h3>Add New Product</h3>
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required>

        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <option value="1">Farm Products</option>
            <option value="2">Industrial Products</option>
            <option value="3">Other Products</option>
        </select>

        <button type="submit" name="add_product">Add Product</button>
    </form>

    <!-- List of Products -->
    <h3>Your Products</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td><?php echo htmlspecialchars($product['price']); ?></td>
                    <td><?php echo htmlspecialchars($product['stock']); ?></td>
                    <td><?php echo htmlspecialchars($product['category_id']); ?></td>
                    <td>
                        <a href="edit_product.php?product_id=<?php echo $product['product_id']; ?>">Edit</a> |
                        <a href="delete_product.php?product_id=<?php echo $product['product_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'dashboard_footer.php'; ?>
