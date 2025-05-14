<?php
$host = 'localhost'; // Or your actual DB host
$db   = 'campus_news';
$user = 'root';      // Use your actual username
$pass = '';          // Use your actual password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

header("Content-Type: application/json");
?>
