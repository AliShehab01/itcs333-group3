<?php
require_once '../../Config/database.php';

//to show backend errors
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM listings ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();

if ($num > 0) {
    $books = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $books[] = [
            "id" => $id,
            "title" => $title,
            "code" => $code,
            "price" => $price,
            "condition" => $condition,
            "pickup" => $pickup,
            "seller" => $seller,
            "image" => $image
        ];
    }
    echo json_encode(["status" => "success", "data" => $books]);
} else {
    echo json_encode(["status" => "error", "message" => "No books found."]);
}
?>
