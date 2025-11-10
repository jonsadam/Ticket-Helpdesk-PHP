-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Sep 2025 pada 11.48
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tiket`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `replies`
--

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `replies`
--

INSERT INTO `replies` (`id`, `ticket_id`, `user_id`, `message`, `created_at`) VALUES
(1, 2, 1, 'Sedang di kerjakan', '2025-09-26 07:24:08'),
(2, 2, 2, 'Oke braders', '2025-09-26 07:35:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `ticket_number` varchar(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('open','in-progress','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_number`, `user_id`, `title`, `description`, `attachment`, `status`, `created_at`) VALUES
(1, NULL, 1, 'sadad', 'adadada', NULL, 'open', '2025-09-26 06:15:12'),
(2, NULL, 2, 'asa', 'asa', '1758868629_arse.png', 'in-progress', '2025-09-26 06:37:09'),
(3, NULL, 2, 'Tidak konek internet ', 'Tidak konek internet karna kabel putus dan butuh di crimping', '1758879720_logo aca.png', 'open', '2025-09-26 09:42:00'),
(4, NULL, 2, 'tidak ada tiket', 'tidak ada tiket', '1758879896_logo aca.png', 'open', '2025-09-26 09:44:56'),
(5, NULL, 2, 'Komputer mati', 'Komputer mati', '1758880068_logo aca.png', 'open', '2025-09-26 09:47:48'),
(6, 'AIL-20250926-2925E', 2, 'ada', 'ada', 'uploads/1758880373_logo aca.png', 'open', '2025-09-26 09:52:53'),
(7, 'AIL-20250929-18DE5', 1, 'Monitor mati', 'Monitor mati di ruangan inventory', 'uploads/1759112166_logo aca.png', 'open', '2025-09-29 02:16:06'),
(8, 'TKT-2025-00001', 1, 'ad', 'asd', 'uploads/1759112626_logo aca.png', 'open', '2025-09-29 02:23:46'),
(9, 'AIL-20250929-00002', 1, 'sa', 'a', 'uploads/1759112684_logo aca.png', 'closed', '2025-09-29 02:24:44'),
(10, 'AIL-20250929-00003', 1, 'as', 'as', 'uploads/1759127206_aa.png', 'open', '2025-09-29 06:26:46'),
(11, 'AIL-20250929-00004', 3, 'Keyboard kebakar', 'Keyboard rusak dan tdk bisa di gunakan', 'uploads/1759131306_logo aca.png', 'in-progress', '2025-09-29 07:35:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$vexquYlr0IFZTtlbwvyaDuR4.JBL8QEC84TyindBp.5lFii4YymEq', 'admin', '2025-09-26 03:58:56'),
(2, 'adam', '$2y$10$Tt2M6NqD8bD.je8dkQtKdOfYAzfiLKtGvJTtGkv.NcdqUX.Zn3BMu', 'user', '2025-09-26 06:28:24'),
(3, 'Abdillah', '$2y$10$TPRAX/Ip.fkD/msQH1IWF.DTpG8AO40sfo3R4MUmCkrFHb5/oT7mm', 'user', '2025-09-29 06:27:48');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indeks untuk tabel `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_number` (`ticket_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
