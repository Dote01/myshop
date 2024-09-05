<?php
session_start();
require_once 'config.php'; // Ensure this file initializes the $conn variable

// Handle search query
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Handle filters
$category_filter = isset($_GET['category']) ? trim($_GET['category']) : '';

// Pagination setup
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Build query
$sql = "SELECT * FROM products WHERE 1";
$params = [];

// Add search filter
if ($search_query) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

// Add category filter
if ($category_filter) {
    $sql .= " AND category = ?";
    $params[] = $category_filter;
}

// Get total products count
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$total_products = $result->num_rows;

// Modify query for pagination
$sql .= " LIMIT ? OFFSET ?";
$params[] = $items_per_page;
$params[] = $offset;

// Fetch products
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param(str_repeat('s', count($params) - 2) . 'ii', ...$params);
}
$stmt->execute();
$products_result = $stmt->get_result();

// Fetch categories for filter
$categories = ['industrial', 'farms', 'others'];

function paginate($total, $current_page, $per_page) {
    $total_pages = ceil($total / $per_page);
    $pagination = '';
    for ($i = 1; $i <= $total_pages; $i++) {
        $pagination .= "<a href='products.php?page=$i" . (isset($_GET['category']) ? "&category=" . $_GET['category'] : "") . "' class='page-button'>$i</a> ";
    }
    return $pagination;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - MyShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f2f4f7;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header Styles */
        header {
            background: linear-gradient(to right, #4CAF50, #2e8b57);
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 2px solid #2e8b57;
        }
        .logo {
            font-size: 1.8em;
            font-weight: bold;
            letter-spacing: 1px;
        }
        #search-form {
            display: flex;
            align-items: center;
        }
        #search-input {
            padding: 10px;
            width: 80%;
            max-width: 400px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        #search-input:focus {
            border-color: #4CAF50;
        }

        /* Filters Styles */
        .filters {
            margin: 20px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }
        .filters select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-left: 20px;
            transition: background-color 0.3s;
        }
        .filters select:hover {
            background-color: #f0f0f0;
        }

        /* Product List Styles */
        #product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: calc(33% - 20px);
            margin: 10px;
            background: #fff;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .product-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product-card h2 {
            font-size: 1.4em;
            margin: 10px 0;
        }
        .product-card p {
            font-size: 1.2em;
            color: #4CAF50;
            margin: 0;
        }
        .product-card a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            margin: 10px 0;
            transition: background-color 0.3s;
        }
        .product-card a:hover {
            background-color: #45a049;
        }

        /* Pagination Styles */
        .pagination {
            text-align: center;
            margin: 20px 0;
        }
        .pagination a {
            color: #4CAF50;
            text-decoration: none;
            margin: 0 5px;
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #fff;
            border: 1px solid #ddd;
            transition: background-color 0.3s, color 0.3s;
        }
        .pagination a:hover {
            background-color: #4CAF50;
            color: #fff;
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: auto;
            position: relative;
            border-top: 2px solid #2e8b57;
        }

    </style>
</head>
<body>
    <header>
        <div class="logo">MyShop</div>
        <form id="search-form" action="products.php" method="GET">
            <input type="text" id="search-input" name="q" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>">
        </form>
    </header>

    <!-- Filters Section -->
    <div class="filters">
        <label for="category-filter">Category:</label>
        <form action="products.php" method="GET">
            <select name="category" id="category-filter" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>" <?php echo ($category_filter === $category) ? 'selected' : ''; ?>>
                        <?php echo ucfirst($category); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="q" value="<?php echo htmlspecialchars($search_query); ?>">
        </form>
    </div>

    <!-- Product List -->
    <div id="product-list">
        <?php if ($products_result->num_rows > 0): ?>
            <?php while ($product = $products_result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p><?php echo htmlspecialchars($product['price']); ?> USD</p>
                    <a href="product_details.php?id=<?php echo $product['id']; ?>">View Details</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php echo paginate($total_products, $page, $items_per_page); ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> MyShop. All Rights Reserved.</p>
    </footer>
</body>
</html>
