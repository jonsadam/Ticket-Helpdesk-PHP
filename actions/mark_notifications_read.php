<?php
session_start();
header('Content-Type: application/json');
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['ok'=>false,'error'=>'unauthenticated']);
    exit;
}
$uid = (int)$_SESSION['user_id'];

if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $id, $uid);
    $stmt->execute();
    $stmt->close();
} else {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $stmt->close();
}

echo json_encode(['ok'=>true]);
