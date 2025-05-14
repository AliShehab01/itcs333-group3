<?php
header("Content-Type: application/json");
include '../database/db_connection.php';

$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";
$category = isset($_GET['category']) ? $_GET['category'] : null;

$sql = "SELECT id, title, summary, image_url, category, published_at FROM news WHERE title LIKE ?";
$params = [$search];

if ($category) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$sql .= " ORDER BY published_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($news);
?>
