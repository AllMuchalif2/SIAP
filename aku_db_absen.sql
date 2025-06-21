-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Jun 21, 2025 at 01:58 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aku_db_absen`
--

-- --------------------------------------------------------

--
-- Table structure for table `absen`
--

CREATE TABLE `absen` (
  `id_absen` int NOT NULL,
  `id_siswa` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` enum('Hadir','Izin','Sakit','Alpa') COLLATE utf8mb4_general_ci DEFAULT 'Alpa',
  `waktu` time DEFAULT NULL,
  `waktu_pulang` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absen`
--

INSERT INTO `absen` (`id_absen`, `id_siswa`, `tanggal`, `keterangan`, `waktu`, `waktu_pulang`) VALUES
(1, '0022-putra', '2025-04-23', 'Hadir', '11:55:57', NULL),
(2, '0054-furqon', '2025-04-23', 'Hadir', '11:55:32', NULL),
(3, '0054-furqon', '2025-04-25', 'Alpa', NULL, NULL),
(4, '0022-putra', '2025-04-30', 'Hadir', '11:45:50', NULL),
(5, '0054-furqon', '2025-04-30', 'Izin', '12:50:10', NULL),
(6, '1', '2025-04-30', 'Sakit', NULL, NULL),
(7, '2', '2025-04-30', 'Hadir', '11:19:28', NULL),
(8, '0022-putra', '2025-05-02', 'Alpa', NULL, NULL),
(9, '0054-furqon', '2025-05-02', 'Hadir', '12:58:09', NULL),
(10, '1', '2025-05-02', 'Sakit', NULL, NULL),
(11, '2', '2025-05-02', 'Hadir', '11:38:15', NULL),
(12, '0022-putra', '2025-05-05', 'Hadir', '15:40:53', NULL),
(13, '0054-furqon', '2025-05-05', 'Izin', NULL, NULL),
(14, '1', '2025-05-05', 'Sakit', NULL, NULL),
(15, '2', '2025-05-05', 'Hadir', '08:26:17', NULL),
(16, '0022-putra', '2025-05-06', 'Hadir', '08:24:53', NULL),
(17, '0054-furqon', '2025-05-06', 'Hadir', '08:24:46', NULL),
(18, '1', '2025-05-06', 'Izin', NULL, NULL),
(19, '2', '2025-05-06', 'Sakit', NULL, NULL),
(20, '0022-putra', '2025-05-07', 'Izin', NULL, NULL),
(21, '0054-furqon', '2025-05-07', 'Sakit', NULL, NULL),
(22, '1', '2025-05-07', 'Alpa', NULL, NULL),
(23, '2', '2025-05-07', 'Alpa', NULL, NULL),
(24, '0054-furqon', '2025-05-31', 'Hadir', '09:33:05', '11:01:55'),
(25, '0022-putra', '2025-05-31', 'Sakit', NULL, NULL),
(26, '1', '2025-05-31', 'Alpa', NULL, NULL),
(27, '2', '2025-05-31', 'Alpa', NULL, NULL),
(28, '0022-putra', '2025-06-18', 'Alpa', NULL, NULL),
(29, '0054-furqon', '2025-06-18', 'Alpa', NULL, NULL),
(30, '1', '2025-06-18', 'Alpa', NULL, NULL),
(31, '2', '2025-06-18', 'Alpa', NULL, NULL),
(32, '0022-putra', '2025-06-20', 'Alpa', NULL, NULL),
(33, '0054-furqon', '2025-06-20', 'Izin', NULL, NULL),
(34, '1', '2025-06-20', 'Alpa', NULL, NULL),
(35, '2', '2025-06-20', 'Alpa', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `absensi_log`
--

CREATE TABLE `absensi_log` (
  `id` int NOT NULL,
  `id_absen` int DEFAULT NULL,
  `id_siswa` varchar(20) DEFAULT NULL,
  `nama_siswa` varchar(100) DEFAULT NULL,
  `keterangan_lama` varchar(20) DEFAULT NULL,
  `keterangan_baru` varchar(20) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `waktu_lama` time DEFAULT NULL,
  `waktu_baru` time DEFAULT NULL,
  `waktu_pulang_lama` time DEFAULT NULL,
  `waktu_pulang_baru` time DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `absensi_log`
--

INSERT INTO `absensi_log` (`id`, `id_absen`, `id_siswa`, `nama_siswa`, `keterangan_lama`, `keterangan_baru`, `username`, `waktu_lama`, `waktu_baru`, `waktu_pulang_lama`, `waktu_pulang_baru`, `updated_at`) VALUES
(1, 20, '0022-putra', 'putra wong bandengan', 'Izin', 'Izin', 'arnama', NULL, NULL, '00:00:00', '00:00:00', '2025-05-07 07:57:00'),
(2, 21, '0054-furqon', 'furqon kraton', 'Alpa', 'Sakit', 'arnama', NULL, NULL, '00:00:00', '00:00:00', '2025-05-07 07:57:06'),
(3, 25, '0022-putra', 'putra wong bandengan', 'Izin', 'Sakit', 'arnama', NULL, NULL, NULL, NULL, '2025-05-31 10:10:24'),
(4, 33, '0054-furqon', 'furqon kraton', 'Alpa', 'Izin', 'arnama', NULL, NULL, NULL, NULL, '2025-06-20 10:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `sekolah` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama`, `sekolah`, `status`) VALUES
('0022-putra', 'putra wong bandengan', 'SMA Negeri 2', 'active'),
('0054-furqon', 'furqon kraton', 'SMKN 2 PKL', 'active'),
('1', '111', '11', 'active'),
('2', '222', '22', 'active'),
('asep', 'Asep', 'Asep', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','asisten') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'asisten'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `password`, `role`) VALUES
(0, 'arnama', 'arnama', '$2y$10$bKrnNGrBwks.fNOzPqUxPeZ.Edquw8KWlRF/GwNtwmkKLeh.jQWgG', 'admin'),
(1, 'asisten', 'asisten', '$2y$10$5SZd5jbPkrPNiZg6g/0zNe9JZW9OjGicnXUZkS1iye.RDPyiMXXJm', 'asisten'),
(4, '24.230.0044', 'Fithnan Riatan', '$2y$10$ZY/nsJvaa.LYLNGwe2Mj7OQVn6CF87wiBVIWKc6TAvFYRs.IZjC5i', 'admin'),
(7, 'albar', 'albar jmk48', '$2y$10$3QfDCCqhjJmmcl9TAvaQH.bN3vsTwhf9nQmnAGc3OenTU526kI.rW', 'asisten');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`id_absen`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `absensi_log`
--
ALTER TABLE `absensi_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absen`
--
ALTER TABLE `absen`
  MODIFY `id_absen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `absensi_log`
--
ALTER TABLE `absensi_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absen`
--
ALTER TABLE `absen`
  ADD CONSTRAINT `absen_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
