<?php
// Database configuration
$host = "localhost";
$user = getenv("db_user");
$pass = getenv("db_pass");
$db = getenv("db_name");

// Create database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");
?>
