<?php
require_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$records_per_page = 10;
$from_record_num = ($records_per_page * $page) - $records_per_page;

$query = "SELECT listings.id, listings.title, listings.description, listings.price, listings.created_at, users.username 
          FROM listings 
          JOIN users ON listings.user_id = users.id 
          ORDER BY listings.created_at DESC 
          LIMIT :from_record_num, :records_per_page";

$stmt = $db->prepare($query);
$stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
$stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);
$stmt->execute();

$num = $stmt->rowCount();

if($num > 0) {
    $listings_arr = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $listing_item = [
            "id" => $id,
            "title" => $title,
            "description" => $description,
            "price" => $price,
            "created_at" => $created_at,
            "username" => $username
        ];
        array_push($listings_arr, $listing_item);
    }
    http_response_code(200);
    echo json_encode($listings_arr);
} else {
    http_response_code(404);
    echo json_encode(["message" => "No listings found."]);
}
?>
