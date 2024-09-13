<?php
session_start();
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize variables
$success_message = '';
$error_message = '';
$email_frequency = '';
$notification_status = '';

// Fetch current email notification settings
$sql = "SELECT * FROM settings WHERE user_id = ? AND setting_key IN ('email_notifications_frequency', 'email_notifications_status')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    if ($row['setting_key'] == 'email_notifications_frequency') {
        $email_frequency = $row['setting_value'];
    } elseif ($row['setting_key'] == 'email_notifications_status') {
        $notification_status = $row['setting_value'];
    }
}

// Update email notification settings
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email_frequency']) && isset($_POST['notification_status'])) {
        $email_frequency = $_POST['email_frequency'];
        $notification_status = $_POST['notification_status'];
        
        // Update settings query
        $sql = "UPDATE settings SET setting_value = ? WHERE user_id = ? AND setting_key = ?";
        $stmt = $conn->prepare($sql);

        // Update frequency
        $stmt->bind_param("sis", $email_frequency, $user_id, 'email_notifications_frequency');
        if (!$stmt->execute()) {
            $error_message = "Error updating email frequency. Please try again.";
        }

        // Update status
        $stmt->bind_param("sis", $notification_status, $user_id, 'email_notifications_status');
        if (!$stmt->execute()) {
            $error_message = "Error updating email notification status. Please try again.";
        }

        if (!$error_message) {
            $success_message = "Email notification settings updated successfully!";
        }
    } else {
        $error_message = "Required fields are missing.";
    }
}
?>

<?php include 'dashboard_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification Settings</title>
    <style>
        /* Your existing CSS styles */
        /* You may want to include additional styles for this page */
    </style>
</head>
<body>
<div class="settings-container">
    <h2>Email Notification Settings</h2>

    <?php if ($success_message): ?>
        <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form action="email_notification.php" method="POST" class="settings-form">
        <div class="form-group">
            <label for="email_frequency">Email Frequency:</label>
            <select id="email_frequency" name="email_frequency" required>
                <option value="" disabled>Select frequency</option>
                <option value="immediate" <?php if ($email_frequency == 'immediate') echo 'selected'; ?>>Immediate</option>
                <option value="daily" <?php if ($email_frequency == 'daily') echo 'selected'; ?>>Daily</option>
                <option value="weekly" <?php if ($email_frequency == 'weekly') echo 'selected'; ?>>Weekly</option>
            </select>
        </div>

        <div class="form-group">
            <label for="notification_status">Enable Notifications:</label>
            <select id="notification_status" name="notification_status" required>
                <option value="1" <?php if ($notification_status == '1') echo 'selected'; ?>>Enabled</option>
                <option value="0" <?php if ($notification_status == '0') echo 'selected'; ?>>Disabled</option>
            </select>
        </div>

        <button type="submit" class="btn-update">Update Settings</button>
    </form>
</div>

<?php include 'dashboard_footer.php'; ?>
</body>
</html>
