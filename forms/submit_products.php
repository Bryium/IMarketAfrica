<?php
session_start();
require_once __DIR__ . '/config.php';


/* ---------- 1. CONNECT USING PDO (safer) ---------- */
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    exit("DB connection failed: " . $e->getMessage());
}

/* ---------- 2. HANDLE ONLY POST ---------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: Sellers Account.html');   
    exit();
}

/* ---------- 3. SANITISE SIMPLE FIELDS ---------- */
$product_name = trim($_POST['product_name'] ?? '');
$price        = trim($_POST['price']        ?? '');
$description  = trim($_POST['description']  ?? '');
$seller_name  = trim($_POST['seller_name']  ?? '');
$contact      = trim($_POST['contact']      ?? '');
$location     = trim($_POST['location']     ?? '');

/* ---------- 4. VALIDATE ---------- */
$errors = [];
if ($product_name === '') $errors[] = 'Product name is required.';
if ($price === '' || !is_numeric($price) || $price < 0) $errors[] = 'Valid price required.';
if ($description === '') $errors[] = 'Description required.';

/* ---------- 5. HANDLE FILE UPLOAD ---------- */
if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    $errors[] = 'Photo upload failed.';
}

if ($errors) {
    echo implode('<br>', $errors);
    exit();
}

$allowed = ['jpg','jpeg','png','gif','webp'];
$ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed)) {
    exit('Unsupported image format.');
}

$uploadsDir = __DIR__ . '/uploads';
if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);

$newFileName = uniqid('prod_', true) . '.' . $ext;
$destPath = $uploadsDir . '/' . $newFileName;

if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destPath)) {
    exit('Could not save uploaded file.');
}

/* ---------- 6. INSERT WITH PREPARED STATEMENT ---------- */
$sql = "INSERT INTO products
        (product_name, price, description, photo, seller_name, contact, location)
        VALUES (:product_name, :price, :description, :photo, :seller_name, :contact, :location)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':product_name' => $product_name,
    ':price'        => $price,
    ':description'  => $description,
    ':photo'        => 'uploads/' . $newFileName, 
    ':seller_name'  => $seller_name,
    ':contact'      => $contact,
    ':location'     => $location,
]);

/* ---------- 7. REDIRECT OR CONFIRM ---------- */
echo "Product added successfully!";
echo ' <a href="view_products.php">View all products</a>';