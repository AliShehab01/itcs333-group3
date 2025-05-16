<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'DatabaseHelper.php';

$db = new DatabaseHelper('localhost', 'mydb', 'user1', 'pass1');

// If the request is a GET (e.g. user wants to see all study groups)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $groups = $db->fetchGroups();
    echo json_encode($groups);
    exit;
}
   // payload is the data sent from the client to the server
// If the request is a POST (e.g., user wants to add a new group)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode(file_get_contents('php://input'), true);

    if (
        empty($payload['subjectCode']) ||
        empty($payload['section']) ||
        empty($payload['college']) ||
        empty($payload['wlink'])
    ) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
        exit;
    }

    $newId = $db->addGroup(
        $payload['subjectCode'],
        $payload['section'],
        $payload['college'],
        $payload['wlink']
    );

    if ($newId !== false) {
        http_response_code(201);
        echo json_encode(['success' => true, 'id' => $newId]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database insert failed.']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
