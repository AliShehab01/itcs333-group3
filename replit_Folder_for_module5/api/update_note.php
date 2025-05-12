<?php
// Include database configuration
require_once 'config.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Check if request method is PUT or POST
if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Only PUT or POST method is allowed']);
    exit;
}

// Get note data
$data = null;
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // For PUT requests, get data from php://input
    $data = json_decode(file_get_contents('php://input'), true);
} else {
    // For POST requests with _method=PUT
    $data = $_POST;
}

// Get note ID
$id = isset($data['id']) ? $data['id'] : null;

// Validate note ID
if (empty($id)) {
    echo json_encode(['status' => 'error', 'message' => 'Note ID is required']);
    exit;
}

// Build update query
$updateFields = [];
$params = [];
$types = "";

// Check which fields to update
if (isset($data['subject']) && !empty($data['subject'])) {
    $updateFields[] = "subject_code = ?";
    $params[] = $data['subject'];
    $types .= "s";
}

if (isset($data['college']) && !empty($data['college'])) {
    $updateFields[] = "college = ?";
    $params[] = $data['college'];
    $types .= "s";
}

if (isset($data['title']) && !empty($data['title'])) {
    $updateFields[] = "title = ?";
    $params[] = $data['title'];
    $types .= "s";
}

if (isset($data['type']) && !empty($data['type'])) {
    $updateFields[] = "type = ?";
    $params[] = $data['type'];
    $types .= "s";
}

if (isset($data['description'])) {
    $updateFields[] = "description = ?";
    $params[] = $data['description'];
    $types .= "s";
}

if (isset($data['semester'])) {
    $updateFields[] = "semester = ?";
    $params[] = $data['semester'];
    $types .= "s";
}

if (isset($data['year'])) {
    $updateFields[] = "year = ?";
    $params[] = $data['year'];
    $types .= "s";
}

// Check if there are fields to update
if (empty($updateFields)) {
    echo json_encode(['status' => 'error', 'message' => 'No fields to update']);
    exit;
}

// Add ID to params
$params[] = $id;
$types .= "i";

// Build SQL query
$sql = "UPDATE notes SET " . implode(", ", $updateFields) . " WHERE id = ?";

// Prepare and execute statement
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Note updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update note: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
