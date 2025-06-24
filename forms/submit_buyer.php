<?php
// IMarketAfricaSite/forms/submit_buyer.php
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO buyers (buyer_name, buyer_email, buyer_phone, product_category, search_term, deposit_amount, withdrawal_amount, transaction_history) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $_POST['buyer_name'],
        $_POST['buyer_email'],
        $_POST['buyer_phone'],
        $_POST['product_category'],
        $_POST['search_term'],
        $_POST['deposit_amount'] ?? 0,
        $_POST['withdrawal_amount'] ?? 0,
        $_POST['transaction_history'] ?? ''
    ]);

    header("Location: view_buyers.php?success=1");
    exit;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}