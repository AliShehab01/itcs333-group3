<?php
header("Content-Type: application/json");
include("db_config.php"); // Assuming this handles database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $title = $_POST['title'];
    $price = $_POST['price'];

    // Updated condition check
    if (
        !empty($_POST['code']) &&
        !empty($_POST['title']) &&
        !empty($_POST['price']) &&
        !empty($_POST['book_condition']) &&
        is_numeric($_POST['price'])
    ) {
        $sql = "UPDATE books
                SET code=:code, title=:title, price=:price, book_condition=:book_condition,
                WHERE id=:id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":code", $_POST['code']);
        $stmt->bindParam(":title", $_POST['title']);
        $stmt->bindParam(":price", $_POST['price']);
        $stmt->bindParam(":book_condition", $_POST['book_condition']);


        if ($stmt->execute()) {
            echo json_encode(array("message" => "Book was updated."));
        } else {
            echo json_encode(array("message" => "Unable to update book."));
        }
    } else {
        echo json_encode(array("message" => "Unable to update book. Data is incomplete."));
    }
} else {
    echo json_encode(array("message" => "Wrong request method."));
}
?>