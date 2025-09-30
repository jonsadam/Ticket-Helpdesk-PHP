<?php
session_start();
include '../config/database.php';


if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$ticket_id = intval($_GET['id']);


$query = "SELECT t.*, u.username 
          FROM tickets t 
          JOIN users u ON t.user_id = u.id 
          WHERE t.id = '$ticket_id'";

$result = mysqli_query($conn, $query);
$ticket = mysqli_fetch_assoc($result);


if (!$ticket) {
  die("Tiket tidak ditemukan.");
}


if ($role !== 'admin' && $ticket['user_id'] != $user_id) {
  die("❌ Akses ditolak. Anda tidak berhak melihat tiket ini.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Detail Tiket - Helpdesk</title>
  <link rel="icon" href="../img/logo-ail.png">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 min-h-screen">
  <div class="max-w-4xl mx-auto mt-10 bg-white shadow-lg rounded-lg p-8">

    <h1 class="text-2xl font-bold text-blue-600 mb-4">Detail Tiket</h1>

  
    <div class="border-b pb-4 mb-6">
      <p><strong>Nomor Tiket:</strong> <?php echo $ticket['ticket_number']; ?></p>
      <p><strong>Judul:</strong> <?= htmlspecialchars($ticket['title']) ?></p>
      <p><strong>Dibuat oleh:</strong> <?= htmlspecialchars($ticket['username']) ?></p>
      <p><strong>Tanggal dibuat:</strong> <?= date('d M Y H:i', strtotime($ticket['created_at'])) ?></p>
      <p><strong>Status:</strong>
        <span class="px-2 py-1 rounded text-white 
          <?= $ticket['status'] == 'open' ? 'bg-green-500' : ($ticket['status'] == 'in-progress' ? 'bg-yellow-500' : 'bg-gray-500') ?>">
          <?= ucfirst($ticket['status']) ?>
        </span>
      </p>
      <p class="mt-4"><strong>Deskripsi:</strong></p>
      <p><?= nl2br(htmlspecialchars($ticket['description'])) ?></p>

      <?php if (!empty($ticket['attachment'])): ?>
      <div class="mt-4">
        <p><strong>Lampiran:</strong></p>
        <button 
          type="button" 
          onclick="openImageModal('../<?= htmlspecialchars($ticket['attachment']) ?>')" 
          class="text-blue-600 underline hover:text-blue-800">
          Lihat Gambar
        </button>
      </div>
    <?php endif; ?>


    <h2 class="text-xl font-semibold mb-4">Balasan</h2>
    <?php
    $replies = mysqli_query($conn, "
      SELECT r.*, u.username 
      FROM replies r 
      JOIN users u ON r.user_id = u.id 
      WHERE r.ticket_id = '$ticket_id'
      ORDER BY r.created_at ASC
    ");
    ?>

    <div class="space-y-4 mb-6">
      <?php if (mysqli_num_rows($replies) > 0): ?>
        <?php while ($reply = mysqli_fetch_assoc($replies)): ?>
          <div class="p-4 bg-gray-50 border rounded-lg">
            <p class="text-sm text-gray-600 mb-1">
              <strong><?= htmlspecialchars($reply['username']) ?></strong> • <?= date('d M Y H:i', strtotime($reply['created_at'])) ?>
            </p>
            <p><?= nl2br(htmlspecialchars($reply['message'])) ?></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-gray-500">Belum ada balasan untuk tiket ini.</p>
      <?php endif; ?>
    </div>

    <form action="../actions/reply_ticket_action.php" method="POST" class="mb-8">
      <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">
      <textarea name="message" rows="4" placeholder="Tulis balasan..." class="w-full border rounded p-2 mb-4" required></textarea>
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim Balasan</button>
    </form>

    
    <?php if ($role === 'admin'): ?>
      <div class="bg-blue-50 p-4 rounded border">
        <h3 class="text-lg font-semibold mb-2">⚙️ Ubah Status Tiket</h3>
        <form action="../actions/update_status_action.php" method="POST" class="flex items-center gap-4">
          <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">
          <select name="status" class="border p-2 rounded">
            <option value="open" <?= $ticket['status'] == 'open' ? 'selected' : '' ?>>Open</option>
            <option value="in-progress" <?= $ticket['status'] == 'in-progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="closed" <?= $ticket['status'] == 'closed' ? 'selected' : '' ?>>Closed</option>
          </select>
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update Status</button>
        </form>
      </div>
    <?php endif; ?>

    <div class="mt-6">
      <a href="dashboard.php" class="text-blue-600 hover:underline">&larr; Back</a>
    </div>
  </div>

  <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden z-50">
    <div class="relative max-w-full max-h-full p-4">
      <button onclick="closeImageModal()" 
              class="absolute top-2 right-2 bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-700">
        ✕
      </button>
      <img id="modalImage" src="" alt="Lampiran" class="max-w-full max-h-[90vh] rounded-lg shadow-lg">
    </div>
  </div>
  <script>
  function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
  }

  function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
  }

  document.getElementById('imageModal').addEventListener('click', function (e) {
    if (e.target === this) {
      closeImageModal();
    }
  });
</script>
</body>
</html>
