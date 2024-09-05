<?php include '../dashboard/dashboard_header.php'; ?>

<!-- Profile Page Content -->
<div class="profile-container">
    <h2>Your Profile</h2>
    <form action="update_profile.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="password">Change Password:</label>
        <input type="password" id="password" name="password">

        <button type="submit">Update Profile</button>
    </form>

    <h3>Purchase History</h3>
    <div class="purchase-history">
        <?php
        // Fetch purchase history from the database
        $sql = "SELECT * FROM purchases WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($purchase = $result->fetch_assoc()) {
                echo '<div class="purchase-item">';
                echo '<p>Product: ' . htmlspecialchars($purchase['product_name']) . '</p>';
                echo '<p>Date: ' . htmlspecialchars($purchase['purchase_date']) . '</p>';
                echo '<p>Amount: $' . htmlspecialchars($purchase['amount']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No purchase history.</p>';
        }
        ?>
    </div>

    <h3>Subscriptions</h3>
    <div class="subscriptions">
        <?php
        // Fetch user subscriptions from the database
        $sql = "SELECT * FROM subscriptions WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($subscription = $result->fetch_assoc()) {
                echo '<div class="subscription-item">';
                echo '<p>Subscribed to: ' . htmlspecialchars($subscription['retailer_name']) . '</p>';
                echo '<button onclick="unsubscribe(' . $subscription['id'] . ')">Unsubscribe</button>';
                echo '</div>';
            }
        } else {
            echo '<p>No subscriptions.</p>';
        }
        ?>
    </div>
</div>

<script>
// JavaScript function for unsubscribing
function unsubscribe(subscriptionId) {
    // Implement unsubscribe functionality
}
</script>

<?php include '../dashboard/dashboard_footer.php'; ?>

