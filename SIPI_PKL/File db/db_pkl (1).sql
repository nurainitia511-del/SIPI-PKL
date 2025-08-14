-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 06 Agu 2025 pada 04.26
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pkl`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `foto` varchar(255) DEFAULT 'default-profile.png',
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id_guru`, `nama`, `nip`, `foto`, `password`) VALUES
(1, 'Surya, S.Pd', '234234', '219986.png', 'e10adc3949ba59abbe56e057f20f883e'),
(6, 'Adam, S.Kom', '123321321555', '219986.png', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Struktur dari tabel `instansi`
--

CREATE TABLE `instansi` (
  `id_instansi` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `kontak` varchar(50) NOT NULL,
  `bidang_instansi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `instansi`
--

INSERT INTO `instansi` (`id_instansi`, `nama`, `alamat`, `kontak`, `bidang_instansi`) VALUES
(1, 'PT. Teknologi Nusantara', 'Jl. Merdeka No.10, Jakarta', '081234567890', 'Teknologi IT'),
(3, 'Startup Inovasi', 'Jl. Diponegoro No.15, Surabaya', '082134567890', 'Startup');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusan`
--

CREATE TABLE `jurusan` (
  `id` int(11) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jurusan`
--

INSERT INTO `jurusan` (`id`, `nama_jurusan`) VALUES
(4, 'Akuntansi'),
(3, 'Multimedia'),
(1, 'Rekayasa Perangkat Lunak'),
(2, 'Teknik Komputer dan Jaringan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_pkl`
--

CREATE TABLE `laporan_pkl` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` text NOT NULL,
  `kendala` text NOT NULL,
  `solusi` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `laporan_pkl`
--

INSERT INTO `laporan_pkl` (`id`, `id_siswa`, `tanggal`, `kegiatan`, `kendala`, `solusi`, `foto`) VALUES
(1, 6, '2025-03-24', 'sds1', 'dsdsd1', 'sdsds1', 'images.jpg'),
(2, 6, '2025-03-23', 'dsd', 'sds', 'sdsd', 'images.jpg'),
(3, 7, '2025-08-06', 'Bertemu bapak kepala dinas ominfo', 'tidak ada', 'tidak ada', '20122018023419_0.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekomendasi_instansi`
--

CREATE TABLE `rekomendasi_instansi` (
  `id_rekomendasi_instansi` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_instansi` int(11) NOT NULL,
  `status` enum('Pending','Disetujui','Tidak Disetujui') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `rekomendasi_instansi`
--

INSERT INTO `rekomendasi_instansi` (`id_rekomendasi_instansi`, `id_siswa`, `id_instansi`, `status`) VALUES
(37, 7, 3, 'Disetujui');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_rekomendasi_instansi`
--

CREATE TABLE `riwayat_rekomendasi_instansi` (
  `id_riwayat` int(11) NOT NULL,
  `id_rekomendasi_instansi` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_instansi` int(11) NOT NULL,
  `status` enum('pending','disetujui','ditolak') NOT NULL,
  `tanggal_proses` timestamp NULL DEFAULT current_timestamp(),
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `riwayat_rekomendasi_instansi`
--

INSERT INTO `riwayat_rekomendasi_instansi` (`id_riwayat`, `id_rekomendasi_instansi`, `id_siswa`, `id_instansi`, `status`, `tanggal_proses`, `id_users`) VALUES
(5, 37, 7, 3, 'disetujui', '2025-04-13 00:47:34', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `foto` varchar(255) DEFAULT 'default-profile.png',
  `jurusan_id` int(11) NOT NULL,
  `id_guru` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama`, `nis`, `kelas`, `password`, `foto`, `jurusan_id`, `id_guru`) VALUES
(5, 'danang', '15675', 'X MM 2', '202cb962ac59075b964b07152d234b70', '1740711743_219986.png', 3, 1),
(6, 'wawan', '234343', 'XII RPL 1', '202cb962ac59075b964b07152d234b70', '1740712004_219986.png', 4, 6),
(7, 'ijan', '123123', 'IX', '202cb962ac59075b964b07152d234b70', '1742798959_219986.png', 4, 1),
(8, 'gogo', '1231234', 'IX', '202cb962ac59075b964b07152d234b70', '1742798969_219986.png', 4, 1),
(9, 'doni', '12312345', 'X MM 2', '202cb962ac59075b964b07152d234b70', '1742799264_219986.png', 4, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT 'default-admin.png',
  `role` enum('admin','siswa','guru') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_users`, `username`, `password`, `foto`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', '600168.png', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indeks untuk tabel `instansi`
--
ALTER TABLE `instansi`
  ADD PRIMARY KEY (`id_instansi`);

--
-- Indeks untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_jurusan` (`nama_jurusan`);

--
-- Indeks untuk tabel `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indeks untuk tabel `rekomendasi_instansi`
--
ALTER TABLE `rekomendasi_instansi`
  ADD PRIMARY KEY (`id_rekomendasi_instansi`),
  ADD KEY `id_tempat` (`id_instansi`);

--
-- Indeks untuk tabel `riwayat_rekomendasi_instansi`
--
ALTER TABLE `riwayat_rekomendasi_instansi`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_rekomendasi_instansi` (`id_rekomendasi_instansi`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_instansi` (`id_instansi`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD KEY `jurusan_id` (`jurusan_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `instansi`
--
ALTER TABLE `instansi`
  MODIFY `id_instansi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `rekomendasi_instansi`
--
ALTER TABLE `rekomendasi_instansi`
  MODIFY `id_rekomendasi_instansi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `riwayat_rekomendasi_instansi`
--
ALTER TABLE `riwayat_rekomendasi_instansi`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  ADD CONSTRAINT `laporan_pkl_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rekomendasi_instansi`
--
ALTER TABLE `rekomendasi_instansi`
  ADD CONSTRAINT `rekomendasi_instansi_ibfk_1` FOREIGN KEY (`id_instansi`) REFERENCES `instansi` (`id_instansi`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `riwayat_rekomendasi_instansi`
--
ALTER TABLE `riwayat_rekomendasi_instansi`
  ADD CONSTRAINT `riwayat_rekomendasi_instansi_ibfk_1` FOREIGN KEY (`id_rekomendasi_instansi`) REFERENCES `rekomendasi_instansi` (`id_rekomendasi_instansi`),
  ADD CONSTRAINT `riwayat_rekomendasi_instansi_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `riwayat_rekomendasi_instansi_ibfk_3` FOREIGN KEY (`id_instansi`) REFERENCES `instansi` (`id_instansi`);

--
-- Ketidakleluasaan untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
