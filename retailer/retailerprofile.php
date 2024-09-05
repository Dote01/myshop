<?php include '../dashboard/dashboard_header.php'; ?>

<!-- Retailer Profile Page Content -->
<div class="retailer-profile-container">
    <h2>Your Retailer Profile</h2>
    <form action="update_profile.php" method="post">
        <label for="retailer_name">Retailer Name:</label>
        <input type="text" id="retailer_name" name="retailer_name" value="<?php echo htmlspecialchars($retailer['name']); ?>" required>

        <label for="business_email">Business Email:</label>
        <input type="email" id="business_email" name="business_email" value="<?php echo htmlspecialchars($retailer['email']); ?>" required>

        <label for="password">Change Password:</label>
        <input type="password" id="password" name="password">

        <button type="submit">Update Profile</button>
    </form>

    <h3>Your Stock</h3>
    <div class="stock-management">
        <?php
        // Fetch stock information from the database
        $sql = "SELECT * FROM stock WHERE retailer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $retailer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($stock_item = $result->fetch_assoc()) {
                echo '<div class="stock-item">';
                echo '<p>Product: ' . htmlspecialchars($stock_item['product_name']) . '</p>';
                echo '<p>Quantity: ' . htmlspecialchars($stock_item['quantity']) . '</p>';
                echo '<button onclick="restock(' . $stock_item['id'] . ')">Restock</button>';
                echo '</div>';
            }
        } else {
            echo '<p>No stock available.</p>';
        }
        ?>
    </div>

    <h3>Your Subscriptions</h3>
    <div class="subscriptions">
        <?php
        // Fetch subscriptions from the database
        $sql = "SELECT * FROM subscriptions WHERE retailer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $retailer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($subscription = $result->fetch_assoc()) {
                echo '<div class="subscription-item">';
                echo '<p>Subscribed User: ' . htmlspecialchars($subscription['user_name']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No subscriptions.</p>';
        }
        ?>
    </div>
</div>

<script>
// JavaScript function for restocking
function restock(stockId) {
    // Implement restock functionality
}
</script>

<?php include '../dashboard/dashboard_footer.php'; ?>
