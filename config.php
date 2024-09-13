<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myshop";


// Database connection constants

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define database connection constants

?>
