<?php
require_once '../../Config/database.php';

//to show backend errors
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->id) &&
    !empty($data->title) &&
    !empty($data->code) &&
    !empty($data->price) &&
    !empty($data->condition) &&
    !empty($data->pickup) &&
    !empty($data->seller) &&
    !empty($data->image)
) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "UPDATE listings 
              SET title = :title, code = :code, price = :price, 
                  condition = :condition, pickup = :pickup, 
                  seller = :seller, image = :image 
              WHERE id = :id";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":id", $data->id);
    $stmt->bindParam(":title", $data->title);
    $stmt->bindParam(":code", $data->code);
    $stmt->bindParam(":price", $data->price);
    $stmt->bindParam(":condition", $data->condition);
    $stmt->bindParam(":pickup", $data->pickup);
    $stmt->bindParam(":seller", $data->seller);
    $stmt->bindParam(":image", $data->image);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Book updated."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update book."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Incomplete data."]);
}
?>
