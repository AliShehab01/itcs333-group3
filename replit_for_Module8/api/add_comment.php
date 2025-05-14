<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'config.php';

$data = json_decode(file_get_contents("php://input"), true);
$news_id = $data["news_id"] ?? null;
$author = $data["author"] ?? "Anonymous";
$content = $data["content"] ?? "";

if (!$news_id || !$content) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO comments (news_id, author, content) VALUES (?, ?, ?)");
    $result = $stmt->execute([$news_id, $author, $content]);
    
    if ($result) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add comment"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
