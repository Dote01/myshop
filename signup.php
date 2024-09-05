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
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $errors[] = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Username or Email is already taken.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Assign role_id based on selected role
        $role_id = ($role === "user") ? 1 : 2;

        if (empty($errors)) {
            // Insert user into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role_id, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("sssi", $username, $email, $hashed_password, $role_id);

            if ($stmt->execute()) {
                // Get the newly created user ID
                $user_id = $stmt->insert_id;

                // If the role is retailer, create a retailer entry
                if ($role_id == 2) {
                    $stmt = $conn->prepare("INSERT INTO retailers (user_id, business_name, storage_capacity, created_at, updated_at) VALUES (?, ?, 500, NOW(), NOW())");
                    $business_name = $username . "'s Business";
                    $stmt->bind_param("is", $user_id, $business_name);
                    $stmt->execute();
                }

                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['logged_in'] = true;

                // Redirect to the appropriate dashboard
                if ($role === "user") {
                    header("Location: dashboard.php");
                } elseif ($role === "retailer") {
                    header("Location: retailer_dashboard.php");
                }
                exit();
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - MyShop</title>
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

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .signup-container {
            max-width: 500px;
            width: 100%;
            padding: 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            animation: fadeIn 1s ease-in-out;
            overflow: hidden;
            position: relative;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header img {
            max-width: 150px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 2.5em;
            color: #0072ff;
            margin: 0;
            font-weight: 700;
            position: relative;
        }

        .header h1::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background: #0072ff;
            border-radius: 2px;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.4s ease-in-out;
        }

        .header h1:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
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

        input, select {
            padding: 15px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 6px;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus {
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
            .signup-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-container">
            <div class="header">
                <!-- Add your logo image here -->
                <img src="path/to/your/logo.png" alt="MyShop Logo">
                <h1>MyShop</h1>
            </div>
            <?php
            if (!empty($errors)) {
                echo '<div class="error-messages">';
                foreach ($errors as $error) {
                    echo '<p>' . htmlspecialchars($error) . '</p>';
                }
                echo '</div>';
            }
            ?>
            <form action="signup.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="" disabled>Select your role</option>
                    <option value="user" <?php echo isset($role) && $role === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="retailer" <?php echo isset($role) && $role === 'retailer' ? 'selected' : ''; ?>>Retailer</option>
                </select>

                <button type="submit">Sign Up</button>
            </form>
            <p>Already have an account? <a href="login.php">Log In</a></p>
        </div>
    </div>
</body>
</html>
