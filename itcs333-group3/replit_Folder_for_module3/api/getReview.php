<?php
require_once '../config.php';

// Handle only GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $sql = "SELECT * FROM course_reviews ORDER BY created_at DESC";
    $stmt = null;

    if (isset($_GET['course'])) {
        $sql = "SELECT * FROM course_reviews WHERE courseName LIKE :course ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':course', '%' . $_GET['course'] . '%', PDO::PARAM_STR);
    } else if (isset($_GET['professor'])) {
        $sql = "SELECT * FROM course_reviews WHERE professorName LIKE :professor ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':professor', '%' . $_GET['professor'] . '%', PDO::PARAM_STR);
    } else {
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($reviews);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
