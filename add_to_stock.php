<?php
session_start();
require_once 'config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Retailer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];
$remaining_capacity = $_POST['remaining_capacity'];

// Check if the retailer has enough stock capacity
$stmt = $conn->prepare("SELECT stock_capacity FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stock_capacity = $result['stock_capacity'];

// Fetch current stock usage for the retailer
$stmt = $conn->prepare("SELECT SUM(stock_level) AS used_capacity FROM stock_management WHERE retailer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$used_capacity = $result['used_capacity'];

if (($used_capacity + $quantity) > $stock_capacity) {
    // Redirect with error if the retailer exceeds stock capacity
    header("Location: warehouse.php?error=stock_capacity_exceeded");
    exit();
}

// Update stock management for retailer
$stmt = $conn->prepare("INSERT INTO stock_management (retailer_id, product_id, stock_level, warehouse_purchase) VALUES (?, ?, ?, 1)");
$stmt->bind_param("iii", $user_id, $product_id, $quantity);

if ($stmt->execute()) {
    // Update remaining capacity in warehouse
    $stmt = $conn->prepare("UPDATE warehouse SET remaining_capacity = remaining_capacity - ? WHERE product_id = ?");
    $stmt->bind_param("ii", $quantity, $product_id);
    $stmt->execute();

    // Redirect to stock page with success message
    header("Location: stock.php?success=stock_updated");
    exit();
} else {
    // Redirect with error if stock update fails
    header("Location: warehouse.php?error=stock_update_failed");
    exit();
}
?>
