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
) ENGINE=InnoDB AUTO_INCREMENT=1226 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.activity_log: ~13 rows (approximately)
DELETE FROM `activity_log`;
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
	(1221, 'default', 'created', 'App\\Models\\Complaint', 'created', 241, 'App\\Models\\User', 1, '{"attributes":{"id":241,"reference_number":"SWE-AS-20260629001","user_name":"Purshottam Kumar","client_id":1,"network_type_id":2,"section_id":4,"intercom":"120304","room_number":"304","description":"Test issue details...","file_path":null,"priority":"high","status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-06-29T08:46:58.000000Z","updated_at":"2026-06-29T08:46:58.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-06-29 08:46:58', '2026-06-29 08:46:58'),
	(1222, 'default', 'created', 'App\\Models\\Complaint', 'created', 242, 'App\\Models\\User', 1, '{"attributes":{"id":242,"reference_number":"H-20260629002","user_name":"Test User","client_id":1,"network_type_id":2,"section_id":4,"intercom":"120304","room_number":"122343","description":"weqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq","file_path":null,"priority":"medium","status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-06-29T08:48:07.000000Z","updated_at":"2026-06-29T08:48:07.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-06-29 08:48:07', '2026-06-29 08:48:07'),
	(1223, 'default', 'created', 'App\\Models\\Complaint', 'created', 243, 'App\\Models\\User', 1, '{"attributes":{"id":243,"reference_number":"SWE-DS-20260629003","user_name":"Amrita Rao","client_id":1,"network_type_id":2,"section_id":2,"intercom":"120304","room_number":"201","description":"qswqqwwq","file_path":null,"priority":"medium","status_id":2,"assigned_to":10,"assigned_by":null,"resolution":null,"created_at":"2026-06-29T09:07:17.000000Z","updated_at":"2026-06-29T09:07:17.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-06-29 09:07:17', '2026-06-29 09:07:17'),
	(1224, 'default', 'created', 'App\\Models\\Complaint', 'created', 244, 'App\\Models\\User', 1, '{"attributes":{"id":244,"reference_number":"SWE-DS-20260629004","user_name":"Amrita Rao","client_id":1,"network_type_id":2,"section_id":4,"intercom":"120304","room_number":"201","description":"gfhhhhhhhhhhhhhh","file_path":null,"priority":"medium","status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-06-29T09:12:28.000000Z","updated_at":"2026-06-29T09:12:28.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-06-29 09:12:28', '2026-06-29 09:12:28'),
	(1225, 'default', 'created', 'App\\Models\\Complaint', 'created', 245, 'App\\Models\\User', 1, '{"attributes":{"id":245,"reference_number":"SWE-DS-20260629005","user_name":"Amrita Rao","client_id":1,"network_type_id":2,"section_id":4,"intercom":"120304","room_number":"201","description":"gfhhhhhhhhhhhhhh","file_path":null,"priority":"medium","status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-06-29T09:15:05.000000Z","updated_at":"2026-06-29T09:15:05.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-06-29 09:15:05', '2026-06-29 09:15:05');

-- Dumping structure for table tms-laravel.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.cache: ~0 rows (approximately)
DELETE FROM `cache`;

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
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.comments: ~0 rows (approximately)
DELETE FROM `comments`;

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
) ENGINE=InnoDB AUTO_INCREMENT=571 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.complaint_actions: ~7 rows (approximately)
DELETE FROM `complaint_actions`;
INSERT INTO `complaint_actions` (`id`, `complaint_id`, `user_id`, `assigned_to`, `status_id`, `description`, `created_at`, `updated_at`) VALUES
	(568, 241, 1, NULL, 1, 'Complaint created', '2026-06-29 08:46:58', '2026-06-29 08:46:58'),
	(569, 242, 1, NULL, 1, 'Complaint created', '2026-06-29 08:48:07', '2026-06-29 08:48:07'),
	(570, 245, 1, NULL, 1, 'Complaint created', '2026-06-29 09:15:05', '2026-06-29 09:15:05');

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
  CONSTRAINT `complaint_vertical_vertical_id_foreign` FOREIGN KEY (`vertical_id`) REFERENCES `verticals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=219 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.complaint_vertical: ~3 rows (approximately)
DELETE FROM `complaint_vertical`;
INSERT INTO `complaint_vertical` (`id`, `complaint_id`, `vertical_id`, `created_at`, `updated_at`) VALUES
	(210, 241, 3, '2026-06-29 08:46:58', '2026-06-29 08:46:58'),
	(211, 241, 10, '2026-06-29 08:46:58', '2026-06-29 08:46:58'),
	(212, 242, 6, '2026-06-29 08:48:07', '2026-06-29 08:48:07'),
	(213, 243, 3, '2026-06-29 09:07:17', '2026-06-29 09:07:17'),
	(214, 243, 11, '2026-06-29 09:07:17', '2026-06-29 09:07:17'),
	(215, 244, 3, '2026-06-29 09:12:28', '2026-06-29 09:12:28'),
	(216, 244, 11, '2026-06-29 09:12:28', '2026-06-29 09:12:28'),
	(217, 245, 3, '2026-06-29 09:15:05', '2026-06-29 09:15:05'),
	(218, 245, 11, '2026-06-29 09:15:05', '2026-06-29 09:15:05');

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
  CONSTRAINT `complaints_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `complaints_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `complaints_network_type_id_foreign` FOREIGN KEY (`network_type_id`) REFERENCES `network_types` (`id`),
  CONSTRAINT `complaints_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  CONSTRAINT `complaints_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.complaints: ~2 rows (approximately)
DELETE FROM `complaints`;
INSERT INTO `complaints` (`id`, `reference_number`, `user_name`, `client_id`, `network_type_id`, `section_id`, `intercom`, `room_number`, `description`, `file_path`, `priority`, `status_id`, `assigned_to`, `assigned_by`, `resolution`, `created_at`, `updated_at`) VALUES
	(241, 'SWE-AS-20260629001', 'Purshottam Kumar', 1, 2, 4, '120304', '304', 'Test issue details...', NULL, 'high', 1, NULL, NULL, NULL, '2026-06-29 08:46:58', '2026-06-29 08:46:58'),
	(242, 'H-20260629002', 'Test User', 1, 2, 4, '120304', '122343', 'weqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq', NULL, 'medium', 1, NULL, NULL, NULL, '2026-06-29 08:48:07', '2026-06-29 08:48:07'),
	(243, 'SWE-DS-20260629003', 'Amrita Rao', 1, 2, 2, '120304', '201', 'qswqqwwq', NULL, 'medium', 2, 10, NULL, NULL, '2026-06-29 09:07:17', '2026-06-29 09:07:17'),
	(244, 'SWE-DS-20260629004', 'Amrita Rao', 1, 2, 4, '120304', '201', 'gfhhhhhhhhhhhhhh', NULL, 'medium', 1, NULL, NULL, NULL, '2026-06-29 09:12:28', '2026-06-29 09:12:28'),
	(245, 'SWE-DS-20260629005', 'Amrita Rao', 1, 2, 4, '120304', '201', 'gfhhhhhhhhhhhhhh', NULL, 'medium', 1, NULL, NULL, NULL, '2026-06-29 09:15:05', '2026-06-29 09:15:05');

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
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.migrations: ~38 rows (approximately)
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
	(44, '2026_06_23_122931_update_verticals_and_complaints_for_parent_id', 25);

-- Dumping structure for table tms-laravel.network_types
CREATE TABLE IF NOT EXISTS `network_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.network_types: ~2 rows (approximately)
DELETE FROM `network_types`;
INSERT INTO `network_types` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Air Gap Network', '2025-06-17 05:29:27', '2025-07-10 07:20:54', NULL),
	(2, 'Internet', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.sections: ~22 rows (approximately)
DELETE FROM `sections`;
INSERT INTO `sections` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'ACC', '2025-06-17 05:29:27', '2025-07-10 07:21:11', NULL),
	(2, 'Ad I', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(3, 'Ad II', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(4, 'CA I', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(5, 'CA II', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(6, 'CA III', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(7, 'CA IV', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(8, 'CA V', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(9, 'Cabinet Section', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
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
	(22, 'ts2', '2025-07-14 12:05:43', '2025-07-14 12:05:57', NULL);

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `statuses_name_unique` (`name`),
  UNIQUE KEY `statuses_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.statuses: ~7 rows (approximately)
DELETE FROM `statuses`;
INSERT INTO `statuses` (`id`, `name`, `slug`, `color`, `description`, `sort_order`, `created_at`, `updated_at`, `visible_to_user`) VALUES
	(1, 'unassigned', 'unassigned', 'warning', 'Complaint is waiting to be assigned', 1, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 0),
	(2, 'assigned', 'assigned', 'info', 'Complaint has been assigned to a team member', 2, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 0),
	(3, 'in_progress', 'in_progress', 'primary', 'Work on the complaint is currently in progress', 3, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 1),
	(4, 'pending_with_vendor', 'pending_with_vendor', 'danger', 'Complaint has been escalated to higher authority', 4, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 1),
	(5, 'pending_with_user', 'pending_with_user', 'danger', 'Complaint has been resolved successfully', 5, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 1),
	(6, 'closed', 'closed', 'success', 'Complaint has been closed', 7, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 0),
	(8, 'completed', 'completed', 'success', NULL, 6, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 1);

-- Dumping structure for table tms-laravel.user_vertical
CREATE TABLE IF NOT EXISTS `user_vertical` (
  `user_id` bigint unsigned NOT NULL,
  `vertical_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`vertical_id`),
  KEY `user_vertical_vertical_id_foreign` (`vertical_id`),
  CONSTRAINT `user_vertical_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_vertical_vertical_id_foreign` FOREIGN KEY (`vertical_id`) REFERENCES `verticals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.user_vertical: ~21 rows (approximately)
DELETE FROM `user_vertical`;
INSERT INTO `user_vertical` (`user_id`, `vertical_id`) VALUES
	(3, 2),
	(4, 1),
	(5, 2),
	(5, 3),
	(6, 1),
	(6, 4),
	(6, 12),
	(6, 13),
	(7, 4),
	(8, 1),
	(8, 3),
	(8, 6),
	(9, 2),
	(10, 3),
	(11, 5),
	(17, 1),
	(17, 5),
	(18, 1),
	(27, 5);

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.users: ~14 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `role_id`, `username`, `email`, `phone_number`, `full_name`, `vertical_id`, `password`, `must_change_password`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 2, 'rohit', 'rohit.kumar09@nic.in', NULL, 'Rohit Kumar', NULL, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 18:58:26', '2026-05-22 10:17:50', NULL),
	(2, 2, 'yogesh', 'yogesh.a@nic.in', NULL, 'Yogesh Kumar', NULL, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 18:59:47', '2026-05-22 10:18:26', NULL),
	(3, 3, 'rachit', 'rachit@gmail.com', NULL, 'Rachit Sharma', 1, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:00:25', '2026-06-11 12:32:00', NULL),
	(4, 4, 'manish', 'manish@nic.in', NULL, 'Manish Singh', 1, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:06:42', '2026-05-22 10:20:58', NULL),
	(5, 4, 'rajkumar', 'rajkumar@nic.in', NULL, 'Rajkumar', 2, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:07:23', '2026-06-11 09:00:05', NULL),
	(6, 4, 'sahil', 'sahil@nic.in', NULL, 'Sahil Gulia', 1, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:08:12', '2026-06-11 09:00:14', NULL),
	(7, 4, 'anil', 'anil@nic.in', NULL, 'Anil Singh', 4, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:08:51', '2026-06-11 09:00:24', NULL),
	(8, 3, 'tarun', 'tarun@nic.in', NULL, 'Tarun Kumar', 3, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:10:12', '2026-06-11 09:00:39', NULL),
	(9, 4, 'vikram', 'vikram@nic.in', NULL, 'Vikram Mahlawat', 2, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:11:17', '2026-06-11 09:00:57', NULL),
	(10, 3, 'praveen', 'praveen@nic.in', NULL, 'Praveen Bansal', 3, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:12:09', '2026-06-11 09:00:47', NULL),
	(11, 3, 'ankit', 'anikitchugh@nic.in', NULL, 'Ankit Chugh', 5, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-06-19 19:13:04', '2026-06-11 09:01:15', NULL),
	(17, 4, 'Ankits', 'ankitsharma@nic.in', NULL, 'Ankit Sharma', 1, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-07-01 06:12:47', '2026-06-11 09:01:27', NULL),
	(18, 3, 'prankur', 'prankur@nic.in', NULL, 'Prankur Sharma', 1, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2025-07-01 08:02:48', '2026-06-11 09:01:37', NULL),
	(27, 3, 'prasad', 'prasad.gl@nic.in', NULL, 'Guggilam Lakshmi Prasad', NULL, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 1, '2026-05-22 10:19:58', '2026-05-22 10:19:58', NULL),
	(28, 1, 'admin', 'admin@nic.in', NULL, 'Admin', NULL, '$2y$12$gBfu.K.azISKyLR6F4EwYOmUrXQAXwkITvIH.b1HC9R7vE09qxKNi', 0, '2026-06-11 06:40:32', '2026-06-11 06:40:35', NULL);

-- Dumping structure for table tms-laravel.verticals
CREATE TABLE IF NOT EXISTS `verticals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL COMMENT 'Null means top-level category',
  `send_email` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verticals_parent_id_foreign` (`parent_id`),
  CONSTRAINT `verticals_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `verticals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.verticals: ~22 rows (approximately)
DELETE FROM `verticals`;
INSERT INTO `verticals` (`id`, `name`, `short_form`, `parent_id`, `send_email`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Network', 'NET', NULL, 1, '2025-06-17 05:29:27', '2026-06-11 11:54:24', NULL),
	(2, 'VC', NULL, NULL, 1, '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL),
	(3, 'Software', 'SWE', NULL, 1, '2025-06-17 05:29:27', '2026-06-11 11:54:55', NULL),
	(4, 'Cyber Security', 'CS', NULL, 1, '2025-06-20 04:45:03', '2026-06-11 11:53:34', NULL),
	(5, 'Email', 'E', NULL, 0, '2025-06-20 04:41:20', '2026-06-11 12:42:20', NULL),
	(6, 'Hardware', 'H', NULL, 1, '2025-07-09 08:25:45', '2026-06-11 11:54:11', NULL),
	(9, 'Other', NULL, NULL, 0, '2026-05-21 08:51:49', '2026-06-11 12:42:29', NULL),
	(10, 'Application Security', 'AS', 3, 1, '2026-06-23 07:17:54', '2026-06-23 07:17:54', NULL),
	(11, 'Database Security', 'DS', 3, 1, '2026-06-23 07:20:49', '2026-06-23 07:20:49', NULL),
	(12, 'Information Security', 'IS', 4, 1, '2026-06-23 07:21:09', '2026-06-23 07:21:09', NULL),
	(13, 'Network Security', 'NS', 4, 1, '2026-06-23 07:21:32', '2026-06-23 07:21:32', NULL),
	(14, 'Wireless Security (Wi-Fi Security)', 'WS', 1, 1, '2026-06-23 07:21:57', '2026-06-23 07:21:57', NULL),
	(15, 'Email Security', 'ES', 5, 1, '2026-06-23 08:56:37', '2026-06-23 08:56:37', NULL),
	(16, 'Physical Security of Devices', 'PSD', 6, 1, '2026-06-23 08:56:59', '2026-06-23 08:56:59', NULL),
	(17, 'PSD2', 'PSD2', 16, 1, '2026-06-23 09:01:14', '2026-06-23 09:01:14', NULL),
	(18, 'Test', 'TT', 17, 1, '2026-06-23 10:55:48', '2026-06-23 11:25:05', '2026-06-23 11:25:05'),
	(19, 'Test2', 'TT2', 18, 1, '2026-06-23 10:57:19', '2026-06-23 11:24:58', '2026-06-23 11:24:58'),
	(20, 'Test New', 'TN', 13, 1, '2026-06-23 11:22:08', '2026-06-23 11:22:08', NULL),
	(21, 'IS2', 'IS2', 12, 1, '2026-06-25 11:30:49', '2026-06-25 11:30:49', NULL),
	(22, 'Test  Test', 'GGGGGG', NULL, 1, '2026-06-25 12:16:18', '2026-06-25 12:16:34', NULL),
	(23, 'gggg5', 'GGGG5', 22, 1, '2026-06-25 12:17:28', '2026-06-25 12:17:28', NULL),
	(24, 'gggg6', 'GGGG6', 23, 1, '2026-06-25 12:20:40', '2026-06-25 12:20:40', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
