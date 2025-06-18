<?php
require_once __DIR__ . '/config.php';   // handles session + constants

/* ---------- 1. CONNECT VIA PDO ---------- */
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    exit("DB connection failed: " . $e->getMessage());
}

/* ---------- 2. HANDLE POST ONLY ---------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '/');
    exit();
}

/* ---------- 3. ROUTE BASED ON accountType ---------- */
$accountType = $_POST['accountType'] ?? '';

try {
    if ($accountType === 'Private') {

        $sql = "INSERT INTO private_accounts
                (first_name, last_name, dob, id_number, country, location,
                 mode_of_transport, phone_number, car_model, reg_number,
                 car_color, services_provided, cost_charges)
                VALUES
                (:first_name, :last_name, :dob, :id_number, :country, :location,
                 :mode_of_transport, :phone_number, :car_model, :reg_number,
                 :car_color, :services_provided, :cost_charges)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':first_name'       => $_POST['firstName'] ?? '',
            ':last_name'        => $_POST['lastName'] ?? '',
            ':dob'              => $_POST['dob'] ?? '',
            ':id_number'        => $_POST['idNum'] ?? '',
            ':country'          => $_POST['country'] ?? '',
            ':location'         => $_POST['location'] ?? '',
            ':mode_of_transport'=> $_POST['mode'] ?? '',
            ':phone_number'     => $_POST['phone'] ?? '',
            ':car_model'        => $_POST['carModel'] ?? '',
            ':reg_number'       => $_POST['regNum'] ?? '',
            ':car_color'        => $_POST['carColor'] ?? '',
            ':services_provided'=> $_POST['services'] ?? '',
            ':cost_charges'     => $_POST['cost'] ?? 0
        ]);

    } elseif ($accountType === 'Corporate') {

        $sql = "INSERT INTO corporate_accounts
                (corporate_name, car_reg_number, car_model, car_color,
                 corporate_phone, email, country, location,
                 services_provided, cost_charges)
                VALUES
                (:corporate_name, :car_reg_number, :car_model, :car_color,
                 :corporate_phone, :email, :country, :location,
                 :services_provided, :cost_charges)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':corporate_name'   => $_POST['corpName'] ?? '',
            ':car_reg_number'   => $_POST['corpRegNum'] ?? '',
            ':car_model'        => $_POST['corpCarModel'] ?? '',
            ':car_color'        => $_POST['corpCarColor'] ?? '',
            ':corporate_phone'  => $_POST['corpPhone'] ?? '',
            ':email'            => $_POST['corpEmail'] ?? '',
            ':country'          => $_POST['corpCountry'] ?? '',
            ':location'         => $_POST['corpLocation'] ?? '',
            ':services_provided'=> $_POST['corpServices'] ?? '',
            ':cost_charges'     => $_POST['corpCost'] ?? 0
        ]);

    } else {
        throw new Exception('Invalid account type selected.');
    }

    // Success
    header('Location: view_accounts.php?success=1');
    exit();

} catch (Exception $e) {
    echo "Error saving data: " . $e->getMessage();
    exit();
}