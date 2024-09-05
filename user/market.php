<?php include '../dashboard/dashboard_header.php'; ?>

<!-- Market Page Content -->
<div class="market-container">
    <h2>Market</h2>
    <div class="search-bar">
        <input type="text" placeholder="Search products..." id="search-input">
        <button onclick="searchProducts()">Search</button>
    </div>

    <div class="filters">
        <h3>Filters</h3>
        <!-- Add filter options here, e.g., price range, category -->
    </div>

    <div class="product-list">
        <?php
        // Fetch products from the database
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

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
</div>

<script>
// JavaScript functions for search and adding to cart
function searchProducts() {
    // Implement search functionality
}

function addToCart(productId) {
    // Implement add to cart functionality
}
</script>

<?php include '../dashboard/dashboard_footer.php'; ?>
