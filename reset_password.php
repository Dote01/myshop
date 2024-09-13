<?php
session_start();
require_once 'config.php';

$errors = [];
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($password) || empty($confirm_password)) {
        $errors[] = "Both password fields are required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } else {
        // Check if token exists and is valid
        $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $reset = $result->fetch_assoc();
            $user_id = $reset['user_id'];

            // Update the user's password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            $stmt->execute();

            // Delete the reset token
            $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            $message = "Your password has been reset successfully. You can now <a href='login.php'>login</a>.";
        } else {
            $errors[] = "Invalid or expired token.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - MyShop</title>
    <style>
        /* Include relevant styles similar to the login page */
    </style>
</head>
<body>
    <div class="login-container">
        <div class="header">
            <h1>MyShop</h1>
        </div>
        <div class="login-form">
            <h2>Reset Password</h2>
            <?php
            if (!empty($errors)) {
                echo '<div class="error-messages">';
                foreach ($errors as $error) {
                    echo '<p>' . htmlspecialchars($error) . '</p>';
                }
                echo '</div>';
            }
            if ($message) {
                echo '<div class="success-messages">';
                echo '<p>' . htmlspecialchars($message) . '</p>';
                echo '</div>';
            }
            ?>
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">

                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
