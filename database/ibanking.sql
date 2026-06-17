-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2026 at 07:55 AM
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
-- Database: `ibanking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'ADMIN',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `mobile_code` varchar(10) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip_postal` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `device_info` text DEFAULT NULL,
  `last_logged_in` timestamp NULL DEFAULT NULL,
  `last_logged_out` timestamp NULL DEFAULT NULL,
  `login_status` tinyint(1) NOT NULL DEFAULT 0,
  `notification_clear_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `firstname`, `lastname`, `username`, `user_type`, `email`, `password`, `image`, `email_verified_at`, `remember_token`, `mobile_code`, `phone`, `country`, `city`, `state`, `zip_postal`, `address`, `device_id`, `status`, `device_info`, `last_logged_in`, `last_logged_out`, `login_status`, `notification_clear_at`, `created_at`, `updated_at`) VALUES
(1, 'Super', 'Admin', 'superadmin', 'ADMIN', 'maduekegideon46@gmail.com', '$2y$10$xPNCv5KE0NG/815BGujRGuD6Eug0gf5lOMSkX5qN9bVVqMWj.8qJG', '52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-13 10:32:33', NULL, 1, NULL, '2026-03-10 02:25:52', '2026-03-13 10:32:34'),
(4, 'Editor', 'Admin', 'editoradmin', 'ADMIN', 'editoradmin@appdevs.net', '$2y$10$rbN7YoveETvQ5AnkmycMd..g749nTXkvvLe2.jHulZTBdfQFQCD76', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, '2026-03-10 02:25:55', NULL),
(5, 'Moderator', 'Admin', 'moderatoradmin', 'ADMIN', 'moderatoradmin@appdevs.net', '$2y$10$hhmaBd/L..yeJRppM7sbWO4xJqZpnsX1ILgrJiDzFPLeQ.IJNKmFO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, '2026-03-10 02:25:57', NULL),
(6, 'Super', 'Admins', 'admin', 'ADMIN', 'admin@site.com', '$2y$12$UKztlQTlHyg6guRlPlWBfemCsJzw7hdgnbBtL3Sn1S8YstEo7T8Ta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, '2026-03-13 04:00:34', '2026-03-13 04:00:34'),
(7, 'Admin', 'One', 'admin1', 'ADMIN', 'admin1@example.com', 'password123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, NULL, '2026-03-13 04:00:34', '2026-03-13 04:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `admin_has_roles`
--

CREATE TABLE `admin_has_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `admin_role_id` bigint(20) UNSIGNED NOT NULL,
  `last_edit_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_has_roles`
--

INSERT INTO `admin_has_roles` (`id`, `admin_id`, `admin_role_id`, `last_edit_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_logs`
--

CREATE TABLE `admin_login_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `mac` varchar(17) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `browser` varchar(30) DEFAULT NULL,
  `os` varchar(30) DEFAULT NULL,
  `timezone` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_login_logs`
--

INSERT INTO `admin_login_logs` (`id`, `admin_id`, `ip`, `mac`, `city`, `country`, `longitude`, `latitude`, `browser`, `os`, `timezone`, `created_at`, `updated_at`) VALUES
(1, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-10 03:14:48', '2026-03-10 03:14:48'),
(2, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-10 03:36:37', '2026-03-10 03:36:37'),
(3, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-10 07:17:38', '2026-03-10 07:17:38'),
(4, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Edge', 'Windows', 'America/New_York', '2026-03-11 08:58:46', '2026-03-11 08:58:46'),
(5, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-11 18:29:22', '2026-03-11 18:29:22'),
(6, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-11 20:41:44', '2026-03-11 20:41:44'),
(7, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-11 21:01:03', '2026-03-11 21:01:03'),
(8, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Edge', 'Windows', 'America/New_York', '2026-03-12 04:14:06', '2026-03-12 04:14:06'),
(9, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-12 04:17:28', '2026-03-12 04:17:28'),
(10, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-12 04:35:15', '2026-03-12 04:35:15'),
(11, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-12 04:36:20', '2026-03-12 04:36:20'),
(12, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Edge', 'Windows', 'America/New_York', '2026-03-12 06:33:49', '2026-03-12 06:33:49'),
(13, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Edge', 'Windows', 'America/New_York', '2026-03-12 08:47:57', '2026-03-12 08:47:57'),
(14, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Edge', 'Windows', 'America/New_York', '2026-03-12 09:46:15', '2026-03-12 09:46:15'),
(15, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Edge', 'Windows', 'America/New_York', '2026-03-13 08:12:10', '2026-03-13 08:12:10'),
(16, 1, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-13 10:32:31', '2026-03-13 10:32:31');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_notifications`
--

INSERT INTO `admin_notifications` (`id`, `admin_id`, `type`, `message`, `created_at`, `updated_at`) VALUES
(1, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/default\\/default.webp\"}', '2026-03-10 03:14:48', '2026-03-10 03:14:48'),
(2, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/default\\/default.webp\"}', '2026-03-10 03:36:37', '2026-03-10 03:36:37'),
(3, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/default\\/default.webp\"}', '2026-03-10 07:17:38', '2026-03-10 07:17:38'),
(4, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-11 08:58:46', '2026-03-11 08:58:46'),
(5, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-11 18:29:22', '2026-03-11 18:29:22'),
(6, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-11 20:41:44', '2026-03-11 20:41:44'),
(7, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-11 21:01:03', '2026-03-11 21:01:03'),
(8, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-12 04:14:06', '2026-03-12 04:14:06'),
(9, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-12 04:17:28', '2026-03-12 04:17:28'),
(10, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-12 04:35:15', '2026-03-12 04:35:15'),
(11, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-12 04:36:20', '2026-03-12 04:36:20'),
(12, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-12 06:33:50', '2026-03-12 06:33:50'),
(13, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-12 08:47:57', '2026-03-12 08:47:57'),
(14, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-12 09:46:15', '2026-03-12 09:46:15'),
(15, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-13 08:12:10', '2026-03-13 08:12:10'),
(16, 1, 'SIDE_NAV', '{\"title\":\"Super Admin(superadmin) logged in.\",\"time\":\"1 second ago\",\"image\":\"http:\\/\\/localhost\\/iBanking%20v1.0\\/iBanking%20v1.0\\/main-files\\/ibanking-web\\/ibanking_web_v1.0.0\\/public\\/backend\\/images\\/admin\\/profile\\/52ee0317-5ac6-46da-b01b-5c3532b26fd4.webp\"}', '2026-03-13 10:32:32', '2026-03-13 10:32:32');

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

CREATE TABLE `admin_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `admin_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Super Admin', 1, '2026-03-10 02:25:57', '2026-03-10 02:25:57'),
(2, 1, 'Sub Admin', 1, '2026-03-10 02:25:57', '2026-03-10 02:25:57'),
(3, 1, 'Support Member', 1, '2026-03-10 02:25:57', '2026-03-10 02:25:57'),
(4, 1, 'Editor', 1, '2026-03-10 02:25:57', '2026-03-10 02:25:57'),
(5, 1, 'Moderator', 1, '2026-03-10 02:25:57', '2026-03-10 02:25:57');

-- --------------------------------------------------------

--
-- Table structure for table `admin_role_has_permissions`
--

CREATE TABLE `admin_role_has_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_role_permission_id` bigint(20) UNSIGNED NOT NULL,
  `route` varchar(100) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `last_edit_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_role_permissions`
--

CREATE TABLE `admin_role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_role_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `announcement_category_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(191) NOT NULL,
  `data` longtext NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `announcement_category_id`, `slug`, `data`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'unveiling-the-future-of-banking-dive-into-the-world-of-ibanking', '{\"language\":{\"en\":{\"title\":\"Personalized Wealth Management: How iBanking Tailors Solutions to Your Financial Goals\",\"description\":\"<p>Managing wealth is not a one-size-fits-all process, which is why iBanking offers personalized wealth management services designed to fit your unique financial needs. Whether you\\u2019re planning for retirement, making investment decisions, or looking to protect your assets, iBanking\\u2019s expert financial tools help you build a strategy that aligns with your goals. Explore how our platform provides you with insightful advice and the tools you need to navigate your financial future with confidence. Take control of your wealth management today, and let us help you achieve your financial dreams.<\\/p>\",\"tags\":[\"Account Settings\"]},\"es\":{\"title\":\"Gesti\\u00f3n patrimonial personalizada: c\\u00f3mo iBanking adapta sus soluciones a sus objetivos financieros\",\"description\":\"<p>La gesti\\u00f3n de patrimonio no es un proceso \\u00fanico para todos, por eso iBanking ofrece servicios de gesti\\u00f3n de patrimonio personalizados dise\\u00f1ados para adaptarse a sus necesidades financieras espec\\u00edficas. Ya sea que est\\u00e9 planificando su jubilaci\\u00f3n, tomando decisiones de inversi\\u00f3n o buscando proteger sus activos, las herramientas financieras especializadas de iBanking lo ayudan a desarrollar una estrategia que se alinee con sus objetivos. Descubra c\\u00f3mo nuestra plataforma le brinda asesoramiento \\u00fatil y las herramientas que necesita para navegar por su futuro financiero con confianza. Tome el control de la gesti\\u00f3n de su patrimonio hoy mismo y perm\\u00edtanos ayudarlo a alcanzar sus sue\\u00f1os financieros.<\\/p>\",\"tags\":[\"Configuraciones de la cuenta\"]},\"ar\":{\"title\":\"\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u062b\\u0631\\u0648\\u0627\\u062a \\u0627\\u0644\\u0634\\u062e\\u0635\\u064a\\u0629: \\u0643\\u064a\\u0641 \\u062a\\u0648\\u0641\\u0631 \\u0644\\u0643 iBanking \\u062d\\u0644\\u0648\\u0644\\u0627\\u064b \\u062a\\u062a\\u0646\\u0627\\u0633\\u0628 \\u0645\\u0639 \\u0623\\u0647\\u062f\\u0627\\u0641\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629\",\"description\":\"<p>\\u0625\\u0646 \\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u062b\\u0631\\u0648\\u0629 \\u0644\\u064a\\u0633\\u062a \\u0639\\u0645\\u0644\\u064a\\u0629 \\u0648\\u0627\\u062d\\u062f\\u0629 \\u062a\\u0646\\u0627\\u0633\\u0628 \\u0627\\u0644\\u062c\\u0645\\u064a\\u0639\\u060c \\u0648\\u0644\\u0647\\u0630\\u0627 \\u0627\\u0644\\u0633\\u0628\\u0628 \\u062a\\u0642\\u062f\\u0645 iBanking \\u062e\\u062f\\u0645\\u0627\\u062a \\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u062b\\u0631\\u0648\\u0629 \\u0627\\u0644\\u0645\\u062e\\u0635\\u0635\\u0629 \\u0648\\u0627\\u0644\\u0645\\u0635\\u0645\\u0645\\u0629 \\u0644\\u062a\\u0646\\u0627\\u0633\\u0628 \\u0627\\u062d\\u062a\\u064a\\u0627\\u062c\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0627\\u0644\\u0641\\u0631\\u064a\\u062f\\u0629. \\u0633\\u0648\\u0627\\u0621 \\u0643\\u0646\\u062a \\u062a\\u062e\\u0637\\u0637 \\u0644\\u0644\\u062a\\u0642\\u0627\\u0639\\u062f \\u0623\\u0648 \\u062a\\u062a\\u062e\\u0630 \\u0642\\u0631\\u0627\\u0631\\u0627\\u062a \\u0627\\u0633\\u062a\\u062b\\u0645\\u0627\\u0631\\u064a\\u0629 \\u0623\\u0648 \\u062a\\u062a\\u0637\\u0644\\u0639 \\u0625\\u0644\\u0649 \\u062d\\u0645\\u0627\\u064a\\u0629 \\u0623\\u0635\\u0648\\u0644\\u0643\\u060c \\u0641\\u0625\\u0646 \\u0627\\u0644\\u0623\\u062f\\u0648\\u0627\\u062a \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0627\\u0644\\u0645\\u062a\\u062e\\u0635\\u0635\\u0629 \\u0627\\u0644\\u062a\\u064a \\u062a\\u0642\\u062f\\u0645\\u0647\\u0627 iBanking \\u062a\\u0633\\u0627\\u0639\\u062f\\u0643 \\u0641\\u064a \\u0628\\u0646\\u0627\\u0621 \\u0627\\u0633\\u062a\\u0631\\u0627\\u062a\\u064a\\u062c\\u064a\\u0629 \\u062a\\u062a\\u0648\\u0627\\u0641\\u0642 \\u0645\\u0639 \\u0623\\u0647\\u062f\\u0627\\u0641\\u0643. \\u0627\\u0643\\u062a\\u0634\\u0641 \\u0643\\u064a\\u0641 \\u062a\\u0648\\u0641\\u0631 \\u0644\\u0643 \\u0645\\u0646\\u0635\\u062a\\u0646\\u0627 \\u0646\\u0635\\u0627\\u0626\\u062d \\u062b\\u0627\\u0642\\u0628\\u0629 \\u0648\\u0627\\u0644\\u0623\\u062f\\u0648\\u0627\\u062a \\u0627\\u0644\\u062a\\u064a \\u062a\\u062d\\u062a\\u0627\\u062c\\u0647\\u0627 \\u0644\\u0644\\u062a\\u0646\\u0642\\u0644 \\u0641\\u064a \\u0645\\u0633\\u062a\\u0642\\u0628\\u0644\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a \\u0628\\u062b\\u0642\\u0629. \\u062a\\u062d\\u0643\\u0645 \\u0641\\u064a \\u0625\\u062f\\u0627\\u0631\\u0629 \\u062b\\u0631\\u0648\\u062a\\u0643 \\u0627\\u0644\\u064a\\u0648\\u0645\\u060c \\u0648\\u062f\\u0639\\u0646\\u0627 \\u0646\\u0633\\u0627\\u0639\\u062f\\u0643 \\u0641\\u064a \\u062a\\u062d\\u0642\\u064a\\u0642 \\u0623\\u062d\\u0644\\u0627\\u0645\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629.<\\/p>\",\"tags\":[\"\\u0625\\u0639\\u062f\\u0627\\u062f\\u0627\\u062a \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\"]}},\"image\":\"bc7cf879-6c1d-4ec6-a168-5036b512d1c1.webp\"}', 1, '2024-10-18 15:53:46', '2024-12-07 15:57:29'),
(2, 4, 'regularly-review-statements', '{\"language\":{\"en\":{\"title\":\"Banking On the Go: iBanking\'s Mobile App Experience\",\"description\":\"<p>Banking has never been easier or more convenient. With the iBanking mobile app, you can manage your finances from anywhere, at any time. Transfer funds, pay bills, check account balances, and access your account statements\\u2014all from the palm of your hand. Our app is designed for ease of use, with a simple interface that makes mobile banking intuitive and efficient. Whether you\'re at home, at work, or on the go, iBanking ensures you have secure access to your financial world wherever you are.<\\/p>\",\"tags\":[\"Help Center\"]},\"es\":{\"title\":\"Banca m\\u00f3vil: la experiencia de la aplicaci\\u00f3n m\\u00f3vil de iBanking\",\"description\":\"<p>Realizar operaciones bancarias nunca ha sido tan f\\u00e1cil ni tan conveniente. Con la aplicaci\\u00f3n m\\u00f3vil iBanking, puede administrar sus finanzas desde cualquier lugar y en cualquier momento. Transfiera fondos, pague facturas, verifique los saldos de sus cuentas y acceda a sus estados de cuenta, todo desde la palma de su mano. Nuestra aplicaci\\u00f3n est\\u00e1 dise\\u00f1ada para que sea f\\u00e1cil de usar, con una interfaz simple que hace que la banca m\\u00f3vil sea intuitiva y eficiente. Ya sea que est\\u00e9 en su casa, en el trabajo o de viaje, iBanking le garantiza un acceso seguro a su mundo financiero dondequiera que est\\u00e9.<\\/p>\",\"tags\":[\"Centro de ayuda\"]},\"ar\":{\"title\":\"\\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0623\\u062b\\u0646\\u0627\\u0621 \\u0627\\u0644\\u062a\\u0646\\u0642\\u0644: \\u062a\\u062c\\u0631\\u0628\\u0629 \\u062a\\u0637\\u0628\\u064a\\u0642 iBanking \\u0644\\u0644\\u062c\\u0648\\u0627\\u0644\",\"description\":\"<p>\\u0644\\u0645 \\u062a\\u0643\\u0646 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0623\\u0633\\u0647\\u0644 \\u0623\\u0648 \\u0623\\u0643\\u062b\\u0631 \\u0645\\u0644\\u0627\\u0621\\u0645\\u0629 \\u0645\\u0646 \\u0623\\u064a \\u0648\\u0642\\u062a \\u0645\\u0636\\u0649. \\u0645\\u0639 \\u062a\\u0637\\u0628\\u064a\\u0642 iBanking \\u0644\\u0644\\u062c\\u0648\\u0627\\u0644\\u060c \\u064a\\u0645\\u0643\\u0646\\u0643 \\u0625\\u062f\\u0627\\u0631\\u0629 \\u0634\\u0624\\u0648\\u0646\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0645\\u0646 \\u0623\\u064a \\u0645\\u0643\\u0627\\u0646 \\u0648\\u0641\\u064a \\u0623\\u064a \\u0648\\u0642\\u062a. \\u064a\\u0645\\u0643\\u0646\\u0643 \\u062a\\u062d\\u0648\\u064a\\u0644 \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644 \\u0648\\u062f\\u0641\\u0639 \\u0627\\u0644\\u0641\\u0648\\u0627\\u062a\\u064a\\u0631 \\u0648\\u0627\\u0644\\u062a\\u062d\\u0642\\u0642 \\u0645\\u0646 \\u0623\\u0631\\u0635\\u062f\\u0629 \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\\u0627\\u062a \\u0648\\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u0649 \\u0643\\u0634\\u0648\\u0641 \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\\u0627\\u062a \\u0627\\u0644\\u062e\\u0627\\u0635\\u0629 \\u0628\\u0643 - \\u0643\\u0644 \\u0630\\u0644\\u0643 \\u0645\\u0646 \\u0631\\u0627\\u062d\\u0629 \\u064a\\u062f\\u0643. \\u062a\\u0645 \\u062a\\u0635\\u0645\\u064a\\u0645 \\u062a\\u0637\\u0628\\u064a\\u0642\\u0646\\u0627 \\u0644\\u0633\\u0647\\u0648\\u0644\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645\\u060c \\u0645\\u0639 \\u0648\\u0627\\u062c\\u0647\\u0629 \\u0628\\u0633\\u064a\\u0637\\u0629 \\u062a\\u062c\\u0639\\u0644 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0639\\u0628\\u0631 \\u0627\\u0644\\u062c\\u0648\\u0627\\u0644 \\u0628\\u062f\\u064a\\u0647\\u064a\\u0629 \\u0648\\u0641\\u0639\\u0627\\u0644\\u0629. \\u0633\\u0648\\u0627\\u0621 \\u0643\\u0646\\u062a \\u0641\\u064a \\u0627\\u0644\\u0645\\u0646\\u0632\\u0644 \\u0623\\u0648 \\u0641\\u064a \\u0627\\u0644\\u0639\\u0645\\u0644 \\u0623\\u0648 \\u0623\\u062b\\u0646\\u0627\\u0621 \\u0627\\u0644\\u062a\\u0646\\u0642\\u0644\\u060c \\u064a\\u0636\\u0645\\u0646 \\u0644\\u0643 \\u062a\\u0637\\u0628\\u064a\\u0642 iBanking \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0627\\u0644\\u0622\\u0645\\u0646 \\u0625\\u0644\\u0649 \\u0639\\u0627\\u0644\\u0645\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a \\u0623\\u064a\\u0646\\u0645\\u0627 \\u0643\\u0646\\u062a.<\\/p>\",\"tags\":[\"\\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u0629\"]}},\"image\":\"96a03704-5a64-432d-975d-958e0c6183b5.webp\"}', 1, '2024-10-18 15:55:25', '2024-12-07 15:55:50'),
(3, 1, 'enable-two-factor-authentication', '{\"language\":{\"en\":{\"title\":\"Strengthening Your Financial Security with iBanking\\u2019s Two-Factor Authentication (2FA)\",\"description\":\"<p>In today\\u2019s digital age, online banking security is more important than ever. iBanking takes your security seriously with Two-Factor Authentication (2FA). This added layer of protection ensures that even if someone gains access to your password, they won\\u2019t be able to access your account without a second verification step. Whether you\'re making a transfer or checking your balance, 2FA ensures peace of mind with every login. Learn how enabling 2FA keeps your financial data secure, giving you the ultimate control over your banking experience.<\\/p>\",\"tags\":[\"Two-Factor Authentication\"]},\"es\":{\"title\":\"Fortalezca su seguridad financiera con la autenticaci\\u00f3n de dos factores (2FA) de iBanking\",\"description\":\"<p>En la era digital actual, la seguridad de la banca en l\\u00ednea es m\\u00e1s importante que nunca. iBanking se toma en serio su seguridad con la autenticaci\\u00f3n de dos factores (2FA). Esta capa adicional de protecci\\u00f3n garantiza que, incluso si alguien obtiene acceso a su contrase\\u00f1a, no podr\\u00e1 acceder a su cuenta sin un segundo paso de verificaci\\u00f3n. Ya sea que est\\u00e9 realizando una transferencia o consultando su saldo, la 2FA le garantiza tranquilidad en cada inicio de sesi\\u00f3n. Descubra c\\u00f3mo habilitar la 2FA mantiene seguros sus datos financieros, lo que le brinda el m\\u00e1ximo control sobre su experiencia bancaria.<\\/p>\",\"tags\":[\"Autenticaci\\u00f3n de dos factores\"]},\"ar\":{\"title\":\"\\u062a\\u0639\\u0632\\u064a\\u0632 \\u0623\\u0645\\u0646\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0627\\u0644\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0627\\u0644\\u062b\\u0646\\u0627\\u0626\\u064a\\u0629 (2FA) \\u0645\\u0646 iBanking\",\"description\":\"<p>\\u0641\\u064a \\u0627\\u0644\\u0639\\u0635\\u0631 \\u0627\\u0644\\u0631\\u0642\\u0645\\u064a \\u0627\\u0644\\u062d\\u0627\\u0644\\u064a\\u060c \\u0623\\u0635\\u0628\\u062d\\u062a \\u0623\\u0645\\u0627\\u0646 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0639\\u0628\\u0631 \\u0627\\u0644\\u0625\\u0646\\u062a\\u0631\\u0646\\u062a \\u0623\\u0643\\u062b\\u0631 \\u0623\\u0647\\u0645\\u064a\\u0629 \\u0645\\u0646 \\u0623\\u064a \\u0648\\u0642\\u062a \\u0645\\u0636\\u0649. \\u062a\\u0623\\u062e\\u0630 iBanking \\u0623\\u0645\\u0627\\u0646\\u0643 \\u0639\\u0644\\u0649 \\u0645\\u062d\\u0645\\u0644 \\u0627\\u0644\\u062c\\u062f \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u0627\\u0644\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0627\\u0644\\u062b\\u0646\\u0627\\u0626\\u064a\\u0629 (2FA). \\u062a\\u0636\\u0645\\u0646 \\u0637\\u0628\\u0642\\u0629 \\u0627\\u0644\\u062d\\u0645\\u0627\\u064a\\u0629 \\u0627\\u0644\\u0625\\u0636\\u0627\\u0641\\u064a\\u0629 \\u0647\\u0630\\u0647 \\u0623\\u0646\\u0647 \\u062d\\u062a\\u0649 \\u0625\\u0630\\u0627 \\u062a\\u0645\\u0643\\u0646 \\u0634\\u062e\\u0635 \\u0645\\u0627 \\u0645\\u0646 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u0649 \\u0643\\u0644\\u0645\\u0629 \\u0645\\u0631\\u0648\\u0631\\u0643\\u060c \\u0641\\u0644\\u0646 \\u064a\\u062a\\u0645\\u0643\\u0646 \\u0645\\u0646 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u0649 \\u062d\\u0633\\u0627\\u0628\\u0643 \\u062f\\u0648\\u0646 \\u062e\\u0637\\u0648\\u0629 \\u062a\\u062d\\u0642\\u0642 \\u062b\\u0627\\u0646\\u064a\\u0629. \\u0633\\u0648\\u0627\\u0621 \\u0643\\u0646\\u062a \\u062a\\u0642\\u0648\\u0645 \\u0628\\u062a\\u062d\\u0648\\u064a\\u0644 \\u0623\\u0648 \\u0627\\u0644\\u062a\\u062d\\u0642\\u0642 \\u0645\\u0646 \\u0631\\u0635\\u064a\\u062f\\u0643\\u060c \\u0641\\u0625\\u0646 \\u0627\\u0644\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0627\\u0644\\u062b\\u0646\\u0627\\u0626\\u064a\\u0629 \\u062a\\u0636\\u0645\\u0646 \\u0631\\u0627\\u062d\\u0629 \\u0627\\u0644\\u0628\\u0627\\u0644 \\u0645\\u0639 \\u0643\\u0644 \\u062a\\u0633\\u062c\\u064a\\u0644 \\u062f\\u062e\\u0648\\u0644. \\u062a\\u0639\\u0631\\u0641 \\u0639\\u0644\\u0649 \\u0643\\u064a\\u0641\\u064a\\u0629 \\u062a\\u0645\\u0643\\u064a\\u0646 \\u0627\\u0644\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0627\\u0644\\u062b\\u0646\\u0627\\u0626\\u064a\\u0629 \\u0644\\u0644\\u062d\\u0641\\u0627\\u0638 \\u0639\\u0644\\u0649 \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0622\\u0645\\u0646\\u0629\\u060c \\u0645\\u0645\\u0627 \\u064a\\u0645\\u0646\\u062d\\u0643 \\u0627\\u0644\\u062a\\u062d\\u0643\\u0645 \\u0627\\u0644\\u0645\\u0637\\u0644\\u0642 \\u0641\\u064a \\u062a\\u062c\\u0631\\u0628\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629.<\\/p>\",\"tags\":[\"\\u0627\\u0644\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0627\\u0644\\u062b\\u0646\\u0627\\u0626\\u064a\\u0629\"]}},\"image\":\"438452f2-97fe-4f05-9a86-47adbb1f1022.webp\"}', 1, '2024-10-18 15:56:27', '2024-12-07 15:54:00');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_categories`
--

CREATE TABLE `announcement_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcement_categories`
--

INSERT INTO `announcement_categories` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, '{\"language\":{\"en\":{\"name\":\"Account Statements\"},\"es\":{\"name\":\"Estados de cuenta\"},\"ar\":{\"name\":\"\\u0643\\u0634\\u0648\\u0641\\u0627\\u062a \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\\u0627\\u062a\"}}}', 1, '2024-10-18 15:50:25', '2024-12-07 15:52:13'),
(2, '{\"language\":{\"en\":{\"name\":\"Credit Card Services\"},\"es\":{\"name\":\"Servicios de tarjetas de cr\\u00e9dito\"},\"ar\":{\"name\":\"\\u062e\\u062f\\u0645\\u0627\\u062a \\u0628\\u0637\\u0627\\u0642\\u0627\\u062a \\u0627\\u0644\\u0627\\u0626\\u062a\\u0645\\u0627\\u0646\"}}}', 1, '2024-10-18 15:50:40', '2024-12-07 15:51:57'),
(3, '{\"language\":{\"en\":{\"name\":\"Wealth Management\"},\"es\":{\"name\":\"Gesti\\u00f3n patrimonial\"},\"ar\":{\"name\":\"\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u062b\\u0631\\u0648\\u0627\\u062a\"}}}', 1, '2024-10-18 15:50:54', '2024-12-07 15:51:36'),
(4, '{\"language\":{\"en\":{\"name\":\"Mobile Banking\"},\"es\":{\"name\":\"Banca m\\u00f3vil\"},\"ar\":{\"name\":\"\\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0639\\u0628\\u0631 \\u0627\\u0644\\u0647\\u0627\\u062a\\u0641 \\u0627\\u0644\\u0645\\u062d\\u0645\\u0648\\u0644\"}}}', 1, '2024-10-18 15:51:04', '2024-12-07 15:51:08'),
(5, '{\"language\":{\"en\":{\"name\":\"Security\"},\"es\":{\"name\":\"Seguridad\"},\"ar\":{\"name\":\"\\u062d\\u0645\\u0627\\u064a\\u0629\"}}}', 1, '2024-10-18 15:51:15', '2024-12-07 15:46:19');

-- --------------------------------------------------------

--
-- Table structure for table `app_onboard_screens`
--

CREATE TABLE `app_onboard_screens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `heading` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  `details` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `last_edit_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_onboard_screens`
--

INSERT INTO `app_onboard_screens` (`id`, `heading`, `title`, `details`, `image`, `status`, `last_edit_by`, `created_at`, `updated_at`) VALUES
(1, '{\"language\":{\"en\":{\"heading\":\"Fund Transfer\"},\"es\":{\"heading\":\"Transferencia de fondos\"},\"ar\":{\"heading\":\"\\u062a\\u062d\\u0648\\u064a\\u0644 \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644\"}}}', '{\"language\":{\"en\":{\"title\":\"Send Money, Anytime, Anywhere\"},\"es\":{\"title\":\"Env\\u00eda dinero, en cualquier momento y a cualquier lugar\"},\"ar\":{\"title\":\"\\u0623\\u0631\\u0633\\u0644 \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644 \\u0641\\u064a \\u0623\\u064a \\u0648\\u0642\\u062a \\u0648\\u0641\\u064a \\u0623\\u064a \\u0645\\u0643\\u0627\\u0646\"}}}', '{\"language\":{\"en\":{\"details\":\"Experience seamless and secure fund transfers across the globe with just a few taps.\"},\"es\":{\"details\":\"Experimente transferencias de fondos fluidas y seguras en todo el mundo con solo unos pocos toques.\"},\"ar\":{\"details\":\"\\u0627\\u0633\\u062a\\u0645\\u062a\\u0639 \\u0628\\u062a\\u062d\\u0648\\u064a\\u0644\\u0627\\u062a \\u0645\\u0627\\u0644\\u064a\\u0629 \\u0633\\u0644\\u0633\\u0629 \\u0648\\u0622\\u0645\\u0646\\u0629 \\u0625\\u0644\\u0649 \\u062c\\u0645\\u064a\\u0639 \\u0623\\u0646\\u062d\\u0627\\u0621 \\u0627\\u0644\\u0639\\u0627\\u0644\\u0645 \\u0628\\u0628\\u0636\\u0639 \\u0646\\u0642\\u0631\\u0627\\u062a \\u0641\\u0642\\u0637.\"}}}', 'onboard1.webp', 1, 1, '2024-12-06 16:20:48', '2024-12-06 16:49:10'),
(2, '{\"language\":{\"en\":{\"heading\":\"Virtual Card\"},\"es\":{\"heading\":\"Tarjeta virtual\"},\"ar\":{\"heading\":\"\\u0628\\u0637\\u0627\\u0642\\u0629 \\u0627\\u0641\\u062a\\u0631\\u0627\\u0636\\u064a\\u0629\"}}}', '{\"language\":{\"en\":{\"title\":\"Your Card, Your Control\"},\"es\":{\"title\":\"Tu Tarjeta, Tu Control\"},\"ar\":{\"title\":\"\\u0628\\u0637\\u0627\\u0642\\u062a\\u0643 \\u0647\\u064a \\u0633\\u064a\\u0637\\u0631\\u062a\\u0643\"}}}', '{\"language\":{\"en\":{\"details\":\"Enjoy secure online shopping and payments with a virtual card tailored to your needs.\"},\"es\":{\"details\":\"Disfruta de compras y pagos online seguros con una tarjeta virtual adaptada a tus necesidades.\"},\"ar\":{\"details\":\"\\u0627\\u0633\\u062a\\u0645\\u062a\\u0639 \\u0628\\u0627\\u0644\\u062a\\u0633\\u0648\\u0642 \\u0627\\u0644\\u0622\\u0645\\u0646 \\u0639\\u0628\\u0631 \\u0627\\u0644\\u0625\\u0646\\u062a\\u0631\\u0646\\u062a \\u0648\\u0627\\u0644\\u062f\\u0641\\u0639 \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0628\\u0637\\u0627\\u0642\\u0629 \\u0627\\u0641\\u062a\\u0631\\u0627\\u0636\\u064a\\u0629 \\u0645\\u0635\\u0645\\u0645\\u0629 \\u062e\\u0635\\u064a\\u0635\\u064b\\u0627 \\u0644\\u062a\\u0644\\u0628\\u064a\\u0629 \\u0627\\u062d\\u062a\\u064a\\u0627\\u062c\\u0627\\u062a\\u0643.\"}}}', 'onboard2.webp', 1, 1, '2024-12-06 16:47:38', '2024-12-06 16:47:38'),
(3, '{\"language\":{\"en\":{\"heading\":\"Setup Pin\"},\"es\":{\"heading\":\"Pin de configuraci\\u00f3n\"},\"ar\":{\"heading\":\"\\u0625\\u0639\\u062f\\u0627\\u062f \\u0627\\u0644\\u062f\\u0628\\u0648\\u0633\"}}}', '{\"language\":{\"en\":{\"title\":\"Secure Your Transactions\"},\"es\":{\"title\":\"Proteja sus transacciones\"},\"ar\":{\"title\":\"\\u062a\\u0623\\u0645\\u064a\\u0646 \\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a\\u0643\"}}}', '{\"language\":{\"en\":{\"details\":\"Create a personal PIN to add an extra layer of security to your financial activities.\"},\"es\":{\"details\":\"Cree un PIN personal para agregar una capa adicional de seguridad a sus actividades financieras.\"},\"ar\":{\"details\":\"\\u0642\\u0645 \\u0628\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0631\\u0642\\u0645 \\u062a\\u0639\\u0631\\u064a\\u0641 \\u0634\\u062e\\u0635\\u064a (PIN) \\u0644\\u0625\\u0636\\u0627\\u0641\\u0629 \\u0637\\u0628\\u0642\\u0629 \\u0625\\u0636\\u0627\\u0641\\u064a\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u0623\\u0645\\u0627\\u0646 \\u0625\\u0644\\u0649 \\u0623\\u0646\\u0634\\u0637\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629.\"}}}', 'onboard3.webp', 1, 1, '2024-12-06 17:01:44', '2024-12-06 17:01:44');

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(50) DEFAULT NULL,
  `splash_screen_image` varchar(255) DEFAULT NULL,
  `url_title` varchar(191) DEFAULT NULL,
  `android_url` varchar(255) DEFAULT NULL,
  `iso_url` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `version`, `splash_screen_image`, `url_title`, `android_url`, `iso_url`, `created_at`, `updated_at`) VALUES
(1, '1.0.0', 'splash.webp', NULL, NULL, NULL, '2026-03-10 02:26:00', '2026-03-10 02:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `bank_branches`
--

CREATE TABLE `bank_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `bank_list_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `alias` varchar(191) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_branches`
--

INSERT INTO `bank_branches` (`id`, `admin_id`, `bank_list_id`, `name`, `alias`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Mirpur', 'mirpur', 1, '2024-02-07 16:03:57', '2024-02-07 16:03:57'),
(2, 1, 2, 'Uttara', 'uttara', 1, '2024-02-07 16:27:01', '2024-02-07 16:27:01'),
(3, 1, 3, 'Dhanmondi', 'dhanmondi', 1, '2024-02-07 16:39:17', '2024-02-07 17:19:05'),
(4, 1, 4, 'Mirpur', 'mirpur', 1, '2024-02-08 09:02:38', '2024-02-08 09:02:38'),
(5, 1, 5, 'Dhanmondi', 'dhanmondi', 1, '2024-02-08 09:02:47', '2024-02-08 09:02:47');

-- --------------------------------------------------------

--
-- Table structure for table `bank_lists`
--

CREATE TABLE `bank_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `alias` varchar(191) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_lists`
--

INSERT INTO `bank_lists` (`id`, `admin_id`, `name`, `alias`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sonali Bank Limited', 'sonali-bank-limited', 1, '2024-02-07 12:07:13', '2026-03-10 08:11:09'),
(2, 1, 'Doutch Bangla Bank', 'doutch-bangla-bank', 1, '2024-02-07 12:07:26', '2024-02-07 12:07:26'),
(3, 1, 'Rupali Bank Limited', 'rupali-bank-limited', 1, '2024-02-07 12:08:33', '2024-02-07 15:05:22'),
(4, 1, 'United Bank Limited', 'united-bank-limited', 1, '2024-02-07 12:08:52', '2024-02-07 12:08:52'),
(5, 1, 'Brack Bank Limited', 'brack-bank-limited', 1, '2024-02-08 09:00:32', '2024-02-08 09:00:32');

-- --------------------------------------------------------

--
-- Table structure for table `basic_settings`
--

CREATE TABLE `basic_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_name` varchar(100) DEFAULT NULL,
  `site_title` varchar(255) DEFAULT NULL,
  `base_color` varchar(50) DEFAULT NULL,
  `secondary_color` varchar(50) DEFAULT NULL,
  `otp_exp_seconds` int(11) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT NULL,
  `user_registration` tinyint(1) NOT NULL DEFAULT 1,
  `secure_password` tinyint(1) NOT NULL DEFAULT 0,
  `agree_policy` tinyint(1) NOT NULL DEFAULT 0,
  `force_ssl` tinyint(1) NOT NULL DEFAULT 0,
  `email_verification` tinyint(1) NOT NULL DEFAULT 0,
  `sms_verification` tinyint(1) NOT NULL DEFAULT 0,
  `email_notification` tinyint(1) NOT NULL DEFAULT 0,
  `push_notification` tinyint(1) NOT NULL DEFAULT 0,
  `kyc_verification` tinyint(1) NOT NULL DEFAULT 0,
  `site_logo_dark` varchar(255) DEFAULT NULL,
  `site_logo` varchar(255) DEFAULT NULL,
  `site_fav_dark` varchar(255) DEFAULT NULL,
  `site_fav` varchar(255) DEFAULT NULL,
  `preloader_image` varchar(255) DEFAULT NULL,
  `mail_config` text DEFAULT NULL,
  `mail_activity` text DEFAULT NULL,
  `push_notification_config` text DEFAULT NULL,
  `push_notification_activity` text DEFAULT NULL,
  `broadcast_config` text DEFAULT NULL,
  `broadcast_activity` text DEFAULT NULL,
  `sms_config` text DEFAULT NULL,
  `sms_activity` text DEFAULT NULL,
  `web_version` varchar(191) DEFAULT NULL,
  `admin_version` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `basic_settings`
--

INSERT INTO `basic_settings` (`id`, `site_name`, `site_title`, `base_color`, `secondary_color`, `otp_exp_seconds`, `timezone`, `user_registration`, `secure_password`, `agree_policy`, `force_ssl`, `email_verification`, `sms_verification`, `email_notification`, `push_notification`, `kyc_verification`, `site_logo_dark`, `site_logo`, `site_fav_dark`, `site_fav`, `preloader_image`, `mail_config`, `mail_activity`, `push_notification_config`, `push_notification_activity`, `broadcast_config`, `broadcast_activity`, `sms_config`, `sms_activity`, `web_version`, `admin_version`, `created_at`, `updated_at`) VALUES
(1, 'EnzoBank', 'Comprehensive Digital Banking and Financial Solution', '#000000', NULL, 3600, 'Asia/Dhaka', 1, 0, 1, 0, 0, 0, 0, 0, 0, '180342a5-31bc-4665-bc69-8a577c95e3e0.webp', '7a09d3c5-0307-48ea-bd88-4ff748da72e2.webp', '2c526cac-1399-4271-b0b9-587d7046bd26.webp', 'b0db0aa2-2745-4990-a577-d4e61d49fa0e.webp', NULL, '{\"method\":\"\",\"host\":\"\",\"port\":\"\",\"encryption\":\"\",\"password\":\"\",\"username\":\"\",\"from\":\"\",\"mail_address\":\"\",\"app_name\":\"\"}', NULL, '{\"method\":\"\",\"instance_id\":\"\",\"primary_key\":\"\"}', NULL, '{\"method\":\"\",\"app_id\":\"\",\"primary_key\":\"\",\"secret_key\":\"\",\"cluster\":\"\"}', NULL, NULL, NULL, '1.0.0', '2.5.0', '2026-03-10 02:25:58', '2026-03-10 04:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_method_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(191) NOT NULL,
  `info` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coinbase_webhook_calls`
--

CREATE TABLE `coinbase_webhook_calls` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) DEFAULT NULL,
  `payload` text DEFAULT NULL,
  `exception` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_requests`
--

CREATE TABLE `contact_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `message` text NOT NULL,
  `reply` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crypto_assets`
--

CREATE TABLE `crypto_assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_gateway_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(250) NOT NULL,
  `chain` varchar(250) NOT NULL,
  `coin` varchar(250) NOT NULL,
  `credentials` text DEFAULT NULL,
  `assets` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crypto_transactions`
--

CREATE TABLE `crypto_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `internal_trx_type` varchar(150) DEFAULT NULL COMMENT 'Internal Transaction Type. EX: Add Money, Money Out',
  `internal_trx_ref_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Internal Transaction Reference ID. EX: Transaction Table ID',
  `transaction_type` varchar(250) DEFAULT NULL,
  `sender_address` varchar(250) DEFAULT NULL,
  `receiver_address` varchar(250) DEFAULT NULL,
  `amount` varchar(250) DEFAULT NULL COMMENT 'Can be positive and negative',
  `asset` varchar(250) DEFAULT NULL,
  `block_number` varchar(250) DEFAULT NULL,
  `txn_hash` varchar(250) DEFAULT NULL COMMENT 'Transaction ID/Transaction Hash',
  `chain` varchar(250) DEFAULT NULL,
  `callback_response` text DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'NOT-USED',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `country` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  `type` enum('CRYPTO','FIAT') NOT NULL DEFAULT 'FIAT',
  `flag` varchar(255) DEFAULT NULL,
  `rate` decimal(28,8) NOT NULL DEFAULT 1.00000000,
  `sender` tinyint(1) NOT NULL DEFAULT 0,
  `receiver` tinyint(1) NOT NULL DEFAULT 0,
  `default` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `admin_id`, `country`, `name`, `code`, `symbol`, `type`, `flag`, `rate`, `sender`, `receiver`, `default`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'United States', 'United States dollar', 'USD', '$', 'FIAT', '1e4551f9-2216-4fcc-83b3-3a9b85c5c379.webp', 1.00000000, 1, 1, 1, 1, '2024-01-17 12:17:53', '2024-01-17 12:18:24');

-- --------------------------------------------------------

--
-- Table structure for table `extensions`
--

CREATE TABLE `extensions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `script` text DEFAULT NULL,
  `shortcode` text DEFAULT NULL,
  `support_image` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=>enable, 2=>disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `extensions`
--

INSERT INTO `extensions` (`id`, `name`, `slug`, `description`, `image`, `script`, `shortcode`, `support_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tawk', 'tawk-to', 'Go to your tawk to dashbaord. Click [setting icon] on top bar. Then click [Chat Widget] link from sidebar and follow the screenshot bellow. Copy property ID and paste it in Property ID field. Then copy widget ID and paste it in Widget ID field. Finally click on [Update] button and you are ready to go.', 'logo-tawk-to.png', NULL, '{\"property_id\":{\"title\":\"Property ID\",\"value\":\"6263cb787b967b11798c1faf\"},\"widget_id\":{\"title\":\"Widget ID\",\"value\":\"1g1at5k98\"}}', 'instruction-tawk-to.png', 1, '2026-03-10 02:26:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `holiday_date` date NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `region` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `holiday_date`, `name`, `region`, `created_at`, `updated_at`) VALUES
(1, '2026-01-01', 'New Year\'s Day', NULL, '2026-03-12 11:04:12', '2026-03-12 11:04:12'),
(2, '2026-12-25', 'Christmas Day', NULL, '2026-03-12 11:04:12', '2026-03-12 11:04:12'),
(3, '2026-07-04', 'Independence Day', 'US', '2026-03-12 11:04:12', '2026-03-12 11:04:12');

-- --------------------------------------------------------

--
-- Table structure for table `investment_assets`
--

CREATE TABLE `investment_assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `symbol` varchar(191) NOT NULL,
  `asset_type` enum('stock','fund','bond','crypto','cash') NOT NULL DEFAULT 'stock',
  `offering_type` enum('fixed_deposit','mutual_fund','gov_bond','corp_bond','stock','retirement') NOT NULL DEFAULT 'stock',
  `risk_level` varchar(191) DEFAULT NULL,
  `risk_score` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `current_price` decimal(19,6) NOT NULL DEFAULT 0.000000,
  `base_yield` decimal(8,4) NOT NULL DEFAULT 0.0000,
  `tiers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tiers`)),
  `maturities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`maturities`)),
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investment_assets`
--

INSERT INTO `investment_assets` (`id`, `name`, `symbol`, `asset_type`, `offering_type`, `risk_level`, `risk_score`, `current_price`, `base_yield`, `tiers`, `maturities`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Fixed Deposit Plus', 'FD-12', 'cash', 'fixed_deposit', 'low', 2, 1.000000, 4.5000, '[{\"min\":0,\"max\":5000,\"rate\":4.25},{\"min\":5000,\"max\":20000,\"rate\":5},{\"min\":20000,\"max\":null,\"rate\":5.75}]', '[6,12,24,36]', 1, '2026-03-12 11:03:59', '2026-03-12 11:03:59'),
(2, 'Growth Mutual Fund', 'MF-GROWTH', 'fund', 'mutual_fund', 'medium', 4, 100.000000, 7.5000, NULL, '[12,24,60]', 1, '2026-03-12 11:03:59', '2026-03-12 11:03:59'),
(3, 'Government Bond 10Y', 'GOV-10Y', 'bond', 'gov_bond', 'low', 1, 1000.000000, 3.6000, NULL, '[12,24,120]', 1, '2026-03-12 11:03:59', '2026-03-12 11:03:59'),
(4, 'Corporate Bond AA', 'CORP-AA', 'bond', 'corp_bond', 'medium', 3, 1000.000000, 6.2000, '[{\"min\":0,\"max\":10000,\"rate\":6},{\"min\":10000,\"max\":null,\"rate\":6.4}]', '[24,36,60]', 1, '2026-03-12 11:03:59', '2026-03-12 11:03:59'),
(5, 'Equity Index ETF', 'STK-ETF', 'stock', 'stock', 'high', 5, 50.000000, 0.0000, NULL, '[0]', 1, '2026-03-12 11:03:59', '2026-03-12 11:03:59'),
(6, 'Retirement Target 2050', 'RET-2050', 'fund', 'retirement', 'medium', 2, 25.000000, 6.0000, '[{\"min\":0,\"max\":10000,\"rate\":5.5},{\"min\":10000,\"max\":null,\"rate\":6.25}]', '[36,60,120,240]', 1, '2026-03-12 11:03:59', '2026-03-12 11:03:59');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `dir` varchar(191) NOT NULL DEFAULT 'ltr',
  `last_edit_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `status`, `dir`, `last_edit_by`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 1, 'ltr', 1, '2026-03-10 02:26:03', '2026-03-10 02:26:03'),
(2, 'Spanish', 'es', 0, 'ltr', 1, '2026-03-10 02:26:03', '2026-03-10 02:26:03'),
(3, 'Arabic', 'ar', 0, 'rtl', 1, '2026-03-10 02:26:03', '2026-03-10 02:26:03');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `loan_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `principal` decimal(19,4) NOT NULL,
  `interest_rate` decimal(8,4) NOT NULL,
  `interest_method` enum('simple','compound','amortized') NOT NULL DEFAULT 'amortized',
  `rate_type` enum('fixed','variable') NOT NULL DEFAULT 'fixed',
  `rate_schedule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rate_schedule`)),
  `term_months` int(10) UNSIGNED NOT NULL,
  `payment_frequency` enum('monthly','biweekly','weekly') NOT NULL DEFAULT 'monthly',
  `start_date` date DEFAULT NULL,
  `grace_days` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `late_fee_type` enum('percent','flat') NOT NULL DEFAULT 'percent',
  `late_fee_value` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `early_settlement_fee_percent` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `balance_principal` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `accrued_interest` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `last_accrual_date` date DEFAULT NULL,
  `status` enum('pending','active','closed','defaulted') NOT NULL DEFAULT 'pending',
  `next_due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period_number` int(10) UNSIGNED DEFAULT NULL,
  `loan_id` bigint(20) UNSIGNED NOT NULL,
  `due_date` date NOT NULL,
  `amount_due` decimal(19,4) NOT NULL,
  `principal_due` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `interest_due` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `fee_due` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `amount_paid` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `principal_paid` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `interest_paid` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `fee_paid` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `remaining_principal` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `status` enum('due','paid','late') NOT NULL DEFAULT 'due',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_products`
--

CREATE TABLE `loan_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `interest_rate` decimal(8,4) NOT NULL DEFAULT 0.0000,
  `term_months` int(10) UNSIGNED NOT NULL DEFAULT 12,
  `min_amount` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `max_amount` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_products`
--

INSERT INTO `loan_products` (`id`, `name`, `interest_rate`, `term_months`, `min_amount`, `max_amount`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Personal Loan Classic', 9.5000, 36, 500.0000, 10000.0000, 'Flexible personal loan', 1, '2026-03-12 11:04:23', '2026-03-12 11:04:23'),
(2, 'Auto Loan', 6.2500, 60, 5000.0000, 50000.0000, 'Competitive rates for vehicles', 1, '2026-03-12 11:04:23', '2026-03-12 11:04:23'),
(3, 'Home Improvement', 7.7500, 48, 2000.0000, 25000.0000, 'For renovation and upgrades', 1, '2026-03-12 11:04:23', '2026-03-12 11:04:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_12_11_061454_create_admins_table', 1),
(6, '2022_12_13_060252_create_transaction_settings_table', 1),
(7, '2022_12_14_103353_create_currencies_table', 1),
(8, '2022_12_17_104923_create_basic_settings_table', 1),
(9, '2022_12_18_093156_create_setup_seos_table', 1),
(10, '2022_12_19_072039_create_app_settings_table', 1),
(11, '2022_12_24_071800_create_site_sections_table', 1),
(12, '2022_12_24_110923_create_app_onboard_screens_table', 1),
(13, '2022_12_25_082623_create_payment_gateways_table', 1),
(14, '2022_12_26_060705_create_payment_gateway_currencies_table', 1),
(15, '2023_01_03_095454_create_extensions_table', 1),
(16, '2023_01_04_061756_create_setup_kycs_table', 1),
(17, '2023_01_07_053411_create_user_profiles_table', 1),
(18, '2023_01_08_133258_create_push_notification_records_table', 1),
(19, '2023_01_10_105235_create_user_password_resets_table', 1),
(20, '2023_01_10_115626_create_admin_login_logs_table', 1),
(21, '2023_01_11_121649_create_admin_roles_table', 1),
(22, '2023_01_11_122240_create_user_login_logs_table', 1),
(23, '2023_01_12_052750_create_admin_role_permissions_table', 1),
(24, '2023_01_12_055705_create_user_wallets_table', 1),
(25, '2023_01_14_093411_create_transactions_table', 1),
(26, '2023_01_14_154700_create_admin_role_has_permissions_table', 1),
(27, '2023_01_15_051638_create_admin_has_roles_table', 1),
(28, '2023_01_16_095331_create_temporary_datas_table', 1),
(29, '2023_01_18_043653_create_admin_notifications_table', 1),
(30, '2023_01_18_102653_create_languages_table', 1),
(31, '2023_01_19_060838_create_coinbase_webhook_calls_table', 1),
(32, '2023_01_28_075936_create_user_support_tickets_table', 1),
(33, '2023_01_28_081512_create_user_support_chats_table', 1),
(34, '2023_01_28_101246_create_user_support_ticket_attachments_table', 1),
(35, '2023_02_06_070418_create_user_mail_logs_table', 1),
(36, '2023_02_06_143558_create_user_authorizations_table', 1),
(37, '2023_02_07_154740_create_user_kyc_data_table', 1),
(38, '2023_02_09_083658_create_setup_pages_table', 1),
(39, '2023_07_24_155431_create_announcement_categories_table', 1),
(40, '2023_07_24_156942_create_announcements_table', 1),
(41, '2023_07_24_172330_create_subscribes_table', 1),
(42, '2023_07_26_105947_create_contact_requests_table', 1),
(43, '2023_07_31_110211_create_useful_links_table', 1),
(44, '2023_10_30_163112_create_crypto_assets_table', 1),
(45, '2023_11_02_180143_create_crypto_transactions_table', 1),
(46, '2024_01_23_034335_create_transaction_devices_table', 1),
(47, '2024_01_29_105156_create_user_notifications_table', 1),
(48, '2024_01_31_042840_create_transaction_methods_table', 1),
(49, '2024_01_31_042930_create_beneficiaries_table', 1),
(50, '2024_02_07_101354_create_bank_lists_table', 1),
(51, '2024_02_07_101415_create_bank_branches_table', 1),
(52, '2024_02_08_115234_create_mobile_banks_table', 1),
(53, '2024_09_12_070757_create_virtual_card_apis_table', 1),
(54, '2024_09_12_091303_create_strowallet_virtual_cards_table', 1),
(55, '2024_09_26_112729_create_system_maintenances_table', 1),
(56, '2024_10_03_112052_create_salary_disbursement_users_table', 1),
(57, '2024_10_07_094452_create_strowallet_customer_kycs_table', 1),
(58, '2016_06_01_000001_create_oauth_auth_codes_table', 2),
(59, '2016_06_01_000002_create_oauth_access_tokens_table', 2),
(60, '2016_06_01_000003_create_oauth_refresh_tokens_table', 2),
(61, '2016_06_01_000004_create_oauth_clients_table', 2),
(62, '2016_06_01_000005_create_oauth_personal_access_clients_table', 2),
(63, '2026_03_11_100001_create_loan_products_table', 2),
(64, '2026_03_11_100002_create_loans_table', 2),
(65, '2026_03_11_100003_create_loan_payments_table', 2),
(66, '2026_03_11_100004_create_investment_assets_table', 2),
(67, '2026_03_11_100005_create_portfolios_table', 2),
(68, '2026_03_11_100006_create_portfolio_holdings_table', 2),
(69, '2026_03_11_100007_create_portfolio_transactions_table', 3),
(70, '2026_03_11_200001_alter_loans_add_calculation_fields', 4),
(71, '2026_03_11_200002_alter_loan_payments_add_components', 4),
(72, '2026_03_11_200003_create_holidays_table', 4),
(73, '2026_03_11_200004_alter_investment_assets_offerings', 4);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_banks`
--

CREATE TABLE `mobile_banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `alias` varchar(191) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mobile_banks`
--

INSERT INTO `mobile_banks` (`id`, `admin_id`, `name`, `alias`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 'Bkash', 'bkash', 1, '2024-02-09 09:09:02', '2024-02-09 09:09:02'),
(3, 1, 'Rocket', 'rocket', 1, '2024-02-09 09:09:21', '2024-02-09 09:09:21'),
(4, 1, 'Nagad', 'nagad', 1, '2024-02-09 09:10:00', '2024-02-09 09:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(191) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `code` int(10) UNSIGNED NOT NULL,
  `type` enum('AUTOMATIC','MANUAL') NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `alias` varchar(120) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `credentials` text DEFAULT NULL,
  `supported_currencies` text DEFAULT NULL,
  `crypto` tinyint(1) NOT NULL DEFAULT 0,
  `desc` text DEFAULT NULL,
  `input_fields` text DEFAULT NULL,
  `env` enum('SANDBOX','PRODUCTION') DEFAULT NULL COMMENT 'Payment Gateway Environment (Ex: Production/Sandbox)',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `last_edit_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_gateways`
--

INSERT INTO `payment_gateways` (`id`, `slug`, `code`, `type`, `name`, `title`, `alias`, `image`, `credentials`, `supported_currencies`, `crypto`, `desc`, `input_fields`, `env`, `status`, `last_edit_by`, `created_at`, `updated_at`) VALUES
(1, 'add-money', 105, 'AUTOMATIC', 'Paypal', 'Paypal Gateway', 'paypal', '0bb65be8-a0cf-4d63-b9d7-5f5d7d76ce35.webp', '[{\"label\":\"Client ID\",\"placeholder\":\"Enter Client ID\",\"name\":\"client-id\",\"value\":\"ASuAkb_xKKu00hA1Pk32UMJvBqE7-GizN2xxBH2nlQgkvGSpgl_h_fmvYiRw__lSwYnw6Bu0G9qtjEY6\"},{\"label\":\"Secret ID\",\"placeholder\":\"Enter Secret ID\",\"name\":\"secret-id\",\"value\":\"EHtZKs6f0h6wDSKykJA4lJMU6GVGTc5GARto_EKQS1R5iGA2Z8n_F8sBQPTmcMWHav1GWNl8iJj169u0\"}]', '[\"USD\",\"GBP\",\"PHP\",\"NZD\",\"MYR\",\"EUR\",\"CNY\",\"CAD\",\"AUD\"]', 0, NULL, NULL, 'SANDBOX', 0, 1, '2023-05-29 15:09:41', '2026-03-13 10:33:24'),
(2, 'add-money', 110, 'AUTOMATIC', 'CoinGate', 'Crypto Payment gateway', 'coingate', '5f5ceacd-bd60-40ba-a8f2-438a90a47388.webp', '[{\"label\":\"Sandbox URL\",\"placeholder\":\"Enter Sandbox URL\",\"name\":\"sandbox-url\",\"value\":\"https:\\/\\/api-sandbox.coingate.com\\/v2\"},{\"label\":\"Sandbox App Token\",\"placeholder\":\"Enter Sandbox App Token\",\"name\":\"sandbox-app-token\",\"value\":\"XJW4RyhT8F-xssX2PvaHMWJjYe5nsbsrbb2Uqy4m\"},{\"label\":\"Production URL\",\"placeholder\":\"Enter Production URL\",\"name\":\"production-url\",\"value\":\"https:\\/\\/api.coingate.com\\/v2\"},{\"label\":\"Production App Token\",\"placeholder\":\"Enter Production App Token\",\"name\":\"production-app-token\",\"value\":null}]', '[\"USD\",\"BTC\",\"LTC\",\"ETH\",\"BCH\",\"TRX\",\"ETC\",\"DOGE\",\"BTG\",\"BNB\",\"TUSD\",\"USDT\",\"BSV\",\"MATIC\",\"BUSD\",\"SOL\",\"WBTC\",\"RVN\",\"BCD\",\"ATOM\",\"BTTC\",\"EURT\"]', 1, NULL, NULL, 'SANDBOX', 1, 1, '2023-08-07 14:36:30', '2023-08-07 16:06:12'),
(3, 'add-money', 120, 'AUTOMATIC', 'Stripe', 'Stripe Gateway', 'stripe', '357e631f-ee53-47cb-b603-71ee46b038c9.webp', '[{\"label\":\"Test Publishable Key\",\"placeholder\":\"Enter Test Publishable Key\",\"name\":\"test-publishable-key\",\"value\":\"YOUR_STRIPE_TEST_PUBLISHABLE_KEY\"},{\"label\":\"Test Secret Key\",\"placeholder\":\"Enter Test Secret Key\",\"name\":\"test-secret-key\",\"value\":\"YOUR_STRIPE_TEST_SECRET_KEY\"},{\"label\":\"Live Publishable Key\",\"placeholder\":\"Enter Live Publishable Key\",\"name\":\"live-publishable-key\",\"value\":null},{\"label\":\"Live Secret Key\",\"placeholder\":\"Enter Live Secret Key\",\"name\":\"live-secret-key\",\"value\":null}]', '[\"USD\",\"AUD\",\"AED\",\"BDT\",\"BGN\",\"CAD\",\"EGP\",\"EUR\",\"GBP\",\"INR\",\"PKR\",\"MYR\",\"NGN\"]', 0, NULL, NULL, 'SANDBOX', 0, 1, '2023-11-07 23:31:10', '2026-03-13 10:33:27'),
(4, 'add-money', 125, 'AUTOMATIC', 'Flutterwave', 'Flutterwave Gateway', 'flutterwave', '56069c39-b494-48e4-a938-98dc00561580.webp', '[{\"label\":\"Public Key\",\"placeholder\":\"Enter Public Key\",\"name\":\"public-key\",\"value\":\"YOUR_FLUTTERWAVE_TEST_PUBLIC_KEY\"},{\"label\":\"Secret Key\",\"placeholder\":\"Enter Secret Key\",\"name\":\"secret-key\",\"value\":\"YOUR_FLUTTERWAVE_TEST_SECRET_KEY\"},{\"label\":\"Encryption key\",\"placeholder\":\"Enter Encryption key\",\"name\":\"encryption-key\",\"value\":\"YOUR_FLUTTERWAVE_TEST_ENCRYPTION_KEY\"}]', '[\"AED\", \"ARS\", \"AUD\", \"CAD\", \"CHF\", \"CZK\", \"ETB\", \"EUR\", \"GBP\", \"GHS\", \"ILS\", \"INR\", \"JPY\", \"KES\", \"MAD\", \"MUR\", \"MYR\", \"NGN\", \"NOK\", \"NZD\", \"PEN\", \"PLN\", \"RUB\", \"RWF\", \"SAR\", \"SEK\", \"SGD\", \"SLL\", \"TZS\", \"UGX\", \"USD\", \"XAF\", \"XOF\", \"ZAR\", \"ZMK\", \"ZMW\", \"MWK\"]', 0, NULL, NULL, 'SANDBOX', 0, 1, '2023-11-08 17:40:33', '2026-03-13 10:33:30'),
(5, 'add-money', 130, 'AUTOMATIC', 'SSLCOMMERZ', 'SSLCOMMERZ Gateway', 'sslcommerz', '9ce51bcf-a572-4c23-b433-a1f03ca5e99f.webp', '[{\"label\":\"Store ID\",\"placeholder\":\"Enter Store ID\",\"name\":\"store-id\",\"value\":\"appde654c6741c4609\"},{\"label\":\"Store Password (API\\/Secret Key)\",\"placeholder\":\"Enter Store Password (API\\/Secret Key)\",\"name\":\"secret-key\",\"value\":\"appde654c6741c4609@ssl\"}]', '[\"BDT\",\"USD\",\"EUR\",\"SGD\",\"INR\",\"MYR\",\"GBP\",\"AUD\"]', 0, NULL, NULL, 'SANDBOX', 0, 1, '2023-11-09 17:58:17', '2026-03-13 10:33:32'),
(6, 'add-money', 135, 'AUTOMATIC', 'Razorpay', 'Razorpay Gateway', 'razorpay', '147f7e97-d293-4f1d-8563-73630f2ce42c.webp', '[{\"label\":\"Key ID\",\"placeholder\":\"Enter Key ID\",\"name\":\"key-id\",\"value\":\"rzp_test_voV4gKUbSxoQez\"},{\"label\":\"Secret Key\",\"placeholder\":\"Enter Secret Key\",\"name\":\"secret-key\",\"value\":\"cJltc1jy6evA4Vvh9lTO7SWr\"}]', '[\"USD\",\"EUR\",\"GBP\",\"SGD\",\"AED\",\"AUD\",\"CAD\",\"CNY\",\"SEK\",\"NZD\",\"MXN\",\"BDT\",\"EGP\",\"HKD\",\"INR\",\"LBP\",\"LKR\",\"MAD\",\"MYR\",\"NGN\",\"NPR\",\"PHP\",\"PKR\",\"QAR\",\"SAR\",\"UZS\",\"GHS\"]', 0, NULL, NULL, 'SANDBOX', 0, 1, '2023-11-09 22:26:21', '2026-03-13 10:33:34'),
(7, 'add-money', 140, 'MANUAL', 'WISE', 'WISE Gateway', 'wise', NULL, NULL, '[\"USD\"]', 0, '<p>To initiate a payment using our manual payment gateway, please follow the instructions provided below. We offer two convenient methods for you to choose from:<br><strong>Option 1: PayPal Payment</strong></p><ol style=\"list-style-type:none;\"><li>Log in to your PayPal account or access the PayPal website.</li><li>Choose the option to send money or make a payment.</li><li>Enter the recipient???s email address: <a href=\"mailto:business@paypal.com\">business@paypal.com</a></li><li>Specify the payment amount in the currency you wish to use.</li><li>Make sure to select ???Business??? as the account type.</li><li>Review the payment details and confirm the transaction.</li><li>Keep the payment confirmation or receipt as proof of the transaction.</li></ol><p><strong>Option 2: Bank Transfer</strong></p><ol style=\"list-style-type:none;\"><li>Visit your local bank or access your online banking platform.</li><li>Initiate a new fund transfer or payment.</li><li>Enter the recipient???s bank account details:</li></ol><ul style=\"list-style-type:none;\"><li>Bank Name: Bank of America</li><li>IBAN (International Bank Account Number): 01234567890</li></ul><ol style=\"list-style-type:none;\"><li>Specify the payment amount in the currency you intend to use.</li><li>Double-check all details, including the recipient???s account information.</li><li>Confirm and authorize the transfer.</li><li>Retain the payment receipt or confirmation for future reference.</li></ol><p>Please ensure that you keep a record of your payment as proof of the transaction. In case of any discrepancies or verification requirements, you may be asked to provide this documentation.Your payment will be manually verified by our team, and once confirmed, your order will be processed promptly. We appreciate your cooperation and look forward to serving you!</p>', '[{\"type\":\"file\",\"label\":\"Screenshot\",\"name\":\"screenshot\",\"required\":false,\"validation\":{\"max\":\"10\",\"mimes\":[\"jpg\",\"png\",\"webp\",\"svg\"],\"min\":\"0\",\"options\":[],\"required\":false}},{\"type\":\"text\",\"label\":\"Transaction ID\",\"name\":\"transaction_id\",\"required\":true,\"validation\":{\"max\":\"60\",\"mimes\":[],\"min\":\"0\",\"options\":[],\"required\":true}}]', NULL, 0, 1, NULL, '2026-03-13 10:36:31'),
(8, 'money-out', 145, 'MANUAL', 'EasyPaisa', 'EasyPaisa Gateway', 'easypaisa', NULL, NULL, '[\"PKR\"]', 0, '<p>To initiate a payment using our manual payment gateway, please follow the instructions provided below. We offer two convenient methods for you to choose from:<br><strong>Option 1: PayPal Payment</strong></p><ol style=\"list-style-type:none;\"><li>Log in to your PayPal account or access the PayPal website.</li><li>Choose the option to send money or make a payment.</li><li>Enter the recipient???s email address: <a href=\"mailto:business@paypal.com\">business@paypal.com</a></li><li>Specify the payment amount in the currency you wish to use.</li><li>Make sure to select ???Business??? as the account type.</li><li>Review the payment details and confirm the transaction.</li><li>Keep the payment confirmation or receipt as proof of the transaction.</li></ol><p><strong>Option 2: Bank Transfer</strong></p><ol style=\"list-style-type:none;\"><li>Visit your local bank or access your online banking platform.</li><li>Initiate a new fund transfer or payment.</li><li>Enter the recipient???s bank account details:</li></ol><ul style=\"list-style-type:none;\"><li>Bank Name: Bank of America</li><li>IBAN (International Bank Account Number): 01234567890</li></ul><ol style=\"list-style-type:none;\"><li>Specify the payment amount in the currency you intend to use.</li><li>Double-check all details, including the recipient???s account information.</li><li>Confirm and authorize the transfer.</li><li>Retain the payment receipt or confirmation for future reference.</li></ol><p>Please ensure that you keep a record of your payment as proof of the transaction. In case of any discrepancies or verification requirements, you may be asked to provide this documentation.Your payment will be manually verified by our team, and once confirmed, your order will be processed promptly. We appreciate your cooperation and look forward to serving you!</p>', '[{\"type\":\"file\",\"label\":\"Screenshot\",\"name\":\"screenshot\",\"required\":false,\"validation\":{\"max\":\"10\",\"mimes\":[\"jpg\",\"png\",\"webp\",\"svg\"],\"min\":\"0\",\"options\":[],\"required\":false}},{\"type\":\"text\",\"label\":\"Transaction ID\",\"name\":\"transaction_id\",\"required\":true,\"validation\":{\"max\":\"60\",\"mimes\":[],\"min\":\"1\",\"options\":[],\"required\":true}}]', NULL, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateway_currencies`
--

CREATE TABLE `payment_gateway_currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_gateway_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `alias` varchar(120) NOT NULL,
  `currency_code` varchar(20) NOT NULL,
  `currency_symbol` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `min_limit` decimal(28,8) UNSIGNED NOT NULL DEFAULT 0.00000000,
  `max_limit` decimal(28,8) UNSIGNED NOT NULL DEFAULT 0.00000000,
  `percent_charge` decimal(28,8) UNSIGNED NOT NULL DEFAULT 0.00000000,
  `fixed_charge` decimal(28,8) UNSIGNED NOT NULL DEFAULT 0.00000000,
  `rate` decimal(28,8) UNSIGNED NOT NULL DEFAULT 0.00000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_gateway_currencies`
--

INSERT INTO `payment_gateway_currencies` (`id`, `payment_gateway_id`, `name`, `alias`, `currency_code`, `currency_symbol`, `image`, `min_limit`, `max_limit`, `percent_charge`, `fixed_charge`, `rate`, `created_at`, `updated_at`) VALUES
(1, 1, 'Paypal USD', 'add-money-paypal-usd-automatic', 'USD', '$', NULL, 1.00000000, 5000.00000000, 2.00000000, 0.00000000, 1.00000000, '2023-05-29 15:15:57', '2023-08-07 14:26:48'),
(3, 3, 'Stripe USD', 'add-money-stripe-usd-automatic', 'USD', '$', NULL, 1.00000000, 1000.00000000, 2.00000000, 1.00000000, 1.00000000, '2023-11-07 23:35:45', '2023-11-07 23:35:45'),
(4, 4, 'Flutterwave NGN', 'add-money-flutterwave-ngn-automatic', 'NGN', NULL, NULL, 1.00000000, 1000000.00000000, 2.00000000, 1.00000000, 780.00000000, '2023-11-08 18:01:40', '2023-11-08 18:01:40'),
(5, 5, 'SSLCOMMERZ BDT', 'add-money-sslcommerz-bdt-automatic', 'BDT', NULL, NULL, 1.00000000, 1000.00000000, 2.00000000, 1.00000000, 110.00000000, '2023-11-09 18:01:12', '2023-11-09 18:01:12'),
(6, 6, 'Razorpay INR', 'add-money-razorpay-inr-automatic', 'INR', NULL, NULL, 1.00000000, 1000.00000000, 2.00000000, 1.00000000, 83.28000000, '2023-11-09 22:36:05', '2023-11-09 22:36:05'),
(7, 7, 'WISE USD', 'add-money-wise-usd-manual', 'USD', '$', NULL, 1.00000000, 10000.00000000, 3.00000000, 1.00000000, 1.00000000, '2023-08-14 21:59:35', '2023-08-14 22:13:18'),
(8, 8, 'EasyPaisa PKR', 'money-out-easypaisa-pkr-manual', 'PKR', 'Rs', NULL, 1.00000000, 10000.00000000, 3.00000000, 1.00000000, 289.38000000, '2023-08-14 22:27:12', '2023-08-14 22:27:12'),
(14, 2, 'CoinGate USDT', 'add-money-coingate-usdt-automatic', 'USDT', NULL, NULL, 100.00000000, 100000000000.00000000, 0.00000000, 0.00000000, 1.00000000, '2026-03-13 10:36:03', '2026-03-13 10:42:03');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`id`, `user_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 2, 'CHISOM EMMANUEL', '2026-03-13 08:37:33', '2026-03-13 08:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_holdings`
--

CREATE TABLE `portfolio_holdings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `portfolio_id` bigint(20) UNSIGNED NOT NULL,
  `investment_asset_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(28,10) NOT NULL DEFAULT 0.0000000000,
  `avg_cost` decimal(19,6) NOT NULL DEFAULT 0.000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolio_holdings`
--

INSERT INTO `portfolio_holdings` (`id`, `portfolio_id`, `investment_asset_id`, `quantity`, `avg_cost`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 3.0000000000, 332.999997, '2026-03-13 08:38:16', '2026-03-13 08:38:16');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_transactions`
--

CREATE TABLE `portfolio_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `portfolio_id` bigint(20) UNSIGNED NOT NULL,
  `investment_asset_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('buy','sell','dividend','interest') NOT NULL DEFAULT 'buy',
  `quantity` decimal(28,10) NOT NULL DEFAULT 0.0000000000,
  `price` decimal(19,6) NOT NULL DEFAULT 0.000000,
  `fee` decimal(19,6) NOT NULL DEFAULT 0.000000,
  `executed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolio_transactions`
--

INSERT INTO `portfolio_transactions` (`id`, `portfolio_id`, `investment_asset_id`, `type`, `quantity`, `price`, `fee`, `executed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'buy', 3.0000000000, 332.999997, 0.000000, '2026-03-13 08:38:16', '2026-03-13 08:38:16', '2026-03-13 08:38:16');

-- --------------------------------------------------------

--
-- Table structure for table `push_notification_records`
--

CREATE TABLE `push_notification_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_ids` text DEFAULT NULL,
  `device_ids` text DEFAULT NULL,
  `method` varchar(50) NOT NULL,
  `response` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `send_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_disbursement_users`
--

CREATE TABLE `salary_disbursement_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_email` varchar(191) NOT NULL,
  `company_name` varchar(191) NOT NULL,
  `company_username` varchar(191) NOT NULL,
  `user_name` varchar(191) NOT NULL,
  `user_email` varchar(191) NOT NULL,
  `user_username` varchar(191) NOT NULL,
  `amount` decimal(28,8) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setup_kycs`
--

CREATE TABLE `setup_kycs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `fields` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `last_edit_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setup_kycs`
--

INSERT INTO `setup_kycs` (`id`, `slug`, `user_type`, `fields`, `status`, `last_edit_by`, `created_at`, `updated_at`) VALUES
(1, 'user', 'USER', '[{\"type\":\"select\",\"label\":\"ID Type\",\"name\":\"id_type\",\"required\":true,\"validation\":{\"max\":0,\"min\":0,\"mimes\":[],\"options\":[\"NID\",\" Driving License\",\" Passport\"],\"required\":true}},{\"type\":\"file\",\"label\":\"Back\",\"name\":\"back\",\"required\":true,\"validation\":{\"max\":\"2\",\"mimes\":[\"jpg\",\"png\",\"webp\",\"jpeg\"],\"min\":0,\"options\":[],\"required\":true}},{\"type\":\"file\",\"label\":\"Front\",\"name\":\"front\",\"required\":true,\"validation\":{\"max\":\"2\",\"mimes\":[\"jpg\",\"png\",\"webp\",\"jpeg\"],\"min\":0,\"options\":[],\"required\":true}}]', 1, 1, '2024-01-30 11:38:09', '2026-03-10 08:09:37');

-- --------------------------------------------------------

--
-- Table structure for table `setup_pages`
--

CREATE TABLE `setup_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(250) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `last_edit_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setup_pages`
--

INSERT INTO `setup_pages` (`id`, `slug`, `title`, `url`, `last_edit_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 'home', 'Home', '/', 1, 1, '2026-03-10 02:26:03', NULL),
(2, 'about', 'About', '/about', 1, 1, '2026-03-10 02:26:03', NULL),
(3, 'services', 'Services', '/services', 1, 1, '2026-03-10 02:26:03', NULL),
(4, 'web-journals', 'Web Journals', '/web-journals', 1, 1, '2026-03-10 02:26:03', NULL),
(5, 'contact', 'Contact', '/contact', 1, 1, '2026-03-10 02:26:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `setup_seos`
--

CREATE TABLE `setup_seos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `last_edit_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setup_seos`
--

INSERT INTO `setup_seos` (`id`, `slug`, `title`, `desc`, `tags`, `image`, `last_edit_by`, `created_at`, `updated_at`) VALUES
(1, 'lorem_ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '[\"Lorem\",\"Ipsum\"]', NULL, 1, '2026-03-10 02:26:00', '2026-03-10 02:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `site_sections`
--

CREATE TABLE `site_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `serialize` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_sections`
--

INSERT INTO `site_sections` (`id`, `key`, `value`, `status`, `serialize`, `created_at`, `updated_at`) VALUES
(1, 'site_cookie', '{\"status\":true,\"link\":\"privacy-policy\",\"desc\":\"We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience.\"}', 1, NULL, '2024-10-16 10:06:37', '2024-10-16 10:06:37'),
(2, 'banner-section', '{\"image\":\"4ac6a673-2a6a-43d8-8e67-468cb77a4c02.webp\",\"language\":{\"en\":{\"heading\":\"Secure | Simple | Smarter Banking.\",\"sub_heading\":\"Welcome to iBanking\\u2014your gateway to modern banking. Manage your money seamlessly with secure fund transfers, virtual cards, and innovative features built for your convenience. Elevate your financial journey with us.\",\"button_name\":\"Get Started\"},\"es\":{\"heading\":\"Banca segura | sencilla | m\\u00e1s inteligente.\",\"sub_heading\":\"Bienvenido a iBanking, su puerta de entrada a la banca moderna. Administre su dinero sin inconvenientes con transferencias de fondos seguras, tarjetas virtuales y funciones innovadoras dise\\u00f1adas para su conveniencia. Mejore su experiencia financiera con nosotros.\",\"button_name\":\"Empezar\"},\"ar\":{\"heading\":\"\\u0622\\u0645\\u0646 | \\u0628\\u0633\\u064a\\u0637 | \\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0623\\u0643\\u062b\\u0631 \\u0630\\u0643\\u0627\\u0621\\u064b.\",\"sub_heading\":\"\\u0645\\u0631\\u062d\\u0628\\u064b\\u0627 \\u0628\\u0643 \\u0641\\u064a iBanking\\u2014\\u0628\\u0648\\u0627\\u0628\\u062a\\u0643 \\u0625\\u0644\\u0649 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0627\\u0644\\u062d\\u062f\\u064a\\u062b\\u0629. \\u064a\\u0645\\u0643\\u0646\\u0643 \\u0625\\u062f\\u0627\\u0631\\u0629 \\u0623\\u0645\\u0648\\u0627\\u0644\\u0643 \\u0628\\u0633\\u0644\\u0627\\u0633\\u0629 \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u0627\\u0644\\u062a\\u062d\\u0648\\u064a\\u0644\\u0627\\u062a \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0627\\u0644\\u0622\\u0645\\u0646\\u0629 \\u0648\\u0627\\u0644\\u0628\\u0637\\u0627\\u0642\\u0627\\u062a \\u0627\\u0644\\u0627\\u0641\\u062a\\u0631\\u0627\\u0636\\u064a\\u0629 \\u0648\\u0627\\u0644\\u0645\\u064a\\u0632\\u0627\\u062a \\u0627\\u0644\\u0645\\u0628\\u062a\\u0643\\u0631\\u0629 \\u0627\\u0644\\u0645\\u0635\\u0645\\u0645\\u0629 \\u0644\\u0631\\u0627\\u062d\\u062a\\u0643. \\u0627\\u0631\\u062a\\u0642\\u0650 \\u0628\\u0631\\u062d\\u0644\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0645\\u0639\\u0646\\u0627.\",\"button_name\":\"\\u0627\\u0644\\u0628\\u062f\\u0621\"}}}', 1, NULL, '2024-10-16 11:14:42', '2024-12-07 16:20:26'),
(3, 'feature-section', '{\"image\":\"\",\"language\":{\"en\":{\"first_heading\":\"Our Key Features\",\"first_sub_heading\":\"Benefit from personalized financial solutions, diverse investment options, real-time updates.\",\"second_heading\":\"Checking Our Key Features\",\"second_sub_heading\":\"Discover a new era in banking with iBanking, where security meets convenience seamlessly. Our platform boasts advanced encryption and multi-factor authentication, ensuring robust security. Experience intuitive navigation through our user-friendly interface, available 24\\/7 across all devices. Benefit from personalized financial solutions, diverse investment options, real-time updates, and comprehensive financial planning tools for your peace of mind.\",\"button_name\":\"Contact Us\"},\"es\":{\"first_heading\":\"Nuestras caracter\\u00edsticas principales\",\"first_sub_heading\":\"Benef\\u00edciese de soluciones financieras personalizadas, diversas opciones de inversi\\u00f3n y actualizaciones en tiempo real.\",\"second_heading\":\"Comprobaci\\u00f3n de nuestras caracter\\u00edsticas principales\",\"second_sub_heading\":\"Descubra una nueva era en la banca con iBanking, donde la seguridad se combina a la perfecci\\u00f3n con la comodidad. Nuestra plataforma cuenta con cifrado avanzado y autenticaci\\u00f3n multifactor, lo que garantiza una seguridad s\\u00f3lida. Disfrute de una navegaci\\u00f3n intuitiva a trav\\u00e9s de nuestra interfaz f\\u00e1cil de usar, disponible las 24 horas, los 7 d\\u00edas de la semana en todos los dispositivos. Benef\\u00edciese de soluciones financieras personalizadas, diversas opciones de inversi\\u00f3n, actualizaciones en tiempo real y herramientas integrales de planificaci\\u00f3n financiera para su tranquilidad.\",\"button_name\":\"Contacta con nosotras\"},\"ar\":{\"first_heading\":\"\\u0645\\u064a\\u0632\\u0627\\u062a\\u0646\\u0627 \\u0627\\u0644\\u0631\\u0626\\u064a\\u0633\\u064a\\u0629\",\"first_sub_heading\":\"\\u0627\\u0633\\u062a\\u0641\\u062f \\u0645\\u0646 \\u0627\\u0644\\u062d\\u0644\\u0648\\u0644 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0627\\u0644\\u0645\\u062e\\u0635\\u0635\\u0629 \\u0648\\u062e\\u064a\\u0627\\u0631\\u0627\\u062a \\u0627\\u0644\\u0627\\u0633\\u062a\\u062b\\u0645\\u0627\\u0631 \\u0627\\u0644\\u0645\\u062a\\u0646\\u0648\\u0639\\u0629 \\u0648\\u0627\\u0644\\u062a\\u062d\\u062f\\u064a\\u062b\\u0627\\u062a \\u0641\\u064a \\u0627\\u0644\\u0648\\u0642\\u062a \\u0627\\u0644\\u0641\\u0639\\u0644\\u064a.\",\"second_heading\":\"\\u0627\\u0644\\u062a\\u062d\\u0642\\u0642 \\u0645\\u0646 \\u0645\\u064a\\u0632\\u0627\\u062a\\u0646\\u0627 \\u0627\\u0644\\u0631\\u0626\\u064a\\u0633\\u064a\\u0629\",\"second_sub_heading\":\"\\u0627\\u0643\\u062a\\u0634\\u0641 \\u062d\\u0642\\u0628\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0641\\u064a \\u0639\\u0627\\u0644\\u0645 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0645\\u0639 iBanking\\u060c \\u062d\\u064a\\u062b \\u064a\\u0644\\u062a\\u0642\\u064a \\u0627\\u0644\\u0623\\u0645\\u0627\\u0646 \\u0628\\u0627\\u0644\\u0631\\u0627\\u062d\\u0629 \\u0628\\u0633\\u0644\\u0627\\u0633\\u0629. \\u062a\\u062a\\u0645\\u064a\\u0632 \\u0645\\u0646\\u0635\\u062a\\u0646\\u0627 \\u0628\\u062a\\u0634\\u0641\\u064a\\u0631 \\u0645\\u062a\\u0642\\u062f\\u0645 \\u0648\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0645\\u062a\\u0639\\u062f\\u062f\\u0629 \\u0627\\u0644\\u0639\\u0648\\u0627\\u0645\\u0644\\u060c \\u0645\\u0645\\u0627 \\u064a\\u0636\\u0645\\u0646 \\u0623\\u0645\\u0627\\u0646\\u064b\\u0627 \\u0642\\u0648\\u064a\\u064b\\u0627. \\u0627\\u0633\\u062a\\u0645\\u062a\\u0639 \\u0628\\u0627\\u0644\\u062a\\u0646\\u0642\\u0644 \\u0627\\u0644\\u0628\\u062f\\u064a\\u0647\\u064a \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u0648\\u0627\\u062c\\u0647\\u062a\\u0646\\u0627 \\u0633\\u0647\\u0644\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645\\u060c \\u0648\\u0627\\u0644\\u0645\\u062a\\u0627\\u062d\\u0629 \\u0639\\u0644\\u0649 \\u0645\\u062f\\u0627\\u0631 \\u0627\\u0644\\u0633\\u0627\\u0639\\u0629 \\u0637\\u0648\\u0627\\u0644 \\u0623\\u064a\\u0627\\u0645 \\u0627\\u0644\\u0623\\u0633\\u0628\\u0648\\u0639 \\u0639\\u0628\\u0631 \\u062c\\u0645\\u064a\\u0639 \\u0627\\u0644\\u0623\\u062c\\u0647\\u0632\\u0629. \\u0627\\u0633\\u062a\\u0641\\u062f \\u0645\\u0646 \\u0627\\u0644\\u062d\\u0644\\u0648\\u0644 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0627\\u0644\\u0645\\u062e\\u0635\\u0635\\u0629 \\u0648\\u062e\\u064a\\u0627\\u0631\\u0627\\u062a \\u0627\\u0644\\u0627\\u0633\\u062a\\u062b\\u0645\\u0627\\u0631 \\u0627\\u0644\\u0645\\u062a\\u0646\\u0648\\u0639\\u0629 \\u0648\\u0627\\u0644\\u062a\\u062d\\u062f\\u064a\\u062b\\u0627\\u062a \\u0641\\u064a \\u0627\\u0644\\u0648\\u0642\\u062a \\u0627\\u0644\\u0641\\u0639\\u0644\\u064a \\u0648\\u0623\\u062f\\u0648\\u0627\\u062a \\u0627\\u0644\\u062a\\u062e\\u0637\\u064a\\u0637 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a \\u0627\\u0644\\u0634\\u0627\\u0645\\u0644\\u0629 \\u0644\\u0631\\u0627\\u062d\\u0629 \\u0628\\u0627\\u0644\\u0643.\",\"button_name\":\"\\u0627\\u062a\\u0635\\u0644 \\u0628\\u0646\\u0627\"}},\"items\":{\"6710978769b5a\":{\"language\":{\"en\":{\"item_title\":\"Advanced Security\"},\"es\":{\"item_title\":\"Seguridad avanzada\"},\"ar\":{\"item_title\":\"\\u0627\\u0644\\u0623\\u0645\\u0627\\u0646 \\u0627\\u0644\\u0645\\u062a\\u0642\\u062f\\u0645\"}},\"id\":\"6710978769b5a\",\"status\":1},\"6710981dcbf6d\":{\"language\":{\"en\":{\"item_title\":\"User-Friendly\"},\"es\":{\"item_title\":\"F\\u00e1cil de usar\"},\"ar\":{\"item_title\":\"\\u0633\\u0647\\u0644 \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645\"}},\"id\":\"6710981dcbf6d\",\"status\":1},\"6710982584e42\":{\"language\":{\"en\":{\"item_title\":\"24\\/7 Access\"},\"es\":{\"item_title\":\"Acceso 24 horas al d\\u00eda, 7 d\\u00edas a la semana\"},\"ar\":{\"item_title\":\"\\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0639\\u0644\\u0649 \\u0645\\u062f\\u0627\\u0631 24 \\u0633\\u0627\\u0639\\u0629 \\u0637\\u0648\\u0627\\u0644 \\u0623\\u064a\\u0627\\u0645 \\u0627\\u0644\\u0623\\u0633\\u0628\\u0648\\u0639\"}},\"id\":\"6710982584e42\",\"status\":1},\"67109831180e1\":{\"language\":{\"en\":{\"item_title\":\"Account Management\"},\"es\":{\"item_title\":\"Gesti\\u00f3n de cuentas\"},\"ar\":{\"item_title\":\"\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\\u0627\\u062a\"}},\"id\":\"67109831180e1\",\"status\":1},\"6710983a507a0\":{\"language\":{\"en\":{\"item_title\":\"Customer Support\"},\"es\":{\"item_title\":\"Atenci\\u00f3n al cliente\"},\"ar\":{\"item_title\":\"\\u062f\\u0639\\u0645 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621\"}},\"id\":\"6710983a507a0\",\"status\":1},\"67109842aa7d2\":{\"language\":{\"en\":{\"item_title\":\"Real-Time Updates\"},\"es\":{\"item_title\":\"Actualizaciones en tiempo real\"},\"ar\":{\"item_title\":\"\\u062a\\u062d\\u062f\\u064a\\u062b\\u0627\\u062a \\u0641\\u064a \\u0627\\u0644\\u0648\\u0642\\u062a \\u0627\\u0644\\u062d\\u0642\\u064a\\u0642\\u064a\"}},\"id\":\"67109842aa7d2\",\"status\":1}}}', 1, NULL, '2024-10-16 13:01:27', '2024-12-07 14:43:24'),
(4, 'how-it-work', '{\"image\":\"f16f81ad-1f1f-4960-8ff1-55ef9b074bb5.webp\",\"language\":{\"en\":{\"heading\":\"Getting Started with iBanking\",\"sub_heading\":\"Sign up, manage your accounts, and complete transactions with ease and security.\"},\"es\":{\"heading\":\"Introducci\\u00f3n a la banca en l\\u00ednea\",\"sub_heading\":\"Reg\\u00edstrese, administre sus cuentas y complete transacciones con facilidad y seguridad.\"},\"ar\":{\"heading\":\"\\u0627\\u0644\\u0628\\u062f\\u0621 \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0639\\u0628\\u0631 \\u0627\\u0644\\u0625\\u0646\\u062a\\u0631\\u0646\\u062a\",\"sub_heading\":\"\\u0642\\u0645 \\u0628\\u0627\\u0644\\u062a\\u0633\\u062c\\u064a\\u0644 \\u0648\\u0625\\u062f\\u0627\\u0631\\u0629 \\u062d\\u0633\\u0627\\u0628\\u0627\\u062a\\u0643 \\u0648\\u0625\\u062a\\u0645\\u0627\\u0645 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0628\\u0633\\u0647\\u0648\\u0644\\u0629 \\u0648\\u0623\\u0645\\u0627\\u0646.\"}},\"items\":{\"6710e2e06fe58\":{\"language\":{\"en\":{\"item_title\":\"Sign up securely by providing basic personal information. Verify your identity through a verification process ensuring the safety of your account.\"},\"es\":{\"item_title\":\"Reg\\u00edstrate de forma segura proporcionando informaci\\u00f3n personal b\\u00e1sica. Verifica tu identidad mediante un proceso de verificaci\\u00f3n que garantiza la seguridad de tu cuenta.\"},\"ar\":{\"item_title\":\"\\u0642\\u0645 \\u0628\\u0627\\u0644\\u062a\\u0633\\u062c\\u064a\\u0644 \\u0628\\u0623\\u0645\\u0627\\u0646 \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u062a\\u0642\\u062f\\u064a\\u0645 \\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0634\\u062e\\u0635\\u064a\\u0629 \\u0623\\u0633\\u0627\\u0633\\u064a\\u0629. \\u0642\\u0645 \\u0628\\u0627\\u0644\\u062a\\u062d\\u0642\\u0642 \\u0645\\u0646 \\u0647\\u0648\\u064a\\u062a\\u0643 \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u0627\\u0644\\u062a\\u062d\\u0642\\u0642 \\u0627\\u0644\\u062a\\u064a \\u062a\\u0636\\u0645\\u0646 \\u0623\\u0645\\u0627\\u0646 \\u062d\\u0633\\u0627\\u0628\\u0643.\"}},\"id\":\"6710e2e06fe58\",\"status\":1},\"6710e2eb7d782\":{\"language\":{\"en\":{\"item_title\":\"Upon login, access an intuitive dashboard displaying account balances, recent transactions, and quick links to various services, simplifying navigation.\"},\"es\":{\"item_title\":\"Al iniciar sesi\\u00f3n, acceda a un panel intuitivo que muestra los saldos de las cuentas, las transacciones recientes y enlaces r\\u00e1pidos a varios servicios, lo que simplifica la navegaci\\u00f3n.\"},\"ar\":{\"item_title\":\"\\u0639\\u0646\\u062f \\u062a\\u0633\\u062c\\u064a\\u0644 \\u0627\\u0644\\u062f\\u062e\\u0648\\u0644\\u060c \\u064a\\u0645\\u0643\\u0646\\u0643 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u0649 \\u0644\\u0648\\u062d\\u0629 \\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0628\\u062f\\u064a\\u0647\\u064a\\u0629 \\u062a\\u0639\\u0631\\u0636 \\u0623\\u0631\\u0635\\u062f\\u0629 \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\\u0627\\u062a \\u0648\\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0623\\u062e\\u064a\\u0631\\u0629 \\u0648\\u0631\\u0648\\u0627\\u0628\\u0637 \\u0633\\u0631\\u064a\\u0639\\u0629 \\u0644\\u0645\\u062e\\u062a\\u0644\\u0641 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a\\u060c \\u0645\\u0645\\u0627 \\u064a\\u0633\\u0647\\u0644 \\u0627\\u0644\\u062a\\u0646\\u0642\\u0644.\"}},\"id\":\"6710e2eb7d782\",\"status\":1},\"6710e2fb0b6bb\":{\"language\":{\"en\":{\"item_title\":\"Initiate transactions effortlessly by selecting the desired option (transfer, payment, etc.), inputting details, and confirming securely.\"},\"es\":{\"item_title\":\"Inicie transacciones sin esfuerzo seleccionando la opci\\u00f3n deseada (transferencia, pago, etc.), ingresando detalles y confirmando de forma segura.\"},\"ar\":{\"item_title\":\"\\u0642\\u0645 \\u0628\\u0625\\u062c\\u0631\\u0627\\u0621 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0628\\u0633\\u0647\\u0648\\u0644\\u0629 \\u0639\\u0646 \\u0637\\u0631\\u064a\\u0642 \\u062a\\u062d\\u062f\\u064a\\u062f \\u0627\\u0644\\u062e\\u064a\\u0627\\u0631 \\u0627\\u0644\\u0645\\u0637\\u0644\\u0648\\u0628 (\\u062a\\u062d\\u0648\\u064a\\u0644\\u060c \\u062f\\u0641\\u0639\\u060c \\u0625\\u0644\\u062e)\\u060c \\u0648\\u0625\\u062f\\u062e\\u0627\\u0644 \\u0627\\u0644\\u062a\\u0641\\u0627\\u0635\\u064a\\u0644\\u060c \\u0648\\u062a\\u0623\\u0643\\u064a\\u062f\\u0647\\u0627 \\u0628\\u0634\\u0643\\u0644 \\u0622\\u0645\\u0646.\"}},\"id\":\"6710e2fb0b6bb\",\"status\":1},\"6710e3a8cabd4\":{\"language\":{\"en\":{\"item_title\":\"Reach out to our responsive customer support team via various channels (chat, email, phone) for assistance, guidance, or issue resolution.\"},\"es\":{\"item_title\":\"Comun\\u00edquese con nuestro receptivo equipo de atenci\\u00f3n al cliente a trav\\u00e9s de varios canales (chat, correo electr\\u00f3nico, tel\\u00e9fono) para obtener ayuda, orientaci\\u00f3n o resoluci\\u00f3n de problemas.\"},\"ar\":{\"item_title\":\"\\u064a\\u0645\\u0643\\u0646\\u0643 \\u0627\\u0644\\u062a\\u0648\\u0627\\u0635\\u0644 \\u0645\\u0639 \\u0641\\u0631\\u064a\\u0642 \\u062f\\u0639\\u0645 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621 \\u0627\\u0644\\u0645\\u062a\\u062c\\u0627\\u0648\\u0628 \\u0644\\u062f\\u064a\\u0646\\u0627 \\u0639\\u0628\\u0631 \\u0642\\u0646\\u0648\\u0627\\u062a \\u0645\\u062e\\u062a\\u0644\\u0641\\u0629 (\\u0627\\u0644\\u062f\\u0631\\u062f\\u0634\\u0629\\u060c \\u0627\\u0644\\u0628\\u0631\\u064a\\u062f \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\\u060c \\u0627\\u0644\\u0647\\u0627\\u062a\\u0641) \\u0644\\u0644\\u062d\\u0635\\u0648\\u0644 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u0629 \\u0623\\u0648 \\u0627\\u0644\\u062a\\u0648\\u062c\\u064a\\u0647 \\u0623\\u0648 \\u062d\\u0644 \\u0627\\u0644\\u0645\\u0634\\u0643\\u0644\\u0629.\"}},\"id\":\"6710e3a8cabd4\",\"status\":1}}}', 1, NULL, '2024-10-17 14:11:20', '2024-12-07 14:45:56'),
(5, 'security-section', '{\"language\":{\"en\":{\"heading\":\"Your Security, Our Priority\",\"sub_heading\":\"At iBanking, we utilize cutting-edge encryption, multi-factor authentication, and advanced monitoring to protect your financial data\\u2014keeping your money and information safe every step of the way.\"},\"es\":{\"heading\":\"Tu seguridad, nuestra prioridad\",\"sub_heading\":\"En iBanking, utilizamos encriptaci\\u00f3n de \\u00faltima generaci\\u00f3n, autenticaci\\u00f3n multifactor y monitoreo avanzado para proteger sus datos financieros, manteniendo su dinero e informaci\\u00f3n seguros en cada paso del proceso.\"},\"ar\":{\"heading\":\"\\u0623\\u0645\\u0646\\u0643 \\u0647\\u0648 \\u0623\\u0648\\u0644\\u0648\\u064a\\u062a\\u0646\\u0627\",\"sub_heading\":\"\\u0641\\u064a iBanking\\u060c \\u0646\\u0633\\u062a\\u062e\\u062f\\u0645 \\u062a\\u0634\\u0641\\u064a\\u0631\\u064b\\u0627 \\u0645\\u062a\\u0637\\u0648\\u0631\\u064b\\u0627 \\u0648\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0645\\u062a\\u0639\\u062f\\u062f\\u0629 \\u0627\\u0644\\u0639\\u0648\\u0627\\u0645\\u0644 \\u0648\\u0645\\u0631\\u0627\\u0642\\u0628\\u0629 \\u0645\\u062a\\u0642\\u062f\\u0645\\u0629 \\u0644\\u062d\\u0645\\u0627\\u064a\\u0629 \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0648\\u0627\\u0644\\u062d\\u0641\\u0627\\u0638 \\u0639\\u0644\\u0649 \\u0623\\u0645\\u0648\\u0627\\u0644\\u0643 \\u0648\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a\\u0643 \\u0622\\u0645\\u0646\\u0629 \\u0641\\u064a \\u0643\\u0644 \\u062e\\u0637\\u0648\\u0629 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0631\\u064a\\u0642.\"}},\"items\":{\"6710ec38908c6\":{\"language\":{\"en\":{\"title\":\"SMS or Email Verification\",\"description\":\"Enhance account security with dual verification methods. Receive a secure OTP (One-Time Password) via SMS or email to confirm your identity before accessing sensitive features.\"},\"es\":{\"title\":\"Verificaci\\u00f3n por SMS o correo electr\\u00f3nico\",\"description\":\"Mejore la seguridad de su cuenta con m\\u00e9todos de verificaci\\u00f3n dual. Reciba una contrase\\u00f1a de un solo uso (OTP) segura por SMS o correo electr\\u00f3nico para confirmar su identidad antes de acceder a funciones confidenciales.\"},\"ar\":{\"title\":\"\\u0627\\u0644\\u062a\\u062d\\u0642\\u0642 \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u0627\\u0644\\u0631\\u0633\\u0627\\u0626\\u0644 \\u0627\\u0644\\u0642\\u0635\\u064a\\u0631\\u0629 \\u0623\\u0648 \\u0627\\u0644\\u0628\\u0631\\u064a\\u062f \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\",\"description\":\"\\u0639\\u0632\\u0632 \\u0623\\u0645\\u0627\\u0646 \\u062d\\u0633\\u0627\\u0628\\u0643 \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0637\\u0631\\u0642 \\u0627\\u0644\\u062a\\u062d\\u0642\\u0642 \\u0627\\u0644\\u0645\\u0632\\u062f\\u0648\\u062c\\u0629. \\u0627\\u062d\\u0635\\u0644 \\u0639\\u0644\\u0649 \\u0643\\u0644\\u0645\\u0629 \\u0645\\u0631\\u0648\\u0631 \\u0622\\u0645\\u0646\\u0629 \\u0644\\u0645\\u0631\\u0629 \\u0648\\u0627\\u062d\\u062f\\u0629 (OTP) \\u0639\\u0628\\u0631 \\u0627\\u0644\\u0631\\u0633\\u0627\\u0626\\u0644 \\u0627\\u0644\\u0642\\u0635\\u064a\\u0631\\u0629 \\u0623\\u0648 \\u0627\\u0644\\u0628\\u0631\\u064a\\u062f \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a \\u0644\\u062a\\u0623\\u0643\\u064a\\u062f \\u0647\\u0648\\u064a\\u062a\\u0643 \\u0642\\u0628\\u0644 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u0649 \\u0627\\u0644\\u0645\\u064a\\u0632\\u0627\\u062a \\u0627\\u0644\\u062d\\u0633\\u0627\\u0633\\u0629.\"}},\"id\":\"6710ec38908c6\",\"icon\":\"fas fa-unlock-alt\"},\"6710ec56369b7\":{\"language\":{\"en\":{\"title\":\"KYC Verification\",\"description\":\"Stay protected with our GDPR-compliant Know Your Customer (KYC) process. Safeguard your account with secure identity verification built for your safety and convenience.\"},\"es\":{\"title\":\"Verificaci\\u00f3n KYC\",\"description\":\"Mant\\u00e9ngase protegido con nuestro proceso Conozca a su cliente (KYC) que cumple con el RGPD. Proteja su cuenta con una verificaci\\u00f3n de identidad segura dise\\u00f1ada para su seguridad y conveniencia.\"},\"ar\":{\"title\":\"\\u0627\\u0644\\u062a\\u062d\\u0642\\u0642 \\u0645\\u0646 \\u0647\\u0648\\u064a\\u0629 \\u0627\\u0644\\u0639\\u0645\\u064a\\u0644\",\"description\":\"\\u062d\\u0627\\u0641\\u0638 \\u0639\\u0644\\u0649 \\u0633\\u0644\\u0627\\u0645\\u062a\\u0643 \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u0639\\u0645\\u0644\\u064a\\u0629 \\\"\\u0627\\u0639\\u0631\\u0641 \\u0639\\u0645\\u064a\\u0644\\u0643\\\" \\u0627\\u0644\\u0645\\u062a\\u0648\\u0627\\u0641\\u0642\\u0629 \\u0645\\u0639 \\u0627\\u0644\\u0644\\u0627\\u0626\\u062d\\u0629 \\u0627\\u0644\\u0639\\u0627\\u0645\\u0629 \\u0644\\u062d\\u0645\\u0627\\u064a\\u0629 \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a (GDPR). \\u0627\\u062d\\u0645\\u0650 \\u062d\\u0633\\u0627\\u0628\\u0643 \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u0627\\u0644\\u062a\\u062d\\u0642\\u0642 \\u0627\\u0644\\u0622\\u0645\\u0646 \\u0645\\u0646 \\u0627\\u0644\\u0647\\u0648\\u064a\\u0629 \\u0627\\u0644\\u0645\\u0635\\u0645\\u0645 \\u0644\\u0633\\u0644\\u0627\\u0645\\u062a\\u0643 \\u0648\\u0631\\u0627\\u062d\\u062a\\u0643.\"}},\"id\":\"6710ec56369b7\",\"icon\":\"fas fa-certificate\"},\"6710ec77ed964\":{\"language\":{\"en\":{\"title\":\"Two-Factor Authentication (2FA)\",\"description\":\"Add an extra layer of protection with 2FA, ensuring only you can access your account\\u2014even if your password is compromised.\"},\"es\":{\"title\":\"Autenticaci\\u00f3n de dos factores (2FA)\",\"description\":\"Agregue una capa adicional de protecci\\u00f3n con 2FA, garantizando que solo usted pueda acceder a su cuenta, incluso si su contrase\\u00f1a est\\u00e1 comprometida.\"},\"ar\":{\"title\":\"\\u0627\\u0644\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0627\\u0644\\u062b\\u0646\\u0627\\u0626\\u064a\\u0629 (2FA)\",\"description\":\"\\u0623\\u0636\\u0641 \\u0637\\u0628\\u0642\\u0629 \\u0625\\u0636\\u0627\\u0641\\u064a\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u062d\\u0645\\u0627\\u064a\\u0629 \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 2FA\\u060c \\u0645\\u0645\\u0627 \\u064a\\u0636\\u0645\\u0646 \\u0644\\u0643 \\u0623\\u0646\\u062a \\u0641\\u0642\\u0637 \\u0627\\u0644\\u0642\\u062f\\u0631\\u0629 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u0649 \\u062d\\u0633\\u0627\\u0628\\u0643\\u060c \\u062d\\u062a\\u0649 \\u0625\\u0630\\u0627 \\u062a\\u0645 \\u0627\\u062e\\u062a\\u0631\\u0627\\u0642 \\u0643\\u0644\\u0645\\u0629 \\u0645\\u0631\\u0648\\u0631\\u0643.\"}},\"id\":\"6710ec77ed964\",\"icon\":\"fas fa-fingerprint\"},\"6710ec9520909\":{\"language\":{\"en\":{\"title\":\"End-to-End Encryption\",\"description\":\"Keep your communications and transactions private. Our encryption ensures that only you can access your account details\\u2014no third parties involved.\"},\"es\":{\"title\":\"Cifrado de extremo a extremo\",\"description\":\"Mantenga la privacidad de sus comunicaciones y transacciones. Nuestro cifrado garantiza que solo usted pueda acceder a los detalles de su cuenta, sin terceros involucrados.\"},\"ar\":{\"title\":\"\\u0627\\u0644\\u062a\\u0634\\u0641\\u064a\\u0631 \\u0645\\u0646 \\u0627\\u0644\\u0628\\u062f\\u0627\\u064a\\u0629 \\u0625\\u0644\\u0649 \\u0627\\u0644\\u0646\\u0647\\u0627\\u064a\\u0629\",\"description\":\"\\u062d\\u0627\\u0641\\u0638 \\u0639\\u0644\\u0649 \\u062e\\u0635\\u0648\\u0635\\u064a\\u0629 \\u0627\\u062a\\u0635\\u0627\\u0644\\u0627\\u062a\\u0643 \\u0648\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a\\u0643. \\u064a\\u0636\\u0645\\u0646 \\u0627\\u0644\\u062a\\u0634\\u0641\\u064a\\u0631 \\u0644\\u062f\\u064a\\u0646\\u0627 \\u0623\\u0646\\u0643 \\u0648\\u062d\\u062f\\u0643 \\u0645\\u0646 \\u064a\\u0645\\u0643\\u0646\\u0647 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u0649 \\u062a\\u0641\\u0627\\u0635\\u064a\\u0644 \\u062d\\u0633\\u0627\\u0628\\u0643 - \\u0648\\u0644\\u0627 \\u064a\\u0648\\u062c\\u062f \\u0637\\u0631\\u0641 \\u062b\\u0627\\u0644\\u062b \\u0645\\u0634\\u0627\\u0631\\u0643.\"}},\"id\":\"6710ec9520909\",\"icon\":\"fas fa-shield-alt\"}}}', 1, NULL, '2024-10-17 14:51:06', '2024-12-07 14:51:31'),
(6, 'overview-section', '{\"language\":{\"en\":{\"heading\":\"Transforming Your Banking Experience\",\"sub_heading\":\"iBanking simplifies your financial journey with secure transactions, user-friendly features, and cutting-edge solutions tailored for your needs.\"},\"es\":{\"heading\":\"Transformando su experiencia bancaria\",\"sub_heading\":\"iBanking simplifica su experiencia financiera con transacciones seguras, funciones f\\u00e1ciles de usar y soluciones de vanguardia adaptadas a sus necesidades.\"},\"ar\":{\"heading\":\"\\u062a\\u062d\\u0648\\u064a\\u0644 \\u062a\\u062c\\u0631\\u0628\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629\",\"sub_heading\":\"\\u064a\\u0633\\u0627\\u0639\\u062f\\u0643 iBanking \\u0639\\u0644\\u0649 \\u062a\\u0628\\u0633\\u064a\\u0637 \\u0631\\u062d\\u0644\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0622\\u0645\\u0646\\u0629 \\u0648\\u0627\\u0644\\u0645\\u064a\\u0632\\u0627\\u062a \\u0633\\u0647\\u0644\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0648\\u0627\\u0644\\u062d\\u0644\\u0648\\u0644 \\u0627\\u0644\\u0645\\u062a\\u0637\\u0648\\u0631\\u0629 \\u0627\\u0644\\u0645\\u0635\\u0645\\u0645\\u0629 \\u062e\\u0635\\u064a\\u0635\\u064b\\u0627 \\u0644\\u062a\\u0644\\u0628\\u064a\\u0629 \\u0627\\u062d\\u062a\\u064a\\u0627\\u062c\\u0627\\u062a\\u0643.\"}},\"items\":{\"6711d1fbf15fd\":{\"language\":{\"en\":{\"title\":\"Total User\",\"description\":\"Join 90K+ users worldwide who trust iBanking for seamless, secure, and innovative banking solutions.\"},\"es\":{\"title\":\"Usuario total\",\"description\":\"\\u00danase a m\\u00e1s de 90.000 usuarios de todo el mundo que conf\\u00edan en iBanking para obtener soluciones bancarias fluidas, seguras e innovadoras.\"},\"ar\":{\"title\":\"\\u0625\\u062c\\u0645\\u0627\\u0644\\u064a \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646\",\"description\":\"\\u0627\\u0646\\u0636\\u0645 \\u0625\\u0644\\u0649 \\u0623\\u0643\\u062b\\u0631 \\u0645\\u0646 90 \\u0623\\u0644\\u0641 \\u0645\\u0633\\u062a\\u062e\\u062f\\u0645 \\u062d\\u0648\\u0644 \\u0627\\u0644\\u0639\\u0627\\u0644\\u0645 \\u064a\\u062b\\u0642\\u0648\\u0646 \\u0641\\u064a iBanking \\u0644\\u0644\\u062d\\u0635\\u0648\\u0644 \\u0639\\u0644\\u0649 \\u062d\\u0644\\u0648\\u0644 \\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0633\\u0644\\u0633\\u0629 \\u0648\\u0622\\u0645\\u0646\\u0629 \\u0648\\u0645\\u0628\\u062a\\u0643\\u0631\\u0629.\"}},\"id\":\"6711d1fbf15fd\",\"counter_value\":\"90000\"},\"6711d2dd40b70\":{\"language\":{\"en\":{\"title\":\"Active Users\",\"description\":\"Over 20K users log in daily to manage their finances effortlessly with iBanking\'s robust and reliable platform.\"},\"es\":{\"title\":\"Usuarias activas\",\"description\":\"M\\u00e1s de 20.000 usuarios inician sesi\\u00f3n diariamente para administrar sus finanzas sin esfuerzo con la plataforma s\\u00f3lida y confiable de iBanking.\"},\"ar\":{\"title\":\"\\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u0648\\u0646 \\u0627\\u0644\\u0646\\u0634\\u0637\\u0648\\u0646\",\"description\":\"\\u064a\\u0642\\u0648\\u0645 \\u0623\\u0643\\u062b\\u0631 \\u0645\\u0646 20 \\u0623\\u0644\\u0641 \\u0645\\u0633\\u062a\\u062e\\u062f\\u0645 \\u0628\\u062a\\u0633\\u062c\\u064a\\u0644 \\u0627\\u0644\\u062f\\u062e\\u0648\\u0644 \\u064a\\u0648\\u0645\\u064a\\u064b\\u0627 \\u0644\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0634\\u0624\\u0648\\u0646\\u0647\\u0645 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0628\\u0643\\u0644 \\u0633\\u0647\\u0648\\u0644\\u0629 \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0645\\u0646\\u0635\\u0629 iBanking \\u0627\\u0644\\u0642\\u0648\\u064a\\u0629 \\u0648\\u0627\\u0644\\u0645\\u0648\\u062b\\u0648\\u0642\\u0629.\"}},\"id\":\"6711d2dd40b70\",\"counter_value\":\"20000\"},\"6711d2eda3954\":{\"language\":{\"en\":{\"title\":\"Successful Transactions\",\"description\":\"Processed with precision and security, over 1K daily transactions showcase iBanking\\u2019s efficiency and trustworthiness.\"},\"es\":{\"title\":\"Transacciones exitosas\",\"description\":\"Procesadas con precisi\\u00f3n y seguridad, m\\u00e1s de 1.000 transacciones diarias demuestran la eficiencia y confiabilidad de iBanking.\"},\"ar\":{\"title\":\"\\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0646\\u0627\\u062c\\u062d\\u0629\",\"description\":\"\\u064a\\u062a\\u0645 \\u062a\\u0646\\u0641\\u064a\\u0630 \\u0623\\u0643\\u062b\\u0631 \\u0645\\u0646 1000 \\u0645\\u0639\\u0627\\u0645\\u0644\\u0629 \\u064a\\u0648\\u0645\\u064a\\u0629 \\u0628\\u062f\\u0642\\u0629 \\u0648\\u0623\\u0645\\u0627\\u0646\\u060c \\u0645\\u0645\\u0627 \\u064a\\u0639\\u0643\\u0633 \\u0643\\u0641\\u0627\\u0621\\u0629 iBanking \\u0648\\u0645\\u0648\\u062b\\u0648\\u0642\\u064a\\u062a\\u0647\\u0627.\"}},\"id\":\"6711d2eda3954\",\"counter_value\":\"1000\"}},\"image\":\"08202b46-4c62-4a8b-b8ca-4779821f765e.webp\"}', 1, NULL, '2024-10-18 07:08:01', '2024-12-07 14:55:44'),
(7, 'choose-us-section', '{\"language\":{\"en\":{\"heading\":\"Why Choose Us\",\"sub_heading\":\"At iBanking, we prioritize your needs. From advanced security to an intuitive design, we deliver a banking experience that\\u2019s secure, seamless, and tailored to you.\"},\"es\":{\"heading\":\"\\u00bfPor qu\\u00e9 elegirnos?\",\"sub_heading\":\"En iBanking, priorizamos sus necesidades. Desde seguridad avanzada hasta un dise\\u00f1o intuitivo, ofrecemos una experiencia bancaria segura, fluida y personalizada.\"},\"ar\":{\"heading\":\"\\u0644\\u0645\\u0627\\u0630\\u0627 \\u062a\\u062e\\u062a\\u0627\\u0631\\u0646\\u0627\\u061f\",\"sub_heading\":\"\\u0641\\u064a iBanking\\u060c \\u0646\\u0636\\u0639 \\u0627\\u062d\\u062a\\u064a\\u0627\\u062c\\u0627\\u062a\\u0643 \\u0641\\u064a \\u0627\\u0644\\u0645\\u0642\\u0627\\u0645 \\u0627\\u0644\\u0623\\u0648\\u0644. \\u0628\\u062f\\u0621\\u064b\\u0627 \\u0645\\u0646 \\u0627\\u0644\\u0623\\u0645\\u0627\\u0646 \\u0627\\u0644\\u0645\\u062a\\u0642\\u062f\\u0645 \\u0648\\u062d\\u062a\\u0649 \\u0627\\u0644\\u062a\\u0635\\u0645\\u064a\\u0645 \\u0627\\u0644\\u0628\\u062f\\u064a\\u0647\\u064a\\u060c \\u0646\\u0642\\u062f\\u0645 \\u0644\\u0643 \\u062a\\u062c\\u0631\\u0628\\u0629 \\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0622\\u0645\\u0646\\u0629 \\u0648\\u0633\\u0644\\u0633\\u0629 \\u0648\\u0645\\u0635\\u0645\\u0645\\u0629 \\u062e\\u0635\\u064a\\u0635\\u064b\\u0627 \\u0644\\u0643.\"}},\"items\":{\"6711e2d3c3769\":{\"language\":{\"en\":{\"title\":\"User-Centric Design\",\"description\":\"Enjoy a platform designed with you in mind\\u2014our intuitive interface ensures easy navigation and effortless banking.\"},\"es\":{\"title\":\"Dise\\u00f1o centrado en el usuario\",\"description\":\"Disfrute de una plataforma dise\\u00f1ada pensando en usted: nuestra interfaz intuitiva garantiza una navegaci\\u00f3n sencilla y operaciones bancarias sin esfuerzo.\"},\"ar\":{\"title\":\"\\u062a\\u0635\\u0645\\u064a\\u0645 \\u064a\\u0631\\u0643\\u0632 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\",\"description\":\"\\u0627\\u0633\\u062a\\u0645\\u062a\\u0639 \\u0628\\u0645\\u0646\\u0635\\u0629 \\u0645\\u0635\\u0645\\u0645\\u0629 \\u062e\\u0635\\u064a\\u0635\\u064b\\u0627 \\u0644\\u062a\\u0646\\u0627\\u0633\\u0628 \\u0627\\u062d\\u062a\\u064a\\u0627\\u062c\\u0627\\u062a\\u0643 - \\u062a\\u0636\\u0645\\u0646 \\u0648\\u0627\\u062c\\u0647\\u062a\\u0646\\u0627 \\u0627\\u0644\\u0628\\u062f\\u064a\\u0647\\u064a\\u0629 \\u0627\\u0644\\u062a\\u0646\\u0642\\u0644 \\u0627\\u0644\\u0633\\u0647\\u0644 \\u0648\\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0627\\u0644\\u0633\\u0647\\u0644\\u0629.\"}},\"id\":\"6711e2d3c3769\",\"icon\":\"fas fa-user-check\"},\"6753c8025f9dd\":{\"language\":{\"en\":{\"title\":\"Advanced Security\",\"description\":\"Your safety is our top priority. We use multi-factor authentication, encryption, and real-time monitoring to protect your financial data.\"},\"es\":{\"title\":\"Seguridad avanzada\",\"description\":\"Su seguridad es nuestra m\\u00e1xima prioridad. Utilizamos autenticaci\\u00f3n multifactor, encriptaci\\u00f3n y monitoreo en tiempo real para proteger sus datos financieros.\"},\"ar\":{\"title\":\"\\u0627\\u0644\\u0623\\u0645\\u0627\\u0646 \\u0627\\u0644\\u0645\\u062a\\u0642\\u062f\\u0645\",\"description\":\"\\u0633\\u0644\\u0627\\u0645\\u062a\\u0643 \\u0647\\u064a \\u0623\\u0648\\u0644\\u0648\\u064a\\u062a\\u0646\\u0627 \\u0627\\u0644\\u0642\\u0635\\u0648\\u0649. \\u0646\\u0633\\u062a\\u062e\\u062f\\u0645 \\u0627\\u0644\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0645\\u062a\\u0639\\u062f\\u062f\\u0629 \\u0627\\u0644\\u0639\\u0648\\u0627\\u0645\\u0644 \\u0648\\u0627\\u0644\\u062a\\u0634\\u0641\\u064a\\u0631 \\u0648\\u0627\\u0644\\u0645\\u0631\\u0627\\u0642\\u0628\\u0629 \\u0641\\u064a \\u0627\\u0644\\u0648\\u0642\\u062a \\u0627\\u0644\\u0641\\u0639\\u0644\\u064a \\u0644\\u062d\\u0645\\u0627\\u064a\\u0629 \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629.\"}},\"id\":\"6753c8025f9dd\",\"icon\":\"fas fa-shield-alt\"},\"6753c8475287d\":{\"language\":{\"en\":{\"title\":\"24\\/7 Accessibility\",\"description\":\"Bank whenever and wherever you want. With iBanking, you can access your account securely on any device, any time.\"},\"es\":{\"title\":\"Accesibilidad 24\\/7\",\"description\":\"Realice operaciones bancarias cuando y donde quiera. Con iBanking, puede acceder a su cuenta de forma segura desde cualquier dispositivo y en cualquier momento.\"},\"ar\":{\"title\":\"\\u0625\\u0645\\u0643\\u0627\\u0646\\u064a\\u0629 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0639\\u0644\\u0649 \\u0645\\u062f\\u0627\\u0631 \\u0627\\u0644\\u0633\\u0627\\u0639\\u0629 \\u0637\\u0648\\u0627\\u0644 \\u0623\\u064a\\u0627\\u0645 \\u0627\\u0644\\u0623\\u0633\\u0628\\u0648\\u0639\",\"description\":\"\\u064a\\u0645\\u0643\\u0646\\u0643 \\u0625\\u062c\\u0631\\u0627\\u0621 \\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0645\\u062a\\u0649 \\u0634\\u0626\\u062a \\u0648\\u0623\\u064a\\u0646\\u0645\\u0627 \\u062a\\u0631\\u064a\\u062f. \\u0645\\u0639 iBanking\\u060c \\u064a\\u0645\\u0643\\u0646\\u0643 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u0649 \\u062d\\u0633\\u0627\\u0628\\u0643 \\u0628\\u0623\\u0645\\u0627\\u0646 \\u0639\\u0644\\u0649 \\u0623\\u064a \\u062c\\u0647\\u0627\\u0632 \\u0648\\u0641\\u064a \\u0623\\u064a \\u0648\\u0642\\u062a.\"}},\"id\":\"6753c8475287d\",\"icon\":\"fas fa-universal-access\"},\"6753c88792054\":{\"language\":{\"en\":{\"title\":\"Responsive Customer Support\",\"description\":\"Get the help you need, when you need it. Our dedicated support team is available via chat, email, or phone to resolve your queries.\"},\"es\":{\"title\":\"Atenci\\u00f3n al cliente receptiva\",\"description\":\"Obt\\u00e9n la ayuda que necesitas, cuando la necesitas. Nuestro equipo de soporte especializado est\\u00e1 disponible por chat, correo electr\\u00f3nico o tel\\u00e9fono para resolver tus consultas.\"},\"ar\":{\"title\":\"\\u062f\\u0639\\u0645 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621 \\u0627\\u0644\\u0645\\u062a\\u062c\\u0627\\u0648\\u0628\",\"description\":\"\\u0627\\u062d\\u0635\\u0644 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u0629 \\u0627\\u0644\\u062a\\u064a \\u062a\\u062d\\u062a\\u0627\\u062c\\u0647\\u0627 \\u0639\\u0646\\u062f\\u0645\\u0627 \\u062a\\u062d\\u062a\\u0627\\u062c \\u0625\\u0644\\u064a\\u0647\\u0627. \\u0641\\u0631\\u064a\\u0642 \\u0627\\u0644\\u062f\\u0639\\u0645 \\u0627\\u0644\\u0645\\u062a\\u062e\\u0635\\u0635 \\u0644\\u062f\\u064a\\u0646\\u0627 \\u0645\\u062a\\u0627\\u062d \\u0639\\u0628\\u0631 \\u0627\\u0644\\u062f\\u0631\\u062f\\u0634\\u0629 \\u0623\\u0648 \\u0627\\u0644\\u0628\\u0631\\u064a\\u062f \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a \\u0623\\u0648 \\u0627\\u0644\\u0647\\u0627\\u062a\\u0641 \\u0644\\u062d\\u0644 \\u0627\\u0633\\u062a\\u0641\\u0633\\u0627\\u0631\\u0627\\u062a\\u0643.\"}},\"id\":\"6753c88792054\",\"icon\":\"fas fa-taxi\"}}}', 1, NULL, '2024-10-18 08:23:16', '2024-12-07 15:01:11'),
(8, 'download-app-section', '{\"image\":\"52613cc9-8c18-479c-a013-cdd05b7079a5.webp\",\"language\":{\"en\":{\"title\":\"Get the iBanking App\",\"heading\":\"Bank on the Go. Our app gives you secure access, fast transactions, and full control of your finances\\u2014anytime, anywhere.\",\"sub_heading\":\"Download the iBanking app and enjoy effortless banking at your fingertips. With an intuitive design, secure access, and real-time monitoring, you can transfer funds, pay bills, and track your account wherever you are. Seamless, secure, and convenient banking\\u2014all in one app.\"},\"es\":{\"title\":\"Obtenga la aplicaci\\u00f3n iBanking\",\"heading\":\"Realice operaciones bancarias desde cualquier lugar. Nuestra aplicaci\\u00f3n le brinda acceso seguro, transacciones r\\u00e1pidas y control total de sus finanzas, en cualquier momento y en cualquier lugar.\",\"sub_heading\":\"Descargue la aplicaci\\u00f3n iBanking y disfrute de operaciones bancarias sencillas y al alcance de su mano. Con un dise\\u00f1o intuitivo, acceso seguro y monitoreo en tiempo real, puede transferir fondos, pagar facturas y hacer un seguimiento de su cuenta dondequiera que est\\u00e9. Operaciones bancarias sencillas, seguras y convenientes, todo en una sola aplicaci\\u00f3n.\"},\"ar\":{\"title\":\"\\u0627\\u062d\\u0635\\u0644 \\u0639\\u0644\\u0649 \\u062a\\u0637\\u0628\\u064a\\u0642 iBanking\",\"heading\":\"\\u0642\\u0645 \\u0628\\u0625\\u062c\\u0631\\u0627\\u0621 \\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0623\\u064a\\u0646\\u0645\\u0627 \\u0643\\u0646\\u062a. \\u064a\\u0648\\u0641\\u0631 \\u0644\\u0643 \\u062a\\u0637\\u0628\\u064a\\u0642\\u0646\\u0627 \\u0625\\u0645\\u0643\\u0627\\u0646\\u064a\\u0629 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0627\\u0644\\u0622\\u0645\\u0646 \\u0648\\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0633\\u0631\\u064a\\u0639\\u0629 \\u0648\\u0627\\u0644\\u062a\\u062d\\u0643\\u0645 \\u0627\\u0644\\u0643\\u0627\\u0645\\u0644 \\u0641\\u064a \\u0623\\u0645\\u0648\\u0627\\u0644\\u0643 - \\u0641\\u064a \\u0623\\u064a \\u0648\\u0642\\u062a \\u0648\\u0641\\u064a \\u0623\\u064a \\u0645\\u0643\\u0627\\u0646.\",\"sub_heading\":\"\\u0642\\u0645 \\u0628\\u062a\\u0646\\u0632\\u064a\\u0644 \\u062a\\u0637\\u0628\\u064a\\u0642 iBanking \\u0648\\u0627\\u0633\\u062a\\u0645\\u062a\\u0639 \\u0628\\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0627\\u0644\\u0633\\u0647\\u0644\\u0629 \\u0641\\u064a \\u0645\\u062a\\u0646\\u0627\\u0648\\u0644 \\u064a\\u062f\\u0643. \\u0628\\u0641\\u0636\\u0644 \\u0627\\u0644\\u062a\\u0635\\u0645\\u064a\\u0645 \\u0627\\u0644\\u0628\\u062f\\u064a\\u0647\\u064a \\u0648\\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0627\\u0644\\u0622\\u0645\\u0646 \\u0648\\u0627\\u0644\\u0645\\u0631\\u0627\\u0642\\u0628\\u0629 \\u0641\\u064a \\u0627\\u0644\\u0648\\u0642\\u062a \\u0627\\u0644\\u0641\\u0639\\u0644\\u064a\\u060c \\u064a\\u0645\\u0643\\u0646\\u0643 \\u062a\\u062d\\u0648\\u064a\\u0644 \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644 \\u0648\\u062f\\u0641\\u0639 \\u0627\\u0644\\u0641\\u0648\\u0627\\u062a\\u064a\\u0631 \\u0648\\u062a\\u062a\\u0628\\u0639 \\u062d\\u0633\\u0627\\u0628\\u0643 \\u0623\\u064a\\u0646\\u0645\\u0627 \\u0643\\u0646\\u062a. \\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0633\\u0644\\u0633\\u0629 \\u0648\\u0622\\u0645\\u0646\\u0629 \\u0648\\u0645\\u0631\\u064a\\u062d\\u0629 - \\u0643\\u0644 \\u0630\\u0644\\u0643 \\u0641\\u064a \\u062a\\u0637\\u0628\\u064a\\u0642 \\u0648\\u0627\\u062d\\u062f.\"}},\"items\":{\"6711e50b828d1\":{\"language\":{\"en\":{\"item_title\":\"Get it on\",\"item_header\":\"Google Pay\"},\"es\":{\"item_title\":\"Cons\\u00edguelo\",\"item_header\":\"Google Pay\"},\"ar\":{\"item_title\":\"\\u0627\\u062d\\u0635\\u0644 \\u0639\\u0644\\u064a\\u0647\",\"item_header\":\"\\u062c\\u0648\\u062c\\u0644 \\u0628\\u0627\\u064a\"}},\"id\":\"6711e50b828d1\",\"image\":\"781d7d85-d34e-4346-9da0-6c719267c174.webp\",\"icon_image\":\"ce620c2d-1848-4299-86d9-ef8d70a26362.webp\",\"link\":\"https:\\/\\/play.google.com\\/store\\/games?hl=en&gl=US\",\"created_at\":\"2024-10-18T04:33:15.569512Z\"},\"6711ec8f8ca82\":{\"language\":{\"en\":{\"item_title\":\"Download On The\",\"item_header\":\"Apple Store\"},\"es\":{\"item_title\":\"Descargar en el\",\"item_header\":\"Tienda Apple\"},\"ar\":{\"item_title\":\"\\u062a\\u0646\\u0632\\u064a\\u0644 \\u0639\\u0644\\u0649\",\"item_header\":\"\\u0645\\u062a\\u062c\\u0631 \\u0627\\u0628\\u0644\"}},\"id\":\"6711ec8f8ca82\",\"image\":\"648ab752-2dbd-41ec-b31d-a2e89a5ee150.webp\",\"icon_image\":\"71d31d67-b3b2-4cfc-8289-8b7eba1bdaff.webp\",\"link\":\"https:\\/\\/www.apple.com\\/app-store\\/\",\"created_at\":\"2024-10-18T05:05:19.590203Z\"}}}', 1, NULL, '2024-10-18 08:32:22', '2024-12-07 15:05:57'),
(9, 'footer-section', '{\"footer\":{\"language\":{\"en\":{\"description\":\"iBanking blends innovative technology with top-tier security, offering a seamless and personalized banking experience for you.\"},\"es\":{\"description\":\"iBanking combina tecnolog\\u00eda innovadora con seguridad de primer nivel, ofreciendo una experiencia bancaria perfecta y personalizada para usted.\"},\"ar\":{\"description\":\"\\u064a\\u062c\\u0645\\u0639 iBanking \\u0628\\u064a\\u0646 \\u0627\\u0644\\u062a\\u0643\\u0646\\u0648\\u0644\\u0648\\u062c\\u064a\\u0627 \\u0627\\u0644\\u0645\\u0628\\u062a\\u0643\\u0631\\u0629 \\u0648\\u0627\\u0644\\u0623\\u0645\\u0646 \\u0645\\u0646 \\u0627\\u0644\\u062f\\u0631\\u062c\\u0629 \\u0627\\u0644\\u0623\\u0648\\u0644\\u0649\\u060c \\u0645\\u0645\\u0627 \\u064a\\u0648\\u0641\\u0631 \\u0644\\u0643 \\u062a\\u062c\\u0631\\u0628\\u0629 \\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0633\\u0644\\u0633\\u0629 \\u0648\\u0645\\u062e\\u0635\\u0635\\u0629.\"}},\"image\":\"\"},\"social_links\":[{\"icon\":\"fab fa-facebook\",\"link\":\"https:\\/\\/www.facebook.com\\/appdevsx\"},{\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/x.com\\/?lang=en\"},{\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"},{\"icon\":\"fab fa-linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"}]}', 1, NULL, '2024-10-18 09:32:03', '2024-12-07 15:06:33'),
(10, 'subscribe-section', '{\"language\":{\"en\":{\"title\":\"Subscribe\",\"description\":\"Stay updated with the latest iBanking news and updates. Simply fill out the form below to subscribe and never miss an update!\"},\"es\":{\"title\":\"Suscribir\",\"description\":\"Mant\\u00e9ngase actualizado con las \\u00faltimas novedades y actualizaciones de iBanking. \\u00a1Simplemente complete el formulario a continuaci\\u00f3n para suscribirse y no perderse ninguna actualizaci\\u00f3n!\"},\"ar\":{\"title\":\"\\u064a\\u0634\\u062a\\u0631\\u0643\",\"description\":\"\\u0643\\u0646 \\u0639\\u0644\\u0649 \\u0627\\u0637\\u0644\\u0627\\u0639 \\u0628\\u0623\\u062d\\u062f\\u062b \\u0623\\u062e\\u0628\\u0627\\u0631 \\u0648\\u062a\\u062d\\u062f\\u064a\\u062b\\u0627\\u062a iBanking. \\u0645\\u0627 \\u0639\\u0644\\u064a\\u0643 \\u0633\\u0648\\u0649 \\u0645\\u0644\\u0621 \\u0627\\u0644\\u0646\\u0645\\u0648\\u0630\\u062c \\u0623\\u062f\\u0646\\u0627\\u0647 \\u0644\\u0644\\u0627\\u0634\\u062a\\u0631\\u0627\\u0643 \\u0648\\u0639\\u062f\\u0645 \\u062a\\u0641\\u0648\\u064a\\u062a \\u0623\\u064a \\u062a\\u062d\\u062f\\u064a\\u062b!\"}}}', 1, NULL, '2024-10-18 10:21:39', '2024-12-07 15:07:24'),
(11, 'about-us-section', '{\"image\":\"f9a15e26-6865-418f-b8ee-5853c67ef736.webp\",\"language\":{\"en\":{\"title\":\"About Us\",\"heading\":\"At iBanking, we\\u2019re redefining the future of banking. From secure online transactions to effortless account management, we put control in your hands.\",\"sub_heading\":\"Welcome to iBanking, where modern banking meets simplicity and security. Our platform empowers you to manage your finances with ease\\u2014whether it\\u2019s transferring funds, monitoring transactions, or accessing statements. With cutting-edge security, 24\\/7 access, and an intuitive design, we make banking convenient, secure, and tailored to your needs. Experience banking reimagined\\u2014simple, secure, and always within reach.\"},\"es\":{\"title\":\"Sobre nosotras\",\"heading\":\"En iBanking, estamos redefiniendo el futuro de la banca. Desde transacciones seguras en l\\u00ednea hasta una gesti\\u00f3n de cuentas sencilla, ponemos el control en sus manos.\",\"sub_heading\":\"Bienvenido a iBanking, donde la banca moderna se combina con la simplicidad y la seguridad. Nuestra plataforma le permite administrar sus finanzas con facilidad, ya sea que se trate de transferir fondos, monitorear transacciones o acceder a sus estados de cuenta. Con seguridad de vanguardia, acceso las 24 horas, los 7 d\\u00edas de la semana y un dise\\u00f1o intuitivo, hacemos que la banca sea conveniente, segura y personalizada seg\\u00fan sus necesidades. Experimente una banca reinventada: simple, segura y siempre a su alcance.\"},\"ar\":{\"title\":\"\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0639\\u0646\\u0627\",\"heading\":\"\\u0641\\u064a iBanking\\u060c \\u0646\\u0639\\u0645\\u0644 \\u0639\\u0644\\u0649 \\u0625\\u0639\\u0627\\u062f\\u0629 \\u062a\\u0639\\u0631\\u064a\\u0641 \\u0645\\u0633\\u062a\\u0642\\u0628\\u0644 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629. \\u0628\\u062f\\u0621\\u064b\\u0627 \\u0645\\u0646 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0622\\u0645\\u0646\\u0629 \\u0639\\u0628\\u0631 \\u0627\\u0644\\u0625\\u0646\\u062a\\u0631\\u0646\\u062a \\u0648\\u062d\\u062a\\u0649 \\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\\u0627\\u062a \\u0628\\u0633\\u0647\\u0648\\u0644\\u0629\\u060c \\u0646\\u0636\\u0639 \\u0627\\u0644\\u0633\\u064a\\u0637\\u0631\\u0629 \\u0628\\u064a\\u0646 \\u064a\\u062f\\u064a\\u0643.\",\"sub_heading\":\"\\u0645\\u0631\\u062d\\u0628\\u064b\\u0627 \\u0628\\u0643 \\u0641\\u064a iBanking\\u060c \\u062d\\u064a\\u062b \\u062a\\u0644\\u062a\\u0642\\u064a \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0627\\u0644\\u062d\\u062f\\u064a\\u062b\\u0629 \\u0628\\u0627\\u0644\\u0628\\u0633\\u0627\\u0637\\u0629 \\u0648\\u0627\\u0644\\u0623\\u0645\\u0627\\u0646. \\u062a\\u062a\\u064a\\u062d \\u0644\\u0643 \\u0645\\u0646\\u0635\\u062a\\u0646\\u0627 \\u0625\\u062f\\u0627\\u0631\\u0629 \\u0634\\u0624\\u0648\\u0646\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0628\\u0643\\u0644 \\u0633\\u0647\\u0648\\u0644\\u0629\\u060c \\u0633\\u0648\\u0627\\u0621 \\u0643\\u0627\\u0646 \\u0630\\u0644\\u0643 \\u0639\\u0646 \\u0637\\u0631\\u064a\\u0642 \\u062a\\u062d\\u0648\\u064a\\u0644 \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644 \\u0623\\u0648 \\u0645\\u0631\\u0627\\u0642\\u0628\\u0629 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0623\\u0648 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u0649 \\u0643\\u0634\\u0648\\u0641 \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\\u0627\\u062a. \\u0628\\u0641\\u0636\\u0644 \\u0627\\u0644\\u0623\\u0645\\u0627\\u0646 \\u0627\\u0644\\u0645\\u062a\\u0637\\u0648\\u0631\\u060c \\u0648\\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0639\\u0644\\u0649 \\u0645\\u062f\\u0627\\u0631 \\u0627\\u0644\\u0633\\u0627\\u0639\\u0629 \\u0637\\u0648\\u0627\\u0644 \\u0623\\u064a\\u0627\\u0645 \\u0627\\u0644\\u0623\\u0633\\u0628\\u0648\\u0639\\u060c \\u0648\\u0627\\u0644\\u062a\\u0635\\u0645\\u064a\\u0645 \\u0627\\u0644\\u0628\\u062f\\u064a\\u0647\\u064a\\u060c \\u0646\\u062c\\u0639\\u0644 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0645\\u0631\\u064a\\u062d\\u0629 \\u0648\\u0622\\u0645\\u0646\\u0629 \\u0648\\u0645\\u0635\\u0645\\u0645\\u0629 \\u062e\\u0635\\u064a\\u0635\\u064b\\u0627 \\u0644\\u062a\\u0644\\u0628\\u064a\\u0629 \\u0627\\u062d\\u062a\\u064a\\u0627\\u062c\\u0627\\u062a\\u0643. \\u0627\\u0633\\u062a\\u0645\\u062a\\u0639 \\u0628\\u062a\\u062c\\u0631\\u0628\\u0629 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0627\\u0644\\u0645\\u062d\\u062f\\u062b\\u0629 - \\u0628\\u0633\\u064a\\u0637\\u0629 \\u0648\\u0622\\u0645\\u0646\\u0629 \\u0648\\u0641\\u064a \\u0645\\u062a\\u0646\\u0627\\u0648\\u0644 \\u0627\\u0644\\u064a\\u062f \\u062f\\u0627\\u0626\\u0645\\u064b\\u0627.\"}}}', 1, NULL, '2024-10-18 13:20:20', '2024-12-07 15:08:56');
INSERT INTO `site_sections` (`id`, `key`, `value`, `status`, `serialize`, `created_at`, `updated_at`) VALUES
(12, 'faq-section', '{\"image\":\"\",\"language\":{\"en\":{\"title\":\"FAQ\",\"heading\":\"Here\\u2019s everything you need to know about using iBanking. If you have more questions, we\\u2019re here to help!\"},\"es\":{\"title\":\"Preguntas frecuentes\",\"heading\":\"Aqu\\u00ed encontrar\\u00e1 todo lo que necesita saber sobre el uso de iBanking. Si tiene m\\u00e1s preguntas, \\u00a1estamos aqu\\u00ed para ayudarle!\"},\"ar\":{\"title\":\"\\u0627\\u0644\\u062a\\u0639\\u0644\\u064a\\u0645\\u0627\\u062a\",\"heading\":\"\\u0625\\u0644\\u064a\\u0643 \\u0643\\u0644 \\u0645\\u0627 \\u062a\\u062d\\u062a\\u0627\\u062c \\u0625\\u0644\\u0649 \\u0645\\u0639\\u0631\\u0641\\u062a\\u0647 \\u062d\\u0648\\u0644 \\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 iBanking. \\u0625\\u0630\\u0627 \\u0643\\u0627\\u0646 \\u0644\\u062f\\u064a\\u0643 \\u0627\\u0644\\u0645\\u0632\\u064a\\u062f \\u0645\\u0646 \\u0627\\u0644\\u0623\\u0633\\u0626\\u0644\\u0629\\u060c \\u0641\\u0646\\u062d\\u0646 \\u0647\\u0646\\u0627 \\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u062a\\u0643!\"}},\"items\":{\"67122bc31a59c\":{\"language\":{\"en\":{\"question\":\"How secure is iBanking?\",\"answer\":\"iBanking uses state-of-the-art security measures such as encryption, multi-factor authentication, and continuous monitoring to protect your financial information.\"},\"es\":{\"question\":\"\\u00bfQu\\u00e9 tan segura es iBanking?\",\"answer\":\"iBanking utiliza medidas de seguridad de \\u00faltima generaci\\u00f3n, como encriptaci\\u00f3n, autenticaci\\u00f3n multifactor y monitoreo continuo para proteger su informaci\\u00f3n financiera.\"},\"ar\":{\"question\":\"\\u0645\\u0627 \\u0645\\u062f\\u0649 \\u0623\\u0645\\u0627\\u0646 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0639\\u0628\\u0631 \\u0627\\u0644\\u0625\\u0646\\u062a\\u0631\\u0646\\u062a\\u061f\",\"answer\":\"\\u064a\\u0633\\u062a\\u062e\\u062f\\u0645 iBanking \\u062a\\u062f\\u0627\\u0628\\u064a\\u0631 \\u0623\\u0645\\u0646\\u064a\\u0629 \\u0645\\u062a\\u0637\\u0648\\u0631\\u0629 \\u0645\\u062b\\u0644 \\u0627\\u0644\\u062a\\u0634\\u0641\\u064a\\u0631 \\u0648\\u0627\\u0644\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0645\\u062a\\u0639\\u062f\\u062f\\u0629 \\u0627\\u0644\\u0639\\u0648\\u0627\\u0645\\u0644 \\u0648\\u0627\\u0644\\u0645\\u0631\\u0627\\u0642\\u0628\\u0629 \\u0627\\u0644\\u0645\\u0633\\u062a\\u0645\\u0631\\u0629 \\u0644\\u062d\\u0645\\u0627\\u064a\\u0629 \\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629.\"}},\"status\":1,\"id\":\"67122bc31a59c\"},\"67122bd021e53\":{\"language\":{\"en\":{\"question\":\"Can I access iBanking on my mobile device?\",\"answer\":\"Yes! Our dedicated mobile app provides seamless access and functionality across both Android and iOS devices.\"},\"es\":{\"question\":\"\\u00bfPuedo acceder a iBanking en mi dispositivo m\\u00f3vil?\",\"answer\":\"\\u00a1S\\u00ed! Nuestra aplicaci\\u00f3n m\\u00f3vil dedicada brinda acceso y funcionalidad sin inconvenientes en dispositivos Android e iOS.\"},\"ar\":{\"question\":\"\\u0647\\u0644 \\u064a\\u0645\\u0643\\u0646\\u0646\\u064a \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u0649 iBanking \\u0639\\u0644\\u0649 \\u062c\\u0647\\u0627\\u0632\\u064a \\u0627\\u0644\\u0645\\u062d\\u0645\\u0648\\u0644\\u061f\",\"answer\":\"\\u0646\\u0639\\u0645! \\u064a\\u0648\\u0641\\u0631 \\u062a\\u0637\\u0628\\u064a\\u0642\\u0646\\u0627 \\u0627\\u0644\\u0645\\u062d\\u0645\\u0648\\u0644 \\u0627\\u0644\\u0645\\u062e\\u0635\\u0635 \\u0625\\u0645\\u0643\\u0627\\u0646\\u064a\\u0629 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0648\\u0627\\u0644\\u0648\\u0638\\u0627\\u0626\\u0641 \\u0628\\u0633\\u0644\\u0627\\u0633\\u0629 \\u0639\\u0628\\u0631 \\u0623\\u062c\\u0647\\u0632\\u0629 Android \\u0648iOS.\"}},\"status\":1,\"id\":\"67122bd021e53\"},\"67122bdbd44f0\":{\"language\":{\"en\":{\"question\":\"How can I contact customer support?\",\"answer\":\"Our customer support team is available 24\\/7 through live chat, email, or phone. We\'re always here to help with any questions or issues.\"},\"es\":{\"question\":\"\\u00bfC\\u00f3mo puedo contactar con atenci\\u00f3n al cliente?\",\"answer\":\"Nuestro equipo de atenci\\u00f3n al cliente est\\u00e1 disponible las 24 horas, los 7 d\\u00edas de la semana a trav\\u00e9s del chat en vivo, correo electr\\u00f3nico o tel\\u00e9fono. Siempre estamos aqu\\u00ed para ayudar con cualquier pregunta o problema.\"},\"ar\":{\"question\":\"\\u0643\\u064a\\u0641 \\u064a\\u0645\\u0643\\u0646\\u0646\\u064a \\u0627\\u0644\\u062a\\u0648\\u0627\\u0635\\u0644 \\u0645\\u0639 \\u062e\\u062f\\u0645\\u0629 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621\\u061f\",\"answer\":\"\\u0641\\u0631\\u064a\\u0642 \\u062f\\u0639\\u0645 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621 \\u0644\\u062f\\u064a\\u0646\\u0627 \\u0645\\u062a\\u0627\\u062d \\u0639\\u0644\\u0649 \\u0645\\u062f\\u0627\\u0631 \\u0627\\u0644\\u0633\\u0627\\u0639\\u0629 \\u0637\\u0648\\u0627\\u0644 \\u0623\\u064a\\u0627\\u0645 \\u0627\\u0644\\u0623\\u0633\\u0628\\u0648\\u0639 \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644 \\u0627\\u0644\\u062f\\u0631\\u062f\\u0634\\u0629 \\u0627\\u0644\\u0645\\u0628\\u0627\\u0634\\u0631\\u0629 \\u0623\\u0648 \\u0627\\u0644\\u0628\\u0631\\u064a\\u062f \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a \\u0623\\u0648 \\u0627\\u0644\\u0647\\u0627\\u062a\\u0641. \\u0646\\u062d\\u0646 \\u0647\\u0646\\u0627 \\u062f\\u0627\\u0626\\u0645\\u064b\\u0627 \\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u062a\\u0643 \\u0641\\u064a \\u0623\\u064a \\u0623\\u0633\\u0626\\u0644\\u0629 \\u0623\\u0648 \\u0645\\u0634\\u0643\\u0644\\u0627\\u062a.\"}},\"status\":1,\"id\":\"67122bdbd44f0\"},\"67122beaa119c\":{\"language\":{\"en\":{\"question\":\"Is my personal information safe with iBanking?\",\"answer\":\"Yes, your privacy is a top priority. We follow strict privacy policies and use robust security protocols to protect your personal and financial data.\"},\"es\":{\"question\":\"\\u00bfEst\\u00e1 segura mi informaci\\u00f3n personal con iBanking?\",\"answer\":\"S\\u00ed, su privacidad es nuestra m\\u00e1xima prioridad. Seguimos estrictas pol\\u00edticas de privacidad y utilizamos s\\u00f3lidos protocolos de seguridad para proteger sus datos personales y financieros.\"},\"ar\":{\"question\":\"\\u0647\\u0644 \\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a\\u064a \\u0627\\u0644\\u0634\\u062e\\u0635\\u064a\\u0629 \\u0622\\u0645\\u0646\\u0629 \\u0645\\u0639 iBanking\\u061f\",\"answer\":\"\\u0646\\u0639\\u0645\\u060c \\u062e\\u0635\\u0648\\u0635\\u064a\\u062a\\u0643 \\u0647\\u064a \\u0645\\u0646 \\u0623\\u0647\\u0645 \\u0623\\u0648\\u0644\\u0648\\u064a\\u0627\\u062a\\u0646\\u0627. \\u0646\\u062d\\u0646 \\u0646\\u062a\\u0628\\u0639 \\u0633\\u064a\\u0627\\u0633\\u0627\\u062a \\u062e\\u0635\\u0648\\u0635\\u064a\\u0629 \\u0635\\u0627\\u0631\\u0645\\u0629 \\u0648\\u0646\\u0633\\u062a\\u062e\\u062f\\u0645 \\u0628\\u0631\\u0648\\u062a\\u0648\\u0643\\u0648\\u0644\\u0627\\u062a \\u0623\\u0645\\u0627\\u0646 \\u0642\\u0648\\u064a\\u0629 \\u0644\\u062d\\u0645\\u0627\\u064a\\u0629 \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0634\\u062e\\u0635\\u064a\\u0629 \\u0648\\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629.\"}},\"status\":1,\"id\":\"67122beaa119c\"},\"67122bf74a982\":{\"language\":{\"en\":{\"question\":\"How quickly are transactions processed on iBanking?\",\"answer\":\"Transactions are processed instantly or within minutes, ensuring quick and efficient management of your funds.\"},\"es\":{\"question\":\"\\u00bfQu\\u00e9 tan r\\u00e1pido se procesan las transacciones en iBanking?\",\"answer\":\"Las transacciones se procesan instant\\u00e1neamente o en cuesti\\u00f3n de minutos, lo que garantiza una gesti\\u00f3n r\\u00e1pida y eficiente de sus fondos.\"},\"ar\":{\"question\":\"\\u0645\\u0627 \\u0645\\u062f\\u0649 \\u0633\\u0631\\u0639\\u0629 \\u0645\\u0639\\u0627\\u0644\\u062c\\u0629 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0639\\u0644\\u0649 iBanking\\u061f\",\"answer\":\"\\u064a\\u062a\\u0645 \\u0645\\u0639\\u0627\\u0644\\u062c\\u0629 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0628\\u0634\\u0643\\u0644 \\u0641\\u0648\\u0631\\u064a \\u0623\\u0648 \\u062e\\u0644\\u0627\\u0644 \\u062f\\u0642\\u0627\\u0626\\u0642\\u060c \\u0645\\u0645\\u0627 \\u064a\\u0636\\u0645\\u0646 \\u0625\\u062f\\u0627\\u0631\\u0629 \\u0633\\u0631\\u064a\\u0639\\u0629 \\u0648\\u0641\\u0639\\u0627\\u0644\\u0629 \\u0644\\u0623\\u0645\\u0648\\u0627\\u0644\\u0643.\"}},\"status\":1,\"id\":\"67122bf74a982\"},\"67122c01a654a\":{\"language\":{\"en\":{\"question\":\"Can I integrate iBanking with other financial apps?\",\"answer\":\"Yes, iBanking supports integration with select third-party financial apps for added convenience and enhanced connectivity.\"},\"es\":{\"question\":\"\\u00bfPuedo integrar iBanking con otras aplicaciones financieras?\",\"answer\":\"S\\u00ed, iBanking admite la integraci\\u00f3n con aplicaciones financieras de terceros seleccionadas para mayor comodidad y mejor conectividad.\"},\"ar\":{\"question\":\"\\u0647\\u0644 \\u064a\\u0645\\u0643\\u0646\\u0646\\u064a \\u062f\\u0645\\u062c iBanking \\u0645\\u0639 \\u062a\\u0637\\u0628\\u064a\\u0642\\u0627\\u062a \\u0645\\u0627\\u0644\\u064a\\u0629 \\u0623\\u062e\\u0631\\u0649\\u061f\",\"answer\":\"\\u0646\\u0639\\u0645\\u060c \\u064a\\u062f\\u0639\\u0645 iBanking \\u0627\\u0644\\u062a\\u0643\\u0627\\u0645\\u0644 \\u0645\\u0639 \\u062a\\u0637\\u0628\\u064a\\u0642\\u0627\\u062a \\u0645\\u0627\\u0644\\u064a\\u0629 \\u062a\\u0627\\u0628\\u0639\\u0629 \\u0644\\u062c\\u0647\\u0627\\u062a \\u062e\\u0627\\u0631\\u062c\\u064a\\u0629 \\u0645\\u062d\\u062f\\u062f\\u0629 \\u0644\\u0645\\u0632\\u064a\\u062f \\u0645\\u0646 \\u0627\\u0644\\u0631\\u0627\\u062d\\u0629 \\u0648\\u062a\\u062d\\u0633\\u064a\\u0646 \\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644.\"}},\"status\":1,\"id\":\"67122c01a654a\"},\"67122c0dbca45\":{\"language\":{\"en\":{\"question\":\"What if I forget my password or username?\",\"answer\":\"No problem! Simply use the \\u2018Forgot Password\\u2019 or \\u2018Retrieve Username\\u2019 options on the login page to reset your credentials.\"},\"es\":{\"question\":\"\\u00bfQu\\u00e9 pasa si olvido mi contrase\\u00f1a o nombre de usuario?\",\"answer\":\"\\u00a1No hay problema! Simplemente use las opciones \\u201cOlvid\\u00e9 mi contrase\\u00f1a\\u201d o \\u201cRecuperar nombre de usuario\\u201d en la p\\u00e1gina de inicio de sesi\\u00f3n para restablecer sus credenciales.\"},\"ar\":{\"question\":\"\\u0645\\u0627\\u0630\\u0627 \\u0644\\u0648 \\u0646\\u0633\\u064a\\u062a \\u0643\\u0644\\u0645\\u0629 \\u0627\\u0644\\u0645\\u0631\\u0648\\u0631 \\u0623\\u0648 \\u0627\\u0633\\u0645 \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u061f\",\"answer\":\"\\u0644\\u0627 \\u0645\\u0634\\u0643\\u0644\\u0629! \\u0645\\u0627 \\u0639\\u0644\\u064a\\u0643 \\u0633\\u0648\\u0649 \\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u062e\\u064a\\u0627\\u0631\\u064a \\\"\\u0646\\u0633\\u064a\\u062a \\u0643\\u0644\\u0645\\u0629 \\u0627\\u0644\\u0645\\u0631\\u0648\\u0631\\\" \\u0623\\u0648 \\\"\\u0627\\u0633\\u062a\\u0639\\u0627\\u062f\\u0629 \\u0627\\u0633\\u0645 \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\\" \\u0641\\u064a \\u0635\\u0641\\u062d\\u0629 \\u062a\\u0633\\u062c\\u064a\\u0644 \\u0627\\u0644\\u062f\\u062e\\u0648\\u0644 \\u0644\\u0625\\u0639\\u0627\\u062f\\u0629 \\u062a\\u0639\\u064a\\u064a\\u0646 \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a \\u0627\\u0644\\u0627\\u0639\\u062a\\u0645\\u0627\\u062f \\u0627\\u0644\\u062e\\u0627\\u0635\\u0629 \\u0628\\u0643.\"}},\"status\":1,\"id\":\"67122c0dbca45\"},\"67122cbd6f4c5\":{\"language\":{\"en\":{\"question\":\"How can I close my iBanking account?\",\"answer\":\"To close your account, please contact our customer support team, who will guide you through the process and any necessary steps.\"},\"es\":{\"question\":\"\\u00bfC\\u00f3mo puedo cerrar mi cuenta iBanking?\",\"answer\":\"Para cerrar su cuenta, comun\\u00edquese con nuestro equipo de atenci\\u00f3n al cliente, quien lo guiar\\u00e1 a trav\\u00e9s del proceso y los pasos necesarios.\"},\"ar\":{\"question\":\"\\u0643\\u064a\\u0641 \\u064a\\u0645\\u0643\\u0646\\u0646\\u064a \\u0625\\u063a\\u0644\\u0627\\u0642 \\u062d\\u0633\\u0627\\u0628\\u064a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u061f\",\"answer\":\"\\u0644\\u0625\\u063a\\u0644\\u0627\\u0642 \\u062d\\u0633\\u0627\\u0628\\u0643\\u060c \\u064a\\u0631\\u062c\\u0649 \\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644 \\u0628\\u0641\\u0631\\u064a\\u0642 \\u062f\\u0639\\u0645 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621 \\u0644\\u062f\\u064a\\u0646\\u0627\\u060c \\u0627\\u0644\\u0630\\u064a \\u0633\\u064a\\u0631\\u0634\\u062f\\u0643 \\u062e\\u0644\\u0627\\u0644 \\u0627\\u0644\\u0639\\u0645\\u0644\\u064a\\u0629 \\u0648\\u0623\\u064a \\u062e\\u0637\\u0648\\u0627\\u062a \\u0636\\u0631\\u0648\\u0631\\u064a\\u0629.\"}},\"status\":1,\"id\":\"67122cbd6f4c5\"}}}', 1, NULL, '2024-10-18 13:32:06', '2024-12-07 15:20:24'),
(13, 'client-feedback-section', '{\"language\":{\"en\":{\"title\":\"Customer Testimonials\",\"heading\":\"Our Customers Love What We Do\",\"sub_heading\":\"At iBanking, we are dedicated to transforming the way you manage your finances. Here\\u2019s what our customers are saying about their experience with our secure, user-friendly platform.\"},\"es\":{\"title\":\"Testimonios de clientes\",\"heading\":\"A nuestros clientes les encanta lo que hacemos\",\"sub_heading\":\"En iBanking, nos dedicamos a transformar la forma en que usted administra sus finanzas. Esto es lo que nuestros clientes dicen sobre su experiencia con nuestra plataforma segura y f\\u00e1cil de usar.\"},\"ar\":{\"title\":\"\\u0634\\u0647\\u0627\\u062f\\u0627\\u062a \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621\",\"heading\":\"\\u0639\\u0645\\u0644\\u0627\\u0624\\u0646\\u0627 \\u064a\\u062d\\u0628\\u0648\\u0646 \\u0645\\u0627 \\u0646\\u0642\\u0648\\u0645 \\u0628\\u0647\",\"sub_heading\":\"\\u0641\\u064a iBanking\\u060c \\u0646\\u062d\\u0646 \\u0645\\u0644\\u062a\\u0632\\u0645\\u0648\\u0646 \\u0628\\u062a\\u062d\\u0648\\u064a\\u0644 \\u0627\\u0644\\u0637\\u0631\\u064a\\u0642\\u0629 \\u0627\\u0644\\u062a\\u064a \\u062a\\u062f\\u064a\\u0631 \\u0628\\u0647\\u0627 \\u0623\\u0645\\u0648\\u0627\\u0644\\u0643. \\u0625\\u0644\\u064a\\u0643 \\u0645\\u0627 \\u064a\\u0642\\u0648\\u0644\\u0647 \\u0639\\u0645\\u0644\\u0627\\u0624\\u0646\\u0627 \\u0639\\u0646 \\u062a\\u062c\\u0631\\u0628\\u062a\\u0647\\u0645 \\u0645\\u0639 \\u0645\\u0646\\u0635\\u062a\\u0646\\u0627 \\u0627\\u0644\\u0622\\u0645\\u0646\\u0629 \\u0648\\u0633\\u0647\\u0644\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645.\"}},\"items\":{\"6712349cbe057\":{\"language\":{\"en\":{\"comment\":\"iBanking has completely changed the way I handle my finances. The seamless transfers and the security features give me peace of mind. I can manage everything from my phone, which is a game changer for someone with a busy schedule like mine.\"},\"es\":{\"comment\":\"iBanking ha cambiado por completo la forma en que gestiono mis finanzas. Las transferencias sin inconvenientes y las funciones de seguridad me brindan tranquilidad. Puedo administrar todo desde mi tel\\u00e9fono, lo cual es un gran cambio para alguien con una agenda tan ocupada como la m\\u00eda.\"},\"ar\":{\"comment\":\"\\u0644\\u0642\\u062f \\u063a\\u064a\\u0631\\u062a \\u062e\\u062f\\u0645\\u0629 iBanking \\u062a\\u0645\\u0627\\u0645\\u064b\\u0627 \\u0627\\u0644\\u0637\\u0631\\u064a\\u0642\\u0629 \\u0627\\u0644\\u062a\\u064a \\u0623\\u062a\\u0639\\u0627\\u0645\\u0644 \\u0628\\u0647\\u0627 \\u0645\\u0639 \\u0634\\u0624\\u0648\\u0646\\u064a \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629. \\u0641\\u0627\\u0644\\u062a\\u062d\\u0648\\u064a\\u0644\\u0627\\u062a \\u0627\\u0644\\u0633\\u0644\\u0633\\u0629 \\u0648\\u0645\\u064a\\u0632\\u0627\\u062a \\u0627\\u0644\\u0623\\u0645\\u0627\\u0646 \\u062a\\u0645\\u0646\\u062d\\u0646\\u064a \\u0631\\u0627\\u062d\\u0629 \\u0627\\u0644\\u0628\\u0627\\u0644. \\u0623\\u0633\\u062a\\u0637\\u064a\\u0639 \\u0625\\u062f\\u0627\\u0631\\u0629 \\u0643\\u0644 \\u0634\\u064a\\u0621 \\u0645\\u0646 \\u0647\\u0627\\u062a\\u0641\\u064a\\u060c \\u0648\\u0647\\u0648 \\u0623\\u0645\\u0631 \\u064a\\u063a\\u064a\\u0631 \\u0642\\u0648\\u0627\\u0639\\u062f \\u0627\\u0644\\u0644\\u0639\\u0628\\u0629 \\u0628\\u0627\\u0644\\u0646\\u0633\\u0628\\u0629 \\u0644\\u0634\\u062e\\u0635 \\u0644\\u062f\\u064a\\u0647 \\u062c\\u062f\\u0648\\u0644 \\u0623\\u0639\\u0645\\u0627\\u0644 \\u0645\\u0632\\u062f\\u062d\\u0645 \\u0645\\u062b\\u0644\\u064a.\"}},\"id\":\"6712349cbe057\",\"image\":\"3ea5021e-e958-4c83-a74c-d39268f54e6b.webp\",\"name\":\"Alex Doe\",\"designation\":\"Senior Manager, Global Tech Solutions\",\"star\":\"5\"},\"67123500a52e3\":{\"language\":{\"en\":{\"comment\":\"I love the intuitive design of iBanking. The platform is simple to use yet powerful, allowing me to track my business and personal finances in one place. The security measures are top-notch, which is essential for me.\"},\"es\":{\"comment\":\"Me encanta el dise\\u00f1o intuitivo de iBanking. La plataforma es sencilla de usar pero potente, lo que me permite hacer un seguimiento de mis finanzas personales y comerciales en un solo lugar. Las medidas de seguridad son de primera categor\\u00eda, lo cual es esencial para m\\u00ed.\"},\"ar\":{\"comment\":\"\\u0623\\u062d\\u0628 \\u0627\\u0644\\u062a\\u0635\\u0645\\u064a\\u0645 \\u0627\\u0644\\u0628\\u062f\\u064a\\u0647\\u064a \\u0644\\u0640 iBanking. \\u0627\\u0644\\u0645\\u0646\\u0635\\u0629 \\u0633\\u0647\\u0644\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0648\\u0644\\u0643\\u0646\\u0647\\u0627 \\u0642\\u0648\\u064a\\u0629\\u060c \\u0645\\u0645\\u0627 \\u064a\\u0633\\u0645\\u062d \\u0644\\u064a \\u0628\\u062a\\u062a\\u0628\\u0639 \\u0623\\u0639\\u0645\\u0627\\u0644\\u064a \\u0648\\u0634\\u0624\\u0648\\u0646\\u064a \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0627\\u0644\\u0634\\u062e\\u0635\\u064a\\u0629 \\u0641\\u064a \\u0645\\u0643\\u0627\\u0646 \\u0648\\u0627\\u062d\\u062f. \\u062a\\u062f\\u0627\\u0628\\u064a\\u0631 \\u0627\\u0644\\u0623\\u0645\\u0627\\u0646 \\u0645\\u0646 \\u0627\\u0644\\u062f\\u0631\\u062c\\u0629 \\u0627\\u0644\\u0623\\u0648\\u0644\\u0649\\u060c \\u0648\\u0647\\u0648 \\u0623\\u0645\\u0631 \\u0636\\u0631\\u0648\\u0631\\u064a \\u0628\\u0627\\u0644\\u0646\\u0633\\u0628\\u0629 \\u0644\\u064a.\"}},\"id\":\"67123500a52e3\",\"image\":\"0237e65c-5b81-4d0c-b5f4-03e78c44ddb8.webp\",\"name\":\"Mary Swift\",\"designation\":\"Entrepreneur & Founder, GreenTech Innovations\",\"star\":\"3\"},\"671235bed1b35\":{\"language\":{\"en\":{\"comment\":\"iBanking makes it easy to manage multiple accounts and quickly make transfers, even between different banks. I can trust that my information is secure, and the mobile app lets me bank on the go\\u2014truly innovative!\"},\"es\":{\"comment\":\"iBanking facilita la gesti\\u00f3n de m\\u00faltiples cuentas y la realizaci\\u00f3n r\\u00e1pida de transferencias, incluso entre distintos bancos. Puedo confiar en que mi informaci\\u00f3n est\\u00e1 segura y la aplicaci\\u00f3n m\\u00f3vil me permite realizar operaciones bancarias desde cualquier lugar. \\u00a1Realmente innovador!\"},\"ar\":{\"comment\":\"\\u064a\\u062c\\u0639\\u0644 iBanking \\u0625\\u062f\\u0627\\u0631\\u0629 \\u062d\\u0633\\u0627\\u0628\\u0627\\u062a \\u0645\\u062a\\u0639\\u062f\\u062f\\u0629 \\u0623\\u0645\\u0631\\u064b\\u0627 \\u0633\\u0647\\u0644\\u0627\\u064b \\u0648\\u0625\\u062c\\u0631\\u0627\\u0621 \\u062a\\u062d\\u0648\\u064a\\u0644\\u0627\\u062a \\u0633\\u0631\\u064a\\u0639\\u0629\\u060c \\u062d\\u062a\\u0649 \\u0628\\u064a\\u0646 \\u0627\\u0644\\u0628\\u0646\\u0648\\u0643 \\u0627\\u0644\\u0645\\u062e\\u062a\\u0644\\u0641\\u0629. \\u064a\\u0645\\u0643\\u0646\\u0646\\u064a \\u0623\\u0646 \\u0623\\u062b\\u0642 \\u0641\\u064a \\u0623\\u0646 \\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a\\u064a \\u0622\\u0645\\u0646\\u0629\\u060c \\u0648\\u064a\\u062a\\u064a\\u062d \\u0644\\u064a \\u0627\\u0644\\u062a\\u0637\\u0628\\u064a\\u0642 \\u0627\\u0644\\u0645\\u062d\\u0645\\u0648\\u0644 \\u0625\\u062c\\u0631\\u0627\\u0621 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0623\\u062b\\u0646\\u0627\\u0621 \\u0627\\u0644\\u062a\\u0646\\u0642\\u0644 - \\u0625\\u0646\\u0647 \\u0645\\u0628\\u062a\\u0643\\u0631 \\u062d\\u0642\\u064b\\u0627!\"}},\"id\":\"671235bed1b35\",\"image\":\"4b0e9c39-fce2-4796-a628-3317dba53304.webp\",\"name\":\"Angel Lonia\",\"designation\":\"CEO, BlueWave Enterprises\",\"star\":\"5\"}}}', 1, NULL, '2024-10-18 14:09:23', '2024-12-07 15:37:28'),
(14, 'services-section', '{\"language\":{\"en\":{\"heading\":\"Our Services\",\"sub_heading\":\"Explore our range of services designed to simplify your financial journey. From secure online banking to advanced security features, iBanking offers solutions that keep you in control.\"},\"es\":{\"heading\":\"Nuestros servicios\",\"sub_heading\":\"Explore nuestra gama de servicios dise\\u00f1ados para simplificar su experiencia financiera. Desde banca en l\\u00ednea segura hasta funciones de seguridad avanzadas, iBanking ofrece soluciones que le permiten tener el control.\"},\"ar\":{\"heading\":\"\\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627\",\"sub_heading\":\"\\u0627\\u0643\\u062a\\u0634\\u0641 \\u0645\\u062c\\u0645\\u0648\\u0639\\u0629 \\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627 \\u0627\\u0644\\u0645\\u0635\\u0645\\u0645\\u0629 \\u0644\\u062a\\u0628\\u0633\\u064a\\u0637 \\u0631\\u062d\\u0644\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629. \\u0628\\u062f\\u0621\\u064b\\u0627 \\u0645\\u0646 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0639\\u0628\\u0631 \\u0627\\u0644\\u0625\\u0646\\u062a\\u0631\\u0646\\u062a \\u0627\\u0644\\u0622\\u0645\\u0646\\u0629 \\u0625\\u0644\\u0649 \\u0645\\u064a\\u0632\\u0627\\u062a \\u0627\\u0644\\u0623\\u0645\\u0627\\u0646 \\u0627\\u0644\\u0645\\u062a\\u0642\\u062f\\u0645\\u0629\\u060c \\u062a\\u0642\\u062f\\u0645 iBanking \\u062d\\u0644\\u0648\\u0644\\u0627\\u064b \\u062a\\u0628\\u0642\\u064a\\u0643 \\u0645\\u062a\\u062d\\u0643\\u0645\\u064b\\u0627.\"}},\"items\":{\"6712492aec0e2\":{\"language\":{\"en\":{\"title\":\"Online Banking\",\"description\":\"Access and manage your accounts, make secure transactions, pay bills, and more\\u2014all from a secure online platform.\"},\"es\":{\"title\":\"Banca en l\\u00ednea\",\"description\":\"Acceda y administre sus cuentas, realice transacciones seguras, pague facturas y m\\u00e1s, todo desde una plataforma en l\\u00ednea segura.\"},\"ar\":{\"title\":\"\\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0639\\u0628\\u0631 \\u0627\\u0644\\u0625\\u0646\\u062a\\u0631\\u0646\\u062a\",\"description\":\"\\u064a\\u0645\\u0643\\u0646\\u0643 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u0649 \\u062d\\u0633\\u0627\\u0628\\u0627\\u062a\\u0643 \\u0648\\u0625\\u062f\\u0627\\u0631\\u062a\\u0647\\u0627 \\u0648\\u0625\\u062c\\u0631\\u0627\\u0621 \\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0622\\u0645\\u0646\\u0629 \\u0648\\u062f\\u0641\\u0639 \\u0627\\u0644\\u0641\\u0648\\u0627\\u062a\\u064a\\u0631 \\u0648\\u0627\\u0644\\u0645\\u0632\\u064a\\u062f - \\u0643\\u0644 \\u0630\\u0644\\u0643 \\u0645\\u0646 \\u0645\\u0646\\u0635\\u0629 \\u0622\\u0645\\u0646\\u0629 \\u0639\\u0628\\u0631 \\u0627\\u0644\\u0625\\u0646\\u062a\\u0631\\u0646\\u062a.\"}},\"id\":\"6712492aec0e2\",\"icon\":\"fas fa-university\"},\"6712493e586fa\":{\"language\":{\"en\":{\"title\":\"Mobile Banking\",\"description\":\"Bank on the go with our easy-to-use mobile app, offering secure access to your accounts and services wherever you are.\"},\"es\":{\"title\":\"Banca m\\u00f3vil\",\"description\":\"Realice operaciones bancarias desde cualquier lugar con nuestra aplicaci\\u00f3n m\\u00f3vil f\\u00e1cil de usar, que ofrece acceso seguro a sus cuentas y servicios dondequiera que est\\u00e9.\"},\"ar\":{\"title\":\"\\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0639\\u0628\\u0631 \\u0627\\u0644\\u0647\\u0627\\u062a\\u0641 \\u0627\\u0644\\u0645\\u062d\\u0645\\u0648\\u0644\",\"description\":\"\\u0642\\u0645 \\u0628\\u0625\\u062c\\u0631\\u0627\\u0621 \\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a\\u0629 \\u0623\\u062b\\u0646\\u0627\\u0621 \\u0627\\u0644\\u062a\\u0646\\u0642\\u0644 \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u062a\\u0637\\u0628\\u064a\\u0642\\u0646\\u0627 \\u0627\\u0644\\u0645\\u062d\\u0645\\u0648\\u0644 \\u0633\\u0647\\u0644 \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645\\u060c \\u0648\\u0627\\u0644\\u0630\\u064a \\u064a\\u0648\\u0641\\u0631 \\u0644\\u0643 \\u0648\\u0635\\u0648\\u0644\\u0627\\u064b \\u0622\\u0645\\u0646\\u064b\\u0627 \\u0625\\u0644\\u0649 \\u062d\\u0633\\u0627\\u0628\\u0627\\u062a\\u0643 \\u0648\\u062e\\u062f\\u0645\\u0627\\u062a\\u0643 \\u0623\\u064a\\u0646\\u0645\\u0627 \\u0643\\u0646\\u062a.\"}},\"id\":\"6712493e586fa\",\"icon\":\"fas fa-mobile-alt\"},\"6712494fd1e4a\":{\"language\":{\"en\":{\"title\":\"Credit\\/Debit Cards\",\"description\":\"Get access to customizable credit and debit cards, with exciting rewards, cashback, and exclusive benefits for iBanking users.\"},\"es\":{\"title\":\"Tarjetas de cr\\u00e9dito\\/d\\u00e9bito\",\"description\":\"Obtenga acceso a tarjetas de cr\\u00e9dito y d\\u00e9bito personalizables, con emocionantes recompensas, reembolsos de efectivo y beneficios exclusivos para usuarios de iBanking.\"},\"ar\":{\"title\":\"\\u0628\\u0637\\u0627\\u0642\\u0627\\u062a \\u0627\\u0644\\u0627\\u0626\\u062a\\u0645\\u0627\\u0646\\/\\u0627\\u0644\\u062e\\u0635\\u0645\",\"description\":\"\\u0627\\u062d\\u0635\\u0644 \\u0639\\u0644\\u0649 \\u0628\\u0637\\u0627\\u0642\\u0627\\u062a \\u0627\\u0626\\u062a\\u0645\\u0627\\u0646 \\u0648\\u062e\\u0635\\u0645 \\u0642\\u0627\\u0628\\u0644\\u0629 \\u0644\\u0644\\u062a\\u062e\\u0635\\u064a\\u0635\\u060c \\u0645\\u0639 \\u0645\\u0643\\u0627\\u0641\\u0622\\u062a \\u0645\\u062b\\u064a\\u0631\\u0629\\u060c \\u0648\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0646\\u0642\\u062f\\u064a\\u060c \\u0648\\u0641\\u0648\\u0627\\u0626\\u062f \\u062d\\u0635\\u0631\\u064a\\u0629 \\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a iBanking.\"}},\"id\":\"6712494fd1e4a\",\"icon\":\"far fa-credit-card\"},\"6712496a784e5\":{\"language\":{\"en\":{\"title\":\"Wealth Management\",\"description\":\"Receive personalized financial planning and investment management tailored to high-net-worth individuals, helping you achieve your financial goals.\"},\"es\":{\"title\":\"Gesti\\u00f3n patrimonial\",\"description\":\"Reciba planificaci\\u00f3n financiera personalizada y gesti\\u00f3n de inversiones adaptada a personas de alto patrimonio, ayud\\u00e1ndole a alcanzar sus objetivos financieros.\"},\"ar\":{\"title\":\"\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u062b\\u0631\\u0648\\u0627\\u062a\",\"description\":\"\\u0627\\u062d\\u0635\\u0644 \\u0639\\u0644\\u0649 \\u062a\\u062e\\u0637\\u064a\\u0637 \\u0645\\u0627\\u0644\\u064a \\u0634\\u062e\\u0635\\u064a \\u0648\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0633\\u062a\\u062b\\u0645\\u0627\\u0631\\u064a\\u0629 \\u0645\\u0635\\u0645\\u0645\\u0629 \\u062e\\u0635\\u064a\\u0635\\u064b\\u0627 \\u0644\\u0644\\u0623\\u0641\\u0631\\u0627\\u062f \\u0630\\u0648\\u064a \\u0627\\u0644\\u062b\\u0631\\u0648\\u0627\\u062a \\u0627\\u0644\\u0639\\u0627\\u0644\\u064a\\u0629\\u060c \\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u062a\\u0643 \\u0639\\u0644\\u0649 \\u062a\\u062d\\u0642\\u064a\\u0642 \\u0623\\u0647\\u062f\\u0627\\u0641\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629.\"}},\"id\":\"6712496a784e5\",\"icon\":\"fas fa-wallet\"},\"671249874e0e1\":{\"language\":{\"en\":{\"title\":\"Statement\",\"description\":\"Stay on top of your finances with iBanking\\u2019s easy-to-access and detailed account statements, providing full transparency and control.\"},\"es\":{\"title\":\"Declaraci\\u00f3n\",\"description\":\"Mant\\u00e9ngase al tanto de sus finanzas con los estados de cuenta detallados y de f\\u00e1cil acceso de iBanking, que brindan total transparencia y control.\"},\"ar\":{\"title\":\"\\u0625\\u0641\\u0627\\u062f\\u0629\",\"description\":\"\\u0627\\u0628\\u0642 \\u0639\\u0644\\u0649 \\u0627\\u0637\\u0644\\u0627\\u0639 \\u062f\\u0627\\u0626\\u0645 \\u0628\\u0623\\u0645\\u0648\\u0631\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0643\\u0634\\u0648\\u0641\\u0627\\u062a \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\\u0627\\u062a \\u0627\\u0644\\u062a\\u0641\\u0635\\u064a\\u0644\\u064a\\u0629 \\u0648\\u0633\\u0647\\u0644\\u0629 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u0625\\u0644\\u064a\\u0647\\u0627 \\u0645\\u0646 iBanking\\u060c \\u0648\\u0627\\u0644\\u062a\\u064a \\u062a\\u0648\\u0641\\u0631 \\u0644\\u0643 \\u0627\\u0644\\u0634\\u0641\\u0627\\u0641\\u064a\\u0629 \\u0648\\u0627\\u0644\\u062a\\u062d\\u0643\\u0645 \\u0627\\u0644\\u0643\\u0627\\u0645\\u0644\\u064a\\u0646.\"}},\"id\":\"671249874e0e1\",\"icon\":\"far fa-file-alt\"},\"671249a452df2\":{\"language\":{\"en\":{\"title\":\"Two-Factor Authentication (2FA)\",\"description\":\"Added protection for your account with Two-Factor Authentication, ensuring your financial data is secure every time you log in.\"},\"es\":{\"title\":\"Autenticaci\\u00f3n de dos factores (2FA)\",\"description\":\"Protecci\\u00f3n adicional para su cuenta con autenticaci\\u00f3n de dos factores, lo que garantiza que sus datos financieros est\\u00e9n seguros cada vez que inicie sesi\\u00f3n.\"},\"ar\":{\"title\":\"\\u0627\\u0644\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0627\\u0644\\u062b\\u0646\\u0627\\u0626\\u064a\\u0629 (2FA)\",\"description\":\"\\u062a\\u0645\\u062a \\u0625\\u0636\\u0627\\u0641\\u0629 \\u062d\\u0645\\u0627\\u064a\\u0629 \\u0644\\u062d\\u0633\\u0627\\u0628\\u0643 \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0627\\u0644\\u0645\\u0635\\u0627\\u062f\\u0642\\u0629 \\u0627\\u0644\\u062b\\u0646\\u0627\\u0626\\u064a\\u0629\\u060c \\u0645\\u0645\\u0627 \\u064a\\u0636\\u0645\\u0646 \\u0623\\u0645\\u0627\\u0646 \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0641\\u064a \\u0643\\u0644 \\u0645\\u0631\\u0629 \\u062a\\u0642\\u0648\\u0645 \\u0641\\u064a\\u0647\\u0627 \\u0628\\u062a\\u0633\\u062c\\u064a\\u0644 \\u0627\\u0644\\u062f\\u062e\\u0648\\u0644.\"}},\"id\":\"671249a452df2\",\"icon\":\"fas fa-lock\"},\"671249bb3d2fe\":{\"language\":{\"en\":{\"title\":\"Customer Support\",\"description\":\"Our dedicated support team is available 24\\/7 to help you with any questions or issues, ensuring you get the assistance you need when you need it.\"},\"es\":{\"title\":\"Atenci\\u00f3n al cliente\",\"description\":\"Nuestro equipo de soporte dedicado est\\u00e1 disponible las 24 horas, los 7 d\\u00edas de la semana para ayudarlo con cualquier pregunta o problema, garantizando que obtenga la asistencia que necesita cuando la necesita.\"},\"ar\":{\"title\":\"\\u062f\\u0639\\u0645 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621\",\"description\":\"\\u0641\\u0631\\u064a\\u0642 \\u0627\\u0644\\u062f\\u0639\\u0645 \\u0627\\u0644\\u0645\\u062a\\u062e\\u0635\\u0635 \\u0644\\u062f\\u064a\\u0646\\u0627 \\u0645\\u062a\\u0627\\u062d \\u0639\\u0644\\u0649 \\u0645\\u062f\\u0627\\u0631 \\u0627\\u0644\\u0633\\u0627\\u0639\\u0629 \\u0637\\u0648\\u0627\\u0644 \\u0623\\u064a\\u0627\\u0645 \\u0627\\u0644\\u0623\\u0633\\u0628\\u0648\\u0639 \\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u062a\\u0643 \\u0641\\u064a \\u0623\\u064a \\u0623\\u0633\\u0626\\u0644\\u0629 \\u0623\\u0648 \\u0645\\u0634\\u0643\\u0644\\u0627\\u062a\\u060c \\u0645\\u0645\\u0627 \\u064a\\u0636\\u0645\\u0646 \\u062d\\u0635\\u0648\\u0644\\u0643 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u0629 \\u0627\\u0644\\u062a\\u064a \\u062a\\u062d\\u062a\\u0627\\u062c\\u0647\\u0627 \\u0639\\u0646\\u062f\\u0645\\u0627 \\u062a\\u062d\\u062a\\u0627\\u062c \\u0625\\u0644\\u064a\\u0647\\u0627.\"}},\"id\":\"671249bb3d2fe\",\"icon\":\"fas fa-headset\"}}}', 1, NULL, '2024-10-18 15:39:59', '2024-12-07 15:44:12'),
(15, 'announcement-section', '{\"language\":{\"en\":{\"heading\":\"Our Latest Web Journal.\",\"sub_heading\":\"A web journal, often known as a blog, is a space to share insights, news, and updates. It\'s a platform where you can explore topics related to finance, banking trends.\"},\"es\":{\"heading\":\"Nuestro \\u00faltimo diario web.\",\"sub_heading\":\"Un diario web, a menudo conocido como blog, es un espacio para compartir informaci\\u00f3n, noticias y actualizaciones. Es una plataforma en la que puedes explorar temas relacionados con las finanzas y las tendencias bancarias.\"},\"ar\":{\"heading\":\"\\u0645\\u062c\\u0644\\u062a\\u0646\\u0627 \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\\u0629 \\u0627\\u0644\\u0623\\u062d\\u062f\\u062b.\",\"sub_heading\":\"\\u0627\\u0644\\u0645\\u062c\\u0644\\u0629 \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\\u0629\\u060c \\u0627\\u0644\\u0645\\u0639\\u0631\\u0648\\u0641\\u0629 \\u063a\\u0627\\u0644\\u0628\\u064b\\u0627 \\u0628\\u0627\\u0633\\u0645 \\u0627\\u0644\\u0645\\u062f\\u0648\\u0646\\u0629\\u060c \\u0647\\u064a \\u0645\\u0633\\u0627\\u062d\\u0629 \\u0644\\u0645\\u0634\\u0627\\u0631\\u0643\\u0629 \\u0627\\u0644\\u0623\\u0641\\u0643\\u0627\\u0631 \\u0648\\u0627\\u0644\\u0623\\u062e\\u0628\\u0627\\u0631 \\u0648\\u0627\\u0644\\u062a\\u062d\\u062f\\u064a\\u062b\\u0627\\u062a. \\u0625\\u0646\\u0647\\u0627 \\u0645\\u0646\\u0635\\u0629 \\u064a\\u0645\\u0643\\u0646\\u0643 \\u0645\\u0646 \\u062e\\u0644\\u0627\\u0644\\u0647\\u0627 \\u0627\\u0633\\u062a\\u0643\\u0634\\u0627\\u0641 \\u0627\\u0644\\u0645\\u0648\\u0636\\u0648\\u0639\\u0627\\u062a \\u0627\\u0644\\u0645\\u062a\\u0639\\u0644\\u0642\\u0629 \\u0628\\u0627\\u0644\\u062a\\u0645\\u0648\\u064a\\u0644 \\u0648\\u0627\\u062a\\u062c\\u0627\\u0647\\u0627\\u062a \\u0627\\u0644\\u0639\\u0645\\u0644 \\u0627\\u0644\\u0645\\u0635\\u0631\\u0641\\u064a.\"}}}', 1, NULL, '2024-10-18 15:49:58', '2024-12-07 15:45:07'),
(16, 'contact-us-section', '{\"schedules\":[{\"schedule\":\"Monday\\u2013Friday, 9am - 6:30pm\"}],\"language\":{\"en\":{\"title\":\"Contact Us\",\"heading\":\"Need help? Contact us for 24\\/7 support, guidance, and assistance with your financial queries and account management.\"},\"es\":{\"title\":\"Contacta con nosotras\",\"heading\":\"\\u00bfNecesita ayuda? P\\u00f3ngase en contacto con nosotros para recibir asistencia, orientaci\\u00f3n y ayuda las 24 horas del d\\u00eda, los 7 d\\u00edas de la semana, con sus consultas financieras y la gesti\\u00f3n de su cuenta.\"},\"ar\":{\"title\":\"\\u0627\\u062a\\u0635\\u0644 \\u0628\\u0646\\u0627\",\"heading\":\"\\u0647\\u0644 \\u062a\\u062d\\u062a\\u0627\\u062c \\u0625\\u0644\\u0649 \\u0645\\u0633\\u0627\\u0639\\u062f\\u0629\\u061f \\u0627\\u062a\\u0635\\u0644 \\u0628\\u0646\\u0627 \\u0644\\u0644\\u062d\\u0635\\u0648\\u0644 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u062f\\u0639\\u0645 \\u0648\\u0627\\u0644\\u062a\\u0648\\u062c\\u064a\\u0647 \\u0648\\u0627\\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u0629 \\u0639\\u0644\\u0649 \\u0645\\u062f\\u0627\\u0631 \\u0627\\u0644\\u0633\\u0627\\u0639\\u0629 \\u0637\\u0648\\u0627\\u0644 \\u0623\\u064a\\u0627\\u0645 \\u0627\\u0644\\u0623\\u0633\\u0628\\u0648\\u0639 \\u0641\\u064a\\u0645\\u0627 \\u064a\\u062a\\u0639\\u0644\\u0642 \\u0628\\u0627\\u0633\\u062a\\u0641\\u0633\\u0627\\u0631\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0648\\u0625\\u062f\\u0627\\u0631\\u0629 \\u062d\\u0633\\u0627\\u0628\\u0643.\"}},\"phone\":\"+1 (800) 123-4567\",\"address\":\"123 Financial Plaza, Suite 400, New York, NY 10001, USA\",\"email\":\"support@ibanking.com\",\"image\":\"\"}', 1, NULL, '2024-10-18 16:25:42', '2024-12-07 15:59:39'),
(17, 'login-section', '{\"language\":{\"en\":{\"title\":\"Log in and Stay Connected\",\"heading\":\"Our secure login process ensures the confidentiality of your information. Log in today and stay connected to your finances, anytime and anywhere. Whether you\\u2019re managing your accounts, making transfers, or checking statements, iBanking keeps you in control\\u201424\\/7, with peace of mind.\"},\"es\":{\"title\":\"Inicia sesi\\u00f3n y mantente conectado\",\"heading\":\"Nuestro proceso de inicio de sesi\\u00f3n seguro garantiza la confidencialidad de su informaci\\u00f3n. Inicie sesi\\u00f3n hoy mismo y mant\\u00e9ngase conectado con sus finanzas, en cualquier momento y en cualquier lugar. Ya sea que est\\u00e9 administrando sus cuentas, haciendo transferencias o consultando sus estados de cuenta, iBanking le permite tener el control las 24 horas del d\\u00eda, los 7 d\\u00edas de la semana, con tranquilidad.\"},\"ar\":{\"title\":\"\\u062a\\u0633\\u062c\\u064a\\u0644 \\u0627\\u0644\\u062f\\u062e\\u0648\\u0644 \\u0648\\u0627\\u0644\\u0628\\u0642\\u0627\\u0621 \\u0639\\u0644\\u0649 \\u0627\\u062a\\u0635\\u0627\\u0644\",\"heading\":\"\\u062a\\u0636\\u0645\\u0646 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u062a\\u0633\\u062c\\u064a\\u0644 \\u0627\\u0644\\u062f\\u062e\\u0648\\u0644 \\u0627\\u0644\\u0622\\u0645\\u0646\\u0629 \\u0644\\u062f\\u064a\\u0646\\u0627 \\u0633\\u0631\\u064a\\u0629 \\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a\\u0643. \\u0633\\u062c\\u0651\\u0644 \\u0627\\u0644\\u062f\\u062e\\u0648\\u0644 \\u0627\\u0644\\u064a\\u0648\\u0645 \\u0648\\u0627\\u0628\\u0642\\u064e \\u0639\\u0644\\u0649 \\u0627\\u062a\\u0635\\u0627\\u0644 \\u0628\\u0623\\u0645\\u0648\\u0627\\u0644\\u0643\\u060c \\u0641\\u064a \\u0623\\u064a \\u0648\\u0642\\u062a \\u0648\\u0641\\u064a \\u0623\\u064a \\u0645\\u0643\\u0627\\u0646. \\u0633\\u0648\\u0627\\u0621 \\u0643\\u0646\\u062a \\u062a\\u062f\\u064a\\u0631 \\u062d\\u0633\\u0627\\u0628\\u0627\\u062a\\u0643 \\u0623\\u0648 \\u062a\\u062c\\u0631\\u064a \\u062a\\u062d\\u0648\\u064a\\u0644\\u0627\\u062a \\u0623\\u0648 \\u062a\\u062a\\u062d\\u0642\\u0642 \\u0645\\u0646 \\u0643\\u0634\\u0648\\u0641 \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\\u0627\\u062a\\u060c \\u0641\\u0625\\u0646 iBanking \\u064a\\u0645\\u0646\\u062d\\u0643 \\u0627\\u0644\\u0633\\u064a\\u0637\\u0631\\u0629 \\u0639\\u0644\\u0649 \\u0645\\u062f\\u0627\\u0631 \\u0627\\u0644\\u0633\\u0627\\u0639\\u0629 \\u0637\\u0648\\u0627\\u0644 \\u0623\\u064a\\u0627\\u0645 \\u0627\\u0644\\u0623\\u0633\\u0628\\u0648\\u0639\\u060c \\u0645\\u0639 \\u0631\\u0627\\u062d\\u0629 \\u0627\\u0644\\u0628\\u0627\\u0644.\"}},\"image\":\"b3983353-0267-41e6-8995-7d5fe4731340.webp\"}', 1, NULL, '2024-10-22 08:12:59', '2024-12-07 16:01:22'),
(18, 'register-section', '{\"language\":{\"en\":{\"title\":\"Register for an Account Today\",\"heading\":\"Join our community and unlock a world of financial possibilities. Register today to enjoy a wide range of benefits and features tailored to meet your needs. With our seamless and user-friendly registration process, getting started with iBanking is quick, easy, and secure. Take control of your finances with just a few simple steps!\"},\"es\":{\"title\":\"Reg\\u00edstrese para obtener una cuenta hoy\",\"heading\":\"\\u00danase a nuestra comunidad y descubra un mundo de posibilidades financieras. Reg\\u00edstrese hoy para disfrutar de una amplia gama de beneficios y funciones adaptadas a sus necesidades. Con nuestro proceso de registro sencillo y f\\u00e1cil de usar, comenzar a utilizar iBanking es r\\u00e1pido, f\\u00e1cil y seguro. \\u00a1Tome el control de sus finanzas con solo unos sencillos pasos!\"},\"ar\":{\"title\":\"\\u0633\\u062c\\u0644 \\u0644\\u0644\\u062d\\u0635\\u0648\\u0644 \\u0639\\u0644\\u0649 \\u062d\\u0633\\u0627\\u0628 \\u0627\\u0644\\u064a\\u0648\\u0645\",\"heading\":\"\\u0627\\u0646\\u0636\\u0645 \\u0625\\u0644\\u0649 \\u0645\\u062c\\u062a\\u0645\\u0639\\u0646\\u0627 \\u0648\\u0627\\u0643\\u062a\\u0634\\u0641 \\u0639\\u0627\\u0644\\u0645\\u064b\\u0627 \\u0645\\u0646 \\u0627\\u0644\\u0625\\u0645\\u0643\\u0627\\u0646\\u0627\\u062a \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629. \\u0633\\u062c\\u0644 \\u0627\\u0644\\u064a\\u0648\\u0645 \\u0644\\u0644\\u0627\\u0633\\u062a\\u0645\\u062a\\u0627\\u0639 \\u0628\\u0645\\u062c\\u0645\\u0648\\u0639\\u0629 \\u0648\\u0627\\u0633\\u0639\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u0645\\u0632\\u0627\\u064a\\u0627 \\u0648\\u0627\\u0644\\u062e\\u0635\\u0627\\u0626\\u0635 \\u0627\\u0644\\u0645\\u0635\\u0645\\u0645\\u0629 \\u062e\\u0635\\u064a\\u0635\\u064b\\u0627 \\u0644\\u062a\\u0644\\u0628\\u064a\\u0629 \\u0627\\u062d\\u062a\\u064a\\u0627\\u062c\\u0627\\u062a\\u0643. \\u0628\\u0641\\u0636\\u0644 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u0627\\u0644\\u062a\\u0633\\u062c\\u064a\\u0644 \\u0627\\u0644\\u0633\\u0644\\u0633\\u0629 \\u0648\\u0633\\u0647\\u0644\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645\\u060c \\u064a\\u0645\\u0643\\u0646\\u0643 \\u0627\\u0644\\u0628\\u062f\\u0621 \\u0641\\u064a \\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 iBanking \\u0628\\u0633\\u0631\\u0639\\u0629 \\u0648\\u0633\\u0647\\u0648\\u0644\\u0629 \\u0648\\u0623\\u0645\\u0627\\u0646. \\u062a\\u062d\\u0643\\u0645 \\u0641\\u064a \\u0634\\u0624\\u0648\\u0646\\u0643 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0628\\u062e\\u0637\\u0648\\u0627\\u062a \\u0628\\u0633\\u064a\\u0637\\u0629 \\u0642\\u0644\\u064a\\u0644\\u0629!\"}},\"image\":\"20741964-ea06-455f-921b-fd421b5fbf19.webp\"}', 1, NULL, '2024-10-22 08:13:27', '2024-12-07 16:02:15');

-- --------------------------------------------------------

--
-- Table structure for table `strowallet_customer_kycs`
--

CREATE TABLE `strowallet_customer_kycs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `id_image` varchar(255) DEFAULT NULL,
  `face_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `strowallet_customer_kycs`
--

INSERT INTO `strowallet_customer_kycs` (`id`, `user_id`, `id_image`, `face_image`, `created_at`, `updated_at`) VALUES
(3, 1, '0e33fb43-9809-4321-8865-4f133057b20a.webp', '1b846b1f-1f46-4d33-b549-686edc425922.webp', '2026-03-12 04:54:51', '2026-03-12 04:57:06');

-- --------------------------------------------------------

--
-- Table structure for table `strowallet_virtual_cards`
--

CREATE TABLE `strowallet_virtual_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name_on_card` varchar(191) NOT NULL,
  `card_id` varchar(191) NOT NULL,
  `card_created_date` varchar(191) NOT NULL,
  `card_type` varchar(191) NOT NULL,
  `card_brand` varchar(191) NOT NULL,
  `card_user_id` varchar(191) NOT NULL,
  `reference` varchar(191) NOT NULL,
  `card_status` varchar(191) NOT NULL,
  `customer_id` varchar(191) NOT NULL,
  `card_name` varchar(191) DEFAULT NULL,
  `card_number` varchar(191) DEFAULT NULL,
  `last4` varchar(191) DEFAULT NULL,
  `cvv` varchar(191) DEFAULT NULL,
  `expiry` varchar(191) DEFAULT NULL,
  `customer_email` varchar(191) DEFAULT NULL,
  `balance` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribes`
--

CREATE TABLE `subscribes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscribes`
--

INSERT INTO `subscribes` (`id`, `email`, `created_at`, `updated_at`) VALUES
(1, 'chisomihekechukwu7@gmail.com', '2026-03-11 08:42:06', '2026-03-11 08:42:06');

-- --------------------------------------------------------

--
-- Table structure for table `system_maintenances`
--

CREATE TABLE `system_maintenances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(191) NOT NULL DEFAULT 'system-maintenance',
  `title` varchar(191) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_maintenances`
--

INSERT INTO `system_maintenances` (`id`, `slug`, `title`, `details`, `status`, `created_at`, `updated_at`) VALUES
(1, 'system-maintenance', 'Enhancing Your Experience ??? Site Under Maintenance', '<p>Our website is down for upgrades and will be back shortly. If you need assistance, please email us at <strong>support@ibanking.com</strong> or message us on WhatsApp at <strong>+1234567890</strong>. Thank you for your patience!</p>', 0, '2026-03-10 02:26:05', '2026-03-12 04:48:32');

-- --------------------------------------------------------

--
-- Table structure for table `temporary_datas`
--

CREATE TABLE `temporary_datas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL,
  `identifier` varchar(191) NOT NULL,
  `gateway_code` varchar(191) DEFAULT NULL,
  `currency_code` varchar(191) DEFAULT NULL,
  `data` mediumtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `temporary_datas`
--

INSERT INTO `temporary_datas` (`id`, `type`, `identifier`, `gateway_code`, `currency_code`, `data`, `created_at`, `updated_at`) VALUES
(1, 'add-money', 'NRiOr9apmQZNMby5', NULL, NULL, '{\"instance\":{\"type\":\"ADD-MONEY\",\"wallet\":{\"id\":1,\"user_id\":1,\"currency_id\":1,\"balance\":\"0.00000000\",\"status\":true,\"created_at\":\"2026-03-09T18:25:09.000000Z\",\"updated_at\":null},\"gateway\":{\"id\":1,\"slug\":\"add-money\",\"code\":105,\"type\":\"AUTOMATIC\",\"name\":\"Paypal\",\"title\":\"Paypal Gateway\",\"alias\":\"paypal\",\"image\":\"0bb65be8-a0cf-4d63-b9d7-5f5d7d76ce35.webp\",\"credentials\":[{\"label\":\"Client ID\",\"placeholder\":\"Enter Client ID\",\"name\":\"client-id\",\"value\":\"ASuAkb_xKKu00hA1Pk32UMJvBqE7-GizN2xxBH2nlQgkvGSpgl_h_fmvYiRw__lSwYnw6Bu0G9qtjEY6\"},{\"label\":\"Secret ID\",\"placeholder\":\"Enter Secret ID\",\"name\":\"secret-id\",\"value\":\"EHtZKs6f0h6wDSKykJA4lJMU6GVGTc5GARto_EKQS1R5iGA2Z8n_F8sBQPTmcMWHav1GWNl8iJj169u0\"}],\"supported_currencies\":[\"USD\",\"GBP\",\"PHP\",\"NZD\",\"MYR\",\"EUR\",\"CNY\",\"CAD\",\"AUD\"],\"crypto\":false,\"desc\":null,\"input_fields\":null,\"env\":\"SANDBOX\",\"status\":1,\"last_edit_by\":1,\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\",\"currencies\":[{\"id\":1,\"payment_gateway_id\":1,\"name\":\"Paypal USD\",\"alias\":\"add-money-paypal-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"5000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\"}]},\"currency\":{\"id\":1,\"payment_gateway_id\":1,\"name\":\"Paypal USD\",\"alias\":\"add-money-paypal-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"5000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\",\"gateway\":{\"id\":1,\"slug\":\"add-money\",\"code\":105,\"type\":\"AUTOMATIC\",\"name\":\"Paypal\",\"title\":\"Paypal Gateway\",\"alias\":\"paypal\",\"image\":\"0bb65be8-a0cf-4d63-b9d7-5f5d7d76ce35.webp\",\"credentials\":[{\"label\":\"Client ID\",\"placeholder\":\"Enter Client ID\",\"name\":\"client-id\",\"value\":\"ASuAkb_xKKu00hA1Pk32UMJvBqE7-GizN2xxBH2nlQgkvGSpgl_h_fmvYiRw__lSwYnw6Bu0G9qtjEY6\"},{\"label\":\"Secret ID\",\"placeholder\":\"Enter Secret ID\",\"name\":\"secret-id\",\"value\":\"EHtZKs6f0h6wDSKykJA4lJMU6GVGTc5GARto_EKQS1R5iGA2Z8n_F8sBQPTmcMWHav1GWNl8iJj169u0\"}],\"supported_currencies\":[\"USD\",\"GBP\",\"PHP\",\"NZD\",\"MYR\",\"EUR\",\"CNY\",\"CAD\",\"AUD\"],\"crypto\":false,\"desc\":null,\"input_fields\":null,\"env\":\"SANDBOX\",\"status\":1,\"last_edit_by\":1,\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\",\"currencies\":[{\"id\":1,\"payment_gateway_id\":1,\"name\":\"Paypal USD\",\"alias\":\"add-money-paypal-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"5000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\"}]}},\"amount\":{\"requested_amount\":\"1000\",\"sender_cur_code\":\"USD\",\"sender_cur_rate\":\"1.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"percent_charge\":20,\"total_charge\":20,\"total_amount\":1020,\"exchange_rate\":\"1.0000000000000000\",\"will_get\":\"1000\",\"default_currency\":\"USD\"},\"form_data\":{\"_token\":\"LQTiAQ1NL1koNKL0y2nxKZgW0qC3PiD76D6wwFNt\",\"amount\":\"1000\",\"gateway_currency\":\"add-money-paypal-usd-automatic\",\"currency\":\"add-money-paypal-usd-automatic\"},\"distribute\":\"paypalInit\",\"record_handler\":\"insertRecordWeb\"},\"request\":{\"_token\":\"LQTiAQ1NL1koNKL0y2nxKZgW0qC3PiD76D6wwFNt\",\"amount\":\"1000\",\"gateway_currency\":\"add-money-paypal-usd-automatic\",\"currency\":\"add-money-paypal-usd-automatic\"}}', '2026-03-10 04:46:34', '2026-03-10 04:46:34'),
(5, 'add-money', '85MYlYYOb6J37Hrd', NULL, NULL, '{\"instance\":{\"type\":\"ADD-MONEY\",\"wallet\":{\"id\":1,\"user_id\":1,\"currency_id\":1,\"balance\":\"1200.00000000\",\"status\":true,\"created_at\":\"2026-03-09T18:25:09.000000Z\",\"updated_at\":\"2026-03-09T18:54:16.000000Z\"},\"gateway\":{\"id\":1,\"slug\":\"add-money\",\"code\":105,\"type\":\"AUTOMATIC\",\"name\":\"Paypal\",\"title\":\"Paypal Gateway\",\"alias\":\"paypal\",\"image\":\"0bb65be8-a0cf-4d63-b9d7-5f5d7d76ce35.webp\",\"credentials\":[{\"label\":\"Client ID\",\"placeholder\":\"Enter Client ID\",\"name\":\"client-id\",\"value\":\"ASuAkb_xKKu00hA1Pk32UMJvBqE7-GizN2xxBH2nlQgkvGSpgl_h_fmvYiRw__lSwYnw6Bu0G9qtjEY6\"},{\"label\":\"Secret ID\",\"placeholder\":\"Enter Secret ID\",\"name\":\"secret-id\",\"value\":\"EHtZKs6f0h6wDSKykJA4lJMU6GVGTc5GARto_EKQS1R5iGA2Z8n_F8sBQPTmcMWHav1GWNl8iJj169u0\"}],\"supported_currencies\":[\"USD\",\"GBP\",\"PHP\",\"NZD\",\"MYR\",\"EUR\",\"CNY\",\"CAD\",\"AUD\"],\"crypto\":false,\"desc\":null,\"input_fields\":null,\"env\":\"SANDBOX\",\"status\":1,\"last_edit_by\":1,\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\",\"currencies\":[{\"id\":1,\"payment_gateway_id\":1,\"name\":\"Paypal USD\",\"alias\":\"add-money-paypal-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"5000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\"}]},\"currency\":{\"id\":1,\"payment_gateway_id\":1,\"name\":\"Paypal USD\",\"alias\":\"add-money-paypal-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"5000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\",\"gateway\":{\"id\":1,\"slug\":\"add-money\",\"code\":105,\"type\":\"AUTOMATIC\",\"name\":\"Paypal\",\"title\":\"Paypal Gateway\",\"alias\":\"paypal\",\"image\":\"0bb65be8-a0cf-4d63-b9d7-5f5d7d76ce35.webp\",\"credentials\":[{\"label\":\"Client ID\",\"placeholder\":\"Enter Client ID\",\"name\":\"client-id\",\"value\":\"ASuAkb_xKKu00hA1Pk32UMJvBqE7-GizN2xxBH2nlQgkvGSpgl_h_fmvYiRw__lSwYnw6Bu0G9qtjEY6\"},{\"label\":\"Secret ID\",\"placeholder\":\"Enter Secret ID\",\"name\":\"secret-id\",\"value\":\"EHtZKs6f0h6wDSKykJA4lJMU6GVGTc5GARto_EKQS1R5iGA2Z8n_F8sBQPTmcMWHav1GWNl8iJj169u0\"}],\"supported_currencies\":[\"USD\",\"GBP\",\"PHP\",\"NZD\",\"MYR\",\"EUR\",\"CNY\",\"CAD\",\"AUD\"],\"crypto\":false,\"desc\":null,\"input_fields\":null,\"env\":\"SANDBOX\",\"status\":1,\"last_edit_by\":1,\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\",\"currencies\":[{\"id\":1,\"payment_gateway_id\":1,\"name\":\"Paypal USD\",\"alias\":\"add-money-paypal-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"5000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\"}]}},\"amount\":{\"requested_amount\":\"200\",\"sender_cur_code\":\"USD\",\"sender_cur_rate\":\"1.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"percent_charge\":4,\"total_charge\":4,\"total_amount\":204,\"exchange_rate\":\"1.0000000000000000\",\"will_get\":\"200\",\"default_currency\":\"USD\"},\"form_data\":{\"_token\":\"LQTiAQ1NL1koNKL0y2nxKZgW0qC3PiD76D6wwFNt\",\"amount\":\"200\",\"gateway_currency\":\"add-money-paypal-usd-automatic\",\"currency\":\"add-money-paypal-usd-automatic\"},\"distribute\":\"paypalInit\",\"record_handler\":\"insertRecordWeb\"},\"request\":{\"_token\":\"LQTiAQ1NL1koNKL0y2nxKZgW0qC3PiD76D6wwFNt\",\"amount\":\"200\",\"gateway_currency\":\"add-money-paypal-usd-automatic\",\"currency\":\"add-money-paypal-usd-automatic\"}}', '2026-03-10 04:56:19', '2026-03-10 04:56:19'),
(6, 'ADD-MONEY', '0P7751119B234311R', NULL, NULL, '{\"gateway\":1,\"currency\":1,\"amount\":{\"requested_amount\":\"200\",\"sender_cur_code\":\"USD\",\"sender_cur_rate\":\"1.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"percent_charge\":4,\"total_charge\":4,\"total_amount\":204,\"exchange_rate\":\"1.0000000000000000\",\"will_get\":\"200\",\"default_currency\":\"USD\"},\"response\":{\"id\":\"0P7751119B234311R\",\"status\":\"CREATED\",\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/0P7751119B234311R\",\"rel\":\"self\",\"method\":\"GET\"},{\"href\":\"https:\\/\\/www.sandbox.paypal.com\\/checkoutnow?token=0P7751119B234311R\",\"rel\":\"approve\",\"method\":\"GET\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/0P7751119B234311R\",\"rel\":\"update\",\"method\":\"PATCH\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/0P7751119B234311R\\/capture\",\"rel\":\"capture\",\"method\":\"POST\"}]},\"wallet_table\":\"user_wallets\",\"wallet_id\":1,\"creator_table\":\"users\",\"creator_id\":1,\"creator_guard\":\"web\"}', '2026-03-10 04:56:37', '2026-03-10 04:56:37'),
(7, 'add-money', 'i93W5jxyOm5DSHcH', NULL, NULL, '{\"instance\":{\"type\":\"ADD-MONEY\",\"wallet\":{\"id\":2,\"user_id\":2,\"currency_id\":1,\"balance\":\"0.00000000\",\"status\":true,\"created_at\":\"2026-03-09T21:21:34.000000Z\",\"updated_at\":null},\"gateway\":{\"id\":1,\"slug\":\"add-money\",\"code\":105,\"type\":\"AUTOMATIC\",\"name\":\"Paypal\",\"title\":\"Paypal Gateway\",\"alias\":\"paypal\",\"image\":\"0bb65be8-a0cf-4d63-b9d7-5f5d7d76ce35.webp\",\"credentials\":[{\"label\":\"Client ID\",\"placeholder\":\"Enter Client ID\",\"name\":\"client-id\",\"value\":\"ASuAkb_xKKu00hA1Pk32UMJvBqE7-GizN2xxBH2nlQgkvGSpgl_h_fmvYiRw__lSwYnw6Bu0G9qtjEY6\"},{\"label\":\"Secret ID\",\"placeholder\":\"Enter Secret ID\",\"name\":\"secret-id\",\"value\":\"EHtZKs6f0h6wDSKykJA4lJMU6GVGTc5GARto_EKQS1R5iGA2Z8n_F8sBQPTmcMWHav1GWNl8iJj169u0\"}],\"supported_currencies\":[\"USD\",\"GBP\",\"PHP\",\"NZD\",\"MYR\",\"EUR\",\"CNY\",\"CAD\",\"AUD\"],\"crypto\":false,\"desc\":null,\"input_fields\":null,\"env\":\"SANDBOX\",\"status\":1,\"last_edit_by\":1,\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\",\"currencies\":[{\"id\":1,\"payment_gateway_id\":1,\"name\":\"Paypal USD\",\"alias\":\"add-money-paypal-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"5000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\"}]},\"currency\":{\"id\":1,\"payment_gateway_id\":1,\"name\":\"Paypal USD\",\"alias\":\"add-money-paypal-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"5000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\",\"gateway\":{\"id\":1,\"slug\":\"add-money\",\"code\":105,\"type\":\"AUTOMATIC\",\"name\":\"Paypal\",\"title\":\"Paypal Gateway\",\"alias\":\"paypal\",\"image\":\"0bb65be8-a0cf-4d63-b9d7-5f5d7d76ce35.webp\",\"credentials\":[{\"label\":\"Client ID\",\"placeholder\":\"Enter Client ID\",\"name\":\"client-id\",\"value\":\"ASuAkb_xKKu00hA1Pk32UMJvBqE7-GizN2xxBH2nlQgkvGSpgl_h_fmvYiRw__lSwYnw6Bu0G9qtjEY6\"},{\"label\":\"Secret ID\",\"placeholder\":\"Enter Secret ID\",\"name\":\"secret-id\",\"value\":\"EHtZKs6f0h6wDSKykJA4lJMU6GVGTc5GARto_EKQS1R5iGA2Z8n_F8sBQPTmcMWHav1GWNl8iJj169u0\"}],\"supported_currencies\":[\"USD\",\"GBP\",\"PHP\",\"NZD\",\"MYR\",\"EUR\",\"CNY\",\"CAD\",\"AUD\"],\"crypto\":false,\"desc\":null,\"input_fields\":null,\"env\":\"SANDBOX\",\"status\":1,\"last_edit_by\":1,\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\",\"currencies\":[{\"id\":1,\"payment_gateway_id\":1,\"name\":\"Paypal USD\",\"alias\":\"add-money-paypal-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"5000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-05-29\",\"updated_at\":\"2023-08-07\"}]}},\"amount\":{\"requested_amount\":\"1000\",\"sender_cur_code\":\"USD\",\"sender_cur_rate\":\"1.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"percent_charge\":20,\"total_charge\":20,\"total_amount\":1020,\"exchange_rate\":\"1.0000000000000000\",\"will_get\":\"1000\",\"default_currency\":\"USD\"},\"form_data\":{\"_token\":\"o4dblAqcamLEPUTRYE8rHo6nMwN3D1aBRyVLZgwj\",\"amount\":\"1000\",\"gateway_currency\":\"add-money-paypal-usd-automatic\",\"currency\":\"add-money-paypal-usd-automatic\"},\"distribute\":\"paypalInit\",\"record_handler\":\"insertRecordWeb\"},\"request\":{\"_token\":\"o4dblAqcamLEPUTRYE8rHo6nMwN3D1aBRyVLZgwj\",\"amount\":\"1000\",\"gateway_currency\":\"add-money-paypal-usd-automatic\",\"currency\":\"add-money-paypal-usd-automatic\"}}', '2026-03-10 07:24:32', '2026-03-10 07:24:32'),
(8, 'ADD-MONEY', '40D10643747461612', NULL, NULL, '{\"gateway\":1,\"currency\":1,\"amount\":{\"requested_amount\":\"1000\",\"sender_cur_code\":\"USD\",\"sender_cur_rate\":\"1.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"percent_charge\":20,\"total_charge\":20,\"total_amount\":1020,\"exchange_rate\":\"1.0000000000000000\",\"will_get\":\"1000\",\"default_currency\":\"USD\"},\"response\":{\"id\":\"40D10643747461612\",\"status\":\"CREATED\",\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/40D10643747461612\",\"rel\":\"self\",\"method\":\"GET\"},{\"href\":\"https:\\/\\/www.sandbox.paypal.com\\/checkoutnow?token=40D10643747461612\",\"rel\":\"approve\",\"method\":\"GET\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/40D10643747461612\",\"rel\":\"update\",\"method\":\"PATCH\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/40D10643747461612\\/capture\",\"rel\":\"capture\",\"method\":\"POST\"}]},\"wallet_table\":\"user_wallets\",\"wallet_id\":2,\"creator_table\":\"users\",\"creator_id\":2,\"creator_guard\":\"web\"}', '2026-03-10 07:25:55', '2026-03-10 07:25:55'),
(10, 'add-money', 'kGiGj8W2GMuANKnc', NULL, NULL, '{\"instance\":{\"type\":\"ADD-MONEY\",\"wallet\":{\"id\":2,\"user_id\":2,\"currency_id\":1,\"balance\":\"1000.00000000\",\"status\":true,\"created_at\":\"2026-03-09T21:21:34.000000Z\",\"updated_at\":\"2026-03-09T21:28:03.000000Z\"},\"gateway\":{\"id\":3,\"slug\":\"add-money\",\"code\":120,\"type\":\"AUTOMATIC\",\"name\":\"Stripe\",\"title\":\"Stripe Gateway\",\"alias\":\"stripe\",\"image\":\"357e631f-ee53-47cb-b603-71ee46b038c9.webp\",\"credentials\":[{\"label\":\"Test Publishable Key\",\"placeholder\":\"Enter Test Publishable Key\",\"name\":\"test-publishable-key\",\"value\":\"YOUR_STRIPE_TEST_PUBLISHABLE_KEY\"},{\"label\":\"Test Secret Key\",\"placeholder\":\"Enter Test Secret Key\",\"name\":\"test-secret-key\",\"value\":\"YOUR_STRIPE_TEST_SECRET_KEY\"},{\"label\":\"Live Publishable Key\",\"placeholder\":\"Enter Live Publishable Key\",\"name\":\"live-publishable-key\",\"value\":null},{\"label\":\"Live Secret Key\",\"placeholder\":\"Enter Live Secret Key\",\"name\":\"live-secret-key\",\"value\":null}],\"supported_currencies\":[\"USD\",\"AUD\",\"AED\",\"BDT\",\"BGN\",\"CAD\",\"EGP\",\"EUR\",\"GBP\",\"INR\",\"PKR\",\"MYR\",\"NGN\"],\"crypto\":false,\"desc\":null,\"input_fields\":null,\"env\":\"SANDBOX\",\"status\":1,\"last_edit_by\":1,\"created_at\":\"2023-11-07\",\"updated_at\":\"2023-11-07\",\"currencies\":[{\"id\":3,\"payment_gateway_id\":3,\"name\":\"Stripe USD\",\"alias\":\"add-money-stripe-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"1000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"1.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-11-07\",\"updated_at\":\"2023-11-07\"}]},\"currency\":{\"id\":3,\"payment_gateway_id\":3,\"name\":\"Stripe USD\",\"alias\":\"add-money-stripe-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"1000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"1.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-11-07\",\"updated_at\":\"2023-11-07\",\"gateway\":{\"id\":3,\"slug\":\"add-money\",\"code\":120,\"type\":\"AUTOMATIC\",\"name\":\"Stripe\",\"title\":\"Stripe Gateway\",\"alias\":\"stripe\",\"image\":\"357e631f-ee53-47cb-b603-71ee46b038c9.webp\",\"credentials\":[{\"label\":\"Test Publishable Key\",\"placeholder\":\"Enter Test Publishable Key\",\"name\":\"test-publishable-key\",\"value\":\"YOUR_STRIPE_TEST_PUBLISHABLE_KEY\"},{\"label\":\"Test Secret Key\",\"placeholder\":\"Enter Test Secret Key\",\"name\":\"test-secret-key\",\"value\":\"YOUR_STRIPE_TEST_SECRET_KEY\"},{\"label\":\"Live Publishable Key\",\"placeholder\":\"Enter Live Publishable Key\",\"name\":\"live-publishable-key\",\"value\":null},{\"label\":\"Live Secret Key\",\"placeholder\":\"Enter Live Secret Key\",\"name\":\"live-secret-key\",\"value\":null}],\"supported_currencies\":[\"USD\",\"AUD\",\"AED\",\"BDT\",\"BGN\",\"CAD\",\"EGP\",\"EUR\",\"GBP\",\"INR\",\"PKR\",\"MYR\",\"NGN\"],\"crypto\":false,\"desc\":null,\"input_fields\":null,\"env\":\"SANDBOX\",\"status\":1,\"last_edit_by\":1,\"created_at\":\"2023-11-07\",\"updated_at\":\"2023-11-07\",\"currencies\":[{\"id\":3,\"payment_gateway_id\":3,\"name\":\"Stripe USD\",\"alias\":\"add-money-stripe-usd-automatic\",\"currency_code\":\"USD\",\"currency_symbol\":\"$\",\"image\":null,\"min_limit\":\"1.0000000000000000\",\"max_limit\":\"1000.0000000000000000\",\"percent_charge\":\"2.0000000000000000\",\"fixed_charge\":\"1.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2023-11-07\",\"updated_at\":\"2023-11-07\"}]}},\"amount\":{\"requested_amount\":\"1000\",\"sender_cur_code\":\"USD\",\"sender_cur_rate\":\"1.0000000000000000\",\"fixed_charge\":\"1.0000000000000000\",\"percent_charge\":20,\"total_charge\":21,\"total_amount\":1021,\"exchange_rate\":\"1.0000000000000000\",\"will_get\":\"1000\",\"default_currency\":\"USD\"},\"form_data\":{\"_token\":\"o4dblAqcamLEPUTRYE8rHo6nMwN3D1aBRyVLZgwj\",\"amount\":\"1000\",\"gateway_currency\":\"add-money-stripe-usd-automatic\",\"currency\":\"add-money-stripe-usd-automatic\"},\"distribute\":\"stripeInit\",\"record_handler\":\"insertRecordWeb\"},\"request\":{\"_token\":\"o4dblAqcamLEPUTRYE8rHo6nMwN3D1aBRyVLZgwj\",\"amount\":\"1000\",\"gateway_currency\":\"add-money-stripe-usd-automatic\",\"currency\":\"add-money-stripe-usd-automatic\"}}', '2026-03-10 08:16:27', '2026-03-10 08:16:27'),
(11, 'money-out', 'mtNXhiJ6RVNVABy4', NULL, NULL, '{\"gateway_currency_id\":8,\"wallet_id\":2,\"charges\":{\"exchange_rate\":\"289.3800000000000000\",\"request_amount\":\"23\",\"fixed_charge\":0.0034556638330223237,\"percent_charge\":0.6900000000000001,\"gateway_currency_code\":\"PKR\",\"gateway_currency_id\":8,\"sender_currency_code\":\"USD\",\"sender_wallet_id\":2,\"will_get\":6655.74,\"receive_currency\":\"PKR\",\"sender_currency\":\"USD\",\"total_charge\":0.6934556638330224,\"total_payable\":23.69345566383302}}', '2026-03-11 20:58:32', '2026-03-11 20:58:32'),
(12, 'add-money', 'jx2cZY3lr4o5TeGA', NULL, NULL, '{\"instance\":{\"type\":\"ADD-MONEY\",\"wallet\":{\"id\":2,\"user_id\":2,\"currency_id\":1,\"balance\":\"1000.00000000\",\"status\":true,\"created_at\":\"2026-03-09T21:21:34.000000Z\",\"updated_at\":\"2026-03-09T21:28:03.000000Z\"},\"gateway\":{\"id\":2,\"slug\":\"add-money\",\"code\":110,\"type\":\"AUTOMATIC\",\"name\":\"CoinGate\",\"title\":\"Crypto Payment gateway\",\"alias\":\"coingate\",\"image\":\"5f5ceacd-bd60-40ba-a8f2-438a90a47388.webp\",\"credentials\":[{\"label\":\"Sandbox URL\",\"placeholder\":\"Enter Sandbox URL\",\"name\":\"sandbox-url\",\"value\":\"https:\\/\\/api-sandbox.coingate.com\\/v2\"},{\"label\":\"Sandbox App Token\",\"placeholder\":\"Enter Sandbox App Token\",\"name\":\"sandbox-app-token\",\"value\":\"XJW4RyhT8F-xssX2PvaHMWJjYe5nsbsrbb2Uqy4m\"},{\"label\":\"Production URL\",\"placeholder\":\"Enter Production URL\",\"name\":\"production-url\",\"value\":\"https:\\/\\/api.coingate.com\\/v2\"},{\"label\":\"Production App Token\",\"placeholder\":\"Enter Production App Token\",\"name\":\"production-app-token\",\"value\":null}],\"supported_currencies\":[\"USD\",\"BTC\",\"LTC\",\"ETH\",\"BCH\",\"TRX\",\"ETC\",\"DOGE\",\"BTG\",\"BNB\",\"TUSD\",\"USDT\",\"BSV\",\"MATIC\",\"BUSD\",\"SOL\",\"WBTC\",\"RVN\",\"BCD\",\"ATOM\",\"BTTC\",\"EURT\"],\"crypto\":true,\"desc\":null,\"input_fields\":null,\"env\":\"SANDBOX\",\"status\":1,\"last_edit_by\":1,\"created_at\":\"2023-08-07\",\"updated_at\":\"2023-08-07\",\"currencies\":[{\"id\":14,\"payment_gateway_id\":2,\"name\":\"CoinGate USDT\",\"alias\":\"add-money-coingate-usdt-automatic\",\"currency_code\":\"USDT\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"100.0000000000000000\",\"max_limit\":\"100000000000.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"},{\"id\":13,\"payment_gateway_id\":2,\"name\":\"CoinGate BNB\",\"alias\":\"add-money-coingate-bnb-automatic\",\"currency_code\":\"BNB\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"0.0000000000000000\",\"max_limit\":\"0.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"},{\"id\":12,\"payment_gateway_id\":2,\"name\":\"CoinGate TRX\",\"alias\":\"add-money-coingate-trx-automatic\",\"currency_code\":\"TRX\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"0.0000000000000000\",\"max_limit\":\"0.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"},{\"id\":11,\"payment_gateway_id\":2,\"name\":\"CoinGate LTC\",\"alias\":\"add-money-coingate-ltc-automatic\",\"currency_code\":\"LTC\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"0.0000000000000000\",\"max_limit\":\"0.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"},{\"id\":10,\"payment_gateway_id\":2,\"name\":\"CoinGate ETH\",\"alias\":\"add-money-coingate-eth-automatic\",\"currency_code\":\"ETH\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"0.0000000000000000\",\"max_limit\":\"0.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"},{\"id\":9,\"payment_gateway_id\":2,\"name\":\"CoinGate BTC\",\"alias\":\"add-money-coingate-btc-automatic\",\"currency_code\":\"BTC\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"0.0000000000000000\",\"max_limit\":\"0.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"}]},\"currency\":{\"id\":14,\"payment_gateway_id\":2,\"name\":\"CoinGate USDT\",\"alias\":\"add-money-coingate-usdt-automatic\",\"currency_code\":\"USDT\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"100.0000000000000000\",\"max_limit\":\"100000000000.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\",\"gateway\":{\"id\":2,\"slug\":\"add-money\",\"code\":110,\"type\":\"AUTOMATIC\",\"name\":\"CoinGate\",\"title\":\"Crypto Payment gateway\",\"alias\":\"coingate\",\"image\":\"5f5ceacd-bd60-40ba-a8f2-438a90a47388.webp\",\"credentials\":[{\"label\":\"Sandbox URL\",\"placeholder\":\"Enter Sandbox URL\",\"name\":\"sandbox-url\",\"value\":\"https:\\/\\/api-sandbox.coingate.com\\/v2\"},{\"label\":\"Sandbox App Token\",\"placeholder\":\"Enter Sandbox App Token\",\"name\":\"sandbox-app-token\",\"value\":\"XJW4RyhT8F-xssX2PvaHMWJjYe5nsbsrbb2Uqy4m\"},{\"label\":\"Production URL\",\"placeholder\":\"Enter Production URL\",\"name\":\"production-url\",\"value\":\"https:\\/\\/api.coingate.com\\/v2\"},{\"label\":\"Production App Token\",\"placeholder\":\"Enter Production App Token\",\"name\":\"production-app-token\",\"value\":null}],\"supported_currencies\":[\"USD\",\"BTC\",\"LTC\",\"ETH\",\"BCH\",\"TRX\",\"ETC\",\"DOGE\",\"BTG\",\"BNB\",\"TUSD\",\"USDT\",\"BSV\",\"MATIC\",\"BUSD\",\"SOL\",\"WBTC\",\"RVN\",\"BCD\",\"ATOM\",\"BTTC\",\"EURT\"],\"crypto\":true,\"desc\":null,\"input_fields\":null,\"env\":\"SANDBOX\",\"status\":1,\"last_edit_by\":1,\"created_at\":\"2023-08-07\",\"updated_at\":\"2023-08-07\",\"currencies\":[{\"id\":14,\"payment_gateway_id\":2,\"name\":\"CoinGate USDT\",\"alias\":\"add-money-coingate-usdt-automatic\",\"currency_code\":\"USDT\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"100.0000000000000000\",\"max_limit\":\"100000000000.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"},{\"id\":13,\"payment_gateway_id\":2,\"name\":\"CoinGate BNB\",\"alias\":\"add-money-coingate-bnb-automatic\",\"currency_code\":\"BNB\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"0.0000000000000000\",\"max_limit\":\"0.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"},{\"id\":12,\"payment_gateway_id\":2,\"name\":\"CoinGate TRX\",\"alias\":\"add-money-coingate-trx-automatic\",\"currency_code\":\"TRX\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"0.0000000000000000\",\"max_limit\":\"0.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"},{\"id\":11,\"payment_gateway_id\":2,\"name\":\"CoinGate LTC\",\"alias\":\"add-money-coingate-ltc-automatic\",\"currency_code\":\"LTC\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"0.0000000000000000\",\"max_limit\":\"0.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"},{\"id\":10,\"payment_gateway_id\":2,\"name\":\"CoinGate ETH\",\"alias\":\"add-money-coingate-eth-automatic\",\"currency_code\":\"ETH\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"0.0000000000000000\",\"max_limit\":\"0.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"},{\"id\":9,\"payment_gateway_id\":2,\"name\":\"CoinGate BTC\",\"alias\":\"add-money-coingate-btc-automatic\",\"currency_code\":\"BTC\",\"currency_symbol\":null,\"image\":null,\"min_limit\":\"0.0000000000000000\",\"max_limit\":\"0.0000000000000000\",\"percent_charge\":\"0.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"rate\":\"1.0000000000000000\",\"created_at\":\"2026-03-13\",\"updated_at\":\"2026-03-13\"}]}},\"amount\":{\"requested_amount\":\"1000\",\"sender_cur_code\":\"USDT\",\"sender_cur_rate\":\"1.0000000000000000\",\"fixed_charge\":\"0.0000000000000000\",\"percent_charge\":0,\"total_charge\":0,\"total_amount\":1000,\"exchange_rate\":\"1.0000000000000000\",\"will_get\":\"1000\",\"default_currency\":\"USD\"},\"form_data\":{\"_token\":\"PFhEKPFmT6X7Ow8NMnerqpuBSGfWH3PfwIEDe8zY\",\"amount\":\"1000\",\"gateway_currency\":\"add-money-coingate-usdt-automatic\",\"currency\":\"add-money-coingate-usdt-automatic\"},\"distribute\":\"coinGateInit\",\"record_handler\":\"insertRecordWeb\"},\"request\":{\"_token\":\"PFhEKPFmT6X7Ow8NMnerqpuBSGfWH3PfwIEDe8zY\",\"amount\":\"1000\",\"gateway_currency\":\"add-money-coingate-usdt-automatic\",\"currency\":\"add-money-coingate-usdt-automatic\"}}', '2026-03-13 10:40:52', '2026-03-13 10:40:52');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('ADD-MONEY','MONEY-OUT','WITHDRAW','COMMISSION','BONUS','TRANSFER-MONEY','MONEY-EXCHANGE','ADD-SUBTRACT-BALANCE','MAKE-PAYMENT','CAPITAL-RETURN','OTHER-BANK-TRANSFER','OWN-BANK-TRANSFER','MOBILE-WALLET-TRANSFER','VIRTUAL-CARD','Salary Disbursement') NOT NULL,
  `trx_id` varchar(191) NOT NULL COMMENT 'Transaction ID',
  `salary_disbursement_id` varchar(191) DEFAULT NULL,
  `user_type` enum('USER','ADMIN') DEFAULT NULL COMMENT 'transaction creator',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'transaction creator id',
  `wallet_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'transaction creator wallet it',
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_gateway_currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `request_amount` decimal(28,8) NOT NULL COMMENT 'add money: user wallet balance, money transfer: send amount, money out: withdraw wallet amount',
  `request_currency` varchar(191) NOT NULL COMMENT 'In add money user wallet currency, money transfer receiver currency',
  `exchange_rate` decimal(28,8) DEFAULT NULL,
  `percent_charge` decimal(28,8) DEFAULT NULL,
  `fixed_charge` decimal(28,8) DEFAULT NULL,
  `total_charge` decimal(28,8) DEFAULT NULL,
  `total_payable` decimal(28,8) DEFAULT NULL,
  `receive_amount` decimal(28,8) DEFAULT NULL COMMENT 'add money: user wallet balance, money transfer: receiver amount, money out: user receive amount using manual info',
  `receiver_type` enum('USER','ADMIN') DEFAULT NULL COMMENT 'Uses maybe money transfer, make payment',
  `receiver_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Uses maybe money transfer, make payment',
  `available_balance` decimal(28,8) NOT NULL,
  `payment_currency` varchar(191) DEFAULT NULL COMMENT 'user payment currency (wallet/gateway)',
  `remark` varchar(191) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `attribute` enum('SEND','RECEIVED') DEFAULT NULL,
  `reject_reason` text DEFAULT NULL,
  `callback_ref` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `type`, `trx_id`, `salary_disbursement_id`, `user_type`, `user_id`, `wallet_id`, `admin_id`, `payment_gateway_currency_id`, `request_amount`, `request_currency`, `exchange_rate`, `percent_charge`, `fixed_charge`, `total_charge`, `total_payable`, `receive_amount`, `receiver_type`, `receiver_id`, `available_balance`, `payment_currency`, `remark`, `details`, `status`, `attribute`, `reject_reason`, `callback_ref`, `created_at`, `updated_at`) VALUES
(4, 'ADD-MONEY', 'AM-12146125755283', NULL, 'USER', 1, 1, NULL, 7, 1000.00000000, 'USD', 1.00000000, 30.00000000, 1.00000000, 31.00000000, 1031.00000000, 1000.00000000, 'USER', 1, 1000.00000000, 'USD', NULL, '{\"input_values\":[{\"type\":\"file\",\"label\":\"Screenshot\",\"name\":\"screenshot\",\"required\":false,\"validation\":{\"max\":\"10\",\"mimes\":[\"jpg\",\"png\",\"webp\",\"svg\"],\"min\":\"0\",\"options\":[],\"required\":false},\"value\":\"fadae205-faeb-48cb-bbb2-b66033ae647e.webp\"},{\"type\":\"text\",\"label\":\"Transaction ID\",\"name\":\"transaction_id\",\"required\":true,\"validation\":{\"max\":\"60\",\"mimes\":[],\"min\":\"0\",\"options\":[],\"required\":true},\"value\":\"123457\"}]}', 1, 'RECEIVED', NULL, NULL, '2026-03-10 04:49:11', '2026-03-10 04:51:18'),
(5, 'ADD-MONEY', 'AM-96676235507890', NULL, 'USER', 1, 1, NULL, 7, 200.00000000, 'USD', 1.00000000, 6.00000000, 1.00000000, 7.00000000, 207.00000000, 200.00000000, 'USER', 1, 1200.00000000, 'USD', NULL, '{\"input_values\":[{\"type\":\"file\",\"label\":\"Screenshot\",\"name\":\"screenshot\",\"required\":false,\"validation\":{\"max\":\"10\",\"mimes\":[\"jpg\",\"png\",\"webp\",\"svg\"],\"min\":\"0\",\"options\":[],\"required\":false},\"value\":\"9bb650a5-92d2-4a9e-84da-9b702da2ed82.webp\"},{\"type\":\"text\",\"label\":\"Transaction ID\",\"name\":\"transaction_id\",\"required\":true,\"validation\":{\"max\":\"60\",\"mimes\":[],\"min\":\"0\",\"options\":[],\"required\":true},\"value\":\"123457\"}]}', 1, 'RECEIVED', NULL, NULL, '2026-03-10 04:53:30', '2026-03-10 04:54:16'),
(6, 'ADD-MONEY', 'AM-40777808938999', NULL, 'USER', 2, 2, NULL, 7, 1000.00000000, 'USD', 1.00000000, 30.00000000, 1.00000000, 31.00000000, 1031.00000000, 1000.00000000, 'USER', 2, 1000.00000000, 'USD', NULL, '{\"input_values\":[{\"type\":\"file\",\"label\":\"Screenshot\",\"name\":\"screenshot\",\"required\":false,\"validation\":{\"max\":\"10\",\"mimes\":[\"jpg\",\"png\",\"webp\",\"svg\"],\"min\":\"0\",\"options\":[],\"required\":false},\"value\":\"97c5b0a4-a21b-49f5-ab3e-afae6a22adc6.webp\"},{\"type\":\"text\",\"label\":\"Transaction ID\",\"name\":\"transaction_id\",\"required\":true,\"validation\":{\"max\":\"60\",\"mimes\":[],\"min\":\"0\",\"options\":[],\"required\":true},\"value\":\"5677899\"}]}', 1, 'RECEIVED', NULL, NULL, '2026-03-10 07:27:05', '2026-03-10 07:28:03');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_devices`
--

CREATE TABLE `transaction_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `mac` varchar(17) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `browser` varchar(30) DEFAULT NULL,
  `os` varchar(30) DEFAULT NULL,
  `timezone` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_methods`
--

CREATE TABLE `transaction_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `last_edit_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_methods`
--

INSERT INTO `transaction_methods` (`id`, `name`, `slug`, `last_edit_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Own Bank Transfer', 'own-bank-transfer', 1, 1, '2026-03-10 02:26:04', NULL),
(2, 'Other Bank Transfer', 'other-bank-transfer', 1, 1, '2026-03-10 02:26:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_settings`
--

CREATE TABLE `transaction_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(191) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `fixed_charge` decimal(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `percent_charge` decimal(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `min_limit` decimal(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `max_limit` decimal(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `monthly_limit` decimal(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `daily_limit` decimal(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_settings`
--

INSERT INTO `transaction_settings` (`id`, `admin_id`, `slug`, `title`, `fixed_charge`, `percent_charge`, `min_limit`, `max_limit`, `monthly_limit`, `daily_limit`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'own-bank-transfer', 'Own Bank Transfer', 1.00, 1.00, 0.00, 50000.00, 50000.00, 5000.00, 1, '2026-03-10 02:25:57', NULL),
(2, 1, 'other-bank-transfer', 'Other Bank Transfer', 1.00, 1.00, 0.00, 50000.00, 50000.00, 5000.00, 1, '2026-03-10 02:25:57', NULL),
(3, 1, 'virtual_card', 'Virtual Card Charges', 1.00, 1.00, 100.00, 50000.00, 50000.00, 50000.00, 1, '2026-03-10 02:25:57', NULL),
(4, 1, 'reload_card', 'Reload Card Charges', 1.00, 1.00, 2.00, 50000.00, 50000.00, 50000.00, 1, '2026-03-10 02:25:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `useful_links`
--

CREATE TABLE `useful_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL,
  `title` text NOT NULL,
  `slug` varchar(191) NOT NULL,
  `url` varchar(191) NOT NULL,
  `content` longtext NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `editable` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `useful_links`
--

INSERT INTO `useful_links` (`id`, `type`, `title`, `slug`, `url`, `content`, `status`, `editable`, `created_at`, `updated_at`) VALUES
(1, 'PRIVACY_POLICY', '{\"language\":{\"en\":{\"title\":\"Privacy Policy\"},\"es\":{\"title\":\"pol\\u00edtica de privacidad\"},\"ar\":{\"title\":\"\\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u062e\\u0635\\u0648\\u0635\\u064a\\u0629\"}}}', 'privacy-policy', 'privacy-policy', '{\"language\":{\"en\":{\"content\":\"<p>At iBanking, we value your privacy and are committed to safeguarding your personal information. This Privacy Policy outlines how we collect, use, and protect your data when you access our website and use our services. By using iBanking, you consent to the practices described in this policy.<\\/p><p>**1. Information Collection:**<br>We collect various types of information to provide and improve our services. This includes:- Personal Information: When you create an account, we collect your name, email address, and payment details. This information is used to verify your identity, process transactions, and provide account-related services.- Usage Data: We gather data on how you interact with our platform, such as your device information, browser type, and pages visited. This helps us enhance user experience and optimize our services.<\\/p><p>**2. Data Usage:**<br>We use your information for the following purposes:- Service Delivery: Your personal data is essential for us to process transactions, manage your investments, and offer customer support.- Improving Services: Analyzing usage patterns helps us identify areas for improvement and develop new features to better serve you.<\\/p><p>**3. Data Security:**<br>Protecting your information is a top priority for us. We implement technical and organizational measures to ensure your data is secure from unauthorized access, loss, or misuse.<\\/p><p>**4. Third Parties:**<br>We may share your data with trusted third-party partners who assist us in delivering our services, such as payment processors and analytics providers. These partners are contractually obligated to handle your data securely and only for the purposes specified.<\\/p><p>**5. Cookies and Tracking:**<br>We use cookies and similar technologies to enhance your experience on our website. Cookies are small text files that are stored on your device when you visit our site. They help us remember your preferences, analyze usage patterns, and tailor content to your interests.<\\/p><p>**6. Communication:**<br>We may send you transactional emails related to your account, updates about our services, and promotional offers. You can opt out of marketing communications at any time by adjusting your preferences in your account settings.<\\/p><p>**7. Data Retention:**<br>We retain your data for as long as necessary to provide our services or comply with legal obligations. If you wish to delete your account, please contact our support team.<\\/p><p>**8. Changes to Privacy Policy:**<br>We may update this Privacy Policy to reflect changes in our practices or legal requirements. Any revisions will be posted on our website, and your continued use of our services after such changes signifies your acceptance of the updated policy.Your privacy matters to us. If you have any questions about our Privacy Policy or how we handle your data, please contact us at [contact email]<\\/p>\"},\"es\":{\"content\":\"<p>En iBanking, valoramos su privacidad y estamos comprometidos a salvaguardar su informaci\\u00f3n personal. Esta Pol\\u00edtica de privacidad describe c\\u00f3mo recopilamos, usamos y protegemos sus datos cuando accede a nuestro sitio web y utiliza nuestros servicios. Al usar iBanking, usted acepta las pr\\u00e1cticas descritas en esta pol\\u00edtica.<\\/p><p>**1. Recopilaci\\u00f3n de inEn iBanking, valoramos su privacidad y nos comprometemos a proteger su informaci\\u00f3n personal. Esta Pol\\u00edtica de privacidad describe c\\u00f3mo recopilamos, usamos y protegemos sus datos cuando accede a nuestro sitio web y utiliza nuestros servicios. Al utilizar iBanking, usted acepta las pr\\u00e1cticas descritas en esta pol\\u00edtica.<\\/p><p>**1. Recopilaci\\u00f3n de informaci\\u00f3n:**<br>Recopilamos varios tipos de informaci\\u00f3n para brindar y mejorar nuestros servicios. Esto incluye:- Informaci\\u00f3n personal: cuando crea una cuenta, recopilamos su nombre, direcci\\u00f3n de correo electr\\u00f3nico y detalles de pago. Esta informaci\\u00f3n se utiliza para verificar su identidad, procesar transacciones y brindar servicios relacionados con la cuenta.- Datos de uso: recopilamos datos sobre c\\u00f3mo interact\\u00faa con nuestra plataforma, como la informaci\\u00f3n de su dispositivo, el tipo de navegador y las p\\u00e1ginas visitadas. Esto nos ayuda a mejorar la experiencia del usuario y optimizar nuestros servicios.<\\/p><p>**2. Uso de datos:**<br>Usamos su informaci\\u00f3n para los siguientes fines:- Prestaci\\u00f3n de servicios: sus datos personales son esenciales para que podamos procesar transacciones, administrar sus inversiones y ofrecer soporte al cliente.- Mejora de los servicios: analizar los patrones de uso nos ayuda a identificar \\u00e1reas de mejora y desarrollar nuevas funciones para brindarle un mejor servicio.<\\/p><p>**3. Seguridad de los datos:**<br>Proteger su informaci\\u00f3n es una prioridad para nosotros. Implementamos medidas t\\u00e9cnicas y organizativas para garantizar que sus datos est\\u00e9n protegidos contra accesos no autorizados, p\\u00e9rdida o uso indebido.<\\/p><p>**4. Terceros:**<br>Podemos compartir sus datos con socios externos de confianza que nos ayudan a brindar nuestros servicios, como procesadores de pagos y proveedores de an\\u00e1lisis. Estos socios est\\u00e1n obligados por contrato a manejar sus datos de forma segura y solo para los fines especificados.<\\/p><p>**5. Cookies y seguimiento:**<br>Usamos cookies y tecnolog\\u00edas similares para mejorar su experiencia en nuestro sitio web. Las cookies son peque\\u00f1os archivos de texto que se almacenan en su dispositivo cuando visita nuestro sitio. Nos ayudan a recordar sus preferencias, analizar patrones de uso y adaptar el contenido a sus intereses.<\\/p><p>**6. Comunicaci\\u00f3n:**<br>Podemos enviarle correos electr\\u00f3nicos transaccionales relacionados con su cuenta, actualizaciones sobre nuestros servicios y ofertas promocionales. Puede optar por no recibir comunicaciones de marketing en cualquier momento ajustando sus preferencias en la configuraci\\u00f3n de su cuenta.<\\/p><p>**7. Retenci\\u00f3n de datos:**<br>Retenemos sus datos durante el tiempo que sea necesario para brindar nuestros servicios o cumplir con las obligaciones legales. Si desea eliminar su cuenta, comun\\u00edquese con nuestro equipo de soporte.<\\/p><p>**8. Cambios en la Pol\\u00edtica de privacidad:**<br>Podemos actualizar esta Pol\\u00edtica de privacidad para reflejar cambios en nuestras pr\\u00e1cticas o requisitos legales. Cualquier revisi\\u00f3n se publicar\\u00e1 en nuestro sitio web, y su uso continuo de nuestros servicios despu\\u00e9s de dichos cambios significa que acepta la pol\\u00edtica actualizada. Su privacidad es importante para nosotros. Si tiene alguna pregunta sobre nuestra Pol\\u00edtica de privacidad o sobre c\\u00f3mo manejamos sus datos, comun\\u00edquese con nosotros a [correo electr\\u00f3nico de contacto]formaci\\u00f3n:**<br>Recopilamos varios tipos de informaci\\u00f3n para proporcionar y mejorar nuestros servicios. Esto incluye:- Informaci\\u00f3n personal: cuando crea una cuenta, recopilamos su nombre, direcci\\u00f3n de correo electr\\u00f3nico y detalles de pago. Esta informaci\\u00f3n se utiliza para verificar su identidad, procesar transacciones y brindar servicios relacionados con la cuenta.- Datos de uso: recopilamos datos sobre c\\u00f3mo interact\\u00faa con nuestra plataforma, como la informaci\\u00f3n de su dispositivo, el tipo de navegador y las p\\u00e1ginas visitadas. Esto nos ayuda a mejorar la experiencia del usuario y optimizar nuestros servicios.<\\/p><p>**2. Uso de datos:**<br>Usamos su informaci\\u00f3n para los siguientes prop\\u00f3sitos:- Prestaci\\u00f3n de servicios: sus datos personales son esenciales para que procesemos transacciones, gestionemos sus inversiones y ofrezcamos atenci\\u00f3n al cliente.- Mejora de los servicios: el an\\u00e1lisis de patrones de uso nos ayuda a identificar \\u00e1reas de mejora y desarrollar nuevas funciones para servirle mejor.<\\/p><p>**3. Seguridad de datos:**<br>Proteger su informaci\\u00f3n es una prioridad para nosotros. Implementamos medidas t\\u00e9cnicas y organizativas para garantizar que sus datos est\\u00e9n seguros contra el acceso no autorizado, la p\\u00e9rdida o el uso indebido.<\\/p><p>**4. Terceros:**<br>Podemos compartir sus datos con socios externos de confianza que nos ayuden a brindar nuestros servicios, como procesadores de pagos y proveedores de an\\u00e1lisis. Estos socios est\\u00e1n obligados por contrato a manejar sus datos de forma segura y solo para los fines especificados.<\\/p><p>**5. Cookies y Seguimiento:**<br>Utilizamos cookies y tecnolog\\u00edas similares para mejorar su experiencia en nuestro sitio web. Las cookies son peque\\u00f1os archivos de texto que se almacenan en su dispositivo cuando visita nuestro sitio. Nos ayudan a recordar sus preferencias, analizar patrones de uso y adaptar el contenido a sus intereses.<\\/p><p>**6. Comunicaci\\u00f3n:**<br>Podemos enviarle correos electr\\u00f3nicos transaccionales relacionados con su cuenta, actualizaciones sobre nuestros servicios y ofertas promocionales. Puede optar por no recibir comunicaciones de marketing en cualquier momento ajustando sus preferencias en la configuraci\\u00f3n de su cuenta.<\\/p><p>**7. Retenci\\u00f3n de datos:**<br>Conservamos sus datos durante el tiempo que sea necesario para prestar nuestros servicios o cumplir con las obligaciones legales. Si desea eliminar su cuenta, comun\\u00edquese con nuestro equipo de soporte.<\\/p><p>**8. Cambios a la Pol\\u00edtica de Privacidad:**<br>Podemos actualizar esta Pol\\u00edtica de privacidad para reflejar cambios en nuestras pr\\u00e1cticas o requisitos legales. Cualquier revisi\\u00f3n se publicar\\u00e1 en nuestro sitio web, y su uso continuado de nuestros servicios despu\\u00e9s de dichos cambios significa que acepta la pol\\u00edtica actualizada. Su privacidad nos importa. Si tiene alguna pregunta sobre nuestra Pol\\u00edtica de privacidad o sobre c\\u00f3mo manejamos sus datos, cont\\u00e1ctenos en [correo electr\\u00f3nico de contacto]<\\/p>\"},\"ar\":{\"content\":\"<p>\\u0641\\u064a iBanking\\u060c \\u0646\\u0642\\u062f\\u0631 \\u062e\\u0635\\u0648\\u0635\\u064a\\u062a\\u0643 \\u0648\\u0646\\u0644\\u062a\\u0632\\u0645 \\u0628\\u062d\\u0645\\u0627\\u064a\\u0629 \\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0634\\u062e\\u0635\\u064a\\u0629. \\u062a\\u0648\\u0636\\u062d \\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u062e\\u0635\\u0648\\u0635\\u064a\\u0629 \\u0647\\u0630\\u0647 \\u0643\\u064a\\u0641\\u064a\\u0629 \\u062c\\u0645\\u0639 \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643 \\u0648\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645\\u0647\\u0627 \\u0648\\u062d\\u0645\\u0627\\u064a\\u062a\\u0647\\u0627 \\u0639\\u0646\\u062f \\u0648\\u0635\\u0648\\u0644\\u0643 \\u0625\\u0644\\u0649 \\u0645\\u0648\\u0642\\u0639\\u0646\\u0627 \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a \\u0648\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627. \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 iBanking\\u060c \\u0641\\u0625\\u0646\\u0643 \\u062a\\u0648\\u0627\\u0641\\u0642 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0645\\u0645\\u0627\\u0631\\u0633\\u0627\\u062a \\u0627\\u0644\\u0645\\u0648\\u0636\\u062d\\u0629 \\u0641\\u064a \\u0647\\u0630\\u0647 \\u0627\\u0644\\u0633\\u064a\\u0627\\u0633\\u0629.<\\/p><p>**1. \\u062c\\u0645\\u0639 \\u0627\\u0644\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a:**<br>\\u0646\\u062c\\u0645\\u0639 \\u0623\\u0646\\u0648\\u0627\\u0639\\u064b\\u0627 \\u0645\\u062e\\u062a\\u0644\\u0641\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0644\\u062a\\u0642\\u062f\\u064a\\u0645 \\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627 \\u0648\\u062a\\u062d\\u0633\\u064a\\u0646\\u0647\\u0627. \\u0648\\u0647\\u0630\\u0627 \\u064a\\u0634\\u0645\\u0644:- \\u0627\\u0644\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0627\\u0644\\u0634\\u062e\\u0635\\u064a\\u0629: \\u0639\\u0646\\u062f \\u0625\\u0646\\u0634\\u0627\\u0621 \\u062d\\u0633\\u0627\\u0628\\u060c \\u0646\\u062c\\u0645\\u0639 \\u0627\\u0633\\u0645\\u0643 \\u0648\\u0639\\u0646\\u0648\\u0627\\u0646 \\u0628\\u0631\\u064a\\u062f\\u0643 \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a \\u0648\\u062a\\u0641\\u0627\\u0635\\u064a\\u0644 \\u0627\\u0644\\u062f\\u0641\\u0639. \\u062a\\u064f\\u0633\\u062a\\u062e\\u062f\\u0645 \\u0647\\u0630\\u0647 \\u0627\\u0644\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0644\\u0644\\u062a\\u062d\\u0642\\u0642 \\u0645\\u0646 \\u0647\\u0648\\u064a\\u062a\\u0643 \\u0648\\u0645\\u0639\\u0627\\u0644\\u062c\\u0629 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0648\\u062a\\u0642\\u062f\\u064a\\u0645 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u062a\\u0639\\u0644\\u0642\\u0629 \\u0628\\u0627\\u0644\\u062d\\u0633\\u0627\\u0628.- \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645: \\u0646\\u062c\\u0645\\u0639 \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a \\u062d\\u0648\\u0644 \\u0643\\u064a\\u0641\\u064a\\u0629 \\u062a\\u0641\\u0627\\u0639\\u0644\\u0643 \\u0645\\u0639 \\u0645\\u0646\\u0635\\u062a\\u0646\\u0627\\u060c \\u0645\\u062b\\u0644 \\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u062c\\u0647\\u0627\\u0632\\u0643 \\u0648\\u0646\\u0648\\u0639 \\u0627\\u0644\\u0645\\u062a\\u0635\\u0641\\u062d \\u0648\\u0627\\u0644\\u0635\\u0641\\u062d\\u0627\\u062a \\u0627\\u0644\\u062a\\u064a \\u0642\\u0645\\u062a \\u0628\\u0632\\u064a\\u0627\\u0631\\u062a\\u0647\\u0627. \\u064a\\u0633\\u0627\\u0639\\u062f\\u0646\\u0627 \\u0647\\u0630\\u0627 \\u0641\\u064a \\u062a\\u062d\\u0633\\u064a\\u0646 \\u062a\\u062c\\u0631\\u0628\\u0629 \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645 \\u0648\\u062a\\u062d\\u0633\\u064a\\u0646 \\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627.<\\/p><p>**2. \\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a:**<br>\\u0646\\u0633\\u062a\\u062e\\u062f\\u0645 \\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a\\u0643 \\u0644\\u0644\\u0623\\u063a\\u0631\\u0627\\u0636 \\u0627\\u0644\\u062a\\u0627\\u0644\\u064a\\u0629:- \\u062a\\u0642\\u062f\\u064a\\u0645 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0629: \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643 \\u0627\\u0644\\u0634\\u062e\\u0635\\u064a\\u0629 \\u0636\\u0631\\u0648\\u0631\\u064a\\u0629 \\u0644\\u0646\\u0627 \\u0644\\u0645\\u0639\\u0627\\u0644\\u062c\\u0629 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0648\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0633\\u062a\\u062b\\u0645\\u0627\\u0631\\u0627\\u062a\\u0643 \\u0648\\u062a\\u0642\\u062f\\u064a\\u0645 \\u062f\\u0639\\u0645 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621.- \\u062a\\u062d\\u0633\\u064a\\u0646 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a: \\u064a\\u0633\\u0627\\u0639\\u062f\\u0646\\u0627 \\u062a\\u062d\\u0644\\u064a\\u0644 \\u0623\\u0646\\u0645\\u0627\\u0637 \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0641\\u064a \\u062a\\u062d\\u062f\\u064a\\u062f \\u0645\\u062c\\u0627\\u0644\\u0627\\u062a \\u0627\\u0644\\u062a\\u062d\\u0633\\u064a\\u0646 \\u0648\\u062a\\u0637\\u0648\\u064a\\u0631 \\u0645\\u064a\\u0632\\u0627\\u062a \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0644\\u062e\\u062f\\u0645\\u062a\\u0643 \\u0628\\u0634\\u0643\\u0644 \\u0623\\u0641\\u0636\\u0644.<\\/p><p>**3. \\u0623\\u0645\\u0646 \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a:**<br>\\u0625\\u0646 \\u062d\\u0645\\u0627\\u064a\\u0629 \\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a\\u0643 \\u062a\\u0634\\u0643\\u0644 \\u0623\\u0648\\u0644\\u0648\\u064a\\u0629 \\u0642\\u0635\\u0648\\u0649 \\u0628\\u0627\\u0644\\u0646\\u0633\\u0628\\u0629 \\u0644\\u0646\\u0627. \\u0648\\u0646\\u062d\\u0646 \\u0646\\u0646\\u0641\\u0630 \\u0627\\u0644\\u062a\\u062f\\u0627\\u0628\\u064a\\u0631 \\u0627\\u0644\\u0641\\u0646\\u064a\\u0629 \\u0648\\u0627\\u0644\\u062a\\u0646\\u0638\\u064a\\u0645\\u064a\\u0629 \\u0644\\u0636\\u0645\\u0627\\u0646 \\u0623\\u0645\\u0627\\u0646 \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643 \\u0645\\u0646 \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644 \\u063a\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0635\\u0631\\u062d \\u0628\\u0647 \\u0623\\u0648 \\u0627\\u0644\\u0641\\u0642\\u062f\\u0627\\u0646 \\u0623\\u0648 \\u0633\\u0648\\u0621 \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645.<\\/p><p>**4. \\u0627\\u0644\\u062c\\u0647\\u0627\\u062a \\u0627\\u0644\\u062e\\u0627\\u0631\\u062c\\u064a\\u0629:**<br>\\u0642\\u062f \\u0646\\u0634\\u0627\\u0631\\u0643 \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643 \\u0645\\u0639 \\u0634\\u0631\\u0643\\u0627\\u0621 \\u0645\\u0648\\u062b\\u0648\\u0642 \\u0628\\u0647\\u0645 \\u0645\\u0646 \\u062c\\u0647\\u0627\\u062a \\u062e\\u0627\\u0631\\u062c\\u064a\\u0629 \\u064a\\u0633\\u0627\\u0639\\u062f\\u0648\\u0646\\u0646\\u0627 \\u0641\\u064a \\u062a\\u0642\\u062f\\u064a\\u0645 \\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627\\u060c \\u0645\\u062b\\u0644 \\u0645\\u0639\\u0627\\u0644\\u062c\\u0627\\u062a \\u0627\\u0644\\u062f\\u0641\\u0639 \\u0648\\u0645\\u0642\\u062f\\u0645\\u064a \\u0627\\u0644\\u062a\\u062d\\u0644\\u064a\\u0644\\u0627\\u062a. \\u0648\\u064a\\u0644\\u062a\\u0632\\u0645 \\u0647\\u0624\\u0644\\u0627\\u0621 \\u0627\\u0644\\u0634\\u0631\\u0643\\u0627\\u0621 \\u0628\\u0645\\u0648\\u062c\\u0628 \\u0627\\u0644\\u0639\\u0642\\u062f \\u0628\\u0627\\u0644\\u062a\\u0639\\u0627\\u0645\\u0644 \\u0645\\u0639 \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643 \\u0628\\u0623\\u0645\\u0627\\u0646 \\u0648\\u0644\\u0644\\u0623\\u063a\\u0631\\u0627\\u0636 \\u0627\\u0644\\u0645\\u062d\\u062f\\u062f\\u0629 \\u0641\\u0642\\u0637.<\\/p><p>**5. \\u0645\\u0644\\u0641\\u0627\\u062a \\u062a\\u0639\\u0631\\u064a\\u0641 \\u0627\\u0644\\u0627\\u0631\\u062a\\u0628\\u0627\\u0637 \\u0648\\u0627\\u0644\\u062a\\u062a\\u0628\\u0639:**<br>\\u0646\\u062d\\u0646 \\u0646\\u0633\\u062a\\u062e\\u062f\\u0645 \\u0645\\u0644\\u0641\\u0627\\u062a \\u062a\\u0639\\u0631\\u064a\\u0641 \\u0627\\u0644\\u0627\\u0631\\u062a\\u0628\\u0627\\u0637 \\u0648\\u062a\\u0642\\u0646\\u064a\\u0627\\u062a \\u0645\\u0645\\u0627\\u062b\\u0644\\u0629 \\u0644\\u062a\\u062d\\u0633\\u064a\\u0646 \\u062a\\u062c\\u0631\\u0628\\u062a\\u0643 \\u0639\\u0644\\u0649 \\u0645\\u0648\\u0642\\u0639\\u0646\\u0627 \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a. \\u0645\\u0644\\u0641\\u0627\\u062a \\u062a\\u0639\\u0631\\u064a\\u0641 \\u0627\\u0644\\u0627\\u0631\\u062a\\u0628\\u0627\\u0637 \\u0647\\u064a \\u0645\\u0644\\u0641\\u0627\\u062a \\u0646\\u0635\\u064a\\u0629 \\u0635\\u063a\\u064a\\u0631\\u0629 \\u064a\\u062a\\u0645 \\u062a\\u062e\\u0632\\u064a\\u0646\\u0647\\u0627 \\u0639\\u0644\\u0649 \\u062c\\u0647\\u0627\\u0632\\u0643 \\u0639\\u0646\\u062f \\u0632\\u064a\\u0627\\u0631\\u0629 \\u0645\\u0648\\u0642\\u0639\\u0646\\u0627. \\u0648\\u0647\\u064a \\u062a\\u0633\\u0627\\u0639\\u062f\\u0646\\u0627 \\u0641\\u064a \\u062a\\u0630\\u0643\\u0631 \\u062a\\u0641\\u0636\\u064a\\u0644\\u0627\\u062a\\u0643 \\u0648\\u062a\\u062d\\u0644\\u064a\\u0644 \\u0623\\u0646\\u0645\\u0627\\u0637 \\u0627\\u0644\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0648\\u062a\\u062e\\u0635\\u064a\\u0635 \\u0627\\u0644\\u0645\\u062d\\u062a\\u0648\\u0649 \\u0648\\u0641\\u0642\\u064b\\u0627 \\u0644\\u0627\\u0647\\u062a\\u0645\\u0627\\u0645\\u0627\\u062a\\u0643.<\\/p><p>**6. \\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644\\u0627\\u062a:**<br>\\u0642\\u062f \\u0646\\u0631\\u0633\\u0644 \\u0625\\u0644\\u064a\\u0643 \\u0631\\u0633\\u0627\\u0626\\u0644 \\u0628\\u0631\\u064a\\u062f \\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a \\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a\\u064a\\u0629 \\u062a\\u062a\\u0639\\u0644\\u0642 \\u0628\\u062d\\u0633\\u0627\\u0628\\u0643 \\u0648\\u062a\\u062d\\u062f\\u064a\\u062b\\u0627\\u062a \\u062d\\u0648\\u0644 \\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627 \\u0648\\u0639\\u0631\\u0648\\u0636 \\u062a\\u0631\\u0648\\u064a\\u062c\\u064a\\u0629. \\u064a\\u0645\\u0643\\u0646\\u0643 \\u0625\\u0644\\u063a\\u0627\\u0621 \\u0627\\u0644\\u0627\\u0634\\u062a\\u0631\\u0627\\u0643 \\u0641\\u064a \\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644\\u0627\\u062a \\u0627\\u0644\\u062a\\u0633\\u0648\\u064a\\u0642\\u064a\\u0629 \\u0641\\u064a \\u0623\\u064a \\u0648\\u0642\\u062a \\u0639\\u0646 \\u0637\\u0631\\u064a\\u0642 \\u062a\\u0639\\u062f\\u064a\\u0644 \\u062a\\u0641\\u0636\\u064a\\u0644\\u0627\\u062a\\u0643 \\u0641\\u064a \\u0625\\u0639\\u062f\\u0627\\u062f\\u0627\\u062a \\u062d\\u0633\\u0627\\u0628\\u0643.<\\/p><p>**7. \\u0627\\u0644\\u0627\\u062d\\u062a\\u0641\\u0627\\u0638 \\u0628\\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a:**<br>\\u0646\\u062d\\u062a\\u0641\\u0638 \\u0628\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643 \\u0637\\u0627\\u0644\\u0645\\u0627 \\u0643\\u0627\\u0646 \\u0630\\u0644\\u0643 \\u0636\\u0631\\u0648\\u0631\\u064a\\u064b\\u0627 \\u0644\\u062a\\u0642\\u062f\\u064a\\u0645 \\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627 \\u0623\\u0648 \\u0627\\u0644\\u0627\\u0645\\u062a\\u062b\\u0627\\u0644 \\u0644\\u0644\\u0627\\u0644\\u062a\\u0632\\u0627\\u0645\\u0627\\u062a \\u0627\\u0644\\u0642\\u0627\\u0646\\u0648\\u0646\\u064a\\u0629. \\u0625\\u0630\\u0627 \\u0643\\u0646\\u062a \\u062a\\u0631\\u063a\\u0628 \\u0641\\u064a \\u062d\\u0630\\u0641 \\u062d\\u0633\\u0627\\u0628\\u0643\\u060c \\u064a\\u0631\\u062c\\u0649 \\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644 \\u0628\\u0641\\u0631\\u064a\\u0642 \\u0627\\u0644\\u062f\\u0639\\u0645 \\u0644\\u062f\\u064a\\u0646\\u0627.<\\/p><p>**8. \\u0627\\u0644\\u062a\\u063a\\u064a\\u064a\\u0631\\u0627\\u062a \\u0639\\u0644\\u0649 \\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u062e\\u0635\\u0648\\u0635\\u064a\\u0629:**<br>\\u0642\\u062f \\u0646\\u0642\\u0648\\u0645 \\u0628\\u062a\\u062d\\u062f\\u064a\\u062b \\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u062e\\u0635\\u0648\\u0635\\u064a\\u0629 \\u0647\\u0630\\u0647 \\u0644\\u062a\\u0639\\u0643\\u0633 \\u0627\\u0644\\u062a\\u063a\\u064a\\u064a\\u0631\\u0627\\u062a \\u0641\\u064a \\u0645\\u0645\\u0627\\u0631\\u0633\\u0627\\u062a\\u0646\\u0627 \\u0623\\u0648 \\u0627\\u0644\\u0645\\u062a\\u0637\\u0644\\u0628\\u0627\\u062a \\u0627\\u0644\\u0642\\u0627\\u0646\\u0648\\u0646\\u064a\\u0629. \\u0633\\u064a\\u062a\\u0645 \\u0646\\u0634\\u0631 \\u0623\\u064a \\u062a\\u0639\\u062f\\u064a\\u0644\\u0627\\u062a \\u0639\\u0644\\u0649 \\u0645\\u0648\\u0642\\u0639\\u0646\\u0627 \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\\u060c \\u0648\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645\\u0643 \\u0627\\u0644\\u0645\\u0633\\u062a\\u0645\\u0631 \\u0644\\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627 \\u0628\\u0639\\u062f \\u0647\\u0630\\u0647 \\u0627\\u0644\\u062a\\u063a\\u064a\\u064a\\u0631\\u0627\\u062a \\u064a\\u0639\\u0646\\u064a \\u0642\\u0628\\u0648\\u0644\\u0643 \\u0644\\u0644\\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u0645\\u062d\\u062f\\u062b\\u0629. \\u062e\\u0635\\u0648\\u0635\\u064a\\u062a\\u0643 \\u0645\\u0647\\u0645\\u0629 \\u0628\\u0627\\u0644\\u0646\\u0633\\u0628\\u0629 \\u0644\\u0646\\u0627. \\u0625\\u0630\\u0627 \\u0643\\u0627\\u0646\\u062a \\u0644\\u062f\\u064a\\u0643 \\u0623\\u064a \\u0623\\u0633\\u0626\\u0644\\u0629 \\u062d\\u0648\\u0644 \\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u062e\\u0635\\u0648\\u0635\\u064a\\u0629 \\u0627\\u0644\\u062e\\u0627\\u0635\\u0629 \\u0628\\u0646\\u0627 \\u0623\\u0648 \\u0643\\u064a\\u0641\\u064a\\u0629 \\u062a\\u0639\\u0627\\u0645\\u0644\\u0646\\u0627 \\u0645\\u0639 \\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\\u0643\\u060c \\u064a\\u0631\\u062c\\u0649 \\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644 \\u0628\\u0646\\u0627 \\u0639\\u0644\\u0649 [\\u0627\\u0644\\u0628\\u0631\\u064a\\u062f \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a \\u0627\\u0644\\u062e\\u0627\\u0635 \\u0628\\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644]<\\/p>\"}}}', 1, 0, '2023-07-31 16:02:44', '2024-12-07 16:08:11'),
(2, 'UNKNOWN', '{\"language\":{\"en\":{\"title\":\"Refund Policy\"},\"es\":{\"title\":\"Pol\\u00edtica de reembolso\"},\"ar\":{\"title\":\"\\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u0631\\u062c\\u0627\\u0639\"}}}', 'refund-policy', 'refund-policy', '{\"language\":{\"en\":{\"content\":\"<p>At iBanking, we strive to provide a transparent and satisfactory experience to all our users. This Refund Policy outlines the circumstances under which refunds may be granted for our services. By using iBanking, you agree to the terms described in this policy.<\\/p><p>**1. Eligibility for Refunds:**<br>Refunds may be considered under the following conditions:<\\/p><p>- Technical Issues: If you experience technical difficulties that prevent you from using our platform as intended, you may be eligible for a refund.<\\/p><p>- Failed Transactions: In the event of a failed transaction due to system errors or other issues, a refund may be granted.<\\/p><p>- Dissatisfaction: If you are dissatisfied with our services and wish to terminate your account within a specified timeframe, a refund may be evaluated on a case-by-case basis.<\\/p><p>**2. Refund Process:**<br>To request a refund, please follow these steps:<\\/p><p>- Contact Support: Reach out to our customer support team at [support email] within [number of days] days of the issue.<\\/p><p>- Provide Details: Explain the reason for your refund request, providing relevant details such as transaction ID, account information, and a clear description of the issue.<\\/p><p>- Evaluation: Our team will review your request and assess your eligibility for a refund based on the outlined criteria.<\\/p><p>- Refund Approval: If your refund request is approved, we will initiate the refund process within [number of days] days.<\\/p><p>**3. Non-Refundable Circumstances:**<br>Please note that refunds will not be granted in the following cases:<\\/p><p>- Change of Mind: Refunds will not be provided for services used or purchased based on a change of mind.<\\/p><p>- Investment Outcomes: Cryptocurrency investments carry inherent risks, and we do not provide refunds based on the performance of your investments.<\\/p><p>- Violation of Terms: If your account has been found in violation of our Terms and Conditions, you will not be eligible for a refund.<\\/p><p>**4. Contact Us:**<br>If you have any questions or concerns about our refund policy, please contact us at [contact email]. We are here to assist you and ensure a fair resolution to any issues you may encounter.<\\/p><p>**5. Policy Updates:**<br>We may update this Refund Policy from time to time to reflect changes in our practices or legal requirements. Any revisions will be posted on our website, and your continued use of our services after such changes signifies your acceptance of the updated policy.<\\/p><p>Please carefully review this Refund Policy before using our services. If you have any queries or require further clarification, do not hesitate to contact us.<\\/p>\"},\"es\":{\"content\":\"<p>En iBanking, nos esforzamos por brindar una experiencia transparente y satisfactoria a todos nuestros usuarios. Esta Pol\\u00edtica de reembolso describe las circunstancias en las que se pueden otorgar reembolsos por nuestros servicios. Al utilizar iBanking, usted acepta los t\\u00e9rminos descritos en esta pol\\u00edtica.<\\/p><p>**1. Elegibilidad para reembolsos:**<br>Los reembolsos pueden considerarse en las siguientes condiciones:<\\/p><p>- Problemas t\\u00e9cnicos: si experimenta dificultades t\\u00e9cnicas que le impidan usar nuestra plataforma como se esperaba, puede ser elegible para un reembolso.<\\/p><p>- Transacciones fallidas: en caso de que una transacci\\u00f3n falle debido a errores del sistema u otros problemas, se puede otorgar un reembolso.<\\/p><p>- Insatisfacci\\u00f3n: si no est\\u00e1 satisfecho con nuestros servicios y desea cancelar su cuenta dentro de un plazo espec\\u00edfico, se puede evaluar un reembolso caso por caso.<\\/p><p>**2. Proceso de reembolso:**<br>Para solicitar un reembolso, siga estos pasos:<\\/p><p>- P\\u00f3ngase en contacto con el servicio de asistencia: comun\\u00edquese con nuestro equipo de atenci\\u00f3n al cliente a [correo electr\\u00f3nico de asistencia] dentro de los [n\\u00famero de d\\u00edas] d\\u00edas posteriores al problema.<\\/p><p>- Proporcione detalles: explique el motivo de su solicitud de reembolso y proporcione detalles relevantes como el ID de la transacci\\u00f3n, la informaci\\u00f3n de la cuenta y una descripci\\u00f3n clara del problema.<\\/p><p>- Evaluaci\\u00f3n: nuestro equipo revisar\\u00e1 su solicitud y evaluar\\u00e1 su elegibilidad para un reembolso seg\\u00fan los criterios descritos.<\\/p><p>- Aprobaci\\u00f3n del reembolso: si se aprueba su solicitud de reembolso, iniciaremos el proceso de reembolso dentro de [n\\u00famero de d\\u00edas] d\\u00edas.<\\/p><p>**3. Circunstancias no reembolsables:**<br>Tenga en cuenta que no se otorgar\\u00e1n reembolsos en los siguientes casos:<\\/p><p>- Cambio de opini\\u00f3n: no se otorgar\\u00e1n reembolsos por servicios utilizados o comprados en funci\\u00f3n de un cambio de opini\\u00f3n.<\\/p><p>- Resultados de la inversi\\u00f3n: las inversiones en criptomonedas conllevan riesgos inherentes y no brindamos reembolsos en funci\\u00f3n del rendimiento de sus inversiones.<\\/p><p>- Incumplimiento de los t\\u00e9rminos: si se descubre que su cuenta infringe nuestros t\\u00e9rminos y condiciones, no ser\\u00e1 elegible para un reembolso.<\\/p><p>**4. Comun\\u00edquese con nosotros:**<br>Si tiene alguna pregunta o inquietud sobre nuestra pol\\u00edtica de reembolso, comun\\u00edquese con nosotros a [correo electr\\u00f3nico de contacto]. Estamos aqu\\u00ed para ayudarlo y garantizar una resoluci\\u00f3n justa de cualquier problema que pueda tener.<\\/p><p>**5. Actualizaciones de la pol\\u00edtica:**<br>Podemos actualizar esta Pol\\u00edtica de reembolso de vez en cuando para reflejar cambios en nuestras pr\\u00e1cticas o requisitos legales. Cualquier revisi\\u00f3n se publicar\\u00e1 en nuestro sitio web y su uso continuo de nuestros servicios despu\\u00e9s de dichos cambios significa que acepta la pol\\u00edtica actualizada.<\\/p><p>Lea atentamente esta Pol\\u00edtica de reembolso antes de utilizar nuestros servicios. Si tiene alguna pregunta o necesita m\\u00e1s aclaraciones, no dude en comunicarse con nosotros.<\\/p>\"},\"ar\":{\"content\":\"<p>\\u0641\\u064a iBanking\\u060c \\u0646\\u0633\\u0639\\u0649 \\u062c\\u0627\\u0647\\u062f\\u064a\\u0646 \\u0644\\u062a\\u0648\\u0641\\u064a\\u0631 \\u062a\\u062c\\u0631\\u0628\\u0629 \\u0634\\u0641\\u0627\\u0641\\u0629 \\u0648\\u0645\\u0631\\u0636\\u064a\\u0629 \\u0644\\u062c\\u0645\\u064a\\u0639 \\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646\\u0627. \\u062a\\u062d\\u062f\\u062f \\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0647\\u0630\\u0647 \\u0627\\u0644\\u0638\\u0631\\u0648\\u0641 \\u0627\\u0644\\u062a\\u064a \\u064a\\u062c\\u0648\\u0632 \\u0628\\u0645\\u0648\\u062c\\u0628\\u0647\\u0627 \\u0645\\u0646\\u062d \\u0627\\u0644\\u0645\\u0628\\u0627\\u0644\\u063a \\u0627\\u0644\\u0645\\u0633\\u062a\\u0631\\u062f\\u0629 \\u0645\\u0642\\u0627\\u0628\\u0644 \\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627. \\u0628\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 iBanking\\u060c \\u0641\\u0625\\u0646\\u0643 \\u062a\\u0648\\u0627\\u0641\\u0642 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0634\\u0631\\u0648\\u0637 \\u0627\\u0644\\u0645\\u0648\\u0636\\u062d\\u0629 \\u0641\\u064a \\u0647\\u0630\\u0647 \\u0627\\u0644\\u0633\\u064a\\u0627\\u0633\\u0629.<\\/p><p>**1. \\u0623\\u0647\\u0644\\u064a\\u0629 \\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644:**<br>\\u064a\\u0645\\u0643\\u0646 \\u0627\\u0644\\u0646\\u0638\\u0631 \\u0641\\u064a \\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644 \\u0641\\u064a \\u0638\\u0644 \\u0627\\u0644\\u0638\\u0631\\u0648\\u0641 \\u0627\\u0644\\u062a\\u0627\\u0644\\u064a\\u0629:<\\/p><p>- \\u0627\\u0644\\u0645\\u0634\\u0643\\u0644\\u0627\\u062a \\u0627\\u0644\\u0641\\u0646\\u064a\\u0629: \\u0625\\u0630\\u0627 \\u0648\\u0627\\u062c\\u0647\\u062a \\u0635\\u0639\\u0648\\u0628\\u0627\\u062a \\u0641\\u0646\\u064a\\u0629 \\u062a\\u0645\\u0646\\u0639\\u0643 \\u0645\\u0646 \\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0645\\u0646\\u0635\\u062a\\u0646\\u0627 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0646\\u062d\\u0648 \\u0627\\u0644\\u0645\\u0642\\u0635\\u0648\\u062f\\u060c \\u0641\\u0642\\u062f \\u062a\\u0643\\u0648\\u0646 \\u0645\\u0624\\u0647\\u0644\\u0627\\u064b \\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644.<\\/p><p>- \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0641\\u0627\\u0634\\u0644\\u0629: \\u0641\\u064a \\u062d\\u0627\\u0644\\u0629 \\u0641\\u0634\\u0644 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0629 \\u0628\\u0633\\u0628\\u0628 \\u0623\\u062e\\u0637\\u0627\\u0621 \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645 \\u0623\\u0648 \\u0645\\u0634\\u0643\\u0644\\u0627\\u062a \\u0623\\u062e\\u0631\\u0649\\u060c \\u064a\\u062c\\u0648\\u0632 \\u0645\\u0646\\u062d \\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644.<\\/p><p>- \\u0639\\u062f\\u0645 \\u0627\\u0644\\u0631\\u0636\\u0627: \\u0625\\u0630\\u0627 \\u0643\\u0646\\u062a \\u063a\\u064a\\u0631 \\u0631\\u0627\\u0636\\u064d \\u0639\\u0646 \\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627 \\u0648\\u062a\\u0631\\u063a\\u0628 \\u0641\\u064a \\u0625\\u0646\\u0647\\u0627\\u0621 \\u062d\\u0633\\u0627\\u0628\\u0643 \\u0641\\u064a \\u063a\\u0636\\u0648\\u0646 \\u0625\\u0637\\u0627\\u0631 \\u0632\\u0645\\u0646\\u064a \\u0645\\u062d\\u062f\\u062f\\u060c \\u0641\\u0642\\u062f \\u064a\\u062a\\u0645 \\u062a\\u0642\\u064a\\u064a\\u0645 \\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644 \\u0639\\u0644\\u0649 \\u0623\\u0633\\u0627\\u0633 \\u0643\\u0644 \\u062d\\u0627\\u0644\\u0629 \\u0639\\u0644\\u0649 \\u062d\\u062f\\u0629.<\\/p><p>**2. \\u0639\\u0645\\u0644\\u064a\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f:**<br>\\u0644\\u0637\\u0644\\u0628 \\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644\\u060c \\u064a\\u0631\\u062c\\u0649 \\u0627\\u062a\\u0628\\u0627\\u0639 \\u0627\\u0644\\u062e\\u0637\\u0648\\u0627\\u062a \\u0627\\u0644\\u062a\\u0627\\u0644\\u064a\\u0629:<\\/p><p>- \\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644 \\u0628\\u0627\\u0644\\u062f\\u0639\\u0645: \\u062a\\u0648\\u0627\\u0635\\u0644 \\u0645\\u0639 \\u0641\\u0631\\u064a\\u0642 \\u062f\\u0639\\u0645 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621 \\u0644\\u062f\\u064a\\u0646\\u0627 \\u0639\\u0644\\u0649 [\\u0628\\u0631\\u064a\\u062f \\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a \\u0644\\u0644\\u062f\\u0639\\u0645] \\u0641\\u064a \\u063a\\u0636\\u0648\\u0646 [\\u0639\\u062f\\u062f \\u0627\\u0644\\u0623\\u064a\\u0627\\u0645] \\u064a\\u0648\\u0645\\u064b\\u0627 \\u0645\\u0646 \\u0627\\u0644\\u0645\\u0634\\u0643\\u0644\\u0629.<\\/p><p>- \\u062a\\u0642\\u062f\\u064a\\u0645 \\u0627\\u0644\\u062a\\u0641\\u0627\\u0635\\u064a\\u0644: \\u0627\\u0634\\u0631\\u062d \\u0633\\u0628\\u0628 \\u0637\\u0644\\u0628 \\u0627\\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f\\u060c \\u0645\\u0639 \\u062a\\u0642\\u062f\\u064a\\u0645 \\u0627\\u0644\\u062a\\u0641\\u0627\\u0635\\u064a\\u0644 \\u0630\\u0627\\u062a \\u0627\\u0644\\u0635\\u0644\\u0629 \\u0645\\u062b\\u0644 \\u0645\\u0639\\u0631\\u0641 \\u0627\\u0644\\u0645\\u0639\\u0627\\u0645\\u0644\\u0629 \\u0648\\u0645\\u0639\\u0644\\u0648\\u0645\\u0627\\u062a \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628 \\u0648\\u0648\\u0635\\u0641 \\u0648\\u0627\\u0636\\u062d \\u0644\\u0644\\u0645\\u0634\\u0643\\u0644\\u0629.<\\/p><p>- \\u0627\\u0644\\u062a\\u0642\\u064a\\u064a\\u0645: \\u0633\\u064a\\u0631\\u0627\\u062c\\u0639 \\u0641\\u0631\\u064a\\u0642\\u0646\\u0627 \\u0637\\u0644\\u0628\\u0643 \\u0648\\u064a\\u0642\\u064a\\u0645 \\u0623\\u0647\\u0644\\u064a\\u062a\\u0643 \\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644 \\u0628\\u0646\\u0627\\u0621\\u064b \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0645\\u0639\\u0627\\u064a\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0648\\u0636\\u062d\\u0629.<\\/p><p>- \\u0645\\u0648\\u0627\\u0641\\u0642\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f: \\u0625\\u0630\\u0627 \\u062a\\u0645\\u062a \\u0627\\u0644\\u0645\\u0648\\u0627\\u0641\\u0642\\u0629 \\u0639\\u0644\\u0649 \\u0637\\u0644\\u0628 \\u0627\\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0627\\u0644\\u062e\\u0627\\u0635 \\u0628\\u0643\\u060c \\u0641\\u0633\\u0646\\u0628\\u062f\\u0623 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0641\\u064a \\u063a\\u0636\\u0648\\u0646 [\\u0639\\u062f\\u062f \\u0627\\u0644\\u0623\\u064a\\u0627\\u0645] \\u064a\\u0648\\u0645\\u064b\\u0627.<\\/p><p>**3. \\u0627\\u0644\\u0638\\u0631\\u0648\\u0641 \\u063a\\u064a\\u0631 \\u0627\\u0644\\u0642\\u0627\\u0628\\u0644\\u0629 \\u0644\\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f:**<br>\\u064a\\u0631\\u062c\\u0649 \\u0645\\u0644\\u0627\\u062d\\u0638\\u0629 \\u0623\\u0646\\u0647 \\u0644\\u0646 \\u064a\\u062a\\u0645 \\u0645\\u0646\\u062d \\u0627\\u0644\\u0645\\u0628\\u0627\\u0644\\u063a \\u0627\\u0644\\u0645\\u0633\\u062a\\u0631\\u062f\\u0629 \\u0641\\u064a \\u0627\\u0644\\u062d\\u0627\\u0644\\u0627\\u062a \\u0627\\u0644\\u062a\\u0627\\u0644\\u064a\\u0629:<\\/p><p>- \\u062a\\u063a\\u064a\\u064a\\u0631 \\u0627\\u0644\\u0631\\u0623\\u064a: \\u0644\\u0646 \\u064a\\u062a\\u0645 \\u062a\\u0642\\u062f\\u064a\\u0645 \\u0627\\u0644\\u0645\\u0628\\u0627\\u0644\\u063a \\u0627\\u0644\\u0645\\u0633\\u062a\\u0631\\u062f\\u0629 \\u0644\\u0644\\u062e\\u062f\\u0645\\u0627\\u062a \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u0629 \\u0623\\u0648 \\u0627\\u0644\\u0645\\u0634\\u062a\\u0631\\u0627\\u0629 \\u0628\\u0646\\u0627\\u0621\\u064b \\u0639\\u0644\\u0649 \\u062a\\u063a\\u064a\\u064a\\u0631 \\u0627\\u0644\\u0631\\u0623\\u064a.<\\/p><p>- \\u0646\\u062a\\u0627\\u0626\\u062c \\u0627\\u0644\\u0627\\u0633\\u062a\\u062b\\u0645\\u0627\\u0631: \\u062a\\u0646\\u0637\\u0648\\u064a \\u0627\\u0633\\u062a\\u062b\\u0645\\u0627\\u0631\\u0627\\u062a \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0645\\u0634\\u0641\\u0631\\u0629 \\u0639\\u0644\\u0649 \\u0645\\u062e\\u0627\\u0637\\u0631 \\u0645\\u062a\\u0623\\u0635\\u0644\\u0629\\u060c \\u0648\\u0644\\u0627 \\u0646\\u0642\\u062f\\u0645 \\u0627\\u0644\\u0645\\u0628\\u0627\\u0644\\u063a \\u0627\\u0644\\u0645\\u0633\\u062a\\u0631\\u062f\\u0629 \\u0628\\u0646\\u0627\\u0621\\u064b \\u0639\\u0644\\u0649 \\u0623\\u062f\\u0627\\u0621 \\u0627\\u0633\\u062a\\u062b\\u0645\\u0627\\u0631\\u0627\\u062a\\u0643.<\\/p><p>- \\u0627\\u0646\\u062a\\u0647\\u0627\\u0643 \\u0627\\u0644\\u0634\\u0631\\u0648\\u0637: \\u0625\\u0630\\u0627 \\u062a\\u0628\\u064a\\u0646 \\u0623\\u0646 \\u062d\\u0633\\u0627\\u0628\\u0643 \\u064a\\u0646\\u062a\\u0647\\u0643 \\u0634\\u0631\\u0648\\u0637\\u0646\\u0627 \\u0648\\u0623\\u062d\\u0643\\u0627\\u0645\\u0646\\u0627\\u060c \\u0641\\u0644\\u0646 \\u062a\\u0643\\u0648\\u0646 \\u0645\\u0624\\u0647\\u0644\\u0627\\u064b \\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644.<\\/p><p>**4. \\u0627\\u062a\\u0635\\u0644 \\u0628\\u0646\\u0627:**<br>\\u0625\\u0630\\u0627 \\u0643\\u0627\\u0646\\u062a \\u0644\\u062f\\u064a\\u0643 \\u0623\\u064a \\u0623\\u0633\\u0626\\u0644\\u0629 \\u0623\\u0648 \\u0645\\u062e\\u0627\\u0648\\u0641 \\u0628\\u0634\\u0623\\u0646 \\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0627\\u0644\\u062e\\u0627\\u0635\\u0629 \\u0628\\u0646\\u0627\\u060c \\u064a\\u0631\\u062c\\u0649 \\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644 \\u0628\\u0646\\u0627 \\u0639\\u0644\\u0649 [\\u0627\\u0644\\u0628\\u0631\\u064a\\u062f \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a \\u0627\\u0644\\u062e\\u0627\\u0635 \\u0628\\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644]. \\u0646\\u062d\\u0646 \\u0647\\u0646\\u0627 \\u0644\\u0645\\u0633\\u0627\\u0639\\u062f\\u062a\\u0643 \\u0648\\u0636\\u0645\\u0627\\u0646 \\u062d\\u0644 \\u0639\\u0627\\u062f\\u0644 \\u0644\\u0623\\u064a \\u0645\\u0634\\u0643\\u0644\\u0627\\u062a \\u0642\\u062f \\u062a\\u0648\\u0627\\u062c\\u0647\\u0647\\u0627.<\\/p><p>**5. \\u062a\\u062d\\u062f\\u064a\\u062b\\u0627\\u062a \\u0627\\u0644\\u0633\\u064a\\u0627\\u0633\\u0629:**<br>\\u0642\\u062f \\u0646\\u0642\\u0648\\u0645 \\u0628\\u062a\\u062d\\u062f\\u064a\\u062b \\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0647\\u0630\\u0647 \\u0645\\u0646 \\u0648\\u0642\\u062a \\u0644\\u0622\\u062e\\u0631 \\u0644\\u062a\\u0639\\u0643\\u0633 \\u0627\\u0644\\u062a\\u063a\\u064a\\u064a\\u0631\\u0627\\u062a \\u0641\\u064a \\u0645\\u0645\\u0627\\u0631\\u0633\\u0627\\u062a\\u0646\\u0627 \\u0623\\u0648 \\u0627\\u0644\\u0645\\u062a\\u0637\\u0644\\u0628\\u0627\\u062a \\u0627\\u0644\\u0642\\u0627\\u0646\\u0648\\u0646\\u064a\\u0629. \\u0633\\u064a\\u062a\\u0645 \\u0646\\u0634\\u0631 \\u0623\\u064a \\u062a\\u0639\\u062f\\u064a\\u0644\\u0627\\u062a \\u0639\\u0644\\u0649 \\u0645\\u0648\\u0642\\u0639\\u0646\\u0627 \\u0627\\u0644\\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\\u060c \\u0648\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645\\u0643 \\u0627\\u0644\\u0645\\u0633\\u062a\\u0645\\u0631 \\u0644\\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627 \\u0628\\u0639\\u062f \\u0647\\u0630\\u0647 \\u0627\\u0644\\u062a\\u063a\\u064a\\u064a\\u0631\\u0627\\u062a \\u064a\\u062f\\u0644 \\u0639\\u0644\\u0649 \\u0642\\u0628\\u0648\\u0644\\u0643 \\u0644\\u0644\\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u0645\\u062d\\u062f\\u062b\\u0629.<\\/p><p>\\u064a\\u0631\\u062c\\u0649 \\u0645\\u0631\\u0627\\u062c\\u0639\\u0629 \\u0633\\u064a\\u0627\\u0633\\u0629 \\u0627\\u0644\\u0627\\u0633\\u062a\\u0631\\u062f\\u0627\\u062f \\u0647\\u0630\\u0647 \\u0628\\u0639\\u0646\\u0627\\u064a\\u0629 \\u0642\\u0628\\u0644 \\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u062e\\u062f\\u0645\\u0627\\u062a\\u0646\\u0627. \\u0625\\u0630\\u0627 \\u0643\\u0627\\u0646\\u062a \\u0644\\u062f\\u064a\\u0643 \\u0623\\u064a \\u0627\\u0633\\u062a\\u0641\\u0633\\u0627\\u0631\\u0627\\u062a \\u0623\\u0648 \\u0643\\u0646\\u062a \\u0628\\u062d\\u0627\\u062c\\u0629 \\u0625\\u0644\\u0649 \\u0645\\u0632\\u064a\\u062f \\u0645\\u0646 \\u0627\\u0644\\u062a\\u0648\\u0636\\u064a\\u062d\\u060c \\u0641\\u0644\\u0627 \\u062a\\u062a\\u0631\\u062f\\u062f \\u0641\\u064a \\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644 \\u0628\\u0646\\u0627.<\\/p>\"}}}', 1, 1, '2023-08-11 00:19:01', '2024-12-07 16:09:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(191) NOT NULL,
  `lastname` varchar(191) DEFAULT NULL,
  `username` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `mobile_code` varchar(191) DEFAULT NULL,
  `mobile` varchar(191) DEFAULT NULL,
  `full_mobile` varchar(191) DEFAULT NULL,
  `account_no` varchar(191) DEFAULT NULL,
  `pin_status` tinyint(1) NOT NULL DEFAULT 0,
  `pin_code` varchar(191) DEFAULT NULL,
  `account_type` varchar(191) DEFAULT 'personal',
  `company_name` varchar(191) DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `birthdate` timestamp NULL DEFAULT NULL,
  `gender` varchar(191) DEFAULT NULL,
  `referral_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Active, 0 == Banned',
  `address` text DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 == Verifiend, 0 == Not verifiend',
  `sms_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 == Verifiend, 0 == Not verifiend',
  `kyc_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Default, 1: Approved, 2: Pending, 3:Rejected',
  `ver_code` int(11) DEFAULT NULL,
  `ver_code_send_at` timestamp NULL DEFAULT NULL,
  `two_factor_verified` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_status` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_secret` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `strowallet_customer` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `mobile_code`, `mobile`, `full_mobile`, `account_no`, `pin_status`, `pin_code`, `account_type`, `company_name`, `password`, `birthdate`, `gender`, `referral_id`, `image`, `status`, `address`, `email_verified`, `sms_verified`, `kyc_verified`, `ver_code`, `ver_code_send_at`, `two_factor_verified`, `two_factor_status`, `two_factor_secret`, `email_verified_at`, `strowallet_customer`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Chisom', 'Ihekechukwu', 'chisom', 'superadmin@appdevs.net', '234', '', '234', '72423461810798', 1, '1357', 'personal', '', '$2y$10$QL6CyXdEJCG74X6yJ9AwY.inss9hNJgNPmX.loIiPIymF5AKuoUxy', NULL, NULL, NULL, NULL, 1, '{\"country\":\"Nigeria\",\"state\":\"Abia\",\"city\":\"ABUJA\",\"zip\":\"431102\",\"address\":\"F14 KUBWA\"}', 1, 1, 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, '2026-03-10 04:25:09', '2026-03-10 04:45:48'),
(2, 'Gideon', 'Arinze', 'gideon', 'maduekegideon46@gmail.com', '234', '09122646692', '23409122646692', '75433837622217', 1, '1357', 'personal', '', '$2y$10$lfQWsyRiugF0PBPaRADr7.mgr9QClyzETlGIwkG01XgIz/fQL3DNu', NULL, NULL, NULL, '80ea2d2f-bdd1-4dbd-9b23-b93e401984cf.webp', 1, '{\"country\":\"Nigeria\",\"state\":\"Abia\",\"city\":\"ABUJA\",\"zip\":\"431102\",\"address\":\"F14 KUBWA\"}', 1, 1, 1, NULL, NULL, 0, 0, 'EOPHRS2Y5ZDUYZSP', NULL, NULL, NULL, NULL, '2026-03-10 07:21:34', '2026-03-13 09:33:37'),
(3, 'John', 'Doe', 'johndoe', 'john@example.com', NULL, NULL, NULL, NULL, 0, NULL, 'personal', NULL, 'password123', NULL, NULL, NULL, NULL, 1, NULL, 0, 0, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-03-13 04:00:34', '2026-03-13 04:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `user_authorizations`
--

CREATE TABLE `user_authorizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `code` int(11) NOT NULL,
  `token` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_kyc_data`
--

CREATE TABLE `user_kyc_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `reject_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_login_logs`
--

CREATE TABLE `user_login_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `mac` varchar(17) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `browser` varchar(30) DEFAULT NULL,
  `os` varchar(30) DEFAULT NULL,
  `timezone` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_login_logs`
--

INSERT INTO `user_login_logs` (`id`, `user_id`, `ip`, `mac`, `city`, `country`, `longitude`, `latitude`, `browser`, `os`, `timezone`, `created_at`, `updated_at`) VALUES
(1, 2, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-11 08:36:19', '2026-03-11 08:36:19'),
(2, 2, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Edge', 'Windows', 'America/New_York', '2026-03-11 15:08:59', '2026-03-11 15:08:59'),
(3, 2, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-11 16:24:46', '2026-03-11 16:24:46'),
(4, 2, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Edge', 'Windows', 'America/New_York', '2026-03-11 17:48:53', '2026-03-11 17:48:53'),
(5, 2, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-11 18:31:06', '2026-03-11 18:31:06'),
(6, 2, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Edge', 'iOS', 'America/New_York', '2026-03-11 19:16:02', '2026-03-11 19:16:02'),
(7, 2, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-13 09:33:45', '2026-03-13 09:33:45'),
(8, 2, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-13 09:49:45', '2026-03-13 09:49:45'),
(9, 2, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-13 10:24:33', '2026-03-13 10:24:33'),
(10, 2, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Edge', 'Windows', 'America/New_York', '2026-03-13 14:05:23', '2026-03-13 14:05:23'),
(11, 2, '::1', '', 'New Haven', 'United States', '-72.92', '41.31', 'Chrome', 'Windows', 'America/New_York', '2026-03-13 14:29:32', '2026-03-13 14:29:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_mail_logs`
--

CREATE TABLE `user_mail_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `method` varchar(191) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_notifications`
--

INSERT INTO `user_notifications` (`id`, `user_id`, `transaction_id`, `type`, `message`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'ADD-MONEY', '{\"title\":\"Add Money\",\"gateway\":\"WISE USD\",\"currency\":\"USD\",\"amount\":1000,\"message\":\"Successfully added.\"}', '2026-03-10 04:49:11', '2026-03-10 04:49:11'),
(2, 1, 5, 'ADD-MONEY', '{\"title\":\"Add Money\",\"gateway\":\"WISE USD\",\"currency\":\"USD\",\"amount\":200,\"message\":\"Successfully added.\"}', '2026-03-10 04:53:30', '2026-03-10 04:53:30'),
(3, 2, 6, 'ADD-MONEY', '{\"title\":\"Add Money\",\"gateway\":\"WISE USD\",\"currency\":\"USD\",\"amount\":1000,\"message\":\"Successfully added.\"}', '2026-03-10 07:27:05', '2026-03-10 07:27:05');

-- --------------------------------------------------------

--
-- Table structure for table `user_password_resets`
--

CREATE TABLE `user_password_resets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `code` bigint(20) UNSIGNED DEFAULT NULL,
  `token` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `zip_code` varchar(191) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `information` text DEFAULT NULL,
  `reject_reason` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_support_chats`
--

CREATE TABLE `user_support_chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_support_ticket_id` bigint(20) UNSIGNED NOT NULL,
  `sender` bigint(20) UNSIGNED NOT NULL,
  `sender_type` varchar(191) NOT NULL,
  `receiver` bigint(20) UNSIGNED DEFAULT NULL,
  `receiver_type` varchar(191) DEFAULT NULL,
  `message` text NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_support_tickets`
--

CREATE TABLE `user_support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(120) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `desc` text DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0: Default, 1: Solved, 2: Active, 3: Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_support_ticket_attachments`
--

CREATE TABLE `user_support_ticket_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_support_ticket_id` bigint(20) UNSIGNED NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `attachment_info` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_wallets`
--

CREATE TABLE `user_wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `balance` decimal(28,8) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_wallets`
--

INSERT INTO `user_wallets` (`id`, `user_id`, `currency_id`, `balance`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1200.00000000, 1, '2026-03-10 04:25:09', '2026-03-10 04:54:16'),
(2, 2, 1, 1000.00000000, 1, '2026-03-10 07:21:34', '2026-03-10 07:28:03');

-- --------------------------------------------------------

--
-- Table structure for table `virtual_card_apis`
--

CREATE TABLE `virtual_card_apis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `card_details` text DEFAULT NULL,
  `card_limit` int(11) NOT NULL DEFAULT 3,
  `config` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `virtual_card_apis`
--

INSERT INTO `virtual_card_apis` (`id`, `admin_id`, `image`, `card_details`, `card_limit`, `config`, `created_at`, `updated_at`) VALUES
(1, 1, 'seeder/virtual-card.png', 'This card is property of iBanking, Wonderland. Misuse is criminal offence. If found, please return to iBanking or to the nearest bank.', 3, '{\"strowallet_public_key\":\"R67MNEPQV2ABQW9HDD7JQFXQ2AJMMY\",\"strowallet_secret_key\":\"AOC963E385FORPRRCXQJ698C1Q953B\",\"strowallet_url\":\"https:\\/\\/strowallet.com\\/api\\/bitvcard\\/\",\"strowallet_mode\":\"live\",\"name\":\"strowallet\"}', '2024-08-22 15:58:03', '2026-03-12 04:55:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_image_unique` (`image`),
  ADD KEY `admins_username_index` (`username`),
  ADD KEY `admins_email_index` (`email`),
  ADD KEY `admins_phone_index` (`phone`);

--
-- Indexes for table `admin_has_roles`
--
ALTER TABLE `admin_has_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_has_roles_admin_id_foreign` (`admin_id`),
  ADD KEY `admin_has_roles_admin_role_id_foreign` (`admin_role_id`),
  ADD KEY `admin_has_roles_last_edit_by_foreign` (`last_edit_by`);

--
-- Indexes for table `admin_login_logs`
--
ALTER TABLE `admin_login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_login_logs_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_notifications_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_password_resets_email_index` (`email`);

--
-- Indexes for table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_roles_name_unique` (`name`),
  ADD KEY `admin_roles_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `admin_role_has_permissions`
--
ALTER TABLE `admin_role_has_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_role_has_permissions_admin_role_permission_id_foreign` (`admin_role_permission_id`),
  ADD KEY `admin_role_has_permissions_last_edit_by_foreign` (`last_edit_by`);

--
-- Indexes for table `admin_role_permissions`
--
ALTER TABLE `admin_role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_role_permissions_name_unique` (`name`),
  ADD UNIQUE KEY `admin_role_permissions_slug_unique` (`slug`),
  ADD KEY `admin_role_permissions_admin_role_id_foreign` (`admin_role_id`),
  ADD KEY `admin_role_permissions_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `announcements_slug_unique` (`slug`),
  ADD KEY `announcements_announcement_category_id_foreign` (`announcement_category_id`);

--
-- Indexes for table `announcement_categories`
--
ALTER TABLE `announcement_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_onboard_screens`
--
ALTER TABLE `app_onboard_screens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_onboard_screens_image_unique` (`image`),
  ADD KEY `app_onboard_screens_last_edit_by_foreign` (`last_edit_by`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_branches`
--
ALTER TABLE `bank_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_branches_admin_id_foreign` (`admin_id`),
  ADD KEY `bank_branches_bank_list_id_foreign` (`bank_list_id`),
  ADD KEY `bank_branches_name_index` (`name`),
  ADD KEY `bank_branches_alias_index` (`alias`);

--
-- Indexes for table `bank_lists`
--
ALTER TABLE `bank_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_lists_admin_id_foreign` (`admin_id`),
  ADD KEY `bank_lists_name_index` (`name`),
  ADD KEY `bank_lists_alias_index` (`alias`);

--
-- Indexes for table `basic_settings`
--
ALTER TABLE `basic_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `beneficiaries_slug_unique` (`slug`),
  ADD KEY `beneficiaries_transaction_method_id_foreign` (`transaction_method_id`),
  ADD KEY `beneficiaries_user_id_foreign` (`user_id`);

--
-- Indexes for table `coinbase_webhook_calls`
--
ALTER TABLE `coinbase_webhook_calls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_requests`
--
ALTER TABLE `contact_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crypto_assets`
--
ALTER TABLE `crypto_assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `crypto_assets_payment_gateway_id_foreign` (`payment_gateway_id`);

--
-- Indexes for table `crypto_transactions`
--
ALTER TABLE `crypto_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_code_unique` (`code`),
  ADD KEY `currencies_admin_id_foreign` (`admin_id`),
  ADD KEY `currencies_country_index` (`country`),
  ADD KEY `currencies_name_index` (`name`);

--
-- Indexes for table `extensions`
--
ALTER TABLE `extensions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `extensions_name_unique` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `holidays_holiday_date_unique` (`holiday_date`),
  ADD KEY `holidays_holiday_date_region_index` (`holiday_date`,`region`);

--
-- Indexes for table `investment_assets`
--
ALTER TABLE `investment_assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `investment_assets_symbol_unique` (`symbol`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `languages_name_unique` (`name`),
  ADD UNIQUE KEY `languages_code_unique` (`code`),
  ADD KEY `languages_last_edit_by_foreign` (`last_edit_by`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loans_loan_product_id_foreign` (`loan_product_id`),
  ADD KEY `loans_user_id_status_index` (`user_id`,`status`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_payments_loan_id_status_index` (`loan_id`,`status`);

--
-- Indexes for table `loan_products`
--
ALTER TABLE `loan_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_banks`
--
ALTER TABLE `mobile_banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mobile_banks_admin_id_foreign` (`admin_id`),
  ADD KEY `mobile_banks_name_index` (`name`),
  ADD KEY `mobile_banks_alias_index` (`alias`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_gateways_code_unique` (`code`),
  ADD KEY `payment_gateways_last_edit_by_foreign` (`last_edit_by`);

--
-- Indexes for table `payment_gateway_currencies`
--
ALTER TABLE `payment_gateway_currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_gateway_currencies_alias_unique` (`alias`),
  ADD KEY `payment_gateway_currencies_payment_gateway_id_foreign` (`payment_gateway_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `portfolios_user_id_index` (`user_id`);

--
-- Indexes for table `portfolio_holdings`
--
ALTER TABLE `portfolio_holdings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portfolio_holdings_portfolio_id_investment_asset_id_unique` (`portfolio_id`,`investment_asset_id`),
  ADD KEY `portfolio_holdings_investment_asset_id_foreign` (`investment_asset_id`);

--
-- Indexes for table `portfolio_transactions`
--
ALTER TABLE `portfolio_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `portfolio_transactions_portfolio_id_foreign` (`portfolio_id`),
  ADD KEY `portfolio_transactions_investment_asset_id_foreign` (`investment_asset_id`);

--
-- Indexes for table `push_notification_records`
--
ALTER TABLE `push_notification_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `push_notification_records_send_by_foreign` (`send_by`);

--
-- Indexes for table `salary_disbursement_users`
--
ALTER TABLE `salary_disbursement_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setup_kycs`
--
ALTER TABLE `setup_kycs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setup_kycs_slug_unique` (`slug`);

--
-- Indexes for table `setup_pages`
--
ALTER TABLE `setup_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setup_pages_slug_unique` (`slug`),
  ADD KEY `setup_pages_last_edit_by_foreign` (`last_edit_by`);

--
-- Indexes for table `setup_seos`
--
ALTER TABLE `setup_seos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setup_seos_last_edit_by_foreign` (`last_edit_by`);

--
-- Indexes for table `site_sections`
--
ALTER TABLE `site_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `strowallet_customer_kycs`
--
ALTER TABLE `strowallet_customer_kycs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `strowallet_customer_kycs_user_id_foreign` (`user_id`);

--
-- Indexes for table `strowallet_virtual_cards`
--
ALTER TABLE `strowallet_virtual_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `strowallet_virtual_cards_user_id_foreign` (`user_id`);

--
-- Indexes for table `subscribes`
--
ALTER TABLE `subscribes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscribes_email_unique` (`email`);

--
-- Indexes for table `system_maintenances`
--
ALTER TABLE `system_maintenances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_maintenances_slug_unique` (`slug`);

--
-- Indexes for table `temporary_datas`
--
ALTER TABLE `temporary_datas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_wallet_id_foreign` (`wallet_id`),
  ADD KEY `transactions_payment_gateway_currency_id_foreign` (`payment_gateway_currency_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `transaction_devices`
--
ALTER TABLE `transaction_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_devices_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `transaction_methods`
--
ALTER TABLE `transaction_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_methods_name_unique` (`name`),
  ADD UNIQUE KEY `transaction_methods_slug_unique` (`slug`),
  ADD KEY `transaction_methods_last_edit_by_foreign` (`last_edit_by`);

--
-- Indexes for table `transaction_settings`
--
ALTER TABLE `transaction_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_settings_slug_unique` (`slug`),
  ADD KEY `transaction_settings_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `useful_links`
--
ALTER TABLE `useful_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_full_mobile_unique` (`full_mobile`),
  ADD UNIQUE KEY `users_account_no_unique` (`account_no`),
  ADD KEY `users_mobile_index` (`mobile`);

--
-- Indexes for table `user_authorizations`
--
ALTER TABLE `user_authorizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_kyc_data`
--
ALTER TABLE `user_kyc_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_kyc_data_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_login_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_mail_logs`
--
ALTER TABLE `user_mail_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_mail_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_notifications_user_id_foreign` (`user_id`),
  ADD KEY `user_notifications_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `user_password_resets`
--
ALTER TABLE `user_password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_password_resets_token_unique` (`token`),
  ADD UNIQUE KEY `user_password_resets_code_unique` (`code`),
  ADD KEY `user_password_resets_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_profiles_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_support_chats`
--
ALTER TABLE `user_support_chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_support_chats_user_support_ticket_id_foreign` (`user_support_ticket_id`);

--
-- Indexes for table `user_support_tickets`
--
ALTER TABLE `user_support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_support_tickets_token_unique` (`token`),
  ADD KEY `user_support_tickets_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_support_ticket_attachments`
--
ALTER TABLE `user_support_ticket_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_support_ticket_attachments_user_support_ticket_id_foreign` (`user_support_ticket_id`);

--
-- Indexes for table `user_wallets`
--
ALTER TABLE `user_wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_wallets_currency_id_foreign` (`currency_id`),
  ADD KEY `user_wallets_user_id_foreign` (`user_id`);

--
-- Indexes for table `virtual_card_apis`
--
ALTER TABLE `virtual_card_apis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `virtual_card_apis_admin_id_foreign` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `admin_has_roles`
--
ALTER TABLE `admin_has_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admin_login_logs`
--
ALTER TABLE `admin_login_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_roles`
--
ALTER TABLE `admin_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admin_role_has_permissions`
--
ALTER TABLE `admin_role_has_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_role_permissions`
--
ALTER TABLE `admin_role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `announcement_categories`
--
ALTER TABLE `announcement_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `app_onboard_screens`
--
ALTER TABLE `app_onboard_screens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_branches`
--
ALTER TABLE `bank_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bank_lists`
--
ALTER TABLE `bank_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `basic_settings`
--
ALTER TABLE `basic_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coinbase_webhook_calls`
--
ALTER TABLE `coinbase_webhook_calls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_requests`
--
ALTER TABLE `contact_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crypto_assets`
--
ALTER TABLE `crypto_assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crypto_transactions`
--
ALTER TABLE `crypto_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `extensions`
--
ALTER TABLE `extensions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `investment_assets`
--
ALTER TABLE `investment_assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_products`
--
ALTER TABLE `loan_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `mobile_banks`
--
ALTER TABLE `mobile_banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payment_gateway_currencies`
--
ALTER TABLE `payment_gateway_currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `portfolio_holdings`
--
ALTER TABLE `portfolio_holdings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `portfolio_transactions`
--
ALTER TABLE `portfolio_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `push_notification_records`
--
ALTER TABLE `push_notification_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_disbursement_users`
--
ALTER TABLE `salary_disbursement_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setup_kycs`
--
ALTER TABLE `setup_kycs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `setup_pages`
--
ALTER TABLE `setup_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `setup_seos`
--
ALTER TABLE `setup_seos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_sections`
--
ALTER TABLE `site_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `strowallet_customer_kycs`
--
ALTER TABLE `strowallet_customer_kycs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `strowallet_virtual_cards`
--
ALTER TABLE `strowallet_virtual_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribes`
--
ALTER TABLE `subscribes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_maintenances`
--
ALTER TABLE `system_maintenances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `temporary_datas`
--
ALTER TABLE `temporary_datas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transaction_devices`
--
ALTER TABLE `transaction_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_methods`
--
ALTER TABLE `transaction_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaction_settings`
--
ALTER TABLE `transaction_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `useful_links`
--
ALTER TABLE `useful_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_authorizations`
--
ALTER TABLE `user_authorizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_kyc_data`
--
ALTER TABLE `user_kyc_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_mail_logs`
--
ALTER TABLE `user_mail_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_password_resets`
--
ALTER TABLE `user_password_resets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_support_chats`
--
ALTER TABLE `user_support_chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_support_tickets`
--
ALTER TABLE `user_support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_support_ticket_attachments`
--
ALTER TABLE `user_support_ticket_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_wallets`
--
ALTER TABLE `user_wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `virtual_card_apis`
--
ALTER TABLE `virtual_card_apis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_has_roles`
--
ALTER TABLE `admin_has_roles`
  ADD CONSTRAINT `admin_has_roles_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_has_roles_admin_role_id_foreign` FOREIGN KEY (`admin_role_id`) REFERENCES `admin_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_has_roles_last_edit_by_foreign` FOREIGN KEY (`last_edit_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `admin_login_logs`
--
ALTER TABLE `admin_login_logs`
  ADD CONSTRAINT `admin_login_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD CONSTRAINT `admin_notifications_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD CONSTRAINT `admin_roles_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `admin_role_has_permissions`
--
ALTER TABLE `admin_role_has_permissions`
  ADD CONSTRAINT `admin_role_has_permissions_admin_role_permission_id_foreign` FOREIGN KEY (`admin_role_permission_id`) REFERENCES `admin_role_permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_role_has_permissions_last_edit_by_foreign` FOREIGN KEY (`last_edit_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `admin_role_permissions`
--
ALTER TABLE `admin_role_permissions`
  ADD CONSTRAINT `admin_role_permissions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_role_permissions_admin_role_id_foreign` FOREIGN KEY (`admin_role_id`) REFERENCES `admin_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_announcement_category_id_foreign` FOREIGN KEY (`announcement_category_id`) REFERENCES `announcement_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `app_onboard_screens`
--
ALTER TABLE `app_onboard_screens`
  ADD CONSTRAINT `app_onboard_screens_last_edit_by_foreign` FOREIGN KEY (`last_edit_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bank_branches`
--
ALTER TABLE `bank_branches`
  ADD CONSTRAINT `bank_branches_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bank_branches_bank_list_id_foreign` FOREIGN KEY (`bank_list_id`) REFERENCES `bank_lists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bank_lists`
--
ALTER TABLE `bank_lists`
  ADD CONSTRAINT `bank_lists_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `beneficiaries_transaction_method_id_foreign` FOREIGN KEY (`transaction_method_id`) REFERENCES `transaction_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `beneficiaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `crypto_assets`
--
ALTER TABLE `crypto_assets`
  ADD CONSTRAINT `crypto_assets_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `currencies`
--
ALTER TABLE `currencies`
  ADD CONSTRAINT `currencies_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `languages`
--
ALTER TABLE `languages`
  ADD CONSTRAINT `languages_last_edit_by_foreign` FOREIGN KEY (`last_edit_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_loan_product_id_foreign` FOREIGN KEY (`loan_product_id`) REFERENCES `loan_products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD CONSTRAINT `loan_payments_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mobile_banks`
--
ALTER TABLE `mobile_banks`
  ADD CONSTRAINT `mobile_banks_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD CONSTRAINT `payment_gateways_last_edit_by_foreign` FOREIGN KEY (`last_edit_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_gateway_currencies`
--
ALTER TABLE `payment_gateway_currencies`
  ADD CONSTRAINT `payment_gateway_currencies_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD CONSTRAINT `portfolios_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `portfolio_holdings`
--
ALTER TABLE `portfolio_holdings`
  ADD CONSTRAINT `portfolio_holdings_investment_asset_id_foreign` FOREIGN KEY (`investment_asset_id`) REFERENCES `investment_assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `portfolio_holdings_portfolio_id_foreign` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `portfolio_transactions`
--
ALTER TABLE `portfolio_transactions`
  ADD CONSTRAINT `portfolio_transactions_investment_asset_id_foreign` FOREIGN KEY (`investment_asset_id`) REFERENCES `investment_assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `portfolio_transactions_portfolio_id_foreign` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `push_notification_records`
--
ALTER TABLE `push_notification_records`
  ADD CONSTRAINT `push_notification_records_send_by_foreign` FOREIGN KEY (`send_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `setup_pages`
--
ALTER TABLE `setup_pages`
  ADD CONSTRAINT `setup_pages_last_edit_by_foreign` FOREIGN KEY (`last_edit_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `setup_seos`
--
ALTER TABLE `setup_seos`
  ADD CONSTRAINT `setup_seos_last_edit_by_foreign` FOREIGN KEY (`last_edit_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `strowallet_customer_kycs`
--
ALTER TABLE `strowallet_customer_kycs`
  ADD CONSTRAINT `strowallet_customer_kycs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `strowallet_virtual_cards`
--
ALTER TABLE `strowallet_virtual_cards`
  ADD CONSTRAINT `strowallet_virtual_cards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_payment_gateway_currency_id_foreign` FOREIGN KEY (`payment_gateway_currency_id`) REFERENCES `payment_gateway_currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `user_wallets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction_devices`
--
ALTER TABLE `transaction_devices`
  ADD CONSTRAINT `transaction_devices_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction_methods`
--
ALTER TABLE `transaction_methods`
  ADD CONSTRAINT `transaction_methods_last_edit_by_foreign` FOREIGN KEY (`last_edit_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction_settings`
--
ALTER TABLE `transaction_settings`
  ADD CONSTRAINT `transaction_settings_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_kyc_data`
--
ALTER TABLE `user_kyc_data`
  ADD CONSTRAINT `user_kyc_data_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  ADD CONSTRAINT `user_login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_mail_logs`
--
ALTER TABLE `user_mail_logs`
  ADD CONSTRAINT `user_mail_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_password_resets`
--
ALTER TABLE `user_password_resets`
  ADD CONSTRAINT `user_password_resets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_support_chats`
--
ALTER TABLE `user_support_chats`
  ADD CONSTRAINT `user_support_chats_user_support_ticket_id_foreign` FOREIGN KEY (`user_support_ticket_id`) REFERENCES `user_support_tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_support_tickets`
--
ALTER TABLE `user_support_tickets`
  ADD CONSTRAINT `user_support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_support_ticket_attachments`
--
ALTER TABLE `user_support_ticket_attachments`
  ADD CONSTRAINT `user_support_ticket_attachments_user_support_ticket_id_foreign` FOREIGN KEY (`user_support_ticket_id`) REFERENCES `user_support_tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_wallets`
--
ALTER TABLE `user_wallets`
  ADD CONSTRAINT `user_wallets_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `virtual_card_apis`
--
ALTER TABLE `virtual_card_apis`
  ADD CONSTRAINT `virtual_card_apis_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
