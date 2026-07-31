-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.46 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.17.0.7270
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for tms-laravel
CREATE DATABASE IF NOT EXISTS `tms-laravel` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `tms-laravel`;

-- Dumping structure for table tms-laravel.activity_log
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`),
  CONSTRAINT `activity_log_chk_1` CHECK (json_valid(`properties`))
) ENGINE=InnoDB AUTO_INCREMENT=566 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.activity_log: ~0 rows (approximately)
DELETE FROM `activity_log`;

-- Dumping structure for table tms-laravel.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.cache: ~2 rows (approximately)
DELETE FROM `cache`;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('crts_cache_83d5e1e49bd5f0ebbf6c9ba40416057fac1b5d76', 'i:1;', 1785490188),
	('crts_cache_83d5e1e49bd5f0ebbf6c9ba40416057fac1b5d76:timer', 'i:1785490188;', 1785490188);

-- Dumping structure for table tms-laravel.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table tms-laravel.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `complaint_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_complaint_id_foreign` (`complaint_id`),
  KEY `comments_user_id_foreign` (`user_id`),
  CONSTRAINT `comments_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.comments: ~2 rows (approximately)
DELETE FROM `comments`;
INSERT INTO `comments` (`id`, `complaint_id`, `user_id`, `comment`, `created_at`, `updated_at`) VALUES
	(87, 542, 1, 'Test', '2026-07-28 11:50:36', '2026-07-28 11:50:36'),
	(88, 570, 30, 'This is completed', '2026-07-31 06:12:13', '2026-07-31 06:12:13'),
	(89, 578, 30, 'ohho', '2026-07-31 09:43:19', '2026-07-31 09:43:19'),
	(90, 578, 30, 'ohho', '2026-07-31 09:44:20', '2026-07-31 09:44:20'),
	(91, 578, 30, 'dsfsdfsdf', '2026-07-31 09:44:26', '2026-07-31 09:44:26'),
	(92, 578, 30, 'fdfsdf', '2026-07-31 09:44:46', '2026-07-31 09:44:46');

-- Dumping structure for table tms-laravel.complaint_actions
CREATE TABLE IF NOT EXISTS `complaint_actions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `complaint_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  `assigned_to` bigint unsigned DEFAULT NULL,
  `status_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `complaint_actions_complaint_id_foreign` (`complaint_id`),
  KEY `complaint_actions_status_id_foreign` (`status_id`),
  CONSTRAINT `complaint_actions_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaint_actions_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1242 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.complaint_actions: ~176 rows (approximately)
DELETE FROM `complaint_actions`;
INSERT INTO `complaint_actions` (`id`, `complaint_id`, `user_id`, `assigned_to`, `status_id`, `description`, `created_at`, `updated_at`) VALUES
	(1003, 509, 0, NULL, 1, 'Complaint created', '2026-07-21 05:58:17', '2026-07-21 05:58:17'),
	(1004, 510, 1, 30, 2, 'Complaint created via bulk import', '2026-07-21 06:52:39', '2026-07-21 06:52:39'),
	(1005, 511, 1, 30, 2, 'Complaint created via bulk import', '2026-07-21 06:58:24', '2026-07-21 06:58:24'),
	(1006, 511, 1, 10, 2, NULL, '2026-07-21 06:59:23', '2026-07-21 06:59:23'),
	(1007, 510, 1, 30, 2, 'Complaint updated', '2026-07-21 09:27:22', '2026-07-21 09:27:22'),
	(1008, 511, 1, 7, 2, 'Complaint updated', '2026-07-21 09:32:41', '2026-07-21 09:32:41'),
	(1009, 510, 30, 8, 2, NULL, '2026-07-21 09:33:31', '2026-07-21 09:33:31'),
	(1010, 510, 8, 1, 2, NULL, '2026-07-21 09:42:13', '2026-07-21 09:42:13'),
	(1011, 510, 1, 8, 2, NULL, '2026-07-21 09:42:40', '2026-07-21 09:42:40'),
	(1012, 510, 8, NULL, 8, NULL, '2026-07-21 09:52:03', '2026-07-21 09:52:03'),
	(1013, 509, 1, 8, 2, NULL, '2026-07-21 09:54:41', '2026-07-21 09:54:41'),
	(1014, 510, 1, NULL, 6, 'checked', '2026-07-21 09:59:20', '2026-07-21 09:59:20'),
	(1015, 512, 1, 6, 1, 'Complaint created', '2026-07-21 10:08:19', '2026-07-21 10:08:19'),
	(1016, 509, 8, 1, 2, NULL, '2026-07-21 11:55:14', '2026-07-21 11:55:14'),
	(1017, 512, 1, 6, 2, NULL, '2026-07-21 12:17:35', '2026-07-21 12:17:35'),
	(1018, 514, 1, 11, 2, NULL, '2026-07-21 12:20:13', '2026-07-21 12:20:13'),
	(1019, 517, 1, 17, 2, NULL, '2026-07-21 12:20:18', '2026-07-21 12:20:18'),
	(1020, 519, 1, 10, 2, NULL, '2026-07-21 12:20:21', '2026-07-21 12:20:21'),
	(1021, 520, 1, 7, 2, NULL, '2026-07-21 12:20:26', '2026-07-21 12:20:26'),
	(1022, 521, 1, 18, 2, 'Complaint updated', '2026-07-21 12:20:59', '2026-07-21 12:20:59'),
	(1023, 512, 1, 8, 2, 'Complaint updated', '2026-07-21 12:21:19', '2026-07-21 12:21:19'),
	(1024, 513, 1, 9, 2, NULL, '2026-07-21 12:21:27', '2026-07-21 12:21:27'),
	(1025, 524, 1, 27, 2, NULL, '2026-07-21 12:21:43', '2026-07-21 12:21:43'),
	(1026, 525, 1, 3, 2, NULL, '2026-07-21 12:21:51', '2026-07-21 12:21:51'),
	(1027, 515, 1, 5, 2, NULL, '2026-07-21 12:22:04', '2026-07-21 12:22:04'),
	(1028, 513, 1, 9, 2, NULL, '2026-07-22 06:08:43', '2026-07-22 06:08:43'),
	(1029, 512, 1, 8, 2, 'Complaint updated', '2026-07-22 09:22:57', '2026-07-22 09:22:57'),
	(1030, 512, 1, 8, 2, 'Complaint updated', '2026-07-22 09:26:25', '2026-07-22 09:26:25'),
	(1031, 512, 1, 8, 2, 'Complaint updated', '2026-07-22 09:28:54', '2026-07-22 09:28:54'),
	(1032, 512, 1, 8, 2, 'Complaint updated', '2026-07-22 09:29:01', '2026-07-22 09:29:01'),
	(1033, 512, 1, 8, 8, 'Complaint updated', '2026-07-23 05:53:42', '2026-07-23 05:53:42'),
	(1034, 512, 1, 8, 2, 'Complaint updated', '2026-07-23 05:53:51', '2026-07-23 05:53:51'),
	(1035, 512, 1, 17, 8, 'Complaint updated', '2026-07-23 05:54:25', '2026-07-23 05:54:25'),
	(1036, 515, 8, 8, 2, NULL, '2026-07-23 06:58:04', '2026-07-23 06:58:04'),
	(1037, 515, 8, NULL, 8, NULL, '2026-07-23 07:19:21', '2026-07-23 07:19:21'),
	(1038, 515, 1, NULL, 6, 'checked', '2026-07-23 07:24:09', '2026-07-23 07:24:09'),
	(1039, 518, 8, 8, 2, NULL, '2026-07-23 08:39:43', '2026-07-23 08:39:43'),
	(1040, 518, 8, NULL, 8, NULL, '2026-07-23 08:42:27', '2026-07-23 08:42:27'),
	(1041, 526, 8, 6, 1, 'Complaint created', '2026-07-23 08:55:23', '2026-07-23 08:55:23'),
	(1042, 527, 0, NULL, 1, 'Complaint created', '2026-07-23 08:56:34', '2026-07-23 08:56:34'),
	(1043, 528, 1, NULL, 1, 'Complaint created', '2026-07-23 09:25:07', '2026-07-23 09:25:07'),
	(1044, 517, 1, 8, 2, 'Complaint updated', '2026-07-23 11:29:27', '2026-07-23 11:29:27'),
	(1045, 531, 1, NULL, 1, 'Complaint created', '2026-07-23 11:41:25', '2026-07-23 11:41:25'),
	(1046, 531, 1, NULL, 1, 'Complaint updated', '2026-07-23 11:42:34', '2026-07-23 11:42:34'),
	(1047, 531, 1, NULL, 2, 'Complaint updated', '2026-07-23 11:42:46', '2026-07-23 11:42:46'),
	(1048, 531, 1, NULL, 1, 'Complaint updated', '2026-07-23 11:48:59', '2026-07-23 11:48:59'),
	(1049, 531, 1, 30, 1, 'Complaint updated', '2026-07-23 11:49:08', '2026-07-23 11:49:08'),
	(1050, 531, 1, 5, 8, 'Complaint updated', '2026-07-23 11:51:22', '2026-07-23 11:51:22'),
	(1051, 532, 1, NULL, 1, 'Complaint created', '2026-07-23 11:51:58', '2026-07-23 11:51:58'),
	(1052, 532, 1, NULL, 3, 'Complaint updated', '2026-07-23 11:52:20', '2026-07-23 11:52:20'),
	(1053, 532, 1, 8, 2, 'Complaint updated', '2026-07-23 11:52:32', '2026-07-23 11:52:32'),
	(1054, 533, 1, NULL, 1, 'Complaint created', '2026-07-23 12:09:04', '2026-07-23 12:09:04'),
	(1055, 534, 0, NULL, 1, 'Complaint created', '2026-07-23 12:09:25', '2026-07-23 12:09:25'),
	(1056, 534, 1, 3, 2, 'Complaint updated', '2026-07-23 12:09:57', '2026-07-23 12:09:57'),
	(1057, 533, 1, 8, 2, 'Complaint updated', '2026-07-23 12:10:12', '2026-07-23 12:10:12'),
	(1058, 533, 1, 10, 2, NULL, '2026-07-23 12:10:29', '2026-07-23 12:10:29'),
	(1059, 535, 1, NULL, 2, 'Complaint created via bulk import', '2026-07-23 12:34:10', '2026-07-23 12:34:10'),
	(1060, 531, 1, NULL, 6, 'ha ha theek h', '2026-07-23 12:35:53', '2026-07-23 12:35:53'),
	(1061, 530, 1, 8, 2, NULL, '2026-07-23 12:37:40', '2026-07-23 12:37:40'),
	(1062, 519, 1, 10, 2, NULL, '2026-07-23 12:37:52', '2026-07-23 12:37:52'),
	(1063, 536, 1, 17, 1, 'Complaint created', '2026-07-24 07:23:54', '2026-07-24 07:23:54'),
	(1064, 536, 1, 6, 2, 'Complaint updated', '2026-07-24 07:24:08', '2026-07-24 07:24:08'),
	(1065, 536, 1, 1, 2, 'Complaint updated', '2026-07-24 07:24:19', '2026-07-24 07:24:19'),
	(1066, 536, 1, 1, 2, 'Complaint updated', '2026-07-24 07:24:28', '2026-07-24 07:24:28'),
	(1067, 536, 1, 6, 2, NULL, '2026-07-24 07:24:33', '2026-07-24 07:24:33'),
	(1068, 537, 1, 1, 1, 'Complaint created', '2026-07-24 09:27:52', '2026-07-24 09:27:52'),
	(1069, 537, 1, 5, 2, 'Complaint updated', '2026-07-24 09:28:45', '2026-07-24 09:28:45'),
	(1070, 537, 1, 5, 2, NULL, '2026-07-24 09:28:59', '2026-07-24 09:28:59'),
	(1071, 537, 1, 5, 2, NULL, '2026-07-24 09:29:19', '2026-07-24 09:29:19'),
	(1072, 537, 1, 5, 2, NULL, '2026-07-24 09:30:41', '2026-07-24 09:30:41'),
	(1073, 538, 1, 8, 1, 'Complaint created', '2026-07-24 09:39:25', '2026-07-24 09:39:25'),
	(1074, 538, 8, NULL, 3, NULL, '2026-07-24 09:39:56', '2026-07-24 09:39:56'),
	(1075, 538, 8, NULL, 4, NULL, '2026-07-24 09:40:04', '2026-07-24 09:40:04'),
	(1076, 538, 8, NULL, 5, NULL, '2026-07-24 09:40:11', '2026-07-24 09:40:11'),
	(1077, 538, 8, NULL, 8, NULL, '2026-07-24 09:40:16', '2026-07-24 09:40:16'),
	(1078, 538, 1, 8, 2, NULL, '2026-07-24 09:40:23', '2026-07-24 09:40:23'),
	(1079, 538, 8, 30, 2, 'Complaint updated', '2026-07-24 09:40:34', '2026-07-24 09:40:34'),
	(1080, 538, 30, 10, 2, NULL, '2026-07-24 09:41:29', '2026-07-24 09:41:29'),
	(1081, 533, 10, NULL, 8, NULL, '2026-07-24 10:12:02', '2026-07-24 10:12:02'),
	(1082, 539, 1, 4, 1, 'Complaint created', '2026-07-27 05:53:07', '2026-07-27 05:53:07'),
	(1083, 539, 1, 11, 2, 'Complaint updated', '2026-07-27 05:53:42', '2026-07-27 05:53:42'),
	(1084, 539, 1, 4, 2, 'Complaint updated', '2026-07-27 05:54:31', '2026-07-27 05:54:31'),
	(1085, 540, 1, 6, 1, 'Complaint created', '2026-07-27 06:00:48', '2026-07-27 06:00:48'),
	(1086, 540, 1, 6, 2, 'Complaint updated', '2026-07-27 06:15:45', '2026-07-27 06:15:45'),
	(1087, 535, 1, 6, 2, NULL, '2026-07-27 06:23:24', '2026-07-27 06:23:24'),
	(1088, 539, 1, 11, 2, 'Complaint updated', '2026-07-27 06:23:35', '2026-07-27 06:23:35'),
	(1089, 541, 1, NULL, 2, 'Complaint created via bulk import', '2026-07-27 06:31:21', '2026-07-27 06:31:21'),
	(1090, 540, 1, 7, 2, NULL, '2026-07-27 11:43:05', '2026-07-27 11:43:05'),
	(1091, 540, 1, 4, 2, NULL, '2026-07-27 11:43:13', '2026-07-27 11:43:13'),
	(1092, 530, 1, 2, 2, 'Complaint updated', '2026-07-27 12:21:56', '2026-07-27 12:21:56'),
	(1093, 542, 1, NULL, 1, 'Complaint created', '2026-07-28 11:50:06', '2026-07-28 11:50:06'),
	(1094, 542, 1, 3, 2, 'Complaint updated', '2026-07-28 11:51:26', '2026-07-28 11:51:26'),
	(1095, 509, 1, 8, 2, NULL, '2026-07-28 11:51:57', '2026-07-28 11:51:57'),
	(1096, 542, 1, 4, 2, NULL, '2026-07-28 11:52:16', '2026-07-28 11:52:16'),
	(1097, 542, 1, 6, 2, NULL, '2026-07-28 11:52:28', '2026-07-28 11:52:28'),
	(1098, 542, 1, 6, 2, 'Complaint updated', '2026-07-28 11:52:36', '2026-07-28 11:52:36'),
	(1099, 537, 1, 10, 2, NULL, '2026-07-28 11:53:20', '2026-07-28 11:53:20'),
	(1100, 535, 1, 17, 2, NULL, '2026-07-28 12:34:43', '2026-07-28 12:34:43'),
	(1101, 532, 1, 4, 2, NULL, '2026-07-28 12:35:48', '2026-07-28 12:35:48'),
	(1102, 538, 10, NULL, 17, NULL, '2026-07-29 06:09:50', '2026-07-29 06:09:50'),
	(1103, 532, 1, 30, 2, NULL, '2026-07-29 08:38:38', '2026-07-29 08:38:38'),
	(1104, 532, 1, 6, 2, NULL, '2026-07-29 08:39:28', '2026-07-29 08:39:28'),
	(1105, 532, 1, 30, 2, NULL, '2026-07-29 08:40:02', '2026-07-29 08:40:02'),
	(1106, 530, 1, 30, 2, NULL, '2026-07-29 08:40:05', '2026-07-29 08:40:05'),
	(1107, 529, 1, 30, 2, NULL, '2026-07-29 08:40:08', '2026-07-29 08:40:08'),
	(1108, 543, 1, NULL, 1, 'Complaint created', '2026-07-29 08:57:38', '2026-07-29 08:57:38'),
	(1109, 544, 1, NULL, 1, 'Complaint created', '2026-07-29 08:58:25', '2026-07-29 08:58:25'),
	(1110, 545, 1, NULL, 1, 'Complaint created', '2026-07-29 09:01:13', '2026-07-29 09:01:13'),
	(1111, 546, 1, NULL, 1, 'Complaint created', '2026-07-29 09:04:07', '2026-07-29 09:04:07'),
	(1112, 547, 1, NULL, 1, 'Complaint created', '2026-07-29 09:15:33', '2026-07-29 09:15:33'),
	(1113, 548, 1, 2, 1, 'Complaint created', '2026-07-29 09:16:56', '2026-07-29 09:16:56'),
	(1114, 547, 1, 3, 2, NULL, '2026-07-29 09:48:59', '2026-07-29 09:48:59'),
	(1115, 548, 1, 3, 2, NULL, '2026-07-29 10:05:37', '2026-07-29 10:05:37'),
	(1116, 549, 0, NULL, 1, 'Complaint created', '2026-07-29 10:56:21', '2026-07-29 10:56:21'),
	(1117, 550, 0, NULL, 1, 'Complaint created', '2026-07-29 11:07:58', '2026-07-29 11:07:58'),
	(1118, 551, 0, NULL, 1, 'Complaint created', '2026-07-29 11:11:41', '2026-07-29 11:11:41'),
	(1119, 552, 0, NULL, 1, 'Complaint created', '2026-07-29 11:13:34', '2026-07-29 11:13:34'),
	(1120, 553, 1, NULL, 1, 'Complaint created', '2026-07-29 11:17:46', '2026-07-29 11:17:46'),
	(1121, 554, 1, NULL, 1, 'Complaint created', '2026-07-29 11:20:09', '2026-07-29 11:20:09'),
	(1122, 555, 1, 30, 1, 'Complaint created', '2026-07-29 11:27:53', '2026-07-29 11:27:53'),
	(1123, 555, 1, 3, 2, 'Complaint updated', '2026-07-29 11:29:54', '2026-07-29 11:29:54'),
	(1124, 555, 1, 1, 2, 'Complaint updated', '2026-07-29 11:31:05', '2026-07-29 11:31:05'),
	(1125, 554, 1, 7, 2, NULL, '2026-07-29 11:37:29', '2026-07-29 11:37:29'),
	(1126, 555, 1, 18, 2, NULL, '2026-07-29 11:37:42', '2026-07-29 11:37:42'),
	(1127, 555, 1, 17, 2, NULL, '2026-07-29 11:37:57', '2026-07-29 11:37:57'),
	(1128, 555, 1, 1, 2, 'Complaint updated', '2026-07-29 11:39:36', '2026-07-29 11:39:36'),
	(1129, 555, 1, 2, 2, NULL, '2026-07-29 11:42:46', '2026-07-29 11:42:46'),
	(1130, 529, 1, 27, 2, NULL, '2026-07-29 11:52:37', '2026-07-29 11:52:37'),
	(1131, 549, 27, 1, 2, NULL, '2026-07-29 11:58:02', '2026-07-29 11:58:02'),
	(1132, 549, 27, 2, 2, NULL, '2026-07-29 12:01:00', '2026-07-29 12:01:00'),
	(1133, 556, 0, NULL, 1, 'Complaint created', '2026-07-29 12:08:41', '2026-07-29 12:08:41'),
	(1134, 556, 1, 2, 2, NULL, '2026-07-29 12:09:22', '2026-07-29 12:09:22'),
	(1135, 556, 1, 1, 2, 'Complaint updated', '2026-07-29 12:09:32', '2026-07-29 12:09:32'),
	(1136, 556, 1, 27, 2, 'Complaint updated', '2026-07-29 12:09:51', '2026-07-29 12:09:51'),
	(1137, 556, 1, 1, 2, 'Complaint updated', '2026-07-29 12:10:03', '2026-07-29 12:10:03'),
	(1138, 556, 1, 3, 2, NULL, '2026-07-29 12:11:09', '2026-07-29 12:11:09'),
	(1139, 556, 1, 2, 2, NULL, '2026-07-29 12:14:55', '2026-07-29 12:14:55'),
	(1140, 557, 1, 2, 1, 'Complaint created', '2026-07-29 12:16:50', '2026-07-29 12:16:50'),
	(1141, 558, 3, 1, 1, 'Complaint created', '2026-07-29 12:17:34', '2026-07-29 12:17:34'),
	(1142, 558, 1, 2, 2, NULL, '2026-07-29 12:24:07', '2026-07-29 12:24:07'),
	(1143, 556, 3, 3, 2, NULL, '2026-07-29 12:24:58', '2026-07-29 12:24:58'),
	(1144, 557, 1, 1, 2, NULL, '2026-07-29 12:31:33', '2026-07-29 12:31:33'),
	(1145, 557, 1, 4, 2, NULL, '2026-07-29 12:31:38', '2026-07-29 12:31:38'),
	(1146, 558, 1, 1, 2, NULL, '2026-07-29 12:31:42', '2026-07-29 12:31:42'),
	(1147, 558, 1, 30, 2, 'Complaint updated', '2026-07-29 12:33:24', '2026-07-29 12:33:24'),
	(1148, 554, 1, 7, 8, 'Complaint updated', '2026-07-29 12:37:26', '2026-07-29 12:37:26'),
	(1149, 559, 1, 7, 1, 'Complaint created', '2026-07-30 05:24:11', '2026-07-30 05:24:11'),
	(1150, 560, 0, NULL, 1, 'Complaint created', '2026-07-30 09:38:35', '2026-07-30 09:38:35'),
	(1151, 561, 0, NULL, 1, 'Complaint created', '2026-07-30 09:42:58', '2026-07-30 09:42:58'),
	(1152, 562, 1, 2, 1, 'Complaint created', '2026-07-30 09:55:21', '2026-07-30 09:55:21'),
	(1153, 562, 1, 1, 2, NULL, '2026-07-30 10:07:06', '2026-07-30 10:07:06'),
	(1154, 561, 1, 1, 2, NULL, '2026-07-30 10:07:11', '2026-07-30 10:07:11'),
	(1155, 560, 1, 1, 2, NULL, '2026-07-30 10:07:18', '2026-07-30 10:07:18'),
	(1156, 560, 1, 1, 8, 'Complaint updated', '2026-07-30 10:07:33', '2026-07-30 10:07:33'),
	(1157, 562, 1, 1, 3, 'Complaint updated', '2026-07-30 10:08:48', '2026-07-30 10:08:48'),
	(1158, 562, 1, 30, 2, NULL, '2026-07-30 10:20:47', '2026-07-30 10:20:47'),
	(1159, 563, 1, NULL, 2, 'Complaint created via bulk import', '2026-07-30 12:55:20', '2026-07-30 12:55:20'),
	(1160, 564, 1, NULL, 2, 'Complaint created via bulk import', '2026-07-30 13:08:56', '2026-07-30 13:08:56'),
	(1161, 565, 1, 30, 1, 'Complaint created', '2026-07-31 05:43:29', '2026-07-31 05:43:29'),
	(1162, 566, 1, 4, 2, 'Complaint created and assigned', '2026-07-31 05:48:25', '2026-07-31 05:48:25'),
	(1163, 567, 1, NULL, 1, 'Complaint created', '2026-07-31 05:53:18', '2026-07-31 05:53:18'),
	(1164, 567, 1, 7, 2, 'Complaint updated', '2026-07-31 05:53:29', '2026-07-31 05:53:29'),
	(1165, 568, 1, 2, 2, 'Complaint created and assigned', '2026-07-31 05:54:06', '2026-07-31 05:54:06'),
	(1166, 570, 1, 30, 2, 'Complaint created and assigned', '2026-07-31 06:07:10', '2026-07-31 06:07:10'),
	(1167, 570, 30, NULL, 8, 'This is completed', '2026-07-31 06:12:13', '2026-07-31 06:12:13'),
	(1168, 571, 1, 2, 2, 'Complaint created and assigned', '2026-07-31 06:22:25', '2026-07-31 06:22:25'),
	(1169, 571, 1, 5, 2, NULL, '2026-07-31 06:22:49', '2026-07-31 06:22:49'),
	(1170, 570, 1, NULL, 6, 'checked', '2026-07-31 06:22:56', '2026-07-31 06:22:56'),
	(1171, 571, 1, 5, 2, 'Complaint updated', '2026-07-31 06:23:39', '2026-07-31 06:23:39'),
	(1172, 572, 1, 7, 2, 'Complaint created and assigned', '2026-07-31 06:28:09', '2026-07-31 06:28:09'),
	(1173, 573, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 06:30:02', '2026-07-31 06:30:02'),
	(1174, 574, 0, NULL, 1, 'Complaint created', '2026-07-31 06:31:21', '2026-07-31 06:31:21'),
	(1175, 574, 1, 2, 2, 'Complaint updated', '2026-07-31 06:32:30', '2026-07-31 06:32:30'),
	(1176, 573, 1, NULL, 6, 'checked', '2026-07-31 06:32:45', '2026-07-31 06:32:45'),
	(1177, 575, 1, 30, 6, 'Complaint created via bulk import', '2026-07-31 06:58:13', '2026-07-31 06:58:13'),
	(1178, 576, 1, NULL, 2, 'Complaint created via bulk import', '2026-07-31 08:37:58', '2026-07-31 08:37:58'),
	(1179, 576, 1, 1, 2, NULL, '2026-07-31 09:23:54', '2026-07-31 09:23:54'),
	(1180, 577, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1181, 578, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1182, 579, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1183, 580, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1184, 581, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1185, 582, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1186, 583, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1187, 584, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1188, 585, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1189, 586, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1190, 587, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1191, 588, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1192, 589, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1193, 590, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1194, 591, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1195, 592, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1196, 593, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1197, 594, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1198, 595, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1199, 596, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1200, 597, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1201, 598, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1202, 599, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1203, 600, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1204, 601, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1205, 602, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1206, 603, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1207, 604, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1208, 605, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1209, 606, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1210, 607, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1211, 608, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1212, 609, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1213, 610, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1214, 611, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1215, 612, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1216, 613, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1217, 614, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1218, 615, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1219, 616, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1220, 617, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1221, 618, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1222, 619, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1223, 620, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1224, 621, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1225, 622, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1226, 623, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1227, 624, 1, 30, 8, 'Complaint created via bulk import', '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(1228, 561, 1, 1, 2, 'Complaint updated', '2026-07-31 09:31:30', '2026-07-31 09:31:30'),
	(1229, 576, 1, 1, 2, NULL, '2026-07-31 09:31:51', '2026-07-31 09:31:51'),
	(1230, 576, 1, 1, 8, 'Status updated', '2026-07-31 09:38:08', '2026-07-31 09:38:08'),
	(1231, 577, 1, 1, 2, NULL, '2026-07-31 09:38:23', '2026-07-31 09:38:23'),
	(1232, 577, 1, 1, 8, 'Status updated', '2026-07-31 09:38:28', '2026-07-31 09:38:28'),
	(1233, 577, 1, NULL, 6, 'checked', '2026-07-31 09:38:32', '2026-07-31 09:38:32'),
	(1234, 578, 1, 30, 2, NULL, '2026-07-31 09:40:33', '2026-07-31 09:40:33'),
	(1235, 578, 1, 30, 2, NULL, '2026-07-31 09:40:47', '2026-07-31 09:40:47'),
	(1236, 579, 1, 30, 2, NULL, '2026-07-31 09:41:03', '2026-07-31 09:41:03'),
	(1237, 578, 30, 30, 3, 'Status updated', '2026-07-31 09:41:27', '2026-07-31 09:41:27'),
	(1238, 578, 30, 30, 4, 'ohho', '2026-07-31 09:44:20', '2026-07-31 09:44:20'),
	(1239, 578, 30, 30, 3, 'Status updated', '2026-07-31 09:44:34', '2026-07-31 09:44:34'),
	(1240, 578, 30, 30, 4, 'Status updated', '2026-07-31 09:44:42', '2026-07-31 09:44:42'),
	(1241, 578, 30, 30, 3, 'fdfsdf', '2026-07-31 09:44:46', '2026-07-31 09:44:46');

-- Dumping structure for table tms-laravel.complaint_vertical
CREATE TABLE IF NOT EXISTS `complaint_vertical` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `complaint_id` bigint unsigned NOT NULL,
  `vertical_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `complaint_vertical_complaint_id_vertical_id_unique` (`complaint_id`,`vertical_id`),
  KEY `complaint_vertical_vertical_id_foreign` (`vertical_id`),
  CONSTRAINT `complaint_vertical_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaint_vertical_vertical_id_foreign` FOREIGN KEY (`vertical_id`) REFERENCES `verticals` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=806 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.complaint_vertical: ~2 rows (approximately)
DELETE FROM `complaint_vertical`;
INSERT INTO `complaint_vertical` (`id`, `complaint_id`, `vertical_id`, `created_at`, `updated_at`) VALUES
	(804, 509, 3, NULL, NULL),
	(805, 509, 10, NULL, NULL);

-- Dumping structure for table tms-laravel.complaints
CREATE TABLE IF NOT EXISTS `complaints` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL DEFAULT '0',
  `network_type_id` bigint unsigned DEFAULT NULL,
  `section_id` bigint unsigned DEFAULT NULL,
  `intercom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_number` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `request_type_id` bigint unsigned DEFAULT NULL,
  `vertical_id` bigint unsigned DEFAULT NULL,
  `status_id` bigint unsigned NOT NULL,
  `assigned_to` bigint unsigned DEFAULT NULL,
  `assigned_by` bigint unsigned DEFAULT NULL,
  `resolution` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `complaints_reference_number_unique` (`reference_number`),
  KEY `complaints_network_type_id_foreign` (`network_type_id`),
  KEY `complaints_section_id_foreign` (`section_id`),
  KEY `complaints_assigned_to_foreign` (`assigned_to`),
  KEY `complaints_assigned_by_foreign` (`assigned_by`),
  KEY `complaints_status_id_foreign` (`status_id`),
  KEY `complaints_request_type_id_foreign` (`request_type_id`),
  KEY `complaints_vertical_id_foreign` (`vertical_id`),
  CONSTRAINT `complaints_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `complaints_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `complaints_network_type_id_foreign` FOREIGN KEY (`network_type_id`) REFERENCES `network_types` (`id`),
  CONSTRAINT `complaints_request_type_id_foreign` FOREIGN KEY (`request_type_id`) REFERENCES `request_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `complaints_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  CONSTRAINT `complaints_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  CONSTRAINT `complaints_vertical_id_foreign` FOREIGN KEY (`vertical_id`) REFERENCES `verticals` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=625 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.complaints: ~68 rows (approximately)
DELETE FROM `complaints`;
INSERT INTO `complaints` (`id`, `reference_number`, `user_name`, `client_id`, `network_type_id`, `section_id`, `intercom`, `room_number`, `description`, `file_path`, `priority`, `request_type_id`, `vertical_id`, `status_id`, `assigned_to`, `assigned_by`, `resolution`, `created_at`, `updated_at`) VALUES
	(509, 'H-PSD-PS2-20260721003', 'Suhani', 0, 2, 9, '120456', '201', 'Test Issue Case...', NULL, 'medium', 2, 3, 2, 8, 1, NULL, '2026-07-21 05:58:17', '2026-07-28 11:51:57'),
	(510, 'SWE-AS-20260721004', 'Harsh Singh', 1, 2, 1, '1234', '101', 'Sample complaint description', NULL, 'high', 2, 10, 6, 8, 1, NULL, '2026-07-21 06:52:39', '2026-07-21 09:59:20'),
	(511, 'SWE-AS-20260721005', 'Harsh Singh', 1, 2, 2, '1234', '101', 'Sample complaint description', NULL, 'high', 2, 25, 2, 7, 1, NULL, '2026-07-21 06:58:24', '2026-07-21 09:32:40'),
	(512, 'CS-ISS-IS2-20260721004', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', 1, 15, 8, 17, 1, NULL, '2026-07-21 10:08:19', '2026-07-23 05:54:25'),
	(513, 'CS-ISS-IS2-20260721005', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, 2, 2, 9, 1, NULL, '2026-07-21 10:08:19', '2026-07-21 12:21:27'),
	(514, 'CS-ISS-IS2-20260721006', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, 15, 2, 11, 1, NULL, '2026-07-21 10:08:19', '2026-07-21 12:20:13'),
	(515, 'CS-ISS-IS2-20260721007', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, 9, 6, 8, 8, NULL, '2026-07-23 10:08:19', '2026-07-23 07:24:09'),
	(516, 'CS-ISS-IS2-20260721008', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, NULL, 2, 6, NULL, NULL, '2026-07-21 10:08:19', '2026-07-21 10:08:19'),
	(517, 'CS-ISS-IS2-20260721009', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', 2, 3, 2, 8, 1, NULL, '2026-07-23 10:08:19', '2026-07-23 11:29:27'),
	(518, 'CS-ISS-IS2-20260721010', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, 16, 8, 8, 8, NULL, '2026-07-21 10:08:19', '2026-07-23 08:42:27'),
	(519, 'CS-ISS-IS2-20260721011', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, 3, 2, 10, 1, NULL, '2026-07-21 10:08:19', '2026-07-21 12:20:21'),
	(520, 'CS-ISS-IS2-20260721012', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, 12, 2, 7, 1, NULL, '2026-07-21 10:08:19', '2026-07-21 12:20:26'),
	(521, 'CS-ISS-IS2-20260721013', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, 14, 2, 18, 1, NULL, '2026-07-21 10:08:19', '2026-07-21 12:20:59'),
	(522, 'CS-ISS-IS2-20260721014', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, NULL, 2, 6, NULL, NULL, '2026-07-21 10:08:19', '2026-07-21 10:08:19'),
	(523, 'CS-ISS-IS2-20260721015', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, 21, 2, 6, NULL, NULL, '2026-07-21 10:08:19', '2026-07-21 10:08:19'),
	(524, 'CS-ISS-IS2-20260721016', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, 15, 2, 27, 1, NULL, '2026-07-21 10:08:19', '2026-07-21 12:21:43'),
	(525, 'CS-ISS-IS2-20260721017', 'Test User', 1, 2, 4, '1234', '201', 'test this', NULL, 'medium', NULL, 2, 2, 3, 1, NULL, '2026-07-21 10:08:19', '2026-07-21 12:21:51'),
	(526, 'CS-ISS-20260723003', 'Ankita Singh', 8, 1, 4, '120456', '201', 'Test issue generated...', NULL, 'medium', 2, 25, 2, 6, NULL, NULL, '2026-07-23 08:55:23', '2026-07-23 08:55:23'),
	(527, 'CS-NS-20260723004', 'Karishma Kumari', 0, 1, 3, '120456', '301', 'Test issue generated...', NULL, 'medium', 1, 13, 1, NULL, NULL, NULL, '2026-07-23 08:56:34', '2026-07-23 08:56:34'),
	(528, 'CS-ISS-IS2-20260723005', 'Test User', 1, 1, 16, '120456', '201', 'Test this issue', NULL, 'medium', 2, 21, 1, NULL, NULL, NULL, '2026-07-23 09:25:07', '2026-07-23 09:25:07'),
	(529, 'SWE-20260723006', 'Test User', 1, 2, 2, '120456', '201', 'Test issue', NULL, 'medium', 2, 3, 2, 27, 1, NULL, '2026-07-23 11:38:59', '2026-07-29 11:52:37'),
	(530, 'SWE-20260723007', 'Test User', 1, 2, 2, '120456', '201', 'Test issue', NULL, 'medium', 2, 31, 2, 30, 1, NULL, '2026-07-23 11:39:58', '2026-07-29 08:40:05'),
	(531, 'SWE-20260723008', 'Test User', 1, 1, 5, '120456', '201', 'Test issue', NULL, 'medium', 2, 2, 6, 5, 1, NULL, '2026-07-23 11:41:25', '2026-07-23 12:35:53'),
	(532, 'SWE-20260723009', 'Prabhanshu', 1, 2, 6, '120456', '201', 'Test issue..', NULL, 'medium', 2, 3, 2, 30, 1, NULL, '2026-07-23 11:51:58', '2026-07-29 08:40:02'),
	(533, 'NET-WS-20260723010', 'Amrita Rao', 1, 1, 4, '120456', '201', 'Test issue craeted', NULL, 'medium', 2, 3, 8, 10, 1, NULL, '2026-07-23 12:09:03', '2026-07-24 10:12:02'),
	(534, 'VC-20260723011', 'Purshottam Kumar', 0, 2, 5, '120456', '321', 'Test issue created', NULL, 'medium', 2, 2, 2, 3, 1, NULL, '2026-07-23 12:09:25', '2026-07-23 12:09:57'),
	(535, 'CS-ISS-IS2-20260723012', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Sample complaint description', NULL, 'medium', 1, 21, 2, 17, 1, NULL, '2026-07-23 12:34:10', '2026-07-28 12:34:43'),
	(536, 'NET-WS-20260724001', 'Gitesh Kumar Srivastav', 1, 2, 4, '120456', '201', 'Test issue...', NULL, 'medium', 2, 14, 2, 6, 1, NULL, '2026-07-24 07:23:54', '2026-07-24 07:24:33'),
	(537, 'NET-20260724002', 'Karan Divakar', 1, 1, 9, '120456', '201', 'This is test issue....', NULL, 'high', 2, 2, 2, 10, 1, NULL, '2026-07-24 09:27:52', '2026-07-28 11:53:20'),
	(538, 'SWE-20260724003', 'Test User', 1, 1, 9, '120789', '201', 'Test issue..', NULL, 'high', 2, 10, 17, 10, 30, NULL, '2026-07-24 09:39:25', '2026-07-29 06:09:50'),
	(539, 'H-PSD-PS2-20260727001', 'Laxmi Prasad', 1, 1, 9, '120789', '201', 'Test Issue Created..', NULL, 'medium', 2, 17, 2, 11, 1, NULL, '2026-07-27 05:53:07', '2026-07-27 06:23:35'),
	(540, 'CS-NS-20260727002', 'Test User', 1, 1, 9, '120369', '201', 'Test Issue Created..', NULL, 'medium', 2, 13, 2, 4, 1, NULL, '2014-07-27 06:00:48', '2026-07-27 11:43:13'),
	(541, 'CS-ISS-IS2-20260727003', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Sample complaint description', NULL, 'medium', 1, 21, 2, NULL, NULL, NULL, '2026-07-27 06:31:21', '2026-07-27 06:31:21'),
	(542, 'H-PSD-PS2-20260728001', 'test user', 1, 1, 9, '120789', '201', 'Test this issue..', NULL, 'medium', 2, 14, 2, 6, 1, NULL, '2026-07-28 11:50:06', '2026-07-28 11:52:36'),
	(543, 'CS-ISS-TEST41-20260729001', 'Test User', 1, 2, 6, '120458', '201', 'Test Issue', NULL, 'medium', 2, 35, 1, NULL, NULL, NULL, '2026-07-29 08:57:38', '2026-07-29 08:57:38'),
	(544, 'CS-ISS-IS2-20260729002', 'tet', 1, 2, 5, '120456', '1201', 'Test', NULL, 'medium', 1, 21, 1, NULL, NULL, NULL, '2026-07-29 08:58:25', '2026-07-29 08:58:25'),
	(545, 'CS-ISS-IS2-20260729003', 'Test User', 1, 2, 3, '120456', '201', 'Test', NULL, 'medium', 1, 21, 1, NULL, NULL, NULL, '2026-07-29 09:01:13', '2026-07-29 09:01:13'),
	(546, 'CS-20260729004', 'test user', 1, 1, 3, '1234', '120', 'fdgdfg', NULL, 'medium', 2, 4, 1, NULL, NULL, NULL, '2026-07-29 09:04:07', '2026-07-29 09:04:07'),
	(547, 'CS-ISS-TEST41-20260729005', 'Laxmi Prasad', 1, 2, 5, '1234', '201', 'Test', NULL, 'medium', 1, 35, 2, 3, 1, NULL, '2026-07-29 09:15:32', '2026-07-29 09:48:59'),
	(548, 'CS-ISS-ISS3-TEST4-TEST41-20260729006', 'test user', 1, 2, 4, '120963`', '201', 'Test', NULL, 'medium', 2, 35, 2, 3, 1, NULL, '2026-07-29 09:16:56', '2026-07-29 10:05:37'),
	(549, 'SWE-AS-20260729007', 'Karan Divakar', 0, 1, 6, '120456', '2010', 'Test Issue created..', NULL, 'medium', 2, 31, 2, 2, 27, NULL, '2026-07-29 10:56:21', '2026-07-29 12:01:00'),
	(550, 'CS-20260729008', 'Karan Divakar', 0, 2, 5, '120789', '201', 'Test', NULL, 'medium', 2, 4, 1, NULL, NULL, NULL, '2026-07-29 11:07:58', '2026-07-29 11:07:58'),
	(551, 'CS-20260729009', 'Laxmi Prasad', 0, 2, 4, '1234', '201', 'Test', NULL, 'medium', 1, 4, 1, NULL, NULL, NULL, '2026-07-29 11:11:41', '2026-07-29 11:11:41'),
	(552, 'CS-ISS-20260729010', 'test user', 0, 1, 4, '120789', '201', 'Test', NULL, 'medium', 2, 12, 1, NULL, NULL, NULL, '2026-07-29 11:13:34', '2026-07-29 11:13:34'),
	(553, 'CS-20260729011', 'Laxmi Prasad', 1, 1, 5, '120789', '201', 'Tet', NULL, 'medium', 2, 4, 1, NULL, NULL, NULL, '2026-07-29 11:17:46', '2026-07-29 11:17:46'),
	(554, 'CS-ISS-IS2-20260729012', 'Test User', 1, 2, 2, '1234', '201', 'Test Issue Created.....', NULL, 'medium', 1, 21, 8, 7, 1, NULL, '2026-07-29 11:20:09', '2026-07-29 12:37:26'),
	(555, 'CS-ISS-IS2-20260729013', 'Laxmi Prasad', 1, 1, 5, '1234', '201', 'Test Issue Created', NULL, 'medium', 2, 21, 2, 2, 1, NULL, '2026-07-29 11:27:53', '2026-07-29 11:42:46'),
	(556, 'SWE-DS-20260729014', 'Ram Prasad', 0, 1, 9, '120458', '320', 'Make a table for verticals', NULL, 'medium', 2, 14, 2, 3, 3, NULL, '2026-07-29 12:08:41', '2026-07-29 12:24:58'),
	(557, 'CS-NS-20260729015', 'test user', 1, 1, 4, '1234', '201', 'Test issue', NULL, 'medium', 2, 13, 2, 4, 1, NULL, '2026-07-29 12:16:49', '2026-07-29 12:31:38'),
	(558, 'SWE-DS-20260729016', 'test user', 3, 1, 6, '120369', '201', 'Test', NULL, 'medium', 2, 31, 2, 30, 1, NULL, '2026-07-29 12:17:34', '2026-07-29 12:33:24'),
	(559, 'CS-ISS-ISS3-TEST4-TEST41-20260730001', 'Test User', 1, 1, 4, '1234', '201', 'Test issue', NULL, 'high', 2, 35, 2, 7, NULL, NULL, '2026-07-30 05:24:11', '2026-07-30 05:24:11'),
	(560, 'CMP-20260730002', 'Test User', 0, 2, 4, '120789', '201', 'Test Issue created..', NULL, 'medium', 1, 31, 8, 1, 1, NULL, '2026-07-30 09:38:35', '2026-07-30 10:07:33'),
	(561, 'CMP-20260730003', 'Test User Created', 0, 2, 9, '123456', '201', 'Test Issue created', NULL, 'medium', 2, 31, 2, 1, 1, NULL, '2026-07-30 09:42:58', '2026-07-31 09:31:30'),
	(562, 'CMP-20260730004', 'Test User', 1, 2, 4, '1234', '201', 'Test Issue Created', NULL, 'medium', 1, 31, 2, 30, 1, NULL, '2026-07-30 09:55:21', '2026-07-30 10:20:47'),
	(563, 'CS-ISS-IS2-20260730005', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Sample complaint description', NULL, 'medium', 1, 21, 2, NULL, NULL, NULL, '2026-07-30 12:55:20', '2026-07-30 12:55:20'),
	(564, 'CMP-20260730006', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Sample complaint description', NULL, 'medium', 1, 21, 2, NULL, NULL, NULL, '2026-07-30 13:08:56', '2026-07-30 13:08:56'),
	(565, 'CMP-20260731001', 'test user', 1, 2, 9, '120458', '201', 'Test issue created..', NULL, 'medium', 1, 31, 2, 30, NULL, NULL, '2026-07-31 05:43:29', '2026-07-31 05:43:29'),
	(566, 'CMP-20260731002', 'test user', 1, 2, 4, '120789', '201', 'Test Issue created', NULL, 'medium', 2, 21, 2, 4, NULL, NULL, '2026-07-31 05:48:25', '2026-07-31 05:48:25'),
	(567, 'CMP-20260731003', 'Laxmi Prasad', 1, 2, 2, '1234', '201', 'Test Issue created', NULL, 'medium', 1, 2, 2, 7, 1, NULL, '2026-07-31 05:53:18', '2026-07-31 05:53:29'),
	(568, 'CMP-20260731004', 'Karan Divakar', 1, 2, 6, '120789', '201', 'Test Issue created......', NULL, 'medium', 2, 31, 2, 2, NULL, NULL, '2026-07-31 05:54:06', '2026-07-31 05:54:06'),
	(569, 'CMP-20260731005', 'Harsh Singh', 1, 1, 4, '120369', '201', 'Software is updated so inform everywhere..', NULL, 'medium', 2, 31, 2, 30, 1, NULL, '2026-07-31 06:04:47', '2026-07-31 06:04:47'),
	(570, 'CMP-20260731006', 'Harsh Singh', 1, 1, 4, '120369', '201', 'Software is updated so inform everywhere..', NULL, 'medium', 2, 31, 6, 30, 1, NULL, '2026-07-31 06:07:10', '2026-07-31 06:22:56'),
	(571, 'CMP-20260731007', 'Karan Divakar', 1, 1, 5, '120789', '201', 'Test issue created.....', NULL, 'medium', 2, 11, 2, 5, 1, NULL, '2026-07-31 06:22:25', '2026-07-31 06:23:39'),
	(572, 'CMP-20260731008', 'Prasad', 1, 2, 5, '123489', '201', 'Tets Created', NULL, 'medium', 2, 36, 2, 7, 1, NULL, '2026-07-31 06:28:09', '2026-07-31 06:28:09'),
	(573, 'CMP-20260731009', 'Test User', 1, 2, 9, '1234', '101', 'Sample complaint description', NULL, 'high', 2, 36, 6, 30, 1, NULL, '2026-07-31 06:30:02', '2026-07-31 06:32:45'),
	(574, 'CMP-20260731010', 'Karan Divakar', 0, 2, 5, '120369', '201', 'Test Issue created....', NULL, 'high', 2, 21, 2, 2, 1, NULL, '2026-07-31 06:31:21', '2026-07-31 06:32:30'),
	(575, 'CMP-20260731011', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Sample complaint description', NULL, 'medium', 1, 21, 6, 30, 1, NULL, '2026-07-31 06:58:13', '2026-07-31 06:58:13'),
	(576, 'CMP-20260731012', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Sample complaint description', NULL, 'medium', 1, 21, 8, 1, 1, NULL, '2026-07-31 08:37:58', '2026-07-31 09:38:08'),
	(577, 'CMP-20260731013', 'Harsh Singh', 1, 1, 1, '1234', '101', 'On the Create page, ensure the first available option in the Request Type and Issue Type dropdowns is pre-selected by default.', NULL, 'medium', 1, 11, 6, 1, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:38:32'),
	(578, 'CMP-20260731014', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Update the layout/formatting in the User Management section to clearly display categories along with their mapped sub-categories.', NULL, 'medium', 1, 11, 3, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:44:46'),
	(579, 'CMP-20260731015', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Filter the Assign To dropdown options dynamically based on the selected Category or Sub-category.', NULL, 'medium', 1, 11, 2, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:41:03'),
	(580, 'CMP-20260731016', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Ensure the Close button and its corresponding confirmation modal/dialog box are available for both closed and reassigned complaints.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(581, 'CMP-20260731017', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Allow completed complaints to be edited, but restrict this capability exclusively to users with the Manager role.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(582, 'CMP-20260731018', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Resolve the issues occurring during Excel file downloads.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(583, 'CMP-20260731019', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Remove background status highlights/colors (such as yellow, red, etc.) from the Usage Report for a cleaner view.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(584, 'CMP-20260731020', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Ensure using the Close button updates the ticket status dynamically without triggering a full page refresh.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(585, 'CMP-20260731021', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Ensure the Status field is included in the exported Excel file.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(586, 'CMP-20260731022', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Set the default status in the Excel file export to Assigned.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(587, 'CMP-20260731023', 'Harsh Singh', 1, 1, 1, '1234', '101', 'When a VM user logs in, hide the Reassign, Revert, and other action options from the Pending action bar.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(588, 'CMP-20260731024', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Add a direct Create button to the top navigation bar.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(589, 'CMP-20260731025', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Update the 3rd metric box (Completed & Closed) on the Dashboard to fetch and calculate data using both Completed and Closed statuses.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(590, 'CMP-20260731026', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Enable multi-select functionality on the Ticket Page filter (allowing Completed and Closed to be selected together) and make all filters across the system support multi-select.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(591, 'CMP-20260731027', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Fix the issue where the Status field hides upon category change on the Edit page, and switch Category/Sub-category trigger logic from onChange to onLoad on both Create and Edit pages.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(592, 'CMP-20260731028', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Hide the VM, NFO, and Manager role labels inside the user profile display box.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(593, 'CMP-20260731029', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Fix the Ticket Details page so that the relevant User Name is correctly displayed.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(594, 'CMP-20260731030', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Display From Date and To Date on the print view, and show full category hierarchy (e.g., Parent -> Sub-Category -> Sub-Category) in the category column.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(595, 'CMP-20260731031', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Revert the Completion Ratio widget layout under User Performance back to the previous design.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(596, 'CMP-20260731032', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Include users with the Manager role in the Assign To dropdown list on both Create and Edit pages.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(597, 'CMP-20260731033', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Intercom / Telephone Field: Update input handling so users do not need to press "Enter" to save/store the phone number.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(598, 'CMP-20260731034', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Redirection Logic: Fix the form submission behavior so that after creating a ticket, the user is redirected back to their previous page instead of always defaulting to the Ticket page.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(599, 'CMP-20260731035', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Rename Section: Rename Network Types to Issue Type.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(600, 'CMP-20260731036', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Parent Category Selection: Add the ability to edit and change the parent category within the Category section.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(601, 'CMP-20260731037', 'Harsh Singh', 1, 1, 1, '1234', '101', 'User Assignment: Add a User assignment option under Category settings.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(602, 'CMP-20260731038', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Single Modal Refactoring: Consolidate the Add/Edit forms for Status, Issue Type, Request Type, Section, and Category into a single, reusable modal component.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(603, 'CMP-20260731039', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Soft Delete Handling: Ensure that if a Status or Category is soft-deleted, historical data associated with it still renders properly on the Dashboard and Index pages.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(604, 'CMP-20260731040', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Active Tab State: Fix the active tab state persistence issue when navigating within Master Management.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(605, 'CMP-20260731041', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Parent Category Filter: Update the category filter to allow selecting a Parent Category to pull all tickets belonging to that category tree.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(606, 'CMP-20260731042', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Deleted Category Filter: Include soft-deleted categories in filter dropdown options so historical data can be queried.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(607, 'CMP-20260731043', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Request Type Filter: Add a Request Type filter on the Index page.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(608, 'CMP-20260731044', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Pagination State Fix: Fix the issue where updating a user on page 2 (or beyond) resets the pagination back to page 1 after saving.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(609, 'CMP-20260731045', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Validation Bug: Fix the "user_id field is required" error triggered on form submission.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(610, 'CMP-20260731046', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Usage Report: Update the Usage Report to track and display tasks/tickets handled by Managers.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(611, 'CMP-20260731047', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Verticals & Main Category Statistics: Add an is_excluded column to the verticals table and create a dedicated summary box to display Main Category Aggregated Statistics.', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(612, 'CMP-20260731048', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Removed revert button', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(613, 'CMP-20260731049', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Hidden Request Type and Category during complaint creation for unauthorized users', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(614, 'CMP-20260731050', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Ensured complaint numbers are generated using CMP prefix only', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(615, 'CMP-20260731051', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Enabled edit option for all tickets', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(616, 'CMP-20260731052', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Displayed all users in Assign dropdown', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(617, 'CMP-20260731053', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Renamed Assign to me to Pending with me and included all statuses except completed and closed', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(618, 'CMP-20260731054', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Hidden Total Tickets from dashboard for VM and NFO roles', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(619, 'CMP-20260731055', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Replaced action button text with icons and added Created At in table', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(620, 'CMP-20260731056', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Removed short form from Category labels', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(621, 'CMP-20260731057', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Added quick time filter', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(622, 'CMP-20260731058', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Fixed Bulk Import save issue using CMP format', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(623, 'CMP-20260731059', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Fixed issue when creating and assigning a complaint at the same time', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49'),
	(624, 'CMP-20260731060', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Fixed assigned by field when creating and assigning a user simultaneously', NULL, 'medium', 1, 11, 8, 30, 1, NULL, '2026-07-31 09:28:49', '2026-07-31 09:28:49');

-- Dumping structure for table tms-laravel.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table tms-laravel.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table tms-laravel.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table tms-laravel.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.migrations: ~70 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000001_create_cache_table', 1),
	(2, '0001_01_01_000002_create_jobs_table', 1),
	(3, '2025_06_14_000001_create_network_types_table', 1),
	(4, '2025_06_14_000002_create_sections_table', 1),
	(5, '2025_06_14_000003_create_verticals_table', 1),
	(6, '2025_06_14_082726_create_users_table', 1),
	(7, '2025_06_14_082814_create_complaints_table', 1),
	(9, '2025_06_14_082826_create_tms_table', 1),
	(10, '2025_06_14_084437_create_sessions_table', 1),
	(11, '2025_06_20_013642_add_reverted_to_status_enum_in_complaints_table', 2),
	(12, '2025_06_20_104201_create_comments_table', 3),
	(13, '2025_06_20_172906_add_resolution_to_complaints_table', 4),
	(14, '2025_06_23_100502_create_statuses_table', 5),
	(15, '2025_06_23_100540_update_complaints_table_use_status_foreign_key', 5),
	(16, '2025_06_23_100620_migrate_existing_complaint_statuses_to_status_table', 6),
	(17, '2025_06_23_100640_remove_status_enum_column_from_complaints_table', 6),
	(18, '2025_06_23_111507_create_roles_table', 6),
	(19, '2025_06_23_111603_add_role_id_to_users_table', 6),
	(20, '2025_06_23_111634_migrate_existing_user_roles', 6),
	(21, '2025_06_23_111659_remove_role_from_users_table', 6),
	(22, '2025_06_14_082815_create_complaint_actions_table', 7),
	(23, '2025_06_25_082900_remove_remember_token_from_users', 8),
	(24, '2025_07_02_114310_add_deleted_at_to_users_table', 9),
	(25, '2025_07_04_000000_create_user_vertical_table', 10),
	(26, '2025_07_04_000001_migrate_user_verticals', 10),
	(27, '2025_07_07_113756_create_activity_log_table', 11),
	(28, '2025_07_07_113757_add_event_column_to_activity_log_table', 11),
	(29, '2025_07_07_113758_add_batch_uuid_column_to_activity_log_table', 11),
	(30, '2025_07_10_000000_add_must_change_password_to_users_table', 12),
	(31, '2025_07_10_000001_add_visible_to_user_to_statuses_table', 13),
	(32, '2025_07_10_000002_remove_is_active_from_statuses_table', 14),
	(33, '2025_07_09_175356_change_action_to_status_id_in_complaint_actions_table', 15),
	(34, '2025_07_10_180000_drop_action_column_from_complaint_actions_table', 15),
	(35, '2025_07_15_000000_make_description_nullable_in_complaint_actions_table', 16),
	(36, '2025_07_15_120000_make_comment_nullable_in_comments_table', 17),
	(37, '2026_05_22_154416_add_email_to_users_table', 18),
	(38, '2026_05_25_170449_create_complaint_vertical_table', 19),
	(39, '2026_05_25_172246_remove_vertical_id_from_complaints_table', 20),
	(40, '2026_06_11_000001_add_short_form_to_verticals_table', 21),
	(41, '2026_06_11_000002_add_send_email_to_verticals_table', 22),
	(42, '2026_06_22_122428_create_sub_categories_table', 23),
	(43, '2026_06_22_122643_add_sub_category_id_to_complaint_vertical_table', 24),
	(44, '2026_06_23_122931_update_verticals_and_complaints_for_parent_id', 25),
	(45, '2026_07_03_114151_add_request_type_id_to_complaints_table', 26),
	(46, '2026_07_06_175450_create_activity_log_table', 0),
	(47, '2026_07_06_175450_create_cache_table', 0),
	(48, '2026_07_06_175450_create_cache_locks_table', 0),
	(49, '2026_07_06_175450_create_comments_table', 0),
	(50, '2026_07_06_175450_create_complaint_actions_table', 0),
	(51, '2026_07_06_175450_create_complaint_vertical_table', 0),
	(52, '2026_07_06_175450_create_complaints_table', 0),
	(53, '2026_07_06_175450_create_failed_jobs_table', 0),
	(54, '2026_07_06_175450_create_job_batches_table', 0),
	(55, '2026_07_06_175450_create_jobs_table', 0),
	(56, '2026_07_06_175450_create_network_types_table', 0),
	(57, '2026_07_06_175450_create_request_types_table', 0),
	(58, '2026_07_06_175450_create_roles_table', 0),
	(59, '2026_07_06_175450_create_sections_table', 0),
	(60, '2026_07_06_175450_create_sessions_table', 0),
	(61, '2026_07_06_175450_create_statuses_table', 0),
	(62, '2026_07_06_175450_create_user_vertical_table', 0),
	(63, '2026_07_06_175450_create_users_table', 0),
	(64, '2026_07_06_175450_create_verticals_table', 0),
	(65, '2026_07_06_175453_add_foreign_keys_to_comments_table', 0),
	(66, '2026_07_06_175453_add_foreign_keys_to_complaint_actions_table', 0),
	(67, '2026_07_06_175453_add_foreign_keys_to_complaint_vertical_table', 0),
	(68, '2026_07_06_175453_add_foreign_keys_to_complaints_table', 0),
	(69, '2026_07_06_175453_add_foreign_keys_to_user_vertical_table', 0),
	(70, '2026_07_06_175453_add_foreign_keys_to_users_table', 0),
	(71, '2026_07_06_175453_add_foreign_keys_to_verticals_table', 0),
	(72, '2026_07_20_181500_add_vertical_id_to_complaints_table', 27);

-- Dumping structure for table tms-laravel.network_types
CREATE TABLE IF NOT EXISTS `network_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.network_types: ~3 rows (approximately)
DELETE FROM `network_types`;
INSERT INTO `network_types` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Air Gap Network', '2025-06-17 05:29:27', '2026-07-29 06:33:55', NULL),
	(2, 'Internet', '2025-06-17 05:29:27', '2026-07-29 06:33:57', NULL),
	(5, 'test1', '2026-07-29 06:34:01', '2026-07-31 12:00:18', '2026-07-31 12:00:18');

-- Dumping structure for table tms-laravel.request_types
CREATE TABLE IF NOT EXISTS `request_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_types_name_unique` (`name`),
  UNIQUE KEY `request_types_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.request_types: ~3 rows (approximately)
DELETE FROM `request_types`;
INSERT INTO `request_types` (`id`, `name`, `slug`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Change Record', 'change-record', '2026-07-03 06:09:31', '2026-07-29 08:46:57', NULL),
	(2, 'New Entry', 'new_entry', '2026-07-03 06:09:31', '2026-07-29 06:38:22', NULL),
	(4, 'test1', 'test1', '2026-07-29 06:38:03', '2026-07-29 06:38:25', '2026-07-29 06:38:25');

-- Dumping structure for table tms-laravel.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.roles: ~5 rows (approximately)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Admin', 'admin', NULL, '2025-06-23 17:44:48', '2025-06-23 17:44:48', NULL),
	(2, 'Manager', 'manager', NULL, '2025-06-23 17:44:48', '2025-06-23 17:44:48', NULL),
	(3, 'Team Lead', 'vm', NULL, '2025-06-23 17:44:48', '2025-06-23 17:44:48', NULL),
	(4, 'NFO', 'nfo', NULL, '2025-06-23 17:44:48', '2025-06-23 17:44:48', NULL),
	(5, 'Client', 'client', NULL, '2025-06-23 17:44:48', '2025-06-23 17:44:48', NULL);

-- Dumping structure for table tms-laravel.sections
CREATE TABLE IF NOT EXISTS `sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.sections: ~23 rows (approximately)
DELETE FROM `sections`;
INSERT INTO `sections` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'ACC', '2025-06-17 05:29:27', '2026-07-10 06:31:22', NULL),
	(2, 'Ad I', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(3, 'Ad II', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(4, 'CA I', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(5, 'CA II', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(6, 'CA III', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(7, 'CA IV', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(8, 'CA V', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(9, 'Cabinet Section', '2025-06-17 05:29:27', '2026-07-29 07:26:27', NULL),
	(10, 'TS Cell', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(11, 'RTI Cell', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(12, 'VCC Cell', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(13, 'Comp Cell', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(14, 'Imp Cell', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(15, 'General Section', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(16, 'Cash Section', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(17, 'Deregulation Cell', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(18, 'GTE Cell', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(19, 'DPG', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(20, 'DBT', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(21, 'NACWC', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(24, 'ACC21', '2026-07-29 06:31:00', '2026-07-29 06:31:09', NULL);

-- Dumping structure for table tms-laravel.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.sessions: ~0 rows (approximately)
DELETE FROM `sessions`;

-- Dumping structure for table tms-laravel.statuses
CREATE TABLE IF NOT EXISTS `statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'secondary',
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `visible_to_user` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `statuses_name_unique` (`name`),
  UNIQUE KEY `statuses_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.statuses: ~8 rows (approximately)
DELETE FROM `statuses`;
INSERT INTO `statuses` (`id`, `name`, `slug`, `color`, `description`, `sort_order`, `created_at`, `updated_at`, `visible_to_user`, `deleted_at`) VALUES
	(1, 'unassigned', 'unassigned', 'warning', 'Complaint is waiting to be assigned', 1, '2025-06-23 17:43:53', '2026-07-29 07:20:08', 0, NULL),
	(2, 'assigned', 'assigned', 'info', 'Complaint has been assigned to a team member', 2, '2025-06-23 17:43:53', '2026-07-29 07:20:10', 0, NULL),
	(3, 'in_progress', 'in_progress', 'primary', 'Work on the complaint is currently in progress', 3, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 1, NULL),
	(4, 'pending_with_vendor', 'pending_with_vendor', 'danger', 'Complaint has been escalated to higher authority', 4, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 1, NULL),
	(5, 'pending_with_user', 'pending_with_user', 'danger', 'Complaint has been resolved successfully', 5, '2025-06-23 17:43:53', '2026-07-29 07:20:12', 1, NULL),
	(6, 'closed', 'closed', 'success', 'Complaint has been closed', 7, '2025-06-23 17:43:53', '2026-07-29 07:20:16', 0, NULL),
	(8, 'completed', 'completed', 'success', NULL, 6, '2025-06-23 17:43:53', '2026-07-29 07:20:14', 1, NULL),
	(17, 'test1', 'test1', 'danger', NULL, 0, '2026-07-29 06:09:11', '2026-07-29 09:45:31', 1, '2026-07-29 09:45:31');

-- Dumping structure for table tms-laravel.user_vertical
CREATE TABLE IF NOT EXISTS `user_vertical` (
  `user_id` bigint unsigned NOT NULL,
  `vertical_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`vertical_id`),
  KEY `user_vertical_vertical_id_foreign` (`vertical_id`),
  CONSTRAINT `user_vertical_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_vertical_vertical_id_foreign` FOREIGN KEY (`vertical_id`) REFERENCES `verticals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.user_vertical: ~19 rows (approximately)
DELETE FROM `user_vertical`;
INSERT INTO `user_vertical` (`user_id`, `vertical_id`) VALUES
	(3, 1),
	(4, 13),
	(5, 2),
	(6, 14),
	(7, 4),
	(7, 36),
	(8, 1),
	(8, 3),
	(9, 2),
	(10, 2),
	(11, 14),
	(11, 36),
	(17, 9),
	(17, 38),
	(18, 1),
	(27, 3),
	(27, 32),
	(27, 37),
	(27, 38),
	(29, 2),
	(29, 3),
	(29, 13),
	(29, 15),
	(29, 34),
	(30, 31);

-- Dumping structure for table tms-laravel.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vertical_id` bigint unsigned DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_vertical_id_foreign` (`vertical_id`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_vertical_id_foreign` FOREIGN KEY (`vertical_id`) REFERENCES `verticals` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.users: ~14 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `role_id`, `username`, `email`, `phone_number`, `full_name`, `vertical_id`, `password`, `must_change_password`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 2, 'rohit', 'rohit.kumar09@nic.in', NULL, 'Rohit Kumar', NULL, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 18:58:26', '2026-05-22 10:17:50', NULL),
	(2, 2, 'yogesh', 'yogesh.a@nic.in', NULL, 'Yogesh Kumar', NULL, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 18:59:47', '2026-05-22 10:18:26', NULL),
	(3, 3, 'rachit', 'rachit@gmail.com', NULL, 'Rachit Sharma', 1, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:00:25', '2026-07-28 12:11:41', NULL),
	(4, 4, 'manish', 'manish@nic.in', NULL, 'Manish Singh', 1, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:06:42', '2026-05-22 10:20:58', NULL),
	(5, 4, 'rajkumar', 'rajkumar@nic.in', NULL, 'Rajkumar', 2, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:07:23', '2026-06-11 09:00:05', NULL),
	(6, 4, 'sahil', 'sahil@nic.in', NULL, 'Sahil Gulia', 1, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:08:12', '2026-06-11 09:00:14', NULL),
	(7, 3, 'anil', 'anil@nic.in', NULL, 'Anil Singh', 4, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:08:51', '2026-07-29 11:47:41', NULL),
	(8, 3, 'tarun', 'tarun@nic.in', NULL, 'Tarun Kumar', 3, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:10:12', '2026-06-11 09:00:39', NULL),
	(9, 4, 'vikram', 'vikram@nic.in', NULL, 'Vikram Mahlawat', 2, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:11:17', '2026-06-11 09:00:57', NULL),
	(10, 3, 'praveen', 'praveen@nic.in', NULL, 'Praveen Bansal', 3, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:12:09', '2026-06-11 09:00:47', NULL),
	(11, 3, 'ankit', 'anikitchugh@nic.in', NULL, 'Ankit Chugh', 5, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:13:04', '2026-06-11 09:01:15', NULL),
	(17, 4, 'Ankits', 'ankitsharma@nic.in', NULL, 'Ankit Sharma', 1, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-07-01 06:12:47', '2026-06-11 09:01:27', NULL),
	(18, 3, 'prankur', 'prankur@nic.in', NULL, 'Prankur Sharma', 1, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-07-01 08:02:48', '2026-06-11 09:01:37', NULL),
	(27, 3, 'prasad', 'prasad.gl@nic.in', NULL, 'Guggilam Lakshmi Prasad', NULL, '$2y$12$817rqp/juVkWQnsNaSNlOOH.UDsrxt0ePJIgvqewbmMWphUC6lazS', 0, '2026-05-22 10:19:58', '2026-07-29 11:50:36', NULL),
	(29, 4, 'testuser', NULL, NULL, 'Test User', NULL, '$2y$12$Q3ewYp99KuDhbO/I7DwGSOwhA5eNtjqiyVaR3yzqpI7G5d8qX/JXy', 1, '2026-07-10 11:59:49', '2026-07-10 11:59:49', NULL),
	(30, 4, 'harsh', 'distinctharsh@gmail.com', NULL, 'Harsh Singh', NULL, '$2y$12$IhhR47HT.crAaKLufMrdN.zjARa2l8QMsWoqwdo69ZfFrXuigHN8e', 0, '2026-07-15 12:04:10', '2026-07-15 12:05:11', NULL),
	(31, 4, 'test2', NULL, NULL, 'Test2', NULL, '$2y$12$4mdky8RkEjsUBKmZSee88eRLEdBUUNSnjSzNgNh.8DuTZMGq/K/8S', 1, '2026-07-31 05:39:17', '2026-07-31 05:39:17', NULL);

-- Dumping structure for table tms-laravel.verticals
CREATE TABLE IF NOT EXISTS `verticals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL COMMENT 'Null means top-level category',
  `send_email` tinyint(1) NOT NULL DEFAULT '1',
  `is_excluded` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verticals_parent_id_foreign` (`parent_id`),
  CONSTRAINT `verticals_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `verticals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.verticals: ~25 rows (approximately)
DELETE FROM `verticals`;
INSERT INTO `verticals` (`id`, `name`, `parent_id`, `send_email`, `is_excluded`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Network', NULL, 1, 0, '2025-06-17 05:29:27', '2026-06-11 11:54:24', NULL),
	(2, 'VC', NULL, 1, 0, '2025-06-17 05:29:27', '2026-07-14 06:23:45', NULL),
	(3, 'Software', NULL, 1, 0, '2025-06-17 05:29:27', '2026-06-11 11:54:55', NULL),
	(4, 'Cyber Security', NULL, 1, 0, '2025-06-20 04:45:03', '2026-07-29 09:39:09', NULL),
	(5, 'Email', NULL, 1, 0, '2025-06-20 04:41:20', '2026-07-27 07:13:36', NULL),
	(6, 'Hardware', NULL, 1, 1, '2025-07-09 08:25:45', '2026-07-27 07:12:34', NULL),
	(9, 'Other', NULL, 1, 0, '2026-05-21 08:51:49', '2026-07-08 12:05:19', NULL),
	(10, 'Application Security', 3, 1, 1, '2026-06-23 07:17:54', '2026-07-10 11:56:57', NULL),
	(11, 'Database Security', 3, 1, 0, '2026-06-23 07:20:49', '2026-06-23 07:20:49', NULL),
	(12, 'Information Security', 4, 1, 0, '2026-06-23 07:21:09', '2026-07-29 09:39:09', NULL),
	(13, 'Network Security', 4, 1, 0, '2026-06-23 07:21:32', '2026-07-29 09:39:09', NULL),
	(14, 'Wireless Security (Wi-Fi Security)', 1, 1, 0, '2026-06-23 07:21:57', '2026-06-23 07:21:57', NULL),
	(15, 'Email Security', 5, 1, 0, '2026-06-23 08:56:37', '2026-07-10 11:56:37', NULL),
	(16, 'Physical Security of Devices', 6, 1, 1, '2026-06-23 08:56:59', '2026-06-23 08:56:59', NULL),
	(17, 'PSD2', 16, 1, 0, '2026-06-23 09:01:14', '2026-07-10 11:56:45', NULL),
	(21, 'IS2', 12, 1, 0, '2026-06-25 11:30:49', '2026-07-29 09:39:09', NULL),
	(25, 'Iss334', 12, 1, 0, '2026-07-10 06:38:24', '2026-07-29 09:39:09', NULL),
	(29, 'test4', 25, 1, 0, '2026-07-23 09:23:25', '2026-07-29 09:39:09', NULL),
	(30, 'Test Category', NULL, 1, 0, '2026-07-27 09:18:01', '2026-07-27 09:18:01', NULL),
	(31, 'Test 2', 10, 1, 0, '2026-07-27 10:00:49', '2026-07-27 10:00:49', NULL),
	(32, 'testyyy', 4, 1, 0, '2026-07-27 10:28:59', '2026-07-31 12:06:45', NULL),
	(33, 'tstyyyy2', 32, 1, 0, '2026-07-27 10:29:56', '2026-07-27 10:30:27', NULL),
	(34, 'Incident Request', NULL, 1, 0, '2026-07-27 12:05:32', '2026-07-27 12:05:32', NULL),
	(35, 'test41', 29, 1, 0, '2026-07-29 06:02:22', '2026-07-29 09:39:09', NULL),
	(36, 'Service Request', NULL, 1, 0, '2026-07-31 06:27:07', '2026-07-31 06:27:07', NULL),
	(37, 'trest', NULL, 1, 0, '2026-07-31 12:05:24', '2026-07-31 12:05:24', NULL),
	(38, 'tesdw2', 10, 1, 0, '2026-07-31 12:05:47', '2026-07-31 12:05:59', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
