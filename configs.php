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

// Optional: Define DSN for PDO if needed
if (!defined('DB_DSN')) {
    define('DB_DSN', "mysql:host=$host;dbname=$db;charset=$charset");
}
function getDbConnection() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

?>
