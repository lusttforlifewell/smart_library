-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2026 at 02:21 PM
-- Server version: 11.7.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_library`
--

-- --------------------------------------------------------

--
-- Table structure for table `aktivitas`
--

CREATE TABLE `aktivitas` (
  `id` int(11) NOT NULL,
  `user` varchar(15) DEFAULT NULL,
  `aksi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aktivitas`
--

INSERT INTO `aktivitas` (`id`, `user`, `aksi`, `created_at`) VALUES
(1, 'Siswa', 'Meminjam buku: Bumi (Kode: BK007-5)', '2026-05-22 17:20:06'),
(2, 'Siswa', 'Meminjam buku: Dilan 1990 (Kode: BK008-3)', '2026-05-22 19:02:54'),
(3, 'Siswa', 'Meminjam buku: Matahari (Kode: EBOOK)', '2026-05-24 13:08:11'),
(4, 'Siswa', 'Meminjam buku: Bumi (Kode: BK007-6)', '2026-05-26 16:23:51'),
(5, 'Siswa', 'Meminjam buku: Dilan 1990 (Kode: BK008-4)', '2026-05-26 16:24:07'),
(6, 'Siswa', 'Meminjam buku: Pemrograman Web Modern (Kode: BK001-1)', '2026-06-11 06:43:11'),
(7, 'Siswa', 'Meminjam buku: Pemrograman Web Modern (Kode: BK001-2)', '2026-06-11 07:02:14');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `tahun_terbit` int(11) DEFAULT NULL,
  `cover` varchar(255) DEFAULT 'https://placehold.co/100x150',
  `deskripsi` text DEFAULT NULL,
  `tipe` enum('fisik','ebook') DEFAULT NULL,
  `kode_buku` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `judul`, `penulis`, `kategori_id`, `stok`, `tahun_terbit`, `cover`, `deskripsi`, `tipe`, `kode_buku`) VALUES
(1, 'Pemrograman Web Modern', 'Budi Santoso', 1, 48, 2023, 'pemweb.jpg', NULL, 'fisik', 'BK001'),
(2, 'Desain Grafis Dasar', 'Ani Wijaya', 2, 50, 2022, 'desain.jpg', NULL, 'fisik', 'BK002'),
(3, 'Fisika Kelas 12', 'Prof. Andi', 3, 50, 2021, 'fisika_kelas_12.png', NULL, 'ebook', 'BK003'),
(4, 'Bahasa Indonesia', 'Tim Guru', 3, 50, 2020, 'bahasa_indonesia.jpg', NULL, 'fisik', 'BK004'),
(5, 'Algoritma & Struktur Data', 'Dewi Sartika', 1, 50, 2024, 'algoritma_struktur_data.jpg', NULL, 'fisik', 'BK005'),
(7, 'Bumi', 'Tere Liye', 4, 45, 2014, 'bumi.jpg', NULL, 'fisik', 'BM 001'),
(8, 'Dilan 1990', 'Pidi Baiq', 4, 46, 2014, 'dilan_1990.jpg', NULL, 'fisik', NULL),
(9, 'Negeri 5 Menara', 'Ahmad Fuadi', 4, 50, 2009, 'negeri_5_menara.jpg', NULL, 'fisik', NULL),
(10, 'Matahari', 'Tere Liye', 4, 51, NULL, 'matahari.jpg', NULL, NULL, NULL),
(11, 'Basis Data', 'Penerbit Erlangga', 3, 50, NULL, '1777344055_basis_data_smk_kelas12.png', NULL, 'fisik', NULL),
(13, '', '', NULL, 0, NULL, 'https://placehold.co/100x150', NULL, NULL, NULL),
(14, 'Janji Di Tanah Jawa', 'Agil Sri Rahayu', 4, 50, NULL, '1779629734_janji_di_tanah_jawa.jpg', NULL, 'fisik', 'JDJ-001');

-- --------------------------------------------------------

--
-- Table structure for table `buku_item`
--

CREATE TABLE `buku_item` (
  `id` int(11) NOT NULL,
  `buku_id` int(11) NOT NULL,
  `kode_buku` varchar(50) NOT NULL,
  `status` enum('available','borrowed') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku_item`
--

INSERT INTO `buku_item` (`id`, `buku_id`, `kode_buku`, `status`, `created_at`) VALUES
(1, 1, 'BK001-1', 'borrowed', '2026-04-27 16:22:29'),
(2, 1, 'BK001-2', 'borrowed', '2026-04-27 16:22:29'),
(3, 1, 'BK001-3', 'available', '2026-04-27 16:22:29'),
(4, 1, 'BK001-4', 'available', '2026-04-27 16:22:29'),
(5, 7, 'BM001', 'borrowed', '2026-04-28 02:46:54'),
(6, 7, 'BK007-2', 'borrowed', '2026-04-28 02:46:54'),
(7, 7, 'BK007-3', 'borrowed', '2026-04-28 02:46:54'),
(8, 7, 'BK007-4', 'borrowed', '2026-04-28 02:46:54'),
(9, 7, 'BK007-5', 'borrowed', '2026-04-28 02:46:54'),
(10, 7, 'BK007-6', 'borrowed', '2026-04-28 02:46:54'),
(11, 8, 'BK008-1', 'borrowed', '2026-04-28 02:50:14'),
(12, 8, 'BK008-2', 'borrowed', '2026-04-28 02:50:14'),
(13, 8, 'BK008-3', 'borrowed', '2026-04-28 02:50:14'),
(14, 8, 'BK008-4', 'borrowed', '2026-04-28 02:50:14'),
(15, 9, 'BK009-1', 'available', '2026-04-28 02:50:30'),
(16, 9, 'BK009-2', 'available', '2026-04-28 02:50:30'),
(17, 9, 'BK009-3', 'available', '2026-04-28 02:50:30'),
(18, 9, 'BK009-4', 'available', '2026-04-28 02:50:30'),
(19, 9, 'BK009-5', 'available', '2026-04-28 02:50:30'),
(20, 10, 'BK010-1', 'available', '2026-04-28 02:50:53'),
(21, 10, 'BK010-2', 'available', '2026-04-28 02:50:53'),
(22, 10, 'BK010-3', 'available', '2026-04-28 02:50:53'),
(23, 10, 'BK010-4', 'available', '2026-04-28 02:50:53'),
(24, 10, 'BK010-5', 'available', '2026-04-28 02:50:53'),
(25, 10, 'BK010-6', 'available', '2026-04-28 02:50:53'),
(26, 10, 'BK010-7', 'available', '2026-04-28 02:50:53'),
(27, 10, 'BK010-8', 'available', '2026-04-28 02:50:53'),
(28, 10, 'BK010-9', 'available', '2026-04-28 02:50:53'),
(29, 10, 'BK010-10', 'available', '2026-04-28 02:50:53'),
(30, 10, 'BK010-11', 'available', '2026-04-28 02:50:53'),
(31, 10, 'BK010-12', 'available', '2026-04-28 02:50:53'),
(32, 10, 'BK010-13', 'available', '2026-04-28 02:50:53'),
(33, 10, 'BK010-14', 'available', '2026-04-28 02:50:53'),
(34, 10, 'BK010-15', 'available', '2026-04-28 02:50:53'),
(35, 11, 'BK011-1', 'available', '2026-04-28 02:51:12'),
(36, 11, 'BK011-2', 'available', '2026-04-28 02:51:12'),
(37, 11, 'BK011-3', 'available', '2026-04-28 02:51:12'),
(38, 11, 'BK011-4', 'available', '2026-04-28 02:51:12'),
(39, 11, 'BK011-5', 'available', '2026-04-28 02:51:12'),
(40, 11, 'BK011-6', 'available', '2026-04-28 02:51:12'),
(41, 11, 'BK011-7', 'available', '2026-04-28 02:51:12'),
(42, 11, 'BK011-8', 'available', '2026-04-28 02:51:12'),
(43, 11, 'BK011-9', 'available', '2026-04-28 02:51:12'),
(44, 11, 'BK011-10', 'available', '2026-04-28 02:51:12'),
(45, 11, 'BK011-11', 'available', '2026-04-28 02:51:12'),
(46, 11, 'BK011-12', 'available', '2026-04-28 02:51:12'),
(47, 11, 'BK011-13', 'available', '2026-04-28 02:51:12'),
(48, 11, 'BK011-14', 'available', '2026-04-28 02:51:12'),
(49, 11, 'BK011-15', 'available', '2026-04-28 02:51:12'),
(50, 11, 'BK011-16', 'available', '2026-04-28 02:51:12'),
(51, 11, 'BK011-17', 'available', '2026-04-28 02:51:12'),
(52, 11, 'BK011-18', 'available', '2026-04-28 02:51:12'),
(53, 11, 'BK011-19', 'available', '2026-04-28 02:51:12'),
(54, 11, 'BK011-20', 'available', '2026-04-28 02:51:12'),
(55, 11, 'BK011-21', 'available', '2026-04-28 02:51:12'),
(56, 11, 'BK011-22', 'available', '2026-04-28 02:51:12'),
(57, 11, 'BK011-23', 'available', '2026-04-28 02:51:12'),
(58, 11, 'BK011-24', 'available', '2026-04-28 02:51:12'),
(59, 11, 'BK011-25', 'available', '2026-04-28 02:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `ebook`
--

CREATE TABLE `ebook` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kode_buku` varchar(30) DEFAULT NULL,
  `penulis` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `file_url` varchar(255) DEFAULT NULL,
  `cover` varchar(255) DEFAULT 'https://placehold.co/100x150',
  `sinopsis` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ebook`
--

INSERT INTO `ebook` (`id`, `judul`, `kode_buku`, `penulis`, `harga`, `stok`, `file_url`, `cover`, `sinopsis`) VALUES
(1, 'Panduan UAS Pemrograman', 'EBK-001', 'Tim IT', 25000.00, 76, NULL, 'pemweb.jpg', 'Perkembangan teknologi informasi yang semakin pesat membuat kebutuhan akan aplikasi berbasis web terus meningkat, terutama dalam bidang pendidikan, bisnis, dan layanan digital. Pemrograman aplikasi web menjadi salah satu keterampilan penting yang harus dipahami karena mampu membantu proses pengolahan data, penyampaian informasi, serta meningkatkan efisiensi kerja secara online. Dengan memanfaatkan bahasa pemrograman seperti PHP, HTML, CSS, JavaScript, dan database MySQL, sebuah aplikasi web dapat dibangun secara interaktif, dinamis, dan mudah diakses oleh pengguna melalui internet.\r\n\r\nBuku Pemrograman Aplikasi Web hadir sebagai media pembelajaran yang membahas dasar hingga penerapan pembuatan website dan sistem berbasis web. Buku ini dirancang untuk membantu pembaca memahami konsep pengembangan aplikasi web mulai dari perancangan tampilan, pengolahan database, autentikasi pengguna, hingga implementasi fitur interaktif. Melalui pembelajaran yang terstruktur dan praktik langsung, pembaca diharapkan mampu mengembangkan aplikasi web yang modern, efektif, dan sesuai dengan kebutuhan perkembangan teknologi saat ini.'),
(2, 'Novel Laskar Pelangi', 'EBK-002', 'Andrea Hirata', 45000.00, 77, 'laskar-pelangi.pdf', 'laskar_pelangi.jpg', 'Laskar Pelangi adalah novel karya Andrea Hirata yang menceritakan perjuangan sepuluh anak dari keluarga sederhana di Belitung dalam meraih pendidikan. Mereka bersekolah di SD Muhammadiyah yang memiliki fasilitas sangat terbatas dan hampir ditutup karena kekurangan murid. Namun, dengan semangat belajar yang tinggi serta dukungan dari guru mereka, Bu Muslimah dan Pak Harfan, anak-anak tersebut tetap berjuang mengejar cita-cita.\r\n\r\nKisah ini berfokus pada persahabatan, mimpi, dan semangat pantang menyerah dalam menghadapi keterbatasan ekonomi maupun sosial. Setiap tokoh memiliki karakter unik yang membuat cerita semakin menarik dan penuh makna. Novel ini memberikan pesan inspiratif bahwa pendidikan dan harapan mampu mengubah masa depan seseorang meskipun hidup dalam kesederhanaan.'),
(3, 'Matahari', NULL, 'Tere Liye', 99999.00, 77, 'Tere_Liye_-_Matahari.pdf.pdf', 'matahari.jpg', 'Sebagai catatan, tokoh utama dalam novel ini adalah tiga sekawan yang mempunyai kemampuan di luar nalar, yaitu Raib (bisa menghilang), Seli (bisa menyalurkan petir dari telapak tangannya), dan Ali (bisa berubah menjadi beruang raksasa). \r\n\r\nRaib, Seli, dan Ali harus menghadiri pemakaman dan diselimuti dengan perasaan duka setelah salah satu temannya, Ily, tewas di medan perang. Usai menjalani hari yang penuh awan gelap, ketiganya kembali ke sekolah dan belajar seperti biasa.\r\n\r\nSingkat cerita, Ali ikut serta dalam pertandingan basket antarsekolah dan dirinya secara tidak sengaja menjadi bintang. Namun, Raib melihat sesuatu yang tidak beres dan menuduh lawan main Ali menggunakan sihir untuk memenangkan lomba. \r\n\r\nMendengar hal tersebut, emosi Ali otomatis tersulut. Ia murka dan wujudnya hampir berubah menjadi beruang raksasa. Beruntung, Raib langsung memanfaatkan alat teleportasi yang dimilikinya untuk memboyong Ali–dan Seli–pergi dari arena.\r\n\r\nRupanya, alat teleportasi itu membawa ketiganya ke basement rumah mewah Ali. Di sana, Raib dan Seli dikejutkan dengan penampakan pesawat kapsul perak yang menunjukkan tanda-tanda peradaban tinggi. \r\n\r\nSetelah berdiskusi panjang tentang ilmu baru yang sebenarnya sedang dipelajari Ali dan munculnya pengakuan dari kedua orang tua Raib perihal perjalanan anaknya dalam melintasi dimensi, mereka pun pergi dengan menaiki pesawat kapsul perak.\r\n\r\nBenda canggih itu membawa ketiganya ke sebuah tempat asing yang menyerupai mulut lorong kuno. Belum sempat mereka beradaptasi dan menyadarkan diri, baik Raib, Seli, dan Ali langsung berhadapan dengan penduduk kota.\r\n\r\nBuku Kehidupan milik Raib dicuri oleh Dewan Sekretaris Kota, Ali terpaksa berubah menjadi beruang raksasa untuk menghalau penjahat yang menyerang mereka, dan Seli pun mengeluarkan kekuatan petirnya untuk memusnahkan lawan.');

-- --------------------------------------------------------

--
-- Table structure for table `ebook_transactions`
--

CREATE TABLE `ebook_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ebook_id` int(11) NOT NULL,
  `tanggal_beli` date NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status_pembayaran` enum('lunas','pending') DEFAULT 'lunas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ebook_transactions`
--

INSERT INTO `ebook_transactions` (`id`, `user_id`, `ebook_id`, `tanggal_beli`, `total_harga`, `status_pembayaran`) VALUES
(3, 2, 1, '2025-06-01', 25000.00, 'lunas'),
(4, 2, 2, '2025-06-05', 45000.00, 'lunas'),
(5, 2, 3, '2026-05-24', 99999.00, 'lunas'),
(6, 13, 3, '2026-05-24', 99999.00, 'lunas'),
(7, 13, 2, '2026-05-24', 45000.00, 'lunas');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `jenis` enum('Fiction','Non-Fiction') DEFAULT 'Non-Fiction'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `jenis`) VALUES
(1, 'Programming', 'Non-Fiction'),
(2, 'Design', 'Non-Fiction'),
(3, 'Subjects', 'Non-Fiction'),
(4, 'Novel', 'Fiction'),
(5, 'Science', 'Non-Fiction');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` enum('info','warning','error','success') DEFAULT 'info',
  `dibaca` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `pesan`, `tipe`, `dibaca`, `created_at`) VALUES
(1, 2, 'Buku Pemrograman Web Modern terlambat 14 hari.', 'warning', 0, '2026-05-18 06:09:17'),
(2, 2, 'Buku Pemrograman Web Modern terlambat 12 hari.', 'warning', 0, '2026-05-18 06:09:17'),
(3, 2, 'Buku Pemrograman Web Modern terlambat 5 hari.', 'warning', 0, '2026-05-18 06:09:17'),
(4, 2, 'Buku Fisika Kelas 12 terlambat 14 hari.', 'warning', 0, '2026-05-18 06:09:18'),
(5, 2, 'Buku Bahasa Indonesia terlambat 14 hari.', 'warning', 0, '2026-05-18 06:09:18'),
(6, 2, 'Buku Laskar Pelangi terlambat 12 hari.', 'warning', 0, '2026-05-18 06:09:18'),
(7, 2, 'Buku Matahari terlambat 13 hari.', 'warning', 0, '2026-05-18 06:09:18'),
(8, 1, 'Siswa  meminjam buku Negeri 5 Menara (Kode: BK009-2)', 'info', 0, '2026-05-18 11:37:09'),
(9, 1, 'Siswa  meminta pengembalian buku.', 'warning', 0, '2026-05-18 11:37:18'),
(10, 2, 'Berhasil meminjam buku  (Kode: BK009-3)', 'info', 0, '2026-05-18 12:53:18'),
(11, 1, 'Siswa  meminjam buku Negeri 5 Menara (Kode: BK009-3)', 'info', 0, '2026-05-18 12:53:18'),
(12, 7, 'Siswa  meminjam buku Negeri 5 Menara (Kode: BK009-3)', 'info', 0, '2026-05-18 12:53:18'),
(13, 1, 'Siswa  meminta pengembalian buku.', 'warning', 0, '2026-05-18 12:54:59'),
(14, 1, 'Siswa  meminta pengembalian buku.', 'warning', 0, '2026-05-18 12:55:03'),
(15, 1, 'Siswa  meminta pengembalian buku.', 'warning', 0, '2026-05-18 12:57:26'),
(16, 1, 'Buku dengan kode BK008-3 telah dikembalikan.', 'success', 0, '2026-05-18 13:11:39'),
(17, 2, 'Berhasil meminjam buku  (Kode: BK001-3)', 'info', 0, '2026-05-18 13:12:22'),
(18, 1, 'Siswa  meminjam buku Pemrograman Web Modern (Kode: BK001-3)', 'info', 0, '2026-05-18 13:12:22'),
(19, 7, 'Siswa  meminjam buku Pemrograman Web Modern (Kode: BK001-3)', 'info', 0, '2026-05-18 13:12:22'),
(20, 1, 'Siswa  meminta pengembalian buku.', 'warning', 0, '2026-05-18 13:12:28'),
(21, 2, 'Berhasil meminjam buku  (Kode: BK007-2)', 'info', 0, '2026-05-18 13:15:55'),
(22, 1, 'Siswa  meminjam buku Bumi (Kode: BK007-2)', 'info', 0, '2026-05-18 13:15:55'),
(23, 7, 'Siswa  meminjam buku Bumi (Kode: BK007-2)', 'info', 0, '2026-05-18 13:15:55'),
(24, 2, 'Berhasil meminjam buku  (Kode: BK009-4)', 'info', 0, '2026-05-18 13:17:10'),
(25, 1, 'Siswa  meminjam buku Negeri 5 Menara (Kode: BK009-4)', 'info', 0, '2026-05-18 13:17:10'),
(26, 7, 'Siswa  meminjam buku Negeri 5 Menara (Kode: BK009-4)', 'info', 0, '2026-05-18 13:17:10'),
(27, 2, 'Pengembalian buku berhasil dikonfirmasi admin.', 'info', 0, '2026-05-18 13:22:43'),
(28, 1, 'Buku dengan kode BK009-4 telah dikembalikan.', 'success', 0, '2026-05-18 13:22:43'),
(29, 2, 'Buku Pemrograman Web Modern terlambat 16 hari.', 'warning', 0, '2026-05-20 04:01:31'),
(30, 2, 'Buku Pemrograman Web Modern terlambat 7 hari.', 'warning', 0, '2026-05-20 04:01:31'),
(31, 2, 'Buku Fisika Kelas 12 terlambat 16 hari.', 'warning', 0, '2026-05-20 04:01:31'),
(32, 2, 'Buku Bahasa Indonesia terlambat 16 hari.', 'warning', 0, '2026-05-20 04:01:31'),
(33, 2, 'Buku Laskar Pelangi terlambat 14 hari.', 'warning', 0, '2026-05-20 04:01:31'),
(34, 2, 'Buku Matahari terlambat 15 hari.', 'warning', 0, '2026-05-20 04:01:31'),
(35, 2, 'Berhasil meminjam buku  (Kode: BK008-3)', 'info', 0, '2026-05-20 04:01:46'),
(36, 1, 'Siswa  meminjam buku Dilan 1990 (Kode: BK008-3)', 'info', 0, '2026-05-20 04:01:46'),
(37, 7, 'Siswa  meminjam buku Dilan 1990 (Kode: BK008-3)', 'info', 0, '2026-05-20 04:01:46'),
(38, 2, 'Buku Pemrograman Web Modern terlambat 18 hari.', 'warning', 0, '2026-05-22 14:16:48'),
(39, 2, 'Buku Pemrograman Web Modern terlambat 9 hari.', 'warning', 0, '2026-05-22 14:16:48'),
(40, 2, 'Buku Fisika Kelas 12 terlambat 18 hari.', 'warning', 0, '2026-05-22 14:16:48'),
(41, 2, 'Buku Bahasa Indonesia terlambat 18 hari.', 'warning', 0, '2026-05-22 14:16:48'),
(42, 2, 'Buku Laskar Pelangi terlambat 16 hari.', 'warning', 0, '2026-05-22 14:16:48'),
(43, 2, 'Buku Matahari terlambat 17 hari.', 'warning', 0, '2026-05-22 14:16:48'),
(44, 2, 'Berhasil meminjam buku  (Kode: BK008-1)', 'info', 0, '2026-05-22 14:38:57'),
(45, 1, 'Siswa  meminjam buku Dilan 1990 (Kode: BK008-1)', 'info', 0, '2026-05-22 14:38:57'),
(46, 7, 'Siswa  meminjam buku Dilan 1990 (Kode: BK008-1)', 'info', 0, '2026-05-22 14:38:57'),
(47, 2, 'Berhasil meminjam buku  (Kode: BM001)', 'info', 0, '2026-05-22 14:43:54'),
(48, 1, 'Siswa  meminjam buku Bumi (Kode: BM001)', 'info', 0, '2026-05-22 14:43:54'),
(49, 7, 'Siswa  meminjam buku Bumi (Kode: BM001)', 'info', 0, '2026-05-22 14:43:54'),
(50, 2, 'Berhasil meminjam buku  (Kode: BK007-2)', 'info', 0, '2026-05-22 15:12:44'),
(51, 1, 'Siswa  meminjam buku Bumi (Kode: BK007-2)', 'info', 0, '2026-05-22 15:12:44'),
(52, 7, 'Siswa  meminjam buku Bumi (Kode: BK007-2)', 'info', 0, '2026-05-22 15:12:44'),
(53, 2, 'Berhasil meminjam buku Bumi (Kode: BK007-3)', 'info', 0, '2026-05-22 15:14:53'),
(54, 1, 'Siswa  meminjam buku Bumi (Kode: BK007-3)', 'info', 0, '2026-05-22 15:14:53'),
(55, 7, 'Siswa  meminjam buku Bumi (Kode: BK007-3)', 'info', 0, '2026-05-22 15:14:53'),
(56, 2, 'Berhasil meminjam buku Dilan 1990 (Kode: BK008-1)', 'info', 0, '2026-05-22 15:15:11'),
(57, 1, 'Siswa  meminjam buku Dilan 1990 (Kode: BK008-1)', 'info', 0, '2026-05-22 15:15:11'),
(58, 7, 'Siswa  meminjam buku Dilan 1990 (Kode: BK008-1)', 'info', 0, '2026-05-22 15:15:11'),
(59, 1, 'Buku dengan kode BK008-1 telah dikembalikan.', 'success', 0, '2026-05-22 15:19:30'),
(60, 1, 'Permintaan pengembalian buku dengan kode BK007-3 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:24:54'),
(61, 7, 'Permintaan pengembalian buku dengan kode BK007-3 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:24:54'),
(62, 1, 'Permintaan pengembalian buku dengan kode BK007-2 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:24:59'),
(63, 7, 'Permintaan pengembalian buku dengan kode BK007-2 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:24:59'),
(64, 1, 'Permintaan pengembalian buku dengan kode BM001 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:25:02'),
(65, 7, 'Permintaan pengembalian buku dengan kode BM001 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:25:02'),
(66, 1, 'Permintaan pengembalian buku dengan kode BK008-1 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:25:06'),
(67, 7, 'Permintaan pengembalian buku dengan kode BK008-1 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:25:06'),
(68, 1, 'Permintaan pengembalian buku dengan kode BK007-2 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:25:10'),
(69, 7, 'Permintaan pengembalian buku dengan kode BK007-2 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:25:10'),
(70, 1, 'Permintaan pengembalian buku dengan kode BK008-3 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:25:13'),
(71, 7, 'Permintaan pengembalian buku dengan kode BK008-3 sedang menunggu persetujuan.', 'info', 0, '2026-05-22 15:25:13'),
(72, 2, 'Berhasil meminjam buku Negeri 5 Menara (Kode: BK009-1)', 'info', 0, '2026-05-22 15:43:49'),
(73, 1, 'Siswa  meminjam buku Negeri 5 Menara (Kode: BK009-1)', 'info', 0, '2026-05-22 15:43:49'),
(74, 7, 'Siswa  meminjam buku Negeri 5 Menara (Kode: BK009-1)', 'info', 0, '2026-05-22 15:43:49'),
(75, 2, 'Berhasil meminjam buku Matahari (Kode: EBOOK)', 'info', 0, '2026-05-22 16:43:43'),
(76, 1, 'Siswa  meminjam buku Matahari (Kode: EBOOK)', 'info', 0, '2026-05-22 16:43:43'),
(77, 7, 'Siswa  meminjam buku Matahari (Kode: EBOOK)', 'info', 0, '2026-05-22 16:43:43'),
(78, 2, 'Berhasil meminjam buku Dilan 1990 (Kode: BK008-1)', 'info', 0, '2026-05-22 17:01:05'),
(79, 1, 'Siswa meminjam buku Dilan 1990 (Kode: BK008-1)', 'info', 0, '2026-05-22 17:01:05'),
(80, 7, 'Siswa meminjam buku Dilan 1990 (Kode: BK008-1)', 'info', 0, '2026-05-22 17:01:05'),
(81, 2, 'Berhasil meminjam buku Dilan 1990 (Kode: BK008-2)', 'info', 0, '2026-05-22 17:08:16'),
(82, 1, 'Siswa meminjam buku Dilan 1990 (Kode: BK008-2)', 'info', 0, '2026-05-22 17:08:16'),
(83, 7, 'Siswa meminjam buku Dilan 1990 (Kode: BK008-2)', 'info', 0, '2026-05-22 17:08:16'),
(84, 2, 'Berhasil meminjam buku Bumi (Kode: BK007-4)', 'info', 0, '2026-05-22 17:17:16'),
(85, 1, 'Siswa meminjam buku Bumi (Kode: BK007-4)', 'info', 0, '2026-05-22 17:17:16'),
(86, 7, 'Siswa meminjam buku Bumi (Kode: BK007-4)', 'info', 0, '2026-05-22 17:17:16'),
(87, 2, 'Berhasil meminjam buku Bumi (Kode: BK007-5)', 'info', 0, '2026-05-22 17:20:06'),
(88, 1, 'Siswa meminjam buku Bumi (Kode: BK007-5)', 'info', 0, '2026-05-22 17:20:06'),
(89, 7, 'Siswa meminjam buku Bumi (Kode: BK007-5)', 'info', 0, '2026-05-22 17:20:06'),
(90, 2, 'Berhasil meminjam buku Dilan 1990 (Kode: BK008-3)', 'info', 0, '2026-05-22 19:02:54'),
(91, 1, 'Siswa meminjam buku Dilan 1990 (Kode: BK008-3)', 'info', 0, '2026-05-22 19:02:54'),
(92, 7, 'Siswa meminjam buku Dilan 1990 (Kode: BK008-3)', 'info', 0, '2026-05-22 19:02:54'),
(93, 9, 'Berhasil meminjam buku Matahari (Kode: EBOOK)', 'info', 0, '2026-05-24 13:08:11'),
(94, 1, 'Siswa meminjam buku Matahari (Kode: EBOOK)', 'info', 0, '2026-05-24 13:08:11'),
(95, 7, 'Siswa meminjam buku Matahari (Kode: EBOOK)', 'info', 0, '2026-05-24 13:08:11'),
(96, 1, 'User baru berhasil mendaftar', 'info', 0, '2026-05-24 13:13:21'),
(97, 7, 'User baru berhasil mendaftar', 'info', 0, '2026-05-24 13:17:00'),
(98, 7, 'Buku baru berhasil ditambahkan', 'success', 0, '2026-05-24 13:35:34'),
(99, 7, 'User baru berhasil mendaftar', 'info', 0, '2026-05-24 13:55:42'),
(100, 2, 'Buku Pemrograman Web Modern terlambat 20 hari.', 'warning', 0, '2026-05-24 15:53:50'),
(101, 2, 'Buku Pemrograman Web Modern terlambat 11 hari.', 'warning', 0, '2026-05-24 15:53:50'),
(102, 2, 'Buku Fisika Kelas 12 terlambat 20 hari.', 'warning', 0, '2026-05-24 15:53:50'),
(103, 2, 'Buku Bahasa Indonesia terlambat 20 hari.', 'warning', 0, '2026-05-24 15:53:50'),
(104, 2, 'Buku Dilan 1990 harus dikembalikan besok.', 'warning', 0, '2026-05-24 15:53:50'),
(105, 2, 'Buku Matahari terlambat 19 hari.', 'warning', 0, '2026-05-24 15:53:50'),
(106, 7, 'User baru berhasil mendaftar', 'info', 0, '2026-05-24 16:48:29'),
(107, 1, 'Transaksi baru: Nelsya Desfrita membeli ebook Novel Laskar Pelangi', 'success', 0, '2026-05-24 16:57:39'),
(108, 13, 'Berhasil membeli ebook Novel Laskar Pelangi', 'info', 0, '2026-05-24 16:57:39'),
(109, 2, 'Buku Pemrograman Web Modern terlambat 21 hari.', 'warning', 0, '2026-05-25 04:52:48'),
(110, 2, 'Buku Fisika Kelas 12 terlambat 21 hari.', 'warning', 0, '2026-05-25 04:52:48'),
(111, 2, 'Buku Bahasa Indonesia terlambat 21 hari.', 'warning', 0, '2026-05-25 04:52:48'),
(112, 2, 'Buku Matahari terlambat 20 hari.', 'warning', 0, '2026-05-25 04:52:48'),
(113, 2, 'Buku Pemrograman Web Modern terlambat 19 hari.', 'warning', 0, '2026-05-25 04:52:48'),
(114, 2, 'Buku Bumi terlambat 1 hari.', 'warning', 0, '2026-05-25 04:52:48'),
(115, 2, 'Buku Dilan 1990 terlambat 1 hari.', 'warning', 0, '2026-05-25 04:52:48'),
(116, 2, 'Buku Pemrograman Web Modern terlambat 22 hari.', 'warning', 0, '2026-05-26 13:22:36'),
(117, 2, 'Buku Fisika Kelas 12 terlambat 22 hari.', 'warning', 0, '2026-05-26 13:22:36'),
(118, 2, 'Buku Bahasa Indonesia terlambat 22 hari.', 'warning', 0, '2026-05-26 13:22:36'),
(119, 2, 'Buku Matahari terlambat 21 hari.', 'warning', 0, '2026-05-26 13:22:36'),
(120, 2, 'Buku Pemrograman Web Modern terlambat 13 hari.', 'warning', 0, '2026-05-26 13:22:36'),
(121, 2, 'Buku Bumi terlambat 2 hari.', 'warning', 0, '2026-05-26 13:22:36'),
(122, 2, 'Buku Dilan 1990 terlambat 2 hari.', 'warning', 0, '2026-05-26 13:22:36'),
(123, 2, 'Berhasil meminjam buku Bumi (Kode: BK007-6)', 'info', 0, '2026-05-26 16:23:51'),
(124, 1, 'Siswa meminjam buku Bumi (Kode: BK007-6)', 'info', 0, '2026-05-26 16:23:51'),
(125, 7, 'Siswa meminjam buku Bumi (Kode: BK007-6)', 'info', 0, '2026-05-26 16:23:51'),
(126, 2, 'Berhasil meminjam buku Dilan 1990 (Kode: BK008-4)', 'info', 0, '2026-05-26 16:24:07'),
(127, 1, 'Siswa meminjam buku Dilan 1990 (Kode: BK008-4)', 'info', 0, '2026-05-26 16:24:07'),
(128, 7, 'Siswa meminjam buku Dilan 1990 (Kode: BK008-4)', 'info', 0, '2026-05-26 16:24:07'),
(129, 2, 'Buku Pemrograman Web Modern terlambat 23 hari.', 'warning', 0, '2026-05-27 10:36:19'),
(130, 2, 'Buku Fisika Kelas 12 terlambat 23 hari.', 'warning', 0, '2026-05-27 10:36:20'),
(131, 2, 'Buku Bahasa Indonesia terlambat 23 hari.', 'warning', 0, '2026-05-27 10:36:20'),
(132, 2, 'Buku Matahari terlambat 22 hari.', 'warning', 0, '2026-05-27 10:36:20'),
(133, 2, 'Buku Bumi terlambat 3 hari.', 'warning', 0, '2026-05-27 10:36:20'),
(134, 2, 'Buku Dilan 1990 terlambat 3 hari.', 'warning', 0, '2026-05-27 10:36:20'),
(135, 7, 'User baru berhasil mendaftar', 'info', 0, '2026-05-27 14:35:55'),
(136, 2, 'Buku Pemrograman Web Modern terlambat 28 hari.', 'warning', 0, '2026-06-01 12:16:05'),
(137, 2, 'Buku Fisika Kelas 12 terlambat 28 hari.', 'warning', 0, '2026-06-01 12:16:05'),
(138, 2, 'Buku Bahasa Indonesia terlambat 28 hari.', 'warning', 0, '2026-06-01 12:16:05'),
(139, 2, 'Buku Matahari terlambat 27 hari.', 'warning', 0, '2026-06-01 12:16:05'),
(140, 2, 'Buku Pemrograman Web Modern terlambat 26 hari.', 'warning', 0, '2026-06-01 12:16:05'),
(141, 2, 'Buku Bumi terlambat 8 hari.', 'warning', 0, '2026-06-01 12:16:05'),
(142, 2, 'Buku Dilan 1990 terlambat 8 hari.', 'warning', 0, '2026-06-01 12:16:05'),
(143, 2, 'Buku Dilan 1990 terlambat 7 hari.', 'warning', 0, '2026-06-01 12:16:05'),
(144, 2, 'Buku Bumi harus dikembalikan besok.', 'warning', 0, '2026-06-01 12:16:05'),
(145, 2, 'Buku Pemrograman Web Modern terlambat 29 hari.', 'warning', 0, '2026-06-02 14:14:30'),
(146, 2, 'Buku Fisika Kelas 12 terlambat 29 hari.', 'warning', 0, '2026-06-02 14:14:30'),
(147, 2, 'Buku Bahasa Indonesia terlambat 29 hari.', 'warning', 0, '2026-06-02 14:14:30'),
(148, 2, 'Buku Matahari terlambat 28 hari.', 'warning', 0, '2026-06-02 14:14:30'),
(149, 2, 'Buku Pemrograman Web Modern terlambat 27 hari.', 'warning', 0, '2026-06-02 14:14:30'),
(150, 2, 'Buku Bumi terlambat 9 hari.', 'warning', 0, '2026-06-02 14:14:30'),
(151, 2, 'Buku Dilan 1990 terlambat 9 hari.', 'warning', 0, '2026-06-02 14:14:30'),
(152, 2, 'Buku Bumi terlambat 4 hari.', 'warning', 0, '2026-06-02 14:14:30'),
(153, 2, 'Buku Pemrograman Web Modern terlambat 38 hari.', 'warning', 0, '2026-06-11 06:42:01'),
(154, 2, 'Buku Fisika Kelas 12 terlambat 38 hari.', 'warning', 0, '2026-06-11 06:42:01'),
(155, 2, 'Buku Bahasa Indonesia terlambat 38 hari.', 'warning', 0, '2026-06-11 06:42:01'),
(156, 2, 'Buku Matahari terlambat 37 hari.', 'warning', 0, '2026-06-11 06:42:01'),
(157, 2, 'Buku Pemrograman Web Modern terlambat 36 hari.', 'warning', 0, '2026-06-11 06:42:01'),
(158, 2, 'Buku Bumi terlambat 18 hari.', 'warning', 0, '2026-06-11 06:42:01'),
(159, 2, 'Buku Dilan 1990 terlambat 18 hari.', 'warning', 0, '2026-06-11 06:42:01'),
(160, 2, 'Buku Dilan 1990 terlambat 17 hari.', 'warning', 0, '2026-06-11 06:42:01'),
(161, 2, 'Buku Bumi terlambat 13 hari.', 'warning', 0, '2026-06-11 06:42:01'),
(162, 2, 'Berhasil meminjam buku Pemrograman Web Modern (Kode: BK001-1)', 'info', 0, '2026-06-11 06:43:11'),
(163, 1, 'Siswa meminjam buku Pemrograman Web Modern (Kode: BK001-1)', 'info', 0, '2026-06-11 06:43:11'),
(164, 7, 'Siswa meminjam buku Pemrograman Web Modern (Kode: BK001-1)', 'info', 0, '2026-06-11 06:43:11'),
(165, 11, 'Berhasil meminjam buku Pemrograman Web Modern (Kode: BK001-2)', 'info', 0, '2026-06-11 07:02:14'),
(166, 1, 'Siswa meminjam buku Pemrograman Web Modern (Kode: BK001-2)', 'info', 0, '2026-06-11 07:02:14'),
(167, 7, 'Siswa meminjam buku Pemrograman Web Modern (Kode: BK001-2)', 'info', 0, '2026-06-11 07:02:14');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `buku_id` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('dipinjam','waiting','dikembalikan','terlambat') DEFAULT 'dipinjam',
  `kode_buku` varchar(20) DEFAULT NULL,
  `denda` int(11) DEFAULT 0,
  `nis` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `user_id`, `buku_id`, `tanggal_pinjam`, `tanggal_jatuh_tempo`, `tanggal_kembali`, `status`, `kode_buku`, `denda`, `nis`) VALUES
(1, 2, 1, '2026-04-23', '2026-04-30', '2026-04-23', 'dikembalikan', NULL, 0, NULL),
(2, 2, 1, '2026-04-27', '2026-05-04', '2026-04-27', 'dikembalikan', '', 0, NULL),
(3, 2, 1, '2026-04-27', '2026-05-04', NULL, 'dipinjam', 'BK001', 0, NULL),
(4, 2, 3, '2026-04-27', '2026-05-04', '2026-04-27', 'dikembalikan', 'BK003', 0, NULL),
(5, 2, 4, '2026-04-27', '2026-05-04', '2026-04-27', 'dikembalikan', 'BK004', 0, NULL),
(6, 2, 3, '2026-04-27', '2026-05-04', NULL, 'dipinjam', 'BK003', 0, NULL),
(7, 2, 2, '2026-04-27', '2026-05-04', NULL, '', 'BK002', 0, NULL),
(8, 2, 4, '2026-04-27', '2026-05-04', NULL, 'dipinjam', 'BK004', 0, NULL),
(9, 2, 3, '2026-04-27', '2026-05-04', NULL, 'dipinjam', 'BK003', 0, NULL),
(11, 2, 3, '2026-04-27', '2026-05-04', '2026-04-28', 'dikembalikan', 'BK003', 0, NULL),
(12, 2, 3, '2026-04-27', '2026-05-04', NULL, '', 'BK003', 0, NULL),
(13, 2, 3, '2026-04-27', '2026-05-04', '2026-04-27', 'dikembalikan', 'BK003', 0, NULL),
(14, 2, 1, '2026-04-27', '2026-05-04', '2026-04-27', 'dikembalikan', 'BK001', 0, NULL),
(15, 2, 1, '2026-04-27', '2026-05-04', NULL, '', 'BK001-1', 0, NULL),
(16, 2, 7, '2026-04-28', '2026-05-05', NULL, '', 'BK007-2', 0, ''),
(17, 2, 10, '2026-04-28', '2026-05-05', NULL, 'dipinjam', '', 0, ''),
(20, 2, 1, '2026-04-29', '2026-05-06', NULL, 'dipinjam', 'BK001-1', 0, ''),
(22, 2, 1, '2026-05-06', '2026-05-13', NULL, 'dipinjam', 'BK001-2', 0, ''),
(23, 2, 7, '2026-05-17', '2026-05-24', NULL, 'dipinjam', 'BK007-1', 0, ''),
(24, 2, 8, '2026-05-17', '2026-05-24', NULL, 'dipinjam', 'BK008-1', 0, ''),
(25, 2, 8, '2026-05-18', '2026-05-25', NULL, 'dipinjam', 'BK008-2', 0, ''),
(26, 2, 8, '2026-05-18', '2026-05-25', '2026-05-18', 'dikembalikan', 'BK008-3', 0, ''),
(27, 2, 8, '2026-05-18', '2026-05-25', NULL, '', 'BK008-4', 0, ''),
(28, 2, 9, '2026-05-18', '2026-05-25', NULL, '', 'BK009-1', 0, ''),
(29, 2, 9, '2026-05-18', '2026-05-25', NULL, '', 'BK009-2', 0, ''),
(30, 2, 9, '2026-05-18', '2026-05-25', NULL, '', 'BK009-3', 0, ''),
(31, 2, 1, '2026-05-18', '2026-05-25', NULL, '', 'BK001-3', 0, ''),
(32, 2, 7, '2026-05-18', '2026-05-25', '2026-05-22', '', 'BK007-2', 0, ''),
(33, 2, 9, '2026-05-18', '2026-05-25', '2026-05-18', 'dikembalikan', 'BK009-4', 0, ''),
(34, 2, 8, '2026-05-20', '2026-05-27', '2026-05-22', '', 'BK008-3', 0, ''),
(35, 2, 8, '2026-05-22', '2026-05-29', '2026-05-22', '', 'BK008-1', 0, ''),
(36, 2, 7, '2026-05-22', '2026-05-29', '2026-05-22', '', 'BM001', 0, ''),
(37, 2, 7, '2026-05-22', '2026-05-29', '2026-05-22', '', 'BK007-2', 0, ''),
(38, 2, 7, '2026-05-22', '2026-05-29', '2026-05-22', '', 'BK007-3', 0, ''),
(39, 2, 8, '2026-05-22', '2026-05-29', '2026-05-22', 'dikembalikan', 'BK008-1', 0, ''),
(40, 2, 9, '2026-05-22', '2026-05-29', NULL, 'dikembalikan', 'BK009-1', 0, ''),
(41, 2, 10, '2026-05-22', '2026-05-29', NULL, 'dikembalikan', 'EBOOK', 0, ''),
(42, 2, 8, '2026-05-22', '2026-05-29', NULL, 'waiting', 'BK008-1', 0, ''),
(43, 2, 8, '2026-05-22', '2026-05-29', NULL, 'waiting', 'BK008-2', 0, ''),
(44, 2, 7, '2026-05-22', '2026-05-29', NULL, 'waiting', 'BK007-4', 0, ''),
(45, 2, 7, '2026-05-22', '2026-05-29', NULL, 'dipinjam', 'BK007-5', 0, ''),
(46, 2, 8, '2026-05-22', '2026-05-29', NULL, 'waiting', 'BK008-3', 0, ''),
(47, 9, 10, '2026-05-24', '2026-05-31', NULL, 'waiting', 'EBOOK', 0, ''),
(48, 2, 7, '2026-05-26', '2026-06-02', NULL, 'dipinjam', 'BK007-6', 0, ''),
(49, 2, 8, '2026-05-26', '2026-06-02', NULL, 'dipinjam', 'BK008-4', 0, ''),
(50, 2, 1, '2026-06-11', '2026-06-18', NULL, 'dipinjam', 'BK001-1', 0, ''),
(51, 11, 1, '2026-06-11', '2026-06-18', NULL, 'dipinjam', 'BK001-2', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL,
  `nama_aplikasi` varchar(100) DEFAULT NULL,
  `nama_instansi` varchar(100) DEFAULT NULL,
  `lama_pinjam` int(11) DEFAULT NULL,
  `max_pinjam` int(11) DEFAULT 3,
  `perpanjang_aktif` tinyint(4) DEFAULT 1,
  `notifikasi_aktif` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama_aplikasi`, `nama_instansi`, `lama_pinjam`, `max_pinjam`, `perpanjang_aktif`, `notifikasi_aktif`) VALUES
(1, 'SmartLibrary', 'SMK PGRI 1', 6, 6, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_ebook`
--

CREATE TABLE `transaksi_ebook` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ebook_id` int(11) NOT NULL,
  `tanggal_beli` date NOT NULL,
  `status_pembayaran` enum('lunas','pending') DEFAULT 'lunas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_ebook`
--

INSERT INTO `transaksi_ebook` (`id`, `user_id`, `ebook_id`, `tanggal_beli`, `status_pembayaran`) VALUES
(1, 14, 2, '2026-05-27', ''),
(2, 2, 3, '2026-06-01', ''),
(3, 2, 2, '2026-06-01', ''),
(4, 2, 1, '2026-06-02', ''),
(5, 11, 3, '2026-06-02', ''),
(6, 11, 1, '2026-06-02', ''),
(7, 11, 2, '2026-06-11', ''),
(8, 14, 3, '2026-06-11', ''),
(9, 14, 1, '2026-06-11', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','siswa') DEFAULT 'siswa',
  `nis` varchar(20) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `nis`, `google_id`, `avatar`, `created_at`, `status`) VALUES
(1, 'Admin Perpus', 'admin@smkpgri1.sch.id', 'admin123', 'admin', NULL, NULL, NULL, '2026-04-23 00:31:19', 'aktif'),
(2, 'Siswa ', 'siswa@smkpgri1.sch.id', 'siswa123', 'siswa', '24050974035', NULL, NULL, '2026-04-23 00:31:19', 'aktif'),
(6, 'Nelsya Putri', 'putrinelsya118@gmail.com', '$2y$10$F7zgjYd/QWK.5n2XbK/RnOhCbBQ8sXqrK3Vd/O.agHXbI2RA/VS8.', 'siswa', NULL, NULL, NULL, '2026-04-28 07:25:03', 'aktif'),
(7, 'Super Admin', 'superadmin@smkpgri1.sch.id', '123456', 'super_admin', NULL, NULL, NULL, '2026-04-29 07:43:20', 'aktif'),
(8, 'Nelsya Putri', 'nelsyaputrii19@gmail.com', '$2y$10$GDH/WgAmdxShN8oYVgvK4ekM56OaR33md4xCTqga4L8dD15RVkG.6', 'siswa', NULL, NULL, NULL, '2026-05-17 14:52:53', 'aktif'),
(9, 'Icha', 'icha@gmail.com', 'Icha12345', 'siswa', '0123456', NULL, NULL, '2026-05-24 13:07:49', 'aktif'),
(10, 'Vania', 'vania@gmail.com', 'Vania123', 'siswa', '0012234', NULL, NULL, '2026-05-24 13:13:21', 'aktif'),
(11, 'Zahra', 'zahra@gmail.com', 'Zahra123', 'siswa', '001234567', NULL, NULL, '2026-05-24 13:17:00', 'nonaktif'),
(12, 'Putri', 'putri@gmail.com', 'putri123', 'siswa', '123456', NULL, NULL, '2026-05-24 13:55:42', 'aktif'),
(13, 'Nelsya Desfrita', 'Nelsya123@gmail.com', '123456', 'siswa', '3445', NULL, NULL, '2026-05-24 16:48:29', 'aktif'),
(14, 'Aurel novita', 'aurelnovita2005@gmail.com', 'Aurel123', 'siswa', '12345678', NULL, NULL, '2026-05-27 14:35:55', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aktivitas`
--
ALTER TABLE `aktivitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `buku_item`
--
ALTER TABLE `buku_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indexes for table `ebook`
--
ALTER TABLE `ebook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebook_transactions`
--
ALTER TABLE `ebook_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ebook_id` (`ebook_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi_ebook`
--
ALTER TABLE `transaksi_ebook`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ebook_id` (`ebook_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aktivitas`
--
ALTER TABLE `aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `buku_item`
--
ALTER TABLE `buku_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `ebook`
--
ALTER TABLE `ebook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ebook_transactions`
--
ALTER TABLE `ebook_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi_ebook`
--
ALTER TABLE `transaksi_ebook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `buku_item`
--
ALTER TABLE `buku_item`
  ADD CONSTRAINT `buku_item_ibfk_1` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ebook_transactions`
--
ALTER TABLE `ebook_transactions`
  ADD CONSTRAINT `ebook_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ebook_transactions_ibfk_2` FOREIGN KEY (`ebook_id`) REFERENCES `ebook` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi_ebook`
--
ALTER TABLE `transaksi_ebook`
  ADD CONSTRAINT `transaksi_ebook_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ebook_ibfk_2` FOREIGN KEY (`ebook_id`) REFERENCES `ebook` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
