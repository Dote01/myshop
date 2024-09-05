<?php
session_start();
require_once 'config.php';

// Check if user is a retailer
if ($_SESSION['role'] !== 'retailer') {
    header("Location: ../dashboard/dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO products (retailer_id, product_name, description, price, stock, category) VALUES (?, ?, ?, ?, ?, 'other')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $user_id, $product_name, $description, $price, $stock);

    if ($stmt->execute()) {
        echo "Product added successfully.";
    } else {
        echo "Error adding other product.";
    }
}
?>

<form method="POST" action="add_other_product.php">
    <label for="product_name">Product Name:</label>
    <input type="text" name="product_name" id="product_name" required>

    <label for="description">Description:</label>
    <textarea name="description" id="description" required></textarea>

    <label for="price">Price:</label>
    <input type="text" name="price" id="price" required>

    <label for="stock">Stock:</label>
    <input type="number" name="stock" id="stock" required>

    <button type="submit">Add Product</button>
</form>
