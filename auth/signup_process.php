<?php
session_start();
require_once 'config.php';  // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Password hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Capture the role and assign role_id accodingly
    $role = $_POST['role'];
    if ($role === 'user') {
        $role_id = 1;
    } elseif ($role === 'retailer') {
        $role_id = 2;
    } else {
        echo "Invalid role selected.";
        exit;
    }

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, email, password, role_id) VALUES ('$username', '$email', '$password', '$role_id')";
    if ($conn->query($sql) === TRUE) {
        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['role_id'] = $role_id;
        $_SESSION['logged_in'] = true;

        // Redirect to the dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method.";

}
?>
