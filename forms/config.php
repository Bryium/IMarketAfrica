<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'ayysxvrg_dev');
define('DB_PASS', 'Market@2025');
define('DB_NAME', 'ayysxvrg_imarketafrica');

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');

    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }
} catch (Exception $e) {
    die('Database connection error: ' . $e->getMessage());
}


define('PASSWORD_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_OPTIONS', ['cost' => 12]);
?>