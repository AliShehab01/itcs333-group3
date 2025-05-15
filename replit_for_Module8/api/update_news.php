<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id, $data->title, $data->body)) {
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

$id    = (int)$data->id;
$title = $conn->real_escape_string($data->title);
$body  = $conn->real_escape_string($data->body);

$sql = "UPDATE news SET title='$title', body='$body' WHERE id=$id";

if ($conn->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $conn->error]);
}
?>
