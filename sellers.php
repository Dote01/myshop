<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) { // Ensure only retailers can access this page
    header("Location: login.php");
    exit();
}

require_once 'config.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $item_description = $_POST['item_description'];
    $item_price = $_POST['item_price'];
    $item_category = $_POST['item_category'];
    $item_image = $_FILES['item_image']['name'];

    // Upload image
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["item_image"]["name"]);
    move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file);

    $stmt = $pdo->prepare("INSERT INTO products (user_id, name, description, price, category, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $item_name, $item_description, $item_price, $item_category, $item_image]);

    header("Location: seller.php?success=Item posted successfully!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Your Products - MyShop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
            margin-top: 30px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px;
        }
        .form-control-file {
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .alert-success {
            margin-top: 20px;
        }
        .card {
            margin-top: 30px;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <header class="bg-success text-white p-3 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4">MyShop</h1>
            <nav>
                <a href="dashboard.php" class="text-white mx-2">Dashboard</a>
                <a href="logout.php" class="text-white">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        
        <h2 class="mb-4">Post Your Product for Sale</h2>
        <form action="seller.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="item_name">Product Name</label>
                <input type="text" id="item_name" name="item_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="item_description">Description</label>
                <textarea id="item_description" name="item_description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="item_price">Price</label>
                <input type="number" id="item_price" name="item_price" class="form-control" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="item_category">Category</label>
                <select id="item_category" name="item_category" class="form-control" required>
                    <option value="">Select Category</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Clothing">Clothing</option>
                    <option value="Home">Home</option>
                    <option value="Sports">Sports</option>
                    <option value="Toys">Toys</option>
                    <!-- Add more categories as needed -->
                </select>
            </div>
            <div class="form-group">
                <label for="item_image">Product Image</label>
                <input type="file" id="item_image" name="item_image" class="form-control-file" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Post Product</button>
        </form>

        <h2 class="mt-5">Browse Available Products</h2>
        <div class="row">
            <?php
            $stmt = $pdo->query("SELECT * FROM products");
            $products = $stmt->fetchAll();

            foreach ($products as $product):
            ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="card-text">Price: $<?php echo number_format($product['price'], 2); ?></p>
                            <p class="card-text">Category: <?php echo htmlspecialchars($product['category']); ?></p>
                            <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-success">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
