-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 05, 2026 at 01:57 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tangwin11`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id_employee` bigint UNSIGNED NOT NULL,
  `employee_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `join_date` date NOT NULL,
  `exit_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `daily_expense_limit` decimal(15,2) DEFAULT NULL,
  `id_store` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id_employee`, `employee_name`, `photo_path`, `position`, `phone_number`, `join_date`, `exit_date`, `is_active`, `daily_expense_limit`, `id_store`, `created_at`, `updated_at`) VALUES
(1, 'Samsir', NULL, 'Capster', '0833457587', '2025-11-02', NULL, 1, '100000.00', 2, '2025-11-02 02:14:45', '2026-02-27 15:26:08'),
(2, 'Renza', NULL, 'Capster', '0833457589', '2025-11-02', NULL, 1, '100000.00', 2, '2025-11-02 02:14:59', '2026-02-27 15:26:08'),
(3, 'Adit', NULL, 'Capster', '0833457588', '2025-11-02', NULL, 1, '100000.00', 3, '2025-11-02 02:15:14', '2026-02-27 15:26:08');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id_expense` bigint UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `expense_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_employee` bigint UNSIGNED NOT NULL,
  `id_store` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id_expense`, `description`, `amount`, `expense_date`, `id_employee`, `id_store`, `id_user`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'bon', '70000.00', '2025-11-02 02:24:22', 2, 2, 2, '2025-11-02 02:25:51', '2025-11-02 02:24:22', '2025-11-02 02:25:51'),
(2, 'bon', '100000.00', '2025-11-02 02:24:40', 1, 2, 2, NULL, '2025-11-02 02:24:40', '2025-11-02 02:24:40'),
(3, 'makan', '50000.00', '2026-02-27 13:30:55', 2, 2, 2, NULL, '2026-02-27 13:30:55', '2026-02-27 13:30:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `id_food` bigint UNSIGNED NOT NULL,
  `food_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_available` int NOT NULL DEFAULT '0',
  `id_store` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`id_food`, `food_name`, `price`, `stock_available`, `id_store`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Aqua 600 ml', '3000.00', 99, 2, '2025-11-04 08:02:30', '2025-11-02 02:16:37', '2025-11-04 08:02:30'),
(2, 'Susu Milku', '5000.00', 27, 2, NULL, '2026-02-10 15:12:38', '2026-03-01 12:23:20'),
(3, 'Risol', '2000.00', 9, 2, NULL, '2026-02-10 15:13:11', '2026-02-19 07:45:02');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_19_164058_create_stores_table', 1),
(5, '2025_10_19_164946_add_role_and_store_id_to_users_table', 1),
(6, '2025_10_20_073624_create_services_table', 1),
(7, '2025_10_20_073633_create_products_table', 1),
(8, '2025_10_20_073640_create_foods_table', 1),
(9, '2025_10_20_073700_create_employees_table', 1),
(10, '2025_10_21_110644_create_payment_methods_table', 1),
(11, '2025_10_21_110651_create_transactions_table', 1),
(12, '2025_10_21_110658_create_transaction_details_table', 1),
(13, '2025_10_21_184210_make_employee_id_nullable_in_transaction_details_table', 1),
(14, '2025_10_22_145106_create_expenses_table', 1),
(15, '2025_10_22_145117_add_daily_expense_limit_to_employees_table', 1),
(16, '2025_11_03_102828_add_is_active_to_users_table', 2),
(17, '2025_11_03_143522_add_is_active_to_stores_table', 3),
(18, '2025_11_04_163024_add_ip_validation_to_stores_table', 4),
(19, '2025_11_05_163313_create_presence_schedules_table', 5),
(20, '2025_11_05_163340_create_presence_logs_table', 5),
(22, '2025_11_13_172642_add_payment_status_to_transactions_table', 6),
(23, '2025_11_17_203221_add_gateway_setting_to_stores_table', 6),
(24, '2025_11_20_131459_create_reservations_table', 7),
(26, '2025_11_20_135406_create_reservation_slots_table', 8),
(27, '2025_11_20_135748_create_reservation_slot_employee_table', 9),
(28, '2025_11_21_135006_add_email_to_reservations_table', 10),
(29, '2025_12_01_134600_add_details_to_stores_table', 11),
(30, '2026_02_12_151826_add_lateness_columns_to_presence_tables', 12),
(31, '2026_02_12_161056_add_soft_deletes_to_presence_schedules_table', 12),
(33, '2026_03_05_091743_create_refunds_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id_payment_method` bigint UNSIGNED NOT NULL,
  `method_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id_payment_method`, `method_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Cash', 1, '2025-11-02 08:41:24', '2025-11-02 07:20:47'),
(2, 'Qris', 1, '2025-11-02 08:41:24', '2025-11-02 08:41:24'),
(4, 'Transfer', 1, '2025-11-02 07:21:12', '2025-11-02 07:21:12');

-- --------------------------------------------------------

--
-- Table structure for table `presence_logs`
--

CREATE TABLE `presence_logs` (
  `id_presence_log` bigint UNSIGNED NOT NULL,
  `id_employee` bigint UNSIGNED NOT NULL,
  `id_store` bigint UNSIGNED NOT NULL,
  `id_presence_schedule` bigint UNSIGNED DEFAULT NULL,
  `check_in_time` datetime DEFAULT NULL,
  `check_out_time` datetime DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `late_minutes` int NOT NULL DEFAULT '0' COMMENT 'Jumlah menit keterlambatan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `presence_logs`
--

INSERT INTO `presence_logs` (`id_presence_log`, `id_employee`, `id_store`, `id_presence_schedule`, `check_in_time`, `check_out_time`, `status`, `notes`, `ip_address`, `late_minutes`, `created_at`, `updated_at`) VALUES
(8, 2, 2, 2, '2025-11-07 22:48:48', NULL, 'Terlambat', 'Terlambat -48 menit.', '127.0.0.1', 0, '2025-11-07 15:48:48', '2025-11-07 15:48:48'),
(9, 2, 2, 4, '2025-11-10 15:05:00', NULL, 'Terlambat', 'Terlambat -75 menit.', '127.0.0.1', 0, '2025-11-10 08:05:00', '2025-11-10 08:05:00'),
(10, 2, 2, 2, '2026-02-27 17:12:18', NULL, 'Tepat Waktu', 'Presensi masuk berhasil.', '127.0.0.1', 0, '2026-02-27 10:12:18', '2026-02-27 10:12:18');

-- --------------------------------------------------------

--
-- Table structure for table `presence_schedules`
--

CREATE TABLE `presence_schedules` (
  `id_presence_schedule` bigint UNSIGNED NOT NULL,
  `id_store` bigint UNSIGNED NOT NULL,
  `day_of_week` tinyint NOT NULL,
  `jam_check_in` time NOT NULL,
  `jam_check_out` time NOT NULL,
  `late_threshold` int NOT NULL DEFAULT '0' COMMENT 'Batas toleransi keterlambatan dalam menit',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `presence_schedules`
--

INSERT INTO `presence_schedules` (`id_presence_schedule`, `id_store`, `day_of_week`, `jam_check_in`, `jam_check_out`, `late_threshold`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 2, 5, '17:00:00', '23:00:00', 15, 1, '2025-11-07 00:39:03', '2026-02-28 17:15:53', NULL),
(4, 2, 1, '13:50:00', '15:00:00', 15, 1, '2025-11-10 07:13:30', '2026-02-12 10:05:48', NULL),
(5, 2, 2, '10:00:00', '22:00:00', 15, 1, '2026-03-02 07:08:25', '2026-03-02 07:08:25', NULL),
(6, 2, 3, '10:00:00', '22:00:00', 0, 1, '2026-03-02 07:08:58', '2026-03-02 07:08:58', NULL),
(7, 2, 4, '10:00:00', '22:00:00', 15, 1, '2026-03-02 07:09:33', '2026-03-02 07:09:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id_product` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `stock_available` int NOT NULL DEFAULT '0',
  `id_store` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id_product`, `product_name`, `description`, `price`, `stock_available`, `id_store`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Hair Tonic', NULL, '15000.00', 99, 2, NULL, '2025-11-02 02:15:44', '2026-03-01 12:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` bigint UNSIGNED NOT NULL,
  `id_reservation` bigint UNSIGNED NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cancel_reason` text COLLATE utf8mb4_unicode_ci,
  `amount` int NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` bigint UNSIGNED NOT NULL,
  `id_store` bigint UNSIGNED NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `id_service` bigint UNSIGNED DEFAULT NULL,
  `id_employee` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `id_store`, `customer_name`, `customer_phone`, `customer_email`, `booking_date`, `booking_time`, `id_service`, `id_employee`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'sdvnsoier', 'srngflksewgn', NULL, '2025-11-24', '11:00:00', 1, 1, 'pending', 'selkfoiwehg', '2025-11-21 00:36:50', '2025-11-21 00:36:50'),
(2, 2, 'R', '23456789', NULL, '2025-11-24', '12:00:00', 1, 1, 'pending', 'Cepak Memek', '2025-11-21 00:38:40', '2025-11-21 00:38:40'),
(3, 2, 'Paul', '2345678', NULL, '2025-11-24', '10:15:00', 1, 1, 'pending', 'Cepak memek', '2025-11-21 01:31:30', '2025-11-21 01:31:30'),
(4, 2, 'we3457', '4567', NULL, '2025-12-01', '10:15:00', 1, 1, 'pending', '5678', '2025-11-21 02:29:48', '2025-11-21 02:29:48'),
(5, 2, 'Coba', '3456', NULL, '2025-12-01', '11:00:00', 1, 1, 'pending', 'mohawk', '2025-11-21 02:31:05', '2025-11-21 02:31:05'),
(6, 2, 'Farrell', '12345678', NULL, '2025-12-01', '12:00:00', 1, 1, 'pending', 'Mohawk', '2025-11-21 03:05:49', '2025-11-21 03:05:49'),
(7, 2, 'Aku', '23456789', NULL, '2025-11-24', '10:15:00', 1, 1, 'approved', '-', '2025-11-21 03:24:36', '2025-11-21 03:24:52'),
(8, 2, 'Paul', '456789', NULL, '2025-12-01', '10:15:00', 1, 1, 'approved', 'aiufui', '2025-11-21 03:26:02', '2025-11-21 03:26:13'),
(9, 2, 'adit', '23456789', NULL, '2025-12-08', '12:00:00', 1, 1, 'approved', 'mohak', '2025-11-21 04:30:31', '2025-11-21 04:30:45'),
(10, 2, 'drftguhij', '23456', 'ngasal@yahoo.com', '2025-12-08', '10:15:00', 1, 1, 'pending', NULL, '2025-11-21 07:49:33', '2025-11-21 07:49:33'),
(11, 2, 'Natan', '1234', 'Test@email.com', '2025-12-08', '10:15:00', 1, 1, 'pending', 'test', '2025-11-22 07:13:23', '2025-11-22 07:13:23'),
(12, 2, 'bn', 'bnm', 'test@gmail.com', '2025-12-08', '11:00:00', 1, 1, 'approved', '-', '2025-11-22 07:21:20', '2025-11-22 07:28:56'),
(13, 2, 'ghjkl', '1234567', 'cia@gmail.com', '2025-12-15', '10:15:00', 1, 1, 'approved', '.', '2025-11-22 07:35:26', '2025-11-22 07:35:38'),
(14, 2, 'nafcasdyfch', '678', 'n@gmail.com', '2025-12-15', '11:00:00', 1, 1, 'approved', '.', '2025-11-22 07:36:27', '2025-11-22 07:37:08'),
(15, 2, 'Adit', '2456234', 'adit@mail.com', '2025-12-15', '12:00:00', 1, 1, 'pending', 'rcyvghb', '2025-11-24 04:38:04', '2025-11-24 04:38:04'),
(16, 2, 'Adit', '2456234', 'adit@mail.com', '2025-12-15', '12:00:00', 1, 1, 'pending', 'rcyvghb', '2025-11-24 04:38:06', '2025-11-24 04:38:06'),
(17, 2, 'ccaca', '345678', 'asmdkasndi@mail.com', '2025-12-01', '10:15:00', 1, 2, 'approved', 'cajkfb', '2025-11-24 04:41:33', '2025-11-24 04:42:00'),
(18, 2, 'nyoba lagi', '098764567', 'natan@mail.com', '2025-12-15', '10:15:00', 1, 1, 'approved', NULL, '2025-11-24 04:44:07', '2025-11-24 04:45:29'),
(19, 2, 'luffy', '789098766', 'luffy@mail.com', '2025-12-22', '10:15:00', 1, 1, 'approved', 'mohak', '2025-11-24 05:21:48', '2025-11-24 05:22:05'),
(20, 2, 'Renza', '123712', 'renza@mail.com', '2025-12-22', '10:15:00', 1, 1, 'approved', 'mohak', '2025-11-25 00:14:14', '2025-11-25 00:14:31'),
(21, 2, 'renza', '2345678', 'renza@mail.com', '2025-12-22', '12:00:00', 1, 1, 'approved', 'gundul', '2025-11-25 00:38:08', '2025-11-25 00:39:25'),
(26, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:22', '2025-11-28 17:32:06'),
(27, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:23', '2025-11-25 05:20:23'),
(28, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:24', '2025-11-25 05:20:24'),
(29, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:25', '2025-11-25 05:20:25'),
(30, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:26', '2025-11-25 05:20:26'),
(31, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:27', '2025-11-25 05:20:27'),
(32, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:29', '2025-11-25 05:20:29'),
(33, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:33', '2025-11-25 05:20:33'),
(34, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:39', '2025-11-25 05:20:39'),
(35, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:44', '2025-11-25 05:20:44'),
(36, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:45', '2025-11-25 05:20:45'),
(37, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:47', '2025-11-25 05:20:47'),
(38, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:48', '2025-11-25 05:20:48'),
(39, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:50', '2025-11-25 05:20:50'),
(40, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:51', '2025-11-25 05:20:51'),
(41, 2, 'henli', '+62 856-0179-7782', 'henli@mail.com', '2025-12-22', '11:00:00', 1, 1, 'pending', 'cepak memek', '2025-11-25 05:20:53', '2025-11-25 05:20:53'),
(42, 2, 'henli lagi', '4567890-', 'henli2@mail.com', '2025-12-29', '10:15:00', 1, 1, 'approved', 'cepak mohak', '2025-11-25 05:23:03', '2025-11-25 05:23:19'),
(43, 2, 'kiya', '567890', 'kiyajancok@mail.com', '2025-12-29', '10:15:00', 1, 1, 'approved', '-', '2025-11-25 05:28:40', '2025-11-28 17:50:41'),
(44, 2, 'tvfywte', '456789', 'tvfywte@mail.com', '2025-12-29', '11:00:00', 1, 1, 'approved', '-', '2025-11-25 05:35:48', '2025-11-25 05:36:18'),
(45, 2, 'kiya tai', '456789', 'kiyamail@mail.com', '2025-12-29', '12:00:00', 1, 1, 'approved', 'gondrong', '2025-11-25 05:55:24', '2025-11-25 05:55:53'),
(46, 2, 'piter', '345678', 'piter@mail.com', '2026-01-05', '10:15:00', 1, 1, 'approved', 'cepak', '2025-12-04 23:41:50', '2025-12-04 23:42:19'),
(47, 2, 'rizky', '23456789', 'Rizky@mail.com', '2026-02-09', '10:15:00', 1, 1, 'pending', 'cepak', '2025-12-10 03:58:52', '2025-12-10 03:58:52'),
(48, 2, 'rizky', '23456789', 'Rizky@mail.com', '2026-02-09', '10:15:00', 1, 1, 'approved', 'cepak', '2025-12-10 03:58:57', '2025-12-10 03:59:16'),
(49, 2, 'Ivo', '34567864567', 'ivo@mail.com', '2026-01-29', '10:00:00', 1, 2, 'pending', NULL, '2026-01-24 12:21:35', '2026-01-24 12:21:35'),
(50, 2, 'Ivo', '45678964567', 'ivo@gmail.com', '2026-01-29', '10:00:00', 1, 2, 'approved', NULL, '2026-01-24 12:23:52', '2026-01-24 12:24:19'),
(51, 2, 'Gracia', '45789378465', 'cia@gmail.com', '2026-02-09', '10:15:00', 1, 1, 'pending', '-', '2026-02-06 04:28:53', '2026-02-06 04:28:53'),
(52, 2, 'gracia', '23534', 'cia@mail.com', '2026-02-09', '10:15:00', 1, 1, 'completed', '-', '2026-02-06 04:32:49', '2026-02-11 13:32:52'),
(53, 2, 'coba tgl 9 jam 10.15', '1234567', 'fgdrtdrt@mail.com', '2026-02-09', '10:15:00', 1, 1, 'approved', NULL, '2026-02-06 04:41:27', '2026-02-06 04:41:59'),
(54, 2, 'Afan', '345678767', 'afan@mail.com', '2026-02-09', '12:00:00', 1, 1, 'approved', 'cepmek', '2026-02-07 00:42:21', '2026-02-07 00:42:39'),
(55, 2, 'andre', '23456u', 'andre@mail.com', '2026-02-16', '12:00:00', 1, 1, 'completed', 'old school', '2026-02-08 08:24:18', '2026-02-11 13:32:46'),
(56, 2, 'Vigo', '234567876', 'vigo@mail.com', '2026-03-04', '15:00:00', 1, 2, 'canceled', 'old school', '2026-03-01 05:28:48', '2026-03-02 07:22:21'),
(57, 2, 'Natan', '08231546725', 'natan@mail.com', '2026-03-06', '10:00:00', 1, 1, 'canceled', '-', '2026-03-04 23:05:46', '2026-03-05 06:41:23'),
(58, 2, 'Natan', '08231546725', 'natan@mail.com', '2026-03-06', '10:00:00', 1, 1, 'canceled', '-', '2026-03-04 23:05:48', '2026-03-05 06:41:28'),
(59, 2, 'natan', '123456789', 'natan@mail.com', '2026-03-06', '10:00:00', 1, 1, 'approved', '-', '2026-03-04 23:43:41', '2026-03-04 23:43:52'),
(60, 2, 'parel', '123456789', 'parel@mail.com', '2026-03-06', '10:00:00', 1, 2, 'approved', '-', '2026-03-04 23:54:51', '2026-03-04 23:55:07');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_slots`
--

CREATE TABLE `reservation_slots` (
  `id_slot` bigint UNSIGNED NOT NULL,
  `id_store` bigint UNSIGNED NOT NULL,
  `day_of_week` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slot_time` time NOT NULL,
  `quota` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservation_slots`
--

INSERT INTO `reservation_slots` (`id_slot`, `id_store`, `day_of_week`, `slot_time`, `quota`, `is_active`, `created_at`, `updated_at`) VALUES
(215, 2, 'Senin', '10:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(216, 2, 'Senin', '10:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(217, 2, 'Senin', '11:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(218, 2, 'Senin', '12:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(219, 2, 'Senin', '13:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(220, 2, 'Senin', '13:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(221, 2, 'Senin', '14:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(222, 2, 'Senin', '15:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(223, 2, 'Senin', '16:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(224, 2, 'Senin', '16:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(225, 2, 'Senin', '17:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(226, 2, 'Senin', '18:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(227, 2, 'Senin', '19:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(228, 2, 'Senin', '19:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(229, 2, 'Senin', '20:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(230, 2, 'Senin', '21:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(231, 2, 'Selasa', '10:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(232, 2, 'Selasa', '10:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(233, 2, 'Selasa', '11:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(234, 2, 'Selasa', '12:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(235, 2, 'Selasa', '13:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(236, 2, 'Selasa', '13:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(237, 2, 'Selasa', '14:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(238, 2, 'Selasa', '15:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(239, 2, 'Selasa', '16:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(240, 2, 'Selasa', '16:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(241, 2, 'Selasa', '17:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(242, 2, 'Selasa', '18:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(243, 2, 'Selasa', '19:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(244, 2, 'Selasa', '19:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(245, 2, 'Selasa', '20:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(246, 2, 'Selasa', '21:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(247, 2, 'Rabu', '10:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(248, 2, 'Rabu', '10:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(249, 2, 'Rabu', '11:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(250, 2, 'Rabu', '12:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(251, 2, 'Rabu', '13:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(252, 2, 'Rabu', '13:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(253, 2, 'Rabu', '14:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(254, 2, 'Rabu', '15:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(255, 2, 'Rabu', '16:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(256, 2, 'Rabu', '16:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(257, 2, 'Rabu', '17:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(258, 2, 'Rabu', '18:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(259, 2, 'Rabu', '19:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(260, 2, 'Rabu', '19:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(261, 2, 'Rabu', '20:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(262, 2, 'Rabu', '21:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(263, 2, 'Kamis', '10:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(264, 2, 'Kamis', '10:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(265, 2, 'Kamis', '11:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(266, 2, 'Kamis', '12:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(267, 2, 'Kamis', '13:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(268, 2, 'Kamis', '13:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(269, 2, 'Kamis', '14:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(270, 2, 'Kamis', '15:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(271, 2, 'Kamis', '16:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(272, 2, 'Kamis', '16:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(273, 2, 'Kamis', '17:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(274, 2, 'Kamis', '18:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(275, 2, 'Kamis', '19:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(276, 2, 'Kamis', '19:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(277, 2, 'Kamis', '20:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(278, 2, 'Kamis', '21:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(279, 2, 'Jumat', '10:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(280, 2, 'Jumat', '10:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(281, 2, 'Jumat', '11:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(282, 2, 'Jumat', '12:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(283, 2, 'Jumat', '13:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(284, 2, 'Jumat', '13:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(285, 2, 'Jumat', '14:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(286, 2, 'Jumat', '15:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(287, 2, 'Jumat', '16:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(288, 2, 'Jumat', '16:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(289, 2, 'Jumat', '17:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(290, 2, 'Jumat', '18:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(291, 2, 'Jumat', '19:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(292, 2, 'Jumat', '19:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(293, 2, 'Jumat', '20:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(294, 2, 'Jumat', '21:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(295, 2, 'Sabtu', '10:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(296, 2, 'Sabtu', '10:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(297, 2, 'Sabtu', '11:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(298, 2, 'Sabtu', '12:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(299, 2, 'Sabtu', '13:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(300, 2, 'Sabtu', '13:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(301, 2, 'Sabtu', '14:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(302, 2, 'Sabtu', '15:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(303, 2, 'Sabtu', '16:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(304, 2, 'Sabtu', '16:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(305, 2, 'Sabtu', '17:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(306, 2, 'Sabtu', '18:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(307, 2, 'Sabtu', '19:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(308, 2, 'Sabtu', '19:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(309, 2, 'Sabtu', '20:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(310, 2, 'Sabtu', '21:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(311, 2, 'Minggu', '10:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(312, 2, 'Minggu', '10:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(313, 2, 'Minggu', '11:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(314, 2, 'Minggu', '12:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(315, 2, 'Minggu', '13:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(316, 2, 'Minggu', '13:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(317, 2, 'Minggu', '14:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(318, 2, 'Minggu', '15:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(319, 2, 'Minggu', '16:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(320, 2, 'Minggu', '16:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(321, 2, 'Minggu', '17:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(322, 2, 'Minggu', '18:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(323, 2, 'Minggu', '19:00:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(324, 2, 'Minggu', '19:45:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(325, 2, 'Minggu', '20:30:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33'),
(326, 2, 'Minggu', '21:15:00', 1, 1, '2026-03-02 15:32:33', '2026-03-02 15:32:33');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_slot_employee`
--

CREATE TABLE `reservation_slot_employee` (
  `id` bigint UNSIGNED NOT NULL,
  `id_slot` bigint UNSIGNED NOT NULL,
  `id_employee` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservation_slot_employee`
--

INSERT INTO `reservation_slot_employee` (`id`, `id_slot`, `id_employee`) VALUES
(360, 215, 1),
(361, 215, 2),
(362, 216, 1),
(363, 216, 2),
(364, 217, 1),
(365, 217, 2),
(366, 218, 1),
(367, 218, 2),
(368, 219, 1),
(369, 219, 2),
(370, 220, 1),
(371, 220, 2),
(372, 221, 1),
(373, 221, 2),
(374, 222, 1),
(375, 222, 2),
(376, 223, 1),
(377, 223, 2),
(378, 224, 1),
(379, 224, 2),
(380, 225, 1),
(381, 225, 2),
(382, 226, 1),
(383, 226, 2),
(384, 227, 1),
(385, 227, 2),
(386, 228, 1),
(387, 228, 2),
(388, 229, 1),
(389, 229, 2),
(390, 230, 1),
(391, 230, 2),
(392, 231, 1),
(393, 231, 2),
(394, 232, 1),
(395, 232, 2),
(396, 233, 1),
(397, 233, 2),
(398, 234, 1),
(399, 234, 2),
(400, 235, 1),
(401, 235, 2),
(402, 236, 1),
(403, 236, 2),
(404, 237, 1),
(405, 237, 2),
(406, 238, 1),
(407, 238, 2),
(408, 239, 1),
(409, 239, 2),
(410, 240, 1),
(411, 240, 2),
(412, 241, 1),
(413, 241, 2),
(414, 242, 1),
(415, 242, 2),
(416, 243, 1),
(417, 243, 2),
(418, 244, 1),
(419, 244, 2),
(420, 245, 1),
(421, 245, 2),
(422, 246, 1),
(423, 246, 2),
(424, 247, 1),
(425, 247, 2),
(426, 248, 1),
(427, 248, 2),
(428, 249, 1),
(429, 249, 2),
(430, 250, 1),
(431, 250, 2),
(432, 251, 1),
(433, 251, 2),
(434, 252, 1),
(435, 252, 2),
(436, 253, 1),
(437, 253, 2),
(438, 254, 1),
(439, 254, 2),
(440, 255, 1),
(441, 255, 2),
(442, 256, 1),
(443, 256, 2),
(444, 257, 1),
(445, 257, 2),
(446, 258, 1),
(447, 258, 2),
(448, 259, 1),
(449, 259, 2),
(450, 260, 1),
(451, 260, 2),
(452, 261, 1),
(453, 261, 2),
(454, 262, 1),
(455, 262, 2),
(456, 263, 1),
(457, 263, 2),
(458, 264, 1),
(459, 264, 2),
(460, 265, 1),
(461, 265, 2),
(462, 266, 1),
(463, 266, 2),
(464, 267, 1),
(465, 267, 2),
(466, 268, 1),
(467, 268, 2),
(468, 269, 1),
(469, 269, 2),
(470, 270, 1),
(471, 270, 2),
(472, 271, 1),
(473, 271, 2),
(474, 272, 1),
(475, 272, 2),
(476, 273, 1),
(477, 273, 2),
(478, 274, 1),
(479, 274, 2),
(480, 275, 1),
(481, 275, 2),
(482, 276, 1),
(483, 276, 2),
(484, 277, 1),
(485, 277, 2),
(486, 278, 1),
(487, 278, 2),
(488, 279, 1),
(489, 279, 2),
(490, 280, 1),
(491, 280, 2),
(492, 281, 1),
(493, 281, 2),
(494, 282, 1),
(495, 282, 2),
(496, 283, 1),
(497, 283, 2),
(498, 284, 1),
(499, 284, 2),
(500, 285, 1),
(501, 285, 2),
(502, 286, 1),
(503, 286, 2),
(504, 287, 1),
(505, 287, 2),
(506, 288, 1),
(507, 288, 2),
(508, 289, 1),
(509, 289, 2),
(510, 290, 1),
(511, 290, 2),
(512, 291, 1),
(513, 291, 2),
(514, 292, 1),
(515, 292, 2),
(516, 293, 1),
(517, 293, 2),
(518, 294, 1),
(519, 294, 2),
(520, 295, 1),
(521, 295, 2),
(522, 296, 1),
(523, 296, 2),
(524, 297, 1),
(525, 297, 2),
(526, 298, 1),
(527, 298, 2),
(528, 299, 1),
(529, 299, 2),
(530, 300, 1),
(531, 300, 2),
(532, 301, 1),
(533, 301, 2),
(534, 302, 1),
(535, 302, 2),
(536, 303, 1),
(537, 303, 2),
(538, 304, 1),
(539, 304, 2),
(540, 305, 1),
(541, 305, 2),
(542, 306, 1),
(543, 306, 2),
(544, 307, 1),
(545, 307, 2),
(546, 308, 1),
(547, 308, 2),
(548, 309, 1),
(549, 309, 2),
(550, 310, 1),
(551, 310, 2),
(552, 311, 1),
(553, 311, 2),
(554, 312, 1),
(555, 312, 2),
(556, 313, 1),
(557, 313, 2),
(558, 314, 1),
(559, 314, 2),
(560, 315, 1),
(561, 315, 2),
(562, 316, 1),
(563, 316, 2),
(564, 317, 1),
(565, 317, 2),
(566, 318, 1),
(567, 318, 2),
(568, 319, 1),
(569, 319, 2),
(570, 320, 1),
(571, 320, 2),
(572, 321, 1),
(573, 321, 2),
(574, 322, 1),
(575, 322, 2),
(576, 323, 1),
(577, 323, 2),
(578, 324, 1),
(579, 324, 2),
(580, 325, 1),
(581, 325, 2),
(582, 326, 1),
(583, 326, 2);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id_service` bigint UNSIGNED NOT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_store` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id_service`, `service_name`, `description`, `price`, `image_path`, `id_store`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Short Hair Cut', NULL, '35000.00', NULL, 2, NULL, '2025-11-02 02:16:15', '2025-11-02 02:16:15'),
(2, 'Long Hair Cut', NULL, '50000.00', NULL, 2, NULL, '2026-02-10 15:12:02', '2026-02-10 15:12:02'),
(3, 'Basic Haircut', NULL, '15000.00', NULL, 3, NULL, '2026-03-04 14:03:36', '2026-03-04 14:03:36'),
(4, 'Short Hair Cut', NULL, '30000.00', NULL, 3, NULL, '2026-03-04 14:05:06', '2026-03-04 14:05:06');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('faVXjEwVWcy8O1hHWs2dEoTSizZIOm1n7fU9yuIH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOE1qNmE0UHUwMUN4Z3ZuQzZtbFRJMGowVHJ2dHhzYzZKRWU3ZGpTciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9jZWstcGVzYW5hbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1772702732),
('SB7tkYkMrlzkGo9SLpWr2UMXhycMYKZBsiOYja3q', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2lyQ1YyVTlwbnlyTEJDRkZoSVFTVE51M0lJbWVOaURHWjJBUVExSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1772695844);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id_store` bigint UNSIGNED NOT NULL,
  `store_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `store_ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_ip_validation` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id_store`, `store_name`, `address`, `phone_number`, `is_active`, `store_ip_address`, `enable_ip_validation`, `created_at`, `updated_at`) VALUES
(1, 'Office', 'Jl. Tlogo Poso Ruko No. 7', '+62 823-2870-1038', 1, NULL, 0, '2025-11-02 01:40:09', '2025-11-02 01:40:09'),
(2, 'Syuhada', 'Jl. Syuhada Raya No.8A', '+62 882-1662-6044', 1, '103.47.133.100, 192.168.100.88, 192.168.100.5, 127.0.0.1', 1, '2025-11-02 01:40:09', '2025-11-18 17:00:16'),
(3, 'Sedayu', 'Jl. Sedayu Tugu No.68', '+62 882-1662-6044', 1, NULL, 0, '2025-11-02 01:40:09', '2025-11-02 01:40:09'),
(4, 'Andre', '-', NULL, 1, '103.47.133.100, 192.168.100.88', 1, '2025-11-03 09:09:08', '2025-11-05 09:32:34');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id_transaction` bigint UNSIGNED NOT NULL,
  `id_store` bigint UNSIGNED NOT NULL,
  `id_employee_primary` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `id_payment_method` bigint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `amount_paid` decimal(15,2) DEFAULT NULL,
  `change_amount` decimal(15,2) DEFAULT NULL,
  `tips` decimal(15,2) DEFAULT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id_transaction`, `id_store`, `id_employee_primary`, `id_user`, `id_payment_method`, `status`, `order_id`, `total_amount`, `amount_paid`, `change_amount`, `tips`, `transaction_date`, `notes`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 2, 1, 'paid', NULL, '53000.00', '100000.00', '47000.00', '0.00', '2025-11-02 02:20:54', NULL, NULL, '2025-11-02 02:20:54', '2025-11-02 02:20:54'),
(2, 2, 1, 2, 2, 'paid', NULL, '88000.00', '0.00', '0.00', '0.00', '2025-11-02 02:21:17', NULL, '2025-11-02 02:22:09', '2025-11-02 02:21:17', '2025-11-02 02:22:09'),
(3, 2, 1, 1, 1, 'paid', NULL, '35000.00', '50000.00', '15000.00', '0.00', '2025-11-13 11:21:37', NULL, NULL, '2025-11-13 11:21:37', '2025-11-13 11:21:37'),
(4, 2, 2, 1, 2, 'paid', NULL, '50000.00', '0.00', '0.00', '0.00', '2025-11-13 14:55:31', NULL, NULL, '2025-11-13 14:55:31', '2025-11-13 14:55:31'),
(5, 2, 1, 1, 2, 'paid', NULL, '35000.00', '0.00', '0.00', '0.00', '2025-11-13 18:02:08', NULL, NULL, '2025-11-13 18:02:08', '2025-11-13 18:02:08'),
(6, 2, 1, 1, 2, 'paid', NULL, '50000.00', '0.00', '0.00', '0.00', '2025-11-13 18:03:31', NULL, NULL, '2025-11-13 18:03:31', '2025-11-13 18:03:31'),
(7, 2, 2, 1, 2, 'paid', NULL, '50000.00', '0.00', '0.00', '0.00', '2025-11-13 18:07:04', NULL, NULL, '2025-11-13 18:07:04', '2025-11-13 18:07:04'),
(8, 2, 2, 1, 2, 'paid', NULL, '50000.00', '0.00', '0.00', '0.00', '2025-11-13 18:45:32', NULL, NULL, '2025-11-13 18:45:32', '2025-11-13 18:45:32'),
(9, 2, 2, 1, 2, 'paid', NULL, '35000.00', '0.00', '0.00', '0.00', '2025-11-13 18:57:38', NULL, NULL, '2025-11-13 18:57:38', '2025-11-13 18:57:38'),
(10, 2, 1, 2, 2, 'paid', NULL, '35000.00', '0.00', '0.00', '0.00', '2025-11-16 15:58:45', NULL, NULL, '2025-11-16 15:58:45', '2025-11-16 15:58:45'),
(11, 2, 2, 1, 2, 'paid', NULL, '35000.00', '0.00', '0.00', '0.00', '2025-11-17 03:17:29', NULL, NULL, '2025-11-17 03:17:29', '2025-11-17 03:17:29'),
(12, 2, 1, 1, 2, 'paid', NULL, '35000.00', '0.00', '0.00', '0.00', '2025-11-17 03:22:56', NULL, NULL, '2025-11-17 03:22:56', '2025-11-17 03:22:56'),
(13, 2, 2, 1, 2, 'paid', NULL, '50000.00', '50000.00', '0.00', '0.00', '2025-11-18 14:34:39', NULL, NULL, '2025-11-18 14:34:39', '2025-11-18 14:34:39'),
(14, 2, 2, 1, 4, 'paid', NULL, '35000.00', '0.00', '0.00', '0.00', '2025-11-18 19:30:06', NULL, NULL, '2025-11-18 19:30:06', '2025-11-18 19:30:06'),
(15, 2, 2, 1, 2, 'paid', NULL, '35000.00', '0.00', '0.00', '0.00', '2025-11-21 06:11:51', NULL, NULL, '2025-11-21 06:11:51', '2025-11-21 06:11:51'),
(16, 2, 2, 1, 2, 'paid', NULL, '50000.00', '0.00', '0.00', '0.00', '2025-11-29 13:20:48', NULL, NULL, '2025-11-29 13:20:48', '2025-11-29 13:20:48'),
(17, 2, 2, 1, 2, 'paid', NULL, '50000.00', '0.00', '0.00', '0.00', '2025-12-01 05:48:15', NULL, NULL, '2025-12-01 05:48:15', '2025-12-01 05:48:15'),
(18, 2, 2, 1, 1, 'paid', NULL, '85000.00', '100000.00', '15000.00', '0.00', '2025-12-01 07:11:51', NULL, NULL, '2025-12-01 07:11:51', '2025-12-01 07:11:51'),
(19, 2, 2, 1, 1, 'paid', NULL, '100000.00', '100000.00', '0.00', '0.00', '2025-12-01 07:13:46', NULL, NULL, '2025-12-01 07:13:46', '2025-12-01 07:13:46'),
(20, 2, 2, 1, 2, 'paid', 'TANGWIN-2-1770736450', '105000.00', '0.00', '0.00', '0.00', '2026-02-10 15:14:49', NULL, NULL, '2026-02-10 15:14:49', '2026-02-10 15:14:49'),
(21, 2, 2, 1, 2, 'paid', 'TANGWIN-2-1771487079', '92000.00', '100000.00', '0.00', '0.00', '2026-02-19 07:45:02', NULL, NULL, '2026-02-19 07:45:02', '2026-02-19 07:45:02'),
(22, 2, 2, 1, 2, 'paid', 'TANGWIN-2-1772179064', '50000.00', '0.00', '0.00', '0.00', '2026-02-27 07:58:03', NULL, NULL, '2026-02-27 07:58:03', '2026-02-27 07:58:03'),
(23, 2, 2, 2, 2, 'paid', 'TANGWIN-2-1772367778', '55000.00', '100000.00', '0.00', '0.00', '2026-03-01 12:23:20', NULL, NULL, '2026-03-01 12:23:20', '2026-03-01 12:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id_transaction_detail` bigint UNSIGNED NOT NULL,
  `id_transaction` bigint UNSIGNED NOT NULL,
  `id_employee` bigint UNSIGNED DEFAULT NULL,
  `item_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_service` bigint UNSIGNED DEFAULT NULL,
  `id_product` bigint UNSIGNED DEFAULT NULL,
  `id_food` bigint UNSIGNED DEFAULT NULL,
  `quantity` int NOT NULL,
  `price_at_sale` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id_transaction_detail`, `id_transaction`, `id_employee`, `item_type`, `id_service`, `id_product`, `id_food`, `quantity`, `price_at_sale`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-02 02:20:54', '2025-11-02 02:20:54'),
(2, 1, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2025-11-02 02:20:54', '2025-11-02 02:20:54'),
(3, 1, NULL, 'food', NULL, NULL, 1, 1, '3000.00', '3000.00', '2025-11-02 02:20:54', '2025-11-02 02:20:54'),
(8, 3, 1, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-13 11:21:37', '2025-11-13 11:21:37'),
(9, 4, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-13 14:55:31', '2025-11-13 14:55:31'),
(10, 4, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2025-11-13 14:55:31', '2025-11-13 14:55:31'),
(11, 5, 1, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-13 18:02:08', '2025-11-13 18:02:08'),
(12, 6, 1, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-13 18:03:31', '2025-11-13 18:03:31'),
(13, 6, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2025-11-13 18:03:31', '2025-11-13 18:03:31'),
(14, 7, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-13 18:07:04', '2025-11-13 18:07:04'),
(15, 7, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2025-11-13 18:07:04', '2025-11-13 18:07:04'),
(16, 8, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-13 18:45:32', '2025-11-13 18:45:32'),
(17, 8, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2025-11-13 18:45:32', '2025-11-13 18:45:32'),
(18, 9, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-13 18:57:38', '2025-11-13 18:57:38'),
(19, 10, 1, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-16 15:58:45', '2025-11-16 15:58:45'),
(20, 11, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-17 03:17:29', '2025-11-17 03:17:29'),
(21, 12, 1, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-17 03:22:56', '2025-11-17 03:22:56'),
(22, 13, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-18 14:34:39', '2025-11-18 14:34:39'),
(23, 13, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2025-11-18 14:34:39', '2025-11-18 14:34:39'),
(24, 14, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-18 19:30:06', '2025-11-18 19:30:06'),
(25, 15, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-21 06:11:51', '2025-11-21 06:11:51'),
(26, 16, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-11-29 13:20:48', '2025-11-29 13:20:48'),
(27, 16, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2025-11-29 13:20:48', '2025-11-29 13:20:48'),
(28, 17, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-12-01 05:48:15', '2025-12-01 05:48:15'),
(29, 17, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2025-12-01 05:48:15', '2025-12-01 05:48:15'),
(30, 18, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-12-01 07:11:51', '2025-12-01 07:11:51'),
(31, 18, 1, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-12-01 07:11:51', '2025-12-01 07:11:51'),
(32, 18, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2025-12-01 07:11:51', '2025-12-01 07:11:51'),
(33, 19, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-12-01 07:13:46', '2025-12-01 07:13:46'),
(34, 19, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2025-12-01 07:13:46', '2025-12-01 07:13:46'),
(35, 19, NULL, 'product', NULL, 1, NULL, 2, '15000.00', '30000.00', '2025-12-01 07:13:46', '2025-12-01 07:13:46'),
(36, 20, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2026-02-10 15:14:49', '2026-02-10 15:14:49'),
(37, 20, 1, 'service', 2, NULL, NULL, 1, '50000.00', '50000.00', '2026-02-10 15:14:49', '2026-02-10 15:14:49'),
(38, 20, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2026-02-10 15:14:49', '2026-02-10 15:14:49'),
(39, 20, NULL, 'food', NULL, NULL, 2, 1, '5000.00', '5000.00', '2026-02-10 15:14:49', '2026-02-10 15:14:49'),
(40, 21, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2026-02-19 07:45:02', '2026-02-19 07:45:02'),
(41, 21, 1, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2026-02-19 07:45:02', '2026-02-19 07:45:02'),
(42, 21, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2026-02-19 07:45:02', '2026-02-19 07:45:02'),
(43, 21, NULL, 'food', NULL, NULL, 2, 1, '5000.00', '5000.00', '2026-02-19 07:45:02', '2026-02-19 07:45:02'),
(44, 21, NULL, 'food', NULL, NULL, 3, 1, '2000.00', '2000.00', '2026-02-19 07:45:02', '2026-02-19 07:45:02'),
(45, 22, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2026-02-27 07:58:03', '2026-02-27 07:58:03'),
(46, 22, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2026-02-27 07:58:03', '2026-02-27 07:58:03'),
(47, 23, 2, 'service', 1, NULL, NULL, 1, '35000.00', '35000.00', '2026-03-01 12:23:20', '2026-03-01 12:23:20'),
(48, 23, NULL, 'product', NULL, 1, NULL, 1, '15000.00', '15000.00', '2026-03-01 12:23:20', '2026-03-01 12:23:20'),
(49, 23, NULL, 'food', NULL, NULL, 2, 1, '5000.00', '5000.00', '2026-03-01 12:23:20', '2026-03-01 12:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kasir',
  `id_store` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `id_store`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, '$2y$12$QeTmv29BCcFDW9xg2XHrXO57whkwIgRPTe8KmlYrB3NdcJdRvjthm', 'admin', 1, 1, 'ejuFRFtdfBUup81FwlWiUegO5TjSQbqSHASZXHGLVB4seQaPCv9QmBD4bz8P', '2025-11-02 01:40:09', '2025-11-02 01:40:09'),
(2, 'Kasir 1', 'kasir1@gmail.com', NULL, '$2y$12$EIELZ.lwz15YPdWn/fkSF.ywBQGYLwuGIr6qAks5kZWN/1dET.0nm', 'kasir', 2, 1, 'XgYtwtiQfUTAQpVMRizTiswijtJAHvi5rRdpFax8GncUmpGaeXBa3H8v9eYk', '2025-11-02 01:40:09', '2025-11-02 01:40:09'),
(3, 'Kasir 2', 'kasir2@gmail.com', NULL, '$2y$12$ZzrfK/vSZq9I.k74yYFElOyIHMf6OzwLOhJRI38UhCgkl2u6jP.EW', 'kasir', 3, 1, NULL, '2025-11-02 01:40:10', '2025-11-02 01:40:10'),
(4, 'Test Admin', 'testadmin@tangwin.com', NULL, '$2y$12$arDWuGKknV2v0uohmP/BWezRGvzcFqmSFLMFyeD1SQ2jV0wOfq4HS', 'kasir', NULL, 1, NULL, '2026-03-02 08:24:43', '2026-03-02 08:24:43'),
(5, 'Admin Test', 'admin@tangwin.com', NULL, '$2y$12$Eomn.QW1/Kxf0BNonmKyuutCdFCo54.uo.mFSLENXmmV00t7EMbX2', 'kasir', NULL, 1, NULL, '2026-03-02 08:26:46', '2026-03-02 08:26:46');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id_employee`),
  ADD KEY `employees_id_store_foreign` (`id_store`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id_expense`),
  ADD KEY `expenses_id_employee_foreign` (`id_employee`),
  ADD KEY `expenses_id_store_foreign` (`id_store`),
  ADD KEY `expenses_id_user_foreign` (`id_user`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id_food`),
  ADD KEY `foods_id_store_foreign` (`id_store`);

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
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id_payment_method`),
  ADD UNIQUE KEY `payment_methods_method_name_unique` (`method_name`);

--
-- Indexes for table `presence_logs`
--
ALTER TABLE `presence_logs`
  ADD PRIMARY KEY (`id_presence_log`),
  ADD KEY `presence_logs_id_employee_foreign` (`id_employee`),
  ADD KEY `presence_logs_id_store_foreign` (`id_store`),
  ADD KEY `presence_logs_id_presence_schedule_foreign` (`id_presence_schedule`);

--
-- Indexes for table `presence_schedules`
--
ALTER TABLE `presence_schedules`
  ADD PRIMARY KEY (`id_presence_schedule`),
  ADD KEY `id_store` (`id_store`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `products_id_store_foreign` (`id_store`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `reservations_id_store_foreign` (`id_store`),
  ADD KEY `reservations_id_service_foreign` (`id_service`),
  ADD KEY `reservations_id_employee_foreign` (`id_employee`);

--
-- Indexes for table `reservation_slots`
--
ALTER TABLE `reservation_slots`
  ADD PRIMARY KEY (`id_slot`),
  ADD KEY `reservation_slots_id_store_foreign` (`id_store`);

--
-- Indexes for table `reservation_slot_employee`
--
ALTER TABLE `reservation_slot_employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reservation_slot_employee_id_slot_id_employee_unique` (`id_slot`,`id_employee`),
  ADD KEY `reservation_slot_employee_id_employee_foreign` (`id_employee`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id_service`),
  ADD KEY `services_id_store_foreign` (`id_store`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id_store`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id_transaction`),
  ADD UNIQUE KEY `transactions_order_id_unique` (`order_id`),
  ADD KEY `transactions_id_store_foreign` (`id_store`),
  ADD KEY `transactions_id_employee_primary_foreign` (`id_employee_primary`),
  ADD KEY `transactions_id_user_foreign` (`id_user`),
  ADD KEY `transactions_id_payment_method_foreign` (`id_payment_method`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id_transaction_detail`),
  ADD KEY `transaction_details_id_transaction_foreign` (`id_transaction`),
  ADD KEY `transaction_details_id_service_foreign` (`id_service`),
  ADD KEY `transaction_details_id_product_foreign` (`id_product`),
  ADD KEY `transaction_details_id_food_foreign` (`id_food`),
  ADD KEY `transaction_details_id_employee_foreign` (`id_employee`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_id_store_foreign` (`id_store`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id_employee` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id_expense` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `id_food` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id_payment_method` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `presence_logs`
--
ALTER TABLE `presence_logs`
  MODIFY `id_presence_log` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `presence_schedules`
--
ALTER TABLE `presence_schedules`
  MODIFY `id_presence_schedule` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id_product` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `reservation_slots`
--
ALTER TABLE `reservation_slots`
  MODIFY `id_slot` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=327;

--
-- AUTO_INCREMENT for table `reservation_slot_employee`
--
ALTER TABLE `reservation_slot_employee`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=584;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id_service` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id_store` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id_transaction` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id_transaction_detail` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_id_store_foreign` FOREIGN KEY (`id_store`) REFERENCES `stores` (`id_store`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_id_employee_foreign` FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id_employee`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_id_store_foreign` FOREIGN KEY (`id_store`) REFERENCES `stores` (`id_store`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `foods`
--
ALTER TABLE `foods`
  ADD CONSTRAINT `foods_id_store_foreign` FOREIGN KEY (`id_store`) REFERENCES `stores` (`id_store`) ON DELETE CASCADE;

--
-- Constraints for table `presence_logs`
--
ALTER TABLE `presence_logs`
  ADD CONSTRAINT `presence_logs_id_employee_foreign` FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id_employee`) ON DELETE CASCADE,
  ADD CONSTRAINT `presence_logs_id_presence_schedule_foreign` FOREIGN KEY (`id_presence_schedule`) REFERENCES `presence_schedules` (`id_presence_schedule`) ON DELETE SET NULL,
  ADD CONSTRAINT `presence_logs_id_store_foreign` FOREIGN KEY (`id_store`) REFERENCES `stores` (`id_store`) ON DELETE CASCADE;

--
-- Constraints for table `presence_schedules`
--
ALTER TABLE `presence_schedules`
  ADD CONSTRAINT `presence_schedules_ibfk_1` FOREIGN KEY (`id_store`) REFERENCES `stores` (`id_store`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_id_store_foreign` FOREIGN KEY (`id_store`) REFERENCES `stores` (`id_store`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_id_employee_foreign` FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id_employee`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservations_id_service_foreign` FOREIGN KEY (`id_service`) REFERENCES `services` (`id_service`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservations_id_store_foreign` FOREIGN KEY (`id_store`) REFERENCES `stores` (`id_store`) ON DELETE CASCADE;

--
-- Constraints for table `reservation_slots`
--
ALTER TABLE `reservation_slots`
  ADD CONSTRAINT `reservation_slots_id_store_foreign` FOREIGN KEY (`id_store`) REFERENCES `stores` (`id_store`) ON DELETE CASCADE;

--
-- Constraints for table `reservation_slot_employee`
--
ALTER TABLE `reservation_slot_employee`
  ADD CONSTRAINT `reservation_slot_employee_id_employee_foreign` FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id_employee`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_slot_employee_id_slot_foreign` FOREIGN KEY (`id_slot`) REFERENCES `reservation_slots` (`id_slot`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_id_store_foreign` FOREIGN KEY (`id_store`) REFERENCES `stores` (`id_store`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_id_employee_primary_foreign` FOREIGN KEY (`id_employee_primary`) REFERENCES `employees` (`id_employee`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_id_payment_method_foreign` FOREIGN KEY (`id_payment_method`) REFERENCES `payment_methods` (`id_payment_method`),
  ADD CONSTRAINT `transactions_id_store_foreign` FOREIGN KEY (`id_store`) REFERENCES `stores` (`id_store`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_id_employee_foreign` FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id_employee`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaction_details_id_food_foreign` FOREIGN KEY (`id_food`) REFERENCES `foods` (`id_food`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_id_product_foreign` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_id_service_foreign` FOREIGN KEY (`id_service`) REFERENCES `services` (`id_service`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_id_transaction_foreign` FOREIGN KEY (`id_transaction`) REFERENCES `transactions` (`id_transaction`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_store_foreign` FOREIGN KEY (`id_store`) REFERENCES `stores` (`id_store`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
