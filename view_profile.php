 <?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT username, email, profile_picture FROM users WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Update user profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $profile_picture = $_FILES['profile_picture'];

    // Handle profile picture upload
    if ($profile_picture['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = basename($profile_picture['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($profile_picture['tmp_name'], $file_path)) {
            // Update profile picture path
            $sql = "UPDATE users SET profile_picture = ? WHERE user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$file_path, $user_id]);
        } else {
            $message = "Error uploading profile picture.";
        }
    }

    // Update other profile data
    $sql = "UPDATE users SET username = ?, email = ? WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$username, $email, $user_id])) {
        $_SESSION['username'] = $username;  // Update session variable
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile.";
    }
}
?>

<?php include 'dashboard_header.php'; ?>

<div class="profile-container">
    <div class="profile-header">
        <h2>User Profile</h2>
        <div class="profile-picture">
            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
        </div>
    </div>

    <?php if (isset($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="view_profile.php" method="POST" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*">

        <button type="submit">Update Profile</button>
    </form>
</div>

<?php include 'dashboard_footer.php'; ?>

<style>
    .profile-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #4CAF50;
        padding-bottom: 20px;
    }

    .profile-header h2 {
        flex: 1;
        color: #4CAF50;
    }

    .profile-picture img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #4CAF50;
    }

    .profile-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .profile-container label {
        font-weight: bold;
        color: #333;
    }

    .profile-container input[type="text"],
    .profile-container input[type="email"],
    .profile-container input[type="file"] {
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        width: 100%;
        box-sizing: border-box;
    }

    .profile-container button {
        padding: 12px 24px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
    }

    .profile-container button:hover {
        background-color: #45a049;
    }

    .message {
        padding: 12px;
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 16px;
    }
</style>
