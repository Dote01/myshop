<?php
session_start();
require_once 'config.php'; // Ensure this file sets up $conn correctly

// Check if the user is a retailer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'retailer') {
    header("Location: login.php");
    exit();
}

// Fetch industrial products from the database
$sql = "SELECT * FROM warehouses WHERE category = 'Industrial'";
$result = $conn->query($sql);

if ($conn->error) {
    die('Error: ' . htmlspecialchars($conn->error));
}
?>

<?php include 'dashboard_header.php'; ?>

<!-- Industrial Products Page Content -->
<div class="industrial-products-container">
    <h2>Industrial Products</h2>
    <form action="add_industrial_product.php" method="post" class="product-form">
        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required>

        <label for="supplier">Supplier:</label>
        <input type="text" id="supplier" name="supplier" required>

        <button type="submit" class="btn-submit">Add Product</button>
    </form>

    <h3>Existing Industrial Products</h3>
    <div class="product-list">
        <?php
        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                echo '<div class="product-item">';
                echo '<h4>Product ID: ' . htmlspecialchars($product['product_id']) . '</h4>';
                echo '<p>Stock: ' . htmlspecialchars($product['stock']) . '</p>';
                echo '<p>Supplier: ' . htmlspecialchars($product['supplier']) . '</p>';
                echo '<p>Category: ' . htmlspecialchars($product['category']) . '</p>';
                echo '<p>Created At: ' . htmlspecialchars($product['created_at']) . '</p>';
                echo '<p>Updated At: ' . htmlspecialchars($product['updated_at']) . '</p>';
                echo '<div class="product-actions">';
                echo '<button class="btn-edit" onclick="editProduct(' . $product['product_id'] . ')">Edit</button>';
                echo '<button class="btn-delete" onclick="deleteProduct(' . $product['product_id'] . ')">Delete</button>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No industrial products available.</p>';
        }
        ?>
    </div>
</div>

<script>
// JavaScript functions for editing and deleting industrial products
function editProduct(productId) {
    // Implement edit product functionality
    alert('Edit functionality for product ID: ' + productId);
}

function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        // Implement delete functionality
        alert('Delete functionality for product ID: ' + productId);
    }
}
</script>

<?php include 'dashboard_footer.php'; ?>

<!-- Add your CSS file or inline styles -->
<style>
.industrial-products-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f4f7f8;
}

.product-form {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.product-form label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
}

.product-form input {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.product-form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    height: 100px;
}

.btn-submit {
    background-color: #28a745;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1em;
    transition: background-color 0.3s;
}

.btn-submit:hover {
    background-color: #218838;
}

.product-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.product-item {
    background-color: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    flex: 1 1 calc(33.333% - 30px);
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-item:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.product-item h4 {
    margin-top: 0;
}

.product-actions {
    margin-top: 15px;
}

.btn-edit, .btn-delete {
    background-color: #007bff;
    color: #ffffff;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9em;
    margin-right: 10px;
    transition: background-color 0.3s;
}

.btn-edit:hover {
    background-color: #0056b3;
}

.btn-delete {
    background-color: #dc3545;
}

.btn-delete:hover {
    background-color: #c82333;
}
</style>
