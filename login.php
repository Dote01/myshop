<?php
session_start();
require_once 'config.php';

// Redirect to dashboard if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required.";
    }

    // If no errors, proceed with login
    if (empty($errors)) {
        // Fetch user from the database
        $stmt = $conn->prepare("SELECT user_id, username, password, role_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role_id'];
                $_SESSION['logged_in'] = true;

                // Redirect based on role
                switch ($user['role_id']) {
                    case 1: // User
                        header("Location: dashboard.php");
                        break;
                    case 2: // Retailer
                        header("Location: retailer_dashboard.php");
                        break;
                    case 3: // Admin
                        header("Location: admin_dashboard.php");
                        break;
                    default:
                        $errors[] = "Invalid role assigned.";
                        session_destroy();
                }
                exit();
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "No account found with that email.";
        }

        // Close the statement
        $stmt->close();
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MyShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
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
            <h2>Login</h2>
            <?php
            if (!empty($errors)) {
                echo '<div class="error-messages">';
                foreach ($errors as $error) {
                    echo '<p>' . htmlspecialchars($error) . '</p>';
                }
                echo '</div>';
            }
            ?>
            <form action="login.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
            <p><a href="forgot_password.php">Forgot your password?</a></p>

        </div>
    </div>
</body>
</html>
