<?php
require_once '../../Config/database.php';

//to show backend errors
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

$database = new Database();
$db = $database->getConnection();

// Use $_POST since frontend sends FormData
if (
    !empty($_POST['code']) &&
    !empty($_POST['title']) &&
    !empty($_POST['price']) &&
    !empty($_POST['condition']) &&
    !empty($_POST['pickup']) &&
    !empty($_POST['seller']) &&
    !empty($_POST['image'])
) {
    $query = "INSERT INTO listings 
              SET code=:code, title=:title, price=:price, condition=:condition, 
                  pickup=:pickup, seller=:seller, image=:image";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":code", $_POST['code']);
    $stmt->bindParam(":title", $_POST['title']);
    $stmt->bindParam(":price", $_POST['price']);
    $stmt->bindParam(":condition", $_POST['condition']);
    $stmt->bindParam(":pickup", $_POST['pickup']);
    $stmt->bindParam(":seller", $_POST['seller']);
    $stmt->bindParam(":image", $_POST['image']);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Book created."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to create book."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Incomplete form data."]);
}
?>
