<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

$user_id = $_SESSION['user_id'];
$product_name = $_POST['product_name'] ?? '';
$quantity = $_POST['quantity'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($product_name) || empty($quantity)) {
        $error = 'Please fill in all fields.';
    } else {
        try {
            // Assuming retailer_id is same as user_id in this context
            $retailer_id = $user_id;

            $sql = "INSERT INTO product_requests (user_id, retailer_id, product_name, quantity, status) VALUES (?, ?, ?, ?, 'Pending')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $retailer_id, $product_name, $quantity]);

            header("Location: buyer_orders.php");
            exit();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
 include 'dashboard_header.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Product Request - MyShop</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add some custom styles here */
        .request-form {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .request-form h1 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
        }
        .request-form input, .request-form textarea {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .request-form button {
            width: 100%;
            padding: 0.75rem;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .request-form button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'dashboard_headerheader.php'; ?>
    
    <main class="request-form">
        <h1>Post Product Request</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="product_name">Product Name</label>
            <input type="text" id="product_name" name="product_name" required>
            
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" required>
            
            <button type="submit">Submit Request</button>
        </form>
    </main>
    
    <?php include 'dashboard_footer.php'; ?>
</body>
</html>
