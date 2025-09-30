<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../public/index.php");
  exit();
}

$ticket_id = intval($_POST['ticket_id']);
$user_id = $_SESSION['user_id'];
$message_text = mysqli_real_escape_string($conn, $_POST['message']);

// simpan balasan
$ins = $conn->prepare("INSERT INTO replies (ticket_id, user_id, message, created_at) VALUES (?, ?, ?, NOW())");
$ins->bind_param('iis', $ticket_id, $user_id, $message_text);
$ok = $ins->execute();
$ins->close();

if ($ok) {
    // ambil info tiket
    $tq = $conn->prepare("SELECT ticket_number, user_id FROM tickets WHERE id = ?");
    $tq->bind_param('i', $ticket_id);
    $tq->execute();
    $tres = $tq->get_result();
    $ticket = $tres->fetch_assoc();
    $tq->close();

    $ticket_num = $ticket['ticket_number'] ?? ('#' . $ticket_id);
    $notif_msg = "Ada balasan baru pada tiket {$ticket_num}";

    // Notif ke owner jika owner bukan pengirim
    $owner_id = (int)$ticket['user_id'];
    if ($owner_id && $owner_id !== $user_id) {
        $n = $conn->prepare("INSERT INTO notifications (user_id, actor_id, ticket_id, type, message) VALUES (?, ?, ?, 'reply', ?)");
        $n->bind_param('iiis', $owner_id, $user_id, $ticket_id, $notif_msg);
        $n->execute();
        $n->close();
    }

    // Notif ke semua admin (kecuali actor)
    $admins = $conn->query("SELECT id FROM users WHERE role='admin'");
    if ($admins) {
        $n = $conn->prepare("INSERT INTO notifications (user_id, actor_id, ticket_id, type, message) VALUES (?, ?, ?, 'reply', ?)");
        while ($ad = $admins->fetch_assoc()) {
            $aid = (int)$ad['id'];
            if ($aid === $user_id) continue;
            $n->bind_param('iiis', $aid, $user_id, $ticket_id, $notif_msg);
            $n->execute();
        }
        $n->close();
    }

    header("Location: ../public/ticket_view.php?id=$ticket_id");
    exit();
} else {
    echo "❌ Gagal mengirim balasan: " . mysqli_error($conn);
}
