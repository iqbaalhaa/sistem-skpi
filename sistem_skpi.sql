-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 18, 2025 at 09:07 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_skpi`
--

-- --------------------------------------------------------

--
-- Table structure for table `biodata_admin`
--

DROP TABLE IF EXISTS `biodata_admin`;
CREATE TABLE IF NOT EXISTS `biodata_admin` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kaprodi` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_dekan` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `biodata_admin_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `biodata_admin`
--

INSERT INTO `biodata_admin` (`id`, `user_id`, `nama`, `nama_kaprodi`, `nama_dekan`, `nip`, `foto`, `created_at`, `updated_at`) VALUES
(2, 7, 'asasa', NULL, NULL, NULL, 'admin-foto/7_1760113534.jpg', '2025-10-10 16:19:01', '2025-10-10 16:25:34'),
(3, 8, 'Admin Prodi Kimia', NULL, NULL, NULL, 'admin-foto/8_1760280496.jpg', '2025-10-12 14:48:16', '2025-10-12 14:48:16');

-- --------------------------------------------------------

--
-- Table structure for table `biodata_mahasiswa`
--

DROP TABLE IF EXISTS `biodata_mahasiswa`;
CREATE TABLE IF NOT EXISTS `biodata_mahasiswa` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `nim` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi_id` bigint UNSIGNED NOT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_masuk` year DEFAULT NULL,
  `tanggal_lulus` date DEFAULT NULL,
  `nomor_ijazah` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ipk` decimal(3,2) DEFAULT NULL,
  `judul_skripsi` text COLLATE utf8mb4_unicode_ci,
  `lama_studi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `biodata_mahasiswa_nim_unique` (`nim`),
  KEY `biodata_mahasiswa_user_id_foreign` (`user_id`),
  KEY `biodata_mahasiswa_prodi_id_foreign` (`prodi_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `biodata_mahasiswa`
--

INSERT INTO `biodata_mahasiswa` (`id`, `user_id`, `nim`, `nama`, `prodi_id`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `foto`, `tahun_masuk`, `tanggal_lulus`, `nomor_ijazah`, `ipk`, `judul_skripsi`, `lama_studi`, `created_at`, `updated_at`) VALUES
(1, 4, '701200009', 'Iqbal', 2, 'Kerinci', '2025-03-13', 'sas', '08222', 'mhs_4_1760790729.jpg', '2022', NULL, NULL, NULL, NULL, NULL, '2025-09-28 17:50:27', '2025-10-18 12:32:09'),
(2, 10, '70120003', 'Budi Setiawan', 3, 'Jambi', '2025-10-01', 'as', '02154', 'mhs_10_1759426520.jpg', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 17:35:20', '2025-10-02 18:09:27'),
(3, 11, '70120004', 'Widia Bela', 4, 'Jambi', '2025-08-07', 'Jambi', '08220000', 'mhs_11_1759842338.jpg', '2022', NULL, NULL, NULL, NULL, NULL, '2025-10-07 13:05:40', '2025-10-07 13:05:40'),
(4, 12, '70121212', 'Kucinggg', 2, 'jambi', '2005-12-12', 'ads', '08222', 'mhs_12_1760371425.jpg', '2021', NULL, NULL, NULL, NULL, NULL, '2025-10-13 16:03:46', '2025-10-13 16:03:46'),
(5, 13, '34140073', 'Bela Oktaviani', 4, 'Jambi', '1996-10-10', 'Panca Usaha', '082180313026', 'mhs_13_1760533808.png', '2021', NULL, NULL, NULL, NULL, NULL, '2025-10-15 13:10:10', '2025-10-15 13:10:10');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keahlian_tambahan`
--

DROP TABLE IF EXISTS `keahlian_tambahan`;
CREATE TABLE IF NOT EXISTS `keahlian_tambahan` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_keahlian` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lembaga` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bukti` int DEFAULT NULL,
  `nomor_sertifikat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun` year DEFAULT NULL,
  `verifikasi` tinyint NOT NULL DEFAULT '0' COMMENT '0 = belum, 1 = diterima, 2 = ditolak',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keahlian_tambahan_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kompetensi_bahasa`
--

DROP TABLE IF EXISTS `kompetensi_bahasa`;
CREATE TABLE IF NOT EXISTS `kompetensi_bahasa` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_kompetensi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skor_kompetensi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` year NOT NULL,
  `bukti` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `verifikasi` tinyint(1) NOT NULL DEFAULT '0',
  `nomor_sertifikat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kompetensi_bahasa_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kompetensi_bahasa`
--

INSERT INTO `kompetensi_bahasa` (`id`, `user_id`, `nama_kompetensi`, `skor_kompetensi`, `tahun`, `bukti`, `catatan`, `verifikasi`, `nomor_sertifikat`, `created_at`, `updated_at`) VALUES
(1, 4, 'xfgdsfsfd', '1000', '2021', 'bukti_bahasa/Uphsud92rrzE3KXsPeK0WHyN4L9NWZT3tKg6YeCS.jpg', 'sds', 1, NULL, '2025-09-29 15:55:08', '2025-10-03 11:06:50'),
(2, 10, 'efrw', 'rewr', '2023', 'bukti_bahasa/pG2DOOVI7WnlPKoZzIhkxUEWgJE4dHB7L9jbLziE.jpg', NULL, 1, NULL, '2025-10-12 06:44:49', '2025-10-12 07:56:45');

-- --------------------------------------------------------

--
-- Table structure for table `kompetensi_keagamaan`
--

DROP TABLE IF EXISTS `kompetensi_keagamaan`;
CREATE TABLE IF NOT EXISTS `kompetensi_keagamaan` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `keterangan_indonesia` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan_inggris` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` year NOT NULL,
  `bukti` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `verifikasi` tinyint(1) NOT NULL DEFAULT '0',
  `nomor_sertifikat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kompetensi_keagamaan_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kompetensi_keagamaan`
--

INSERT INTO `kompetensi_keagamaan` (`id`, `user_id`, `keterangan_indonesia`, `keterangan_inggris`, `tahun`, `bukti`, `catatan`, `verifikasi`, `nomor_sertifikat`, `created_at`, `updated_at`) VALUES
(1, 4, 'sasaasdsa', 'dsfsdsfs', '2021', 'bukti_keagamaan/c7zrqJ8rKi07GBw8SyN5j4gkn28chGEyVaLKeTa1.jpg', 'asa', 1, NULL, '2025-09-29 16:26:45', '2025-10-03 11:06:51'),
(2, 10, 'ewrwe', 'adewe', '2020', 'bukti_keagamaan/QTnO5xItkkrL0nh9JlZdFWsf9jhxxGtwwnuFAHK5.jpg', NULL, 1, NULL, '2025-10-12 07:28:36', '2025-10-12 07:56:47');

-- --------------------------------------------------------

--
-- Table structure for table `lain_lain`
--

DROP TABLE IF EXISTS `lain_lain`;
CREATE TABLE IF NOT EXISTS `lain_lain` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_kegiatan` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penyelenggara` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bukti` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_sertifikat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun` year DEFAULT NULL,
  `verifikasi` tinyint NOT NULL DEFAULT '0' COMMENT '0 = belum, 1 = diterima, 2 = ditolak',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lain_lain_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_09_26_163346_add_username_to_users_table', 2),
(6, '2025_09_26_175949_add_role_to_users_table', 3),
(7, '2025_09_27_225601_create_prodi_table', 4),
(8, '2025_09_28_123906_create_biodata_mahasiswa_table', 5),
(9, '2025_09_29_000001_add_foto_to_biodata_mahasiswa_table', 6),
(10, '2025_09_29_000002_create_skpi_tables', 7),
(11, '2025_10_01_000000_create_pengajuan_skpi_table', 8),
(12, '2025_10_01_231952_add_prodi_id_to_users_table', 9),
(13, '2025_10_01_235043_add_prodi_id_to_users_table', 10),
(14, '2025_10_03_235757_add_tahun_masuk_to_biodata_mahasiswa_table', 11),
(15, '2025_10_06_000000_create_skpi_certificates_table', 12),
(16, '2025_10_09_225504_create_biodata_admin_table', 13),
(17, '2025_10_11_000143_add_columns_to_prodi_table', 14),
(18, '2025_10_12_211857_update_pengajuan_skpi_status_enum', 15),
(19, '2025_10_19_022923_add_fields_to_biodata_tables', 16),
(20, '2025_10_19_023216_update_skpi_tables_add_fields_and_new_tables', 17);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_skpi`
--

DROP TABLE IF EXISTS `pengajuan_skpi`;
CREATE TABLE IF NOT EXISTS `pengajuan_skpi` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` enum('menunggu','diterima_prodi','ditolak_prodi','diterima_fakultas','ditolak_fakultas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `tanggal_pengajuan` timestamp NULL DEFAULT NULL,
  `tanggal_verifikasi_prodi` timestamp NULL DEFAULT NULL,
  `tanggal_verifikasi_fakultas` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengajuan_skpi_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuan_skpi`
--

INSERT INTO `pengajuan_skpi` (`id`, `user_id`, `status`, `catatan_admin`, `tanggal_pengajuan`, `tanggal_verifikasi_prodi`, `tanggal_verifikasi_fakultas`, `created_at`, `updated_at`) VALUES
(1, 4, 'diterima_fakultas', NULL, NULL, '2025-10-06 09:52:04', '2025-10-12 14:21:49', '2025-10-06 08:51:06', '2025-10-12 14:21:49'),
(2, 10, 'diterima_fakultas', NULL, NULL, '2025-10-12 06:41:39', '2025-10-12 14:39:54', '2025-10-12 06:41:39', '2025-10-12 14:39:54'),
(3, 12, 'diterima_fakultas', NULL, NULL, '2025-10-13 16:10:45', '2025-10-13 16:16:34', '2025-10-13 16:10:45', '2025-10-13 16:16:34'),
(4, 13, 'diterima_fakultas', NULL, NULL, '2025-10-15 13:21:26', '2025-10-15 13:22:18', '2025-10-15 13:21:26', '2025-10-15 13:22:18'),
(5, 11, 'diterima_fakultas', NULL, NULL, '2025-10-16 04:02:24', '2025-10-16 04:04:00', '2025-10-16 04:02:24', '2025-10-16 04:04:00');

-- --------------------------------------------------------

--
-- Table structure for table `pengalaman_magang`
--

DROP TABLE IF EXISTS `pengalaman_magang`;
CREATE TABLE IF NOT EXISTS `pengalaman_magang` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `keterangan_indonesia` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan_inggris` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lembaga` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` year NOT NULL,
  `bukti` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `verifikasi` tinyint(1) NOT NULL DEFAULT '0',
  `nomor_sertifikat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengalaman_magang_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengalaman_magang`
--

INSERT INTO `pengalaman_magang` (`id`, `user_id`, `keterangan_indonesia`, `keterangan_inggris`, `lembaga`, `tahun`, `bukti`, `catatan`, `verifikasi`, `nomor_sertifikat`, `created_at`, `updated_at`) VALUES
(1, 4, 'wewe', 'wewe', 'wewew', '2022', 'bukti_magang/5tbqdUa52IuSy4sgj8jaTVgJZ1uiHhr19nvpkO8A.jpg', 'asa', 1, NULL, '2025-09-29 16:13:44', '2025-10-03 11:06:51'),
(2, 10, 'qweqw', 'wqewq', 'wqeqw', '2023', 'bukti_magang/kZ9sE4L8Ym3fU5a987hIZE1xvcbuJocTVRArie67.jpg', NULL, 1, NULL, '2025-10-12 06:45:49', '2025-10-12 07:56:46');

-- --------------------------------------------------------

--
-- Table structure for table `pengalaman_organisasi`
--

DROP TABLE IF EXISTS `pengalaman_organisasi`;
CREATE TABLE IF NOT EXISTS `pengalaman_organisasi` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `organisasi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_awal` year NOT NULL,
  `tahun_akhir` year NOT NULL,
  `bukti` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `verifikasi` tinyint(1) NOT NULL DEFAULT '0',
  `nomor_sertifikat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengalaman_organisasi_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengalaman_organisasi`
--

INSERT INTO `pengalaman_organisasi` (`id`, `user_id`, `organisasi`, `tahun_awal`, `tahun_akhir`, `bukti`, `catatan`, `verifikasi`, `nomor_sertifikat`, `created_at`, `updated_at`) VALUES
(1, 4, 'sdasw', '2020', '2021', 'bukti_organisasi/l14BjhlFA8iIQj92fsEQZsySzzTjKKsVP93dWjRE.jpg', '033', 1, NULL, '2025-09-29 15:48:02', '2025-10-03 11:06:49'),
(2, 10, 'ewr', '2022', '2023', 'bukti_organisasi/hLRM2hKCKNFLKdlVkv2uIeJWkOoLv5N3hASY6L0q.jpg', NULL, 1, NULL, '2025-10-12 06:42:21', '2025-10-12 07:56:45');

-- --------------------------------------------------------

--
-- Table structure for table `penghargaan_prestasi`
--

DROP TABLE IF EXISTS `penghargaan_prestasi`;
CREATE TABLE IF NOT EXISTS `penghargaan_prestasi` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `keterangan_indonesia` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan_inggris` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_organisasi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` year NOT NULL,
  `bukti` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `verifikasi` tinyint(1) NOT NULL DEFAULT '0',
  `nomor_sertifikat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penghargaan_prestasi_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penghargaan_prestasi`
--

INSERT INTO `penghargaan_prestasi` (`id`, `user_id`, `keterangan_indonesia`, `keterangan_inggris`, `jenis_organisasi`, `tahun`, `bukti`, `catatan`, `verifikasi`, `nomor_sertifikat`, `created_at`, `updated_at`) VALUES
(1, 4, 'dsfasas', 'sdarfs', 'ghdads', '2015', 'bukti_penghargaan/dPmmfm0tJHQuBOeOxMv8CCUxdAmIpTO33whBsw2R.jpg', 'qaweqe', 1, NULL, '2025-09-28 18:38:51', '2025-10-07 13:23:53'),
(2, 4, 'fdgd', 'eesf', 'dsfs', '2020', 'bukti_penghargaan/dmxN9NHMYK8lyEmg6Xc25OiscKaRMwByBy7gG6qz.jpg', 'fg', 1, NULL, '2025-09-28 18:39:14', '2025-10-07 13:23:55'),
(3, 11, 'abc', 'abc', 'abc', '2020', 'bukti_penghargaan/eAv97po5jUXnWiOrXsoaKohdV4G0I5O6w6ojXyYO.jpg', NULL, 1, NULL, '2025-10-07 13:08:33', '2025-10-16 03:58:55'),
(4, 10, 'dsfsdf', 'sdfsdf', 'dsfsd', '2022', 'bukti_penghargaan/RI9wAG1d9aKbveC7Hm887Q26OfvZ8jyJ40e0Krlf.jpg', NULL, 1, NULL, '2025-10-12 06:41:18', '2025-10-12 06:41:32'),
(5, 12, 'seds', 'sds', 'dsd', '2022', 'bukti_penghargaan/zXaUjxcANNS0xpVrKFIsFPUqfjpsXOJtdBA0lJmK.webp', NULL, 1, NULL, '2025-10-13 16:04:17', '2025-10-13 16:05:00'),
(6, 13, 'lomba pidato', 'speech', 'Universitas Jambi', '2022', 'bukti_penghargaan/n1qFIGyEGnD8dPLO5as2jlETkcWAkUxJkrWYkOsG.pdf', NULL, 1, NULL, '2025-10-15 13:14:35', '2025-10-15 13:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

DROP TABLE IF EXISTS `prodi`;
CREATE TABLE IF NOT EXISTS `prodi` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_prodi` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenjang_pendidikan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gelar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `akreditasi` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_prodi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id`, `nama_prodi`, `jenjang_pendidikan`, `gelar`, `akreditasi`, `kode_prodi`) VALUES
(2, 'Sistem Informasi', 'Sarjana', 'S.Kom', 'Baik Sekali', '00001'),
(3, 'Kimia', 'Sarjana', 'S.Si', 'Unggul', '00002'),
(4, 'Biologi', 'Sarjana', 'S.Si', 'Baik Sekali', '00003');

-- --------------------------------------------------------

--
-- Table structure for table `skpi_certificates`
--

DROP TABLE IF EXISTS `skpi_certificates`;
CREATE TABLE IF NOT EXISTS `skpi_certificates` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `skpi_certificates_user_id_index` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skpi_certificates`
--

INSERT INTO `skpi_certificates` (`id`, `user_id`, `file_path`, `generated_at`, `created_at`, `updated_at`) VALUES
(1, 4, 'skpi/generated/SKPI_Iqbal_20251006_213336.docx', '2025-10-06 14:33:36', '2025-10-06 14:33:36', '2025-10-06 14:33:36'),
(2, 4, 'skpi/generated/SKPI_Iqbal_20251006_221538.docx', '2025-10-06 15:15:38', '2025-10-06 15:15:38', '2025-10-06 15:15:38'),
(3, 4, 'skpi/generated/SKPI_Iqbal_20251006_221846.docx', '2025-10-06 15:18:46', '2025-10-06 15:18:46', '2025-10-06 15:18:46'),
(4, 4, 'skpi/generated/SKPI_Iqbal_20251006_222255.docx', '2025-10-06 15:22:55', '2025-10-06 15:22:55', '2025-10-06 15:22:55'),
(5, 4, 'skpi/generated/SKPI_Iqbal_20251006_222804.docx', '2025-10-06 15:28:04', '2025-10-06 15:28:04', '2025-10-06 15:28:04'),
(6, 4, 'skpi/generated/SKPI_Iqbal_20251006_223003.docx', '2025-10-06 15:30:03', '2025-10-06 15:30:03', '2025-10-06 15:30:03'),
(7, 4, 'skpi/generated/SKPI_Iqbal_20251006_223349.docx', '2025-10-06 15:33:49', '2025-10-06 15:33:49', '2025-10-06 15:33:49'),
(8, 4, 'skpi/generated/SKPI_Iqbal_20251007_201057.docx', '2025-10-07 13:10:57', '2025-10-07 13:10:57', '2025-10-07 13:10:57'),
(9, 4, 'skpi/generated/SKPI_Iqbal_20251011_003614.docx', '2025-10-10 17:36:14', '2025-10-10 17:36:14', '2025-10-10 17:36:14'),
(10, 4, 'skpi/generated/SKPI_Iqbal_20251011_003822.docx', '2025-10-10 17:38:22', '2025-10-10 17:38:22', '2025-10-10 17:38:22'),
(11, 4, 'skpi/generated/SKPI_Iqbal_20251011_003905.docx', '2025-10-10 17:39:05', '2025-10-10 17:39:05', '2025-10-10 17:39:05'),
(12, 4, 'skpi/generated/SKPI_Iqbal_20251012_212205.docx', '2025-10-12 14:22:05', '2025-10-12 14:22:05', '2025-10-12 14:22:05'),
(13, 13, 'skpi/generated/SKPI_Bela_Oktaviani_20251015_202348.docx', '2025-10-15 13:23:48', '2025-10-15 13:23:48', '2025-10-15 13:23:48'),
(14, 13, 'skpi/generated/SKPI_Bela_Oktaviani_20251015_202601.docx', '2025-10-15 13:26:01', '2025-10-15 13:26:01', '2025-10-15 13:26:01'),
(15, 4, 'skpi/generated/SKPI_Iqbal_20251017_004749.docx', '2025-10-16 17:47:50', '2025-10-16 17:47:50', '2025-10-16 17:47:50'),
(16, 4, 'skpi/generated/SKPI_Iqbal_20251018_000733.docx', '2025-10-17 17:07:34', '2025-10-17 17:07:34', '2025-10-17 17:07:34'),
(17, 4, 'skpi/generated/SKPI_Iqbal_20251018_000954.docx', '2025-10-17 17:09:54', '2025-10-17 17:09:54', '2025-10-17 17:09:54'),
(18, 4, 'skpi/generated/SKPI_Iqbal_20251018_004822.docx', '2025-10-17 17:48:22', '2025-10-17 17:48:22', '2025-10-17 17:48:22'),
(19, 4, 'skpi/generated/SKPI_Iqbal_20251018_005134.docx', '2025-10-17 17:51:34', '2025-10-17 17:51:34', '2025-10-17 17:51:34'),
(20, 4, 'skpi/generated/SKPI_Iqbal_20251018_005830.docx', '2025-10-17 17:58:31', '2025-10-17 17:58:31', '2025-10-17 17:58:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('mahasiswa','admin_prodi','admin_fakultas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mahasiswa',
  `prodi_id` bigint UNSIGNED DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_prodi_id_foreign` (`prodi_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `role`, `prodi_id`, `password`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(6, 'adminfakultas', 'fakultas@gmail.com', 'admin_fakultas', NULL, '$2y$12$akKX5V6krPQEwjgEETaaKucMMVg5GbzwJm6/GNVIfK1/JQjRwYFtu', NULL, NULL, '2025-09-26 20:23:04', '2025-09-26 20:23:04'),
(4, 'mahasiswa01', 'mahasiswa01@gmail.com', 'mahasiswa', 2, '$2y$12$TxChP1YzkHa2wxgx7Fl5pu3YdkovYjNGJt0L5FTCgdBMjQRvFmvX2', NULL, NULL, '2025-09-26 20:23:03', '2025-09-26 20:23:03'),
(7, 'adminsi', 'sisteminformasi@gmail.com', 'admin_prodi', 2, '$2y$12$YfbgD5n8n4wi1puMP7y1Qey0WsHLleaAG7rVifsCrz1NGlXCZPxlK', NULL, NULL, '2025-10-01 17:12:49', '2025-10-02 05:56:15'),
(8, 'adminkimia', 'kimia@gmail.com', 'admin_prodi', 3, '$2y$12$va.rGL6fF9k6UGxLeenDr.k7XngrLhanZuydxR80Ai3PWWbvb5Dca', NULL, NULL, '2025-10-02 05:55:58', '2025-10-02 05:55:58'),
(9, 'adminbiologi', 'biologi@gmail.com', 'admin_prodi', 4, '$2y$12$50ZYCNDVB3JeWw4lf9KdweGafvOnvjyrnl9twu7Kn8AN4tOHG/Unm', NULL, NULL, '2025-10-02 05:56:52', '2025-10-12 13:18:41'),
(10, 'budiset', 'budset@gmail.com', 'mahasiswa', 3, '$2y$12$kjh.NnrGOJyS4c6SVXEgOeYFewt/D3Bvra55ixnW9Kz19UiHMxkH6', NULL, NULL, '2025-10-02 15:49:11', '2025-10-02 15:49:11'),
(11, 'bela12', 'belaa@gmail.com', 'mahasiswa', NULL, '$2y$12$dv2jLKUlpwDKeQqCP3DUkOQR8Q.lXI2eUT../TqResfu2V.AB2FUa', NULL, NULL, '2025-10-07 13:04:22', '2025-10-07 13:04:22'),
(13, 'Bela', 'widiabela10@gmail.com', 'mahasiswa', NULL, '$2y$12$Lqm7RBVSBcIS/lTPyrfZnOq7OiFwOcpR2eft.Kg943Sex6mFQX7Qm', NULL, NULL, '2025-10-15 13:08:26', '2025-10-15 13:08:26');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
