<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include '../config/database.php';

$isAdmin = $_SESSION['role'] === 'admin';
$userId = $_SESSION['user_id'];

// ambil jumlah notifikasi belum dibaca
$notif_count_query = $conn->prepare("SELECT COUNT(*) as total FROM notifications WHERE user_id = ? AND is_read = 0");
$notif_count_query->bind_param('i', $userId);
$notif_count_query->execute();
$notif_result = $notif_count_query->get_result()->fetch_assoc();
$notif_count = $notif_result['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Helpdesk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../img/logo-ail.png">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
  <!-- Navbar -->
  <nav class="bg-blue-600 text-white px-4 py-3 flex justify-between items-center shadow">
    <div class="flex items-center space-x-2">
      <img src="../img/logo-ail.png" alt="Logo" class="w-8 h-8">
      <h1 class="text-xl font-bold">Helpdesk System</h1>
    </div>
    <div class="flex items-center space-x-6">
      <div class="relative">
        <a href="#" class="relative">
          <?php if ($notif_count > 0): ?>
            <span class="absolute -top-2 -right-2 bg-red-600 text-xs text-white px-2 py-0.5 rounded-full"><?= $notif_count ?></span>
          <?php endif; ?>
        </a>
      </div>
      <div class="text-right">
        <p>Halo, <a href="edit_profile.php" class="font-bold hover:underline"><?= $_SESSION['username'] ?></a></p>
        <a href="../actions/logout_action.php" class="underline text-sm">Logout</a>
      </div>
    </div>
  </nav>
  <!-- Konten -->
  <div class="flex-1 container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-2">
      <div class="flex gap-2 w-full sm:w-auto">
        <input id="searchInput" type="text" placeholder="Cari tiket, user, status..." class="border px-3 py-2 rounded w-full sm:w-64">
        <select id="statusFilter" class="border px-3 py-2 rounded">
          <option value="">Semua</option>
          <option value="open">Open</option>
          <option value="in-progress">In Progress</option>
          <option value="closed">Closed</option>
        </select>
        <button onclick="searchTickets()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cari</button>
      </div>
      <div class="flex gap-2">
        <a href="ticket_create.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">+Tiket baru</a>
        <?php if ($isAdmin): ?>
          <a href="reset_user.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Reset Password</a>
        <?php endif; ?>
      </div>
    </div>
  <!-- Tabel Tiket -->
    <div class="overflow-x-auto bg-white rounded shadow">
      <table class="min-w-full text-sm text-left" id="ticketTable">
        <thead class="bg-blue-100 text-blue-800">
          <tr>
            <th class="p-3 cursor-pointer" onclick="sortTable(0)">Nomor Tiket </th>
            <th class="p-3 cursor-pointer" onclick="sortTable(1)">Judul </th>
            <th class="p-3 cursor-pointer" onclick="sortTable(2)">Status </th>
            <th class="p-3 cursor-pointer" onclick="sortTable(3)">User </th>
            <th class="p-3 cursor-pointer" onclick="sortTable(4)">Tanggal Buat </th>
            <th class="p-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = $isAdmin 
            ? "SELECT t.*, u.username FROM tickets t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC"
            : "SELECT t.*, u.username FROM tickets t JOIN users u ON t.user_id = u.id WHERE t.user_id = $userId ORDER BY t.created_at DESC";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)):
            $statusColor = match($row['status']) {
              'open' => 'bg-green-100 text-green-800',
              'in-progress' => 'bg-yellow-100 text-yellow-800',
              'closed' => 'bg-gray-200 text-gray-700',
              default => 'bg-blue-100 text-blue-800'
            };
          ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="p-3"><?= $row['ticket_number'] ?></td>
              <td class="p-3"><?= $row['title'] ?></td>
              <td class="p-3"><span class="px-3 py-1 rounded <?= $statusColor ?>"><?= ucfirst($row['status']) ?></span></td>
              <td class="p-3"><?= $row['username'] ?></td>
              <td class="p-3"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
              <td class="p-3 flex gap-2">
                <a href="ticket_view.php?id=<?= $row['id'] ?>" class="text-indigo-600 underline">Detail</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
  <script>
    const USER_ID = <?= $_SESSION['user_id'] ?>;
  </script>
  <script src="../assets/js/dashboard.js"></script>
  <?php include '../includes/footer.php'; ?>
</body>
</html>
