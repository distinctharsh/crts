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
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.activity_log: ~166 rows (approximately)
DELETE FROM `activity_log`;
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
	(8, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-21 05:14:26', '2026-07-21 05:14:26'),
	(9, 'default', 'created', 'App\\Models\\Complaint', 'created', 507, NULL, NULL, '{"attributes":{"id":507,"reference_number":"SWE-AS-20260721001","user_name":"Gitesh Kumar Shrivastav","client_id":0,"network_type_id":2,"section_id":9,"intercom":"120278","room_number":"201","description":"Sundar nari jag se pyari hojaye hamari","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":null,"status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-21T05:17:40.000000Z","updated_at":"2026-07-21T05:17:40.000000Z"},"ip_address":"10.19.88.148"}', NULL, '2026-07-21 05:17:40', '2026-07-21 05:17:40'),
	(10, 'default', 'created', 'App\\Models\\Complaint', 'created', 508, NULL, NULL, '{"attributes":{"id":508,"reference_number":"CS-NS-TNS-20260721002","user_name":"Purshottam Kumar","client_id":0,"network_type_id":2,"section_id":4,"intercom":"120331","room_number":"201","description":"Network is not working","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":null,"status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-21T05:21:02.000000Z","updated_at":"2026-07-21T05:21:02.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 05:21:02', '2026-07-21 05:21:02'),
	(11, 'default', 'created', 'App\\Models\\Complaint', 'created', 509, NULL, NULL, '{"attributes":{"id":509,"reference_number":"H-PSD-PS2-20260721003","user_name":"Suhani","client_id":0,"network_type_id":2,"section_id":9,"intercom":"120456","room_number":"201","description":"Test Issue Case...","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":17,"status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-21T05:58:17.000000Z","updated_at":"2026-07-21T05:58:17.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 05:58:17', '2026-07-21 05:58:17'),
	(12, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-21 06:06:06', '2026-07-21 06:06:06'),
	(13, 'default', 'User verticals updated', 'App\\Models\\User', NULL, 11, 'App\\Models\\User', 1, '{"user_full_name":"Ankit Chugh","old_vertical_ids":[5,9],"new_vertical_ids":[5,9,17],"ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-21 06:13:56', '2026-07-21 06:13:56'),
	(14, 'default', 'User verticals updated', 'App\\Models\\User', NULL, 4, 'App\\Models\\User', 1, '{"user_full_name":"Manish Singh","old_vertical_ids":[11],"new_vertical_ids":[11,16],"ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-21 06:14:06', '2026-07-21 06:14:06'),
	(15, 'default', 'created', 'App\\Models\\Complaint', 'created', 510, 'App\\Models\\User', 1, '{"attributes":{"id":510,"reference_number":"AS-20260721004","user_name":"Harsh Singh","client_id":1,"network_type_id":2,"section_id":1,"intercom":"1234","room_number":"101","description":"Sample complaint description","file_path":null,"priority":"high","request_type_id":2,"vertical_id":10,"status_id":2,"assigned_to":30,"assigned_by":1,"resolution":null,"created_at":"2026-07-21T06:52:39.000000Z","updated_at":"2026-07-21T06:52:39.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 06:52:39', '2026-07-21 06:52:39'),
	(16, 'default', 'created', 'App\\Models\\Complaint', 'created', 511, 'App\\Models\\User', 1, '{"attributes":{"id":511,"reference_number":"SWE-AS-20260721005","user_name":"Harsh Singh","client_id":1,"network_type_id":2,"section_id":1,"intercom":"1234","room_number":"101","description":"Sample complaint description","file_path":null,"priority":"high","request_type_id":2,"vertical_id":10,"status_id":2,"assigned_to":30,"assigned_by":1,"resolution":null,"created_at":"2026-07-21T06:58:24.000000Z","updated_at":"2026-07-21T06:58:24.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 06:58:24', '2026-07-21 06:58:24'),
	(17, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 511, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":10,"updated_at":"2026-07-21T06:59:23.000000Z"},"old":{"assigned_to":30,"updated_at":"2026-07-21T06:58:24.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 06:59:23', '2026-07-21 06:59:23'),
	(18, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 510, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":11,"assigned_to":10,"updated_at":"2026-07-21T09:26:22.000000Z"},"old":{"vertical_id":10,"assigned_to":30,"updated_at":"2026-07-21T06:52:39.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 09:26:22', '2026-07-21 09:26:22'),
	(19, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 510, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":10,"assigned_to":30,"updated_at":"2026-07-21T09:27:22.000000Z"},"old":{"vertical_id":11,"assigned_to":10,"updated_at":"2026-07-21T09:26:22.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 09:27:22', '2026-07-21 09:27:22'),
	(20, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 511, 'App\\Models\\User', 1, '{"attributes":{"section_id":2,"vertical_id":25,"assigned_to":7,"updated_at":"2026-07-21T09:32:40.000000Z"},"old":{"section_id":1,"vertical_id":10,"assigned_to":10,"updated_at":"2026-07-21T06:59:23.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 09:32:41', '2026-07-21 09:32:41'),
	(21, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 30, '{"user_full_name":"Harsh Singh","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-21 09:33:25', '2026-07-21 09:33:25'),
	(22, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 510, 'App\\Models\\User', 30, '{"attributes":{"assigned_to":8,"assigned_by":30,"updated_at":"2026-07-21T09:33:31.000000Z"},"old":{"assigned_to":30,"assigned_by":1,"updated_at":"2026-07-21T09:27:22.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 09:33:31', '2026-07-21 09:33:31'),
	(23, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 30, '{"user_full_name":"Harsh Singh","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-21 09:33:41', '2026-07-21 09:33:41'),
	(24, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-21 09:33:48', '2026-07-21 09:33:48'),
	(25, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 510, 'App\\Models\\User', 8, '{"attributes":{"assigned_to":1,"assigned_by":8,"updated_at":"2026-07-21T09:42:13.000000Z"},"old":{"assigned_to":8,"assigned_by":30,"updated_at":"2026-07-21T09:33:31.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 09:42:13', '2026-07-21 09:42:13'),
	(26, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 510, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":8,"assigned_by":1,"updated_at":"2026-07-21T09:42:40.000000Z"},"old":{"assigned_to":1,"assigned_by":8,"updated_at":"2026-07-21T09:42:13.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 09:42:40', '2026-07-21 09:42:40'),
	(27, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 510, 'App\\Models\\User', 8, '{"attributes":{"status_id":8,"updated_at":"2026-07-21T09:52:03.000000Z"},"old":{"status_id":2,"updated_at":"2026-07-21T09:42:40.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 09:52:03', '2026-07-21 09:52:03'),
	(28, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 509, 'App\\Models\\User', 1, '{"attributes":{"status_id":2,"assigned_to":8,"assigned_by":1,"updated_at":"2026-07-21T09:54:41.000000Z"},"old":{"status_id":1,"assigned_to":null,"assigned_by":null,"updated_at":"2026-07-21T05:58:17.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 09:54:41', '2026-07-21 09:54:41'),
	(29, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 510, 'App\\Models\\User', 1, '{"attributes":{"status_id":6,"updated_at":"2026-07-21T09:59:20.000000Z"},"old":{"status_id":8,"updated_at":"2026-07-21T09:52:03.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 09:59:20', '2026-07-21 09:59:20'),
	(30, 'default', 'created', 'App\\Models\\Complaint', 'created', 512, 'App\\Models\\User', 1, '{"attributes":{"id":512,"reference_number":"CS-ISS-IS2-20260721004","user_name":"Test User","client_id":1,"network_type_id":2,"section_id":4,"intercom":"1234","room_number":"201","description":"test this","file_path":null,"priority":"medium","request_type_id":3,"vertical_id":21,"status_id":2,"assigned_to":6,"assigned_by":null,"resolution":null,"created_at":"2026-07-21T10:08:19.000000Z","updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 10:08:19', '2026-07-21 10:08:19'),
	(31, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 509, 'App\\Models\\User', 8, '{"attributes":{"assigned_to":1,"assigned_by":8,"updated_at":"2026-07-21T11:55:14.000000Z"},"old":{"assigned_to":8,"assigned_by":1,"updated_at":"2026-07-21T09:54:41.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 11:55:14', '2026-07-21 11:55:14'),
	(32, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 512, 'App\\Models\\User', 1, '{"attributes":{"assigned_by":1,"updated_at":"2026-07-21T12:17:35.000000Z"},"old":{"assigned_by":null,"updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:17:35', '2026-07-21 12:17:35'),
	(33, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 514, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":11,"assigned_by":1,"updated_at":"2026-07-21T12:20:13.000000Z"},"old":{"assigned_to":6,"assigned_by":null,"updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:20:13', '2026-07-21 12:20:13'),
	(34, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 517, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":17,"assigned_by":1,"updated_at":"2026-07-21T12:20:18.000000Z"},"old":{"assigned_to":6,"assigned_by":null,"updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:20:18', '2026-07-21 12:20:18'),
	(35, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 519, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":10,"assigned_by":1,"updated_at":"2026-07-21T12:20:21.000000Z"},"old":{"assigned_to":6,"assigned_by":null,"updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:20:21', '2026-07-21 12:20:21'),
	(36, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 520, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":7,"assigned_by":1,"updated_at":"2026-07-21T12:20:26.000000Z"},"old":{"assigned_to":6,"assigned_by":null,"updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:20:26', '2026-07-21 12:20:26'),
	(37, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 521, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":14,"assigned_to":18,"assigned_by":1,"updated_at":"2026-07-21T12:20:59.000000Z"},"old":{"vertical_id":26,"assigned_to":6,"assigned_by":null,"updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:20:59', '2026-07-21 12:20:59'),
	(38, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 512, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":17,"assigned_to":8,"updated_at":"2026-07-21T12:21:19.000000Z"},"old":{"vertical_id":21,"assigned_to":6,"updated_at":"2026-07-21T12:17:35.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:21:19', '2026-07-21 12:21:19'),
	(39, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 513, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":9,"assigned_by":1,"updated_at":"2026-07-21T12:21:27.000000Z"},"old":{"assigned_to":6,"assigned_by":null,"updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:21:27', '2026-07-21 12:21:27'),
	(40, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 524, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":27,"assigned_by":1,"updated_at":"2026-07-21T12:21:43.000000Z"},"old":{"assigned_to":6,"assigned_by":null,"updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:21:43', '2026-07-21 12:21:43'),
	(41, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 525, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":3,"assigned_by":1,"updated_at":"2026-07-21T12:21:51.000000Z"},"old":{"assigned_to":6,"assigned_by":null,"updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:21:51', '2026-07-21 12:21:51'),
	(42, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 515, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":5,"assigned_by":1,"updated_at":"2026-07-21T12:22:04.000000Z"},"old":{"assigned_to":6,"assigned_by":null,"updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:22:04', '2026-07-21 12:22:04'),
	(43, 'default', 'User verticals updated', 'App\\Models\\User', NULL, 3, 'App\\Models\\User', 1, '{"user_full_name":"Rachit Sharma","old_vertical_ids":[2,9],"new_vertical_ids":[2,9,17],"ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-21 12:23:57', '2026-07-21 12:23:57'),
	(44, 'default', 'updated', 'App\\Models\\Status', 'updated', 13, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-21T12:24:16.000000Z","deleted_at":null},"old":{"updated_at":"2026-07-13T12:10:18.000000Z","deleted_at":"2026-07-13T12:10:18.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:24:16', '2026-07-21 12:24:16'),
	(45, 'default', 'restored', 'App\\Models\\Status', 'restored', 13, 'App\\Models\\User', 1, '{"attributes":{"id":13,"name":"test","slug":"test","color":"info","description":null,"sort_order":0,"created_at":"2026-07-10T05:58:21.000000Z","updated_at":"2026-07-21T12:24:16.000000Z","visible_to_user":1,"deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:24:16', '2026-07-21 12:24:16'),
	(46, 'default', 'deleted', 'App\\Models\\Status', 'deleted', 13, 'App\\Models\\User', 1, '{"old":{"id":13,"name":"test","slug":"test","color":"info","description":null,"sort_order":0,"created_at":"2026-07-10T05:58:21.000000Z","updated_at":"2026-07-21T12:24:22.000000Z","visible_to_user":1,"deleted_at":"2026-07-21T12:24:22.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-21 12:24:22', '2026-07-21 12:24:22'),
	(47, 'default', 'User verticals updated', 'App\\Models\\User', NULL, 30, 'App\\Models\\User', 1, '{"user_full_name":"Harsh Singh","old_vertical_ids":[10,28],"new_vertical_ids":[3,10,28],"ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-21 12:32:17', '2026-07-21 12:32:17'),
	(48, 'default', 'User verticals updated', 'App\\Models\\User', NULL, 30, 'App\\Models\\User', 1, '{"user_full_name":"Harsh Singh","old_vertical_ids":[3,10,28],"new_vertical_ids":[10,28],"ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-21 12:32:35', '2026-07-21 12:32:35'),
	(49, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-22 04:47:02', '2026-07-22 04:47:02'),
	(50, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-22 05:55:23', '2026-07-22 05:55:23'),
	(51, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-22 05:55:27', '2026-07-22 05:55:27'),
	(52, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-22 06:20:33', '2026-07-22 06:20:33'),
	(53, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"10.19.88.148","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-22 06:29:22', '2026-07-22 06:29:22'),
	(54, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"10.19.88.148","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-22 06:35:19', '2026-07-22 06:35:19'),
	(55, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"10.19.88.148","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-22 06:35:24', '2026-07-22 06:35:24'),
	(56, 'default', 'created', 'App\\Models\\Status', 'created', 14, 'App\\Models\\User', 1, '{"attributes":{"id":14,"name":"test2","slug":"test2","color":"primary","description":null,"sort_order":0,"created_at":"2026-07-22T09:37:42.000000Z","updated_at":"2026-07-22T09:37:42.000000Z","visible_to_user":1,"deleted_at":null},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:37:42', '2026-07-22 09:37:42'),
	(57, 'default', 'updated', 'App\\Models\\Status', 'updated', 14, 'App\\Models\\User', 1, '{"attributes":{"name":"test21","slug":"test21","updated_at":"2026-07-22T09:41:46.000000Z"},"old":{"name":"test2","slug":"test2","updated_at":"2026-07-22T09:37:42.000000Z"},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:41:47', '2026-07-22 09:41:47'),
	(58, 'default', 'deleted', 'App\\Models\\Status', 'deleted', 14, 'App\\Models\\User', 1, '{"old":{"id":14,"name":"test21","slug":"test21","color":"primary","description":null,"sort_order":0,"created_at":"2026-07-22T09:37:42.000000Z","updated_at":"2026-07-22T09:41:54.000000Z","visible_to_user":1,"deleted_at":"2026-07-22T09:41:54.000000Z"},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:41:54', '2026-07-22 09:41:54'),
	(59, 'default', 'updated', 'App\\Models\\Status', 'updated', 14, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-22T09:41:59.000000Z","deleted_at":null},"old":{"updated_at":"2026-07-22T09:41:54.000000Z","deleted_at":"2026-07-22T09:41:54.000000Z"},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:41:59', '2026-07-22 09:41:59'),
	(60, 'default', 'restored', 'App\\Models\\Status', 'restored', 14, 'App\\Models\\User', 1, '{"attributes":{"id":14,"name":"test21","slug":"test21","color":"primary","description":null,"sort_order":0,"created_at":"2026-07-22T09:37:42.000000Z","updated_at":"2026-07-22T09:41:59.000000Z","visible_to_user":1,"deleted_at":null},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:41:59', '2026-07-22 09:41:59'),
	(61, 'default', 'deleted', 'App\\Models\\Status', 'deleted', 14, 'App\\Models\\User', 1, '{"old":{"id":14,"name":"test21","slug":"test21","color":"primary","description":null,"sort_order":0,"created_at":"2026-07-22T09:37:42.000000Z","updated_at":"2026-07-22T09:42:04.000000Z","visible_to_user":1,"deleted_at":"2026-07-22T09:42:04.000000Z"},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:42:04', '2026-07-22 09:42:04'),
	(62, 'default', 'updated', 'App\\Models\\Status', 'updated', 14, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-22T09:42:08.000000Z","deleted_at":null},"old":{"updated_at":"2026-07-22T09:42:04.000000Z","deleted_at":"2026-07-22T09:42:04.000000Z"},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:42:08', '2026-07-22 09:42:08'),
	(63, 'default', 'restored', 'App\\Models\\Status', 'restored', 14, 'App\\Models\\User', 1, '{"attributes":{"id":14,"name":"test21","slug":"test21","color":"primary","description":null,"sort_order":0,"created_at":"2026-07-22T09:37:42.000000Z","updated_at":"2026-07-22T09:42:08.000000Z","visible_to_user":1,"deleted_at":null},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:42:08', '2026-07-22 09:42:08'),
	(64, 'default', 'updated', 'App\\Models\\Status', 'updated', 14, 'App\\Models\\User', 1, '{"attributes":{"name":"test212","slug":"test212","updated_at":"2026-07-22T09:42:12.000000Z"},"old":{"name":"test21","slug":"test21","updated_at":"2026-07-22T09:42:08.000000Z"},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:42:12', '2026-07-22 09:42:12'),
	(65, 'default', 'deleted', 'App\\Models\\Status', 'deleted', 14, 'App\\Models\\User', 1, '{"old":{"id":14,"name":"test212","slug":"test212","color":"primary","description":null,"sort_order":0,"created_at":"2026-07-22T09:37:42.000000Z","updated_at":"2026-07-22T09:42:20.000000Z","visible_to_user":1,"deleted_at":"2026-07-22T09:42:20.000000Z"},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:42:20', '2026-07-22 09:42:20'),
	(66, 'default', 'created', 'App\\Models\\Status', 'created', 15, 'App\\Models\\User', 1, '{"attributes":{"id":15,"name":"test3","slug":"test3","color":"primary","description":null,"sort_order":0,"created_at":"2026-07-22T09:42:27.000000Z","updated_at":"2026-07-22T09:42:27.000000Z","visible_to_user":1,"deleted_at":null},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:42:27', '2026-07-22 09:42:27'),
	(67, 'default', 'created', 'App\\Models\\Status', 'created', 16, 'App\\Models\\User', 1, '{"attributes":{"id":16,"name":"test4","slug":"test4","color":"primary","description":null,"sort_order":0,"created_at":"2026-07-22T09:46:12.000000Z","updated_at":"2026-07-22T09:46:12.000000Z","visible_to_user":1,"deleted_at":null},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:46:12', '2026-07-22 09:46:12'),
	(68, 'default', 'deleted', 'App\\Models\\Status', 'deleted', 16, 'App\\Models\\User', 1, '{"old":{"id":16,"name":"test4","slug":"test4","color":"primary","description":null,"sort_order":0,"created_at":"2026-07-22T09:46:12.000000Z","updated_at":"2026-07-22T09:46:16.000000Z","visible_to_user":1,"deleted_at":"2026-07-22T09:46:16.000000Z"},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:46:16', '2026-07-22 09:46:16'),
	(69, 'default', 'deleted', 'App\\Models\\Status', 'deleted', 15, 'App\\Models\\User', 1, '{"old":{"id":15,"name":"test3","slug":"test3","color":"primary","description":null,"sort_order":0,"created_at":"2026-07-22T09:42:27.000000Z","updated_at":"2026-07-22T09:46:19.000000Z","visible_to_user":1,"deleted_at":"2026-07-22T09:46:19.000000Z"},"ip_address":"10.19.88.148"}', NULL, '2026-07-22 09:46:19', '2026-07-22 09:46:19'),
	(70, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 04:52:02', '2026-07-23 04:52:02'),
	(71, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 512, 'App\\Models\\User', 1, '{"attributes":{"request_type_id":1,"status_id":8,"updated_at":"2026-07-23T05:53:42.000000Z"},"old":{"request_type_id":null,"status_id":2,"updated_at":"2026-07-21T12:21:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 05:53:42', '2026-07-23 05:53:42'),
	(72, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 512, 'App\\Models\\User', 1, '{"attributes":{"status_id":2,"updated_at":"2026-07-23T05:53:51.000000Z"},"old":{"status_id":8,"updated_at":"2026-07-23T05:53:42.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 05:53:51', '2026-07-23 05:53:51'),
	(73, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 512, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":15,"status_id":8,"assigned_to":17,"updated_at":"2026-07-23T05:54:25.000000Z"},"old":{"vertical_id":17,"status_id":2,"assigned_to":8,"updated_at":"2026-07-23T05:53:51.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 05:54:25', '2026-07-23 05:54:25'),
	(74, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 05:59:24', '2026-07-23 05:59:24'),
	(75, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 06:09:15', '2026-07-23 06:09:15'),
	(76, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 06:09:37', '2026-07-23 06:09:37'),
	(77, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 06:13:30', '2026-07-23 06:13:30'),
	(78, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 06:19:37', '2026-07-23 06:19:37'),
	(79, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 06:19:45', '2026-07-23 06:19:45'),
	(80, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 06:20:35', '2026-07-23 06:20:35'),
	(81, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 515, 'App\\Models\\User', 8, '{"attributes":{"assigned_to":8,"assigned_by":8,"updated_at":"2026-07-23T06:58:04.000000Z"},"old":{"assigned_to":5,"assigned_by":1,"updated_at":"2026-07-21T12:22:04.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 06:58:04', '2026-07-23 06:58:04'),
	(82, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 515, 'App\\Models\\User', 8, '{"attributes":{"status_id":8,"updated_at":"2026-07-23T07:19:21.000000Z"},"old":{"status_id":2,"updated_at":"2026-07-23T06:58:04.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 07:19:21', '2026-07-23 07:19:21'),
	(83, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 515, 'App\\Models\\User', 1, '{"attributes":{"status_id":6,"updated_at":"2026-07-23T07:24:09.000000Z"},"old":{"status_id":8,"updated_at":"2026-07-23T07:19:21.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 07:24:09', '2026-07-23 07:24:09'),
	(84, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 518, 'App\\Models\\User', 8, '{"attributes":{"assigned_to":8,"assigned_by":8,"updated_at":"2026-07-23T08:39:43.000000Z"},"old":{"assigned_to":6,"assigned_by":null,"updated_at":"2026-07-21T10:08:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 08:39:43', '2026-07-23 08:39:43'),
	(85, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 518, 'App\\Models\\User', 8, '{"attributes":{"status_id":8,"updated_at":"2026-07-23T08:42:27.000000Z"},"old":{"status_id":2,"updated_at":"2026-07-23T08:39:43.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 08:42:27', '2026-07-23 08:42:27'),
	(86, 'default', 'created', 'App\\Models\\Complaint', 'created', 526, 'App\\Models\\User', 8, '{"attributes":{"id":526,"reference_number":"CS-ISS-20260723003","user_name":"Ankita Singh","client_id":8,"network_type_id":1,"section_id":4,"intercom":"120456","room_number":"201","description":"Test issue generated...","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":25,"status_id":2,"assigned_to":6,"assigned_by":null,"resolution":null,"created_at":"2026-07-23T08:55:23.000000Z","updated_at":"2026-07-23T08:55:23.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 08:55:23', '2026-07-23 08:55:23'),
	(87, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 08:55:57', '2026-07-23 08:55:57'),
	(88, 'default', 'created', 'App\\Models\\Complaint', 'created', 527, NULL, NULL, '{"attributes":{"id":527,"reference_number":"CS-NS-20260723004","user_name":"Karishma Kumari","client_id":0,"network_type_id":1,"section_id":3,"intercom":"120456","room_number":"301","description":"Test issue generated...","file_path":null,"priority":"medium","request_type_id":1,"vertical_id":13,"status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-23T08:56:34.000000Z","updated_at":"2026-07-23T08:56:34.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 08:56:34', '2026-07-23 08:56:34'),
	(89, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 08:56:53', '2026-07-23 08:56:53'),
	(90, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 08:58:39', '2026-07-23 08:58:39'),
	(91, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 08:58:56', '2026-07-23 08:58:56'),
	(92, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 09:04:38', '2026-07-23 09:04:38'),
	(93, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 30, '{"user_full_name":"Harsh Singh","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 09:04:46', '2026-07-23 09:04:46'),
	(94, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 30, '{"user_full_name":"Harsh Singh","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 09:04:57', '2026-07-23 09:04:57'),
	(95, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 09:05:05', '2026-07-23 09:05:05'),
	(96, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 09:07:20', '2026-07-23 09:07:20'),
	(97, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 6, '{"user_full_name":"Sahil Gulia","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 09:07:27', '2026-07-23 09:07:27'),
	(98, 'default', 'created', 'App\\Models\\Vertical', 'created', 29, 'App\\Models\\User', 1, '{"attributes":{"id":29,"name":"test4","short_form":null,"parent_id":25,"send_email":1,"created_at":"2026-07-23T09:23:25.000000Z","updated_at":"2026-07-23T09:23:25.000000Z","deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 09:23:25', '2026-07-23 09:23:25'),
	(99, 'default', 'created', 'App\\Models\\Complaint', 'created', 528, 'App\\Models\\User', 1, '{"attributes":{"id":528,"reference_number":"CS-ISS-IS2-20260723005","user_name":"Test User","client_id":1,"network_type_id":1,"section_id":16,"intercom":"120456","room_number":"201","description":"Test this issue","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":21,"status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-23T09:25:07.000000Z","updated_at":"2026-07-23T09:25:07.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 09:25:07', '2026-07-23 09:25:07'),
	(100, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 517, 'App\\Models\\User', 1, '{"attributes":{"request_type_id":2,"vertical_id":3,"assigned_to":8,"updated_at":"2026-07-23T11:29:27.000000Z"},"old":{"request_type_id":null,"vertical_id":14,"assigned_to":17,"updated_at":"2026-07-21T12:20:18.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:29:27', '2026-07-23 11:29:27'),
	(101, 'default', 'created', 'App\\Models\\Complaint', 'created', 529, 'App\\Models\\User', 1, '{"attributes":{"id":529,"reference_number":"SWE-20260723006","user_name":"Test User","client_id":1,"network_type_id":2,"section_id":2,"intercom":"120456","room_number":"201","description":"Test issue","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":3,"status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-23T11:38:59.000000Z","updated_at":"2026-07-23T11:38:59.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:38:59', '2026-07-23 11:38:59'),
	(102, 'default', 'created', 'App\\Models\\Complaint', 'created', 530, 'App\\Models\\User', 1, '{"attributes":{"id":530,"reference_number":"SWE-20260723007","user_name":"Test User","client_id":1,"network_type_id":2,"section_id":2,"intercom":"120456","room_number":"201","description":"Test issue","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":3,"status_id":2,"assigned_to":8,"assigned_by":null,"resolution":null,"created_at":"2026-07-23T11:39:58.000000Z","updated_at":"2026-07-23T11:39:58.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:39:58', '2026-07-23 11:39:58'),
	(103, 'default', 'created', 'App\\Models\\Complaint', 'created', 531, 'App\\Models\\User', 1, '{"attributes":{"id":531,"reference_number":"SWE-20260723008","user_name":"Test User","client_id":1,"network_type_id":1,"section_id":5,"intercom":"120456","room_number":"201","description":"Test issue","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":3,"status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-23T11:41:25.000000Z","updated_at":"2026-07-23T11:41:25.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:41:25', '2026-07-23 11:41:25'),
	(104, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 531, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":10,"updated_at":"2026-07-23T11:42:34.000000Z"},"old":{"vertical_id":3,"updated_at":"2026-07-23T11:41:25.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:42:34', '2026-07-23 11:42:34'),
	(105, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 531, 'App\\Models\\User', 1, '{"attributes":{"status_id":2,"updated_at":"2026-07-23T11:42:46.000000Z"},"old":{"status_id":1,"updated_at":"2026-07-23T11:42:34.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:42:46', '2026-07-23 11:42:46'),
	(106, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 531, 'App\\Models\\User', 1, '{"attributes":{"status_id":1,"updated_at":"2026-07-23T11:48:59.000000Z"},"old":{"status_id":2,"updated_at":"2026-07-23T11:42:46.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:48:59', '2026-07-23 11:48:59'),
	(107, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 531, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":30,"assigned_by":1,"updated_at":"2026-07-23T11:49:07.000000Z"},"old":{"assigned_to":null,"assigned_by":null,"updated_at":"2026-07-23T11:48:59.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:49:07', '2026-07-23 11:49:07'),
	(108, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 531, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":2,"status_id":8,"assigned_to":5,"updated_at":"2026-07-23T11:51:22.000000Z"},"old":{"vertical_id":10,"status_id":1,"assigned_to":30,"updated_at":"2026-07-23T11:49:07.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:51:22', '2026-07-23 11:51:22'),
	(109, 'default', 'created', 'App\\Models\\Complaint', 'created', 532, 'App\\Models\\User', 1, '{"attributes":{"id":532,"reference_number":"SWE-20260723009","user_name":"Prabhanshu","client_id":1,"network_type_id":2,"section_id":6,"intercom":"120456","room_number":"201","description":"Test issue..","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":3,"status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-23T11:51:58.000000Z","updated_at":"2026-07-23T11:51:58.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:51:58', '2026-07-23 11:51:58'),
	(110, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 532, 'App\\Models\\User', 1, '{"attributes":{"status_id":3,"updated_at":"2026-07-23T11:52:20.000000Z"},"old":{"status_id":1,"updated_at":"2026-07-23T11:51:58.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:52:20', '2026-07-23 11:52:20'),
	(111, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 532, 'App\\Models\\User', 1, '{"attributes":{"status_id":2,"assigned_to":8,"assigned_by":1,"updated_at":"2026-07-23T11:52:32.000000Z"},"old":{"status_id":3,"assigned_to":null,"assigned_by":null,"updated_at":"2026-07-23T11:52:20.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 11:52:32', '2026-07-23 11:52:32'),
	(112, 'default', 'created', 'App\\Models\\Complaint', 'created', 533, 'App\\Models\\User', 1, '{"attributes":{"id":533,"reference_number":"NET-WS-20260723010","user_name":"Amrita Rao","client_id":1,"network_type_id":1,"section_id":4,"intercom":"120456","room_number":"201","description":"Test issue craeted","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":14,"status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-23T12:09:03.000000Z","updated_at":"2026-07-23T12:09:03.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 12:09:04', '2026-07-23 12:09:04'),
	(113, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 12:09:06', '2026-07-23 12:09:06'),
	(114, 'default', 'created', 'App\\Models\\Complaint', 'created', 534, NULL, NULL, '{"attributes":{"id":534,"reference_number":"VC-20260723011","user_name":"Purshottam Kumar","client_id":0,"network_type_id":2,"section_id":5,"intercom":"120456","room_number":"321","description":"Test issue created","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":2,"status_id":1,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-23T12:09:25.000000Z","updated_at":"2026-07-23T12:09:25.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 12:09:25', '2026-07-23 12:09:25'),
	(115, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-23 12:09:39', '2026-07-23 12:09:39'),
	(116, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 534, 'App\\Models\\User', 1, '{"attributes":{"status_id":2,"assigned_to":3,"assigned_by":1,"updated_at":"2026-07-23T12:09:57.000000Z"},"old":{"status_id":1,"assigned_to":null,"assigned_by":null,"updated_at":"2026-07-23T12:09:25.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 12:09:57', '2026-07-23 12:09:57'),
	(117, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 533, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":3,"status_id":2,"assigned_to":8,"assigned_by":1,"updated_at":"2026-07-23T12:10:12.000000Z"},"old":{"vertical_id":14,"status_id":1,"assigned_to":null,"assigned_by":null,"updated_at":"2026-07-23T12:09:03.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 12:10:12', '2026-07-23 12:10:12'),
	(118, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 533, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":10,"updated_at":"2026-07-23T12:10:29.000000Z"},"old":{"assigned_to":8,"updated_at":"2026-07-23T12:10:12.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 12:10:29', '2026-07-23 12:10:29'),
	(119, 'default', 'created', 'App\\Models\\Complaint', 'created', 535, 'App\\Models\\User', 1, '{"attributes":{"id":535,"reference_number":"CS-ISS-IS2-20260723012","user_name":"Harsh Singh","client_id":1,"network_type_id":1,"section_id":1,"intercom":"1234","room_number":"101","description":"Sample complaint description","file_path":null,"priority":"medium","request_type_id":1,"vertical_id":21,"status_id":2,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-23T12:34:10.000000Z","updated_at":"2026-07-23T12:34:10.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 12:34:10', '2026-07-23 12:34:10'),
	(120, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 531, 'App\\Models\\User', 1, '{"attributes":{"status_id":6,"updated_at":"2026-07-23T12:35:53.000000Z"},"old":{"status_id":8,"updated_at":"2026-07-23T11:51:22.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 12:35:53', '2026-07-23 12:35:53'),
	(121, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 530, 'App\\Models\\User', 1, '{"attributes":{"assigned_by":1,"updated_at":"2026-07-23T12:37:40.000000Z"},"old":{"assigned_by":null,"updated_at":"2026-07-23T11:39:58.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-23 12:37:40', '2026-07-23 12:37:40'),
	(122, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 06:17:03', '2026-07-24 06:17:03'),
	(123, 'default', 'deleted', 'App\\Models\\Vertical', 'deleted', 21, 'App\\Models\\User', 1, '{"old":{"id":21,"name":"IS2","short_form":"IS2","parent_id":12,"send_email":0,"created_at":"2026-06-25T11:30:49.000000Z","updated_at":"2026-07-24T06:33:54.000000Z","deleted_at":"2026-07-24T06:33:54.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:33:54', '2026-07-24 06:33:54'),
	(124, 'default', 'deleted', 'App\\Models\\Vertical', 'deleted', 29, 'App\\Models\\User', 1, '{"old":{"id":29,"name":"test4","short_form":null,"parent_id":25,"send_email":1,"created_at":"2026-07-23T09:23:25.000000Z","updated_at":"2026-07-24T06:33:54.000000Z","deleted_at":"2026-07-24T06:33:54.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:33:54', '2026-07-24 06:33:54'),
	(125, 'default', 'deleted', 'App\\Models\\Vertical', 'deleted', 25, 'App\\Models\\User', 1, '{"old":{"id":25,"name":"Iss334","short_form":null,"parent_id":12,"send_email":1,"created_at":"2026-07-10T06:38:24.000000Z","updated_at":"2026-07-24T06:33:54.000000Z","deleted_at":"2026-07-24T06:33:54.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:33:54', '2026-07-24 06:33:54'),
	(126, 'default', 'deleted', 'App\\Models\\Vertical', 'deleted', 12, 'App\\Models\\User', 1, '{"old":{"id":12,"name":"Information Security","short_form":"ISS","parent_id":4,"send_email":1,"created_at":"2026-06-23T07:21:09.000000Z","updated_at":"2026-07-24T06:33:54.000000Z","deleted_at":"2026-07-24T06:33:54.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:33:54', '2026-07-24 06:33:54'),
	(127, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 12, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-24T06:34:22.000000Z","deleted_at":null},"old":{"updated_at":"2026-07-24T06:33:54.000000Z","deleted_at":"2026-07-24T06:33:54.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:34:22', '2026-07-24 06:34:22'),
	(128, 'default', 'restored', 'App\\Models\\Vertical', 'restored', 12, 'App\\Models\\User', 1, '{"attributes":{"id":12,"name":"Information Security","short_form":"ISS","parent_id":4,"send_email":1,"created_at":"2026-06-23T07:21:09.000000Z","updated_at":"2026-07-24T06:34:22.000000Z","deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:34:22', '2026-07-24 06:34:22'),
	(129, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 21, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-24T06:34:22.000000Z","deleted_at":null},"old":{"updated_at":"2026-07-24T06:33:54.000000Z","deleted_at":"2026-07-24T06:33:54.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:34:22', '2026-07-24 06:34:22'),
	(130, 'default', 'restored', 'App\\Models\\Vertical', 'restored', 21, 'App\\Models\\User', 1, '{"attributes":{"id":21,"name":"IS2","short_form":"IS2","parent_id":12,"send_email":0,"created_at":"2026-06-25T11:30:49.000000Z","updated_at":"2026-07-24T06:34:22.000000Z","deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:34:22', '2026-07-24 06:34:22'),
	(131, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 25, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-24T06:34:22.000000Z","deleted_at":null},"old":{"updated_at":"2026-07-24T06:33:54.000000Z","deleted_at":"2026-07-24T06:33:54.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:34:22', '2026-07-24 06:34:22'),
	(132, 'default', 'restored', 'App\\Models\\Vertical', 'restored', 25, 'App\\Models\\User', 1, '{"attributes":{"id":25,"name":"Iss334","short_form":null,"parent_id":12,"send_email":1,"created_at":"2026-07-10T06:38:24.000000Z","updated_at":"2026-07-24T06:34:22.000000Z","deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:34:22', '2026-07-24 06:34:22'),
	(133, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 29, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-24T06:34:22.000000Z","deleted_at":null},"old":{"updated_at":"2026-07-24T06:33:54.000000Z","deleted_at":"2026-07-24T06:33:54.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:34:22', '2026-07-24 06:34:22'),
	(134, 'default', 'restored', 'App\\Models\\Vertical', 'restored', 29, 'App\\Models\\User', 1, '{"attributes":{"id":29,"name":"test4","short_form":null,"parent_id":25,"send_email":1,"created_at":"2026-07-23T09:23:25.000000Z","updated_at":"2026-07-24T06:34:22.000000Z","deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 06:34:22', '2026-07-24 06:34:22'),
	(135, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 6, '{"user_full_name":"Sahil Gulia","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 06:59:02', '2026-07-24 06:59:02'),
	(136, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 6, '{"user_full_name":"Sahil Gulia","ip_address":"10.19.88.148","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 07:01:18', '2026-07-24 07:01:18'),
	(137, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 6, '{"user_full_name":"Sahil Gulia","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 07:02:52', '2026-07-24 07:02:52'),
	(138, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 07:02:59', '2026-07-24 07:02:59'),
	(139, 'default', 'User verticals updated', 'App\\Models\\User', NULL, 6, 'App\\Models\\User', 1, '{"user_full_name":"Sahil Gulia","old_vertical_ids":[1,4,9,12,13],"new_vertical_ids":[4,9,12,13,14],"ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 07:08:04', '2026-07-24 07:08:04'),
	(140, 'default', 'created', 'App\\Models\\Complaint', 'created', 536, 'App\\Models\\User', 1, '{"attributes":{"id":536,"reference_number":"NET-WS-20260724001","user_name":"Gitesh Kumar Srivastav","client_id":1,"network_type_id":2,"section_id":4,"intercom":"120456","room_number":"201","description":"Test issue...","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":14,"status_id":2,"assigned_to":17,"assigned_by":null,"resolution":null,"created_at":"2026-07-24T07:23:54.000000Z","updated_at":"2026-07-24T07:23:54.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 07:23:54', '2026-07-24 07:23:54'),
	(141, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 536, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":6,"assigned_by":1,"updated_at":"2026-07-24T07:24:08.000000Z"},"old":{"assigned_to":17,"assigned_by":null,"updated_at":"2026-07-24T07:23:54.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 07:24:08', '2026-07-24 07:24:08'),
	(142, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 536, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":1,"assigned_to":1,"updated_at":"2026-07-24T07:24:19.000000Z"},"old":{"vertical_id":14,"assigned_to":6,"updated_at":"2026-07-24T07:24:08.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 07:24:19', '2026-07-24 07:24:19'),
	(143, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 536, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":14,"updated_at":"2026-07-24T07:24:28.000000Z"},"old":{"vertical_id":1,"updated_at":"2026-07-24T07:24:19.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 07:24:28', '2026-07-24 07:24:28'),
	(144, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 536, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":6,"updated_at":"2026-07-24T07:24:33.000000Z"},"old":{"assigned_to":1,"updated_at":"2026-07-24T07:24:28.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 07:24:33', '2026-07-24 07:24:33'),
	(145, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"10.19.88.148","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 09:21:58', '2026-07-24 09:21:58'),
	(146, 'default', 'created', 'App\\Models\\Complaint', 'created', 537, 'App\\Models\\User', 1, '{"attributes":{"id":537,"reference_number":"NET-20260724002","user_name":"Karan Divakar","client_id":1,"network_type_id":1,"section_id":9,"intercom":"120456","room_number":"201","description":"This is test issue....","file_path":null,"priority":"high","request_type_id":2,"vertical_id":1,"status_id":2,"assigned_to":1,"assigned_by":null,"resolution":null,"created_at":"2026-07-24T09:27:52.000000Z","updated_at":"2026-07-24T09:27:52.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 09:27:52', '2026-07-24 09:27:52'),
	(147, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 537, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":2,"assigned_to":5,"assigned_by":1,"updated_at":"2026-07-24T09:28:45.000000Z"},"old":{"vertical_id":1,"assigned_to":1,"assigned_by":null,"updated_at":"2026-07-24T09:27:52.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 09:28:45', '2026-07-24 09:28:45'),
	(148, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 09:34:59', '2026-07-24 09:34:59'),
	(149, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 09:35:49', '2026-07-24 09:35:49'),
	(150, 'default', 'created', 'App\\Models\\Complaint', 'created', 538, 'App\\Models\\User', 1, '{"attributes":{"id":538,"reference_number":"SWE-20260724003","user_name":"Test User","client_id":1,"network_type_id":1,"section_id":9,"intercom":"120789","room_number":"201","description":"Test issue..","file_path":null,"priority":"high","request_type_id":2,"vertical_id":3,"status_id":2,"assigned_to":8,"assigned_by":null,"resolution":null,"created_at":"2026-07-24T09:39:25.000000Z","updated_at":"2026-07-24T09:39:25.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 09:39:25', '2026-07-24 09:39:25'),
	(151, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 538, 'App\\Models\\User', 8, '{"attributes":{"status_id":3,"updated_at":"2026-07-24T09:39:56.000000Z"},"old":{"status_id":2,"updated_at":"2026-07-24T09:39:25.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 09:39:56', '2026-07-24 09:39:56'),
	(152, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 538, 'App\\Models\\User', 8, '{"attributes":{"status_id":4,"updated_at":"2026-07-24T09:40:04.000000Z"},"old":{"status_id":3,"updated_at":"2026-07-24T09:39:56.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 09:40:04', '2026-07-24 09:40:04'),
	(153, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 538, 'App\\Models\\User', 8, '{"attributes":{"status_id":5,"updated_at":"2026-07-24T09:40:11.000000Z"},"old":{"status_id":4,"updated_at":"2026-07-24T09:40:04.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 09:40:11', '2026-07-24 09:40:11'),
	(154, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 538, 'App\\Models\\User', 8, '{"attributes":{"status_id":8,"updated_at":"2026-07-24T09:40:16.000000Z"},"old":{"status_id":5,"updated_at":"2026-07-24T09:40:11.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 09:40:16', '2026-07-24 09:40:16'),
	(155, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 538, 'App\\Models\\User', 1, '{"attributes":{"status_id":2,"assigned_by":1,"updated_at":"2026-07-24T09:40:23.000000Z"},"old":{"status_id":8,"assigned_by":null,"updated_at":"2026-07-24T09:40:16.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 09:40:23', '2026-07-24 09:40:23'),
	(156, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 538, 'App\\Models\\User', 8, '{"attributes":{"vertical_id":10,"assigned_to":30,"assigned_by":8,"updated_at":"2026-07-24T09:40:34.000000Z"},"old":{"vertical_id":3,"assigned_to":8,"assigned_by":1,"updated_at":"2026-07-24T09:40:23.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 09:40:34', '2026-07-24 09:40:34'),
	(157, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 8, '{"user_full_name":"Tarun Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 09:40:36', '2026-07-24 09:40:36'),
	(158, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 30, '{"user_full_name":"Harsh Singh","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 09:40:44', '2026-07-24 09:40:44'),
	(159, 'default', 'User verticals updated', 'App\\Models\\User', NULL, 4, 'App\\Models\\User', 1, '{"user_full_name":"Manish Singh","old_vertical_ids":[11,16],"new_vertical_ids":[3,11,16],"ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 09:41:13', '2026-07-24 09:41:13'),
	(160, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 538, 'App\\Models\\User', 30, '{"attributes":{"assigned_to":10,"assigned_by":30,"updated_at":"2026-07-24T09:41:29.000000Z"},"old":{"assigned_to":30,"assigned_by":8,"updated_at":"2026-07-24T09:40:34.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 09:41:29', '2026-07-24 09:41:29'),
	(161, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 30, '{"user_full_name":"Harsh Singh","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 09:41:31', '2026-07-24 09:41:31'),
	(162, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 10, '{"user_full_name":"Praveen Bansal","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-24 09:41:39', '2026-07-24 09:41:39'),
	(163, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 533, 'App\\Models\\User', 10, '{"attributes":{"status_id":8,"updated_at":"2026-07-24T10:12:02.000000Z"},"old":{"status_id":2,"updated_at":"2026-07-23T12:10:29.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-24 10:12:02', '2026-07-24 10:12:02'),
	(164, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '{"user_full_name":"Rohit Kumar","ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-27 04:41:57', '2026-07-27 04:41:57'),
	(165, 'default', 'created', 'App\\Models\\Complaint', 'created', 539, 'App\\Models\\User', 1, '{"attributes":{"id":539,"reference_number":"H-PSD-PS2-20260727001","user_name":"Laxmi Prasad","client_id":1,"network_type_id":1,"section_id":9,"intercom":"120789","room_number":"201","description":"Test Issue Created..","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":17,"status_id":2,"assigned_to":4,"assigned_by":null,"resolution":null,"created_at":"2026-07-27T05:53:07.000000Z","updated_at":"2026-07-27T05:53:07.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 05:53:07', '2026-07-27 05:53:07'),
	(166, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 539, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":11,"assigned_by":1,"updated_at":"2026-07-27T05:53:42.000000Z"},"old":{"assigned_to":4,"assigned_by":null,"updated_at":"2026-07-27T05:53:07.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 05:53:42', '2026-07-27 05:53:42'),
	(167, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 539, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":4,"updated_at":"2026-07-27T05:54:31.000000Z"},"old":{"assigned_to":11,"updated_at":"2026-07-27T05:53:42.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 05:54:31', '2026-07-27 05:54:31'),
	(168, 'default', 'created', 'App\\Models\\Complaint', 'created', 540, 'App\\Models\\User', 1, '{"attributes":{"id":540,"reference_number":"CS-NS-20260727002","user_name":"Test User","client_id":1,"network_type_id":1,"section_id":9,"intercom":"120369","room_number":"201","description":"Test Issue Created..","file_path":null,"priority":"medium","request_type_id":2,"vertical_id":13,"status_id":2,"assigned_to":6,"assigned_by":null,"resolution":null,"created_at":"2026-07-27T06:00:48.000000Z","updated_at":"2026-07-27T06:00:48.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 06:00:48', '2026-07-27 06:00:48'),
	(169, 'default', 'User verticals updated', 'App\\Models\\User', NULL, 3, 'App\\Models\\User', 1, '{"user_full_name":"Rachit Sharma","old_vertical_ids":[2,9,17],"new_vertical_ids":[2,3,9,17],"ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-27 06:11:55', '2026-07-27 06:11:55'),
	(170, 'default', 'User verticals updated', 'App\\Models\\User', NULL, 18, 'App\\Models\\User', 1, '{"user_full_name":"Prankur Sharma","old_vertical_ids":[1,9],"new_vertical_ids":[1,4,9],"ip_address":"127.0.0.1","user_agent":"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/150.0.0.0 Safari\\/537.36"}', NULL, '2026-07-27 06:12:03', '2026-07-27 06:12:03'),
	(171, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 535, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":6,"assigned_by":1,"updated_at":"2026-07-27T06:23:24.000000Z"},"old":{"assigned_to":null,"assigned_by":null,"updated_at":"2026-07-23T12:34:10.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 06:23:24', '2026-07-27 06:23:24'),
	(172, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 539, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":11,"updated_at":"2026-07-27T06:23:35.000000Z"},"old":{"assigned_to":4,"updated_at":"2026-07-27T05:54:31.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 06:23:35', '2026-07-27 06:23:35'),
	(173, 'default', 'created', 'App\\Models\\Complaint', 'created', 541, 'App\\Models\\User', 1, '{"attributes":{"id":541,"reference_number":"CS-ISS-IS2-20260727003","user_name":"Harsh Singh","client_id":1,"network_type_id":1,"section_id":1,"intercom":"1234","room_number":"101","description":"Sample complaint description","file_path":null,"priority":"medium","request_type_id":1,"vertical_id":21,"status_id":2,"assigned_to":null,"assigned_by":null,"resolution":null,"created_at":"2026-07-27T06:31:21.000000Z","updated_at":"2026-07-27T06:31:21.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 06:31:21', '2026-07-27 06:31:21'),
	(174, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 21, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T07:03:39.000000Z"},"old":{"updated_at":"2026-07-24T06:34:22.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 07:03:39', '2026-07-27 07:03:39'),
	(175, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 21, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T07:03:53.000000Z"},"old":{"updated_at":"2026-07-27T07:03:39.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 07:03:53', '2026-07-27 07:03:53'),
	(176, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 21, 'App\\Models\\User', 1, '{"attributes":{"send_email":1,"updated_at":"2026-07-27T07:04:07.000000Z"},"old":{"send_email":0,"updated_at":"2026-07-27T07:03:53.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 07:04:07', '2026-07-27 07:04:07'),
	(177, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 21, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T07:04:26.000000Z"},"old":{"updated_at":"2026-07-27T07:04:07.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 07:04:26', '2026-07-27 07:04:26'),
	(178, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 21, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T07:06:04.000000Z"},"old":{"updated_at":"2026-07-27T07:04:26.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 07:06:04', '2026-07-27 07:06:04'),
	(179, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 21, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T07:06:11.000000Z"},"old":{"updated_at":"2026-07-27T07:06:04.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 07:06:11', '2026-07-27 07:06:11'),
	(180, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 12, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T07:11:27.000000Z"},"old":{"updated_at":"2026-07-24T06:34:22.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 07:11:27', '2026-07-27 07:11:27'),
	(181, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 6, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T07:12:34.000000Z"},"old":{"updated_at":"2026-06-11T11:54:11.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 07:12:34', '2026-07-27 07:12:34'),
	(182, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 5, 'App\\Models\\User', 1, '{"attributes":{"send_email":1,"updated_at":"2026-07-27T07:13:36.000000Z"},"old":{"send_email":0,"updated_at":"2026-06-11T12:42:20.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 07:13:36', '2026-07-27 07:13:36'),
	(183, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 4, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T09:14:16.000000Z"},"old":{"updated_at":"2026-07-10T11:34:26.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 09:14:16', '2026-07-27 09:14:16'),
	(184, 'default', 'created', 'App\\Models\\Vertical', 'created', 30, 'App\\Models\\User', 1, '{"attributes":{"id":30,"name":"Test Category","short_form":null,"parent_id":null,"send_email":1,"created_at":"2026-07-27T09:18:01.000000Z","updated_at":"2026-07-27T09:18:01.000000Z","deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 09:18:01', '2026-07-27 09:18:01'),
	(185, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 4, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T09:44:41.000000Z"},"old":{"updated_at":"2026-07-27T09:14:16.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 09:44:41', '2026-07-27 09:44:41'),
	(186, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 4, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T09:46:30.000000Z"},"old":{"updated_at":"2026-07-27T09:44:41.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 09:46:30', '2026-07-27 09:46:30'),
	(187, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 4, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T09:46:45.000000Z"},"old":{"updated_at":"2026-07-27T09:46:30.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 09:46:45', '2026-07-27 09:46:45'),
	(188, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 4, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T09:53:35.000000Z"},"old":{"updated_at":"2026-07-27T09:46:45.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 09:53:35', '2026-07-27 09:53:35'),
	(189, 'default', 'created', 'App\\Models\\Vertical', 'created', 31, 'App\\Models\\User', 1, '{"attributes":{"id":31,"name":"Test 2","short_form":null,"parent_id":10,"send_email":1,"created_at":"2026-07-27T10:00:49.000000Z","updated_at":"2026-07-27T10:00:49.000000Z","deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 10:00:49', '2026-07-27 10:00:49'),
	(190, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 29, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T10:02:23.000000Z"},"old":{"updated_at":"2026-07-24T06:34:22.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 10:02:23', '2026-07-27 10:02:23'),
	(191, 'default', 'created', 'App\\Models\\Vertical', 'created', 32, 'App\\Models\\User', 1, '{"attributes":{"id":32,"name":"testyyy","short_form":null,"parent_id":null,"send_email":1,"created_at":"2026-07-27T10:28:59.000000Z","updated_at":"2026-07-27T10:28:59.000000Z","deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 10:28:59', '2026-07-27 10:28:59'),
	(192, 'default', 'created', 'App\\Models\\Vertical', 'created', 33, 'App\\Models\\User', 1, '{"attributes":{"id":33,"name":"tstyyyy2","short_form":"TES2","parent_id":32,"send_email":1,"created_at":"2026-07-27T10:29:56.000000Z","updated_at":"2026-07-27T10:29:56.000000Z","deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 10:29:56', '2026-07-27 10:29:56'),
	(193, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 33, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T10:30:06.000000Z"},"old":{"updated_at":"2026-07-27T10:29:56.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 10:30:06', '2026-07-27 10:30:06'),
	(194, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 33, 'App\\Models\\User', 1, '{"attributes":{"short_form":"TES22","updated_at":"2026-07-27T10:30:14.000000Z"},"old":{"short_form":"TES2","updated_at":"2026-07-27T10:30:06.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 10:30:14', '2026-07-27 10:30:14'),
	(195, 'default', 'deleted', 'App\\Models\\Vertical', 'deleted', 33, 'App\\Models\\User', 1, '{"old":{"id":33,"name":"tstyyyy2","short_form":"TES22","parent_id":32,"send_email":1,"created_at":"2026-07-27T10:29:56.000000Z","updated_at":"2026-07-27T10:30:21.000000Z","deleted_at":"2026-07-27T10:30:21.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 10:30:21', '2026-07-27 10:30:21'),
	(196, 'default', 'updated', 'App\\Models\\Vertical', 'updated', 33, 'App\\Models\\User', 1, '{"attributes":{"updated_at":"2026-07-27T10:30:27.000000Z","deleted_at":null},"old":{"updated_at":"2026-07-27T10:30:21.000000Z","deleted_at":"2026-07-27T10:30:21.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 10:30:27', '2026-07-27 10:30:27'),
	(197, 'default', 'restored', 'App\\Models\\Vertical', 'restored', 33, 'App\\Models\\User', 1, '{"attributes":{"id":33,"name":"tstyyyy2","short_form":"TES22","parent_id":32,"send_email":1,"created_at":"2026-07-27T10:29:56.000000Z","updated_at":"2026-07-27T10:30:27.000000Z","deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 10:30:27', '2026-07-27 10:30:27'),
	(198, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 540, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":7,"assigned_by":1,"updated_at":"2026-07-27T11:43:05.000000Z"},"old":{"assigned_to":6,"assigned_by":null,"updated_at":"2026-07-27T06:00:48.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 11:43:05', '2026-07-27 11:43:05'),
	(199, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 540, 'App\\Models\\User', 1, '{"attributes":{"assigned_to":4,"updated_at":"2026-07-27T11:43:13.000000Z"},"old":{"assigned_to":7,"updated_at":"2026-07-27T11:43:05.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 11:43:13', '2026-07-27 11:43:13'),
	(200, 'default', 'created', 'App\\Models\\Vertical', 'created', 34, 'App\\Models\\User', 1, '{"attributes":{"id":34,"name":"Incident Request","short_form":"IR","parent_id":null,"send_email":0,"is_excluded":0,"created_at":"2026-07-27T12:05:32.000000Z","updated_at":"2026-07-27T12:05:32.000000Z","deleted_at":null},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 12:05:32', '2026-07-27 12:05:32'),
	(201, 'default', 'updated', 'App\\Models\\Complaint', 'updated', 530, 'App\\Models\\User', 1, '{"attributes":{"vertical_id":31,"assigned_to":2,"updated_at":"2026-07-27T12:21:55.000000Z"},"old":{"vertical_id":3,"assigned_to":8,"updated_at":"2026-07-23T12:37:40.000000Z"},"ip_address":"127.0.0.1"}', NULL, '2026-07-27 12:21:56', '2026-07-27 12:21:56');

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
	('crts_cache_83d5e1e49bd5f0ebbf6c9ba40416057fac1b5d76', 'i:1;', 1785133941),
	('crts_cache_83d5e1e49bd5f0ebbf6c9ba40416057fac1b5d76:timer', 'i:1785133941;', 1785133941);

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
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.comments: ~2 rows (approximately)
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
) ENGINE=InnoDB AUTO_INCREMENT=1093 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.complaint_actions: ~87 rows (approximately)
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
	(1092, 530, 1, 2, 2, 'Complaint updated', '2026-07-27 12:21:56', '2026-07-27 12:21:56');

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
) ENGINE=InnoDB AUTO_INCREMENT=542 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.complaints: ~33 rows (approximately)
DELETE FROM `complaints`;
INSERT INTO `complaints` (`id`, `reference_number`, `user_name`, `client_id`, `network_type_id`, `section_id`, `intercom`, `room_number`, `description`, `file_path`, `priority`, `request_type_id`, `vertical_id`, `status_id`, `assigned_to`, `assigned_by`, `resolution`, `created_at`, `updated_at`) VALUES
	(509, 'H-PSD-PS2-20260721003', 'Suhani', 0, 2, 9, '120456', '201', 'Test Issue Case...', NULL, 'medium', 2, 3, 2, 1, 8, NULL, '2026-07-21 05:58:17', '2026-07-21 11:55:14'),
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
	(529, 'SWE-20260723006', 'Test User', 1, 2, 2, '120456', '201', 'Test issue', NULL, 'medium', 2, 3, 1, NULL, NULL, NULL, '2026-07-23 11:38:59', '2026-07-23 11:38:59'),
	(530, 'SWE-20260723007', 'Test User', 1, 2, 2, '120456', '201', 'Test issue', NULL, 'medium', 2, 31, 2, 2, 1, NULL, '2026-07-23 11:39:58', '2026-07-27 12:21:55'),
	(531, 'SWE-20260723008', 'Test User', 1, 1, 5, '120456', '201', 'Test issue', NULL, 'medium', 2, 2, 6, 5, 1, NULL, '2026-07-23 11:41:25', '2026-07-23 12:35:53'),
	(532, 'SWE-20260723009', 'Prabhanshu', 1, 2, 6, '120456', '201', 'Test issue..', NULL, 'medium', 2, 3, 2, 8, 1, NULL, '2026-07-23 11:51:58', '2026-07-23 11:52:32'),
	(533, 'NET-WS-20260723010', 'Amrita Rao', 1, 1, 4, '120456', '201', 'Test issue craeted', NULL, 'medium', 2, 3, 8, 10, 1, NULL, '2026-07-23 12:09:03', '2026-07-24 10:12:02'),
	(534, 'VC-20260723011', 'Purshottam Kumar', 0, 2, 5, '120456', '321', 'Test issue created', NULL, 'medium', 2, 2, 2, 3, 1, NULL, '2026-07-23 12:09:25', '2026-07-23 12:09:57'),
	(535, 'CS-ISS-IS2-20260723012', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Sample complaint description', NULL, 'medium', 1, 21, 2, 6, 1, NULL, '2026-07-23 12:34:10', '2026-07-27 06:23:24'),
	(536, 'NET-WS-20260724001', 'Gitesh Kumar Srivastav', 1, 2, 4, '120456', '201', 'Test issue...', NULL, 'medium', 2, 14, 2, 6, 1, NULL, '2026-07-24 07:23:54', '2026-07-24 07:24:33'),
	(537, 'NET-20260724002', 'Karan Divakar', 1, 1, 9, '120456', '201', 'This is test issue....', NULL, 'high', 2, 2, 2, 5, 1, NULL, '2026-07-24 09:27:52', '2026-07-24 09:28:45'),
	(538, 'SWE-20260724003', 'Test User', 1, 1, 9, '120789', '201', 'Test issue..', NULL, 'high', 2, 10, 2, 10, 30, NULL, '2026-07-24 09:39:25', '2026-07-24 09:41:29'),
	(539, 'H-PSD-PS2-20260727001', 'Laxmi Prasad', 1, 1, 9, '120789', '201', 'Test Issue Created..', NULL, 'medium', 2, 17, 2, 11, 1, NULL, '2026-07-27 05:53:07', '2026-07-27 06:23:35'),
	(540, 'CS-NS-20260727002', 'Test User', 1, 1, 9, '120369', '201', 'Test Issue Created..', NULL, 'medium', 2, 13, 2, 4, 1, NULL, '2026-07-27 06:00:48', '2026-07-27 11:43:13'),
	(541, 'CS-ISS-IS2-20260727003', 'Harsh Singh', 1, 1, 1, '1234', '101', 'Sample complaint description', NULL, 'medium', 1, 21, 2, NULL, NULL, NULL, '2026-07-27 06:31:21', '2026-07-27 06:31:21');

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

-- Dumping data for table tms-laravel.migrations: ~71 rows (approximately)
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.network_types: ~1 rows (approximately)
DELETE FROM `network_types`;
INSERT INTO `network_types` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Air Gap Network', '2025-06-17 05:29:27', '2026-07-10 11:03:25', NULL),
	(2, 'Internet', '2025-06-17 05:29:27', '2026-07-10 06:24:03', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.request_types: ~1 rows (approximately)
DELETE FROM `request_types`;
INSERT INTO `request_types` (`id`, `name`, `slug`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Change Record', 'change_record', '2026-07-03 06:09:31', '2026-07-10 09:05:13', NULL),
	(2, 'New Entry', 'new_entry', '2026-07-03 06:09:31', '2026-07-03 06:09:31', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.sections: ~20 rows (approximately)
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
	(21, 'NACWC', '2025-06-17 05:29:27', '2025-06-17 05:29:27', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.statuses: ~7 rows (approximately)
DELETE FROM `statuses`;
INSERT INTO `statuses` (`id`, `name`, `slug`, `color`, `description`, `sort_order`, `created_at`, `updated_at`, `visible_to_user`, `deleted_at`) VALUES
	(1, 'unassigned', 'unassigned', 'warning', 'Complaint is waiting to be assigned', 1, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 0, NULL),
	(2, 'assigned', 'assigned', 'info', 'Complaint has been assigned to a team member', 2, '2025-06-23 17:43:53', '2026-07-10 08:41:40', 0, NULL),
	(3, 'in_progress', 'in_progress', 'primary', 'Work on the complaint is currently in progress', 3, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 1, NULL),
	(4, 'pending_with_vendor', 'pending_with_vendor', 'danger', 'Complaint has been escalated to higher authority', 4, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 1, NULL),
	(5, 'pending_with_user', 'pending_with_user', 'danger', 'Complaint has been resolved successfully', 5, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 1, NULL),
	(6, 'closed', 'closed', 'success', 'Complaint has been closed', 7, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 0, NULL),
	(8, 'completed', 'completed', 'success', NULL, 6, '2025-06-23 17:43:53', '2025-06-23 17:43:53', 1, NULL);

-- Dumping structure for table tms-laravel.user_vertical
CREATE TABLE IF NOT EXISTS `user_vertical` (
  `user_id` bigint unsigned NOT NULL,
  `vertical_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`vertical_id`),
  KEY `user_vertical_vertical_id_foreign` (`vertical_id`),
  CONSTRAINT `user_vertical_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_vertical_vertical_id_foreign` FOREIGN KEY (`vertical_id`) REFERENCES `verticals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.user_vertical: ~40 rows (approximately)
DELETE FROM `user_vertical`;
INSERT INTO `user_vertical` (`user_id`, `vertical_id`) VALUES
	(3, 2),
	(3, 3),
	(3, 9),
	(3, 17),
	(4, 3),
	(4, 4),
	(4, 11),
	(4, 16),
	(4, 21),
	(5, 2),
	(5, 9),
	(5, 11),
	(6, 4),
	(6, 6),
	(6, 9),
	(6, 12),
	(6, 13),
	(6, 14),
	(7, 4),
	(7, 9),
	(7, 21),
	(7, 29),
	(7, 30),
	(8, 1),
	(8, 3),
	(8, 6),
	(8, 9),
	(9, 2),
	(9, 9),
	(10, 2),
	(10, 3),
	(10, 9),
	(11, 4),
	(11, 5),
	(11, 9),
	(11, 12),
	(11, 17),
	(11, 21),
	(11, 30),
	(11, 34),
	(17, 1),
	(17, 4),
	(17, 5),
	(17, 9),
	(17, 21),
	(17, 30),
	(17, 33),
	(18, 1),
	(18, 4),
	(18, 9),
	(18, 21),
	(27, 4),
	(27, 5),
	(27, 9),
	(27, 21),
	(27, 30),
	(29, 9),
	(29, 25),
	(30, 4),
	(30, 10),
	(30, 21),
	(30, 31),
	(30, 32),
	(30, 34);

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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
	(29, 4, 'testuser', NULL, NULL, 'Test User', NULL, '$2y$12$Q3ewYp99KuDhbO/I7DwGSOwhA5eNtjqiyVaR3yzqpI7G5d8qX/JXy', 1, '2026-07-10 11:59:49', '2026-07-10 11:59:49', NULL),
	(30, 4, 'harsh', 'distinctharsh@gmail.com', NULL, 'Harsh Singh', NULL, '$2y$12$IhhR47HT.crAaKLufMrdN.zjARa2l8QMsWoqwdo69ZfFrXuigHN8e', 0, '2026-07-15 12:04:10', '2026-07-15 12:05:11', NULL);

-- Dumping structure for table tms-laravel.verticals
CREATE TABLE IF NOT EXISTS `verticals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_form` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL COMMENT 'Null means top-level category',
  `send_email` tinyint(1) NOT NULL DEFAULT '1',
  `is_excluded` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verticals_parent_id_foreign` (`parent_id`),
  CONSTRAINT `verticals_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `verticals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table tms-laravel.verticals: ~18 rows (approximately)
DELETE FROM `verticals`;
INSERT INTO `verticals` (`id`, `name`, `short_form`, `parent_id`, `send_email`, `is_excluded`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Network', 'NET', NULL, 1, 0, '2025-06-17 05:29:27', '2026-06-11 11:54:24', NULL),
	(2, 'VC', 'VC', NULL, 1, 0, '2025-06-17 05:29:27', '2026-07-14 06:23:45', NULL),
	(3, 'Software', 'SWE', NULL, 1, 0, '2025-06-17 05:29:27', '2026-06-11 11:54:55', NULL),
	(4, 'Cyber Security', 'CS', NULL, 1, 0, '2025-06-20 04:45:03', '2026-07-27 09:53:35', NULL),
	(5, 'Email', 'E', NULL, 1, 0, '2025-06-20 04:41:20', '2026-07-27 07:13:36', NULL),
	(6, 'Hardware', 'H', NULL, 1, 1, '2025-07-09 08:25:45', '2026-07-27 07:12:34', NULL),
	(9, 'Other', 'OTHER', NULL, 0, 0, '2026-05-21 08:51:49', '2026-07-08 12:05:19', NULL),
	(10, 'Application Security', 'AS', 3, 0, 1, '2026-06-23 07:17:54', '2026-07-10 11:56:57', NULL),
	(11, 'Database Security', 'DS', 3, 1, 0, '2026-06-23 07:20:49', '2026-06-23 07:20:49', NULL),
	(12, 'Information Security', 'ISS', 4, 1, 0, '2026-06-23 07:21:09', '2026-07-27 07:11:27', NULL),
	(13, 'Network Security', 'NS', 4, 1, 0, '2026-06-23 07:21:32', '2026-07-10 11:34:26', NULL),
	(14, 'Wireless Security (Wi-Fi Security)', 'WS', 1, 1, 0, '2026-06-23 07:21:57', '2026-06-23 07:21:57', NULL),
	(15, 'Email Security', 'ES', 5, 0, 0, '2026-06-23 08:56:37', '2026-07-10 11:56:37', NULL),
	(16, 'Physical Security of Devices', 'PSD', 6, 1, 1, '2026-06-23 08:56:59', '2026-06-23 08:56:59', NULL),
	(17, 'PSD2', 'PS2', 16, 0, 0, '2026-06-23 09:01:14', '2026-07-10 11:56:45', NULL),
	(21, 'IS2', 'IS2', 12, 1, 0, '2026-06-25 11:30:49', '2026-07-27 07:06:11', NULL),
	(25, 'Iss334', NULL, 12, 1, 0, '2026-07-10 06:38:24', '2026-07-24 06:34:22', NULL),
	(29, 'test4', NULL, 25, 1, 0, '2026-07-23 09:23:25', '2026-07-27 10:02:23', NULL),
	(30, 'Test Category', NULL, NULL, 1, 0, '2026-07-27 09:18:01', '2026-07-27 09:18:01', NULL),
	(31, 'Test 2', NULL, 10, 1, 0, '2026-07-27 10:00:49', '2026-07-27 10:00:49', NULL),
	(32, 'testyyy', NULL, NULL, 1, 0, '2026-07-27 10:28:59', '2026-07-27 10:28:59', NULL),
	(33, 'tstyyyy2', 'TES22', 32, 1, 0, '2026-07-27 10:29:56', '2026-07-27 10:30:27', NULL),
	(34, 'Incident Request', 'IR', NULL, 0, 0, '2026-07-27 12:05:32', '2026-07-27 12:05:32', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
