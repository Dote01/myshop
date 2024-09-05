<?php
session_start();
require_once 'configs.php';

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

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $profile_picture = $_FILES['profile_picture']['name'];
    
    // Profile picture upload
    if (!empty($profile_picture)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is an actual image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check === false) {
            $uploadOk = 0;
            $message = "File is not an image.";
        }
        
        // Check file size
        if ($_FILES["profile_picture"]["size"] > 500000) {
            $uploadOk = 0;
            $message = "Sorry, your file is too large.";
        }
        
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
        
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $message = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $message = "The file ". htmlspecialchars(basename($profile_picture)). " has been uploaded.";
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        }
    }
    
    // Update query
    $sql = "UPDATE users SET username = ?, email = ?" . (!empty($profile_picture) ? ", profile_picture = ?" : "") . " WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    if (!empty($profile_picture)) {
        $stmt->execute([$username, $email, $profile_picture, $user_id]);
    } else {
        $stmt->execute([$username, $email, $user_id]);
    }
    
    if ($stmt->rowCount()) {
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
    </div>

    <?php if (isset($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <div class="profile-content">
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars(!empty($user['profile_picture']) ? 'uploads/' . $user['profile_picture'] : 'uploads/default.png'); ?>" alt="Profile Picture">
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
            </div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</div>

<?php include 'dashboard_footer.php'; ?>

<style>
    .profile-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .profile-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-header h2 {
        color: #4CAF50;
    }

    .profile-content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .profile-picture {
        text-align: center;
    }

    .profile-picture img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #4CAF50;
        margin-bottom: 10px;
    }

    .profile-picture input[type="file"] {
        margin-top: 10px;
        padding: 10px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .profile-picture input[type="file"]:hover {
        background-color: #45a049;
    }

    .profile-content label {
        font-weight: bold;
        color: #333;
    }

    .profile-content input[type="text"],
    .profile-content input[type="email"] {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%;
        box-sizing: border-box;
    }

    .profile-content button {
        padding: 12px 24px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
    }

    .profile-content button:hover {
        background-color: #45a049;
    }

    .message {
        padding: 15px;
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 4px;
        margin-bottom: 20px;
        font-size: 16px;
    }
</style>
