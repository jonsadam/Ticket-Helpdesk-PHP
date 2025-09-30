<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: ../public/dashboard.php");
  exit();
}
include '../config/database.php';

$id = $_GET['id'];
$newPass = md5("user123"); // default password baru

mysqli_query($conn, "UPDATE users SET password='$newPass' WHERE id='$id'");
header("Location: ../public/reset_user.php?success=1");
