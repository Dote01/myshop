<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current notification settings
$sql = "SELECT email_notifications, sms_notifications FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
    $sms_notifications = isset($_POST['sms_notifications']) ? 1 : 0;

    $sql = "UPDATE users SET email_notifications = ?, sms_notifications = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $email_notifications, $sms_notifications, $user_id);
    
    if ($stmt->execute()) {
        echo "Notification preferences updated successfully.";
    } else {
        echo "Error updating notification preferences.";
    }
}
?>

<form method="POST" action="update_notifications.php">
    <label for="email_notifications">
        <input type="checkbox" name="email_notifications" id="email_notifications" <?php echo $user['email_notifications'] ? 'checked' : ''; ?>>
        Email Notifications
    </label>
    <label for="sms_notifications">
        <input type="checkbox" name="sms_notifications" id="sms_notifications" <?php echo $user['sms_notifications'] ? 'checked' : ''; ?>>
        SMS Notifications
    </label>
    <button type="submit">Update Preferences</button>
</form>
