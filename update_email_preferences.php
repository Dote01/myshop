<?php
session_start();
require_once 'config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current email preferences
$sql = "SELECT receive_newsletters FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receive_newsletters = isset($_POST['receive_newsletters']) ? 1 : 0;

    $sql = "UPDATE users SET receive_newsletters = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $receive_newsletters, $user_id);
    
    if ($stmt->execute()) {
        echo "Email preferences updated successfully.";
    } else {
        echo "Error updating email preferences.";
    }
}

?>

<form method="POST" action="update_email_preferences.php">
    <label for="receive_newsletters">
        <input type="checkbox" name="receive_newsletters" id="receive_newsletters" <?php echo $user['receive_newsletters'] ? 'checked' : ''; ?>>
        Receive Newsletters
    </label>
    <button type="submit">Update Preferences</button>
</form>
