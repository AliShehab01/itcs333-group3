<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Review ID is required']);
        exit;
    }

    $checkStmt = $conn->prepare("SELECT id FROM course_reviews WHERE id = :id");
    $checkStmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
    $checkStmt->execute();

    if ($checkStmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Review not found']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM course_reviews WHERE id = :id");
    $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['message' => 'Review deleted successfully', 'id' => $data['id']]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
