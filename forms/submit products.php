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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $seller_name = $_POST['seller_name'];
    $contact = $_POST['contact'];
    $location = $_POST['location'];

    // Handle file upload
    $photo = $_FILES['photo']['name'];
    $photo_temp = $_FILES['photo']['tmp_name'];
    $photo_path = "uploads/" . basename($photo);

    // Move the uploaded file to the "uploads" directory
    move_uploaded_file($photo_temp, $photo_path);

    // Insert data into the database
    $sql = "INSERT INTO products (product_name, price, description, photo, seller_name, contact, location) 
            VALUES ('$product_name', '$price', '$description', '$photo_path', '$seller_name', '$contact', '$location')";

    if ($conn->query($sql) === TRUE) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch and display all products
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div id='product-list'>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<h3>" . $row['product_name'] . "</h3>";
        echo "<p>Price: $" . $row['price'] . "</p>";
        echo "<p>Description: " . $row['description'] . "</p>";
        echo "<img src='" . $row['photo'] . "' alt='Product Photo' width='200'>";
        echo "<p>Seller: " . $row['seller_name'] . "</p>";
        echo "<p>Contact: " . $row['contact'] . "</p>";
        echo "<p>Location: <a href='" . $row['location'] . "' target='_blank'>" . $row['location'] . "</a></p>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "No products found.";
}

$conn->close();
?>
