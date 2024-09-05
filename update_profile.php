<?php
session_start();
require_once 'config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE users SET username = ?, email = ?, phone = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $email, $phone, $user_id);

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile.";
    }
}

// Fetch the current profile information
$sql = "SELECT username, email, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $phone);
$stmt->fetch();
$stmt->close();
?>

<form method="POST" action="update_profile.php">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" value="<?php echo $username; ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>

    <label for="phone">Phone:</label>
    <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>">

    <button type="submit">Update Profile</button>
</form>
