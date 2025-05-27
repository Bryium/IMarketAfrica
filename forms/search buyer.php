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

// Handle buyer search
if (isset($_GET['buyer_name'])) {
    $buyer_name = $_GET['buyer_name'];

    // Sanitize input
    $buyer_name = $conn->real_escape_string($buyer_name);

    // Search the buyers table
    $sql = "SELECT * FROM buyers WHERE name LIKE '%$buyer_name%' OR contact LIKE '%$buyer_name%'";
    $result = $conn->query($sql);

    echo "<h3>Search Results:</h3>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h4>" . $row['name'] . "</h4>";
            echo "<p>Contact: " . $row['contact'] . "</p>";
            echo "<p>Email: " . $row['email'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No buyers found.";
    }
}

$conn->close();
?>
u