-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 14, 2021 at 10:14 AM
-- Server version: 10.3.29-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nb32895_qwilivery`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'avatar.png',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `first_name`, `last_name`, `phone_number`, `email`, `password`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'Amin', 'Guesmi', '5147061712', 'amine.guesmi83@hotmail.fr', '$2y$10$65C87OglsUjwej6Unoh.WOPIdSVgEFfmf50D.GhypLYlvUvOAtuwW', '1620147935.jpg', NULL, NULL),
(2, 'Up', 'Spove', '0645951147', 'contact@spoveup.com', '$2y$10$QzhRACubqvlWPHE41tYWyusnqGg8DNTtJiCajO4aGQ4TFUgi1Od66', 'avatar.png', NULL, '2021-05-13 17:30:25');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `restaurant_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `restaurant_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'soupes', 'Tom Yum\nSoupe Thaï\nATTENTION! AVIS AUX ALLERGÈNES\nThaï Express offre des produits contenants du poisson, crustacé, mollusque, sésame, soya,produit de lait modifié, œuf, blé..', '2021-05-13 18:16:46', '2021-05-13 18:16:46');

-- --------------------------------------------------------

--
-- Table structure for table `chatflows`
--

CREATE TABLE `chatflows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `from` enum('delivery','restaurant','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `to` enum('delivery','restaurant','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_id` bigint(20) UNSIGNED DEFAULT NULL,
  `restaurant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` enum('delivery','restaurant','admin') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `experience` int(11) NOT NULL,
  `permit` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'avatar.png',
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `blocked_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id`, `first_name`, `last_name`, `email`, `password`, `experience`, `permit`, `avatar`, `phone_number`, `approved_at`, `blocked_at`, `verified_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'ayari', 'tahar', 'taharayari59@yahoo.ca', '$2y$10$Or33gSveo0d9YCaJaZbfjOijsGCye6Y9AcuY4XnI9AUvfMewYiFkq', 5, '1620911651.jpg', 'avatar.png', '4388785904', '2021-05-14 18:01:50', NULL, NULL, NULL, '2021-05-13 17:14:11', '2021-05-14 18:01:50'),
(2, 'fabien', 'fayy', 'fayfabien42@gmail.com', '$2y$10$TYtQw5kkUolrfuhrKb9V2uFAv6ncws42x/6i52iXb0vd5OXxGJ2rm', 5, '1620913279.jpg', 'avatar.png', '4388388392', '2021-05-13 17:43:01', NULL, NULL, NULL, '2021-05-13 17:41:19', '2021-05-13 17:43:01'),
(3, 'aouled', 'idriss', 'Idrissaouled2015@gmail.com', '$2y$10$NHZnNbdl.UN8c0ZXuwMXZeMvuWEfuZzZRoQo9W1hIprJZ3IiKD78m', 2, '1620999917.jpg', 'avatar.png', '4384027919', '2021-05-14 18:01:39', NULL, NULL, NULL, '2021-05-14 17:45:18', '2021-05-14 18:01:39'),
(4, 'iman', 'abdillahi', 'Abdillahiiman14@gmail.com', '$2y$10$9/bZ.DiGQd6LJ15D39wQO.t22JPyKtpqg/tIBSXjfTjlODJ0g5X9S', 3, '1621000040.jpg', 'avatar.png', '4389894526', '2021-05-14 18:01:37', NULL, NULL, NULL, '2021-05-14 17:47:20', '2021-05-14 18:01:37');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2021_03_24_162615_create_deliveries_table', 1),
(6, '2021_03_24_162708_create_admins_table', 1),
(7, '2021_03_24_162744_create_restaurants_table', 1),
(8, '2021_04_06_104823_create_categories_table', 1),
(9, '2021_04_06_105030_create_variants_table', 1),
(10, '2021_04_21_010059_create_pre_orders_table', 1),
(11, '2021_04_21_010417_create_orders_table', 1),
(12, '2021_04_28_053928_create_reports_table', 1),
(13, '2021_04_30_234714_create_chatflows_table', 1),
(14, '2021_05_01_012408_create_conversations_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pre_order_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `pre_order_id`, `variant_id`, `qty`, `created_at`, `updated_at`) VALUES
(3, 3, 1, 1, '2021-05-14 06:49:55', '2021-05-14 06:49:55'),
(2, 2, 1, 1, '2021-05-13 18:31:52', '2021-05-13 18:31:52');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Admin', 2, 'admin-api', '2176f4e186d9b9df41087e3a0c40ed42b6592ea3c4333e14886d3ca1889ca7df', '[\"admin-stuff\"]', '2021-05-13 17:23:14', '2021-05-13 17:17:34', '2021-05-13 17:23:14'),
(2, 'App\\Models\\Admin', 2, 'admin-api', '2cbf8c2f6b154967d19223e22cb15aaa174b45b58abc266b52baf952c8ea3066', '[\"admin-stuff\"]', '2021-05-13 17:30:25', '2021-05-13 17:30:05', '2021-05-13 17:30:25'),
(3, 'App\\Models\\Admin', 2, 'admin-api', 'd86d826fc60beb75c76f0fed0b556c8ca20ec2dfa8f9c73bbc964b1de0af684e', '[\"admin-stuff\"]', '2021-05-13 17:30:42', '2021-05-13 17:30:37', '2021-05-13 17:30:42'),
(4, 'App\\Models\\Admin', 1, 'admin-api', 'c3d1597db97dea2b0129468be9f58469337aa78b200e883c3462a8e92a06041e', '[\"admin-stuff\"]', '2021-05-13 17:32:00', '2021-05-13 17:31:59', '2021-05-13 17:32:00'),
(5, 'App\\Models\\Admin', 1, 'admin-api', '38879389c3d91b14269029cf1c92915e762b1250c9680d2edc07052a6a5a4bab', '[\"admin-stuff\"]', '2021-05-13 18:07:37', '2021-05-13 17:41:34', '2021-05-13 18:07:37'),
(6, 'App\\Models\\Delivery', 2, 'admin-api', '91592df5899e0eb1fb560164f8b67347103b03f508e29ceb6c6c6491c318770a', '[\"admin-stuff\"]', '2021-05-13 17:55:22', '2021-05-13 17:55:21', '2021-05-13 17:55:22'),
(7, 'App\\Models\\Admin', 1, 'admin-api', 'e4d4e9107426dfd43781868a4f84907b407b264dfad80b1afd6e5cade451a7cf', '[\"admin-stuff\"]', '2021-05-13 18:01:22', '2021-05-13 18:01:19', '2021-05-13 18:01:22'),
(8, 'App\\Models\\Restaurant', 1, 'restaurant-api', '12d3fe96702e0d7cf2468441f5e672ddda59bfed4e04bbbab95fcb933a40d26f', '[\"restaurant-stuff\"]', '2021-05-13 18:40:27', '2021-05-13 18:06:23', '2021-05-13 18:40:27'),
(9, 'App\\Models\\Admin', 1, 'admin-api', '8b46c1640ffb85a5d2319c4231f0e6ee11bf6c6618781169a5de84d2b32fb901', '[\"admin-stuff\"]', '2021-05-13 18:08:10', '2021-05-13 18:08:06', '2021-05-13 18:08:10'),
(10, 'App\\Models\\Delivery', 2, 'admin-api', '8c2dc085313e5b1438eb905e73ed67e0bd914edf178acd2bf215371a55184a68', '[\"admin-stuff\"]', '2021-05-13 19:21:58', '2021-05-13 18:08:29', '2021-05-13 19:21:58'),
(11, 'App\\Models\\Admin', 2, 'admin-api', 'b2da8e74a7031509d14d468dc7ca0e368d8284f115e0e101d6395c14ac0efb7c', '[\"admin-stuff\"]', '2021-05-13 18:16:19', '2021-05-13 18:16:16', '2021-05-13 18:16:19'),
(12, 'App\\Models\\Admin', 1, 'admin-api', '5c2603d72da8e5809e6fa1e7fef23659368b8772fc517aefaea3fc1639f076d5', '[\"admin-stuff\"]', '2021-05-13 19:23:09', '2021-05-13 19:22:16', '2021-05-13 19:23:09'),
(13, 'App\\Models\\Admin', 1, 'admin-api', '54f4a8ba1eb8ef513b9c1c60772c47d458064192e6b832b54f678362241a13be', '[\"admin-stuff\"]', '2021-05-14 04:54:47', '2021-05-14 02:00:37', '2021-05-14 04:54:47'),
(14, 'App\\Models\\Admin', 1, 'admin-api', '396e7cb7640cf5179751aaf2efaa9821fb888cf3dd607d63d8d7976338d37241', '[\"admin-stuff\"]', '2021-05-14 06:32:21', '2021-05-14 06:29:34', '2021-05-14 06:32:21'),
(15, 'App\\Models\\Restaurant', 3, 'restaurant-api', '8df477e70c42742caa4d579aecf98e7b1cbf035a8ff6ec2ec49554c3ed424745', '[\"restaurant-stuff\"]', '2021-05-14 06:38:26', '2021-05-14 06:37:37', '2021-05-14 06:38:26'),
(16, 'App\\Models\\Admin', 1, 'admin-api', '19a7c3d47e369821294df5d63018e15efd12935eb96dc707db7f748c18c8502b', '[\"admin-stuff\"]', '2021-05-14 06:45:04', '2021-05-14 06:38:47', '2021-05-14 06:45:04'),
(17, 'App\\Models\\Restaurant', 4, 'restaurant-api', '5ba4f96a5fffc9104eb190aea1cd61c45777c1fb2df0e2ab67fc8387e5bc4b41', '[\"restaurant-stuff\"]', '2021-05-14 06:47:05', '2021-05-14 06:46:05', '2021-05-14 06:47:05'),
(18, 'App\\Models\\Admin', 1, 'admin-api', 'c5d233152aa7480a39d3d25fbaf4003120edd13a938fe00404f55447613ed9b4', '[\"admin-stuff\"]', '2021-05-14 06:47:15', '2021-05-14 06:47:14', '2021-05-14 06:47:15'),
(19, 'App\\Models\\Restaurant', 1, 'restaurant-api', 'ffcfcd577a52301114d45066132559e22955e478203f4b14bcfbf4d4f2b8f3e0', '[\"restaurant-stuff\"]', '2021-05-14 06:52:43', '2021-05-14 06:48:20', '2021-05-14 06:52:43'),
(20, 'App\\Models\\Admin', 1, 'admin-api', '6d3accad4e7023a3f9f0507bfb5e91353fbc6f06b3a68ec3ac0531236c6528f1', '[\"admin-stuff\"]', '2021-05-14 06:52:28', '2021-05-14 06:48:45', '2021-05-14 06:52:28'),
(21, 'App\\Models\\Restaurant', 2, 'restaurant-api', '182fdc77adf5fa30010140ea1862bbab5b784bb095329d0180b8f0656e26caae', '[\"restaurant-stuff\"]', '2021-05-14 09:14:58', '2021-05-14 09:14:32', '2021-05-14 09:14:58'),
(22, 'App\\Models\\Restaurant', 2, 'restaurant-api', 'ceeada780e77bd12e8b70a5ec0a48f2b8c86720cacfb9f247accabb75ecbd2a2', '[\"restaurant-stuff\"]', '2021-05-14 15:31:41', '2021-05-14 15:30:39', '2021-05-14 15:31:41'),
(23, 'App\\Models\\Admin', 1, 'admin-api', '3090c3c2f3939037ae53193835bef148feff72d0dd620d30838d7475754e8b49', '[\"admin-stuff\"]', '2021-05-14 16:35:34', '2021-05-14 16:33:54', '2021-05-14 16:35:34'),
(24, 'App\\Models\\Restaurant', 5, 'restaurant-api', 'd4bda4a1fbef7603926091e2d0ff958c7a6cea46446440c63c4137b90bf20826', '[\"restaurant-stuff\"]', '2021-05-14 16:40:12', '2021-05-14 16:39:44', '2021-05-14 16:40:12'),
(25, 'App\\Models\\Admin', 1, 'admin-api', 'c6b0b84c47be7473fdf933c31bad26574f1626c0dd1b7e77f63ab7b538eede0b', '[\"admin-stuff\"]', '2021-05-14 17:16:59', '2021-05-14 16:42:04', '2021-05-14 17:16:59'),
(26, 'App\\Models\\Restaurant', 7, 'restaurant-api', 'ccd81a33f974cee6ce3eae5f9c09690bdc9dc309dffe748a28a6e059ad2e4f37', '[\"restaurant-stuff\"]', '2021-05-14 17:17:57', '2021-05-14 17:17:22', '2021-05-14 17:17:57'),
(27, 'App\\Models\\Admin', 1, 'admin-api', '9435336de626d288e971446f9e4d58c60fb56efa80b706edfc5c7a3fee2ac2e9', '[\"admin-stuff\"]', '2021-05-14 17:58:57', '2021-05-14 17:18:52', '2021-05-14 17:58:57'),
(28, 'App\\Models\\Restaurant', 8, 'restaurant-api', '2e69bfbeaddc4ce934fabe4478f9ff7c1f5d099ddd0746efeed2aa347f895b7d', '[\"restaurant-stuff\"]', '2021-05-14 18:00:32', '2021-05-14 18:00:07', '2021-05-14 18:00:32'),
(29, 'App\\Models\\Admin', 1, 'admin-api', '602909f067f2e7809fbe28362766e649ca703a363383e4379778e9e4c71bce14', '[\"admin-stuff\"]', '2021-05-14 18:04:50', '2021-05-14 18:01:26', '2021-05-14 18:04:50');

-- --------------------------------------------------------

--
-- Table structure for table `pre_orders`
--

CREATE TABLE `pre_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_id` bigint(20) UNSIGNED DEFAULT NULL,
  `restaurant_id` bigint(20) UNSIGNED NOT NULL,
  `fullname` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lng` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pre_orders`
--

INSERT INTO `pre_orders` (`id`, `delivery_id`, `restaurant_id`, `fullname`, `phone_number`, `address`, `lat`, `lng`, `delivered_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 2, 1, 'hedi talbi', '5147061712', '3930JEANTALON EST', NULL, NULL, NULL, '2021-05-14 06:52:43', '2021-05-14 06:49:55', '2021-05-14 06:52:43'),
(2, 2, 1, 'khaled', '4389935528', '2932SHERBRROKE EST', NULL, NULL, '2021-05-13 18:33:10', '2021-05-13 18:40:27', '2021-05-13 18:31:52', '2021-05-13 18:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `guard` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_id` int(11) NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lng` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'restaurant.png',
  `rate` double NOT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `blocked_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `email`, `password`, `phone_number`, `address`, `lat`, `lng`, `logo`, `rate`, `approved_at`, `blocked_at`, `verified_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'thai express', 'thaiexpress22@gmail.com', '$2y$10$EiSKiF8ZKcXu216xRgbxie1hlP4Z076v9uH3.GHBRPNAyEE0ZgaBq', '5147213131', '5040jeantalon est', '45.481224565276', '-73.588042587658', 'restaurant.png', 120, NULL, NULL, NULL, NULL, '2021-05-13 17:58:48', '2021-05-13 18:30:11'),
(2, 'art kabeb', 'tgvmontreal@hotmail.com', '$2y$10$RBpmgYlv0qLHDuo4VvdTm.HfeMW5fxUyN9LcQhddq022Oc8vv2frW', '5147309638', '4528 jean talon est', NULL, NULL, 'restaurant.png', 120, NULL, NULL, NULL, NULL, '2021-05-14 02:01:45', '2021-05-14 15:31:35'),
(3, 'la perle antillaise', 'antillaiselaperle19@gmail.com', '$2y$10$WKg81xS678siHkvabcVqs.cZhOwm0Y7xvFjC.bnG4pfh9yKTF1qCO', '5146033249', '5152beaubien est', NULL, NULL, 'restaurant.png', 160, NULL, NULL, NULL, NULL, '2021-05-14 06:32:20', '2021-05-14 06:38:23'),
(4, 'restaurant sabra', 'grillades.sabra@gmail.com', '$2y$10$.rrQyyyGer1n6TpkqUjbS.qpaAxcbKMC1YqOv1rWwllvZG/sMAZe6', '5147270936', '3930jeantalon est', NULL, NULL, 'restaurant.png', 100, NULL, NULL, NULL, NULL, '2021-05-14 06:45:03', '2021-05-14 06:47:02'),
(5, 'sachi suchi', 'sachisuchi035@gmail.com', '$2y$10$erqDTx5ZZTLEW1FNmaATCuu7oZIPfq6Um/08ojOdSXbFiSo3V31Ie', '5143768867', '4931 beaubien est', NULL, NULL, 'restaurant.png', 120, NULL, NULL, NULL, NULL, '2021-05-14 16:35:33', '2021-05-14 16:40:08'),
(6, 'restaurant futago', 'cohoa@outlook.com', '$2y$10$uGlaTKXSSxceDfGQ2hDfKuGRuYnvGNbHl4NEQp4g/.OZwt0qzelcG', '5147507575', '1251 rue belanger', NULL, NULL, 'restaurant.png', 100, NULL, NULL, NULL, '2021-05-14 17:13:46', '2021-05-14 16:43:41', '2021-05-14 17:13:46'),
(7, 'futago belanger', 'futagobelanger@gmail.com', '$2y$10$UZCWhkQEmmOvWlu/bgD5bePuGFfnc4x3kihJeOeTks7Fw4BlZCH3a', '5149522773', '1251 rue belanger', NULL, NULL, 'restaurant.png', 100, NULL, NULL, NULL, NULL, '2021-05-14 17:15:09', '2021-05-14 17:17:50'),
(8, 'la belle province', 'labelleprovince815@gmail.com', '$2y$10$ta3dX8XHSQkgHQzfDhHVQOkRdBon.Kz/qBJcR8J1ca5Zv8KHaaHcy', '5147223694', '1356 jarry est', NULL, NULL, 'restaurant.png', 100, NULL, NULL, NULL, NULL, '2021-05-14 17:58:56', '2021-05-14 18:00:28'),
(9, 'pizza time', 'cherif.73@hotmail.com', '$2y$10$ynjrQoUy7vgjUVLFVDVYz./eQGZyZJpApHLg4SaDqBcxhGNhbtYva', '5147270222', '3882 rue belanger', NULL, NULL, 'restaurant.png', 120, NULL, NULL, NULL, NULL, '2021-05-14 18:04:48', '2021-05-14 18:04:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variants`
--

CREATE TABLE `variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variants`
--

INSERT INTO `variants` (`id`, `photo`, `name`, `price`, `description`, `category_id`, `created_at`, `updated_at`) VALUES
(1, '1620915641.jpg', 'Soupe Thaï', 11, 'Bouillon au poulet épicé aigre-doux à la citronnelle avec nouilles de riz, fève germée, oignon', 1, '2021-05-13 18:20:41', '2021-05-13 18:20:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_restaurant_id_foreign` (`restaurant_id`);

--
-- Indexes for table `chatflows`
--
ALTER TABLE `chatflows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chatflows_conversation_id_foreign` (`conversation_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversations_delivery_id_foreign` (`delivery_id`),
  ADD KEY `conversations_restaurant_id_foreign` (`restaurant_id`),
  ADD KEY `conversations_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deliveries_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_pre_order_id_foreign` (`pre_order_id`),
  ADD KEY `orders_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pre_orders`
--
ALTER TABLE `pre_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pre_orders_delivery_id_foreign` (`delivery_id`),
  ADD KEY `pre_orders_restaurant_id_foreign` (`restaurant_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurants_email_unique` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variants_category_id_foreign` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chatflows`
--
ALTER TABLE `chatflows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `pre_orders`
--
ALTER TABLE `pre_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variants`
--
ALTER TABLE `variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
