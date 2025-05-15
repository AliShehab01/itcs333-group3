<?php
// Include database configuration
require_once 'config.php';

// Get note ID
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Validate note ID
if (empty($id)) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Note ID is required']);
    exit;
}

// Get note information
$sql = "SELECT * FROM notes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $note = $result->fetch_assoc();
    
    // Update download count regardless of file existence
    $sql = "UPDATE notes SET download_count = download_count + 1 WHERE id = ?";
    $updateStmt = $conn->prepare($sql);
    $updateStmt->bind_param("i", $id);
    $updateStmt->execute();
    $updateStmt->close();
    
    // Check if this is a default 'no_file.txt' entry
    if ($note['file_path'] == 'no_file.txt') {
        // Return note details as JSON instead of a file download
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success', 
            'message' => 'Note details retrieved successfully',
            'data' => [
                'subject_code' => $note['subject_code'],
                'title' => $note['title'],
                'college' => $note['college'],
                'type' => $note['type'],
                'description' => $note['description'],
                'semester' => $note['semester'],
                'year' => $note['year']
            ]
        ]);
        exit;
    }
    
    // If not a default entry, try to find the actual file
    $filePath = '../uploads/' . $note['file_path'];
    if (file_exists($filePath)) {
        // Set headers for download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($note['file_path']) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        
        // Clear output buffer
        ob_clean();
        flush();
        
        // Read file and output
        readfile($filePath);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'File not found, but note details exist in database']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Note not found']);
}

$stmt->close();
$conn->close();
?>
