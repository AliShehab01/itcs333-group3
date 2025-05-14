<?php
require_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id) && !empty($data->title) && !empty($data->description) && !empty($data->price)) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "INSERT INTO listings SET user_id=:user_id, title=:title, description=:description, price=:price";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":user_id", $data->user_id);
    $stmt->bindParam(":title", $data->title);
    $stmt->bindParam(":description", $data->description);
    $stmt->bindParam(":price", $data->price);

    if($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Listing created."]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Unable to create listing."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data."]);
}
?>
