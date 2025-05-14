<?php
header("Content-Type: application/json");
include '../database/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$news_id = $data["news_id"] ?? null;
$author = $data["author"] ?? "Anonymous";
$content = $data["content"] ?? "";

if ($news_id && $content) {
    $stmt = $conn->prepare("INSERT INTO comments (news_id, author, content) VALUES (?, ?, ?)");
    $stmt->execute([$news_id, $author, $content]);
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
}
?>
