
<?php
header("Content-Type: application/json");
require_once "../../Config/database.php";

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    error_log("Database connection failed");
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

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
        try {
            $sql = "INSERT INTO listings (code, title, price, book_condition, pickup, seller, image) 
                    VALUES (:code, :title, :price, :book_condition, :pickup, :seller, :image)";
            
            $stmt = $db->prepare($sql);
            
            $params = [
                ':code' => $_POST['code'],
                ':title' => $_POST['title'],
                ':price' => $_POST['price'],
                ':book_condition' => $_POST['book_condition'],
                ':pickup' => $_POST['pickup'],
                ':seller' => $_POST['seller'],
                ':image' => $_POST['image']
            ];

            if ($stmt->execute($params)) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Book added successfully",
                    "id" => $db->lastInsertId()
                ]);
            } else {
                error_log("Execute failed: " . implode(", ", $stmt->errorInfo()));
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed to add book"
                ]);
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode([
                "status" => "error", 
                "message" => "Database error occurred"
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method"
    ]);
}
?>
