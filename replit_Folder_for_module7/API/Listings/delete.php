<?php
require_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "DELETE FROM listings WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $data->id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Listing deleted."]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Unable to delete listing."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data."]);
}
?>
