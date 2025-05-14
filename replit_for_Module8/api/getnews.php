<?php
include 'config.php';

$sql = "SELECT * FROM news ORDER BY created_at DESC";
$result = $conn->query($sql);

$news = [];

while ($row = $result->fetch_assoc()) {
    $news[] = $row;
}

echo json_encode($news);
?>
