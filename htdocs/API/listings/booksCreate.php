
<?php
header("Content-Type: application/json");
require_once "../../Config/database.php";

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['code']) && 
        isset($_POST['title']) && 
        isset($_POST['price']) && 
        isset($_POST['book_condition']) && 
        isset($_POST['pickup']) && 
        isset($_POST['seller']) &&
        isset($_POST['image'])
    ) {
        $sql = "INSERT INTO listings (code, title, price, book_condition, pickup, seller, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                $_POST['code'],
                $_POST['title'],
                $_POST['price'],
                $_POST['book_condition'],
                $_POST['pickup'],
                $_POST['seller'],
                $_POST['image']
            ]);

            if ($result) {
                echo json_encode(["status" => "success", "message" => "Book added successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to add book"]);
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(["status" => "error", "message" => "Database error occurred"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
