<?php
session_start();
if (isset($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Helpdesk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <link rel="icon" href="../img/logo-ail.png">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-100 flex items-center justify-center min-h-screen px-4">
  <form action="../actions/login_action.php" method="POST" 
        class="bg-white w-full max-w-sm sm:max-w-md p-6 sm:p-8 rounded-lg shadow-md">
        
    <div class="flex justify-center mb-4">
      <img src="../img/logo-ail.png" alt="Logo" class="w-16 h-16 sm:w-20 sm:h-20">
    </div>
    <h1 class="text-2xl font-bold text-blue-500 mb-6 text-center">Helpdesk Login</h1>
    <input type="text" name="username" placeholder="Username" required 
           class="w-full mb-4 p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
    <input type="password" name="password" placeholder="Password" required 
           class="w-full mb-4 p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
    <button type="submit" 
            class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition duration-200">
      Login
    </button>
    <div class="text-center mt-4">
      <a href="register.php" class="text-blue-600 underline hover:text-blue-800">Register</a>
    </div>
    <?php if(isset($_GET['error'])): ?>
      <p class="text-red-500 text-center mt-3"><?php echo $_GET['error']; ?></p>
    <?php endif; ?>
  </form>
</body>
</html>
