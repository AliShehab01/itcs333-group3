<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->title, $data->body)) {
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

$title = $conn->real_escape_string($data->title);
$body  = $conn->real_escape_string($data->body);

$sql = "INSERT INTO news (title, body, created_at) VALUES ('$title', '$body', NOW())";

if ($conn->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $conn->error]);
}
?>
