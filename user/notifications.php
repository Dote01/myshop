
<?php include '../dashboard/dashboard_header.php'; ?>

<!-- Notifications Page Content -->
<div class="notifications-container">
    <h2>Notifications</h2>
    <div class="notifications-list">
        <?php
        // Fetch notifications from the database
        $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($notification = $result->fetch_assoc()) {
                echo '<div class="notification-item">';
                echo '<p>' . htmlspecialchars($notification['message']) . '</p>';
                echo '<span>' . htmlspecialchars($notification['created_at']) . '</span>';
                echo '</div>';
            }
        } else {
            echo '<p>No notifications available.</p>';
        }
        ?>
    </div>
</div>

<?php include '../dashboard/dashboard_footer.php'; ?>
