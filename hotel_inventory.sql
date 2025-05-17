-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2025 at 04:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `record_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `created_at`, `updated_at`) VALUES
(1, 1, 'login', 'users', 1, NULL, NULL, '2025-04-19 07:18:15', '2025-04-19 07:18:15'),
(2, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-19 07:23:59', '2025-04-19 07:23:59'),
(3, 1, 'login', 'users', 1, NULL, NULL, '2025-04-19 07:24:05', '2025-04-19 07:24:05'),
(4, NULL, 'system', 'audit_logs', 1, '\"{\\\"status\\\":\\\"empty\\\"}\"', '\"{\\\"status\\\":\\\"test_log_1745076344\\\"}\"', '2025-04-19 07:25:44', '2025-04-19 07:25:44'),
(5, NULL, 'system', 'audit_logs', 1, '\"{\\\"status\\\":\\\"empty\\\"}\"', '\"{\\\"status\\\":\\\"test_log_1745076345\\\"}\"', '2025-04-19 07:25:45', '2025-04-19 07:25:45'),
(6, 1, 'created', 'suppliers', 3, NULL, '{\"name\":\"Bayonyon\",\"contact_person\":\"YonaBa\",\"email\":\"bayoniks@gmail.com\",\"phone\":\"0981122431\",\"address\":\"Bokayo, Buhangin\",\"notes\":null,\"is_active\":true,\"updated_at\":\"2025-04-19 15:52:06\",\"created_at\":\"2025-04-19 15:52:06\",\"id\":3}', '2025-04-19 07:52:06', '2025-04-19 07:52:06'),
(7, 1, 'updated', 'suppliers', 3, '{\"is_active\":true,\"updated_at\":\"2025-04-19T15:52:06.000000Z\"}', '{\"id\":3,\"name\":\"Bayonyon\",\"contact_person\":\"YonaBa\",\"email\":\"bayoniks@gmail.com\",\"phone\":\"0981122431\",\"address\":\"Bokayo, Buhangin\",\"notes\":null,\"is_active\":false,\"created_at\":\"2025-04-19 15:52:06\",\"updated_at\":\"2025-04-19 15:52:28\"}', '2025-04-19 07:52:28', '2025-04-19 07:52:28'),
(8, 1, 'login', 'users', 1, NULL, NULL, '2025-04-19 20:39:39', '2025-04-19 20:39:39'),
(9, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-19 20:46:11', '2025-04-19 20:46:11'),
(10, 2, 'login', 'users', 2, NULL, NULL, '2025-04-19 20:53:53', '2025-04-19 20:53:53'),
(11, 2, 'created', 'items', 10, NULL, '{\"name\":\"unique bed\",\"description\":\"unique ni\",\"category_id\":\"1\",\"is_active\":\"1\",\"updated_at\":\"2025-04-20 05:40:23\",\"created_at\":\"2025-04-20 05:40:23\",\"id\":10}', '2025-04-19 21:40:23', '2025-04-19 21:40:23'),
(12, 1, 'login', 'users', 1, NULL, NULL, '2025-04-22 03:33:05', '2025-04-22 03:33:05'),
(13, 1, 'created', 'locations', 21, NULL, '{\"name\":\"Floor 20 - Room 12\",\"floor_number\":\"20\",\"area_type\":\"room\",\"room_number\":\"12\",\"description\":null,\"updated_at\":\"2025-04-22 11:33:50\",\"created_at\":\"2025-04-22 11:33:50\",\"id\":21}', '2025-04-22 03:33:50', '2025-04-22 03:33:50'),
(14, 1, 'deleted', 'purchase_orders', 4, '{\"id\":4,\"supplier_id\":1,\"order_date\":\"2025-04-20\",\"status\":\"canceled\",\"delivered_date\":null,\"total_amount\":\"1230.00\",\"created_at\":\"2025-04-20 05:29:46\",\"updated_at\":\"2025-04-22 11:34:16\"}', NULL, '2025-04-22 03:36:24', '2025-04-22 03:36:24'),
(15, 1, 'created', 'purchase_orders', 5, NULL, '{\"supplier_id\":\"1\",\"order_date\":\"2025-04-22 00:00:00\",\"status\":\"pending\",\"total_amount\":100,\"updated_at\":\"2025-04-22 11:37:36\",\"created_at\":\"2025-04-22 11:37:36\",\"id\":5}', '2025-04-22 03:37:36', '2025-04-22 03:37:36'),
(16, 1, 'updated', 'purchase_orders', 5, '{\"total_amount\":\"100.00\",\"updated_at\":\"2025-04-22T11:37:36.000000Z\"}', '{\"id\":5,\"supplier_id\":\"1\",\"order_date\":\"2025-04-22\",\"status\":\"pending\",\"delivered_date\":null,\"total_amount\":150,\"created_at\":\"2025-04-22 11:37:36\",\"updated_at\":\"2025-04-22 11:37:54\"}', '2025-04-22 03:37:54', '2025-04-22 03:37:54'),
(17, 1, 'updated', 'purchase_orders', 5, '{\"status\":\"pending\",\"delivered_date\":null,\"updated_at\":\"2025-04-22T11:37:54.000000Z\"}', '{\"id\":5,\"supplier_id\":1,\"order_date\":\"2025-04-22\",\"status\":\"delivered\",\"delivered_date\":\"2025-04-22 11:38:42\",\"total_amount\":\"150.00\",\"created_at\":\"2025-04-22 11:37:36\",\"updated_at\":\"2025-04-22 11:38:42\"}', '2025-04-22 03:38:42', '2025-04-22 03:38:42'),
(18, 1, 'status_changed', 'purchase_orders', 5, '{\"status\":\"pending\"}', '{\"status\":\"delivered\",\"action\":\"marked_as_delivered\"}', '2025-04-22 03:38:42', '2025-04-22 03:38:42'),
(19, 1, 'login', 'users', 1, NULL, NULL, '2025-04-22 05:03:43', '2025-04-22 05:03:43'),
(20, 1, 'created', 'purchase_orders', 6, NULL, '{\"supplier_id\":\"1\",\"order_date\":\"2025-04-22 00:00:00\",\"status\":\"pending\",\"total_amount\":1000,\"updated_at\":\"2025-04-22 14:59:51\",\"created_at\":\"2025-04-22 14:59:51\",\"id\":6}', '2025-04-22 06:59:51', '2025-04-22 06:59:51'),
(21, 1, 'updated', 'purchase_orders', 6, '{\"status\":\"pending\",\"delivered_date\":null,\"updated_at\":\"2025-04-22T14:59:51.000000Z\"}', '{\"id\":6,\"supplier_id\":1,\"order_date\":\"2025-04-22\",\"status\":\"delivered\",\"delivered_date\":\"2025-04-22 14:59:58\",\"total_amount\":\"1000.00\",\"created_at\":\"2025-04-22 14:59:51\",\"updated_at\":\"2025-04-22 14:59:58\"}', '2025-04-22 06:59:58', '2025-04-22 06:59:58'),
(22, 1, 'status_changed', 'purchase_orders', 6, '{\"status\":\"pending\"}', '{\"status\":\"delivered\",\"action\":\"marked_as_delivered\"}', '2025-04-22 06:59:58', '2025-04-22 06:59:58'),
(23, 1, 'created', 'purchase_orders', 7, NULL, '{\"supplier_id\":\"1\",\"order_date\":\"2025-04-22 00:00:00\",\"status\":\"pending\",\"total_amount\":24,\"updated_at\":\"2025-04-22 15:03:46\",\"created_at\":\"2025-04-22 15:03:46\",\"id\":7}', '2025-04-22 07:03:46', '2025-04-22 07:03:46'),
(24, 1, 'updated', 'purchase_orders', 7, '{\"status\":\"pending\",\"delivered_date\":null,\"updated_at\":\"2025-04-22T15:03:46.000000Z\"}', '{\"id\":7,\"supplier_id\":1,\"order_date\":\"2025-04-22\",\"status\":\"delivered\",\"delivered_date\":\"2025-04-22 15:03:51\",\"total_amount\":\"24.00\",\"created_at\":\"2025-04-22 15:03:46\",\"updated_at\":\"2025-04-22 15:03:51\"}', '2025-04-22 07:03:51', '2025-04-22 07:03:51'),
(25, 1, 'status_changed', 'purchase_orders', 7, '{\"status\":\"pending\"}', '{\"status\":\"delivered\",\"action\":\"marked_as_delivered\"}', '2025-04-22 07:03:51', '2025-04-22 07:03:51'),
(26, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-22 07:06:31', '2025-04-22 07:06:31'),
(27, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-22 07:06:31', '2025-04-22 07:06:31'),
(28, 1, 'login', 'users', 1, NULL, NULL, '2025-04-22 07:16:40', '2025-04-22 07:16:40'),
(29, 1, 'login', 'users', 1, NULL, NULL, '2025-04-22 07:16:40', '2025-04-22 07:16:40'),
(30, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-22 07:23:17', '2025-04-22 07:23:17'),
(31, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-22 07:23:17', '2025-04-22 07:23:17'),
(32, 1, 'login', 'users', 1, NULL, NULL, '2025-04-22 07:24:42', '2025-04-22 07:24:42'),
(33, 1, 'login', 'users', 1, NULL, NULL, '2025-04-22 07:24:42', '2025-04-22 07:24:42'),
(34, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-22 07:25:07', '2025-04-22 07:25:07'),
(35, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-22 07:25:07', '2025-04-22 07:25:07'),
(36, 1, 'login', 'users', 1, NULL, NULL, '2025-04-22 07:25:13', '2025-04-22 07:25:13'),
(37, 1, 'login', 'users', 1, NULL, NULL, '2025-04-22 07:25:13', '2025-04-22 07:25:13'),
(38, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-22 07:25:28', '2025-04-22 07:25:28'),
(39, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-22 07:25:28', '2025-04-22 07:25:28'),
(40, 1, 'login', 'users', 1, NULL, NULL, '2025-04-23 04:57:20', '2025-04-23 04:57:20'),
(41, 1, 'login', 'users', 1, NULL, NULL, '2025-04-23 04:57:20', '2025-04-23 04:57:20'),
(42, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-23 05:06:11', '2025-04-23 05:06:11'),
(43, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-23 05:06:11', '2025-04-23 05:06:11'),
(44, 1, 'login', 'users', 1, NULL, NULL, '2025-04-23 05:47:07', '2025-04-23 05:47:07'),
(45, 1, 'login', 'users', 1, NULL, NULL, '2025-04-23 05:47:07', '2025-04-23 05:47:07'),
(46, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-23 05:47:39', '2025-04-23 05:47:39'),
(47, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-23 05:47:39', '2025-04-23 05:47:39'),
(48, 1, 'login', 'users', 1, NULL, NULL, '2025-04-23 05:48:29', '2025-04-23 05:48:29'),
(49, 1, 'login', 'users', 1, NULL, NULL, '2025-04-23 05:48:29', '2025-04-23 05:48:29'),
(50, 1, 'logout', 'users', 1, NULL, NULL, '2025-04-23 05:52:22', '2025-04-23 05:52:22'),
(51, 2, 'login', 'users', 2, NULL, NULL, '2025-04-23 05:52:41', '2025-04-23 05:52:41'),
(52, 2, 'logout', 'users', 2, NULL, NULL, '2025-04-23 05:53:39', '2025-04-23 05:53:39'),
(53, 1, 'login', 'users', 1, NULL, NULL, '2025-04-25 05:55:27', '2025-04-25 05:55:27'),
(54, 1, 'login', 'users', 1, NULL, NULL, '2025-05-05 04:01:54', '2025-05-05 04:01:54'),
(55, 1, 'login', 'users', 1, NULL, NULL, '2025-05-07 05:40:43', '2025-05-07 05:40:43'),
(56, 1, 'login', 'users', 1, NULL, NULL, '2025-05-09 02:28:39', '2025-05-09 02:28:39'),
(57, 1, 'created', 'purchase_orders', 8, NULL, '{\"supplier_id\":\"1\",\"order_date\":\"2025-05-09 00:00:00\",\"status\":\"pending\",\"total_amount\":5000,\"updated_at\":\"2025-05-09 12:20:16\",\"created_at\":\"2025-05-09 12:20:16\",\"id\":8}', '2025-05-09 04:20:16', '2025-05-09 04:20:16'),
(58, 1, 'updated', 'purchase_orders', 8, '{\"status\":\"pending\",\"updated_at\":\"2025-05-09T12:20:16.000000Z\"}', '{\"id\":8,\"supplier_id\":1,\"order_date\":\"2025-05-09\",\"status\":\"canceled\",\"delivered_date\":null,\"total_amount\":\"5000.00\",\"created_at\":\"2025-05-09 12:20:16\",\"updated_at\":\"2025-05-09 12:21:47\"}', '2025-05-09 04:21:47', '2025-05-09 04:21:47'),
(59, 1, 'status_changed', 'purchase_orders', 8, '{\"status\":\"pending\"}', '{\"status\":\"canceled\",\"action\":\"marked_as_canceled\"}', '2025-05-09 04:21:47', '2025-05-09 04:21:47'),
(60, 1, 'created', 'purchase_orders', 9, NULL, '{\"supplier_id\":\"1\",\"order_date\":\"2025-05-09 00:00:00\",\"status\":\"pending\",\"total_amount\":24000,\"updated_at\":\"2025-05-09 12:25:40\",\"created_at\":\"2025-05-09 12:25:40\",\"id\":9}', '2025-05-09 04:25:40', '2025-05-09 04:25:40'),
(61, 1, 'updated', 'purchase_orders', 9, '{\"total_amount\":\"24000.00\",\"updated_at\":\"2025-05-09T12:25:40.000000Z\"}', '{\"id\":9,\"supplier_id\":\"1\",\"order_date\":\"2025-05-09\",\"status\":\"pending\",\"delivered_date\":null,\"total_amount\":26500,\"created_at\":\"2025-05-09 12:25:40\",\"updated_at\":\"2025-05-09 12:26:29\"}', '2025-05-09 04:26:29', '2025-05-09 04:26:29'),
(62, 1, 'updated', 'purchase_orders', 9, '{\"total_amount\":\"26500.00\",\"updated_at\":\"2025-05-09T12:26:29.000000Z\"}', '{\"id\":9,\"supplier_id\":\"1\",\"order_date\":\"2025-05-09\",\"status\":\"pending\",\"delivered_date\":null,\"total_amount\":26000,\"created_at\":\"2025-05-09 12:25:40\",\"updated_at\":\"2025-05-09 12:35:39\"}', '2025-05-09 04:35:39', '2025-05-09 04:35:39'),
(63, 1, 'login', 'users', 1, NULL, NULL, '2025-05-11 00:38:59', '2025-05-11 00:38:59'),
(64, 1, 'created', 'items', 11, NULL, '{\"name\":\"TV\",\"description\":\"14 inches TV\",\"category_id\":\"5\",\"is_active\":\"1\",\"updated_at\":\"2025-05-11 08:54:55\",\"created_at\":\"2025-05-11 08:54:55\",\"id\":11}', '2025-05-11 00:54:55', '2025-05-11 00:54:55'),
(65, 1, 'updated', 'suppliers', 3, '{\"is_active\":false,\"updated_at\":\"2025-04-19T15:52:28.000000Z\"}', '{\"id\":3,\"name\":\"Bayonyon\",\"contact_person\":\"YonaBa\",\"email\":\"bayoniks@gmail.com\",\"phone\":\"0981122431\",\"address\":\"Bokayo, Buhangin\",\"notes\":null,\"is_active\":true,\"created_at\":\"2025-04-19 15:52:06\",\"updated_at\":\"2025-05-11 08:55:20\"}', '2025-05-11 00:55:20', '2025-05-11 00:55:20'),
(66, 1, 'created', 'purchase_orders', 10, NULL, '{\"supplier_id\":\"3\",\"order_date\":\"2025-05-11 00:00:00\",\"status\":\"pending\",\"total_amount\":145000,\"updated_at\":\"2025-05-11 08:56:09\",\"created_at\":\"2025-05-11 08:56:09\",\"id\":10}', '2025-05-11 00:56:09', '2025-05-11 00:56:09'),
(67, 1, 'updated', 'purchase_orders', 10, '{\"status\":\"pending\",\"delivered_date\":null,\"updated_at\":\"2025-05-11T08:56:09.000000Z\"}', '{\"id\":10,\"supplier_id\":3,\"order_date\":\"2025-05-11\",\"status\":\"delivered\",\"delivered_date\":\"2025-05-11 08:56:42\",\"total_amount\":\"145000.00\",\"created_at\":\"2025-05-11 08:56:09\",\"updated_at\":\"2025-05-11 08:56:42\"}', '2025-05-11 00:56:42', '2025-05-11 00:56:42'),
(68, 1, 'status_changed', 'purchase_orders', 10, '{\"status\":\"pending\"}', '{\"status\":\"delivered\",\"action\":\"marked_as_delivered\"}', '2025-05-11 00:56:42', '2025-05-11 00:56:42'),
(69, 1, 'logout', 'users', 1, NULL, NULL, '2025-05-11 01:02:50', '2025-05-11 01:02:50'),
(70, 1, 'login', 'users', 1, NULL, NULL, '2025-05-11 01:03:01', '2025-05-11 01:03:01'),
(71, 1, 'logout', 'users', 1, NULL, NULL, '2025-05-11 01:03:08', '2025-05-11 01:03:08'),
(72, 1, 'login', 'users', 1, NULL, NULL, '2025-05-11 01:03:13', '2025-05-11 01:03:13'),
(73, 1, 'logout', 'users', 1, NULL, NULL, '2025-05-11 01:12:04', '2025-05-11 01:12:04'),
(74, 1, 'login', 'users', 1, NULL, NULL, '2025-05-11 01:12:08', '2025-05-11 01:12:08'),
(75, 1, 'logout', 'users', 1, NULL, NULL, '2025-05-11 01:16:43', '2025-05-11 01:16:43'),
(76, 1, 'login', 'users', 1, NULL, NULL, '2025-05-11 01:16:46', '2025-05-11 01:16:46'),
(77, 1, 'logout', 'users', 1, NULL, NULL, '2025-05-11 01:18:47', '2025-05-11 01:18:47'),
(78, 1, 'login', 'users', 1, NULL, NULL, '2025-05-11 01:18:51', '2025-05-11 01:18:51'),
(79, 1, 'logout', 'users', 1, NULL, NULL, '2025-05-11 01:25:55', '2025-05-11 01:25:55'),
(80, 1, 'login', 'users', 1, NULL, NULL, '2025-05-14 16:45:21', '2025-05-14 16:45:21'),
(81, 1, 'login', 'users', 1, NULL, NULL, '2025-05-17 05:35:43', '2025-05-17 05:35:43'),
(82, 1, 'logout', 'users', 1, NULL, NULL, '2025-05-17 14:34:28', '2025-05-17 14:34:28'),
(83, 1, 'login', 'users', 1, NULL, NULL, '2025-05-17 14:34:34', '2025-05-17 14:34:34');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'BED', NULL, 1, '2025-04-15 05:51:35', '2025-04-17 06:14:35'),
(2, 'silverware', NULL, 1, '2025-04-15 05:52:39', '2025-04-15 05:52:39'),
(3, 'Refrigerator', NULL, 1, '2025-04-15 05:52:59', '2025-04-15 05:52:59'),
(4, 'Aircon', NULL, 1, '2025-04-15 05:53:05', '2025-04-17 06:00:04'),
(5, 'Appliances', NULL, 1, '2025-05-11 00:54:12', '2025-05-11 00:54:12');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `reorder_level` int(11) NOT NULL DEFAULT 10,
  `last_stocked_at` datetime DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `purchase_order_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_id`, `current_stock`, `reorder_level`, `last_stocked_at`, `supplier_name`, `purchase_order_id`, `created_at`, `updated_at`) VALUES
(1, 1, 50, 10, '2025-04-15 13:57:03', 'earlNatiks', 1, '2025-04-15 05:57:03', '2025-04-15 06:13:23'),
(2, 2, 100, 10, '2025-04-15 13:57:03', 'earlNatiks', 1, '2025-04-15 05:57:03', '2025-04-15 05:57:03'),
(3, 3, 65, 10, '2025-04-22 11:38:42', 'earlNatiks', 5, '2025-04-15 05:57:03', '2025-04-22 03:38:42'),
(4, 4, 100, 10, '2025-04-15 13:57:03', 'earlNatiks', 1, '2025-04-15 05:57:03', '2025-04-15 05:57:03'),
(5, 8, 105, 10, '2025-04-22 14:59:58', 'earlNatiks', 6, '2025-04-15 05:57:03', '2025-04-22 06:59:58'),
(6, 7, 107, 10, '2025-04-22 15:03:51', 'earlNatiks', 7, '2025-04-15 05:57:03', '2025-04-22 07:03:51'),
(8, 6, 32, 10, '2025-04-15 13:57:03', 'earlNatiks', 1, '2025-04-15 05:57:03', '2025-04-15 06:31:31'),
(9, 5, 100, 10, '2025-04-15 14:07:03', 'earlNatiks', 3, '2025-04-15 06:07:03', '2025-04-15 06:07:03'),
(10, 11, 9, 10, '2025-05-11 08:56:42', 'Bayonyon', 10, '2025-05-11 00:56:42', '2025-05-11 00:58:20'),
(11, 10, 8, 10, '2025-05-11 08:56:42', 'Bayonyon', 10, '2025-05-11 00:56:42', '2025-05-11 00:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `description`, `category_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Massive Bed', 'dako na bed', 1, 1, '2025-04-15 05:53:20', '2025-04-15 05:53:20'),
(2, 'Small Bed', 'gamay na bed', 1, 1, '2025-04-15 05:53:30', '2025-04-15 05:53:30'),
(3, 'Knife', 'kutsilyo', 2, 1, '2025-04-15 05:53:44', '2025-04-15 05:53:44'),
(4, 'Spoon', 'kutsra', 2, 0, '2025-04-15 05:53:58', '2025-04-17 05:54:39'),
(5, 'Small  Ref', 'gamay ref', 3, 1, '2025-04-15 05:54:13', '2025-04-15 05:54:13'),
(6, 'Big Ref', 'dako', 3, 1, '2025-04-15 05:54:25', '2025-04-15 05:54:25'),
(7, 'Non Split', 'dili split', 4, 1, '2025-04-15 05:54:49', '2025-04-15 05:54:49'),
(8, 'Split Type', 'Split', 4, 1, '2025-04-15 05:54:58', '2025-04-15 05:54:58'),
(10, 'unique bed', 'unique ni', 1, 1, '2025-04-19 21:40:23', '2025-04-19 21:40:23'),
(11, 'TV', '14 inches TV', 5, 1, '2025-05-11 00:54:55', '2025-05-11 00:54:55');

-- --------------------------------------------------------

--
-- Table structure for table `item_pullouts`
--

CREATE TABLE `item_pullouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_pullouts`
--

INSERT INTO `item_pullouts` (`id`, `item_id`, `location_id`, `quantity`, `reason`, `status`, `user_id`, `notes`, `created_at`, `updated_at`) VALUES
(7, 1, 18, 2, 'Damaged', 'completed', 1, NULL, '2025-04-18 07:44:28', '2025-04-18 07:44:28'),
(8, 3, 2, 2, 'Defective', 'completed', 1, NULL, '2025-04-18 07:47:07', '2025-04-18 07:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `floor_number` int(11) NOT NULL,
  `area_type` enum('room','kitchen','hallway','restaurant','storage','other') NOT NULL,
  `room_number` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `floor_number`, `area_type`, `room_number`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Floor 1 Room 1', 1, 'room', '1', 'rooms', 1, '2025-04-15 06:08:04', '2025-04-15 06:08:04'),
(2, 'Floor 1 Room 2', 1, 'room', '2', 'rooms', 1, '2025-04-15 06:08:04', '2025-04-15 06:08:04'),
(3, 'Floor 1 Room 3', 1, 'room', '3', 'rooms', 1, '2025-04-15 06:08:04', '2025-04-15 06:08:04'),
(4, 'Floor 1 Room 4', 1, 'room', '4', 'rooms', 1, '2025-04-15 06:08:04', '2025-04-15 06:08:04'),
(5, 'Floor 1 Room 5', 1, 'room', '5', 'rooms', 1, '2025-04-15 06:08:04', '2025-04-15 06:08:04'),
(6, 'Floor 1 Room 6', 1, 'room', '6', 'rooms', 1, '2025-04-15 06:08:04', '2025-04-15 06:08:04'),
(7, 'Floor 1 Room 7', 1, 'room', '7', 'rooms', 1, '2025-04-15 06:08:04', '2025-04-15 06:08:04'),
(8, 'Floor 1 Room 8', 1, 'room', '8', 'rooms', 1, '2025-04-15 06:08:04', '2025-04-15 06:08:04'),
(9, 'Floor 1 Room 9', 1, 'room', '9', 'rooms', 1, '2025-04-15 06:08:04', '2025-04-15 06:08:04'),
(10, 'Floor 1 Room 10', 1, 'room', '10', 'rooms', 1, '2025-04-15 06:08:04', '2025-04-15 06:08:04'),
(11, 'Floor 1 - Hallway', 1, 'hallway', NULL, 'hallways', 1, '2025-04-15 06:08:37', '2025-04-15 06:08:37'),
(12, 'Floor 2 Kitchen 1', 2, 'kitchen', '1', 'kitchens', 1, '2025-04-15 06:09:13', '2025-04-15 06:09:13'),
(13, 'Floor 2 Kitchen 2', 2, 'kitchen', '2', 'kitchens', 1, '2025-04-15 06:09:13', '2025-04-15 06:09:13'),
(14, 'Floor 2 Kitchen 3', 2, 'kitchen', '3', 'kitchens', 1, '2025-04-15 06:09:13', '2025-04-15 06:09:13'),
(15, 'Floor 2 Kitchen 4', 2, 'kitchen', '4', 'kitchens', 1, '2025-04-15 06:09:13', '2025-04-15 06:09:13'),
(16, 'Floor 2 Kitchen 5', 2, 'kitchen', '5', 'kitchens', 1, '2025-04-15 06:09:13', '2025-04-15 06:09:13'),
(17, 'Floor 0 - Storage', 0, 'storage', NULL, 'store', 0, '2025-04-15 06:09:37', '2025-04-15 06:09:56'),
(18, 'Floor 5 - Restaurant', 5, 'restaurant', NULL, 'res', 1, '2025-04-15 06:35:19', '2025-04-15 06:35:19'),
(21, 'Floor 20 - Room 12', 20, 'room', '12', NULL, 1, '2025-04-22 03:33:50', '2025-04-22 03:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `location_items`
--

CREATE TABLE `location_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `location_items`
--

INSERT INTO `location_items` (`id`, `location_id`, `item_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, '2025-04-15 06:13:23', '2025-04-16 06:47:10'),
(2, 2, 1, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(3, 3, 1, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(4, 4, 1, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(5, 5, 1, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(6, 1, 3, 8, '2025-04-15 06:13:23', '2025-04-16 07:01:31'),
(7, 2, 3, 8, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(8, 3, 3, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(9, 4, 3, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(10, 5, 3, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(11, 1, 6, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(12, 2, 6, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(13, 3, 6, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(14, 4, 6, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(15, 5, 6, 10, '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(16, 12, 6, 10, '2025-04-15 06:31:08', '2025-04-15 06:31:08'),
(17, 13, 6, 5, '2025-04-15 06:31:08', '2025-04-15 06:31:08'),
(18, 14, 6, 3, '2025-04-15 06:31:31', '2025-04-15 06:31:31'),
(19, 18, 1, 5, '2025-04-16 06:47:10', '2025-04-16 06:47:10'),
(20, 18, 3, 2, '2025-04-16 07:01:31', '2025-04-16 07:01:31'),
(21, 1, 11, 3, '2025-05-11 00:58:20', '2025-05-11 00:58:20'),
(22, 2, 11, 2, '2025-05-11 00:58:20', '2025-05-11 00:58:20'),
(23, 3, 11, 1, '2025-05-11 00:58:20', '2025-05-11 00:58:20'),
(24, 4, 10, 12, '2025-05-11 00:58:51', '2025-05-11 00:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_03_20_133815_create_roles_table', 1),
(5, '2025_03_20_133845_create_categories_table', 1),
(6, '2025_03_20_133900_create_items_table', 1),
(7, '2025_03_20_133910_create_suppliers_table', 1),
(8, '2025_03_20_133925_create_locations_table', 1),
(9, '2025_03_20_133936_create_inventory_table', 1),
(10, '2025_03_20_133945_create_stock_movements_table', 1),
(11, '2025_03_20_133955_create_purchase_orders_table', 1),
(12, '2025_03_20_134005_create_purchase_order_items_table', 1),
(13, '2025_03_20_134016_create_audit_logs_table', 1),
(14, '2025_04_15_134811_create_location_items_table', 1),
(15, '2023_03_20_add_is_active_to_categories_table', 2),
(16, '2023_03_20_add_is_active_to_items_table', 2),
(17, '2023_03_20_add_is_active_to_suppliers_table', 2),
(18, '2024_03_25_000000_create_item_pullouts_table', 3),
(19, '2025_04_18_000000_update_stock_movements_type_column', 4),
(20, '2024_04_15_000000_create_audit_logs_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `order_date` date NOT NULL,
  `status` enum('pending','delivered','canceled') NOT NULL DEFAULT 'pending',
  `delivered_date` date DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `supplier_id`, `order_date`, `status`, `delivered_date`, `total_amount`, `created_at`, `updated_at`) VALUES
(3, 1, '2025-04-15', 'delivered', '2025-04-15', 10000.00, '2025-04-15 06:06:20', '2025-04-15 06:07:03'),
(5, 1, '2025-04-22', 'delivered', '2025-04-22', 150.00, '2025-04-22 03:37:36', '2025-04-22 03:38:42'),
(6, 1, '2025-04-22', 'delivered', '2025-04-22', 1000.00, '2025-04-22 06:59:51', '2025-04-22 06:59:58'),
(7, 1, '2025-04-22', 'delivered', '2025-04-22', 24.00, '2025-04-22 07:03:46', '2025-04-22 07:03:51'),
(8, 1, '2025-05-09', 'canceled', NULL, 5000.00, '2025-05-09 04:20:16', '2025-05-09 04:21:47'),
(9, 1, '2025-05-09', 'pending', NULL, 26000.00, '2025-05-09 04:25:40', '2025-05-09 04:35:39'),
(10, 3, '2025-05-11', 'delivered', '2025-05-11', 145000.00, '2025-05-11 00:56:09', '2025-05-11 00:56:42');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `item_name`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
(13, 3, 'Small  Ref', 100, 100.00, 10000.00, '2025-04-15 06:06:56', '2025-04-15 06:06:56'),
(16, 5, 'Knife', 15, 10.00, 150.00, '2025-04-22 03:37:54', '2025-04-22 03:37:54'),
(17, 6, 'Split Type', 5, 100.00, 500.00, '2025-04-22 06:59:51', '2025-04-22 06:59:51'),
(18, 6, 'Non Split', 5, 100.00, 500.00, '2025-04-22 06:59:51', '2025-04-22 06:59:51'),
(19, 7, 'Non Split', 2, 12.00, 24.00, '2025-04-22 07:03:46', '2025-04-22 07:03:46'),
(20, 8, 'Knife', 5, 1000.00, 5000.00, '2025-05-09 04:20:16', '2025-05-09 04:20:16'),
(24, 9, 'Big Ref', 3, 8000.00, 24000.00, '2025-05-09 04:35:39', '2025-05-09 04:35:39'),
(25, 9, 'Knife', 2, 500.00, 1000.00, '2025-05-09 04:35:39', '2025-05-09 04:35:39'),
(26, 9, 'Massive Bed', 1, 1000.00, 1000.00, '2025-05-09 04:35:39', '2025-05-09 04:35:39'),
(27, 10, 'TV', 15, 5000.00, 75000.00, '2025-05-11 00:56:09', '2025-05-11 00:56:09'),
(28, 10, 'unique bed', 20, 3500.00, 70000.00, '2025-05-11 00:56:09', '2025-05-11 00:56:09');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Kh6sWPTTRGkqRg1aVUBzIXmYMKGFjv8SQzlvYyKo', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWDFRR0Z4bEJkdFJzN3dSMHdSUTlBbGtIUHhtOHhBM25qbWVHVTVnTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvaW52ZW50b3J5L3N0b2NrLW1vdmVtZW50cyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1747492483);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `from_location_id` bigint(20) UNSIGNED DEFAULT NULL,
  `to_location_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `type` enum('in','out','transfer','pullout') DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `item_id`, `from_location_id`, `to_location_id`, `quantity`, `type`, `user_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(2, 1, NULL, 2, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(3, 1, NULL, 3, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(4, 1, NULL, 4, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(5, 1, NULL, 5, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(6, 3, NULL, 1, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(7, 3, NULL, 2, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(8, 3, NULL, 3, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(9, 3, NULL, 4, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(10, 3, NULL, 5, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(11, 6, NULL, 1, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(12, 6, NULL, 2, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(13, 6, NULL, 3, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(14, 6, NULL, 4, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(15, 6, NULL, 5, 10, 'out', 1, 'stock ta boy', '2025-04-15 06:13:23', '2025-04-15 06:13:23'),
(16, 6, NULL, 12, 10, 'out', 1, 'qwerty', '2025-04-15 06:31:08', '2025-04-15 06:31:08'),
(17, 6, NULL, 13, 5, 'out', 1, 'qwerty', '2025-04-15 06:31:08', '2025-04-15 06:31:08'),
(18, 6, NULL, 14, 3, 'out', 1, NULL, '2025-04-15 06:31:31', '2025-04-15 06:31:31'),
(19, 1, 1, 18, 7, 'transfer', 1, NULL, '2025-04-16 06:47:10', '2025-04-16 06:47:10'),
(20, 3, 1, 18, 2, 'transfer', 1, NULL, '2025-04-16 07:01:31', '2025-04-16 07:01:31'),
(21, 1, 18, NULL, 2, 'out', 1, 'Pullout: Damaged', '2025-04-18 07:44:28', '2025-04-18 07:44:28'),
(22, 3, 2, NULL, 2, 'out', 1, 'Pullout: Defective', '2025-04-18 07:47:07', '2025-04-18 07:47:07'),
(23, 7, NULL, NULL, 2, 'in', 1, 'Stock received from PO #7 (earlNatiks)', '2025-04-22 07:03:51', '2025-04-22 07:03:51'),
(24, 11, NULL, NULL, 15, 'in', 1, 'Stock received from PO #10 (Bayonyon)', '2025-05-11 00:56:42', '2025-05-11 00:56:42'),
(25, 10, NULL, NULL, 20, 'in', 1, 'Stock received from PO #10 (Bayonyon)', '2025-05-11 00:56:42', '2025-05-11 00:56:42'),
(26, 11, NULL, 1, 3, 'out', 1, NULL, '2025-05-11 00:58:20', '2025-05-11 00:58:20'),
(27, 11, NULL, 2, 2, 'out', 1, NULL, '2025-05-11 00:58:20', '2025-05-11 00:58:20'),
(28, 11, NULL, 3, 1, 'out', 1, NULL, '2025-05-11 00:58:20', '2025-05-11 00:58:20'),
(29, 10, NULL, 4, 12, 'out', 1, NULL, '2025-05-11 00:58:51', '2025-05-11 00:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `email`, `phone`, `address`, `notes`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'earlNatiks', 'Earl', 'earl@gmail.com', '09199284675', 'Matina', 'qwe', 1, '2025-04-15 05:55:16', '2025-04-15 05:55:16'),
(2, 'gabSoy', 'Gabrielle', 'gab@gmail.com', '0999888222', 'Cabaguio', '2ndni', 0, '2025-04-17 06:01:16', '2025-04-17 06:01:46'),
(3, 'Bayonyon', 'YonaBa', 'bayoniks@gmail.com', '0981122431', 'Bokayo, Buhangin', NULL, 1, '2025-04-19 07:52:06', '2025-05-11 00:55:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`) VALUES
(1, 'dexter', 'dexter@gmail.com', NULL, '$2y$12$qzNlY8nKF9OqHpifHistp.lG8pzxNzUkWg3BKLiMwo.43XpHEQgXC', NULL, '2025-04-15 05:50:22', '2025-04-15 05:50:22', NULL),
(2, 'EarlSoy', 'earl@gmail.com', NULL, '$2y$12$pJBW/AUm899gMmo3hxnyYutv0GhbZmTkQI9Vj6/oeveAxE2S31DgW', NULL, '2025-04-19 20:53:53', '2025-04-19 20:53:53', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`),
  ADD KEY `audit_logs_action_table_name_index` (`action`,`table_name`),
  ADD KEY `audit_logs_created_at_index` (`created_at`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_item_id_foreign` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_category_id_foreign` (`category_id`);

--
-- Indexes for table `item_pullouts`
--
ALTER TABLE `item_pullouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_pullouts_item_id_foreign` (`item_id`),
  ADD KEY `item_pullouts_location_id_foreign` (`location_id`),
  ADD KEY `item_pullouts_user_id_foreign` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locations_floor_number_area_type_room_number_unique` (`floor_number`,`area_type`,`room_number`);

--
-- Indexes for table `location_items`
--
ALTER TABLE `location_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `location_items_location_id_item_id_unique` (`location_id`,`item_id`),
  ADD KEY `location_items_item_id_foreign` (`item_id`);

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
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_orders_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_item_id_foreign` (`item_id`),
  ADD KEY `stock_movements_from_location_id_foreign` (`from_location_id`),
  ADD KEY `stock_movements_to_location_id_foreign` (`to_location_id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `item_pullouts`
--
ALTER TABLE `item_pullouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `location_items`
--
ALTER TABLE `location_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_pullouts`
--
ALTER TABLE `item_pullouts`
  ADD CONSTRAINT `item_pullouts_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_pullouts_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_pullouts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `location_items`
--
ALTER TABLE `location_items`
  ADD CONSTRAINT `location_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `location_items_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_from_location_id_foreign` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_movements_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_to_location_id_foreign` FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
