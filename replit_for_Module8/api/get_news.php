<?php
header("Content-Type: application/json");
require_once 'config.php';

try {
    $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";
    $category = isset($_GET['category']) && $_GET['category'] !== '' ? $_GET['category'] : null;

    $sql = "SELECT id, title, summary, content, image_url, category, published_at FROM news WHERE title LIKE :search";
    $params = [':search' => $search];

    if ($category) {
        $sql .= " AND category = :category";
        $params[':category'] = $category;
    }

    $sql .= " ORDER BY published_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $news = $stmt->fetchAll();

    echo json_encode($news);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
