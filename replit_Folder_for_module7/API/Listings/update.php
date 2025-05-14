<?php
require_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id) && !empty($data->title) && !empty($data->description) && !empty($data->price)) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "UPDATE listings SET title = :title, description = :description, price = :price WHERE id = :id";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":title", $data->title);
    $stmt->bindParam(":description", $data->description);
    $stmt->bindParam(":price", $data->price);
    $stmt->bindParam(":id", $data->id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Listing updated."]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Unable to update listing."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data."]);
}
?>
