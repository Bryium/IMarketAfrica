<?php
session_start();

// Optional: Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php'); 
    exit();
}

$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Buyer's Account</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

  <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-md">
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Buyer’s Account Info</h2>

    <table class="w-full text-sm text-left text-gray-700">
      <tbody>
        <tr class="border-b">
          <th scope="row" class="py-3 font-medium text-gray-900">Username</th>
          <td class="py-3"><?= htmlspecialchars($username) ?></td>
        </tr>
        <tr class="border-b">
          <th scope="row" class="py-3 font-medium text-gray-900">Email</th>
          <td class="py-3"><?= htmlspecialchars($email) ?></td>
        </tr>
      </tbody>
    </table>

    <div class="mt-6 text-center">
      <a href="index_old.html" class="text-blue-600 hover:underline text-sm">← Back to Home</a>
    </div>

</body>

</html>