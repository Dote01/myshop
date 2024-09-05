<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current privacy settings
$sql = "SELECT profile_visibility FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $profile_visibility = $_POST['profile_visibility'];

    $sql = "UPDATE users SET profile_visibility = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $profile_visibility, $user_id);
    
    if ($stmt->execute()) {
        echo "Privacy settings updated successfully.";
    } else {
        echo "Error updating privacy settings.";
    }
}
?>

<form method="POST" action="update_privacy_settings.php">
    <label for="profile_visibility">Profile Visibility:</label>
    <select name="profile_visibility" id="profile_visibility">
        <option value="public" <?php echo $user['profile_visibility'] == 'public' ? 'selected' : ''; ?>>Public</option>
        <option value="private" <?php echo $user['profile_visibility'] == 'private' ? 'selected' : ''; ?>>Private</option>
    </select>
    <button type="submit">Update Settings</button>
</form>
