<?php
session_start();
require_once 'config.php';

// Check if the user is a retailer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'retailer') {
    header("Location: login.php");
    exit();
}

// Fetch electronics products from the database
$sql = "SELECT * FROM products WHERE category = 'Electronics'";
$result = $conn->query($sql);

if ($conn->error) {
    die('Error: ' . htmlspecialchars($conn->error));
}
?>

<?php include 'dashboard_header.php'; ?>

<!-- Electronics Products Page Content -->
<div class="electronics-products-container">
    <h2>Electronics Products</h2>

    <!-- Add Product Form -->
    <form action="add_electronics_product.php" method="post" class="product-form">
        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required>

        <label for="supplier">Supplier:</label>
        <input type="text" id="supplier" name="supplier" required>

        <button type="submit" class="btn-submit">Add Product</button>
    </form>

    <!-- Existing Products List -->
    <h3>Existing Electronics Products</h3>
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
            echo '<p>No electronics products available.</p>';
        }
        ?>
    </div>
</div>

<!-- Modals for Editing and Deleting -->
<div id="edit-product-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('edit-product-modal')">&times;</span>
        <h2>Edit Product</h2>
        <!-- Add form or fields for editing product details -->
    </div>
</div>

<div id="delete-product-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('delete-product-modal')">&times;</span>
        <h2>Are you sure you want to delete this product?</h2>
        <button id="confirm-delete" class="btn-delete-confirm">Yes, Delete</button>
        <button class="btn-cancel" onclick="closeModal('delete-product-modal')">Cancel</button>
    </div>
</div>

<script>
// JavaScript functions for editing and deleting electronics products
function editProduct(productId) {
    openModal('edit-product-modal');
    // Load edit form dynamically if needed
    alert('Edit functionality for product ID: ' + productId);
}

function deleteProduct(productId) {
    openModal('delete-product-modal');
    document.getElementById('confirm-delete').onclick = function() {
        // Perform AJAX request to delete the product
        alert('Delete functionality for product ID: ' + productId);
        closeModal('delete-product-modal');
    }
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Close modals when clicking outside of them
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        closeModal(event.target.id);
    }
}
</script>

<?php include 'dashboard_footer.php'; ?>

<!-- Add your CSS file or inline styles -->
<style>
/* Container Styles */
.electronics-products-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f4f7f8;
}

/* Product Form Styles */
.product-form {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.product-form label {
    display: block;
    margin: 10px 0 5px;
}

.product-form input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    margin-bottom: 15px;
}

.product-form .btn-submit {
    background-color: #007bff;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1em;
    transition: background-color 0.3s;
}

.product-form .btn-submit:hover {
    background-color: #0056b3;
}

/* Product List Styles */
.product-list {
    display: flex;
    flex-wrap: wrap;
}

.product-item {
    background-color: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin: 15px;
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

.product-item p {
    margin: 10px 0;
}

/* Product Actions Styles */
.product-actions {
    margin-top: 15px;
}

.product-actions .btn-edit, .product-actions .btn-delete {
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

.product-actions .btn-edit:hover {
    background-color: #0056b3;
}

.product-actions .btn-delete {
    background-color: #dc3545;
}

.product-actions .btn-delete:hover {
    background-color: #c82333;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #ffffff;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    border-radius: 8px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
}

/* Button Styles in Modal */
.btn-delete-confirm {
    background-color: #dc3545;
    color: #ffffff;
}

.btn-cancel {
    background-color: #6c757d;
    color: #ffffff;
}

.btn-delete-confirm, .btn-cancel {
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    margin: 5px;
    transition: background-color 0.3s;
}

.btn-delete-confirm:hover {
    background-color: #c82333;
}

.btn-cancel:hover {
    background-color: #5a6268;
}
</style>
