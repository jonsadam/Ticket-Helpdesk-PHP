<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../public/index.php");
    exit;
}

$userInput = trim($_POST['username']);
$password = $_POST['password'];

// Ambil user berdasar username (atau email)
$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $userInput);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    header("Location: ../public/index.php?error=" . urlencode('User tidak ditemukan'));
    exit();
}
$user = $res->fetch_assoc();
$db_hash = $user['password'];

// verifikasi modern
if (password_verify($password, $db_hash)) {
    // OK
} elseif (hash_equals($db_hash, md5($password))) {
    // legacy md5 match -> rehash ke password_hash
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $upd->bind_param('si', $newHash, $user['id']);
    $upd->execute();
    $upd->close();
} else {
    header("Location: ../public/index.php?error=" . urlencode('Password salah'));
    exit();
}

// set session
session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

header("Location: ../public/dashboard.php");
exit();
