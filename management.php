<?php include '../dashboard/dashboard_header.php'; ?>

<!-- Warehouse Management Page Content -->
<div class="warehouse-management-container">
    <h2>Warehouse Management</h2>

    <form action="add_product.php" method="post">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Industrial">Industrial</option>
            <option value="Farm">Farm</option>
            <option value="Other">Other</option>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>

        <button type="submit">Add Product</button>
    </form>

    <h3>Product Inventory</h3>
    <div class="product-inventory">
        <?php
        // Fetch products from the database
        $sql = "SELECT * FROM warehouse_products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                echo '<div class="product-item">';
                echo '<h4>' . htmlspecialchars($product['name']) . '</h4>';
                echo '<p>Category: ' . htmlspecialchars($product['category']) . '</p>';
                echo '<p>Description: ' . htmlspecialchars($product['description']) . '</p>';
                echo '<p>Price: $' . htmlspecialchars($product['price']) . '</p>';
                echo '<p>Quantity: ' . htmlspecialchars($product['quantity']) . '</p>';
                echo '<button onclick="editProduct(' . $product['id'] . ')">Edit</button>';
                echo '<button onclick="deleteProduct(' . $product['id'] . ')">Delete</button>';
                echo '</div>';
            }
        } else {
            echo '<p>No products available.</p>';
        }
        ?>
    </div>
</div>

<script>
// JavaScript functions for editing and deleting products
function editProduct(productId) {
    // Implement edit product functionality
}

function deleteProduct(productId) {
    // Implement delete product functionality
}
</script>

<?php include '../dashboard/dashboard_footer.php'; ?>
