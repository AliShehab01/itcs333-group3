<?php
require_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->username) && !empty($data->email) && !empty($data->password)) {
    $database = new database();
    $db = $database->getConnection();

    $query = "INSERT INTO users SET username=:username, email=:email, password=:password";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":username", $data->username);
    $stmt->bindParam(":email", $data->email);
    $password_hash = password_hash($data->password, PASSWORD_BCRYPT);
    $stmt->bindParam(":password", $password_hash);

    if($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "User was registered."]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Unable to register the user."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data."]);
}
?>
