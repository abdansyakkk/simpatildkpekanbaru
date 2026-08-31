-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2025 at 03:12 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projek_pktbt`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_biaya_denda`
--

CREATE TABLE `tbl_biaya_denda` (
  `id_biaya_denda` int(11) NOT NULL,
  `harga_denda` varchar(255) NOT NULL,
  `stat` varchar(255) NOT NULL,
  `tgl_tetap` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_biaya_denda`
--

INSERT INTO `tbl_biaya_denda` (`id_biaya_denda`, `harga_denda`, `stat`, `tgl_tetap`) VALUES
(1, '4000', 'Aktif', '2019-11-23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_buku`
--

CREATE TABLE `tbl_buku` (
  `id_buku` int(11) NOT NULL,
  `buku_id` varchar(255) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_rak` int(11) NOT NULL,
  `sampul` varchar(255) DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `lampiran` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `penerbit` varchar(255) DEFAULT NULL,
  `pengarang` varchar(255) DEFAULT NULL,
  `thn_buku` varchar(255) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `jml` int(11) DEFAULT NULL,
  `tgl_masuk` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_buku`
--

INSERT INTO `tbl_buku` (`id_buku`, `buku_id`, `id_kategori`, `id_rak`, `sampul`, `isbn`, `lampiran`, `title`, `penerbit`, `pengarang`, `thn_buku`, `isi`, `jml`, `tgl_masuk`) VALUES
(8, 'BK008', 2, 1, '0', '132-123-234-231', '0', 'CARA MUDAH BELAJAR PEMROGRAMAN C++', 'INFORMATIKA BANDUNG', 'BUDI RAHARJO ', '2012', '<table class=\"table table-bordered\" style=\"background-color: rgb(255, 255, 255); width: 653px; color: rgb(51, 51, 51);\"><tbody><tr><td style=\"padding: 8px; line-height: 1.42857; border-color: rgb(244, 244, 244);\">Tipe Buku</td><td style=\"padding: 8px; line-height: 1.42857; border-color: rgb(244, 244, 244);\">Kertas</td></tr><tr><td style=\"padding: 8px; line-height: 1.42857; border-color: rgb(244, 244, 244);\">Bahasa</td><td style=\"padding: 8px; line-height: 1.42857; border-color: rgb(244, 244, 244);\">Indonesia</td></tr></tbody></table>', 23, '2019-11-23 11:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_denda`
--

CREATE TABLE `tbl_denda` (
  `id_denda` int(11) NOT NULL,
  `pinjam_id` varchar(255) NOT NULL,
  `denda` varchar(255) NOT NULL,
  `lama_waktu` int(11) NOT NULL,
  `tgl_denda` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_denda`
--

INSERT INTO `tbl_denda` (`id_denda`, `pinjam_id`, `denda`, `lama_waktu`, `tgl_denda`) VALUES
(3, 'PJ001', '0', 0, '2020-05-20'),
(5, 'PJ009', '0', 0, '2020-05-20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_detail_pelatihan`
--

CREATE TABLE `tbl_detail_pelatihan` (
  `id_detail_pelatihan` int(11) NOT NULL,
  `id_pelatihan` int(11) NOT NULL,
  `id_penanggung_jawab` int(11) DEFAULT NULL,
  `id_ketua_panitia` int(11) DEFAULT NULL,
  `id_akademis` int(11) DEFAULT NULL,
  `id_keuangan` int(11) DEFAULT NULL,
  `id_administrasi` int(11) DEFAULT NULL,
  `id_wi_1` int(11) DEFAULT NULL,
  `id_wi_2` int(11) DEFAULT NULL,
  `id_wi_3` int(11) DEFAULT NULL,
  `id_wi_rapat_kelulusan` int(11) DEFAULT NULL,
  `id_wi_4` int(11) DEFAULT NULL,
  `id_pengajar_1` int(11) DEFAULT NULL,
  `id_pengajar_2` int(11) DEFAULT NULL,
  `id_pengajar_3` int(11) DEFAULT NULL,
  `jumlah_wi_pengajar` int(11) DEFAULT 0,
  `jumlah_pendidikan_wi_s1` int(11) DEFAULT 0,
  `jumlah_pendidikan_wi_s2` int(11) DEFAULT 0,
  `jumlah_pendidikan_wi_s3` int(11) DEFAULT 0,
  `jumlah_peserta` int(11) DEFAULT 0,
  `jumlah_lulus` int(11) DEFAULT NULL,
  `jumlah_tidak_lulus` int(11) DEFAULT NULL,
  `jabatan_peserta` text DEFAULT NULL,
  `jumlah_peserta_asn` int(11) DEFAULT 0,
  `jumlah_peserta_non_asn` int(11) DEFAULT 0,
  `jumlah_peserta_laki` int(11) DEFAULT 0,
  `jumlah_peserta_wanita` int(11) DEFAULT 0,
  `jumlah_pendidikan_peserta_sma` int(11) DEFAULT 0,
  `jumlah_pendidikan_peserta_s1` int(11) DEFAULT 0,
  `jumlah_pendidikan_peserta_s2` int(11) DEFAULT 0,
  `jumlah_pendidikan_peserta_s3` int(11) DEFAULT 0,
  `rab` decimal(18,2) DEFAULT NULL,
  `realisasi` decimal(18,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_detail_pelatihan`
--

INSERT INTO `tbl_detail_pelatihan` (`id_detail_pelatihan`, `id_pelatihan`, `id_penanggung_jawab`, `id_ketua_panitia`, `id_akademis`, `id_keuangan`, `id_administrasi`, `id_wi_1`, `id_wi_2`, `id_wi_3`, `id_wi_rapat_kelulusan`, `id_wi_4`, `id_pengajar_1`, `id_pengajar_2`, `id_pengajar_3`, `jumlah_wi_pengajar`, `jumlah_pendidikan_wi_s1`, `jumlah_pendidikan_wi_s2`, `jumlah_pendidikan_wi_s3`, `jumlah_peserta`, `jumlah_lulus`, `jumlah_tidak_lulus`, `jabatan_peserta`, `jumlah_peserta_asn`, `jumlah_peserta_non_asn`, `jumlah_peserta_laki`, `jumlah_peserta_wanita`, `jumlah_pendidikan_peserta_sma`, `jumlah_pendidikan_peserta_s1`, `jumlah_pendidikan_peserta_s2`, `jumlah_pendidikan_peserta_s3`, `rab`, `realisasi`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 4, 5, NULL, NULL, 11, 12, NULL, NULL, NULL, 13, 14, 1, 6, 0, 2, 3, 40, 40, 0, 'Guru', 30, 10, 20, 20, 20, 10, 5, 5, '230000000000.00', '230000000000.00', '2025-07-19 07:39:11', '2025-07-26 08:39:34', NULL),
(3, 4, 1, 4, 5, 6, 8, 15, 16, 17, 15, NULL, 1, 18, 19, 6, 0, 3, 3, 30, 22, 8, 'Pengurus Zakat', 17, 13, 17, 13, 4, 19, 7, 0, '230000000000.00', '230000000000.00', '2025-07-26 08:32:39', '2025-07-26 08:39:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dokumen`
--

CREATE TABLE `tbl_dokumen` (
  `id_dokumen` int(11) NOT NULL,
  `nama_dokumen` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_dokumen`
--

INSERT INTO `tbl_dokumen` (`id_dokumen`, `nama_dokumen`, `deskripsi`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Daftar Hadir Rapat Persiapan', NULL, '2025-07-31 08:11:15', '2025-08-01 01:50:54', NULL),
(2, 'Notulen Rapat Persiapan', NULL, '2025-07-31 08:11:15', '2025-07-31 08:11:15', NULL),
(3, 'Surat Pemberitahuan', '', '2025-07-31 09:08:28', '2025-08-01 01:28:58', NULL),
(4, 'Surat Permohonan Widyaiswara', '', '2025-08-01 01:29:12', '2025-08-01 01:46:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kategori`
--

CREATE TABLE `tbl_kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_kategori`
--

INSERT INTO `tbl_kategori` (`id_kategori`, `nama_kategori`) VALUES
(2, 'Pemrograman');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login`
--

CREATE TABLE `tbl_login` (
  `id_login` int(11) NOT NULL,
  `anggota_id` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tgl_lahir` varchar(255) NOT NULL,
  `jenkel` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tgl_bergabung` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_login`
--

INSERT INTO `tbl_login` (`id_login`, `anggota_id`, `user`, `pass`, `level`, `nama`, `tempat_lahir`, `tgl_lahir`, `jenkel`, `alamat`, `telepon`, `email`, `tgl_bergabung`, `foto`) VALUES
(1, 'AG001', 'anang', '202cb962ac59075b964b07152d234b70', 'Petugas', 'Admin', 'Pekanbaru', '1999-04-05', 'Laki-Laki', 'Pekanbaru', '089618173609', 'fauzan1892@codekop.com', '2019-11-20', 'user_1752396603.png'),
(2, 'AG002', 'fauzan', '202cb962ac59075b964b07152d234b70', 'Anggota', 'Fauzan', 'Bekasi', '1998-11-18', 'Laki-Laki', 'Bekasi Barat', '08123123185', 'fauzanfalah21@gmail.com', '2019-11-21', 'user_1589911243.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_materi_pelatihan`
--

CREATE TABLE `tbl_materi_pelatihan` (
  `id_materi_pelatihan` int(11) NOT NULL,
  `id_pelatihan` int(11) NOT NULL,
  `jumlah_jp` int(11) DEFAULT 0,
  `jp_kel_dasar` int(11) DEFAULT 0,
  `jp_kel_inti` int(11) DEFAULT 0,
  `jp_kel_penunjang` int(11) DEFAULT 0,
  `nama_mata_pelatihan_kel_dasar` mediumtext DEFAULT NULL,
  `nama_mata_pelatihan_kel_inti` mediumtext DEFAULT NULL,
  `nama_mata_pelatihan_kel_penunjang` mediumtext DEFAULT NULL,
  `latar_belakang` mediumtext DEFAULT NULL,
  `tujuan_pelatihan` mediumtext DEFAULT NULL,
  `tujuan_kursil` mediumtext DEFAULT NULL,
  `asal_kursil` mediumtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_materi_pelatihan`
--

INSERT INTO `tbl_materi_pelatihan` (`id_materi_pelatihan`, `id_pelatihan`, `jumlah_jp`, `jp_kel_dasar`, `jp_kel_inti`, `jp_kel_penunjang`, `nama_mata_pelatihan_kel_dasar`, `nama_mata_pelatihan_kel_inti`, `nama_mata_pelatihan_kel_penunjang`, `latar_belakang`, `tujuan_pelatihan`, `tujuan_kursil`, `asal_kursil`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 60, 9, 40, 11, '1. Mata \r\n2. gigi', '1. Mata \r\n2. gigi', '1. Mata \r\n2. gigi', 'Publikasi ilmiah merupakan jantung dari dunia akademik. Melalui publikasi, para peneliti dapat berbagi temuan-temuan baru, mempromosikan ide-ide inovatif, dan berkontribusi pada perkembangan ilmu pengetahuan. Publikasi ilmiah juga menjadi salah satu indikator kualitas penelitian dan menjadi syarat penting dalam karir akademik.', 'dalam menyusun dan mempublikasikan karya ilmiah.', 'Peserta pelatihan mampu menganalisis sistematika makalah presentasi pada forum ilmiah.\r\nPeserta pelatihan dapat menganalisis sistematika makalah tinjauan Ilmiah/Best Practice.\r\nPeserta pelatihan mampu menganalisis sistematika makalah Buku Teks Pelajaran, Buku Pengayaan, Karya Terjemahan dan Buku Pedoman Guru.\r\nPeserta pelatihan dapat menyusun laporan hasil penelitian.', 'Pusdiklat Tenaga Teknis Pendidikan dan Keagamaan', '2025-07-27 01:49:23', '2025-07-27 01:49:23', NULL),
(2, 4, 60, 9, 44, 7, '1 Moderasi Beragama dan Pembangunan Nasional\r\n2 Nilai-Nilai Dasar Sumber Daya Manusia (SDM) Kementerian Agama \r\n3 Sistem Pelatihan dan Pengembangan SDM Kementerian Agama', '1 Peraturan Perundang Undangan Zakat\r\n2 Fiqh Zakat\r\n3 Perhitungan Zakat\r\n4 Fundraising Zakat\r\n5 Sistem Akuntansi dan Pelaporan Zakat\r\n6 Zakat dan Pajak\r\n7 Pengelolaan zakat  di BAZNAS  \r\n', '1 Overview \r\n2 Building Learning Commitment\r\n3 Evaluasi Program\r\n4 RencanaTindakLanjut\r\n5 Ujian\r\n', 'Zakat merupakan salah satu pilar utama dalam ajaran Islam yang berperan penting dalam mengentaskan kemiskinan dan mewujudkan kesejahteraan umat. Di era modern ini, pengelolaan zakat menjadi semakin kompleks dan membutuhkan pendekatan manajemen yang profesional, akuntabel, dan berbasis teknologi agar dapat memberikan dampak yang maksimal kepada penerima manfaat.\r\n\r\nNamun, tantangan dalam pengelolaan zakat sering kali muncul, mulai dari kurangnya pemahaman manajerial di kalangan pengelola zakat, rendahnya tingkat literasi masyarakat tentang pentingnya zakat, hingga pengelolaan yang belum sepenuhnya memanfaatkan teknologi informasi. Oleh karena itu, diperlukan upaya peningkatan kapasitas sumber daya manusia (SDM) pengelola zakat, baik di lembaga zakat pemerintah maupun swasta.\r\n\r\nMelihat kebutuhan tersebut, pelaksanaan Pelatihan Jarak Jauh (PJJ) Manajemen Zakat menjadi salah satu solusi strategis untuk menjawab tantangan ini. Dengan memanfaatkan platform digital, pelatihan ini dirancang untuk memberikan pemahaman komprehensif tentang prinsip, regulasi, dan praktik terbaik dalam manajemen zakat, serta mendorong pengelola zakat agar lebih profesional dan inovatif.', 'Melaksanakan pengelolaan zakat secara profesional', '1. Peningkatan Pemahaman Tentang Zakat\r\nMemahami konsep dasar zakat, jenis-jenis zakat (zakat mal dan zakat fitrah), dan dalil-dalil syariah yang mendasarinya.\r\n\r\n2. Pengelolaan Dana Zakat yang Efektif\r\nMembekali peserta dengan keterampilan teknis untuk mengelola dana zakat, mulai dari pengumpulan, pendistribusian, hingga pelaporan.\r\n\r\n3.Peningkatan Kapasitas Lembaga Pengelola Zakat (LPZ)\r\nMemperkuat manajemen lembaga zakat agar lebih profesional, transparan, dan akuntabel.\r\n\r\n4.Strategi Pengumpulan Zakat\r\nMengajarkan teknik penggalangan dana zakat yang inovatif dan berbasis teknologi untuk menjangkau lebih banyak muzakki (pemberi zakat).\r\n\r\n5.Distribusi Zakat yang Tepat Sasaran\r\nMemberikan wawasan mengenai metode pendistribusian zakat yang adil, tepat sasaran, dan berdampak besar bagi mustahik (penerima zakat).\r\n\r\n6. Peningkatan Kesejahteraan Umat\r\nMenanamkan nilai-nilai bahwa zakat adalah instrumen pemberdayaan ekonomi umat, bukan sekadar kewajiban agama.\r\n\r\n7. Kepatuhan Syariah\r\nMenjamin bahwa pengelolaan zakat dilakukan sesuai dengan kaidah syariah dan hukum positif yang berlaku, seperti Undang-Undang Pengelolaan Zakat.', 'Pusdiklat Tenaga Teknis Pendidikan dan Keagamaan', '2025-07-27 02:49:00', '2025-07-27 02:49:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pegawai`
--

CREATE TABLE `tbl_pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `NIP` varchar(25) DEFAULT NULL,
  `jabatan` int(11) NOT NULL,
  `asal_satker` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_pegawai`
--

INSERT INTO `tbl_pegawai` (`id_pegawai`, `nama`, `NIP`, `jabatan`, `asal_satker`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Drs. H. Khrisfison, S.IPI., M.Pd', '196702161994031005', 1, 'Loka Diklat Keagamaan Pekanbaru', '2025-07-13 10:03:47', '2025-07-13 10:03:47', NULL),
(2, 'Muhammad Fauzi Fayyad, S.Kom.', '199912192025051003', 2, 'Loka Diklat Keagamaan Pekanbaru', '2025-07-16 07:46:04', '2025-07-16 07:50:10', NULL),
(3, 'H. Aprianto S.Ag., M.A..', '197603012003121004', 1, 'Loka Diklat Keagamaan Pekanbaru', '2025-07-16 07:57:22', '2025-07-16 07:57:34', NULL),
(4, 'Eko Oktaviadi, S.H.', '198210282009121005', 5, 'Loka Diklat Keagamaan Pekanbaru', '2025-07-19 04:28:19', '2025-07-19 04:28:19', NULL),
(5, 'Aryati, S.Pd.I', '198112252005012025', 7, 'Loka Diklat Keagamaan Pekanbaru', '2025-07-19 04:29:04', '2025-07-19 04:29:04', NULL),
(6, 'Azrul Pajri, S.P', '', 8, 'Loka Diklat Keagamaan Pekanbaru', '2025-07-19 04:48:53', '2025-07-19 04:48:53', NULL),
(8, 'Lani Clara Refiarika, A. Md', '', 8, 'Loka Diklat Keagamaan Pekanbaru', '2025-07-19 04:52:35', '2025-07-19 04:52:35', NULL),
(9, 'Ecal Ade Yansyah, S.Pd', '', 9, 'Loka Diklat Keagamaan Pekanbaru', '2025-07-19 04:55:36', '2025-07-19 04:55:36', NULL),
(10, 'Nur Aisyah, S.Sos', '199512212024212042', 9, 'Loka Diklat Keagamaan Pekanbaru', '2025-07-19 04:56:18', '2025-07-19 04:56:18', NULL),
(11, 'Dr. Hj. Siti Aminah, M.A.', '196909131994032001', 10, 'Balai Diklat Keagamaan Semarang', '2025-07-19 07:13:00', '2025-07-19 07:13:00', NULL),
(12, 'Arsyil waritsman, S.Pd., M.Pd.', '', 11, 'Loka Diklat Keagamaan Ambon', '2025-07-19 07:15:25', '2025-07-19 07:15:25', NULL),
(13, 'Dr. H. Mahyudin, MA.', '197006131995031001', 13, 'Kantor Wilayah Kementerian Agama Provinsi Riau', '2025-07-19 07:18:31', '2025-07-19 07:18:31', NULL),
(14, 'Dr. H. Muliardi, M.Pd.', '196910011997031004', 14, 'Kantor Wilayah Kementerian Agama Provinsi Riau', '2025-07-19 07:20:11', '2025-07-19 07:20:11', NULL),
(15, 'Makmun Hidayat, S.Pd., M.Pd.', '', 10, 'Balai Diklat Keagamaan Surabaya', '2025-07-19 07:22:31', '2025-07-19 07:22:31', NULL),
(16, 'Khobibah, S.Ag, M.A., M.HI.', '197101272005012001', 10, 'Balai Diklat Keagamaan Surabaya', '2025-07-19 07:23:24', '2025-07-19 07:23:24', NULL),
(17, 'Dr. Yessi Fitri SE. Ak, M.Si, CA', '197609242006042002', 18, 'UIN Syarif Hidayatullah', '2025-07-19 07:26:14', '2025-07-19 07:26:14', NULL),
(18, 'Dr. H. Muchammad Toha, S.Ag., M.Si.', '196910282002121002', 15, 'Balai Diklat Keagamaan Semarang', '2025-07-19 07:29:14', '2025-07-19 07:29:14', NULL),
(19, 'Prof. Dr. H. Samsul Nizar, M.Ag.', '197010241997031001', 16, 'STAIN Bengkalis', '2025-07-19 07:31:05', '2025-07-19 07:31:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pelatihan`
--

CREATE TABLE `tbl_pelatihan` (
  `id_pelatihan` int(11) NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `nama_pelatihan` varchar(255) NOT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kab_kota` varchar(100) DEFAULT NULL,
  `tempat` varchar(255) DEFAULT NULL,
  `tanggal_mulai_pelatihan` date NOT NULL,
  `tanggal_selesai_pelatihan` date NOT NULL,
  `bulan_ttd_lap` varchar(20) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `hari_tanggal_pembukaan` date DEFAULT NULL,
  `waktu_pembukaan` time DEFAULT NULL,
  `id_pejabat_pembuka` int(11) DEFAULT NULL,
  `id_role_pembuka` int(11) DEFAULT NULL,
  `hari_tanggal_penutupan` date DEFAULT NULL,
  `waktu_penutupan` time DEFAULT NULL,
  `id_pejabat_penutup` int(11) DEFAULT NULL,
  `id_role_penutup` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_pelatihan`
--

INSERT INTO `tbl_pelatihan` (`id_pelatihan`, `nama_kegiatan`, `nama_pelatihan`, `provinsi`, `kab_kota`, `tempat`, `tanggal_mulai_pelatihan`, `tanggal_selesai_pelatihan`, `bulan_ttd_lap`, `tahun`, `hari_tanggal_pembukaan`, `waktu_pembukaan`, `id_pejabat_pembuka`, `id_role_pembuka`, `hari_tanggal_penutupan`, `waktu_penutupan`, `id_pejabat_penutup`, `id_role_penutup`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pelatihan Jarak Jauh (PJJ) Publikasi Ilmiah Angkatan III', 'Publikasi Ilmiah Angkatan III', 'Riau', 'Kota Pekanbaru', 'Wilayah Kerja Kanwil Kemenag Provinsi Riau dan Kanwil Kemenag Provinsi Kepulauan Riau', '2024-06-19', '2024-06-29', 'Juni', 2024, '2024-06-19', '09:00:00', 1, 1, '2024-06-29', '16:00:00', 1, 1, '2025-07-13 10:05:05', '2025-07-16 04:25:24', NULL),
(4, 'Pelatihan Jarak Jauh (PJJ) Manajemen Zakat Angkatan I', 'Manajemen Zakat Angkatan I', 'Riau dan Kepulauan Riau', 'Kota Pekanbaru', 'Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru', '2024-08-27', '2024-09-03', 'September', 2024, '2024-08-27', '08:00:00', 1, 1, '2024-09-04', '09:30:00', 1, 1, '2025-07-17 01:37:34', '2025-07-26 05:47:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pelatihan_activity`
--

CREATE TABLE `tbl_pelatihan_activity` (
  `id_activity` int(11) NOT NULL,
  `id_pelatihan` int(11) NOT NULL,
  `sesi_ke` tinyint(3) UNSIGNED NOT NULL CHECK (`sesi_ke` between 1 and 15),
  `day_ke` tinyint(3) UNSIGNED NOT NULL CHECK (`day_ke` between 1 and 30),
  `nama_kegiatan` varchar(255) NOT NULL,
  `id_narasumber` int(11) DEFAULT NULL,
  `activity_desc` text DEFAULT NULL,
  `tanggal_activity` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_pelatihan_activity`
--

INSERT INTO `tbl_pelatihan_activity` (`id_activity`, `id_pelatihan`, `sesi_ke`, `day_ke`, `nama_kegiatan`, `id_narasumber`, `activity_desc`, `tanggal_activity`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 1, 1, 'Pembukaan Pelatihan PJJ', 3, '', '2025-08-01', '07:30:00', '16:00:00', '2025-08-01 08:00:23', '2025-08-01 08:12:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pelatihan_dokumen`
--

CREATE TABLE `tbl_pelatihan_dokumen` (
  `id_pelatihan_dokumen` int(11) NOT NULL,
  `id_pelatihan` int(11) NOT NULL,
  `id_dokumen` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `tanggal_upload` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_pelatihan_dokumen`
--

INSERT INTO `tbl_pelatihan_dokumen` (`id_pelatihan_dokumen`, `id_pelatihan`, `id_dokumen`, `file_path`, `tanggal_upload`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 1, '', '2025-08-01', '2025-08-01 02:54:30', '2025-08-01 03:50:35', NULL),
(2, 4, 4, '', '2025-08-01', '2025-08-01 03:09:05', '2025-08-01 04:06:28', NULL),
(3, 4, 3, 'd692e069174af0dd294117e29f9907f5.pdf', '2025-08-01', '2025-08-01 03:25:55', '2025-08-01 04:13:34', NULL),
(4, 4, 2, '265a0d1fc5fa5f0bd87e457a22a1c33c.pdf', '2025-08-01', '2025-08-01 03:57:56', '2025-08-01 07:03:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pelatihan_foto`
--

CREATE TABLE `tbl_pelatihan_foto` (
  `id_foto` int(11) NOT NULL,
  `id_activity` int(11) NOT NULL,
  `foto_path` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal_foto` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_pelatihan_foto`
--

INSERT INTO `tbl_pelatihan_foto` (`id_foto`, `id_activity`, `foto_path`, `keterangan`, `tanggal_foto`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 1, 'assets/foto_kegiatan/1754036605_Screenshot 2025-07-31 103758.png', '', '2025-08-01', '2025-08-01 08:23:25', '2025-08-01 08:23:25', NULL),
(6, 1, 'assets/foto_kegiatan/1754036605_Screenshot 2025-07-30 132159.png', '', '2025-08-01', '2025-08-01 08:23:25', '2025-08-01 08:23:25', NULL),
(7, 1, 'assets/foto_kegiatan/1754036605_Screenshot 2025-07-30 132100.png', '', '2025-08-01', '2025-08-01 08:23:25', '2025-08-01 08:23:25', NULL),
(8, 1, 'assets/foto_kegiatan/1754036711_Screenshot 2025-07-29 213828.png', '', '2025-08-01', '2025-08-01 08:25:11', '2025-08-01 08:25:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pinjam`
--

CREATE TABLE `tbl_pinjam` (
  `id_pinjam` int(11) NOT NULL,
  `pinjam_id` varchar(255) NOT NULL,
  `anggota_id` varchar(255) NOT NULL,
  `buku_id` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `tgl_pinjam` varchar(255) NOT NULL,
  `lama_pinjam` int(11) NOT NULL,
  `tgl_balik` varchar(255) NOT NULL,
  `tgl_kembali` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_pinjam`
--

INSERT INTO `tbl_pinjam` (`id_pinjam`, `pinjam_id`, `anggota_id`, `buku_id`, `status`, `tgl_pinjam`, `lama_pinjam`, `tgl_balik`, `tgl_kembali`) VALUES
(8, 'PJ001', 'AG002', 'BK008', 'Di Kembalikan', '2020-05-19', 1, '2020-05-20', '2020-05-20'),
(10, 'PJ009', 'AG002', 'BK008', 'Di Kembalikan', '2020-05-20', 1, '2020-05-21', '2020-05-20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rak`
--

CREATE TABLE `tbl_rak` (
  `id_rak` int(11) NOT NULL,
  `nama_rak` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_rak`
--

INSERT INTO `tbl_rak` (`id_rak`, `nama_rak`) VALUES
(1, 'Rak Buku 1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_role`
--

CREATE TABLE `tbl_role` (
  `id_role` int(11) NOT NULL,
  `nama_role` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_role`
--

INSERT INTO `tbl_role` (`id_role`, `nama_role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Kepala Loka Diklat Keagamaan Pekanbaru', '2025-07-13 10:01:06', '2025-07-13 10:01:06', NULL),
(2, 'Pranata Komputer Ahli Pertama', '2025-07-15 17:06:30', '2025-07-16 00:56:43', NULL),
(3, 'Pranata Humas Ahli Pertama', '2025-07-15 17:17:45', '2025-07-17 01:30:29', NULL),
(4, 'Ketua Tim Pelatihan LDK Pekanbaru', '2025-07-19 04:22:22', '2025-07-19 04:22:22', NULL),
(5, 'Ketua Tim Penjaminan Mutu LDK Pekanbaru', '2025-07-19 04:22:36', '2025-07-19 04:22:36', NULL),
(6, 'Ketua Tim Tata Usaha LDK Pekanbaru', '2025-07-19 04:22:52', '2025-07-19 04:22:52', NULL),
(7, 'Pelaksana Analis Laporan Keuangan LDK Pekanbaru', '2025-07-19 04:23:44', '2025-07-19 04:23:44', NULL),
(8, 'Pegawai Pemerintah Non Pegawai Negeri (PPNPN)', '2025-07-19 04:26:28', '2025-07-19 04:26:28', NULL),
(9, 'Pegawai Pemerintah dengan Perjanjian Kerja (PPPK)', '2025-07-19 04:27:13', '2025-07-19 04:27:13', NULL),
(10, 'Widyaiswara Ahli Madya', '2025-07-19 04:58:03', '2025-07-19 04:58:03', NULL),
(11, 'Widyaiswara Ahli Pertama', '2025-07-19 05:00:25', '2025-07-19 05:00:25', NULL),
(12, 'Widyaiswara Ahli Muda', '2025-07-19 05:00:34', '2025-07-19 05:00:34', NULL),
(13, 'Kepala Kantor Wilayah Kementerian Agama Provinsi Riau', '2025-07-19 05:06:10', '2025-07-19 05:06:10', NULL),
(14, 'Kepala Bidang Pendidikan Madrasah Kanwil Kemenag Provinsi Riau', '2025-07-19 05:06:44', '2025-07-19 05:06:44', NULL),
(15, 'Kepala Balai Diklat Keagamaan Semarang', '2025-07-19 06:59:13', '2025-07-19 06:59:13', NULL),
(16, 'Guru Besar STAIN Bengkalis', '2025-07-19 07:00:32', '2025-07-19 07:00:32', NULL),
(17, 'Kepala Pusdiklat Tenaga Teknis Pendidikan dan Keagamaan', '2025-07-19 07:06:40', '2025-07-19 07:06:40', NULL),
(18, 'Lektor', '2025-07-19 07:25:07', '2025-07-19 07:25:07', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_biaya_denda`
--
ALTER TABLE `tbl_biaya_denda`
  ADD PRIMARY KEY (`id_biaya_denda`);

--
-- Indexes for table `tbl_buku`
--
ALTER TABLE `tbl_buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `tbl_denda`
--
ALTER TABLE `tbl_denda`
  ADD PRIMARY KEY (`id_denda`);

--
-- Indexes for table `tbl_detail_pelatihan`
--
ALTER TABLE `tbl_detail_pelatihan`
  ADD PRIMARY KEY (`id_detail_pelatihan`),
  ADD KEY `fk_detail_pelatihan` (`id_pelatihan`),
  ADD KEY `fk_penanggung_jawab` (`id_penanggung_jawab`),
  ADD KEY `fk_ketua_panitia` (`id_ketua_panitia`),
  ADD KEY `fk_akademis` (`id_akademis`),
  ADD KEY `fk_keuangan` (`id_keuangan`),
  ADD KEY `fk_administrasi` (`id_administrasi`),
  ADD KEY `fk_wi_1` (`id_wi_1`),
  ADD KEY `fk_wi_2` (`id_wi_2`),
  ADD KEY `fk_wi_3` (`id_wi_3`),
  ADD KEY `fk_wi_4` (`id_wi_4`),
  ADD KEY `fk_pengajar_1` (`id_pengajar_1`),
  ADD KEY `fk_pengajar_2` (`id_pengajar_2`),
  ADD KEY `fk_pengajar_3` (`id_pengajar_3`),
  ADD KEY `fk_wi_rapat_kelulusan` (`id_wi_rapat_kelulusan`);

--
-- Indexes for table `tbl_dokumen`
--
ALTER TABLE `tbl_dokumen`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indexes for table `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD PRIMARY KEY (`id_login`);

--
-- Indexes for table `tbl_materi_pelatihan`
--
ALTER TABLE `tbl_materi_pelatihan`
  ADD PRIMARY KEY (`id_materi_pelatihan`),
  ADD KEY `fk_pelatihan_materi` (`id_pelatihan`);

--
-- Indexes for table `tbl_pegawai`
--
ALTER TABLE `tbl_pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD KEY `fk_jabatan` (`jabatan`);

--
-- Indexes for table `tbl_pelatihan`
--
ALTER TABLE `tbl_pelatihan`
  ADD PRIMARY KEY (`id_pelatihan`),
  ADD KEY `fk_pejabat_pembuka` (`id_pejabat_pembuka`),
  ADD KEY `fk_role_pembuka` (`id_role_pembuka`),
  ADD KEY `fk_pejabat_penutup` (`id_pejabat_penutup`),
  ADD KEY `fk_role_penutup` (`id_role_penutup`);

--
-- Indexes for table `tbl_pelatihan_activity`
--
ALTER TABLE `tbl_pelatihan_activity`
  ADD PRIMARY KEY (`id_activity`),
  ADD KEY `id_pelatihan` (`id_pelatihan`),
  ADD KEY `id_narasumber` (`id_narasumber`);

--
-- Indexes for table `tbl_pelatihan_dokumen`
--
ALTER TABLE `tbl_pelatihan_dokumen`
  ADD PRIMARY KEY (`id_pelatihan_dokumen`),
  ADD KEY `id_pelatihan` (`id_pelatihan`),
  ADD KEY `id_master_document` (`id_dokumen`);

--
-- Indexes for table `tbl_pelatihan_foto`
--
ALTER TABLE `tbl_pelatihan_foto`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `id_activity` (`id_activity`);

--
-- Indexes for table `tbl_pinjam`
--
ALTER TABLE `tbl_pinjam`
  ADD PRIMARY KEY (`id_pinjam`);

--
-- Indexes for table `tbl_rak`
--
ALTER TABLE `tbl_rak`
  ADD PRIMARY KEY (`id_rak`);

--
-- Indexes for table `tbl_role`
--
ALTER TABLE `tbl_role`
  ADD PRIMARY KEY (`id_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_biaya_denda`
--
ALTER TABLE `tbl_biaya_denda`
  MODIFY `id_biaya_denda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_buku`
--
ALTER TABLE `tbl_buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_denda`
--
ALTER TABLE `tbl_denda`
  MODIFY `id_denda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_detail_pelatihan`
--
ALTER TABLE `tbl_detail_pelatihan`
  MODIFY `id_detail_pelatihan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_dokumen`
--
ALTER TABLE `tbl_dokumen`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_login`
--
ALTER TABLE `tbl_login`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_materi_pelatihan`
--
ALTER TABLE `tbl_materi_pelatihan`
  MODIFY `id_materi_pelatihan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_pegawai`
--
ALTER TABLE `tbl_pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_pelatihan`
--
ALTER TABLE `tbl_pelatihan`
  MODIFY `id_pelatihan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_pelatihan_activity`
--
ALTER TABLE `tbl_pelatihan_activity`
  MODIFY `id_activity` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_pelatihan_dokumen`
--
ALTER TABLE `tbl_pelatihan_dokumen`
  MODIFY `id_pelatihan_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_pelatihan_foto`
--
ALTER TABLE `tbl_pelatihan_foto`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_pinjam`
--
ALTER TABLE `tbl_pinjam`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_rak`
--
ALTER TABLE `tbl_rak`
  MODIFY `id_rak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_role`
--
ALTER TABLE `tbl_role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_detail_pelatihan`
--
ALTER TABLE `tbl_detail_pelatihan`
  ADD CONSTRAINT `fk_administrasi` FOREIGN KEY (`id_administrasi`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_akademis` FOREIGN KEY (`id_akademis`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_detail_pelatihan` FOREIGN KEY (`id_pelatihan`) REFERENCES `tbl_pelatihan` (`id_pelatihan`),
  ADD CONSTRAINT `fk_ketua_panitia` FOREIGN KEY (`id_ketua_panitia`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_keuangan` FOREIGN KEY (`id_keuangan`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_penanggung_jawab` FOREIGN KEY (`id_penanggung_jawab`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_pengajar_1` FOREIGN KEY (`id_pengajar_1`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_pengajar_2` FOREIGN KEY (`id_pengajar_2`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_pengajar_3` FOREIGN KEY (`id_pengajar_3`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_wi_1` FOREIGN KEY (`id_wi_1`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_wi_2` FOREIGN KEY (`id_wi_2`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_wi_3` FOREIGN KEY (`id_wi_3`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_wi_4` FOREIGN KEY (`id_wi_4`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_wi_rapat_kelulusan` FOREIGN KEY (`id_wi_rapat_kelulusan`) REFERENCES `tbl_pegawai` (`id_pegawai`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_materi_pelatihan`
--
ALTER TABLE `tbl_materi_pelatihan`
  ADD CONSTRAINT `fk_pelatihan_materi` FOREIGN KEY (`id_pelatihan`) REFERENCES `tbl_pelatihan` (`id_pelatihan`);

--
-- Constraints for table `tbl_pegawai`
--
ALTER TABLE `tbl_pegawai`
  ADD CONSTRAINT `fk_jabatan` FOREIGN KEY (`jabatan`) REFERENCES `tbl_role` (`id_role`);

--
-- Constraints for table `tbl_pelatihan`
--
ALTER TABLE `tbl_pelatihan`
  ADD CONSTRAINT `fk_pejabat_pembuka` FOREIGN KEY (`id_pejabat_pembuka`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_pejabat_penutup` FOREIGN KEY (`id_pejabat_penutup`) REFERENCES `tbl_pegawai` (`id_pegawai`),
  ADD CONSTRAINT `fk_role_pembuka` FOREIGN KEY (`id_role_pembuka`) REFERENCES `tbl_role` (`id_role`),
  ADD CONSTRAINT `fk_role_penutup` FOREIGN KEY (`id_role_penutup`) REFERENCES `tbl_role` (`id_role`);

--
-- Constraints for table `tbl_pelatihan_activity`
--
ALTER TABLE `tbl_pelatihan_activity`
  ADD CONSTRAINT `tbl_pelatihan_activity_ibfk_1` FOREIGN KEY (`id_pelatihan`) REFERENCES `tbl_pelatihan` (`id_pelatihan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_pelatihan_activity_ibfk_2` FOREIGN KEY (`id_narasumber`) REFERENCES `tbl_pegawai` (`id_pegawai`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_pelatihan_dokumen`
--
ALTER TABLE `tbl_pelatihan_dokumen`
  ADD CONSTRAINT `tbl_pelatihan_dokumen_ibfk_1` FOREIGN KEY (`id_pelatihan`) REFERENCES `tbl_pelatihan` (`id_pelatihan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_pelatihan_dokumen_ibfk_2` FOREIGN KEY (`id_dokumen`) REFERENCES `tbl_dokumen` (`id_dokumen`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_pelatihan_foto`
--
ALTER TABLE `tbl_pelatihan_foto`
  ADD CONSTRAINT `tbl_pelatihan_foto_ibfk_1` FOREIGN KEY (`id_activity`) REFERENCES `tbl_pelatihan_activity` (`id_activity`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
