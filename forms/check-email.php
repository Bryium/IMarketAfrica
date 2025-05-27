<?php
header("Content-Type: application/json");
include "config.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $email = trim($input["email"] ?? "");

    if (empty($email)) {
        echo json_encode(["exists" => false]);
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    echo json_encode(["exists" => $stmt->num_rows > 0]);
    $stmt->close();
    $conn->close();
    exit();
}

http_response_code(404);
echo json_encode(["exists" => false]);