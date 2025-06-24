<?php
// IMarketAfricaSite/forms/view_buyers.php
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $buyers = $pdo->query("SELECT * FROM buyers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    exit("DB error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Buyer Records</title>
</head>

<body>
  <h2>Saved Buyer Records</h2>
  <?php if (!$buyers): ?>
  <p>No data available.</p>
  <?php else: ?>
  <table border="1" cellpadding="6">
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Category</th>
      <th>Search</th>
      <th>Deposit</th>
      <th>Withdraw</th>
    </tr>
    <?php foreach ($buyers as $b): ?>
    <tr>
      <td><?= htmlspecialchars($b['buyer_name']) ?></td>
      <td><?= htmlspecialchars($b['buyer_email']) ?></td>
      <td><?= htmlspecialchars($b['buyer_phone']) ?></td>
      <td><?= htmlspecialchars($b['product_category']) ?></td>
      <td><?= htmlspecialchars($b['search_term']) ?></td>
      <td><?= $b['deposit_amount'] ?></td>
      <td><?= $b['withdrawal_amount'] ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
  <?php endif; ?>

  <br>
  <a href="../Buyers Account.html"><button>← Back to Form</button></a>
</body>

</html>