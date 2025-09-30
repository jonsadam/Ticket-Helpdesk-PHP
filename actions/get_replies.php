<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  echo json_encode(['error' => 'Unauthorized']);
  exit();
}

$ticket_id = intval($_GET['ticket_id']);

$sql = "SELECT r.*, u.username 
        FROM replies r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.ticket_id = ? 
        ORDER BY r.created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $ticket_id);
$stmt->execute();
$res = $stmt->get_result();

$replies = [];
while ($row = $res->fetch_assoc()) {
  $replies[] = $row;
}

header('Content-Type: application/json');
echo json_encode($replies);
