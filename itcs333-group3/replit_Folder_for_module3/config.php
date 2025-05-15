<?php
// Replit MySQL connection settings


try {
    $host = "127.0.0.1";
    $user = getenv("db_user");
    $pass = getenv("db_pass");
    $db = getenv("db_name");

    $conn = new mysqli( $host, $user, $pass, $db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    

    $conn->exec("USE $dbname");
    
    if (basename($_SERVER['PHP_SELF']) === 'api.php') {
        header('Content-Type: application/json');
    }
} catch(PDOException $e) {
    if (basename($_SERVER['PHP_SELF']) === 'api.php') {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode(['error' => "Connection failed: " . $e->getMessage()]);
    } else {
        echo "Database Connection Error: " . $e->getMessage();
    }
    exit;
}
?>