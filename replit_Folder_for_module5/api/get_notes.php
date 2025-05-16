<?php
// Include database configuration
require_once 'config.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get query parameters for filtering
$college = isset($_GET['college']) ? $_GET['college'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build the query
$sql = "SELECT * FROM notes WHERE 1=1";

// Add filters if provided
if (!empty($college)) {
    $sql .= " AND college = '" . $conn->real_escape_string($college) . "'";
}

if (!empty($year)) {
    $sql .= " AND year = '" . $conn->real_escape_string($year) . "'";
}

if (!empty($semester)) {
    $sql .= " AND semester = '" . $conn->real_escape_string($semester) . "'";
}

if (!empty($type)) {
    $sql .= " AND type = '" . $conn->real_escape_string($type) . "'";
}

if (!empty($search)) {
    $sql .= " AND (subject_code LIKE '%" . $conn->real_escape_string($search) . "%' 
              OR title LIKE '%" . $conn->real_escape_string($search) . "%'
              OR description LIKE '%" . $conn->real_escape_string($search) . "%')";
}

// Order by most recent
$sql .= " ORDER BY upload_date DESC";

// Execute query
$result = $conn->query($sql);

// Check if query was successful
if ($result) {
    $notes = array();
    
    // Fetch all notes
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
    
    // Return notes as JSON
    echo json_encode(['status' => 'success', 'data' => $notes]);
} else {
    // Return error
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch notes: ' . $conn->error]);
}

// Close connection
$conn->close();
?>
