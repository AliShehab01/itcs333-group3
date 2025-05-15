<?php
// Include database configuration
require_once 'config.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Only POST method is allowed']);
    exit;
}

// Get form data
$subject_code = isset($_POST['subject']) ? $_POST['subject'] : '';
$college = isset($_POST['college']) ? $_POST['college'] : '';
$title = isset($_POST['title']) ? $_POST['title'] : '';
$type = isset($_POST['type']) ? $_POST['type'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$year = isset($_POST['year']) ? $_POST['year'] : '';

// Validate required fields
if (empty($subject_code) || empty($college) || empty($title) || empty($type)) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields are missing']);
    exit;
}

// Set a default file path since we're not requiring file upload
$fileName = 'no_file.txt';

// Insert note into database
$sql = "INSERT INTO notes (subject_code, college, title, type, description, file_path, semester, year) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $subject_code, $college, $title, $type, $description, $fileName, $semester, $year);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Note added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add note: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
