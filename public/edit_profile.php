<?php
session_start();
include '../config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = (int) $_SESSION['user_id'];

// Ambil data user
$stmt = $conn->prepare("SELECT username, password FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    die("User tidak ditemukan.");
}
$user = $res->fetch_assoc();

$message = "";

if (isset($_POST['update'])) {
    $new_username = trim($_POST['username']);
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    // Ambil hash dari DB
    $db_hash = $user['password'];

    $old_ok = false;

    // 1) Cek password modern (password_hash)
    if (password_verify($old_password, $db_hash)) {
        $old_ok = true;
    } else {
        // 2) Cek legacy md5 (jika dulu pakai md5)
        if (hash_equals($db_hash, md5($old_password))) {
            $old_ok = true;
            // upgrade ke password_hash agar aman
            $rehash = password_hash($old_password, PASSWORD_DEFAULT);
            $upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $upd->bind_param('si', $rehash, $user_id);
            $upd->execute();
            $upd->close();
        }
    }

    if ($old_ok) {
        if (!empty($new_password)) {
            // set username + new password
            $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $u = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
            $u->bind_param('ssi', $new_username, $new_hashed, $user_id);
            $ok = $u->execute();
            $u->close();
        } else {
            // hanya update username
            $u = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $u->bind_param('si', $new_username, $user_id);
            $ok = $u->execute();
            $u->close();
        }

        if ($ok) {
            $_SESSION['username'] = $new_username;
            $message = "<p class='text-green-600'>✅ Profil berhasil diperbarui.</p>";
            // refresh $user array agar form menampilkan username baru
            $user['username'] = $new_username;
        } else {
            $message = "<p class='text-red-600'>❌ Gagal menyimpan perubahan.</p>";
        }

    } else {
        $message = "<p class='text-red-600'>❌ Password lama salah.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Profil</title>
<link rel="icon" href="../img/logo-ail.png">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 min-h-screen flex items-center justify-center">
  <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold mb-4 text-center">Edit Profil</h2>
    <?= $message; ?>
    <form method="POST" action="">
      <label class="block mb-2 font-semibold">Username Baru</label>
      <input type="text" name="username" class="w-full p-2 border rounded mb-4" value="<?= htmlspecialchars($user['username']) ?>" required>

      <label class="block mb-2 font-semibold">Password Lama</label>
      <input type="password" name="old_password" class="w-full p-2 border rounded mb-4" placeholder="Masukkan password lama" required>

      <label class="block mb-2 font-semibold">Password Baru (opsional)</label>
      <input type="password" name="new_password" class="w-full p-2 border rounded mb-6" placeholder="Kosongkan jika tidak ingin ganti">

      <button type="submit" name="update" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">Simpan Perubahan</button>
    </form>
    <div class="mt-4 text-center">
      <a href="dashboard.php" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
