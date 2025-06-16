<?php
// ------------------------------
// SESSION CONFIGURATION
// ------------------------------
if (session_status() === PHP_SESSION_NONE) {
    // Set session ini settings BEFORE starting the session
    ini_set('session.cookie_lifetime', 3600);        
    ini_set('session.gc_maxlifetime', 3600);          

    ini_set('session.cookie_httponly', 1);            
    ini_set('session.cookie_secure', 0);              
    ini_set('session.use_strict_mode', 1);           

    session_start(); // Start session after ini_set()
}

// ------------------------------
// DATABASE CONFIGURATION
// ------------------------------
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'imarketafrica');

// ------------------------------
// MYSQLI CONNECTION
// ------------------------------
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');

    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }
} catch (Exception $e) {
    die('Database connection error: ' . $e->getMessage());
}

// ------------------------------
// PASSWORD HASHING CONFIGURATION
// ------------------------------
define('PASSWORD_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_OPTIONS', ['cost' => 12]);
?>