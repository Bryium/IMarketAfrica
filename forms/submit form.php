<?php
require_once __DIR__ . '/config.php';       

/* ---------- CONNECT ---------- */
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    exit("DB connection failed: " . $e->getMessage());
}

/* ---------- POST ONLY ---------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
    exit();
}

/* ---------- UPLOAD HELPER ---------- */
function uploadVehiclePhoto(string $input): ?string
{
    // 1. Nothing uploaded?
    if (empty($_FILES[$input]['name'])) {
        return null;
    }

    // 2. Validate extension
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext     = strtolower(pathinfo($_FILES[$input]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        return null;
    }

    // 3. Destination folder  (/forms/uploads)
    $dir = __DIR__ . '/uploads';           // __DIR__ already = .../forms
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    // 4. Unique file name
    $newName = uniqid('veh_', true) . '.' . $ext;
    $dest    = $dir . '/' . $newName;

    // 5. Move file
    if (move_uploaded_file($_FILES[$input]['tmp_name'], $dest)) {
        // ✅ Save path WITHOUT leading dot and WITH slash
        return 'uploads/' . $newName;      // what you store in DB
    }

    return null;                           // upload failed
}


/* ---------- ROUTE BY TYPE ---------- */
$type = $_POST['accountType'] ?? '';

try {
    if ($type === 'Private') {

        $photo = uploadVehiclePhoto('privateVehiclePhoto');

        $sql = "INSERT INTO private_accounts
                (first_name,last_name,dob,id_number,country,location,
                 mode_of_transport,phone_number,car_model,reg_number,
                 car_color,services_provided,cost_charges,vehicle_photo)
                VALUES
                (:fn,:ln,:dob,:id,:cty,:loc,:mode,:phone,:model,:reg,
                 :color,:svc,:cost,:photo)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':fn'    => $_POST['firstName']  ?? '',
            ':ln'    => $_POST['lastName']   ?? '',
            ':dob'   => $_POST['dob']        ?? '',
            ':id'    => $_POST['idNum']      ?? '',
            ':cty'   => $_POST['country']    ?? '',
            ':loc'   => $_POST['location']   ?? '',
            ':mode'  => $_POST['mode']       ?? '',
            ':phone' => $_POST['phone']      ?? '',
            ':model' => $_POST['carModel']   ?? '',
            ':reg'   => $_POST['regNum']     ?? '',
            ':color' => $_POST['carColor']   ?? '',
            ':svc'   => $_POST['services']   ?? '',
            ':cost'  => $_POST['cost']       ?? 0,
            ':photo' => $photo
        ]);

    } elseif ($type === 'Corporate') {

        $photo = uploadVehiclePhoto('corporateVehiclePhoto');

        $sql = "INSERT INTO corporate_accounts
                (corporate_name,car_reg_number,car_model,car_color,
                 corporate_phone,email,country,location,
                 services_provided,cost_charges,vehicle_photo)
                VALUES
                (:name,:reg,:model,:color,:phone,:email,
                 :cty,:loc,:svc,:cost,:photo)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'  => $_POST['corpName']      ?? '',
            ':reg'   => $_POST['corpRegNum']    ?? '',
            ':model' => $_POST['corpCarModel']  ?? '',
            ':color' => $_POST['corpCarColor']  ?? '',
            ':phone' => $_POST['corpPhone']     ?? '',
            ':email' => $_POST['corpEmail']     ?? '',
            ':cty'   => $_POST['corpCountry']   ?? '',
            ':loc'   => $_POST['corpLocation']  ?? '',
            ':svc'   => $_POST['corpServices']  ?? '',
            ':cost'  => $_POST['corpCost']      ?? 0,
            ':photo' => $photo
        ]);

    } else {
        throw new Exception('Invalid account type.');
    }

    header('Location: view_accounts.php?success=1');
    exit();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}