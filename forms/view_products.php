<?php
require_once __DIR__ . '/config.php'; 

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
                   DB_USER, DB_PASS,
                   [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) { exit($e->getMessage()); }

$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Products</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
  <div class="max-w-5xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Product Listings</h1>

    <?php if (!$products): ?>
    <p>No products found.</p>
    <?php else: ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($products as $p): ?>
      <div class="bg-white rounded-xl shadow p-4 flex flex-col">
        <img src="<?= htmlspecialchars($p['photo']) ?>" alt="" class="rounded w-full h-48 object-cover mb-4">
        <h3 class="text-xl font-semibold"><?= htmlspecialchars($p['product_name']) ?></h3>
        <p class="text-green-700 font-medium mb-2">$<?= number_format($p['price'],2) ?></p>
        <p class="text-gray-600 mb-2"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
        <p class="text-sm">Seller: <strong><?= htmlspecialchars($p['seller_name']) ?></strong></p>
        <p class="text-sm mb-2">Contact: <?= htmlspecialchars($p['contact']) ?></p>
        <a href="<?= htmlspecialchars($p['location']) ?>" class="text-blue-600 underline text-sm mt-auto"
          target="_blank">View location</a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</body>

</html>