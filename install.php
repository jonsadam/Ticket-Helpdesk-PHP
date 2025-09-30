<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

if (!$conn) {
    die("❌ Gagal terhubung ke database. Periksa config/database.php");
}

$success = false;
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
       
        $conn->query("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin','user') DEFAULT 'user',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;
        ");

       
        $conn->query("
            CREATE TABLE IF NOT EXISTS tickets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ticket_number VARCHAR(50) UNIQUE,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                status ENUM('open','in-progress','closed') DEFAULT 'open',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB;
        ");

        
        $conn->query("
            CREATE TABLE IF NOT EXISTS replies (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ticket_id INT NOT NULL,
                user_id INT NOT NULL,
                message TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB;
        ");

       
        $conn->query("
            CREATE TABLE IF NOT EXISTS notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                actor_id INT NOT NULL,
                ticket_id INT,
                type ENUM('status','reply') NOT NULL,
                message VARCHAR(255),
                is_read TINYINT(1) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (actor_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE
            ) ENGINE=InnoDB;
        ");

        $checkAdmin = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='admin'");
        $adminExists = $checkAdmin->fetch_assoc()['total'];

        if ($adminExists == 0) {
            $defaultPassword = password_hash("admin123", PASSWORD_BCRYPT);
            $conn->query("
                INSERT INTO users (username, password, role) 
                VALUES ('admin', '{$defaultPassword}', 'admin')
            ");
        }

        $success = true;
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
    if ($success) {
        file_put_contents(__DIR__ . '/.installed', date('Y-m-d H:i:s'));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Installer Helpdesk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white w-full max-w-lg p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4 text-center text-blue-600">Instalasi Database Helpdesk</h1>

    <?php if ($success): ?>
      <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
        Instalasi berhasil! Semua tabel sudah dibuat.<br>
        Akun admin default: <strong>admin</strong><br>
        Password: <strong>admin123</strong>
      </div>
      <div class="text-center">
        <a href="public/" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ke Halaman Login</a>
      </div>
    <?php else: ?>
      <?php if ($errorMsg): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
          Terjadi kesalahan: <?= htmlspecialchars($errorMsg) ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-4">
        <p class="text-gray-600 text-center">Klik tombol di bawah untuk memulai instalasi database.</p>
        <div class="text-center">
          <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
            Jalankan Instalasi
          </button>
        </div>
      </form>
    <?php endif; ?>
  </div>

</body>
</html>
