<?php
require_once __DIR__ . '/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                   DB_USER, DB_PASS,
                   [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    exit("DB connection failed: " . $e->getMessage());
}

$private = $pdo->query("SELECT * FROM private_accounts ORDER BY created_at DESC")->fetchAll();
$corporate = $pdo->query("SELECT * FROM corporate_accounts ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Transport Accounts Viewer</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    margin: 20px;
  }

  h2 {
    background: #007bff;
    color: #fff;
    padding: 6px 10px;
    border-radius: 5px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 40px;
  }

  th,
  td {
    border: 1px solid #ccc;
    padding: 8px;
  }

  th {
    background: #f2f2f2;
  }
  </style>
</head>

<body>

  <?php if (isset($_GET['success'])): ?>
  <p style="color:green;">Record saved successfully!</p>
  <?php endif; ?>

  <h2>Private Transport Accounts</h2>
  <?php if (!$private): ?>
  <p>No private accounts yet.</p>
  <?php else: ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>DOB</th>
      <th>Phone</th>
      <th>Car</th>
      <th>Services</th>
      <th>Cost</th>
      <th>Created</th>
    </tr>
    <?php foreach ($private as $p): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?></td>
      <td><?= $p['dob'] ?></td>
      <td><?= htmlspecialchars($p['phone_number']) ?></td>
      <td><?= htmlspecialchars($p['car_model']) ?> (<?= htmlspecialchars($p['reg_number']) ?>)</td>
      <td><?= nl2br(htmlspecialchars($p['services_provided'])) ?></td>
      <td><?= number_format($p['cost_charges'],2) ?></td>
      <td><?= $p['created_at'] ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
  <?php endif; ?>

  <h2>Corporate Transport Accounts</h2>
  <?php if (!$corporate): ?>
  <p>No corporate accounts yet.</p>
  <?php else: ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Corporate</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Car</th>
      <th>Services</th>
      <th>Cost</th>
      <th>Created</th>
    </tr>
    <?php foreach ($corporate as $c): ?>
    <tr>
      <td><?= $c['id'] ?></td>
      <td><?= htmlspecialchars($c['corporate_name']) ?></td>
      <td><?= htmlspecialchars($c['corporate_phone']) ?></td>
      <td><?= htmlspecialchars($c['email']) ?></td>
      <td><?= htmlspecialchars($c['car_model']) ?> (<?= htmlspecialchars($c['car_reg_number']) ?>)</td>
      <td><?= nl2br(htmlspecialchars($c['services_provided'])) ?></td>
      <td><?= number_format($c['cost_charges'],2) ?></td>
      <td><?= $c['created_at'] ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
  <?php endif; ?>
  <div style="text-align:center; margin-top: 30px;">
    <a href="../Transport account.html" style="text-decoration:none;">
      <button
        style="padding:10px 20px; font-size:16px; cursor:pointer; background:#007bff; color:#fff; border:none; border-radius:5px;">
        ← Back to Transport Account Form
      </button>
    </a>
  </div>

</body>

</html>