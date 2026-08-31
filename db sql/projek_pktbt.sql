-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 04:57 AM
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
(1, 'AG001', 'anang', '202cb962ac59075b964b07152d234b70', 'Petugas', 'Anang', 'Bekasi', '1999-04-05', 'Laki-Laki', 'Ujung Harapan', '089618173609', 'fauzan1892@codekop.com', '2019-11-20', 'user_1752396603.png'),
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
  `nama_mata_pelatihan_kel_dasar` varchar(255) DEFAULT NULL,
  `nama_mata_pelatihan_kel_inti` varchar(255) DEFAULT NULL,
  `nama_mata_pelatihan_kel_penunjang` varchar(255) DEFAULT NULL,
  `latar_belakang` varchar(255) DEFAULT NULL,
  `tujuan_pelatihan` varchar(255) DEFAULT NULL,
  `tujuan_kursil` varchar(255) DEFAULT NULL,
  `asal_kursil` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(3, 'H. Aprianto S.Ag., M.A..', '197603012003121004', 1, 'Loka Diklat Keagamaan Pekanbaru', '2025-07-16 07:57:22', '2025-07-16 07:57:34', NULL);

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
(4, 'Pelatihan Jarak Jauh (PJJ) Manajemen zakat Angkatan I', 'Manajemen Zakat Angkatan I', 'Riau dan Kepulauan Riau', 'Kota Pekanbaru', 'Loka Pendidikan dan Pelatihan Keagamaan Pekanbaru', '2024-08-27', '2024-09-03', 'September', 2024, '2024-08-27', '08:00:00', 1, 1, '2024-09-04', '09:30:00', 1, 1, '2025-07-17 01:37:34', '2025-07-17 01:53:20', NULL);

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
(3, 'Pranata Humas Ahli Pertama', '2025-07-15 17:17:45', '2025-07-17 01:30:29', NULL);

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
  ADD KEY `fk_pengajar_3` (`id_pengajar_3`);

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
  ADD UNIQUE KEY `NIP` (`NIP`),
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
  MODIFY `id_detail_pelatihan` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_materi_pelatihan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_pegawai`
--
ALTER TABLE `tbl_pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_pelatihan`
--
ALTER TABLE `tbl_pelatihan`
  MODIFY `id_pelatihan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `fk_wi_4` FOREIGN KEY (`id_wi_4`) REFERENCES `tbl_pegawai` (`id_pegawai`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
