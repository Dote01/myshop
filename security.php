<?php include 'dashboard/dashboard_header.php'; ?>

<!-- Security Page Content -->
<div class="security-page-container">
    <h2>Security Settings</h2>
    
    <form action="update_password.php" method="post">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>
        
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <button type="submit">Update Password</button>
    </form>

    <form action="update_2fa.php" method="post">
        <label for="2fa_option">Two-Factor Authentication:</label>
        <select id="2fa_option" name="2fa_option" required>
            <option value="enable">Enable</option>
            <option value="disable">Disable</option>
        </select>
        <button type="submit">Update 2FA</button>
    </form>

    <h3>Login History</h3>
    <div class="login-history">
        <?php
        // Fetch login history from the database
        $sql = "SELECT * FROM login_history WHERE user_id = ? ORDER BY login_time DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($login = $result->fetch_assoc()) {
                echo '<div class="login-item">';
                echo '<p>Date: ' . htmlspecialchars($login['login_time']) . '</p>';
                echo '<p>IP Address: ' . htmlspecialchars($login['ip_address']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No login history available.</p>';
        }
        ?>
    </div>
</div>

<?php include 'dashboard/dashboard_footer.php'; ?>
