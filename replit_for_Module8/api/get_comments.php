<?php
header("Content-Type: application/json");
include '../database/db_connection.php';

$news_id = $_GET['news_id'] ?? null;
if (!$news_id) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT author, content FROM comments WHERE news_id = ? ORDER BY created_at DESC");
$stmt->execute([$news_id]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
