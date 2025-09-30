<?php
session_start();
header('Content-Type: application/json');
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error'=>'unauthenticated']);
    exit;
}
$uid = (int)$_SESSION['user_id'];

// count unread
$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM notifications WHERE user_id = ? AND is_read = 0");
$stmt->bind_param('i', $uid);
$stmt->execute();
$unread = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$stmt->close();

// fetch latest 8
$stmt = $conn->prepare("SELECT n.id, n.ticket_id, n.message, n.is_read, n.created_at, u.username AS actor_name
                        FROM notifications n
                        LEFT JOIN users u ON u.id = n.user_id
                        WHERE n.user_id = ?
                        ORDER BY n.created_at DESC
                        LIMIT 8");
$stmt->bind_param('i', $uid);
$stmt->execute();
$res = $stmt->get_result();
$items = [];
while ($r = $res->fetch_assoc()) {
    $items[] = $r;
}
$stmt->close();

echo json_encode(['unread' => (int)$unread, 'items' => $items]);
