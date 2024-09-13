<?php
session_start();
require_once 'config.php';

// Check if the user is a retailer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'retailer') {
    header("Location: login.php");
    exit();
}

$retailer_id = $_SESSION['user_id'];

function fetchStockCapacity($conn, $retailer_id) {
    $sql = "SELECT total_capacity, used_capacity FROM retailer_stock WHERE retailer_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("i", $retailer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $capacity = $result->fetch_assoc();

    if (!$capacity) {
        $capacity = ['total_capacity' => 1000, 'used_capacity' => 0];
        $sql = "INSERT INTO retailer_stock (retailer_id, total_capacity, used_capacity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Prepare fail ed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("iii", $retailer_id, $capacity['total_capacity'], $capacity['used_capacity']);
        $stmt->execute();
    }

    return $capacity;
}

$capacity = fetchStockCapacity($conn, $retailer_id);
$remaining_capacity = $capacity['total_capacity'] - $capacity['used_capacity'];

// Handle category filter
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$category_condition = ($category === 'all') ? '' : "AND category = '" . $conn->real_escape_string($category) . "'";

$sql = "SELECT * FROM products WHERE retailer_id = ? $category_condition";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("i", $retailer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'dashboard_header.php'; ?>

<!-- Stock Management Page Content -->
<div class="stock-management-container">
    <h2>Stock Management</h2>

    <!-- Display stock capacity -->
    <div class="stock-capacity">
        <p>Total Capacity: <span><?php echo htmlspecialchars($capacity['total_capacity']); ?></span></p>
        <p>Used Capacity: <span><?php echo htmlspecialchars($capacity['used_capacity']); ?></span></p>
        <p>Remaining Capacity: <span><?php echo htmlspecialchars($remaining_capacity); ?></span></p>
    </div>

    <!-- Filter by category -->
    <div class="category-filter">
        <a href="all.php?category=all" class="<?php echo $category === 'all' ? 'active' : ''; ?>">All</a>
        <a href="farm_product.php" class="<?php echo $category === 'farm' ? 'active' : ''; ?>">Farm</a>
        <a href="industrial_product.php" class="<?php echo $category === 'industrial' ? 'active' : ''; ?>">Industrial</a>
        <a href="others.php?category=others" class="<?php echo $category === 'others' ? 'active' : ''; ?>">Others</a>
    </div>

    <h3>Your Products</h3>
    <div class="product-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                    <p>Quantity: <?php echo htmlspecialchars($product['quantity']); ?></p>
                    <div class="product-actions">
                        <button class="btn-edit" onclick="editProduct(<?php echo $product['id']; ?>)">Edit</button>
                        <button class="btn-delete" onclick="deleteProduct(<?php echo $product['id']; ?>)">Delete</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Modals for Editing and Deleting -->
<div id="edit-product-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('edit-product-modal')">&times;</span>
        <h2>Edit Product</h2>
        <!-- Add your form or content for editing a product here -->
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
// JavaScript functions for editing and deleting products
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function editProduct(productId) {
    openModal('edit-product-modal');
    // Load edit form dynamically if needed
}

function deleteProduct(productId) {
    openModal('delete-product-modal');
    document.getElementById('confirm-delete').onclick = function() {
        // Perform AJAX request to delete the product
        closeModal('delete-product-modal');
    }
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
.stock-management-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f4f7f8;
}

/* Stock Capacity Styles */
.stock-capacity {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.stock-capacity p {
    font-size: 1.2em;
    margin: 10px 0;
}

.stock-capacity span {
    font-weight: bold;
    color: #333;
}

/* Category Filter Styles */
.category-filter {
    margin-bottom: 20px;
}

.category-filter a {
    text-decoration: none;
    color: #007bff;
    margin-right: 15px;
    font-weight: bold;
    padding: 10px 15px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.category-filter a.active, .category-filter a:hover {
    background-color: #e2e6ea;
    color: #0056b3;
}

/* Product List Styles */
.product-list {
    display: flex;
    flex-wrap: wrap;
}

.product-card {
    background-color: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    flex: 1 1 calc(33.333% - 30px);
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.product-card h4 {
    margin-top: 0;
}

.product-card p {
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
