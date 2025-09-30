<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: dashboard.php");
  exit();
}
include '../config/database.php';
$users = mysqli_query($conn, "SELECT * FROM users WHERE role='user'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password User</title>
  <link rel="icon" href="../img/logo-ail.png">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-50 p-8">
  <h1 class="text-2xl font-bold text-blue-700 mb-4">Reset Password User</h1>
  <table class="w-full bg-white rounded shadow">
    <tr class="bg-blue-200 text-blue-800">
      <th class="p-2">Username</th>
      <th class="p-2">Aksi</th>
    </tr>
    <?php while($u = mysqli_fetch_assoc($users)): ?>
      <tr class="border-b">
        <td class="p-2"><?= $u['username'] ?></td>
        <td class="p-2">
          <a href="../actions/reset_user_action.php?id=<?= $u['id'] ?>" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Reset Password</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
  <div class="mt-4">
    <a href="dashboard.php" class="text-blue-600 underline">← Kembali</a>
  </div>
</body>
</html>
