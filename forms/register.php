<?php
header("Content-Type: application/json");
include "config.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

function jsonResponse($status, $message) {
    echo json_encode([
        "success" => $status,
        "message" => $message
    ]);
    exit();
}

if (strpos($requestUri, "check-email.php") !== false && $requestMethod === "POST") {
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

if (strpos($requestUri, "register.php") !== false && $requestMethod === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $username = trim($data["username"] ?? "");
    $email = trim($data["email"] ?? "");
    $password = $data["password"] ?? "";
    $confirmPassword = $data["confirm_password"] ?? "";

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        jsonResponse(false, "All fields are required.");
    }

    if ($password !== $confirmPassword) {
        jsonResponse(false, "Passwords do not match.");
    }

    if (strlen($password) < 6) {
        jsonResponse(false, "Password must be at least 6 characters.");
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        jsonResponse(false, "Email already exists.");
    }

    $stmt->close();

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        jsonResponse(true, "Registration successful.");
    } else {
        jsonResponse(false, "Registration failed. Please try again.");
    }

    $stmt->close();
    $conn->close();
}

http_response_code(404);
jsonResponse(false, "Endpoint not found.");