-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 24, 2026 at 04:01 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cuti-tambahan`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangs`
--

CREATE TABLE `barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_barang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `supplier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangs`
--

INSERT INTO `barangs` (`id`, `kode_barang`, `nama`, `jumlah`, `supplier`, `created_at`, `updated_at`) VALUES
(1, 'BRG001', 'Laptop ASUS', 10, 'PT ABC Teknologi', '2026-01-11 01:35:50', '2026-01-11 01:35:50'),
(2, 'BRG002', 'Mouse Logitech', 50, 'PT XYZ Komputer', '2026-01-11 01:35:50', '2026-01-11 01:35:50'),
(3, 'BRG003', 'Monitor Samsung', 20, 'PT ABC Teknologi', '2026-01-11 01:35:50', '2026-01-11 01:35:50'),
(4, 'BRG004', 'Keyboard Mechanical', 30, 'PT Maju Jaya', '2026-01-11 01:35:50', '2026-01-11 01:35:50'),
(5, 'BRG005', 'Printer Epson', 5, 'PT Sumber Makmur', '2026-01-11 01:35:50', '2026-01-11 01:35:50');

-- --------------------------------------------------------

--
-- Table structure for table `buku_tamu`
--

CREATE TABLE `buku_tamu` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_satker` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_tamu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pegawai_kppn_id` bigint UNSIGNED NOT NULL,
  `kantor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `waktu_kunjungan` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku_tamu`
--

INSERT INTO `buku_tamu` (`id`, `nama_satker`, `nama_tamu`, `pegawai_kppn_id`, `kantor`, `foto_path`, `keterangan`, `waktu_kunjungan`, `created_at`, `updated_at`) VALUES
(4, 'KPPN Banyuwangi', 'Tamu 1', 2, 'Front Office', 'buku-tamu/tamu_699d161633dc5.jpg', 'Konsultasi', '2026-02-24 03:08:07', '2026-02-24 03:08:07', '2026-02-24 03:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `cuti_tambahan`
--

CREATE TABLE `cuti_tambahan` (
  `id` bigint UNSIGNED NOT NULL,
  `pegawai_id` bigint UNSIGNED NOT NULL,
  `nomor_nota_dinas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_nota_dinas` date NOT NULL,
  `cuti_tambahan_jumlah` int NOT NULL,
  `cuti_tahunan_jumlah` decimal(4,1) NOT NULL DEFAULT '0.0',
  `tanggal_cuti` json NOT NULL,
  `tanggal_cuti_tambahan` json DEFAULT NULL,
  `tanggal_cuti_tahunan` json DEFAULT NULL,
  `alasan_cuti` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_cuti` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('menunggu','disetujui','ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_admin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cuti_tambahan`
--

INSERT INTO `cuti_tambahan` (`id`, `pegawai_id`, `nomor_nota_dinas`, `tanggal_nota_dinas`, `cuti_tambahan_jumlah`, `cuti_tahunan_jumlah`, `tanggal_cuti`, `tanggal_cuti_tambahan`, `tanggal_cuti_tahunan`, `alasan_cuti`, `alamat_cuti`, `status`, `catatan_admin`, `created_at`, `updated_at`) VALUES
(10, 9, 'ND-CT/001/KEP/02/2026', '2026-02-23', 1, 1.0, '[\"2026-02-24\", \"2026-02-25\"]', '[\"2026-02-25\"]', '[\"2026-02-24\"]', 'Mengunjungi Keluarga', 'Purworejo, Jawa Tengah', 'disetujui', NULL, '2026-02-23 05:42:50', '2026-02-23 05:43:59'),
(12, 10, 'ND-CT/002/KEP/02/2026', '2026-02-24', 1, 1.0, '[\"2026-02-25\", \"2026-02-26\"]', '[\"2026-02-26\"]', '[{\"sesi\": \"penuh\", \"tanggal\": \"2026-02-25\"}]', 'Menemui Keluarga', 'Magelang', 'disetujui', NULL, '2026-02-24 01:52:18', '2026-02-24 02:00:12'),
(13, 9, 'ND-CT/003/KEP/02/2026', '2026-02-24', 0, 0.5, '[\"2026-02-27\"]', '[]', '[{\"sesi\": \"pagi\", \"tanggal\": \"2026-02-27\"}]', 'Pulang', 'Purworejo', 'disetujui', NULL, '2026-02-24 03:30:44', '2026-02-24 03:31:11');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kepala_kantor`
--

CREATE TABLE `kepala_kantor` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pangkat_gol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kepala_kantor`
--

INSERT INTO `kepala_kantor` (`id`, `nama`, `nip`, `pangkat_gol`, `created_at`, `updated_at`) VALUES
(1, 'Setyawan', '197211181993011001', 'Pembina (IV/a)', '2026-02-22 01:53:16', '2026-02-22 04:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_01_01_090757_create_templates_table', 1),
(6, '2026_01_11_090934_create_barangs_table', 2),
(7, '2026_02_22_091456_create_pegawai_table', 3),
(8, '2026_02_22_091520_create_cuti_tambahan_table', 3),
(9, '2026_02_22_091603_create_kepala_kantor_table', 3),
(10, '2026_02_22_095300_add_username_to_users_table', 4),
(11, '2026_02_22_121404_create_seksi_table', 5),
(12, '2026_02_22_121444_add_seksi_id_to_table_pegawai', 5),
(13, '2026_02_22_130916_ubah_cuti_tambahan', 6),
(14, '2026_02_23_012523_add_cuti_tahunan_to_cuti_tambahan', 7),
(15, '2026_02_23_015935_add_tanggal_to_cuti_tahunan', 8),
(16, '2026_02_23_024554_add_catatan_admin_to_cuti_tambahan_table', 9),
(17, '2026_02_23_131727_add_kuota_fields_to_pegawai_table', 10),
(18, '2026_02_23_141106_add_cuti_setengah_hari_to_cuti_tahunan', 11),
(19, '2026_02_24_083643_create_buku_tamu_table', 12),
(20, '2026_02_24_115313_change_sisa_cuti_columns', 13);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pangkat_gol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_kerja` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sisa_cuti_tahunan` decimal(5,1) NOT NULL DEFAULT '12.0',
  `sisa_cuti_tambahan` decimal(5,1) NOT NULL DEFAULT '0.0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `seksi_id` bigint UNSIGNED DEFAULT NULL,
  `kuota_cuti_tahunan` decimal(5,1) DEFAULT NULL,
  `kuota_cuti_tambahan` decimal(5,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id`, `user_id`, `nama`, `nip`, `pangkat_gol`, `jabatan`, `unit_kerja`, `sisa_cuti_tahunan`, `sisa_cuti_tambahan`, `created_at`, `updated_at`, `seksi_id`, `kuota_cuti_tahunan`, `kuota_cuti_tambahan`) VALUES
(2, 3, 'Agnesti Shiffa Pertiwi', '199908062019122001', 'Pengatur (II/C)', 'Pelaksana Seksi Verifikasi Akuntansi dan Kepatuhan Internal', 'KPPN Kolaka', 12.0, 12.0, '2026-02-22 05:23:11', '2026-02-22 18:20:23', 2, 12.0, 12.0),
(8, 11, 'Ian Rialdy Abuston Muhammadong', '199607282018121001', 'Pengatur Tingkat I  (II/d)', 'Pelaksana Seksi Verifikasi, Akuntansi dan Kepatuhan Internal', 'KPPN Kolaka', 24.0, 0.0, '2026-02-23 05:32:32', '2026-02-23 05:38:34', 2, 24.0, 0.0),
(9, 12, 'Muhamad Dimas Pratama', '199903152019121002', 'Pengatur (II/C)', 'Pelaksana Seksi Pencairan Dana dan Manajemen Satker', 'KPPN Kolaka', 22.0, 11.0, '2026-02-23 05:42:06', '2026-02-24 03:31:11', 1, 23.0, 12.0),
(10, 14, 'Mila Lisniwati', '200103272023022001', 'Pengatur (II/c)', 'Pelaksana Subbagian Umum', 'KPPN Kolaka', 23.0, 11.0, '2026-02-24 01:50:46', '2026-02-24 02:00:12', 3, 24.0, 12.0);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seksi`
--

CREATE TABLE `seksi` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_seksi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kepala_seksi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip_kepala_seksi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seksi`
--

INSERT INTO `seksi` (`id`, `nama_seksi`, `nama_kepala_seksi`, `nip_kepala_seksi`, `created_at`, `updated_at`) VALUES
(1, 'Seksi Pencairan Dana dan Manajemen Satker', 'Subagya', '198302272002121002', '2026-02-22 04:26:18', '2026-02-23 03:02:47'),
(2, 'Seksi Verifikasi Akuntansi dan Kepatuhan Internal', 'Nurlaila Khurriyah', '197608161996022001', '2026-02-22 05:21:37', '2026-02-22 05:21:37'),
(3, 'Subbagian Umum', 'Agus Setya Harwanta', '198208092002121002', '2026-02-23 03:42:09', '2026-02-23 03:42:09'),
(4, 'Seksi Bank', 'Romadhon', '198107152002121001', '2026-02-23 03:43:06', '2026-02-23 03:43:06');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `konten` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fields` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `nama`, `kode`, `deskripsi`, `konten`, `fields`, `created_at`, `updated_at`) VALUES
(1, 'Surat Undangan Rapat', 'UNDANGAN', 'Template surat undangan rapat resmi', '<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"UTF-8\">\n    <style>\n        body { font-family: \"Times New Roman\", Times, serif; font-size: 12pt; line-height: 1.5; }\n        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #000; padding-bottom: 10px; }\n        .header h1 { font-size: 16pt; margin: 5px 0; font-weight: bold; }\n        .header h2 { font-size: 14pt; margin: 5px 0; font-weight: bold; }\n        .header p { font-size: 10pt; margin: 3px 0; }\n        .kop-table { width: 100%; margin-bottom: 20px; }\n        .kop-table td { padding: 2px 0; }\n        .kop-table td:first-child { width: 20%; }\n        .kop-table td:nth-child(2) { width: 5%; }\n        .content { text-align: justify; }\n        .detail-table { margin-left: 40px; margin-bottom: 20px; }\n        .detail-table td { padding: 2px 0; vertical-align: top; }\n        .detail-table td:first-child { width: 120px; }\n        .detail-table td:nth-child(2) { width: 20px; }\n        .signature { margin-top: 40px; text-align: right; }\n        .signature p { margin: 5px 0; }\n        .signature-space { margin: 60px 0; }\n        strong { font-weight: bold; }\n        u { text-decoration: underline; }\n    </style>\n</head>\n<body>\n    <div class=\"header\">\n        <h1>PEMERINTAH DAERAH WAKANDA</h1>\n        <h2>DINAS LINGKUNGAN HIDUP</h2>\n        <p>Jl. Vibranium Raya No. 45, Wakanda 12345</p>\n        <p>Telp: (021) 1234-5678 | Email: dlh@wakanda.go.id</p>\n    </div>\n\n    <table class=\"kop-table\">\n        <tr>\n            <td>Nomor</td>\n            <td>:</td>\n            <td>{nomor_surat}</td>\n        </tr>\n        <tr>\n            <td>Lampiran</td>\n            <td>:</td>\n            <td>-</td>\n        </tr>\n        <tr>\n            <td>Perihal</td>\n            <td>:</td>\n            <td><strong>Undangan Rapat</strong></td>\n        </tr>\n    </table>\n\n    <p>{tanggal}</p>\n    <br>\n\n    <p>Kepada Yth.<br>\n    <strong>{nama_penerima}</strong><br>\n    {jabatan_penerima}<br>\n    di tempat</p>\n    <br>\n\n    <p>Dengan hormat,</p>\n    <br>\n\n    <p class=\"content\">Sehubungan dengan rencana kegiatan koordinasi dan evaluasi program Dinas Lingkungan Hidup, dengan ini kami mengundang Bapak/Ibu untuk menghadiri rapat yang akan dilaksanakan pada:</p>\n    <br>\n\n    <table class=\"detail-table\">\n        <tr>\n            <td>Hari/Tanggal</td>\n            <td>:</td>\n            <td>{hari_rapat}, {tanggal_rapat}</td>\n        </tr>\n        <tr>\n            <td>Waktu</td>\n            <td>:</td>\n            <td>{waktu} WIB</td>\n        </tr>\n        <tr>\n            <td>Tempat</td>\n            <td>:</td>\n            <td>{tempat}</td>\n        </tr>\n        <tr>\n            <td>Agenda</td>\n            <td>:</td>\n            <td>{agenda}</td>\n        </tr>\n    </table>\n    <br>\n\n    <p class=\"content\">Mengingat pentingnya acara tersebut, kami mengharapkan kehadiran Bapak/Ibu tepat waktu. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>\n    <br>\n\n    <div class=\"signature\">\n        <p>Kepala Dinas Lingkungan Hidup</p>\n        <p>Pemda Wakanda,</p>\n        <div class=\"signature-space\"></div>\n        <p><strong><u>Drs. T\'Challa Udaku, M.Si</u></strong></p>\n        <p>NIP. 196705121990031004</p>\n    </div>\n</body>\n</html>', '[\"nomor_surat\", \"tanggal\", \"nama_penerima\", \"jabatan_penerima\", \"hari_rapat\", \"tanggal_rapat\", \"waktu\", \"tempat\", \"agenda\"]', '2026-01-01 01:55:00', '2026-01-01 01:55:00'),
(2, 'Surat Tugas Pegawai', 'TUGAS', 'Template surat penugasan pegawai', '<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"UTF-8\">\n    <style>\n        body { font-family: \"Times New Roman\", Times, serif; font-size: 12pt; line-height: 1.5; }\n        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #000; padding-bottom: 10px; }\n        .header h1 { font-size: 16pt; margin: 5px 0; font-weight: bold; }\n        .header h2 { font-size: 14pt; margin: 5px 0; font-weight: bold; }\n        .header p { font-size: 10pt; margin: 3px 0; }\n        .title { text-align: center; margin: 30px 0; }\n        .title h3 { font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 5px 0; }\n        .title p { font-size: 12pt; margin: 5px 0; }\n        .content { text-align: justify; }\n        .detail-table { margin-left: 40px; margin-bottom: 20px; }\n        .detail-table td { padding: 2px 0; vertical-align: top; }\n        .detail-table td:first-child { width: 120px; }\n        .detail-table td:nth-child(2) { width: 20px; }\n        .signature { margin-top: 40px; text-align: center; }\n        .signature p { margin: 5px 0; }\n        .signature-space { margin: 60px 0; }\n        strong { font-weight: bold; }\n        u { text-decoration: underline; }\n    </style>\n</head>\n<body>\n    <div class=\"header\">\n        <h1>PEMERINTAH DAERAH WAKANDA</h1>\n        <h2>DINAS LINGKUNGAN HIDUP</h2>\n        <p>Jl. Vibranium Raya No. 45, Wakanda 12345</p>\n        <p>Telp: (021) 1234-5678 | Email: dlh@wakanda.go.id</p>\n    </div>\n\n    <div class=\"title\">\n        <h3>SURAT TUGAS</h3>\n        <p>Nomor: {nomor_surat}</p>\n    </div>\n    <br>\n\n    <p class=\"content\">Yang bertanda tangan di bawah ini, Kepala Dinas Lingkungan Hidup Pemerintah Daerah Wakanda, dengan ini memberikan tugas kepada:</p>\n    <br>\n\n    <table class=\"detail-table\">\n        <tr>\n            <td>Nama</td>\n            <td>:</td>\n            <td><strong>{nama_pegawai}</strong></td>\n        </tr>\n        <tr>\n            <td>NIP</td>\n            <td>:</td>\n            <td>{nip}</td>\n        </tr>\n        <tr>\n            <td>Jabatan</td>\n            <td>:</td>\n            <td>{jabatan}</td>\n        </tr>\n    </table>\n    <br>\n\n    <p class=\"content\">Untuk melaksanakan tugas:</p>\n    <br>\n\n    <table class=\"detail-table\">\n        <tr>\n            <td>Keperluan</td>\n            <td>:</td>\n            <td>{keperluan}</td>\n        </tr>\n        <tr>\n            <td>Tujuan</td>\n            <td>:</td>\n            <td>{tujuan_tugas}</td>\n        </tr>\n        <tr>\n            <td>Waktu</td>\n            <td>:</td>\n            <td>{tanggal_mulai} s.d. {tanggal_selesai}</td>\n        </tr>\n    </table>\n    <br>\n\n    <p class=\"content\">Demikian surat tugas ini dibuat untuk dapat dilaksanakan dengan penuh tanggung jawab.</p>\n    <br><br>\n\n    <div class=\"signature\">\n        <p>Ditetapkan di: Wakanda</p>\n        <p>Pada tanggal: {tanggal}</p>\n        <p>Kepala Dinas Lingkungan Hidup,</p>\n        <div class=\"signature-space\"></div>\n        <p><strong><u>Drs. T\'Challa Udaku, M.Si</u></strong></p>\n        <p>NIP. 196705121990031004</p>\n    </div>\n</body>\n</html>', '[\"nomor_surat\", \"tanggal\", \"nama_pegawai\", \"nip\", \"jabatan\", \"tujuan_tugas\", \"tanggal_mulai\", \"tanggal_selesai\", \"keperluan\"]', '2026-01-01 01:55:00', '2026-01-01 01:55:00'),
(3, 'Surat Permohonan Izin Kegiatan Lingkungan', 'IZIN', 'Template surat permohonan izin kegiatan lingkungan', '<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"UTF-8\">\n    <style>\n        body { font-family: \"Times New Roman\", Times, serif; font-size: 12pt; line-height: 1.5; }\n        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #000; padding-bottom: 10px; }\n        .header h1 { font-size: 16pt; margin: 5px 0; font-weight: bold; }\n        .header h2 { font-size: 14pt; margin: 5px 0; font-weight: bold; }\n        .header p { font-size: 10pt; margin: 3px 0; }\n        .kop-table { width: 100%; margin-bottom: 20px; }\n        .kop-table td { padding: 2px 0; }\n        .kop-table td:first-child { width: 20%; }\n        .kop-table td:nth-child(2) { width: 5%; }\n        .content { text-align: justify; }\n        .detail-table { margin-left: 40px; margin-bottom: 20px; }\n        .detail-table td { padding: 2px 0; vertical-align: top; }\n        .detail-table td:first-child { width: 150px; }\n        .detail-table td:nth-child(2) { width: 20px; }\n        .signature { margin-top: 40px; text-align: right; }\n        .signature p { margin: 5px 0; }\n        .signature-space { margin: 60px 0; }\n        strong { font-weight: bold; }\n        u { text-decoration: underline; }\n    </style>\n</head>\n<body>\n    <div class=\"header\">\n        <h1>PEMERINTAH DAERAH WAKANDA</h1>\n        <h2>DINAS LINGKUNGAN HIDUP</h2>\n        <p>Jl. Vibranium Raya No. 45, Wakanda 12345</p>\n        <p>Telp: (021) 1234-5678 | Email: dlh@wakanda.go.id</p>\n    </div>\n\n    <table class=\"kop-table\">\n        <tr>\n            <td>Nomor</td>\n            <td>:</td>\n            <td>{nomor_surat}</td>\n        </tr>\n        <tr>\n            <td>Lampiran</td>\n            <td>:</td>\n            <td>1 (satu) berkas</td>\n        </tr>\n        <tr>\n            <td>Perihal</td>\n            <td>:</td>\n            <td><strong>Permohonan Izin Kegiatan Lingkungan</strong></td>\n        </tr>\n    </table>\n\n    <p>{tanggal}</p>\n    <br>\n\n    <p>Kepada Yth.<br>\n    <strong>Kepala Dinas Lingkungan Hidup</strong><br>\n    Pemerintah Daerah Wakanda<br>\n    di tempat</p>\n    <br>\n\n    <p>Dengan hormat,</p>\n    <br>\n\n    <p class=\"content\">Yang bertanda tangan di bawah ini:</p>\n    <br>\n\n    <table class=\"detail-table\">\n        <tr>\n            <td>Nama</td>\n            <td>:</td>\n            <td><strong>{nama_pemohon}</strong></td>\n        </tr>\n        <tr>\n            <td>Alamat</td>\n            <td>:</td>\n            <td>{alamat_pemohon}</td>\n        </tr>\n    </table>\n    <br>\n\n    <p class=\"content\">Dengan ini mengajukan permohonan izin untuk melaksanakan kegiatan lingkungan dengan rincian sebagai berikut:</p>\n    <br>\n\n    <table class=\"detail-table\">\n        <tr>\n            <td>Jenis Kegiatan</td>\n            <td>:</td>\n            <td>{jenis_kegiatan}</td>\n        </tr>\n        <tr>\n            <td>Lokasi</td>\n            <td>:</td>\n            <td>{lokasi_kegiatan}</td>\n        </tr>\n        <tr>\n            <td>Waktu Pelaksanaan</td>\n            <td>:</td>\n            <td>{tanggal_pelaksanaan}</td>\n        </tr>\n        <tr>\n            <td>Deskripsi</td>\n            <td>:</td>\n            <td>{deskripsi_kegiatan}</td>\n        </tr>\n    </table>\n    <br>\n\n    <p class=\"content\">Demikian permohonan ini kami sampaikan. Atas perhatian dan pertimbangan Bapak/Ibu, kami ucapkan terima kasih.</p>\n    <br>\n\n    <div class=\"signature\">\n        <p>Hormat kami,</p>\n        <p>Pemohon,</p>\n        <div class=\"signature-space\"></div>\n        <p><strong><u>{nama_pemohon}</u></strong></p>\n    </div>\n</body>\n</html>', '[\"nomor_surat\", \"tanggal\", \"nama_pemohon\", \"alamat_pemohon\", \"jenis_kegiatan\", \"lokasi_kegiatan\", \"tanggal_pelaksanaan\", \"deskripsi_kegiatan\"]', '2026-01-01 01:55:00', '2026-01-01 01:55:00'),
(4, 'Laporan Kegiatan Kebersihan Kota', 'LAPORAN', 'Template laporan kegiatan kebersihan kota', '<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"UTF-8\">\n    <style>\n        body { font-family: \"Times New Roman\", Times, serif; font-size: 12pt; line-height: 1.5; }\n        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #000; padding-bottom: 10px; }\n        .header h1 { font-size: 16pt; margin: 5px 0; font-weight: bold; }\n        .header h2 { font-size: 14pt; margin: 5px 0; font-weight: bold; }\n        .header p { font-size: 10pt; margin: 3px 0; }\n        .title { text-align: center; margin: 30px 0; }\n        .title h3 { font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 5px 0; }\n        .title p { font-size: 12pt; margin: 5px 0; }\n        .section-title { font-size: 12pt; font-weight: bold; margin: 20px 0 10px 0; }\n        .content { text-align: justify; }\n        .detail-table { margin-left: 30px; margin-bottom: 20px; width: 90%; }\n        .detail-table td { padding: 2px 0; vertical-align: top; }\n        .detail-table td:first-child { width: 200px; }\n        .detail-table td:nth-child(2) { width: 20px; }\n        .signature { margin-top: 40px; text-align: center; }\n        .signature p { margin: 5px 0; }\n        .signature-space { margin: 60px 0; }\n        strong { font-weight: bold; }\n        u { text-decoration: underline; }\n    </style>\n</head>\n<body>\n    <div class=\"header\">\n        <h1>PEMERINTAH DAERAH WAKANDA</h1>\n        <h2>DINAS LINGKUNGAN HIDUP</h2>\n        <p>Jl. Vibranium Raya No. 45, Wakanda 12345</p>\n        <p>Telp: (021) 1234-5678 | Email: dlh@wakanda.go.id</p>\n    </div>\n\n    <div class=\"title\">\n        <h3>LAPORAN KEGIATAN KEBERSIHAN KOTA</h3>\n        <p>Nomor: {nomor_laporan}</p>\n    </div>\n    <br>\n\n    <p class=\"section-title\">I. PENDAHULUAN</p>\n    <p class=\"content\">Laporan ini disusun sebagai bentuk pertanggungjawaban pelaksanaan kegiatan kebersihan kota yang telah dilaksanakan oleh Dinas Lingkungan Hidup Pemerintah Daerah Wakanda.</p>\n    <br>\n\n    <p class=\"section-title\">II. DATA KEGIATAN</p>\n    <table class=\"detail-table\">\n        <tr>\n            <td>Nama Kegiatan</td>\n            <td>:</td>\n            <td><strong>{nama_kegiatan}</strong></td>\n        </tr>\n        <tr>\n            <td>Lokasi</td>\n            <td>:</td>\n            <td>{lokasi}</td>\n        </tr>\n        <tr>\n            <td>Tanggal Pelaksanaan</td>\n            <td>:</td>\n            <td>{tanggal_kegiatan}</td>\n        </tr>\n        <tr>\n            <td>Jumlah Peserta</td>\n            <td>:</td>\n            <td>{jumlah_peserta} orang</td>\n        </tr>\n        <tr>\n            <td>Volume Sampah Terkumpul</td>\n            <td>:</td>\n            <td>{volume_sampah} kg</td>\n        </tr>\n        <tr>\n            <td>Jenis Sampah</td>\n            <td>:</td>\n            <td>{jenis_sampah}</td>\n        </tr>\n    </table>\n    <br>\n\n    <p class=\"section-title\">III. HASIL KEGIATAN</p>\n    <p class=\"content\" style=\"margin-left: 30px;\">{hasil_kegiatan}</p>\n    <br>\n\n    <p class=\"section-title\">IV. KENDALA</p>\n    <p class=\"content\" style=\"margin-left: 30px;\">{kendala}</p>\n    <br>\n\n    <p class=\"section-title\">V. PENUTUP</p>\n    <p class=\"content\" style=\"margin-left: 30px;\">Demikian laporan ini dibuat dengan sebenar-benarnya untuk dapat digunakan sebagaimana mestinya.</p>\n    <br><br>\n\n    <div class=\"signature\">\n        <p>Wakanda, {tanggal}</p>\n        <p>Penanggung Jawab Kegiatan,</p>\n        <div class=\"signature-space\"></div>\n        <p><strong><u>{penanggung_jawab}</u></strong></p>\n        <p>NIP. {nip_penanggung_jawab}</p>\n    </div>\n</body>\n</html>', '[\"nomor_laporan\", \"tanggal\", \"nama_kegiatan\", \"lokasi\", \"tanggal_kegiatan\", \"jumlah_peserta\", \"volume_sampah\", \"jenis_sampah\", \"hasil_kegiatan\", \"kendala\", \"penanggung_jawab\", \"nip_penanggung_jawab\"]', '2026-01-01 01:55:00', '2026-01-01 01:55:00'),
(5, 'Berita Acara Serah Terima Barang Lingkungan', 'BAST', 'Template berita acara serah terima barang lingkungan', '<!DOCTYPE html>\n<html>\n<head>\n    <meta charset=\"UTF-8\">\n    <style>\n        body { font-family: \"Times New Roman\", Times, serif; font-size: 12pt; line-height: 1.5; }\n        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #000; padding-bottom: 10px; }\n        .header h1 { font-size: 16pt; margin: 5px 0; font-weight: bold; }\n        .header h2 { font-size: 14pt; margin: 5px 0; font-weight: bold; }\n        .header p { font-size: 10pt; margin: 3px 0; }\n        .title { text-align: center; margin: 30px 0; }\n        .title h3 { font-size: 14pt; font-weight: bold; text-decoration: underline; margin: 5px 0; }\n        .title p { font-size: 12pt; margin: 5px 0; }\n        .content { text-align: justify; }\n        .detail-table { margin-left: 30px; margin-bottom: 20px; width: 90%; }\n        .detail-table td { padding: 2px 0; vertical-align: top; }\n        .detail-table td:first-child { width: 150px; }\n        .detail-table td:nth-child(2) { width: 20px; }\n        .signature-table { width: 100%; margin-top: 40px; }\n        .signature-table td { text-align: center; vertical-align: top; padding: 0 20px; }\n        .signature-table p { margin: 5px 0; }\n        .signature-space { margin: 60px 0; }\n        strong { font-weight: bold; }\n        u { text-decoration: underline; }\n    </style>\n</head>\n<body>\n    <div class=\"header\">\n        <h1>PEMERINTAH DAERAH WAKANDA</h1>\n        <h2>DINAS LINGKUNGAN HIDUP</h2>\n        <p>Jl. Vibranium Raya No. 45, Wakanda 12345</p>\n        <p>Telp: (021) 1234-5678 | Email: dlh@wakanda.go.id</p>\n    </div>\n\n    <div class=\"title\">\n        <h3>BERITA ACARA SERAH TERIMA BARANG</h3>\n        <p>Nomor: {nomor_ba}</p>\n    </div>\n    <br>\n\n    <p class=\"content\">Pada hari ini, {tanggal}, yang bertanda tangan di bawah ini:</p>\n    <br>\n\n    <table class=\"detail-table\">\n        <tr>\n            <td colspan=\"3\"><strong>PIHAK PERTAMA (PENYERAH)</strong></td>\n        </tr>\n        <tr>\n            <td>Nama</td>\n            <td>:</td>\n            <td>{nama_penyerah}</td>\n        </tr>\n        <tr>\n            <td>Jabatan</td>\n            <td>:</td>\n            <td>{jabatan_penyerah}</td>\n        </tr>\n    </table>\n\n    <table class=\"detail-table\">\n        <tr>\n            <td colspan=\"3\"><strong>PIHAK KEDUA (PENERIMA)</strong></td>\n        </tr>\n        <tr>\n            <td>Nama</td>\n            <td>:</td>\n            <td>{nama_penerima}</td>\n        </tr>\n        <tr>\n            <td>Jabatan</td>\n            <td>:</td>\n            <td>{jabatan_penerima}</td>\n        </tr>\n    </table>\n    <br>\n\n    <p class=\"content\">Dengan ini menyatakan telah dilakukan serah terima barang dengan rincian sebagai berikut:</p>\n    <br>\n\n    <table class=\"detail-table\">\n        <tr>\n            <td>Nama Barang</td>\n            <td>:</td>\n            <td><strong>{nama_barang}</strong></td>\n        </tr>\n        <tr>\n            <td>Jumlah</td>\n            <td>:</td>\n            <td>{jumlah_barang}</td>\n        </tr>\n        <tr>\n            <td>Spesifikasi</td>\n            <td>:</td>\n            <td>{spesifikasi}</td>\n        </tr>\n        <tr>\n            <td>Kondisi</td>\n            <td>:</td>\n            <td>{kondisi}</td>\n        </tr>\n        <tr>\n            <td>Keperluan</td>\n            <td>:</td>\n            <td>{keperluan}</td>\n        </tr>\n    </table>\n    <br>\n\n    <p class=\"content\">Barang tersebut telah diterima dalam keadaan baik dan sesuai dengan spesifikasi yang telah ditentukan. Pihak kedua bertanggung jawab penuh atas penggunaan dan pemeliharaan barang tersebut.</p>\n    <br>\n\n    <p class=\"content\">Demikian Berita Acara ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>\n    <br><br>\n\n    <table class=\"signature-table\">\n        <tr>\n            <td>\n                <p><strong>PIHAK PERTAMA</strong></p>\n                <p>Yang Menyerahkan,</p>\n                <div class=\"signature-space\"></div>\n                <p><strong><u>{nama_penyerah}</u></strong></p>\n                <p>{jabatan_penyerah}</p>\n            </td>\n            <td>\n                <p><strong>PIHAK KEDUA</strong></p>\n                <p>Yang Menerima,</p>\n                <div class=\"signature-space\"></div>\n                <p><strong><u>{nama_penerima}</u></strong></p>\n                <p>{jabatan_penerima}</p>\n            </td>\n        </tr>\n    </table>\n</body>\n</html>', '[\"nomor_ba\", \"tanggal\", \"nama_penyerah\", \"jabatan_penyerah\", \"nama_penerima\", \"jabatan_penerima\", \"nama_barang\", \"jumlah_barang\", \"spesifikasi\", \"kondisi\", \"keperluan\"]', '2026-01-01 01:55:00', '2026-01-01 01:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pegawai',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$dyHJugOmG7qEivGHba0k3eZ7E9TvBStmVAvh3d2MoOJjD/pIyuxp2', 'admin', NULL, '2026-02-22 01:53:16', '2026-02-22 01:53:16'),
(3, '199908062019122001', '$2y$12$6sdBpee/oZbYZ/4CpligNON/ZjuODhw.5clXW.w6NRNlSf3jJhZQy', 'pegawai', NULL, '2026-02-22 05:23:10', '2026-02-22 05:23:10'),
(11, '199607282018121001', '$2y$12$k.FfFUVbSju6w6JNTMHcwO1ioVa3RHjvpnHMf/7Zt4tIpglaOGQZ6', 'pegawai', NULL, '2026-02-23 05:32:32', '2026-02-23 05:32:32'),
(12, '199903152019121002', '$2y$12$xXYerAFrR5D7/n1p73cg5O1.6W.dC/tQrdrTZjUHNi..QDVaYxh46', 'pegawai', NULL, '2026-02-23 05:42:06', '2026-02-23 05:42:06'),
(13, 'mira', '$2y$12$YG1WQO590NPysA39dHYWE.4xMB2ZW69Q7icfX1BWoHn6wnxBVWvtq', 'sekre', NULL, '2026-02-24 01:17:56', '2026-02-24 01:17:56'),
(14, '200103272023022001', '$2y$12$yQWXA9yV5c9PaA65kyjFxO9SbbKHdZGI4.oCmCIrxouVrTkFs0AwK', 'pegawai', NULL, '2026-02-24 01:50:46', '2026-02-24 01:50:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buku_tamu`
--
ALTER TABLE `buku_tamu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buku_tamu_pegawai_kppn_id_foreign` (`pegawai_kppn_id`);

--
-- Indexes for table `cuti_tambahan`
--
ALTER TABLE `cuti_tambahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuti_tambahan_pegawai_id_foreign` (`pegawai_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `kepala_kantor`
--
ALTER TABLE `kepala_kantor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pegawai_nip_unique` (`nip`),
  ADD KEY `pegawai_user_id_foreign` (`user_id`),
  ADD KEY `pegawai_seksi_id_foreign` (`seksi_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `seksi`
--
ALTER TABLE `seksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangs`
--
ALTER TABLE `barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `buku_tamu`
--
ALTER TABLE `buku_tamu`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cuti_tambahan`
--
ALTER TABLE `cuti_tambahan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kepala_kantor`
--
ALTER TABLE `kepala_kantor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seksi`
--
ALTER TABLE `seksi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku_tamu`
--
ALTER TABLE `buku_tamu`
  ADD CONSTRAINT `buku_tamu_pegawai_kppn_id_foreign` FOREIGN KEY (`pegawai_kppn_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cuti_tambahan`
--
ALTER TABLE `cuti_tambahan`
  ADD CONSTRAINT `cuti_tambahan_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_seksi_id_foreign` FOREIGN KEY (`seksi_id`) REFERENCES `seksi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pegawai_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
