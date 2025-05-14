
<?php
header("Content-Type: application/json");
require_once 'config.php';

try {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    $stmt = $conn->prepare("SELECT * FROM news WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $news = $stmt->fetch();
    
    if ($news) {
        echo json_encode($news);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'News not found']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
