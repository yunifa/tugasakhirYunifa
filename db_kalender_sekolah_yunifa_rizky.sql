-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Feb 2026 pada 06.46
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kalender_sekolah_yunifa_rizky`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru_yunifa`
--

CREATE TABLE `guru_yunifa` (
  `id_guru` int(11) NOT NULL,
  `nip` bigint(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('wakil kepala sekolah bidang kesiswaan','wakil kepala sekolah bidang kurikulum','wakil kepala sekolah bidang sarana dan prasarana','wakil kepala sekolah bidang hubungan industri dan masyarakat','wakil kepala sekolah bidang manajemen mutu','staff','guru') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru_yunifa`
--

INSERT INTO `guru_yunifa` (`id_guru`, `nip`, `nama`, `password`, `role`) VALUES
(1, 198006052024212012, 'Kiki Juniantie, S.Pd', 'gurukiki', 'guru'),
(2, 196812112007012006, 'Tuti Murdayani, S.Pd., MM', 'wakatuti', 'wakil kepala sekolah bidang manajemen mutu'),
(3, 1985112220100110066, 'Kusman Subarja, S.P., M.T.', 'wakakusman', 'wakil kepala sekolah bidang kurikulum');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan_yunifa`
--

CREATE TABLE `kegiatan_yunifa` (
  `id_kegiatan` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `tanggal_selesai` date NOT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `seharian` enum('ya','tidak') DEFAULT 'tidak',
  `hak_akses` enum('guru','internal','publik') NOT NULL,
  `id_guru` int(11) NOT NULL,
  `status_kunci` enum('terbuka','terkunci') DEFAULT 'terbuka',
  `dibuat_pada` datetime DEFAULT current_timestamp(),
  `warna` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kegiatan_yunifa`
--

INSERT INTO `kegiatan_yunifa` (`id_kegiatan`, `judul`, `deskripsi`, `tanggal_mulai`, `waktu_mulai`, `tanggal_selesai`, `waktu_selesai`, `seharian`, `hak_akses`, `id_guru`, `status_kunci`, `dibuat_pada`, `warna`) VALUES
(7, 'festival', 'sjsj', '2026-02-12', '09:00:00', '2026-02-12', '16:00:00', 'tidak', 'publik', 2, 'terbuka', '2026-02-12 12:13:20', '#56bd5d'),
(8, 'festival', 'erjwk', '2026-02-12', '12:17:00', '2026-02-12', '16:17:00', 'tidak', 'internal', 2, 'terbuka', '2026-02-12 12:13:59', '#08121b');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa_yunifa`
--

CREATE TABLE `siswa_yunifa` (
  `id_siswa` int(11) NOT NULL,
  `nis` int(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `email_siswa` varchar(100) DEFAULT NULL,
  `password_siswa` varchar(255) NOT NULL,
  `email_ortu` varchar(100) DEFAULT NULL,
  `password_ortu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa_yunifa`
--

INSERT INTO `siswa_yunifa` (`id_siswa`, `nis`, `nama`, `kelas`, `email_siswa`, `password_siswa`, `email_ortu`, `password_ortu`) VALUES
(1, 10243320, 'Yunifa Rizky', 'XI RPL B', 'siswa1@gmail.com', 'siswa1', 'ortu1@gmail.com', 'ortu1');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `guru_yunifa`
--
ALTER TABLE `guru_yunifa`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indeks untuk tabel `kegiatan_yunifa`
--
ALTER TABLE `kegiatan_yunifa`
  ADD PRIMARY KEY (`id_kegiatan`),
  ADD KEY `fk_kegiatan_guru` (`id_guru`);

--
-- Indeks untuk tabel `siswa_yunifa`
--
ALTER TABLE `siswa_yunifa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `guru_yunifa`
--
ALTER TABLE `guru_yunifa`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kegiatan_yunifa`
--
ALTER TABLE `kegiatan_yunifa`
  MODIFY `id_kegiatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `siswa_yunifa`
--
ALTER TABLE `siswa_yunifa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kegiatan_yunifa`
--
ALTER TABLE `kegiatan_yunifa`
  ADD CONSTRAINT `fk_kegiatan_guru` FOREIGN KEY (`id_guru`) REFERENCES `guru_yunifa` (`id_guru`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
