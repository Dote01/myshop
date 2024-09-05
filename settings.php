<?php
session_start();
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user settings
$sql = "SELECT * FROM settings WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$settings = $result->fetch_assoc();

// Update user settings
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $setting_key = $_POST['setting_key'];
    $setting_value = $_POST['setting_value'];
    
    // Update settings query
    $sql = "UPDATE settings SET setting_value = ? WHERE user_id = ? AND setting_key = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $setting_value, $user_id, $setting_key);
    
    if ($stmt->execute()) {
        echo "<div class='success-message'>Settings updated successfully!</div>";
    } else {
        echo "<div class='error-message'>Error updating settings.</div>";
    }
}
?>

<?php include 'dashboard_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .settings-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .settings-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .settings-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group select,
        .form-group input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-update {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-update:hover {
            background-color: #45a049;
        }

        .success-message,
        .error-message {
            text-align: center;
            font-size: 14px;
            padding: 10px;
            margin-top: 10px;
        }

        .success-message {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
        }

        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="settings-container">
    <h2>User Settings</h2>
    <form action="settings.php" method="POST" class="settings-form">
        <div class="form-group">
            <label for="setting_key">Select Setting:</label>
            <select id="setting_key" name="setting_key">
                <option value="email_notifications" <?php if ($settings['setting_key'] == 'email_notifications') echo 'selected'; ?>>Email Notifications</option>
                <option value="sms_notifications" <?php if ($settings['setting_key'] == 'sms_notifications') echo 'selected'; ?>>SMS Notifications</option>
                <option value="push_notifications" <?php if ($settings['setting_key'] == 'push_notifications') echo 'selected'; ?>>Push Notifications</option>
            </select>
        </div>

        <div class="form-group">
            <label for="setting_value">Enter Value:</label>
            <input type="text" id="setting_value" name="setting_value" value="<?php echo htmlspecialchars($settings['setting_value']); ?>" required>
        </div>

        <button type="submit" class="btn-update">Update Settings</button>
    </form>
</div>

<?php include 'dashboard_footer.php'; ?>
</body>
</html>
