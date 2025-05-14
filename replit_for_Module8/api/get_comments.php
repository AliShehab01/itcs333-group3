<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include 'config.php';

$news_id = $_GET['news_id'] ?? null;

if (!$news_id) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, author, content, created_at FROM comments WHERE news_id = ? ORDER BY created_at DESC");
    $stmt->execute([$news_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($comments);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
