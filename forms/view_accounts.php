<?php
require_once __DIR__ . '/config.php';  

/* ---------- CONNECT ---------- */
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    exit("DB connection failed: " . $e->getMessage());
}

/* ---------- FETCH ---------- */
$private   = $pdo->query("SELECT * FROM private_accounts   ORDER BY created_at DESC")->fetchAll();
$corporate = $pdo->query("SELECT * FROM corporate_accounts ORDER BY created_at DESC")->fetchAll();

/* ---------- SMALL HELPER ---------- */
function relPhoto(string $dbPath=null): ?string
{
    if (!$dbPath) return null;
    // Remove leading slash or “forms/” if present, then prepend “uploads/”
    $clean = ltrim($dbPath, '/');           // strip leading “/”
    $clean = preg_replace('#^forms/#', '', $clean); // strip leading “forms/”
    return 'uploads/' . basename($clean);    // relative to forms/
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Accounts Viewer</title>
  <style>
  body {
    font-family: Arial;
    margin: 20px
  }

  h2 {
    background: #007bff;
    color: #fff;
    padding: 6px 10px;
    border-radius: 5px
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 40px
  }

  th,
  td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
    vertical-align: middle
  }

  th {
    background: #f2f2f2
  }

  img.vehicle {
    width: 100px;
    border-radius: 5px
  }

  .btn {
    text-align: center;
    margin-top: 30px
  }

  .btn button {
    padding: 10px 20px;
    font-size: 16px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer
  }
  </style>
</head>

<body>

  <?php if(isset($_GET['success'])): ?>
  <p style="color:green;">Record saved successfully!</p>
  <?php endif; ?>

  <!-- ========= PRIVATE ========== -->
  <h2>Private Transport Accounts</h2>
  <?php if(!$private): ?>
  <p>No private accounts yet.</p>
  <?php else: ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>DOB</th>
      <th>Phone</th>
      <th>Car</th>
      <th>Picture</th>
      <th>Services</th>
      <th>Cost</th>
      <th>Created</th>
    </tr>
    <?php foreach($private as $p): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= htmlspecialchars($p['first_name'].' '.$p['last_name']) ?></td>
      <td><?= $p['dob'] ?></td>
      <td><?= htmlspecialchars($p['phone_number']) ?></td>
      <td><?= htmlspecialchars($p['car_model']) ?> (<?= htmlspecialchars($p['reg_number']) ?>)</td>
      <td>
        <?php if($src = relPhoto($p['vehicle_photo'])): ?>
        <img class="vehicle" src="<?= htmlspecialchars($src) ?>" alt="Vehicle">
        <?php else: ?>No Photo<?php endif; ?>
      </td>
      <td><?= nl2br(htmlspecialchars($p['services_provided'])) ?></td>
      <td><?= number_format($p['cost_charges'],2) ?></td>
      <td><?= $p['created_at'] ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
  <?php endif; ?>

  <!-- ========= CORPORATE ========== -->
  <h2>Corporate Transport Accounts</h2>
  <?php if(!$corporate): ?>
  <p>No corporate accounts yet.</p>
  <?php else: ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Corporate</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Car</th>
      <th>Picture</th>
      <th>Services</th>
      <th>Cost</th>
      <th>Created</th>
    </tr>
    <?php foreach($corporate as $c): ?>
    <tr>
      <td><?= $c['id'] ?></td>
      <td><?= htmlspecialchars($c['corporate_name']) ?></td>
      <td><?= htmlspecialchars($c['corporate_phone']) ?></td>
      <td><?= htmlspecialchars($c['email']) ?></td>
      <td><?= htmlspecialchars($c['car_model']) ?> (<?= htmlspecialchars($c['car_reg_number']) ?>)</td>
      <td>
        <?php if($src = relPhoto($c['vehicle_photo'])): ?>
        <img class="vehicle" src="<?= htmlspecialchars($src) ?>" alt="Vehicle">
        <?php else: ?>No Photo<?php endif; ?>
      </td>
      <td><?= nl2br(htmlspecialchars($c['services_provided'])) ?></td>
      <td><?= number_format($c['cost_charges'],2) ?></td>
      <td><?= $c['created_at'] ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
  <?php endif; ?>

  <!-- ========= BACK BUTTON ========== -->
  <div class="btn">
    <a href="../Transport Account.html"><button>← Back to Transport Account Form</button></a>
  </div>

</body>

</html>