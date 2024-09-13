<?php
session_start();
require_once 'config.php'; // Database configuration

// Redirect if user is not a retailer
if ($_SESSION['role'] !== 'retailer') {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = htmlspecialchars(trim($_POST['product_name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category = htmlspecialchars(trim($_POST['category']));

    // Prepare and execute SQL statement
    $sql = "INSERT INTO products (retailer_id, product_name, description, price, stock, category) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssis", $user_id, $product_name, $description, $price, $stock, $category);

    if ($stmt->execute()) {
        $message = '<p class="success-message">Product added successfully.</p>';
    } else {
        $message = '<p class="error-message">Error adding product. Please try again.</p>';
    }
}
?>

<?php include 'dashboard_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - MyShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
   

        .container {
            max-width: 1200px;
            width: 100%;
        }

        .form-container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 0 auto;
            max-width: 600px;
            width: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .form-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 700;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            text-align: left;
        }

        input[type="text"], input[type="number"], select, textarea {
            width: 100%;
            padding: 14px;
            margin-bottom: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: #fafafa;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #007bff;
            box-shadow: 0 0 6px rgba(0, 123, 255, 0.2);
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 16px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .success-message, .error-message {
            font-size: 16px;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .success-message {
            color: #28a745;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .form-footer {
            margin-top: 20px;
            text-align: center;
        }

        .form-footer a {
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Add Product</h1>
            <form method="POST" action="">
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" id="product_name" required>

                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>

                <label for="price">Price:</label>
                <input type="number" step="0.01" name="price" id="price" required>

                <label for="stock">Stock:</label>
                <input type="number" name="stock" id="stock" required>

                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <option value="farm">Farm</option>
                    <option value="industrial">Industrial</option>
                    <option value="other">Other</option>
                </select>

                <button type="submit">Add Product</button>

                <!-- Display success or error message -->
                <?php if ($message): ?>
                    <?php echo $message; ?>
                <?php endif; ?>
            </form>

            <div class="form-footer">
                <a href="dashboard.php">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
