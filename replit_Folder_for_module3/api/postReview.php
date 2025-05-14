<?php
require_once '../replit_Folder_for_module3/config.php';

// Handle only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        $data = $_POST;
    }

    if (empty($data['courseName']) || empty($data['professorName']) || 
        empty($data['rating']) || empty($data['semester']) || 
        empty($data['reviewText'])) {

        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $sql = "INSERT INTO course_reviews (courseName, professorName, rating, semester, reviewText) 
            VALUES (:courseName, :professorName, :rating, :semester, :reviewText)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':courseName', $data['courseName'], PDO::PARAM_STR);
    $stmt->bindParam(':professorName', $data['professorName'], PDO::PARAM_STR);
    $stmt->bindParam(':rating', $data['rating'], PDO::PARAM_INT);
    $stmt->bindParam(':semester', $data['semester'], PDO::PARAM_STR);
    $stmt->bindParam(':reviewText', $data['reviewText'], PDO::PARAM_STR);

    $stmt->execute();

    $reviewId = $conn->lastInsertId();
    $data['id'] = (int)$reviewId;
    $data['created_at'] = date('Y-m-d H:i:s');

    http_response_code(201);
    echo json_encode($data);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
