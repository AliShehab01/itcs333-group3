<?php
// Include database configuration
require_once 'config.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Check if request method is DELETE or POST
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Only DELETE or POST method is allowed']);
    exit;
}

// Get note ID
$id = null;
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // For DELETE requests, get data from php://input
    $data = json_decode(file_get_contents('php://input'), true);
    $id = isset($data['id']) ? $data['id'] : null;
} else {
    // For POST requests with _method=DELETE
    $id = isset($_POST['id']) ? $_POST['id'] : null;
}

// Validate note ID
if (empty($id)) {
    echo json_encode(['status' => 'error', 'message' => 'Note ID is required']);
    exit;
}

// Check if note exists
$sql = "SELECT id FROM notes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Delete note from database
    $sql = "DELETE FROM notes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Note deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete note: ' . $stmt->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Note not found']);
}

$stmt->close();
$conn->close();
?>
