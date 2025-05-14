<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'DatabaseHelper.php';

// Instantiate with your DB credentials
$db = new DatabaseHelper(
    getenv('DB_HOST') ?: 'localhost',
    getenv('DB_NAME') ?: 'mydb',
    getenv('DB_USER') ?: 'user1',
    getenv('DB_PASS') ?: 'pass1'
);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Fetch all study groups
        $groups = $db->fetchGroups();
        echo json_encode($groups);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Read raw JSON payload
        $payload = json_decode(file_get_contents('php://input'), true) ?: [];

        // Validate required fields
        if (empty($payload['subjectCode']) ||
            empty($payload['section']) ||
            empty($payload['college']) ||
            empty($payload['wlink'])
        ) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'error'   => 'Missing required fields.'
            ]);
            exit;
        }

        // Insert new group
        $newId = $db->addGroup(
            $payload['subjectCode'],
            $payload['section'],
            $payload['college'],
            $payload['wlink']
        );

        if ($newId !== false) {
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'id'      => $newId
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error'   => 'Database insert failed.'
            ]);
        }
        exit;
    }

    // Method not allowed
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error'   => 'Method not allowed.'
    ]);
} catch (Exception $e) {
    // Unexpected server error
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Server error: ' . $e->getMessage()
    ]);
}
