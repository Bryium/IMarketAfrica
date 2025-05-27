<?php
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password (use your actual password)
$dbname = "transport_accounts"; // The database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accountType = $_POST['accountType'];
    
    if ($accountType == "Private") {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $dob = $_POST['dob'];
        $idNum = $_POST['idNum'];
        $country = $_POST['country'];
        $location = $_POST['location'];
        $mode = $_POST['mode'];
        $phone = $_POST['phone'];
        $carModel = $_POST['carModel'];
        $regNum = $_POST['regNum'];
        $carColor = $_POST['carColor'];
        $services = $_POST['services'];
        $cost = $_POST['cost'];

        $sql = "INSERT INTO private_accounts (first_name, last_name, dob, id_number, country, location, mode_of_transport, phone_number, car_model, reg_number, car_color, services_provided, cost_charges)
                VALUES ('$firstName', '$lastName', '$dob', '$idNum', '$country', '$location', '$mode', '$phone', '$carModel', '$regNum', '$carColor', '$services', '$cost')";
    } elseif ($accountType == "Corporate") {
        $corpName = $_POST['corpName'];
        $corpRegNum = $_POST['corpRegNum'];
        $corpCarModel = $_POST['corpCarModel'];
        $corpCarColor = $_POST['corpCarColor'];
        $corpPhone = $_POST['corpPhone'];
        $corpEmail = $_POST['corpEmail'];
        $corpCountry = $_POST['corpCountry'];
        $corpLocation = $_POST['corpLocation'];
        $corpServices = $_POST['corpServices'];
        $corpCost = $_POST['corpCost'];

        $sql = "INSERT INTO corporate_accounts (corporate_name, car_reg_number, car_model, car_color, corporate_phone, email, country, location, services_provided, cost_charges)
                VALUES ('$corpName', '$corpRegNum', '$corpCarModel', '$corpCarColor', '$corpPhone', '$corpEmail', '$corpCountry', '$corpLocation', '$corpServices', '$corpCost')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>
