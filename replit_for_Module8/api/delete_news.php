<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id)) {
    echo json_encode(['error' => 'Missing ID']);
    exit;
}

$id = (int)$data->id;

$sql = "DELETE FROM news WHERE id = $id";

if ($conn->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $conn->error]);
}
?>
