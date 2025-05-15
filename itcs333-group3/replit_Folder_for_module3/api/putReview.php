<?php
require_once '../config.php';

// Handle only PUT requests
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

    // Update the review
    $fields = [];
    $params = [':id' => $data['id']];

    if (isset($data['courseName'])) {
        $fields[] = "courseName = :courseName";
        $params[':courseName'] = $data['courseName'];
    }

    if (isset($data['professorName'])) {
        $fields[] = "professorName = :professorName";
        $params[':professorName'] = $data['professorName'];
    }

    if (isset($data['rating'])) {
        $fields[] = "rating = :rating";
        $params[':rating'] = $data['rating'];
    }

    if (isset($data['semester'])) {
        $fields[] = "semester = :semester";
        $params[':semester'] = $data['semester'];
    }

    if (isset($data['reviewText'])) {
        $fields[] = "reviewText = :reviewText";
        $params[':reviewText'] = $data['reviewText'];
    }

    if (empty($fields)) {
        http_response_code(400);
        echo json_encode(['error' => 'No fields to update']);
        exit;
    }

    $sql = "UPDATE course_reviews SET " . implode(", ", $fields) . " WHERE id = :id";
    $stmt = $conn->prepare($sql);

    foreach ($params as $key => $value) {
        $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($key, $value, $paramType);
    }

    $stmt->execute();

    // Get the updated review
    $getStmt = $conn->prepare("SELECT * FROM course_reviews WHERE id = :id");
    $getStmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
    $getStmt->execute();
    $updatedReview = $getStmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($updatedReview);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
