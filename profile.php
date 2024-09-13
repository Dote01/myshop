<?php
session_start();
require_once 'configss.php'; // Ensure this path is correct

if (!isset($pdo)) {
    die("PDO is not initialized.");
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
try {
    $sql = "SELECT username, email, profile_picture, password FROM users WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $profile_picture = $_FILES['profile_picture']['name'] ?? '';
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $message = '';
}
    // Update password if provided
    if (!empty($new_password) || !empty($confirm_password)) {
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                try {
                    $sql = "UPDATE users SET password = ? WHERE user_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$hashed_password, $user_id]);
                    $message = "Password updated successfully!";
                } catch (PDOException $e) {
                    $message = "Password update failed: " . $e->getMessage();
                }
            } else {
                $message = "New passwords do not match.";
            }
        } else {
            $message = "Current password is incorrect.";
        }
    }
// Profile picture upload
if (!empty($profile_picture)) {
    $target_dir = "uploads/";
    $file_name = time() . "_" . basename($profile_picture); // Unique file name
    $target_file = $target_dir . $file_name;
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
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        $uploadOk = 0;
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $message = "The file ". htmlspecialchars($file_name). " has been uploaded.";
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}

// Update profile
try {
    $sql = "UPDATE users SET username = ?, email = ?" . (!empty($profile_picture) ? ", profile_picture = ?" : "") . " WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $params = [$username, $email];
    if (!empty($profile_picture)) {
        $params[] = $file_name; // Use unique file name
    }
    $params[] = $user_id;

    if ($stmt->execute($params)) {
        $_SESSION['username'] = $username;  // Update session variable
        $_SESSION['profile_picture'] = $file_name; // Update session variable
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile.";
    }
} catch (PDOException $e) {
    $message = "Update failed: " . $e->getMessage();
}


// Handle account deactivation
if (isset($_POST['deactivate'])) {
    try {
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$user_id])) {
            session_destroy();
            header("Location: login.php");
            exit();
        } else {
            $message = "Error deactivating account.";
        }
    } catch (PDOException $e) {
        $message = "Deactivation failed: " . $e->getMessage();
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

            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password">

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password">

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">

            <button type="submit">Update Profile</button>
        </form>

        <form action="profile.php" method="POST" style="margin-top: 20px;">
            <button type="submit" name="deactivate" class="deactivate-button">Deactivate Account</button>
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
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-header h2 {
        color: #4CAF50;
        font-size: 24px;
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
    }

    label {
        font-weight: bold;
        margin-top: 10px;
        display: block;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    button[type="submit"] {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        text-transform: uppercase;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
    }

    button[type="submit"]:hover {
        background-color: #45a049;
    }

    .deactivate-button {
        background-color: #e74c3c;
    }

    .deactivate-button:hover {
        background-color: #c0392b;
    }

    .message {
        color: #e74c3c;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
    }
</style>
