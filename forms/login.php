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

// Only handle POST requests to login.php
if (strpos($requestUri, "login.php") !== false && $requestMethod === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $email = trim($data["email"] ?? "");
    $password = $data["password"] ?? "";

    // Validate inputs
    if (empty($email) || empty($password)) {
        jsonResponse(false, "Email and password are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(false, "Invalid email format.");
    }

    // Prepare statement to fetch user by email
    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ? LIMIT 1");
    if (!$stmt) {
        jsonResponse(false, "Database error: failed to prepare statement.");
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user["password"])) {
            session_start();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"] = $user["email"];

            jsonResponse(true, "Login successful.");
        } else {
            jsonResponse(false, "Invalid email or password.");
        }
    } else {
        jsonResponse(false, "Invalid email or password.");
    }

    $stmt->close();
    $conn->close();
}

http_response_code(404);
jsonResponse(false, "Endpoint not found.");