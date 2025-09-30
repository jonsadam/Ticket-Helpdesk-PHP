<?php
include '../config/database.php';

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = md5($_POST['password']);
$check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
if (mysqli_num_rows($check) > 0) {
  header("Location: ../public/index.php.php?error=Username%20sudah%20terdaftar");
  exit();
}

$sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'user')";
if (mysqli_query($conn, $sql)) {
  header("Location: ../public/index.php?success=register");
} else {
  header("Location: ../public/index.php?error=Gagal%20register");
}

