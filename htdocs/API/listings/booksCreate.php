<?php
require_once '../../Config/database.php';

header("Content-Type: application/json");
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !empty($_POST['code']) &&
        !empty($_POST['title']) &&
        !empty($_POST['price']) &&
        !empty($_POST['book_condition']) &&
        !empty($_POST['pickup']) &&
        !empty($_POST['seller']) &&
        !empty($_POST['image'])
    ) {
        $query = "INSERT INTO listings (code, title, price, `condition`, pickup, seller, image) 
                  VALUES (:code, :title, :price, :book_condition, :pickup, :seller, :image)";

        $stmt = $db->prepare($query);

        $stmt->bindParam(":code", $_POST['code']);
        $stmt->bindParam(":title", $_POST['title']);
        $stmt->bindParam(":price", $_POST['price']);
        $stmt->bindParam(":book_condition", $_POST['book_condition']);
        $stmt->bindParam(":pickup", $_POST['pickup']);
        $stmt->bindParam(":seller", $_POST['seller']);
        $stmt->bindParam(":image", $_POST['image']);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Book was created successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Unable to create book."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Incomplete data provided."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>