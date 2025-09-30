<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register User</title>
  <link rel="icon" href="../img/logo-ail.png">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-100 flex items-center justify-center h-screen">
  <form action="../actions/register_action.php" method="POST" class="bg-white p-8 rounded-lg shadow-md w-96">
    <div class="flex justify-center mb-4">
      <img src="../img/logo-ail.png" alt="Logo" class="w-20 h-20">
    </div>
    <h1 class="text-2xl font-bold text-blue-500 mb-6 text-center">Register User</h1>
    <input type="text" name="username" placeholder="Username" required class="w-full mb-4 p-2 border rounded">
    <input type="password" name="password" placeholder="Password" required class="w-full mb-4 p-2 border rounded">
    <button class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Daftar</button>
    <div class="text-center mt-4">
      <a href="./" class="text-blue-600 underline">← Kembali ke Login</a>
    </div>
  </form>
</body>
</html>
