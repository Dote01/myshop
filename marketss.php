<?php include 'dashboard_header.php'; ?>

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
    const query = document.getElementById('search-input').value.toLowerCase();
    const items = document.querySelectorAll('.product-item');
    
    items.forEach(item => {
        const name = item.querySelector('h4').textContent.toLowerCase();
        const description = item.querySelector('p').textContent.toLowerCase();
        
        if (name.includes(query) || description.includes(query)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function addToCart(productId) {
    // Implement add to cart functionality
    alert('Product ' + productId + ' added to cart!');
}
</script>

<?php include 'dashboard_footer.php'; ?>

<style>
    .market-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .market-container h2 {
        color: #4CAF50;
        margin-bottom: 20px;
    }

    .search-bar {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .search-bar input[type="text"] {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        flex: 1;
    }

    .search-bar button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-left: 10px;
        transition: background-color 0.3s;
    }

    .search-bar button:hover {
        background-color: #45a049;
    }

    .filters {
        margin-bottom: 20px;
    }

    .product-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .product-item {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 30%;
        box-sizing: border-box;
    }

    .product-item h4 {
        margin-bottom: 10px;
        color: #333;
    }

    .product-item p {
        margin-bottom: 10px;
        color: #666;
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
</style>
