<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../public/index.php");
  exit();
}

$ticket_id = intval($_POST['ticket_id']);
$status = mysqli_real_escape_string($conn, $_POST['status']);

$sql = "UPDATE tickets SET status = '$status' WHERE id = '$ticket_id'";
if (mysqli_query($conn, $sql)) {
  header("Location: ../public/ticket_view.php?id=$ticket_id");
} else {
  echo "❌ Gagal mengubah status: " . mysqli_error($conn);
}
