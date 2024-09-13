<?php
session_start();
require_once 'config.php';

$errors = [];
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Validate input
    if (empty($email)) {
        $errors[] = "Email is required.";
    } else {
        // Check if the email exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $user_id = $user['user_id'];

            // Generate a unique token
            $token = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store token and expiry in the database
            $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $token, $expires_at);
            $stmt->execute();

            // Send reset link to the user
            $reset_link = "http://yourdomain.com/reset_password.php?token=$token";
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password: $reset_link";
            $headers = "From: no-reply@yourdomain.com";

            if (mail($email, $subject, $message, $headers)) {
                $message = "A password reset link has been sent to your email address.";
            } else {
                $errors[] = "Failed to send email. Please try again later.";
            }
        } else {
            $errors[] = "No account found with that email.";
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
    <title>Forgot Password - MyShop</title>
    <style>
        /* Include relevant styles similar to the login page */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            color: #333;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 3em;
            color: #0072ff;
            margin: 0;
            font-weight: 700;
        }

        .login-form {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            animation: fadeIn 1s ease-in-out;
            overflow: hidden;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .login-form h2 {
            font-size: 2.2em;
            color: #0072ff;
            margin-bottom: 20px;
            font-weight: 700;
            text-align: center;
        }

        .error-messages {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 0.9em;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .error-messages p {
            margin: 0;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 8px;
        }

        input {
            padding: 15px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 6px;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus {
            border-color: #0072ff;
            box-shadow: 0 0 6px rgba(0, 114, 255, 0.5);
            outline: none;
        }

        button {
            background: #28a745;
            color: #ffffff;
            padding: 15px;
            font-size: 1.1em;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
        }

        button:hover {
            background: #218838;
            transform: scale(1.03);
        }

        p {
            font-size: 1em;
            color: #555;
            text-align: center;
        }

        a {
            color: #0072ff;
            text-decoration: none;
            font-weight: 700;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .login-form {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="header">
            <h1>MyShop</h1>
        </div>
        <div class="login-form">
            <h2>Forgot Password</h2>
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
            <form action="forgot_password.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <button type="submit">Send Reset Link</button>
            </form>
            <p>Remembered your password? <a href="login.php">Login here</a>.</p>
        </div>
    </div>
</body>
</html>
