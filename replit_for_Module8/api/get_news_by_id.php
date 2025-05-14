<?php
header("Content-Type: application/json");
include '../database/db_connection.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(["error" => "No ID provided"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($news);
?>
