<?php
include '../config/database.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // 🖼️ Upload gambar opsional
    $attachment = NULL;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES["attachment"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFilePath)) {
            $attachment = "uploads/" . $fileName; // Simpan path relatif ke DB
        }
    }

    // Ambil nomor tiket terakhir dari database
    $lastTicketQuery = mysqli_query($conn, "SELECT ticket_number FROM tickets ORDER BY id DESC LIMIT 1");
    $lastNumber = 0;

    if ($lastTicketQuery && mysqli_num_rows($lastTicketQuery) > 0) {
        $lastTicket = mysqli_fetch_assoc($lastTicketQuery);
        // Ambil 5 digit terakhir dari nomor sebelumnya
        preg_match('/(\d{5})$/', $lastTicket['ticket_number'], $matches);
        if (isset($matches[1])) {
            $lastNumber = (int)$matches[1];
        }
    }

    // Nomor berikutnya +1
    $newNumber = str_pad($lastNumber + 1, 5, "0", STR_PAD_LEFT);

    // Format: TKT-2025-00001
    $ticketNumber = "AIL-" . date("Ymd") . "-" . $newNumber;


    // ✅ Simpan ke database
    $sql = "INSERT INTO tickets (ticket_number, user_id, title, description, attachment, status, created_at) 
            VALUES ('$ticketNumber', '$user_id', '$title', '$description', '$attachment', 'open', NOW())";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "✅ Tiket berhasil dibuat! Nomor tiket Anda: <strong>$ticketNumber</strong>";
        header("Location: ../public/dashboard.php");
        exit;
    } else {
        echo "<h3>❌ Gagal menyimpan tiket:</h3> " . mysqli_error($conn);
    }
} else {
    header("Location: ../public/new_ticket.php");
    exit;
}
