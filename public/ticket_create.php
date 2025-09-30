<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Buat Tiket Baru</title>
  <link rel="icon" href="../img/logo-ail.png">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center">
  <div class="max-w-xl w-full bg-white shadow-lg rounded-lg p-8 mt-10">
    <h1 class="text-3xl font-bold text-blue-600 mb-6 text-center">Buat Tiket Baru</h1>

    <form action="../actions/ticket_create_action.php" method="POST" enctype="multipart/form-data" class="space-y-4">
      <input type="text" name="title" placeholder="Judul Tiket" required class="w-full p-3 border rounded">
      <textarea name="description" placeholder="Deskripsi Masalah" required class="w-full p-3 border rounded"></textarea>

      <div>
        <label class="block mb-2 text-gray-700">Lampiran (opsional):</label>
        <input type="file" name="attachment" accept="image/*" class="w-full p-2 border rounded">
      </div>

      <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700 transition">Kirim Tiket</button>
    </form>

    <div class="text-center mt-4">
      <a href="dashboard.php" class="text-blue-500 hover:underline">← Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
