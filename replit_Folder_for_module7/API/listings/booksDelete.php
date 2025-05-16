<?php
require_once '../../Config/database.php';

//to show backend errors
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "DELETE FROM listings WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $data->id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Book deleted."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete book."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Book ID is required."]);
}
?>
