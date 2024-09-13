<?php
$host = 'localhost';
$db = 'myshop'; // Replace with your actual database name
$user = 'root'; // Replace with your actual database user
$pass = ''; // Replace with your actual database password
$charset = 'utf8mb4';

// Define database connection constants
define('DB_HOST', $host);
define('DB_NAME', $db);
define('DB_USER', $user);
define('DB_PASSWORD', $pass);
define('DB_DSN', "mysql:host=$host;dbname=$db;charset=$charset");

// Create PDO instance
try {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
