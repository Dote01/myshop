<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current 2FA settings
$sql = "SELECT two_factor_enabled FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $two_factor_enabled = isset($_POST['two_factor_enabled']) ? 1 : 0;

    $sql = "UPDATE users SET two_factor_enabled = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $two_factor_enabled, $user_id);

    if ($stmt->execute()) {
        echo "2FA settings updated successfully.";
    } else {
        echo "Error updating 2FA settings.";
    }
}
?>

<form method="POST" action="update_2fa.php">
    <label for="two_factor_enabled">
        <input type="checkbox" name="two_factor_enabled" id="two_factor_enabled" <?php echo $user['two_factor_enabled'] ? 'checked' : ''; ?>>
        Enable Two-Factor Authentication
    </label>
    <button type="submit">Update 2FA Settings</button>
</form>
