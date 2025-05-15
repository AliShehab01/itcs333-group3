<?php
require_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email) && !empty($data->password)) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT id, username, password FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $data->email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($data->password, $user['password'])) {
        http_response_code(200);
        echo json_encode([
            "message" => "Login successful.",
            "user_id" => $user['id'],
            "username" => $user['username']
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Login failed."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data."]);
}
?>
