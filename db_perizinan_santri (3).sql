-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Sep 2025 pada 02.14
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
-- Database: `db_perizinan_santri`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `blocked_times`
--

CREATE TABLE `blocked_times` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `day_of_week` tinyint(4) NOT NULL,
  `time_slot` tinyint(4) NOT NULL,
  `reason` varchar(255) DEFAULT NULL COMMENT 'Contoh: Upacara Bendera',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `blocked_times`
--

INSERT INTO `blocked_times` (`id`, `day_of_week`, `time_slot`, `reason`, `created_at`, `updated_at`) VALUES
(9, 1, 1, NULL, '2025-08-31 03:06:48', '2025-08-31 03:06:48'),
(10, 3, 7, NULL, '2025-08-31 03:06:48', '2025-08-31 03:06:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `catatan_harians`
--

CREATE TABLE `catatan_harians` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `catatan` text NOT NULL,
  `dicatat_oleh_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `catatan_harians`
--

INSERT INTO `catatan_harians` (`id`, `santri_id`, `tanggal`, `catatan`, `dicatat_oleh_id`, `created_at`, `updated_at`) VALUES
(2, 8, '2025-08-26', 'faqih hari ini sedang belajar bahasa inggris', 1, '2025-08-26 03:33:47', '2025-08-26 03:33:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hour_priorities`
--

CREATE TABLE `hour_priorities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject_category` varchar(255) NOT NULL COMMENT 'Merujuk ke kolom category di mata_pelajarans',
  `day_of_week` tinyint(4) NOT NULL,
  `time_slot` tinyint(4) NOT NULL,
  `is_allowed` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `hour_priorities`
--

INSERT INTO `hour_priorities` (`id`, `subject_category`, `day_of_week`, `time_slot`, `is_allowed`, `created_at`, `updated_at`) VALUES
(10, 'Umum', 1, 2, 1, '2025-08-31 03:06:48', '2025-08-31 03:06:48'),
(11, 'Diniyah', 1, 2, 1, '2025-08-31 03:06:48', '2025-08-31 03:06:48'),
(12, 'Diniyah', 1, 3, 1, '2025-08-31 03:06:48', '2025-08-31 03:06:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatans`
--

CREATE TABLE `jabatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_jabatan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jabatans`
--

INSERT INTO `jabatans` (`id`, `nama_jabatan`, `created_at`, `updated_at`) VALUES
(1, 'Wali Kelas Putra', '2025-08-25 20:38:36', '2025-08-25 20:38:36'),
(2, 'Wali Kelas Putri', '2025-08-25 20:38:46', '2025-08-25 20:38:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan_user`
--

CREATE TABLE `jabatan_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `jabatan_id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(9) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jabatan_user`
--

INSERT INTO `jabatan_user` (`id`, `user_id`, `kelas_id`, `jabatan_id`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(6, 7, 18, 1, '2025/2026', '2025-08-27 23:18:56', '2025-08-27 23:18:56'),
(7, 12, 5, 2, '2025/2026', '2025-08-28 01:52:28', '2025-08-28 01:52:28'),
(8, 5, 9, 1, '2025/2026', '2025-08-28 01:53:55', '2025-08-28 01:53:55'),
(9, 13, 6, 2, '2025/2026', '2025-08-28 02:06:45', '2025-08-28 02:06:45'),
(11, 1, 15, 1, '2025/2026', '2025-09-23 20:44:10', '2025-09-23 20:44:10'),
(13, 1, 17, 1, '2025/2026', '2025-09-23 20:46:42', '2025-09-23 20:46:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `tingkatan` varchar(255) DEFAULT NULL,
  `total_jp_sehari` int(11) NOT NULL DEFAULT 7,
  `is_active_for_scheduling` tinyint(1) NOT NULL DEFAULT 1,
  `level` int(11) DEFAULT NULL COMMENT 'Tingkat kelas, cth: 7, 8, 9',
  `parallel_name` varchar(255) DEFAULT NULL COMMENT 'Nama paralel, cth: A, B, Putra A',
  `kurikulum_template_id` bigint(20) UNSIGNED DEFAULT NULL,
  `room_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkatan`, `total_jp_sehari`, `is_active_for_scheduling`, `level`, `parallel_name`, `kurikulum_template_id`, `room_id`, `created_at`, `updated_at`) VALUES
(3, '1A', '1', 7, 1, NULL, NULL, 1, 9, '2025-08-24 09:32:21', '2025-09-23 21:44:43'),
(4, '1B', '1', 7, 1, NULL, NULL, 1, 10, '2025-08-24 09:34:09', '2025-09-23 21:44:58'),
(5, '1C', '1', 7, 1, NULL, NULL, 1, 8, '2025-08-24 09:34:16', '2025-09-23 21:26:08'),
(6, '2A', '2', 7, 1, NULL, NULL, 2, 11, '2025-08-24 09:34:34', '2025-09-23 21:45:13'),
(7, '2B', '2', 7, 1, NULL, NULL, 2, 12, '2025-08-24 09:34:38', '2025-09-23 21:45:39'),
(8, '2C', '2', 7, 1, NULL, NULL, 2, 13, '2025-08-24 09:34:43', '2025-09-23 21:45:54'),
(9, '3A', '5', 7, 1, NULL, NULL, 4, 13, '2025-08-24 09:34:51', '2025-09-23 21:46:10'),
(10, '3B', '5', 7, 1, NULL, NULL, 4, 14, '2025-08-24 09:35:01', '2025-09-23 21:46:23'),
(12, '4A', '5', 7, 1, NULL, NULL, 5, 15, '2025-08-24 09:35:37', '2025-09-23 21:46:38'),
(13, '4B', '5', 7, 1, NULL, NULL, 5, 3, '2025-08-24 09:35:44', '2025-09-23 21:46:53'),
(14, '5A', '5', 7, 1, NULL, NULL, 6, 2, '2025-08-24 09:35:51', '2025-09-23 21:47:06'),
(15, '5B', '5', 7, 1, NULL, NULL, 6, 17, '2025-08-24 09:35:58', '2025-09-23 20:43:48'),
(16, '6A', '6', 7, 1, NULL, NULL, 3, 18, '2025-08-24 09:36:04', '2025-09-23 21:17:46'),
(17, '6B', '6', 7, 1, NULL, NULL, 3, 16, '2025-08-24 09:36:10', '2025-09-23 20:42:45'),
(18, '4Int', '5', 7, 1, NULL, NULL, 5, 4, '2025-08-26 10:19:07', '2025-09-23 21:47:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas_mata_pelajaran`
--

CREATE TABLE `kelas_mata_pelajaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `mata_pelajaran_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kelas_mata_pelajaran`
--

INSERT INTO `kelas_mata_pelajaran` (`id`, `kelas_id`, `mata_pelajaran_id`, `teacher_id`, `created_at`, `updated_at`) VALUES
(1, 17, 2, 1, NULL, NULL),
(2, 17, 1, 1, NULL, NULL),
(3, 16, 1, 1, NULL, NULL),
(4, 16, 2, 1, NULL, NULL),
(5, 4, 5, 2, NULL, NULL),
(6, 5, 5, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kurikulum_templates`
--

CREATE TABLE `kurikulum_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_template` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kurikulum_templates`
--

INSERT INTO `kurikulum_templates` (`id`, `nama_template`, `created_at`, `updated_at`) VALUES
(1, 'Kurikulum Kelas 1', '2025-08-27 01:52:12', '2025-08-27 01:52:12'),
(2, 'Kurikulum Kelas 2', '2025-08-27 02:05:39', '2025-08-27 02:05:39'),
(3, 'Kurikulum Kelas 6', '2025-08-27 02:11:57', '2025-08-27 02:11:57'),
(4, 'Kurikulum Kelas 3', '2025-08-27 02:13:55', '2025-08-27 02:13:55'),
(5, 'Kurikulum Kelas 4', '2025-08-27 02:15:40', '2025-08-27 02:15:40'),
(6, 'Kurikulum Kelas 5', '2025-08-30 22:57:31', '2025-08-30 22:57:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_pelajarans`
--

CREATE TABLE `mata_pelajarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_pelajaran` varchar(255) NOT NULL,
  `tingkatan` varchar(255) NOT NULL DEFAULT 'Umum',
  `duration_jp` int(11) NOT NULL COMMENT 'Durasi dalam Jam Pelajaran (JP)',
  `requires_special_room` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Apakah butuh ruang khusus seperti lab',
  `kategori` enum('Umum','Diniyah') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mata_pelajarans`
--

INSERT INTO `mata_pelajarans` (`id`, `nama_pelajaran`, `tingkatan`, `duration_jp`, `requires_special_room`, `kategori`, `created_at`, `updated_at`) VALUES
(1, 'Matematika', '6', 2, 0, 'Umum', '2025-08-25 02:09:41', '2025-09-23 20:31:47'),
(2, 'Faraidh', '6', 1, 0, 'Diniyah', '2025-08-25 02:09:52', '2025-09-23 21:55:39'),
(3, 'Nahwu', '2', 2, 0, 'Diniyah', '2025-08-26 22:20:04', '2025-08-31 05:03:04'),
(4, 'English Lesson', '1', 3, 0, 'Umum', '2025-08-26 22:21:08', '2025-08-31 04:28:04'),
(5, 'Bahasa Indonesia', '1', 1, 0, 'Umum', '2025-08-27 02:14:44', '2025-09-23 21:25:52'),
(6, 'TIK', '1', 1, 1, 'Umum', '2025-08-31 01:44:50', '2025-08-31 04:28:04'),
(7, 'Mahfudzot', '1', 2, 0, 'Diniyah', '2025-08-31 01:48:53', '2025-08-31 04:28:04'),
(8, 'Matematika', '5', 2, 0, 'Umum', '2025-08-31 04:11:26', '2025-08-31 04:28:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_pelajaran_teacher`
--

CREATE TABLE `mata_pelajaran_teacher` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mata_pelajaran_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mata_pelajaran_teacher`
--

INSERT INTO `mata_pelajaran_teacher` (`id`, `mata_pelajaran_id`, `teacher_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL),
(2, 1, 1, NULL, NULL),
(3, 5, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_pelajaran_user`
--

CREATE TABLE `mata_pelajaran_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `mata_pelajaran_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mata_pelajaran_user`
--

INSERT INTO `mata_pelajaran_user` (`user_id`, `mata_pelajaran_id`) VALUES
(1, 1),
(1, 2),
(1, 5),
(6, 4),
(6, 6),
(10, 3),
(10, 5),
(11, 4),
(12, 8),
(13, 7),
(15, 4),
(16, 4),
(17, 3),
(18, 5),
(19, 3),
(21, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_23_231753_create_santris_table', 1),
(5, '2025_08_23_231926_create_perizinans_table', 1),
(6, '2025_08_24_005700_create_kelas_table', 2),
(7, '2025_08_24_005730_update_santris_table_for_kelas_relation', 2),
(8, '2025_08_24_084018_create_pelanggarans_table', 3),
(9, '2025_08_25_085902_create_mata_pelajarans_table', 4),
(10, '2025_08_25_091153_create_nilais_table', 5),
(11, '2025_08_25_102022_make_nis_and_rayon_nullable_in_santris_table', 6),
(12, '2025_08_25_131108_add_wali_fields_to_santris_table', 7),
(13, '2025_08_25_172941_add_wali_santri_role_to_users_table', 8),
(14, '2025_08_26_031806_create_jabatans_table', 9),
(15, '2025_08_26_031809_create_jabatan_users_table', 9),
(16, '2025_08_26_031931_add_jenis_kelamin_to_santris_table', 9),
(17, '2025_08_26_054956_create_catatan_harians_table', 10),
(18, '2025_08_26_054958_create_prestasis_table', 10),
(19, '2025_08_27_080717_create_kelas_mata_pelajaran_table', 11),
(20, '2025_08_27_083713_create_kurikulum_templates_table', 12),
(21, '2025_08_27_083715_create_kurikulum_template_mata_pelajaran_table', 12),
(22, '2025_08_27_083826_add_kurikulum_template_id_to_kelas_table', 12),
(23, '2025_08_31_020532_add_teacher_code_to_users_table', 13),
(24, '2025_08_31_020557_add_scheduling_fields_to_mata_pelajarans_table', 13),
(25, '2025_08_31_020605_add_scheduling_fields_to_kelas_table', 13),
(26, '2025_08_31_020829_create_rooms_table', 14),
(27, '2025_08_31_020830_create_teacher_unavailabilities_table', 14),
(28, '2025_08_31_020831_create_blocked_times_table', 14),
(29, '2025_08_31_020831_create_hour_priorities_table', 14),
(30, '2025_08_31_020833_create_schedules_table', 14),
(31, '2025_08_31_024117_rename_user_id_to_teacher_id_in_teacher_unavailabilities_table', 15),
(32, '2025_08_31_035332_ensure_scheduling_fields_exist_in_kelas_table', 16),
(33, '2025_08_31_051933_create_mata_pelajaran_user_table', 17),
(34, '2025_08_31_061023_rename_user_id_to_teacher_id_in_schedules_table', 18),
(35, '2025_08_31_061207_make_version_id_nullable_in_schedules_table', 19),
(36, '2025_08_31_084354_clean_up_category_column_in_mata_pelajarans_table', 20),
(37, '2025_08_31_092251_add_teacher_id_to_kelas_mata_pelajaran_table', 21),
(38, '2025_08_31_104416_add_duration_jp_to_kelas_mata_pelajaran_table', 22),
(39, '2025_08_31_083948_ensure_kategori_column_name_in_mata_pelajarans_table', 23),
(40, '2025_08_31_110519_add_tingkatan_to_mata_pelajarans_table', 23),
(41, '2025_09_03_150846_add_room_id_to_kelas_table', 24),
(42, '2025_09_04_065219_remove_room_id_from_kelas_table', 24),
(43, '2025_09_04_145545_add_room_id_to_kelas_table', 24),
(44, '2025_09_16_122301_create_teachers_table', 24),
(46, '2025_09_24_022455_create_teachers_table', 25),
(47, '2025_09_24_025326_create_kelas_mata_pelajaran_table', 26),
(48, '2025_09_24_033055_create_mata_pelajaran_teacher_table', 27),
(49, '2025_09_24_034218_add_tingkatan_to_kelas_table', 28),
(50, '2025_09_24_041631_modify_teacher_id_in_kelas_mata_pelajaran_table', 29);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilais`
--

CREATE TABLE `nilais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `mata_pelajaran_id` bigint(20) UNSIGNED NOT NULL,
  `nilai_tugas` int(10) UNSIGNED DEFAULT NULL,
  `nilai_uts` int(10) UNSIGNED DEFAULT NULL,
  `nilai_uas` int(10) UNSIGNED DEFAULT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `tahun_ajaran` varchar(9) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `nilais`
--

INSERT INTO `nilais` (`id`, `santri_id`, `kelas_id`, `mata_pelajaran_id`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `semester`, `tahun_ajaran`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(3, 8, 3, 1, 70, 65, 70, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(4, 20, 3, 1, 65, 60, 75, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(5, 9, 3, 1, 70, 75, 65, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(6, 21, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(7, 22, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(8, 10, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(9, 11, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(10, 23, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(11, 24, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(12, 12, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(13, 25, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(14, 13, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(15, 26, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(16, 31, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(17, 27, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(18, 14, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(19, 15, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(20, 16, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(21, 17, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(22, 28, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(23, 29, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(24, 30, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(25, 18, 3, 1, NULL, 75, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-28 07:50:43'),
(26, 19, 3, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-25 03:51:18', '2025-08-25 03:51:18'),
(27, 226, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(28, 237, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(29, 238, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(30, 227, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(31, 239, 12, 3, 85, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-27 02:28:22'),
(32, 228, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(33, 229, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(34, 230, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(35, 240, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(36, 231, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(37, 241, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(38, 232, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(39, 233, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(40, 234, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(41, 235, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(42, 242, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(43, 243, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(44, 236, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(45, 244, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(46, 245, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(47, 246, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(48, 247, 12, 3, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:38', '2025-08-26 22:20:38'),
(49, 226, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:52', '2025-08-26 22:20:52'),
(50, 237, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:52', '2025-08-26 22:20:52'),
(51, 238, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:52', '2025-08-26 22:20:52'),
(52, 227, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:52', '2025-08-26 22:20:52'),
(53, 239, 12, 1, 75, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:52', '2025-08-26 22:20:52'),
(54, 228, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:52', '2025-08-26 22:20:52'),
(55, 229, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(56, 230, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(57, 240, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(58, 231, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(59, 241, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(60, 232, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(61, 233, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(62, 234, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(63, 235, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(64, 242, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(65, 243, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(66, 236, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(67, 244, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(68, 245, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(69, 246, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(70, 247, 12, 1, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:20:53', '2025-08-26 22:20:53'),
(71, 226, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(72, 237, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(73, 238, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(74, 227, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(75, 239, 12, 4, 70, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(76, 228, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(77, 229, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(78, 230, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(79, 240, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(80, 231, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(81, 241, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(82, 232, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(83, 233, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(84, 234, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(85, 235, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(86, 242, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(87, 243, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(88, 236, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(89, 244, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(90, 245, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(91, 246, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(92, 247, 12, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-26 22:21:36', '2025-08-26 22:21:36'),
(93, 8, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(94, 20, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(95, 9, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(96, 21, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(97, 22, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(98, 10, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(99, 11, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(100, 23, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(101, 24, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(102, 12, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(103, 25, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(104, 13, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(105, 26, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(106, 31, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(107, 27, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(108, 14, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(109, 15, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(110, 16, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(111, 17, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(112, 28, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(113, 29, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(114, 30, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(115, 18, 3, 5, NULL, 80, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(116, 19, 3, 5, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:14', '2025-08-28 07:50:14'),
(117, 8, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(118, 20, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(119, 9, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(120, 21, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(121, 22, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(122, 10, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(123, 11, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(124, 23, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(125, 24, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(126, 12, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(127, 25, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(128, 13, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(129, 26, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(130, 31, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(131, 27, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(132, 14, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(133, 15, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(134, 16, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(135, 17, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(136, 28, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(137, 29, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(138, 30, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(139, 18, 3, 4, NULL, 80, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29'),
(140, 19, 3, 4, NULL, NULL, NULL, 'Ganjil', '2025/2026', 1, 1, '2025-08-28 07:50:29', '2025-08-28 07:50:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggarans`
--

CREATE TABLE `pelanggarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_pelanggaran` varchar(255) NOT NULL,
  `tanggal_kejadian` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `dicatat_oleh` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pelanggarans`
--

INSERT INTO `pelanggarans` (`id`, `santri_id`, `jenis_pelanggaran`, `tanggal_kejadian`, `keterangan`, `dicatat_oleh`, `created_at`, `updated_at`) VALUES
(3, 250, 'Keluar tanpa izin', '2025-08-20', NULL, 'Fauzi', '2025-08-28 00:49:27', '2025-08-28 00:49:27'),
(4, 426, 'Pelanggar Berat', '2025-08-29', 'Main laptop di dalam kamar', 'Fachril Ramadan', '2025-08-29 10:47:52', '2025-08-29 10:47:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `perizinans`
--

CREATE TABLE `perizinans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_izin` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_akhir` date DEFAULT NULL,
  `status` enum('aktif','selesai','terlambat') NOT NULL DEFAULT 'aktif',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `perizinans`
--

INSERT INTO `perizinans` (`id`, `santri_id`, `jenis_izin`, `kategori`, `keterangan`, `tanggal_mulai`, `tanggal_akhir`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(11, 390, 'Izin Piket Rayon', 'Pengasuhan', 'Ketua piket rayon', '2025-08-27', NULL, 'selesai', 5, NULL, '2025-08-27 23:45:59', '2025-08-28 06:07:04'),
(12, 200, 'Izin Piket Rayon', 'Pengasuhan', 'Alfarobi', '2025-08-28', NULL, 'selesai', 5, NULL, '2025-08-28 00:20:37', '2025-08-29 00:00:06'),
(13, 226, 'Izin Piket Rayon', 'Pengasuhan', 'Bakes', '2025-08-28', NULL, 'selesai', 5, NULL, '2025-08-28 00:21:35', '2025-08-29 00:00:06'),
(14, 251, 'Izin Piket Rayon', 'Pengasuhan', 'Bakes', '2025-08-28', NULL, 'selesai', 5, NULL, '2025-08-28 00:22:02', '2025-08-29 00:00:06'),
(15, 129, 'Izin Piket Rayon', 'Pengasuhan', 'Atthaillah', '2025-08-28', NULL, 'selesai', 5, NULL, '2025-08-28 00:22:34', '2025-08-29 00:00:06'),
(16, 146, 'Izin Piket Rayon', 'Pengasuhan', 'Atthaillah', '2025-08-28', NULL, 'selesai', 5, NULL, '2025-08-28 00:23:01', '2025-08-29 00:00:06'),
(17, 166, 'Izin Piket Rayon', 'Pengasuhan', 'Alfarobi', '2025-08-28', NULL, 'selesai', 5, NULL, '2025-08-28 00:23:24', '2025-08-29 00:00:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `prestasis`
--

CREATE TABLE `prestasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `nama_prestasi` varchar(255) NOT NULL,
  `poin` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `dicatat_oleh_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `prestasis`
--

INSERT INTO `prestasis` (`id`, `santri_id`, `nama_prestasi`, `poin`, `tanggal`, `keterangan`, `dicatat_oleh_id`, `created_at`, `updated_at`) VALUES
(1, 31, 'Menjadi Peserta Terbaik Pidato Bahasa Arab', 90, '2025-08-26', NULL, 1, '2025-08-25 23:41:02', '2025-08-25 23:41:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Biasa','Khusus') NOT NULL DEFAULT 'Biasa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Lab Komputer', 'Khusus', '2025-08-30 19:44:18', '2025-08-30 19:44:18'),
(2, 'Ibnu Khaldun 2', 'Biasa', '2025-08-30 22:30:25', '2025-08-30 22:31:04'),
(3, 'Ibnu Khaldun 1', 'Biasa', '2025-08-30 22:30:53', '2025-08-30 22:30:53'),
(4, 'Ibnu Khaldun 3', 'Biasa', '2025-08-30 22:31:16', '2025-08-30 22:31:16'),
(5, 'Ibnu Khaldun 4', 'Biasa', '2025-08-30 22:33:17', '2025-08-30 22:33:17'),
(6, 'Ibnu Khaldun 5', 'Biasa', '2025-08-30 22:33:28', '2025-08-30 22:33:28'),
(7, 'Ibnu Khaldun 6', 'Biasa', '2025-08-30 22:33:38', '2025-08-30 22:33:38'),
(8, 'Al Ghazali 1', 'Biasa', '2025-08-30 22:34:01', '2025-08-30 22:34:01'),
(9, 'Al Ghazali 2', 'Biasa', '2025-08-30 22:34:09', '2025-08-30 22:34:09'),
(10, 'Al Ghazali 3', 'Biasa', '2025-08-30 22:34:16', '2025-08-30 22:34:16'),
(11, 'Al Ghazali 4', 'Biasa', '2025-08-30 22:34:23', '2025-08-30 22:34:23'),
(12, 'Al Ghazali 5', 'Biasa', '2025-08-30 22:34:33', '2025-08-30 22:34:33'),
(13, 'Al Ghazali 6', 'Biasa', '2025-08-30 22:34:41', '2025-08-30 22:34:41'),
(14, 'Al Ghazali 7', 'Biasa', '2025-08-30 22:34:48', '2025-08-30 22:34:48'),
(15, 'Al Ghazali 8', 'Biasa', '2025-08-30 22:34:56', '2025-08-30 22:34:56'),
(16, 'Al Farobi 301', 'Biasa', '2025-08-30 22:35:30', '2025-08-30 22:35:30'),
(17, 'Al Farobi 302', 'Biasa', '2025-08-30 22:35:36', '2025-08-30 22:35:36'),
(18, 'Al Farobi 303', 'Biasa', '2025-08-30 22:35:44', '2025-08-30 22:35:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `santris`
--

CREATE TABLE `santris` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wali_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_registrasi_wali` varchar(255) DEFAULT NULL,
  `nis` varchar(255) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` enum('Putra','Putri') NOT NULL,
  `rayon` varchar(255) DEFAULT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `santris`
--

INSERT INTO `santris` (`id`, `wali_id`, `kode_registrasi_wali`, `nis`, `nama`, `jenis_kelamin`, `rayon`, `kelas_id`, `foto`, `created_at`, `updated_at`) VALUES
(8, NULL, 'WALI-Q5NO4QYE', NULL, 'A. Faqih Muqaddas', 'Putra', NULL, 3, NULL, '2025-08-25 03:20:59', '2025-08-26 09:41:50'),
(9, NULL, 'WALI-NHP4VAVE', NULL, 'Aditya Firdaus Kurniawan', 'Putra', NULL, 3, NULL, '2025-08-25 03:21:30', '2025-08-25 06:33:04'),
(10, NULL, 'WALI-BTEGDPTL', NULL, 'Erlan Hamed', 'Putra', NULL, 3, NULL, '2025-08-25 03:21:49', '2025-08-25 06:33:04'),
(11, NULL, 'WALI-DJ5NJSZQ', NULL, 'Fadhil Muhammad', 'Putra', NULL, 3, NULL, '2025-08-25 03:22:04', '2025-08-25 06:33:04'),
(12, NULL, 'WALI-AFAPJNGZ', NULL, 'Fardhan Arfa Gunawan', 'Putra', NULL, 3, NULL, '2025-08-25 03:22:19', '2025-08-25 06:33:04'),
(13, NULL, 'WALI-REMLVMXM', NULL, 'Hugo Iftikhar Andiego', 'Putra', NULL, 3, NULL, '2025-08-25 03:22:52', '2025-08-25 06:33:04'),
(14, NULL, 'WALI-S9EER6HP', NULL, 'M. Azummi Prayitno', 'Putra', NULL, 3, NULL, '2025-08-25 03:23:15', '2025-08-25 06:33:05'),
(15, NULL, 'WALI-1PZE9UDP', NULL, 'M. Maulana Karim Hamzah', 'Putra', NULL, 3, NULL, '2025-08-25 03:23:30', '2025-08-25 06:33:05'),
(16, NULL, 'WALI-XKFPPTZF', NULL, 'Muhammad Azril Darmawan', 'Putra', NULL, 3, NULL, '2025-08-25 03:23:47', '2025-08-25 06:33:05'),
(17, NULL, 'WALI-OHXJFKUP', NULL, 'Muhammad Najla\'a Alhadzik', 'Putra', NULL, 3, NULL, '2025-08-25 03:24:16', '2025-08-25 06:33:05'),
(18, NULL, 'WALI-9AWUUJJX', NULL, 'Sunan Gunung Jati', 'Putra', NULL, 3, NULL, '2025-08-25 03:24:26', '2025-08-25 06:33:05'),
(19, NULL, 'WALI-MX1OTCNH', NULL, 'Wildan Khoir Assukri', 'Putra', NULL, 3, NULL, '2025-08-25 03:24:54', '2025-08-25 06:33:05'),
(20, NULL, 'WALI-QSWOYZXQ', NULL, 'Adia Putri Ramadhani', 'Putra', NULL, 3, NULL, '2025-08-25 03:25:20', '2025-08-25 06:33:05'),
(21, NULL, 'WALI-BN1TPDJS', NULL, 'Auliya Rheisya Oktavia', 'Putra', NULL, 3, NULL, '2025-08-25 03:25:47', '2025-08-25 06:33:05'),
(22, NULL, 'WALI-ZJENTZHT', NULL, 'Devika Amara Ghea', 'Putra', NULL, 3, NULL, '2025-08-25 03:26:07', '2025-08-25 06:33:05'),
(23, NULL, 'WALI-3Q7C3YWT', NULL, 'Fakhira Sakhi Irawan', 'Putra', NULL, 3, NULL, '2025-08-25 03:26:23', '2025-08-25 06:33:05'),
(24, NULL, 'WALI-X4ZV5LCQ', NULL, 'Farah Nafisah Hasanah', 'Putra', NULL, 3, NULL, '2025-08-25 03:26:38', '2025-08-25 06:33:05'),
(25, NULL, 'WALI-NYWKVRQC', NULL, 'Haura Syaqilah', 'Putra', NULL, 3, NULL, '2025-08-25 03:26:54', '2025-08-25 06:33:05'),
(26, NULL, 'WALI-ASUTHFGR', NULL, 'Jihan Nayfatul Ramadani', 'Putra', NULL, 3, NULL, '2025-08-25 03:27:17', '2025-08-25 06:33:05'),
(27, NULL, 'WALI-MRLUAYIU', NULL, 'Kirania Sekar Gumintang', 'Putra', NULL, 3, NULL, '2025-08-25 03:27:41', '2025-08-25 06:33:05'),
(28, NULL, 'WALI-Z5CATNQK', NULL, 'Putri Bintang Hooriya', 'Putra', NULL, 3, NULL, '2025-08-25 03:28:06', '2025-08-25 06:33:05'),
(29, NULL, 'WALI-JN6K4RKV', NULL, 'Raden Kaysah Zahidah Zahsy', 'Putra', NULL, 3, NULL, '2025-08-25 03:28:42', '2025-08-25 06:33:05'),
(30, NULL, 'WALI-1QPOTP8H', NULL, 'Ruqoyah Tri Julianingsih', 'Putra', NULL, 3, NULL, '2025-08-25 03:29:07', '2025-08-25 06:33:05'),
(31, NULL, 'WALI-6NKEMTMT', NULL, 'Juni Hamdah', 'Putra', NULL, 3, NULL, '2025-08-25 03:29:18', '2025-08-25 10:30:09'),
(32, NULL, 'WALI-RB5R1VAJ', NULL, 'Alvian Delvin Saputra', 'Putra', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(33, NULL, 'WALI-ZK4QX4DW', NULL, 'Fathan Firmansyah Putra ', 'Putra', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(34, NULL, 'WALI-FSC9GHSD', NULL, 'M. Hatik Aldiansyah', 'Putra', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(35, NULL, 'WALI-DP9CXABI', NULL, 'M. Revan Alfiansyah', 'Putra', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(36, NULL, 'WALI-AFA0LSAB', NULL, 'Mahesa Putra Adhie Nugroho ', 'Putra', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(37, NULL, 'WALI-VN0IAXNN', NULL, 'Maulana Yusuf ', 'Putra', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(38, NULL, 'WALI-L6IWSV1S', NULL, 'Muhammad Chairul Ghani Guci', 'Putra', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(39, NULL, 'WALI-QZV78CVE', NULL, 'Muhammad Rafardhan Al - Andini ', 'Putra', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(40, NULL, 'WALI-HISMXNLQ', NULL, 'Muhammad Thoriq Al Azam', 'Putra', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(41, NULL, 'WALI-HN2VVDQ2', NULL, 'Ripa\'i', 'Putra', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(42, NULL, 'WALI-RNGVRXID', NULL, 'Rofarfi Al Haedar ', 'Putra', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(43, NULL, 'WALI-EEEDQNCR', NULL, 'Afika Ziviliani', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(44, NULL, 'WALI-EO3HQOOX', NULL, 'Ayra Alisha Putri ', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(45, NULL, 'WALI-O9AF7FY5', NULL, 'Azzahra Erilda Sari', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(46, NULL, 'WALI-XZMYYF61', NULL, 'Hana Khairunnisa', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(47, NULL, 'WALI-ULLVIMIS', NULL, 'Hellena Salsabila', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(48, NULL, 'WALI-0BZOVYAX', NULL, 'Lecyana Cantika Zahra', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(49, NULL, 'WALI-HIZDBRXX', NULL, 'Naura Ufaira Azzahra ', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(50, NULL, 'WALI-XWQ8MVZE', NULL, 'Nayla Fawwazah', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(51, NULL, 'WALI-BGIY7LIF', NULL, 'Rara Ayu Meyfianti', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(52, NULL, 'WALI-YEEOWFG7', NULL, 'Ratu Hana Humaira', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(53, NULL, 'WALI-HAJUZ2QI', NULL, 'Siti Nurul \'Izzati', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(54, NULL, 'WALI-KXYIKIYU', NULL, 'Syifa Aulia Azzahra', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(55, NULL, 'WALI-BWTPMYHN', NULL, 'Aina Talita Zahra', 'Putri', NULL, 4, NULL, '2025-08-26 10:09:39', '2025-08-26 10:28:03'),
(79, NULL, 'WALI-QD0NYL8A', NULL, 'Ahmad Faris ', 'Putra', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:03'),
(80, NULL, 'WALI-P4PVI8CQ', NULL, 'Azka Aldric', 'Putra', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:03'),
(81, NULL, 'WALI-UVGSBGJF', NULL, 'Azmi Akazya Sulaeman', 'Putra', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(82, NULL, 'WALI-QQFJNMPA', NULL, 'Banu Mibras Naufal', 'Putra', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(83, NULL, 'WALI-XYGM283C', NULL, 'Fathan Fasya Hadziqi', 'Putra', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(84, NULL, 'WALI-KJQZYZBE', NULL, 'M. Rasyid', 'Putra', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(85, NULL, 'WALI-WAAGEMOO', NULL, 'Muhammad Zain Nuryadin', 'Putra', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(86, NULL, 'WALI-HLFALQ4K', NULL, 'Putera Dwi Wijaya', 'Putra', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(87, NULL, 'WALI-TYP5KSO8', NULL, 'Raju Harsyadillah', 'Putra', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(88, NULL, 'WALI-3CDNNBU1', NULL, 'Razka Akmal Lazuardi ', 'Putra', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(89, NULL, 'WALI-ZGRRXYJX', NULL, 'Saudin', 'Putra', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(90, NULL, 'WALI-KNI3FPI6', NULL, 'Azkia Siti Nurlatifah', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(91, NULL, 'WALI-CNT8A0TV', NULL, 'Fathin Darwisyah Putri', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(92, NULL, 'WALI-WSMT3JA0', NULL, 'Fatiya Sholehah Hidayaty', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(93, NULL, 'WALI-QFJMJ5Q8', NULL, 'Filza Adzkiya', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(94, NULL, 'WALI-IYDP0UJM', NULL, 'Indira Ghina Suhendra', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(95, NULL, 'WALI-DPHVQ7M4', NULL, 'Nadzirah Rangkuti ', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(96, NULL, 'WALI-7QKCPWHK', NULL, 'Quina Aqila Fakhrudin', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(97, NULL, 'WALI-KHUZWLSJ', NULL, 'Siti Ismah Khoirunnisa Akhmad', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(98, NULL, 'WALI-MOEEGKDM', NULL, 'Siti Salma Salsabila', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(99, NULL, 'WALI-RNXTRBAH', NULL, 'Sri Desiana', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(100, NULL, 'WALI-SM7IFQIO', NULL, 'Talitha Fawwaz Maulida', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(101, NULL, 'WALI-NKWFGEDD', NULL, 'Zulpa Ruliyanah', 'Putri', NULL, 5, NULL, '2025-08-26 10:12:05', '2025-08-26 10:28:04'),
(102, NULL, 'WALI-N49EBFC7', NULL, 'Ahmad Farid Asyam', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(103, NULL, 'WALI-VUDRQORK', NULL, 'Amanagappa Ewako', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(104, NULL, 'WALI-GNS06EF0', NULL, 'Bintang Khaerul Alam', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(105, NULL, 'WALI-OJAUSY7S', NULL, 'Bintang Najwan Nugraha', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(106, NULL, 'WALI-HDKCURYO', NULL, 'Chaesar Nafhan Atabik Haffen', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(107, NULL, 'WALI-V6JJJYR9', NULL, 'Fatullah Nur Hafiz ', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(108, NULL, 'WALI-JF0IPVYU', NULL, 'Haidar Almairi Tsaqib', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(109, NULL, 'WALI-XEZQ73B5', NULL, 'M. Kahfi Majid', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(110, NULL, 'WALI-V95CY8CB', NULL, 'M. Khan Kaisan', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(111, NULL, 'WALI-ZEFZ4MS1', NULL, 'Mohamed Baarek Rajby', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(112, NULL, 'WALI-XMOKPMYP', NULL, 'Muhamad Haekal Zain', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(113, NULL, 'WALI-TPQ8QLOA', NULL, 'Muhammad Hafiz Aldiansyah', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(114, NULL, 'WALI-US49MQI7', NULL, 'Rhafisya Aldio Pratama', 'Putra', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(115, NULL, 'WALI-WWF5MVEQ', NULL, 'Amira Nadya Azzahra', 'Putri', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(116, NULL, 'WALI-XZGYGO45', NULL, 'Farah Noe Valinka', 'Putri', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(117, NULL, 'WALI-ATPBIYCV', NULL, 'Ida Faridatunnisa', 'Putri', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(118, NULL, 'WALI-QKRYEDTQ', NULL, 'Ihda Haura Paradis', 'Putri', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(119, NULL, 'WALI-UPH8GNYP', NULL, 'Naura Nazwa', 'Putri', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(120, NULL, 'WALI-EZL9KEXI', NULL, 'Naysilla Farrannisa', 'Putri', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(121, NULL, 'WALI-SFUKLVY2', NULL, 'Queenza Salsabila Putri', 'Putri', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(122, NULL, 'WALI-JFDY4SGR', NULL, 'Radella Desra Putri', 'Putri', NULL, 6, NULL, '2025-08-26 10:13:53', '2025-08-26 10:28:04'),
(123, NULL, 'WALI-OQ1KJYVM', NULL, 'Achmad Shandya Niskala Nuswantara Poetra', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(124, NULL, 'WALI-QQGT2FSZ', NULL, 'Ahmad Syauqi Rabbani', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(125, NULL, 'WALI-PXOWB3YB', NULL, 'Al Farizy Nabih Afify', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(126, NULL, 'WALI-HTWVIGQV', NULL, 'Azhim Azhari', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(127, NULL, 'WALI-YXWHPD1P', NULL, 'M. Agna Muttaqien', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(128, NULL, 'WALI-YF8BZAUT', NULL, 'M. Fakhri Arrizqi', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(129, NULL, 'WALI-9SHITYER', NULL, 'Moh. Azmi Zidan Naufal ', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(130, NULL, 'WALI-AFBYSVGN', NULL, 'Muhammad Khoirul Anam', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(131, NULL, 'WALI-D2AA008D', NULL, 'Muhammad Nafiluzen Nasa\'I', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(132, NULL, 'WALI-TZFYELOK', NULL, 'Muhammad Wildan Al-Baidlowi', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(133, NULL, 'WALI-OUOVSKWV', NULL, 'Restu Ilham Kiandra', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(134, NULL, 'WALI-CYNL84LW', NULL, 'Sep Nijam Nurpahmi Muhazir', 'Putra', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(135, NULL, 'WALI-RAE2KXRG', NULL, 'Alisha Ramadhania Hendryana', 'Putri', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(136, NULL, 'WALI-N1YWNXYW', NULL, 'Arini Rahayu', 'Putri', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(137, NULL, 'WALI-KYAVBNJI', NULL, 'Asyifa Maheswari Margono', 'Putri', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(138, NULL, 'WALI-PT9T4UX0', NULL, 'Haifa Isfahana Hafni Fajriyati', 'Putri', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(139, NULL, 'WALI-JU1K6336', NULL, 'Najwa Mariam', 'Putri', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(140, NULL, 'WALI-3M6NVDZ5', NULL, 'Nur\'adila Oktaviani', 'Putri', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(141, NULL, 'WALI-NE3YYTVK', NULL, 'Riska Siti Aulia', 'Putri', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(142, NULL, 'WALI-SU3GJ6FZ', NULL, 'Safira Himatul Ilmi', 'Putri', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(143, NULL, 'WALI-BMLTSXP9', NULL, 'Safira Surya Ningtyas ', 'Putri', NULL, 7, NULL, '2025-08-26 10:14:54', '2025-08-26 10:28:04'),
(144, NULL, 'WALI-KYSJLLQC', NULL, 'Afnan Evan Al Husaini', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(145, NULL, 'WALI-QKJHOPLB', NULL, 'Ahmad Gias Al - Faqih Munawan', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(146, NULL, 'WALI-GYOECQTW', NULL, 'Aldo Raja Vidic Supriatna', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(147, NULL, 'WALI-CE1EN9IW', NULL, 'Asyam Nur Khairan', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(148, NULL, 'WALI-FLAVWBVZ', NULL, 'Fathan Atman Alfarezi', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(149, NULL, 'WALI-D7WTHJD1', NULL, 'Haikal Kandayas ', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(150, NULL, 'WALI-TTLF8FMC', NULL, 'Ibnu Imanullah', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(151, NULL, 'WALI-GSIDBEIM', NULL, 'Ibrahim Putra Herdinan', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(152, NULL, 'WALI-6XHQQUJV', NULL, 'Muhamad Agung Aldiansyah', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(153, NULL, 'WALI-VMKJIXVF', NULL, 'Muhamad Irham Yazid', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(154, NULL, 'WALI-XGGDTWA2', NULL, 'Muhammad Darwisy Naufal', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(155, NULL, 'WALI-BJSC8NK1', NULL, 'Rasya Arsyi Wirapratama', 'Putra', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(156, NULL, 'WALI-MCDC2R38', NULL, 'Annisa Luthfiah', 'Putri', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(157, NULL, 'WALI-U1JATQ4W', NULL, 'Auliya Cahya Ramadani', 'Putri', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(158, NULL, 'WALI-RYQV122B', NULL, 'Cinta Regina Putri', 'Putri', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(159, NULL, 'WALI-6JCFXHAW', NULL, 'Dewi Farida', 'Putri', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(160, NULL, 'WALI-3VZ7TBUW', NULL, 'Fakhira Zahratunnisa', 'Putri', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(161, NULL, 'WALI-ZAY30Q8C', NULL, 'Khalisa Aulia', 'Putri', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(162, NULL, 'WALI-JFDXQEKK', NULL, 'Olivia Cinta Haqiqi', 'Putri', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(163, NULL, 'WALI-TPHKCPSV', NULL, 'Qurota Ayun Maharani', 'Putri', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(164, NULL, 'WALI-FAYJXJIJ', NULL, 'Siti Nur hafidzoh', 'Putri', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(165, NULL, 'WALI-ILJDRBDM', NULL, 'Adelia Rakasiwi', 'Putri', NULL, 8, NULL, '2025-08-26 10:15:53', '2025-08-26 10:28:04'),
(166, NULL, 'WALI-OWQ3VDMA', NULL, 'Ahmad Faqih Munadi', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:04'),
(167, NULL, 'WALI-W0Z2DMUZ', NULL, 'Ahmad Naufal Dzul Fayidh', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:04'),
(168, NULL, 'WALI-NXGZ63R1', NULL, 'Alfarizi Bastian Ramadhani', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:04'),
(169, NULL, 'WALI-FV0DMAFC', NULL, 'Alwan Ghani Ali Rahman', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:04'),
(170, NULL, 'WALI-ZPLOY9K1', NULL, 'Billy Maliq Adillah', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:04'),
(171, NULL, 'WALI-UHZYE52W', NULL, 'Fakhry Zaki Alqodri', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:04'),
(172, NULL, 'WALI-1WBAW3UN', NULL, 'Ilham Sigit Maulana', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:04'),
(173, NULL, 'WALI-JTCDHCRY', NULL, 'Irham Fajar Putra Maulana', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:04'),
(174, NULL, 'WALI-SX24W88X', NULL, 'Keiro Ikrima', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:04'),
(175, NULL, 'WALI-NHXSPUK4', NULL, 'M.Rayhan Nugraha', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:04'),
(176, NULL, 'WALI-ZEFULAZR', NULL, 'Muhammad Ardya Difta Suherlan', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(177, NULL, 'WALI-RHPRV3D2', NULL, 'Muhammad Rafa Syazani', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(178, NULL, 'WALI-4HZ6MIQX', NULL, 'Pradika Aditya', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(179, NULL, 'WALI-XZZASWNI', NULL, 'Prince Anugerah Cahya Sumantri', 'Putra', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(180, NULL, 'WALI-ILPNPVBB', NULL, 'Firda Al Khosiyah Ramadani', 'Putri', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(181, NULL, 'WALI-2IJ974EL', NULL, 'Firya Al-fakhryiah', 'Putri', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(182, NULL, 'WALI-XG7FSYRM', NULL, 'Hayatunisa Al-faizah', 'Putri', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(183, NULL, 'WALI-95SGNROK', NULL, 'Naylah Fakhriyah', 'Putri', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(184, NULL, 'WALI-QB3GISQW', NULL, 'Putri Asya Aprilia', 'Putri', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(185, NULL, 'WALI-FURSVQHL', NULL, 'Salma Aulya Syarifah', 'Putri', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(186, NULL, 'WALI-LHTQASTM', NULL, 'Silvia Nanda Sofyan', 'Putri', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(187, NULL, 'WALI-8PCS4L8A', NULL, 'Tiara Wulan Sari', 'Putri', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(188, NULL, 'WALI-N7CGCFRL', NULL, 'Verah Noe Ayoudia Valinka', 'Putri', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(189, NULL, 'WALI-LQTOZUA4', NULL, 'Zahira Sannah Tri Susanti', 'Putri', NULL, 9, NULL, '2025-08-26 10:17:10', '2025-08-26 10:28:05'),
(190, NULL, 'WALI-JFM8QJ1P', NULL, 'A. Haikal Izzul Islam', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(191, NULL, 'WALI-OVHR1Z40', NULL, 'Alfan Fauzi', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(192, NULL, 'WALI-ZSD4KB8V', NULL, 'Alvin Putra Mutopik', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(193, NULL, 'WALI-QBNIHV1C', NULL, 'Anwarudin', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(194, NULL, 'WALI-DTTO3TWY', NULL, 'Dafa Anugrah', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(195, NULL, 'WALI-K1R6YW88', NULL, 'Daniel Pratama Adi Nugraha', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(196, NULL, 'WALI-VPTORSYC', NULL, 'Fadhirahman Javas Nararya', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(197, NULL, 'WALI-UD0MHSLG', NULL, 'Irfan Robiansyah', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(198, NULL, 'WALI-HKZTKEAV', NULL, 'Kasyfi Milzam Akbar Ali', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(199, NULL, 'WALI-64CN9IHR', NULL, 'Muhammad Agil', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(200, NULL, 'WALI-CLRI0RXP', NULL, 'Muhammad Al Ghazali', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(201, NULL, 'WALI-EPXNKC1Z', NULL, 'Muhammad Fahri Nurhadi', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(202, NULL, 'WALI-1BUSXTIW', NULL, 'Muhammad Rafa Aditya', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(203, NULL, 'WALI-K97GAV6N', NULL, 'Rifat Musyaffa Aria', 'Putra', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(204, NULL, 'WALI-K47UV7KU', NULL, 'Arum Aulya Mewarni', 'Putri', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(205, NULL, 'WALI-EGOVXYLR', NULL, 'Fitri Hayatun Nisa', 'Putri', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(206, NULL, 'WALI-ICEGQTDV', NULL, 'Ghaniya Artha Latiifah', 'Putri', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(207, NULL, 'WALI-2NVYNLRK', NULL, 'Intan Permatasari', 'Putri', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(208, NULL, 'WALI-1LI57JNL', NULL, 'Karima Khoerunnisa', 'Putri', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(209, NULL, 'WALI-Q94SC53E', NULL, 'Khalilah Adhawiyah Salsabila', 'Putri', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(210, NULL, 'WALI-MOGVB6FJ', NULL, 'Khansa Alkhila Zhafirah', 'Putri', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(211, NULL, 'WALI-WFBOPILV', NULL, 'Meysha Vidiani', 'Putri', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(212, NULL, 'WALI-7QFNXRRA', NULL, 'Putri Sri Handayani', 'Putri', NULL, 10, NULL, '2025-08-26 10:18:13', '2025-08-26 10:28:05'),
(213, NULL, 'WALI-BYDW4DFE', NULL, 'Fajrul Ilmi Ar - Rohimy', 'Putra', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-29 07:05:26'),
(214, NULL, 'WALI-Y9MR7ZF7', NULL, 'Firzaqi Ali Ramadhan ', 'Putra', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(215, NULL, 'WALI-ERDPXDXH', NULL, 'Kore Dima De Gama Rohi', 'Putra', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(216, NULL, 'WALI-8CWGBG64', NULL, 'M.Hafiz Arrasyid ', 'Putra', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(217, NULL, 'WALI-Q305I7EP', NULL, 'Muhamad Ahwaluddin Assoburi', 'Putra', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(218, NULL, 'WALI-MRI2C0XA', NULL, 'Muhammad Hafizh Ansharullah', 'Putra', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(219, NULL, 'WALI-T5GENYO4', NULL, 'Muhammad Qital Abdillah Andiego', 'Putra', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(220, NULL, 'WALI-RDVIY3D7', NULL, 'Muhammad Rivalno Hidayat', 'Putra', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(221, NULL, 'WALI-NFGJZH7D', NULL, 'Mujahidil Falah ', 'Putra', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(222, NULL, 'WALI-J5CRTS6H', NULL, 'Rafa Maula Rizky ', 'Putra', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(223, NULL, 'WALI-IKX9UAQO', NULL, 'An-Nisa Maulida Hamzah ', 'Putri', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(224, NULL, 'WALI-IQKYMBSN', NULL, 'Lidya Rahmawal Jepa', 'Putri', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(225, NULL, 'WALI-DLXI9PV9', NULL, 'Sakila Aulia', 'Putri', NULL, 18, NULL, '2025-08-26 10:20:33', '2025-08-26 10:28:05'),
(226, NULL, 'WALI-I7ASKRIK', '6746746', 'Adnan Faruq Albantani', 'Putra', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-09-24 03:46:49'),
(227, NULL, 'WALI-LHIINIGY', NULL, 'Daffa Dhiya\'ulhaq', 'Putra', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(228, NULL, 'WALI-XUPJP4C5', NULL, 'Faisal Abda\'u', 'Putra', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(229, NULL, 'WALI-7TUMXTRG', NULL, 'Gian Nara Paramudya Pratama', 'Putra', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(230, NULL, 'WALI-DUCHF86S', NULL, 'Hisyam Kabbani', 'Putra', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(231, NULL, 'WALI-VZUHNKHD', NULL, 'M. Wafa Shaquille', 'Putra', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(232, NULL, 'WALI-GRHEW0TV', NULL, 'Moh.Haidy Syafril Arrasyid', 'Putra', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(233, NULL, 'WALI-UYHAXDSM', NULL, 'Muhammad Abdul Salam', 'Putra', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(234, NULL, 'WALI-74FNUDBJ', NULL, 'Muhammad Farikh Firliansyah', 'Putra', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(235, NULL, 'WALI-KGELOG3I', NULL, 'Naufal Tedi Perdana', 'Putra', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(236, NULL, 'WALI-HXURNS8M', NULL, 'Rifqy Fathur Rahman', 'Putra', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(237, NULL, 'WALI-UMUWXYAL', NULL, 'Aira Arifatunnisa El-Syifa', 'Putri', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(238, NULL, 'WALI-PYLUPSDL', NULL, 'Aura Zahrah Qibtia', 'Putri', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(239, NULL, 'WALI-EXFABSJP', NULL, 'Dinara Hanifatu Septiani', 'Putri', 'Khodijah', 12, 'foto_santri/NB9D4dEv06KQyoQne1cDJqEQ7111OlmT2eDUrws9.jpg', '2025-08-26 10:21:49', '2025-08-28 01:17:45'),
(240, NULL, 'WALI-MJUZMMGC', NULL, 'Indah Suci Ramadhan', 'Putri', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(241, NULL, 'WALI-AEPOVZRR', NULL, 'Marwaa Nurrasmi', 'Putri', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(242, NULL, 'WALI-IUI6S9AR', NULL, 'Novierra Rainy Hendryana', 'Putri', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(243, NULL, 'WALI-XS40BZAL', NULL, 'Rifa Khofifah', 'Putri', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(244, NULL, 'WALI-9SLIIZCI', NULL, 'Siti Fatimatu Zahra', 'Putri', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(245, NULL, 'WALI-AT4CVL5W', NULL, 'Siti Nazwa Aeriya', 'Putri', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(246, NULL, 'WALI-XZEEWETI', NULL, 'Siti Raycha Nabilah', 'Putri', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(247, NULL, 'WALI-NIMNYSHN', NULL, 'Syafira Azzahroh', 'Putri', NULL, 12, NULL, '2025-08-26 10:21:49', '2025-08-26 10:28:05'),
(248, NULL, 'WALI-XN6OIXGA', NULL, 'Adrian Wiar', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(249, NULL, 'WALI-NZDBXD2R', NULL, 'Afyad Muyassar Ramadhan', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(250, NULL, 'WALI-HBIWZ1SB', NULL, 'Aldi Saepullah', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(251, NULL, 'WALI-EWECQCSG', NULL, 'Fachri Maulana Putra', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(252, NULL, 'WALI-HASDQTAY', NULL, 'M. Hidayatullah', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(253, NULL, 'WALI-ZQVFIELV', NULL, 'Muhammad Adriza Fairuz', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(254, NULL, 'WALI-JN4HBUSP', NULL, 'Muhammad Aryadilah Al Zabirr', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(255, NULL, 'WALI-3FNPAI6C', NULL, 'Muhammad Farhan Aulia Rahman', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(256, NULL, 'WALI-LKL9FEHF', NULL, 'Muhammad Faris', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(257, NULL, 'WALI-Y8SEF4F5', NULL, 'Muhammad Fauzan Kamil', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(258, NULL, 'WALI-BNISBRLC', NULL, 'Reval Mulyadi', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(259, NULL, 'WALI-0BD35XNY', NULL, 'Tb Adly Beydhowi Damanhuri', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(260, NULL, 'WALI-7ND60ORS', NULL, 'Wira Ali Ghiery', 'Putra', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(261, NULL, 'WALI-K6KZ3OKS', NULL, 'Alvina Setiyaningrum', 'Putri', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(262, NULL, 'WALI-A2ECCNSA', NULL, 'Aura Aqila Alfayaza', 'Putri', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(263, NULL, 'WALI-VIT51DGA', NULL, 'Dinar Prisilia', 'Putri', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(264, NULL, 'WALI-BSGUJXSA', NULL, 'Hilya Nafisa', 'Putri', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(265, NULL, 'WALI-FU4X6YGX', NULL, 'Melly Nayla Putri', 'Putri', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(266, NULL, 'WALI-WTOHZKNG', NULL, 'Nida Maulida', 'Putri', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(267, NULL, 'WALI-OUEKHOXD', NULL, 'Parhah Nurul Hidayah', 'Putri', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(268, NULL, 'WALI-5RFHMQA6', NULL, 'Qonita Maulida', 'Putri', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(269, NULL, 'WALI-SKHHDDLZ', NULL, 'Salwa Salsabila Afifah', 'Putri', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(270, NULL, 'WALI-SQ33SXY3', NULL, 'Siti Nur Fadillah', 'Putri', NULL, 13, NULL, '2025-08-26 10:22:55', '2025-08-26 10:28:05'),
(346, 22, 'WALI-7QQZNJVR', NULL, 'Ahmad Mahrus Albi', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-09-17 05:33:42'),
(347, NULL, 'WALI-EJ3PUJRY', NULL, 'Alfan Rheza Amirudin', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:05'),
(348, NULL, 'WALI-D6J0AR0R', NULL, 'Angga Saputra Agustian', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(349, NULL, 'WALI-94ZDGH35', NULL, 'Bagas Aqso Alvaro', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(350, NULL, 'WALI-GOOLJCTK', NULL, 'Brian Izaz Kamali', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(351, NULL, 'WALI-MQYBGOYF', NULL, 'Fadlan Manafi Hafidz', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(352, NULL, 'WALI-LL6DLBJQ', NULL, 'Fahreza Sujanson', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(353, NULL, 'WALI-Q6BPFX69', NULL, 'Fathan Rizqi Ramadhan', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(354, NULL, 'WALI-JP9UJHOM', NULL, 'Imam Alghazali Fauzi', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(355, NULL, 'WALI-8L0G2AMP', NULL, 'M. Wildan Izudin', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(356, NULL, 'WALI-OBSOQBW6', NULL, 'M. Zidni Ihda Taqqiya', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(357, NULL, 'WALI-HBFCSTBC', NULL, 'M.Wildan Firdaus', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(358, NULL, 'WALI-NWHGOQRR', NULL, 'Mochammad Scihabudin Maulana', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(359, NULL, 'WALI-FEL41NMG', NULL, 'Muhamad Bahis Alfawaz', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(360, NULL, 'WALI-QZRRUAA0', NULL, 'Muhammad Faiz Amirul Tasman', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(361, NULL, 'WALI-IWJWGDDY', NULL, 'Raden Muhammad Dzikwan Sukmadijaya', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(362, NULL, 'WALI-SUIEZOFV', NULL, 'Rafif Adis Arrasyid', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(363, NULL, 'WALI-GW7MEQJS', NULL, 'Revan Bernaldo', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(364, NULL, 'WALI-XDA5HX4W', NULL, 'Ridho Ibnu Tsaalist', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(365, NULL, 'WALI-HKTQW3EL', NULL, 'Rifki Juliansyah', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(366, NULL, 'WALI-5LAPWECO', NULL, 'Rizqi Al-Bahrain', 'Putra', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(367, NULL, 'WALI-LDFBKJ9R', NULL, 'Areta Yasya Nafilah', 'Putri', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(368, NULL, 'WALI-BWSSQVO7', NULL, 'Dwi Aulia Nurhasanah', 'Putri', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(369, NULL, 'WALI-HVFC1GRP', NULL, 'Monaya Putri Chairani', 'Putri', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(370, NULL, 'WALI-HBJRDQKA', NULL, 'Puti Nyssa Anindia', 'Putri', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(371, NULL, 'WALI-JLUUQZEW', NULL, 'Rea Syarifa Putri', 'Putri', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(372, NULL, 'WALI-I47RDIFV', NULL, 'Selma Alivia Kirani', 'Putri', NULL, 14, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(373, NULL, 'WALI-KKXBOTWX', NULL, 'Ahmad Gilang Maulana', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(374, NULL, 'WALI-ECZTOUOZ', NULL, 'Aidil Fitrah Buana Guci', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(375, NULL, 'WALI-IIO3VRH0', NULL, 'Fardian Azmi Fudaili', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(376, NULL, 'WALI-OVFPYKWU', NULL, 'Hilal Nuru Shidqi', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(377, NULL, 'WALI-6JO9Q71F', NULL, 'Ibnu Rusydan Azzuhri', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(378, NULL, 'WALI-7JVSZUSI', NULL, 'Jibran Al Zidan', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(379, NULL, 'WALI-AZL8XODY', NULL, 'Kenzie Khalilullah Ad Zakie', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(380, NULL, 'WALI-UHXS2K1S', NULL, 'M. Daffa Firdaus', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(381, NULL, 'WALI-SM0FWVTO', NULL, 'M. Fawwaz Fairuz', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(382, NULL, 'WALI-AMEFIJGJ', NULL, 'M. Naufal Abdullah', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(383, NULL, 'WALI-OZ9DYEZ7', NULL, 'M. Nurromdhoni', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(384, NULL, 'WALI-PSNN3EIF', NULL, 'Muhamad Alvino Dhinejhad', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(385, NULL, 'WALI-N7WAI53N', NULL, 'Muhamad Faiq Septian', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(386, NULL, 'WALI-MURJTUDP', NULL, 'Muhammad FahriYadi Al Amin', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(387, NULL, 'WALI-CAO3MCGK', NULL, 'Muhammad Faiz Ramadhan', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(388, NULL, 'WALI-ERHTEAX0', NULL, 'Muhammad Fudlan ', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(389, NULL, 'WALI-DV9BPPKX', NULL, 'Muhammad Khalish Zakwan Yazid', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(390, NULL, 'WALI-EXJHDYHK', NULL, 'Reiyhan Zibran Okta Al Gifari', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(391, NULL, 'WALI-BNR6YERX', NULL, 'Sakti Aditya Saryanto', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(392, NULL, 'WALI-UDWYKMX2', NULL, 'Yanva \'Unii Mukti Santosa', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(393, NULL, 'WALI-IHKCEN83', NULL, 'Zaidan Asyauqi Yusqi Putra', 'Putra', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(394, NULL, 'WALI-4IIE20XW', NULL, 'Abriligga Rizqika Maulidani', 'Putri', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(395, NULL, 'WALI-NYD07RXE', NULL, 'Hatim Muhammad Bajri', 'Putri', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(396, NULL, 'WALI-TUGYUKSD', NULL, 'Kasihlawati', 'Putri', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(397, NULL, 'WALI-3TPI23UO', NULL, 'Laila Najwa', 'Putri', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(398, NULL, 'WALI-37PUGK5T', NULL, 'Nabila Auliya Az-Zahra', 'Putri', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(399, NULL, 'WALI-GZ2UCSLO', NULL, 'Siti Aida Zahra', 'Putri', NULL, 15, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(400, NULL, 'WALI-3RHCDO1S', NULL, 'Cep Dimas Permana', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(401, NULL, 'WALI-1HHMSEF2', NULL, 'Fakhri Hazami', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(402, NULL, 'WALI-P7MPZF1I', NULL, 'Fasya Raditia Ramdhani', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(403, NULL, 'WALI-D86BTRIB', NULL, 'Fikri Ahmad Jaiz', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(404, NULL, 'WALI-USCWCQFT', NULL, 'Malikul Kandias', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(405, NULL, 'WALI-AZSXYWLY', NULL, 'Muhamad Sofyan Gandi', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(406, NULL, 'WALI-QUZRE0LU', NULL, 'Muhammad Arif Rahman', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(407, NULL, 'WALI-QVMBAX3E', NULL, 'Muhammad Bahrussofa', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(408, NULL, 'WALI-PXU7MVK3', NULL, 'Muhammad Rafi Wandani', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(409, NULL, 'WALI-AM20VCQ1', NULL, 'Refta Aryadillah', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(410, NULL, 'WALI-HHYFJ6BN', NULL, 'Sayyidul Laili', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(411, NULL, 'WALI-PXPLETLC', NULL, 'Taj Fairuz Phillein Chavins Javier', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(412, NULL, 'WALI-IF4R7TS4', NULL, 'Wastuhalfi Fawwaz Septiawan', 'Putra', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(413, NULL, 'WALI-U6AWUGN2', NULL, 'Bilqis Nur Gumaisha', 'Putri', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(414, NULL, 'WALI-IWXZBDAL', NULL, 'Ghamar Faradillah', 'Putri', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(415, NULL, 'WALI-AVWXWDZX', NULL, 'Giska Julianah', 'Putri', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(416, NULL, 'WALI-DGATJAXR', NULL, 'Meloni Azzahra Maulida', 'Putri', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(417, NULL, 'WALI-0JRLEZDS', NULL, 'Nazwa Narulita Khumaeni', 'Putri', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(418, NULL, 'WALI-SPYWFHIA', NULL, 'Silfia Khozinatul Asror', 'Putri', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(419, NULL, 'WALI-YXX9LIUZ', NULL, 'Velytha Aurora Keysha', 'Putri', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(420, NULL, 'WALI-HOGPD1DM', NULL, 'Widadatul Aulia', 'Putri', NULL, 16, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(421, NULL, 'WALI-ACMX6PYM', NULL, 'Ahmad Malik Fathir', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(422, NULL, 'WALI-KYYFQQHO', NULL, 'Arsy Andrian Annafii', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(423, NULL, 'WALI-CAEZNYHR', NULL, 'Bima Sakti', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(424, NULL, 'WALI-ABRFRPSD', NULL, 'Diaz Khaerul Ikhwan', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(425, NULL, 'WALI-R1VMUUKV', NULL, 'Fahmi Raihan', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(426, NULL, 'WALI-MDPVQZLS', NULL, 'Faizal Nurfaizi', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(427, NULL, 'WALI-KATMPTF1', NULL, 'Ibnu Al Pajri', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(428, NULL, 'WALI-MYEQSRZ1', NULL, 'Ilham Ramadhan', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(429, NULL, 'WALI-GMHLVX1W', NULL, 'Muhamad Naufal Hadi Aroyan', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(430, NULL, 'WALI-HJ9XYXR3', NULL, 'Muhammad Adrian Nurwahid ', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(431, NULL, 'WALI-JRPBEAGN', NULL, 'Muhammad Afzaal Rayhan', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(432, NULL, 'WALI-XQ407M5Y', NULL, 'Muhammad Azril Ibrahim', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(433, NULL, 'WALI-19Y3QKA0', NULL, 'Muhammad Farrel', 'Putra', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(434, NULL, 'WALI-2LUH7ZH4', NULL, 'Afiatunnazilah', 'Putri', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(435, NULL, 'WALI-9WWYXIMD', NULL, 'Amanda Wulandari', 'Putri', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(436, NULL, 'WALI-HTF0VFQY', NULL, 'Ghina Fazilatul Aqila', 'Putri', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(437, NULL, 'WALI-ZJHKJPXG', NULL, 'Mutia Alvita Khaerani', 'Putri', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(438, NULL, 'WALI-KGRUR0NS', NULL, 'Nikita Mahrunnisa', 'Putri', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(439, NULL, 'WALI-FWHNMZ2Y', NULL, 'Satrin Wijdan', 'Putri', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06'),
(440, NULL, 'WALI-EXX3TQJJ', NULL, 'Siti Mutiah', 'Putri', NULL, 17, NULL, '2025-08-26 10:27:13', '2025-08-26 10:28:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `mata_pelajaran_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Merujuk ke user pengajar',
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `day_of_week` tinyint(4) NOT NULL,
  `time_slot` tinyint(4) NOT NULL,
  `version_id` int(11) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `schedules`
--

INSERT INTO `schedules` (`id`, `kelas_id`, `mata_pelajaran_id`, `teacher_id`, `room_id`, `day_of_week`, `time_slot`, `version_id`, `is_published`, `created_at`, `updated_at`) VALUES
(1189, 4, 5, 2, 10, 3, 3, NULL, 0, NULL, '2025-09-24 03:48:45'),
(1190, 16, 1, 1, 18, 3, 5, NULL, 0, NULL, NULL),
(1191, 17, 1, 1, 16, 3, 6, NULL, 0, NULL, NULL),
(1192, 5, 5, 2, 8, 2, 3, NULL, 0, NULL, '2025-09-24 03:48:45'),
(1193, 17, 1, 1, 16, 2, 6, NULL, 0, NULL, NULL),
(1194, 16, 2, 1, 18, 4, 6, NULL, 0, NULL, NULL),
(1195, 16, 1, 1, 18, 6, 4, NULL, 0, NULL, NULL),
(1196, 17, 2, 1, 16, 5, 4, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `teacher_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `teacher_code`, `created_at`, `updated_at`) VALUES
(1, 'Ahmad Adnan', 'AZ', '2025-09-23 20:05:56', '2025-09-23 20:07:06'),
(2, 'Ali muksit', 'NN', '2025-09-23 21:24:53', '2025-09-23 22:36:33'),
(3, 'Fachril Ramadan', 'FC', '2025-09-23 21:25:01', '2025-09-23 21:25:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teacher_unavailabilities`
--

CREATE TABLE `teacher_unavailabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `day_of_week` tinyint(4) NOT NULL COMMENT '1 for Monday, 7 for Sunday',
  `time_slot` tinyint(4) NOT NULL COMMENT 'Jam ke- (1, 2, 3, etc)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `teacher_unavailabilities`
--

INSERT INTO `teacher_unavailabilities` (`id`, `teacher_id`, `day_of_week`, `time_slot`, `created_at`, `updated_at`) VALUES
(29, 8, 1, 1, '2025-08-30 23:03:44', '2025-08-30 23:03:44'),
(30, 8, 1, 6, '2025-08-30 23:03:44', '2025-08-30 23:03:44'),
(38, 1, 1, 1, '2025-08-31 02:07:31', '2025-08-31 02:07:31'),
(39, 1, 1, 2, '2025-08-31 02:07:31', '2025-08-31 02:07:31'),
(40, 1, 1, 3, '2025-08-31 02:07:31', '2025-08-31 02:07:31'),
(41, 1, 1, 4, '2025-08-31 02:07:31', '2025-08-31 02:07:31'),
(42, 1, 1, 5, '2025-08-31 02:07:31', '2025-08-31 02:07:31'),
(43, 1, 1, 6, '2025-08-31 02:07:31', '2025-08-31 02:07:31'),
(44, 1, 1, 7, '2025-08-31 02:07:31', '2025-08-31 02:07:31'),
(45, 6, 1, 1, '2025-08-31 02:09:52', '2025-08-31 02:09:52'),
(46, 6, 4, 2, '2025-08-31 02:09:52', '2025-08-31 02:09:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pengasuhan','kesehatan','pengajaran','ustadz_umum','wali_santri') NOT NULL DEFAULT 'ustadz_umum',
  `teacher_code` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `teacher_code`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ahmad Adnan', 'adnan@gmail.com', NULL, '$2y$12$OaOeZXdMbZUD3fpB3QrZO.l/SKfn7GAvqGNBXdGUUPxahe6IGEcCe', 'admin', NULL, 'v5JNgeEOY6qB1uLzkMvQZnLitoFJpiBW8627kqcq7FKKD1tjAoHiRVHZDduV', '2025-08-23 17:09:25', '2025-08-23 17:09:25'),
(2, 'Nannn', 'nan@gmail.com', NULL, '$2y$12$4ryv04h9F/tybQEk697iAO7rIwCeiZpRfX183DDdVBPTytcXyIMV2', 'admin', NULL, NULL, '2025-08-24 04:01:27', '2025-08-30 20:21:40'),
(5, 'Noval Izharul', 'noval@gmail.com', NULL, '$2y$12$fuELUHWHJ9t3myzpd4wlcuahRggdf5GCUwO0JNytpuS6W247/sHmu', 'pengasuhan', NULL, NULL, '2025-08-27 17:04:24', '2025-08-27 17:50:09'),
(6, 'Bapak 11 Meter', 'bapak11meter@gmail.com', NULL, '$2y$12$4uFaKdgNxt.H3TKYpsIAdO7L/zY8bu10/RhrmLX0fR1vcx8dRR8S6', 'ustadz_umum', NULL, NULL, '2025-08-27 17:10:22', '2025-08-27 17:10:22'),
(7, 'Maruf Faraidhul Fikri', 'maruf@gmail.com', NULL, '$2y$12$idebibgWacJVoXK.EUlN/.a/0rLBtuN8g.kI104PUBNmxK8JOHXZy', 'pengasuhan', NULL, '6RoW5IyP72tXy6SrSnl0FUZs3P2vRliHuMj5ySI49RfJqahCcR3CTKYwWaEi', '2025-08-27 22:23:35', '2025-08-27 22:23:35'),
(8, 'Direktur II', 'direktur2@gmail.com', NULL, '$2y$12$qxNfna76PzHFhJn4wJes4u9NIPtNVYh1dZ0xgk19AJ08zXPL5NF0y', 'admin', NULL, NULL, '2025-08-27 23:25:45', '2025-08-27 23:25:45'),
(9, 'Fachril Ramadan', 'fachrilramdan27@gmail.com', NULL, '$2y$12$FIAqE3gzPlu9YHoZ3efEEeoNZ0Lg3DEXQX7eMMR/QL97A9FNnxVDa', 'pengasuhan', NULL, '4TtVc1zDBLelZYADxmCp3whHaJOhYCfk5QPcE7OEOvIxXkhhH2gAdUQxoVvz', '2025-08-28 00:31:50', '2025-08-28 00:41:50'),
(10, 'Fauzi', 'ojihidayat333333@gmail.com', NULL, '$2y$12$irk9rxTGrPOn9INQgMCmFerXQk.AjU3pnRe6tzPzkcpRpnL/Pc1nW', 'pengasuhan', NULL, NULL, '2025-08-28 00:37:25', '2025-08-28 00:41:57'),
(11, 'Muhammad Syifar Rahman', 'kurmabakery@gmail.com', NULL, '$2y$12$6G9WZMXdol4grvaxoTsJwu2y/YFoukj.M3XCjAoB0v7R87FB.M7Qa', 'kesehatan', NULL, NULL, '2025-08-28 01:08:57', '2025-08-28 01:42:58'),
(12, 'Mutiara Ummu Atiyyah', 'mutiaraummuatiyyah@gmail.com', NULL, '$2y$12$gGR2UsV876DHEwB/Q/aoA.VBK4EArwwmmXdJGLGr9Q5a4G5DAwG8y', 'kesehatan', NULL, NULL, '2025-08-28 01:12:32', '2025-08-28 01:43:11'),
(13, 'putri faridhatul millah', 'putrifaridhatul@gmail.com', NULL, '$2y$12$Ki0WgOTy1cdIP1a1Z1jlbeTS.LNdLIjWb/E7fNgRz2NHCVjCaB82G', 'pengasuhan', NULL, NULL, '2025-08-28 01:13:45', '2025-08-28 01:44:39'),
(14, 'Dewi Durotun Nafisah', 'dewidurotunnafisah@gmail.com', NULL, '$2y$12$rXY8yf0LmpDPIOAG8uTJ1uwAnnkeiNwe5EqY/CttkMu2nrLeJ6ESm', 'kesehatan', NULL, NULL, '2025-08-28 01:16:11', '2025-08-28 01:44:31'),
(15, 'Daffa Fauzan', 'daffafzn1801@gmail.com', NULL, '$2y$12$RpDkw5fCaYTBCeKWui0yveU2QUTH2Vmcv9yxDJ6xic.DwTBco9qEq', 'pengasuhan', NULL, NULL, '2025-08-28 01:56:17', '2025-08-28 02:07:46'),
(16, 'Zahid noor', 'zahiddd616@gmail.com', NULL, '$2y$12$FMA8PkeJstcpiQYnf8K0JeXDo44OkOx1.XURaiM35T13Mg2ly4NBS', 'pengasuhan', NULL, NULL, '2025-08-28 02:43:39', '2025-08-28 04:17:01'),
(17, 'Muhammad Al\'Amien', 'alamienpro@gmail.com', NULL, '$2y$12$u1mHprybUfSjfgjX7wb/xesKWp7L51q3rSjf2JjeJ4WZXRc/jJ7oW', 'pengasuhan', NULL, NULL, '2025-08-28 03:14:31', '2025-08-28 04:16:52'),
(18, 'Nisrina Nasywa', 'acauuu29@gmail.com', NULL, '$2y$12$j5WDOtDZrjoA6u2ME6CXWOfkO4YDs84dbG0KfaMeD9xODZhytPHFq', 'ustadz_umum', NULL, NULL, '2025-08-28 05:00:24', '2025-08-28 05:00:24'),
(19, 'M. Rifky Aditiya Putra', 'mrifkyraditiya75385@gmail.com', NULL, '$2y$12$n8ddVGYdObgip0d062PEku5LG48Poz8uWp9EjcYssb0cOdaiHG4EG', 'ustadz_umum', NULL, NULL, '2025-08-28 13:49:07', '2025-08-28 13:49:07'),
(20, 'SEKRET', 'fakhrimaulana311@gmail.com', NULL, '$2y$12$7CLDtNHkjym1a4yr0wSYtu3tCKN5s7OZzNEGrrZpxfYCAys8XNCz6', 'admin', NULL, NULL, '2025-08-29 07:21:04', '2025-08-29 07:21:04'),
(21, 'Khotibul Umam', 'umam76207@gmail.com', NULL, '$2y$12$or91DYZACV4TJMb7yT1/8uXAEOkg.Ds0DdZ5IBDwrKbP/abQu01JK', 'ustadz_umum', NULL, NULL, '2025-08-29 11:46:02', '2025-08-29 11:46:02'),
(22, 'bapak', 'admin@kunka.com', NULL, '$2y$12$qxT.jC88kkwKWQnYKE9IZOBlHBh.ThLjhznd.KEofxNcClQWYwRmu', 'wali_santri', NULL, NULL, '2025-09-17 05:33:42', '2025-09-17 05:33:42');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `blocked_times`
--
ALTER TABLE `blocked_times`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `catatan_harians`
--
ALTER TABLE `catatan_harians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `catatan_harians_santri_id_foreign` (`santri_id`),
  ADD KEY `catatan_harians_dicatat_oleh_id_foreign` (`dicatat_oleh_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `hour_priorities`
--
ALTER TABLE `hour_priorities`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jabatans`
--
ALTER TABLE `jabatans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jabatans_nama_jabatan_unique` (`nama_jabatan`);

--
-- Indeks untuk tabel `jabatan_user`
--
ALTER TABLE `jabatan_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jabatan_user_user_id_foreign` (`user_id`),
  ADD KEY `jabatan_user_kelas_id_foreign` (`kelas_id`),
  ADD KEY `jabatan_user_jabatan_id_foreign` (`jabatan_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kelas_nama_kelas_unique` (`nama_kelas`),
  ADD KEY `kelas_kurikulum_template_id_foreign` (`kurikulum_template_id`),
  ADD KEY `kelas_room_id_foreign` (`room_id`);

--
-- Indeks untuk tabel `kelas_mata_pelajaran`
--
ALTER TABLE `kelas_mata_pelajaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kelas_mata_pelajaran_kelas_id_mata_pelajaran_id_unique` (`kelas_id`,`mata_pelajaran_id`),
  ADD KEY `kelas_mata_pelajaran_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  ADD KEY `kelas_mata_pelajaran_teacher_id_foreign` (`teacher_id`);

--
-- Indeks untuk tabel `kurikulum_templates`
--
ALTER TABLE `kurikulum_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kurikulum_templates_nama_template_unique` (`nama_template`);

--
-- Indeks untuk tabel `mata_pelajarans`
--
ALTER TABLE `mata_pelajarans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mata_pelajaran_teacher`
--
ALTER TABLE `mata_pelajaran_teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mata_pelajaran_teacher_mata_pelajaran_id_teacher_id_unique` (`mata_pelajaran_id`,`teacher_id`),
  ADD KEY `mata_pelajaran_teacher_teacher_id_foreign` (`teacher_id`);

--
-- Indeks untuk tabel `mata_pelajaran_user`
--
ALTER TABLE `mata_pelajaran_user`
  ADD PRIMARY KEY (`user_id`,`mata_pelajaran_id`),
  ADD KEY `mata_pelajaran_user_mata_pelajaran_id_foreign` (`mata_pelajaran_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `nilais`
--
ALTER TABLE `nilais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nilais_santri_id_mata_pelajaran_id_semester_tahun_ajaran_unique` (`santri_id`,`mata_pelajaran_id`,`semester`,`tahun_ajaran`),
  ADD KEY `nilais_kelas_id_foreign` (`kelas_id`),
  ADD KEY `nilais_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  ADD KEY `nilais_created_by_foreign` (`created_by`),
  ADD KEY `nilais_updated_by_foreign` (`updated_by`);

--
-- Indeks untuk tabel `pelanggarans`
--
ALTER TABLE `pelanggarans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggarans_santri_id_foreign` (`santri_id`);

--
-- Indeks untuk tabel `perizinans`
--
ALTER TABLE `perizinans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `perizinans_santri_id_foreign` (`santri_id`),
  ADD KEY `perizinans_created_by_foreign` (`created_by`),
  ADD KEY `perizinans_updated_by_foreign` (`updated_by`);

--
-- Indeks untuk tabel `prestasis`
--
ALTER TABLE `prestasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestasis_santri_id_foreign` (`santri_id`),
  ADD KEY `prestasis_dicatat_oleh_id_foreign` (`dicatat_oleh_id`);

--
-- Indeks untuk tabel `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `santris`
--
ALTER TABLE `santris`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `santris_nis_unique` (`nis`),
  ADD UNIQUE KEY `santris_kode_registrasi_wali_unique` (`kode_registrasi_wali`),
  ADD KEY `santris_kelas_id_foreign` (`kelas_id`),
  ADD KEY `santris_wali_id_foreign` (`wali_id`);

--
-- Indeks untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `schedule_kelas_unique` (`kelas_id`,`day_of_week`,`time_slot`,`version_id`),
  ADD UNIQUE KEY `schedule_teacher_unique` (`teacher_id`,`day_of_week`,`time_slot`,`version_id`),
  ADD UNIQUE KEY `schedule_room_unique` (`room_id`,`day_of_week`,`time_slot`,`version_id`),
  ADD KEY `schedules_mata_pelajaran_id_foreign` (`mata_pelajaran_id`);

--
-- Indeks untuk tabel `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teachers_teacher_code_unique` (`teacher_code`);

--
-- Indeks untuk tabel `teacher_unavailabilities`
--
ALTER TABLE `teacher_unavailabilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_unavailabilities_teacher_id_day_of_week_time_slot_unique` (`teacher_id`,`day_of_week`,`time_slot`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_teacher_code_unique` (`teacher_code`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `blocked_times`
--
ALTER TABLE `blocked_times`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `catatan_harians`
--
ALTER TABLE `catatan_harians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hour_priorities`
--
ALTER TABLE `hour_priorities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `jabatans`
--
ALTER TABLE `jabatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `jabatan_user`
--
ALTER TABLE `jabatan_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `kelas_mata_pelajaran`
--
ALTER TABLE `kelas_mata_pelajaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kurikulum_templates`
--
ALTER TABLE `kurikulum_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `mata_pelajarans`
--
ALTER TABLE `mata_pelajarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `mata_pelajaran_teacher`
--
ALTER TABLE `mata_pelajaran_teacher`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `nilais`
--
ALTER TABLE `nilais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT untuk tabel `pelanggarans`
--
ALTER TABLE `pelanggarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `perizinans`
--
ALTER TABLE `perizinans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `prestasis`
--
ALTER TABLE `prestasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `santris`
--
ALTER TABLE `santris`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=447;

--
-- AUTO_INCREMENT untuk tabel `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1197;

--
-- AUTO_INCREMENT untuk tabel `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `teacher_unavailabilities`
--
ALTER TABLE `teacher_unavailabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `catatan_harians`
--
ALTER TABLE `catatan_harians`
  ADD CONSTRAINT `catatan_harians_dicatat_oleh_id_foreign` FOREIGN KEY (`dicatat_oleh_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `catatan_harians_santri_id_foreign` FOREIGN KEY (`santri_id`) REFERENCES `santris` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jabatan_user`
--
ALTER TABLE `jabatan_user`
  ADD CONSTRAINT `jabatan_user_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jabatan_user_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jabatan_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_kurikulum_template_id_foreign` FOREIGN KEY (`kurikulum_template_id`) REFERENCES `kurikulum_templates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `kelas_mata_pelajaran`
--
ALTER TABLE `kelas_mata_pelajaran`
  ADD CONSTRAINT `kelas_mata_pelajaran_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_mata_pelajaran_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajarans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_mata_pelajaran_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mata_pelajaran_teacher`
--
ALTER TABLE `mata_pelajaran_teacher`
  ADD CONSTRAINT `mata_pelajaran_teacher_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajarans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mata_pelajaran_teacher_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mata_pelajaran_user`
--
ALTER TABLE `mata_pelajaran_user`
  ADD CONSTRAINT `mata_pelajaran_user_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajarans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mata_pelajaran_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nilais`
--
ALTER TABLE `nilais`
  ADD CONSTRAINT `nilais_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `nilais_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilais_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajarans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilais_santri_id_foreign` FOREIGN KEY (`santri_id`) REFERENCES `santris` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilais_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `pelanggarans`
--
ALTER TABLE `pelanggarans`
  ADD CONSTRAINT `pelanggarans_santri_id_foreign` FOREIGN KEY (`santri_id`) REFERENCES `santris` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `perizinans`
--
ALTER TABLE `perizinans`
  ADD CONSTRAINT `perizinans_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `perizinans_santri_id_foreign` FOREIGN KEY (`santri_id`) REFERENCES `santris` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `perizinans_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `prestasis`
--
ALTER TABLE `prestasis`
  ADD CONSTRAINT `prestasis_dicatat_oleh_id_foreign` FOREIGN KEY (`dicatat_oleh_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestasis_santri_id_foreign` FOREIGN KEY (`santri_id`) REFERENCES `santris` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `santris`
--
ALTER TABLE `santris`
  ADD CONSTRAINT `santris_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `santris_wali_id_foreign` FOREIGN KEY (`wali_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajarans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `teacher_unavailabilities`
--
ALTER TABLE `teacher_unavailabilities`
  ADD CONSTRAINT `teacher_unavailabilities_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
