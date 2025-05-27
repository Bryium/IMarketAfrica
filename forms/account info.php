<?php
// Database connection
$servername = "localhost";
$username = "root";  // replace with your DB username
$password = "";      // replace with your DB password
$dbname = "sellers_products";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $seller_id = 1; // Assume seller is logged in, fetch their ID dynamically
    $transaction_type = $_POST['action'];
    $amount = $transaction_type == 'deposit' ? $_POST['deposit_amount'] : $_POST['withdrawal_amount'];

    // Insert the transaction into the transactions table
    $sql = "INSERT INTO transactions (seller_id, transaction_type, amount) VALUES ('$seller_id', '$transaction_type', '$amount')";

    if ($conn->query($sql) === TRUE) {
        echo "Transaction successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
