<?php
session_start(); // Start the session

// Include configuration and header
include 'config.php';
include 'dashboard_header.php';

// Handle section selection
if (isset($_POST['section'])) {
    $_SESSION['section'] = $_POST['section'];
    header('Location: market.php');
    exit();
}

$section = $_SESSION['section'] ?? 'all';
$search_query = $_GET['search'] ?? '';
$filters = $_GET['filters'] ?? [];
$sort_by = $_GET['sort_by'] ?? 'name';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 20;
$offset = ($page - 1) * $items_per_page;

// Build the SQL query based on search and filters
$sql = "SELECT * FROM products WHERE 1=1";
if ($section !== 'all') {
    $sql .= " AND section = '$section'";
}
if (!empty($search_query)) {
    $search_query = $conn->real_escape_string($search_query);
    $sql .= " AND (name LIKE '%$search_query%' OR description LIKE '%$search_query%')";
}
if (!empty($filters)) {
    foreach ($filters as $key => $value) {
        $value = $conn->real_escape_string($value);
        $sql .= " AND $key = '$value'";
    }
}
$sql .= " ORDER BY $sort_by LIMIT $offset, $items_per_page";
$result = $conn->query($sql);

// Fetch total number of products for pagination
$total_sql = "SELECT COUNT(*) as total FROM products WHERE 1=1";
if ($section !== 'all') {
    $total_sql .= " AND section = '$section'";
}
if (!empty($search_query)) {
    $total_sql .= " AND (name LIKE '%$search_query%' OR description LIKE '%$search_query%')";
}
if (!empty($filters)) {
    foreach ($filters as $key => $value) {
        $value = $conn->real_escape_string($value);
        $total_sql .= " AND $key = '$value'";
    }
}
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $items_per_page);
?>

<!-- Market Page Content -->
<div class="market-container">
    <h2>Select Market Section</h2>
    <div class="section-selection">
        <a href="market.php?section=buyers" class="section-button">Buyers</a>
        <a href="market.php?section=sellers" class="section-button">Sellers</a>
    </div>

    <div class="search-bar">
        <input type="text" placeholder="Search products..." id="search-input" value="<?php echo htmlspecialchars($search_query); ?>">
        <button onclick="searchProducts()">Search</button>
    </div>

    <div class="filters">
        <h3>Filters</h3>
        <label>Category:
            <select id="filter-category">
                <option value="">All</option>
                <option value="electronics" <?php echo in_array('electronics', $filters) ? 'selected' : ''; ?>>Electronics</option>
                <option value="clothing" <?php echo in_array('clothing', $filters) ? 'selected' : ''; ?>>Clothing</option>
                <!-- Add more options as needed -->
            </select>
        </label>
        <label>Price Range:
            <input type="text" id="filter-price-min" placeholder="Min Price" value="<?php echo htmlspecialchars($filters['price_min'] ?? ''); ?>">
            <input type="text" id="filter-price-max" placeholder="Max Price" value="<?php echo htmlspecialchars($filters['price_max'] ?? ''); ?>">
        </label>
        <label>Rating:
            <select id="filter-rating">
                <option value="">All Ratings</option>
                <option value="4" <?php echo in_array('4', $filters) ? 'selected' : ''; ?>>4 Stars & Up</option>
                <option value="5" <?php echo in_array('5', $filters) ? 'selected' : ''; ?>>5 Stars</option>
            </select>
        </label>
        <button onclick="applyFilters()">Apply Filters</button>
    </div>

    <div class="sort-options">
        <h3>Sort By</h3>
        <select id="sort-by">
            <option value="name" <?php echo $sort_by === 'name' ? 'selected' : ''; ?>>Name</option>
            <option value="price" <?php echo $sort_by === 'price' ? 'selected' : ''; ?>>Price</option>
            <option value="popularity" <?php echo $sort_by === 'popularity' ? 'selected' : ''; ?>>Popularity</option>
            <option value="date" <?php echo $sort_by === 'date' ? 'selected' : ''; ?>>Newest</option>
        </select>
        <button onclick="sortProducts()">Sort</button>
    </div>

    <div class="product-list">
        <?php
        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                echo '<div class="product-item">';
                echo '<h4>' . htmlspecialchars($product['name']) . '</h4>';
                echo '<p>' . htmlspecialchars($product['description']) . '</p>';
                echo '<p>Price: $' . htmlspecialchars($product['price']) . '</p>';
                echo '<button onclick="addToCart(' . $product['id'] . ')">Add to Cart</button>';
                echo '</div>';
            }
        } else {
            echo '<p>No products available.</p>';
        }
        ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="market.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search_query); ?>&section=<?php echo $section; ?>&sort_by=<?php echo urlencode($sort_by); ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="market.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search_query); ?>&section=<?php echo $section; ?>&sort_by=<?php echo urlencode($sort_by); ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="market.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search_query); ?>&section=<?php echo $section; ?>&sort_by=<?php echo urlencode($sort_by); ?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<script>
// JavaScript functions for search, sorting, and adding to cart
function searchProducts() {
    const query = document.getElementById('search-input').value;
    window.location.href = `market.php?search=${encodeURIComponent(query)}&section=<?php echo $section; ?>&sort_by=<?php echo urlencode($sort_by); ?>`;
}

function applyFilters() {
    const category = document.getElementById('filter-category').value;
    const priceMin = document.getElementById('filter-price-min').value;
    const priceMax = document.getElementById('filter-price-max').value;
    const rating = document.getElementById('filter-rating').value;
    window.location.href = `market.php?search=<?php echo urlencode($search_query); ?>&section=<?php echo $section; ?>&filters[category]=${encodeURIComponent(category)}&filters[price_min]=${encodeURIComponent(priceMin)}&filters[price_max]=${encodeURIComponent(priceMax)}&filters[rating]=${encodeURIComponent(rating)}&sort_by=<?php echo urlencode($sort_by); ?>`;
}

function sortProducts() {
    const sortBy = document.getElementById('sort-by').value;
    window.location.href = `market.php?search=<?php echo urlencode($search_query); ?>&section=<?php echo $section; ?>&sort_by=${encodeURIComponent(sortBy)}&page=<?php echo $page; ?>`;
}

function addToCart(productId) {
    // Implement add to cart functionality
    alert('Product ' + productId + ' added to cart!');
}
</script>

<?php include 'dashboard_footer.php'; ?>
<style>
/* Enhanced Market Page Styles */
.market-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%; /* Ensure container takes full width */
    box-sizing: border-box; /* Include padding and border in the element's total width and height */
}

.market-container h2 {
    color: #4CAF50;
    margin-bottom: 20px;
    font-size: 24px;
    text-align: center; /* Center-align heading for better presentation */
}

.section-selection {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.section-button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    margin: 0 10px;
    font-size: 16px;
    transition: background-color 0.3s;
    display: inline-block;
    text-align: center;
}

.section-button:hover {
    background-color: #45a049;
}

.search-bar {
    display: flex;
    justify-content: center; /* Center-align search bar */
    margin-bottom: 20px;
}

.search-bar input[type="text"] {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    flex: 1;
    max-width: 400px; /* Limit width of search input */
    margin-right: 10px; /* Spacing between input and button */
}

.search-bar button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.search-bar button:hover {
    background-color: #45a049;
}

.filters, .sort-options {
    margin-bottom: 20px;
}

.filters label, .sort-options h3 {
    display: block;
    margin-bottom: 10px;
}

.filters select,
.filters input[type="text"],
.sort-options select {
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ddd;
    font-size: 16px;
    width: 100%; /* Ensure filters and sort options take full width */
    box-sizing: border-box;
}

.filters button, .sort-options button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.3s;
}

.filters button:hover, .sort-options button:hover {
    background-color: #45a049;
}

.product-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center; /* Center-align product list items */
}

.product-item {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: calc(33.333% - 20px); /* Adjust width based on gap */
    box-sizing: border-box;
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.product-item h4 {
    margin-bottom: 10px;
    color: #333;
    font-size: 18px;
}

.product-item p {
    margin-bottom: 10px;
    color: #666;
    font-size: 16px;
}

.product-item button {
    background-color: #4CAF50;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.product-item button:hover {
    background-color: #45a049;
}

.pagination {
    margin-top: 20px;
    text-align: center;
}

.pagination a {
    padding: 10px 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin: 0 5px;
    text-decoration: none;
    color: #4CAF50;
    font-size: 16px;
    transition: background-color 0.3s, color 0.3s;
}

.pagination a.active {
    background-color: #4CAF50;
    color: #fff;
}

.pagination a:hover {
    background-color: #45a049;
    color: #fff;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .product-item {
        width: calc(50% - 20px); /* Two items per row for medium screens */
    }
}

@media (max-width: 768px) {
    .product-item {
        width: calc(100% - 20px); /* Full width for small screens */
    }
    
    .search-bar {
        flex-direction: column; /* Stack search input and button vertically on small screens */
    }
    
    .search-bar input[type="text"] {
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    .filters, .sort-options {
        margin-bottom: 15px;
    }
}
</style>

