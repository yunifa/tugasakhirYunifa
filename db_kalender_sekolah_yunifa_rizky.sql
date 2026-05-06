-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Bulan Mei 2026 pada 12.32
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
  `id_guru_yunifa` int(11) NOT NULL,
  `id_role_yunifa` int(11) NOT NULL,
  `email_guru_yunifa` varchar(200) DEFAULT NULL,
  `nip_yunifa` bigint(20) NOT NULL,
  `nama_yunifa` varchar(100) NOT NULL,
  `password_yunifa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru_yunifa`
--

INSERT INTO `guru_yunifa` (`id_guru_yunifa`, `id_role_yunifa`, `email_guru_yunifa`, `nip_yunifa`, `nama_yunifa`, `password_yunifa`) VALUES
(1, 7, NULL, 197104092006041010, 'Asep Suwarno, S.E., M.M.Pd.', '197104092006041010'),
(2, 7, NULL, 196810212006041006, 'A C A, S.Pd', '196810212006041006'),
(3, 6, NULL, 197009202006042005, 'Heni Hadiati, S.Pd', '197009202006042005'),
(4, 7, NULL, 196710042006042002, 'Nani Hasanah, S.Pd', '196710042006042002'),
(5, 6, NULL, 196703252007011008, 'Saliman, S.Ag., MM.Pd.', '196703252007011008'),
(6, 7, NULL, 196802042007011018, 'Engkus Kuswara, S.Pd', '196802042007011018'),
(7, 4, NULL, 196812112007012006, 'Tuti Murdayani, S.Pd., MM.', '196812112007012006'),
(8, 7, NULL, 196912312007011077, 'Yayat Ruhyat, S.Pd', '196912312007011077'),
(9, 7, NULL, 197002262007012006, 'Hana Susanti, S.Pd', '197002262007012006'),
(10, 6, NULL, 197003202007011015, 'Edy Santoso, ST., M.Pd', '197003202007011015'),
(11, 6, NULL, 197101232007011004, 'Asep Sukmana, ST., M.M', '197101232007011004'),
(12, 6, NULL, 197206302007012009, 'Dwi Cahyaningsih, S.Pd', '197206302007012009'),
(13, 7, NULL, 197207082007011009, 'Nandang, S.Ag., MM.', '197207082007011009'),
(14, 7, NULL, 197405112007012007, 'Sri Mulyati, S.Pd', '197405112007012007'),
(15, 4, NULL, 197412252007012009, 'Rohaeni Nur Eli, S.Si, M.Pd', '197412252007012009'),
(16, 5, NULL, 197608272007012007, 'Ima Nurmayanti Ramdhianingsih, M.Pd', '197608272007012007'),
(17, 7, NULL, 197611132007012010, 'Lilis Susanti, S.Pd., MM', '197611132007012010'),
(18, 6, NULL, 197702152007012005, 'Yusi Siti Masitoh, S.Pd', '197702152007012005'),
(19, 7, NULL, 197302242008012004, 'Kuswati, SE., MM.', '197302242008012004'),
(20, 6, NULL, 197405262008012004, 'Tini Hernawati, S.Pd', '197405262008012004'),
(21, 7, NULL, 197412162008011003, 'Andi Garnadi, S.Pd , M.Pd', '197412162008011003'),
(22, 7, NULL, 197505132008011006, 'Agus Basuki, S.Pd', '197505132008011006'),
(23, 1, 'endro@gmail.com', 197507122008011009, 'Endro Tri Prasetyo, S.Pd', '197507122008011009'),
(24, 7, NULL, 197507312008011007, 'Setiawan, S.Pd', '197507312008011007'),
(25, 3, NULL, 197508142008011009, 'Agus Mochamad Sopyan, S.Pd., M.Pd.', '197508142008011009'),
(26, 6, NULL, 197601232008011002, 'Rd. Rulyan Saptadji, S.Si', '197601232008011002'),
(27, 6, NULL, 197607012008011007, 'Dadan Mahdan, M.Pd', '197607012008011007'),
(28, 7, NULL, 197808222008012012, 'Syntia Mahyarani, S.Pd', '197808222008012012'),
(29, 7, NULL, 197907312008011006, 'Wisnu Ramdhani, ST., MT.', '197907312008011006'),
(30, 6, NULL, 198110062008012010, 'Nurrani Siswanti, M.Pd', '198110062008012010'),
(31, 7, NULL, 198111032008011005, 'Gugum Gumilar, ST., MT', '198111032008011005'),
(32, 6, NULL, 198205312009021002, 'Yana Cahya Kusumah, S.Kom., MT.', '198205312009021002'),
(33, 6, NULL, 198107012010011010, 'Yulius Rudiana, S.Pd., MT.', '198107012010011010'),
(34, 6, NULL, 198408062010011009, 'Rd. Irfan Santika Rahman, S.Pd., MT', '198408062010011009'),
(35, 2, NULL, 198511222010011006, 'Kusman Subarja, S.Pd., MT.', '198511222010011006'),
(36, 7, NULL, 197906042010012009, 'Dwisnaini Adriyos, S.Kom.', '197906042010012009'),
(37, 7, NULL, 198109182010012007, 'Mariam Komalawati, S.Kom', '198109182010012007'),
(38, 7, NULL, 197405202009022001, 'Utami Nurhayati, S.Si', '197405202009022001'),
(39, 6, NULL, 198208272010012015, 'Dyah Kusumaningrum, ST.', '198208272010012015'),
(40, 6, NULL, 197807052010012008, 'Julisa Irtina, S.Si, M.Pd', '197807052010012008'),
(41, 6, NULL, 198201022010011017, 'Ridwan Yanuardi, S.Pd., M.Pd', '198201022010011017'),
(42, 7, NULL, 198606112011012005, 'Asri Dena Veviani, S.Pd', '198606112011012005'),
(43, 6, NULL, 199306052019031012, 'Dede Pamungkas, S.ST.', '199306052019031012'),
(44, 7, NULL, 196912202022211001, 'Dedi Suhendar, S.Pd', '196912202022211001'),
(45, 7, NULL, 197301242022212002, 'Eneng Sayidah, S.Pd., MM.', '197301242022212002'),
(46, 7, NULL, 197309272022212006, 'Maya Karmila, S.Pd., M.Pd', '197309272022212006'),
(47, 7, NULL, 197503162022211001, 'Fajar Heriyanto, S.Pd', '197503162022211001'),
(48, 7, NULL, 197508312022212002, 'Siti Roidah, S.S', '197508312022212002'),
(49, 7, NULL, 197701132022211003, 'Dadang Rosadi, S.Pd', '197701132022211003'),
(50, 7, NULL, 197706302022211003, 'Yayat Sudrajat, S.Pd', '197706302022211003'),
(51, 7, NULL, 197801032022212012, 'Teti Suhartati, S.Pd', '197801032022212012'),
(52, 7, NULL, 197804112022212012, 'Yudith Rahayu, S.Psi., M.Pd', '197804112022212012'),
(53, 6, NULL, 197910092022211001, 'Wahyu Sumirat Sumardi, S.Pd', '197910092022211001'),
(54, 6, NULL, 198306192022211005, 'Ramdan Nurhaidir, ST.', '198306192022211005'),
(55, 7, NULL, 198409012022212018, 'Astri Putri Perdana, S.Si., M.Pd', '198409012022212018'),
(56, 7, NULL, 198409082022212027, 'Astri Hastriani, S.Pd', '198409082022212027'),
(57, 6, NULL, 198905262022212011, 'Gigin Gantini Putri, M.Pd', '198905262022212011'),
(58, 6, NULL, 198909182022212011, 'Gina Dwi Septiani, S.Pd., M.Pd', '198909182022212011'),
(59, 6, NULL, 199006132022212007, 'Raniutami Widiyanti, S.Pd.', '199006132022212007'),
(60, 7, NULL, 199111192022212022, 'Arum Pertiwi, S.Psi', '199111192022212022'),
(61, 6, NULL, 199204102022211005, 'Syaifullah, S.Ds', '199204102022211005'),
(62, 7, NULL, 199210052022212019, 'Mutiara Sabariah, S.Psi', '199210052022212019'),
(63, 6, NULL, 199307242022212006, 'Izma Yuliana, S.T.', '199307242022212006'),
(64, 6, NULL, 199310052022211013, 'Fajri Bani Fauzan, S.Sn', '199310052022211013'),
(65, 7, NULL, 199310272022212023, 'Husni Mardiah, M.Pd', '199310272022212023'),
(66, 7, NULL, 199401162022211003, 'Moch. Gani Setiawan, S.Kom', '199401162022211003'),
(67, 6, NULL, 199412022022211007, 'Durahman, S.Sos', '199412022022211007'),
(68, 7, NULL, 199501232022212005, 'Pradina Diah Aryanti, S.Pd', '199501232022212005'),
(69, 7, NULL, 199609052022212010, 'Siti Eftafiyana, S.Pd', '199609052022212010'),
(70, 7, NULL, 197701172023211003, 'Mas Yudi Riksa Kusumah, S.Pd', '197701172023211003'),
(71, 6, NULL, 198204192023211007, 'Iwan Toni Saputro, S.Pd., M.T.', '198204192023211007'),
(72, 6, NULL, 198207012023211007, 'Rahmat Santa, S.Kom., MM.', '198207012023211007'),
(73, 6, NULL, 198805062023212018, 'Ismita Ratnasari, S.ST., MM.', '198805062023212018'),
(74, 6, NULL, 198806072023211017, 'Didit Ariadi, S.ST', '198806072023211017'),
(75, 6, NULL, 199108042023212027, 'Hani Handayani, S.Pd', '199108042023212027'),
(76, 6, NULL, 199108092023212011, 'Neneng Fauziah, S.Ud', '199108092023212011'),
(77, 7, NULL, 199511152023212025, 'Yuliani, ST', '199511152023212025'),
(78, 6, NULL, 199601022023212021, 'Ratna Isnaeni Tesdy, S.Pd.', '199601022023212021'),
(79, 7, NULL, 196907092024212001, 'Tati Julaeha Karwati, S.Pd', '196907092024212001'),
(80, 6, NULL, 197908302024211005, 'Samsudin, S.T.', '197908302024211005'),
(81, 7, NULL, 198006052024212012, 'Kiki Juniantie, S.Pd', '198006052024212012'),
(82, 7, NULL, 198008242024212006, 'Irma Rahmawati,S.Pd', '198008242024212006'),
(83, 4, NULL, 198807052024212027, 'Wulan Indah Pratiwi, M.Pd', '198807052024212027'),
(84, 7, NULL, 199303102024212043, 'Susi Nur Susilawati S.Pd', '199303102024212043'),
(85, 6, NULL, 199602122024211011, 'Iqbal Ramdani, S.Tr.T.', '199602122024211011'),
(86, 7, NULL, 198305132025211003, 'Irvan Hilmi, S.S', '198305132025211003'),
(87, 7, NULL, 198606202025212104, 'Yulie Yulianti, S.Pd.i', '198606202025212104'),
(88, 7, NULL, 199401112025212119, 'Adrianty Noorhanif, S.Pd', '199401112025212119'),
(89, 7, NULL, 199111052025211134, 'Anom Jati Kusumo, S.Psi', '199111052025211134'),
(90, 7, NULL, 199207082025212134, 'Erni Anggraeni, S.Pd', '199207082025212134'),
(91, 7, NULL, 199506052025212190, 'Ririn Widiarti, S.Pd', '199506052025212190'),
(92, 7, NULL, 196701052025212001, 'Dra. Cucu Lasmanawati', '196701052025212001'),
(93, 7, NULL, 199501122025212132, 'Neneng Isti Janiarti, S.Sn', '199501122025212132'),
(94, 7, NULL, 199601062025211118, 'Fauzi Nugroho, S.Pd.', '199601062025211118'),
(95, 7, NULL, 199512222025211117, 'Ridwan Firdaus, S.Pd.', '199512222025211117'),
(96, 7, NULL, 199504222025211116, 'Daniel Adhi Hutomo, S.Tr.T', '199504222025211116'),
(97, 7, NULL, 198602012025211135, 'Yudi Wahyudi, S.Pd', '198602012025211135'),
(98, 7, NULL, 199903152025212108, 'Marsita Dahliani Putri, S.Pd.', '199903152025212108'),
(99, 7, NULL, 196805101990031012, 'Alilias, SAP., MM.', '196805101990031012'),
(100, 7, NULL, 197711282010012001, 'Wiwi Sartika Dewi', '197711282010012001'),
(101, 7, NULL, 198110272010011001, 'Saeful Anwar', '198110272010011001'),
(102, 7, NULL, 197711102025211078, 'Deny Kurniawan', '197711102025211078'),
(103, 7, NULL, 198704072025212101, 'Indah Nurtikawati, S.Pd', '198704072025212101'),
(104, 7, NULL, 199007222025212068, 'Eris Risnadewi', '199007222025212068'),
(105, 7, NULL, 199305152025212179, 'Yupdina, S.Pd', '199305152025212179'),
(106, 7, NULL, 199310072025211078, 'Topan Sopiyan, S.Tr.T', '199310072025211078'),
(107, 7, NULL, 198803262025211072, 'Ryadi Alan Saputra, S.Pd.', '198803262025211072'),
(108, 7, NULL, 198908122025211107, 'Jaenudin', '198908122025211107'),
(109, 7, NULL, 198210022025211059, 'Dian Darmawan', '198210022025211059'),
(110, 7, NULL, 198305062025211065, 'Dodih Wahyudin', '198305062025211065'),
(111, 7, NULL, 197204102025211037, 'Nanang Suherlan', '197204102025211037'),
(112, 7, NULL, 198807152025211186, 'Ridwan', '198807152025211186'),
(113, 7, NULL, 199811162025211053, 'Riyan', '199811162025211053'),
(114, 7, NULL, 197804132025212030, 'Yuyun Sugiawati', '197804132025212030'),
(115, 7, NULL, 199103182025211087, 'Cucu Sopiani, S.Pd', '199103182025211087'),
(116, 7, NULL, 198206172025212053, 'Yuni Lastriani, S.IP', '198206172025212053'),
(117, 7, NULL, 199701262025211073, 'Nizar Purnama Sidik', '199701262025211073'),
(118, 7, NULL, 199509092025211159, 'Rian Riansyah', '199509092025211159'),
(119, 7, NULL, 198010212025211048, 'Wawan Kurniawan', '198010212025211048'),
(120, 7, NULL, 200105132025211030, 'Rizky Aditya Rinaldy', '200105132025211030'),
(121, 7, NULL, 200205202025211009, 'Firman Maulana', '200205202025211009'),
(122, 7, NULL, 200204042025211025, 'Aqmal Danar Firdaus', '200204042025211025'),
(123, 7, NULL, 197712172025211027, 'Wawan Setiawan', '197712172025211027'),
(124, 7, NULL, 199509062025212089, 'Alviana Prakastiwi', '199509062025212089'),
(125, 7, NULL, 198001112025211068, 'Agus Mulyadi', '198001112025211068'),
(126, 7, NULL, 198912272025211126, 'Teguh Suprayitno', '198912272025211126'),
(127, 7, NULL, 198205272025211060, 'Edi Kusmayadi', '198205272025211060'),
(128, 8, 'admin@gmail.com', 123456, 'admin', '$2y$10$3dUL9ewZAYlfoIS1KOkRIOQuqLbzPflz8qbmYR0TnVo.sDdxEVh/y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan_yunifa`
--

CREATE TABLE `kegiatan_yunifa` (
  `id_kegiatan_yunifa` int(11) NOT NULL,
  `judul_yunifa` varchar(150) NOT NULL,
  `deskripsi_yunifa` text DEFAULT NULL,
  `tanggal_mulai_yunifa` date NOT NULL,
  `waktu_mulai_yunifa` time DEFAULT NULL,
  `tanggal_selesai_yunifa` date NOT NULL,
  `waktu_selesai_yunifa` time DEFAULT NULL,
  `seharian_yunifa` enum('ya','tidak') DEFAULT 'tidak',
  `hak_akses_yunifa` enum('guru','internal','publik') NOT NULL,
  `id_guru_yunifa` int(11) NOT NULL,
  `status_kunci_yunifa` enum('terbuka','terkunci') DEFAULT 'terbuka',
  `dibuat_pada_yunifa` datetime DEFAULT current_timestamp(),
  `warna_yunifa` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kegiatan_yunifa`
--

INSERT INTO `kegiatan_yunifa` (`id_kegiatan_yunifa`, `judul_yunifa`, `deskripsi_yunifa`, `tanggal_mulai_yunifa`, `waktu_mulai_yunifa`, `tanggal_selesai_yunifa`, `waktu_selesai_yunifa`, `seharian_yunifa`, `hak_akses_yunifa`, `id_guru_yunifa`, `status_kunci_yunifa`, `dibuat_pada_yunifa`, `warna_yunifa`) VALUES
(5, 'tka', 'kelas xii', '2026-04-06', '08:49:00', '2026-04-10', '17:49:00', 'tidak', 'publik', 6, 'terbuka', '2026-04-16 20:50:05', '#bbbfc3'),
(6, 'mbg', 'df', '2026-04-14', '08:50:00', '2026-04-14', '10:50:00', 'tidak', 'internal', 6, 'terbuka', '2026-04-16 20:51:12', '#03594f'),
(7, 'rapat', 'sf', '2026-04-16', '00:00:00', '2026-04-16', '00:00:00', 'ya', 'guru', 6, 'terbuka', '2026-04-16 20:51:41', '#92455c'),
(8, 'senam', 'dtgf', '2026-04-17', '07:52:00', '2026-04-17', '09:52:00', 'tidak', 'guru', 6, 'terbuka', '2026-04-16 20:52:49', '#7f7834'),
(11, 'festival', 'dsfdf', '2026-04-22', '00:00:00', '2026-04-22', '00:00:00', 'ya', 'guru', 23, 'terbuka', '2026-04-22 10:03:33', '#37d78d'),
(12, 'kdskad', 'nfms', '2026-04-26', '00:00:00', '2026-04-26', '00:00:00', 'ya', 'guru', 23, 'terbuka', '2026-04-26 16:14:21', '#1a73e8'),
(13, 'ms dma', 'samd ma', '2026-04-27', '00:00:00', '2026-04-30', '00:00:00', 'ya', 'publik', 23, 'terbuka', '2026-04-26 16:15:03', '#e53935'),
(14, 'samdms', 'asd', '2026-04-27', '00:00:00', '2026-04-27', '00:00:00', 'ya', 'publik', 23, 'terbuka', '2026-04-26 16:15:24', '#43a047'),
(15, 'sd', 'sd as', '2026-04-27', '00:00:00', '2026-04-27', '00:00:00', 'ya', 'publik', 23, 'terbuka', '2026-04-26 16:15:36', '#fb8c00'),
(16, 'mbg', 'smda', '2026-04-27', '00:00:00', '2026-04-27', '00:00:00', 'ya', 'internal', 23, 'terbuka', '2026-04-26 16:15:51', '#00acc1'),
(17, 'sdnmassdsn d', 'asnd', '2026-04-27', '00:00:00', '2026-04-27', '00:00:00', 'ya', 'internal', 23, 'terbuka', '2026-04-26 16:16:09', '#0b8043'),
(18, 'seharian', 'p', '2026-04-27', '00:00:00', '2026-04-27', '00:00:00', 'ya', 'internal', 23, 'terbuka', '2026-04-26 16:21:06', '#1a73e8'),
(19, 'seharian lagi', '', '2026-04-27', '00:00:00', '2026-04-27', '23:59:00', 'ya', 'publik', 23, 'terbuka', '2026-04-26 16:24:12', '#fb8c00'),
(20, 'kegiatan', 'asd', '2026-04-30', '00:00:00', '2026-04-30', '23:59:00', 'ya', 'guru', 3, 'terbuka', '2026-04-29 10:28:09', '#43a047'),
(21, 'ms dma', 'ds', '2026-05-01', '11:18:00', '2026-05-01', '11:20:00', 'tidak', 'internal', 23, 'terbuka', '2026-04-29 11:19:09', '#f4511e');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_yunifa`
--

CREATE TABLE `role_yunifa` (
  `id_role_yunifa` int(11) NOT NULL,
  `role_yunifa` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `role_yunifa`
--

INSERT INTO `role_yunifa` (`id_role_yunifa`, `role_yunifa`) VALUES
(1, 'wakil kepala sekolah bidang kesiswaan'),
(2, 'wakil kepala sekolah bidang kurikulum'),
(3, 'wakil kepala sekolah bidang hubungan industri dan masyarakat'),
(4, 'wakil kepala sekolah bidang manajemen mutu'),
(5, 'wakil kepala sekolah bidang sarana dan prasarana'),
(6, 'staff'),
(7, 'guru'),
(8, 'admin'),
(9, 'siswa & ortu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa_yunifa`
--

CREATE TABLE `siswa_yunifa` (
  `id_siswa_yunifa` int(11) NOT NULL,
  `id_role_yunifa` int(11) NOT NULL,
  `nis_yunifa` int(10) NOT NULL,
  `nama_yunifa` varchar(255) NOT NULL,
  `kelas_yunifa` varchar(255) NOT NULL,
  `email_siswa_yunifa` varchar(100) DEFAULT NULL,
  `password_siswa_yunifa` varchar(255) NOT NULL,
  `email_ortu_yunifa` varchar(100) DEFAULT NULL,
  `password_ortu_yunifa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa_yunifa`
--

INSERT INTO `siswa_yunifa` (`id_siswa_yunifa`, `id_role_yunifa`, `nis_yunifa`, `nama_yunifa`, `kelas_yunifa`, `email_siswa_yunifa`, `password_siswa_yunifa`, `email_ortu_yunifa`, `password_ortu_yunifa`) VALUES
(1, 9, 10251001, 'ABDUL AZIZ', 'X TEKNIK MEKATRONIKA - A', NULL, '10251001', 'ortu@gmail.com', 'ortu'),
(2, 9, 10251002, 'ADLY ZHAFIR ZAHRANDITYA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251002', NULL, ''),
(3, 9, 10251003, 'ADRIAN RAFI ALTAP BASYAR', 'X TEKNIK MEKATRONIKA - A', NULL, '10251003', NULL, ''),
(4, 9, 10251004, 'ALIA ZASKIA WAHYUNI', 'X TEKNIK MEKATRONIKA - A', NULL, '10251004', NULL, ''),
(5, 9, 10251005, 'ARYO AKBAR', 'X TEKNIK MEKATRONIKA - A', NULL, '10251005', NULL, ''),
(6, 9, 10251006, 'ASHILE SHAUGHI MUMTAZZA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251006', NULL, ''),
(7, 9, 10251007, 'AZAHRA SABILA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251007', NULL, ''),
(8, 9, 10251008, 'BIMA ARYA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251008', NULL, ''),
(9, 9, 10251009, 'CHANDRA FEBRIAN PURNAMA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251009', NULL, ''),
(10, 9, 10251010, 'CHRISTOPHER JOSEPH TANDAYU', 'X TEKNIK MEKATRONIKA - A', NULL, '10251010', NULL, ''),
(11, 9, 10251011, 'DANU ZUHAIR AZIZ', 'X TEKNIK MEKATRONIKA - A', NULL, '10251011', NULL, ''),
(12, 9, 10251012, 'DZAKI AFLAH MUHAFIZH', 'X TEKNIK MEKATRONIKA - A', NULL, '10251012', NULL, ''),
(13, 9, 10251013, 'FADHILAN ALANSYAH', 'X TEKNIK MEKATRONIKA - A', NULL, '10251013', NULL, ''),
(14, 9, 10251014, 'FAHMI NURUL DZIHNI', 'X TEKNIK MEKATRONIKA - A', NULL, '10251014', NULL, ''),
(15, 9, 10251015, 'FAIZ JUANI MAULANA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251015', NULL, ''),
(16, 9, 10251016, 'FARREL GHANI KURNIAWAN', 'X TEKNIK MEKATRONIKA - A', NULL, '10251016', NULL, ''),
(17, 9, 10251017, 'FATURAHMAN JULI PRASETYA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251017', NULL, ''),
(18, 9, 10251018, 'FIRAAS FAHDAEN', 'X TEKNIK MEKATRONIKA - A', NULL, '10251018', NULL, ''),
(19, 9, 10251019, 'GIAN MAOELANA JAELANI', 'X TEKNIK MEKATRONIKA - A', NULL, '10251019', NULL, ''),
(20, 9, 10251020, 'GILVAN DITIA PRATAMA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251020', NULL, ''),
(21, 9, 10251021, 'HADID HARDIANSAH', 'X TEKNIK MEKATRONIKA - A', NULL, '10251021', NULL, ''),
(22, 9, 10251023, 'KHAINDRA PRATAMA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251023', NULL, ''),
(23, 9, 10251024, 'LINGGA NADHIF ARKANANTA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251024', NULL, ''),
(24, 9, 10251025, 'MOCHAMAD ZAKY RIZKY UMBARA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251025', NULL, ''),
(25, 9, 10251026, 'MUHAMAD DANAR JUNIAR\'RAHMAN', 'X TEKNIK MEKATRONIKA - A', NULL, '10251026', NULL, ''),
(26, 9, 10251027, 'MUHAMAD KHANAFI ZAKARIA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251027', NULL, ''),
(27, 9, 10251028, 'MUHAMAD RIZAL HIMAWAN', 'X TEKNIK MEKATRONIKA - A', NULL, '10251028', NULL, ''),
(28, 9, 10251029, 'MUHAMAD YUSUF ISMAIL', 'X TEKNIK MEKATRONIKA - A', NULL, '10251029', NULL, ''),
(29, 9, 10251030, 'MUHAMMAD AL RIDWAN', 'X TEKNIK MEKATRONIKA - A', NULL, '10251030', NULL, ''),
(30, 9, 10251031, 'MUHAMMAD ARIF RIZQI SYABAN', 'X TEKNIK MEKATRONIKA - A', NULL, '10251031', NULL, ''),
(31, 9, 10251032, 'MUHAMMAD FATHIR AL GHIFARI', 'X TEKNIK MEKATRONIKA - A', NULL, '10251032', NULL, ''),
(32, 9, 10251033, 'MUHAMMAD FATHURRAHMAN SHIDIQ', 'X TEKNIK MEKATRONIKA - A', NULL, '10251033', NULL, ''),
(33, 9, 10251034, 'MUHAMMAD KHAIRUL AL HASBY', 'X TEKNIK MEKATRONIKA - A', NULL, '10251034', NULL, ''),
(34, 9, 10251035, 'NAYLA KHOERUNISA', 'X TEKNIK MEKATRONIKA - A', NULL, '10251035', NULL, ''),
(35, 9, 10251036, 'REVAN MUHAMMAD AZWAR', 'X TEKNIK MEKATRONIKA - A', NULL, '10251036', NULL, ''),
(36, 9, 10251037, 'RHEZA AGUNG FIRMANSYAH', 'X TEKNIK MEKATRONIKA - A', NULL, '10251037', NULL, ''),
(37, 9, 10251038, 'RIZKI SUTIAWAN', 'X TEKNIK MEKATRONIKA - A', NULL, '10251038', NULL, ''),
(38, 9, 10251039, 'SIGIT RAMADHAN', 'X TEKNIK MEKATRONIKA - A', NULL, '10251039', NULL, ''),
(39, 9, 10251040, 'VIDIKA RAMADHAN SETIAWAN', 'X TEKNIK MEKATRONIKA - A', NULL, '10251040', NULL, ''),
(40, 9, 10251022, 'KEYZA NAZWA SUKMARAMADHAN', 'X TEKNIK MEKATRONIKA - A', NULL, '10251022', NULL, ''),
(41, 9, 10251041, 'AHMAD BAHTIAR', 'X TEKNIK MEKATRONIKA - B', NULL, '10251041', NULL, ''),
(42, 9, 10251042, 'AKBAR IKHSAN NURSYA\'BAN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251042', NULL, ''),
(43, 9, 10251043, 'AKVA FAZIL ERDIANA', 'X TEKNIK MEKATRONIKA - B', NULL, '10251043', NULL, ''),
(44, 9, 10251044, 'ARIEF RAHMAN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251044', NULL, ''),
(45, 9, 10251045, 'AZHARI DWI SAPUTRA', 'X TEKNIK MEKATRONIKA - B', NULL, '10251045', NULL, ''),
(46, 9, 10251046, 'DANDI YUDHA WARDHANI', 'X TEKNIK MEKATRONIKA - B', NULL, '10251046', NULL, ''),
(47, 9, 10251047, 'DIAZ ARFEN SYAHREZA', 'X TEKNIK MEKATRONIKA - B', NULL, '10251047', NULL, ''),
(48, 9, 10251048, 'DIMAS FATURROHMAN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251048', NULL, ''),
(49, 9, 10251049, 'FAARIS FAHDAEN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251049', NULL, ''),
(50, 9, 10251050, 'FAHRI RAMDANI', 'X TEKNIK MEKATRONIKA - B', NULL, '10251050', NULL, ''),
(51, 9, 10251051, 'FAJRI FALAH AZIKRY', 'X TEKNIK MEKATRONIKA - B', NULL, '10251051', NULL, ''),
(52, 9, 10251052, 'FAJRY HERMAWAN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251052', NULL, ''),
(53, 9, 10251053, 'FAKHRI FADILAH', 'X TEKNIK MEKATRONIKA - B', NULL, '10251053', NULL, ''),
(54, 9, 10251054, 'FAREL MUHAMAD ANDRYAWAN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251054', NULL, ''),
(55, 9, 10251055, 'HASNA TALITHA', 'X TEKNIK MEKATRONIKA - B', NULL, '10251055', NULL, ''),
(56, 9, 10251056, 'HERI KURNIAWAN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251056', NULL, ''),
(57, 9, 10251057, 'IRFAN RIAN FATHURRAHMAN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251057', NULL, ''),
(58, 9, 10251058, 'KENAN ATHAYA DWI GUNAWAN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251058', NULL, ''),
(59, 9, 10251059, 'MALIK PUTRA RUSDIANA', 'X TEKNIK MEKATRONIKA - B', NULL, '10251059', NULL, ''),
(60, 9, 10251060, 'MIRZA RIZKY RADISHA', 'X TEKNIK MEKATRONIKA - B', NULL, '10251060', NULL, ''),
(61, 9, 10251061, 'MOHAMMAD JEREMY FADHIL AKASYAH', 'X TEKNIK MEKATRONIKA - B', NULL, '10251061', NULL, ''),
(62, 9, 10251062, 'MOHAMMAD MUGHNI MUZAKI', 'X TEKNIK MEKATRONIKA - B', NULL, '10251062', NULL, ''),
(63, 9, 10251063, 'MUHAMAD MARVEL', 'X TEKNIK MEKATRONIKA - B', NULL, '10251063', NULL, ''),
(64, 9, 10251064, 'MUHAMAD RAIHAN FABIAN IDRIS', 'X TEKNIK MEKATRONIKA - B', NULL, '10251064', NULL, ''),
(65, 9, 10251065, 'MUHAMAD REVANO JEPRI ALPAJRI', 'X TEKNIK MEKATRONIKA - B', NULL, '10251065', NULL, ''),
(66, 9, 10251066, 'MUHAMMAD FAISHAL ANSHARY', 'X TEKNIK MEKATRONIKA - B', NULL, '10251066', NULL, ''),
(67, 9, 10251067, 'MUHAMMAD HAIKAL MU\'AIBI', 'X TEKNIK MEKATRONIKA - B', NULL, '10251067', NULL, ''),
(68, 9, 10251068, 'MUHAMMAD RIFQI SYAFIQ ILMANSYAH', 'X TEKNIK MEKATRONIKA - B', NULL, '10251068', NULL, ''),
(69, 9, 10251069, 'NAFIZA AYU', 'X TEKNIK MEKATRONIKA - B', NULL, '10251069', NULL, ''),
(70, 9, 10251070, 'OBBY HERLYNO', 'X TEKNIK MEKATRONIKA - B', NULL, '10251070', NULL, ''),
(71, 9, 10251071, 'RARA ANGELINA PADANG', 'X TEKNIK MEKATRONIKA - B', NULL, '10251071', NULL, ''),
(72, 9, 10251072, 'RAZAAN ARUNATA SUNANDAR', 'X TEKNIK MEKATRONIKA - B', NULL, '10251072', NULL, ''),
(73, 9, 10251073, 'RIFQI PRATAMA', 'X TEKNIK MEKATRONIKA - B', NULL, '10251073', NULL, ''),
(74, 9, 10251074, 'RIZKY ADRIAN LESMANA', 'X TEKNIK MEKATRONIKA - B', NULL, '10251074', NULL, ''),
(75, 9, 10251075, 'RIZKY BUDI PRATAMA', 'X TEKNIK MEKATRONIKA - B', NULL, '10251075', NULL, ''),
(76, 9, 10251076, 'RIZKY MAULANA WIJAYA', 'X TEKNIK MEKATRONIKA - B', NULL, '10251076', NULL, ''),
(77, 9, 10251077, 'SAVIRA NURHALISSA AL ZAHRA', 'X TEKNIK MEKATRONIKA - B', NULL, '10251077', NULL, ''),
(78, 9, 10251078, 'TOMI ALVIAN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251078', NULL, ''),
(79, 9, 10251079, 'WILDAN HIMAWAN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251079', NULL, ''),
(80, 9, 10251080, 'YHUDA DWIDARMAWAN', 'X TEKNIK MEKATRONIKA - B', NULL, '10251080', NULL, ''),
(81, 9, 10243320, 'YUNIFA RIZKY', 'XI RPL- B', 'ortu@gmail.com', '10243320', NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `guru_yunifa`
--
ALTER TABLE `guru_yunifa`
  ADD PRIMARY KEY (`id_guru_yunifa`),
  ADD UNIQUE KEY `nip` (`nip_yunifa`),
  ADD UNIQUE KEY `email_guru` (`email_guru_yunifa`),
  ADD KEY `fk_role` (`id_role_yunifa`);

--
-- Indeks untuk tabel `kegiatan_yunifa`
--
ALTER TABLE `kegiatan_yunifa`
  ADD PRIMARY KEY (`id_kegiatan_yunifa`),
  ADD KEY `fk_kegiatan_guru` (`id_guru_yunifa`);

--
-- Indeks untuk tabel `role_yunifa`
--
ALTER TABLE `role_yunifa`
  ADD PRIMARY KEY (`id_role_yunifa`);

--
-- Indeks untuk tabel `siswa_yunifa`
--
ALTER TABLE `siswa_yunifa`
  ADD PRIMARY KEY (`id_siswa_yunifa`),
  ADD UNIQUE KEY `nis` (`nis_yunifa`),
  ADD UNIQUE KEY `email_siswa` (`email_siswa_yunifa`),
  ADD KEY `fk_role_siswa` (`id_role_yunifa`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `guru_yunifa`
--
ALTER TABLE `guru_yunifa`
  MODIFY `id_guru_yunifa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT untuk tabel `kegiatan_yunifa`
--
ALTER TABLE `kegiatan_yunifa`
  MODIFY `id_kegiatan_yunifa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `role_yunifa`
--
ALTER TABLE `role_yunifa`
  MODIFY `id_role_yunifa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `siswa_yunifa`
--
ALTER TABLE `siswa_yunifa`
  MODIFY `id_siswa_yunifa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `guru_yunifa`
--
ALTER TABLE `guru_yunifa`
  ADD CONSTRAINT `fk_role_yunifa` FOREIGN KEY (`id_role_yunifa`) REFERENCES `role_yunifa` (`id_role_yunifa`);

--
-- Ketidakleluasaan untuk tabel `kegiatan_yunifa`
--
ALTER TABLE `kegiatan_yunifa`
  ADD CONSTRAINT `fk_kegiatan_guru_yunifa` FOREIGN KEY (`id_guru_yunifa`) REFERENCES `guru_yunifa` (`id_guru_yunifa`);

--
-- Ketidakleluasaan untuk tabel `siswa_yunifa`
--
ALTER TABLE `siswa_yunifa`
  ADD CONSTRAINT `fk_role_siswa_yunifa` FOREIGN KEY (`id_role_yunifa`) REFERENCES `role_yunifa` (`id_role_yunifa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
