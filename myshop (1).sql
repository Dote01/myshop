-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2024 at 10:10 AM
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
-- Database: `myshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `activity` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `date`, `activity`) VALUES
(1, '2024-09-12 06:24:41', 'User logged in'),
(2, '2024-09-12 06:24:41', 'User updated profile'),
(3, '2024-09-12 06:24:41', 'Order placed');

-- --------------------------------------------------------

--
-- Table structure for table `ai_queries`
--

CREATE TABLE `ai_queries` (
  `id` int(11) NOT NULL,
  `retailer_id` int(11) NOT NULL,
  `query_text` text NOT NULL,
  `response_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `analysis`
--

CREATE TABLE `analysis` (
  `analysis_id` int(11) NOT NULL,
  `retailer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `clicks` int(11) DEFAULT 0,
  `sales` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `retailer_id` int(11) NOT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `campaign_type` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Farm Products'),
(2, 'Industrial Products'),
(3, 'Other Products');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `discussion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `discussion_id`, `user_id`, `content`, `created_at`) VALUES
(1, 10, 40, 'sgdf', '2024-09-11 16:49:27'),
(2, 15, 41, 'wsafg', '2024-09-12 08:00:38'),
(3, 15, 41, 'wsafg', '2024-09-12 08:00:59'),
(4, 15, 41, 'wsafg', '2024-09-12 08:05:32'),
(5, 6, 41, 'adfrs', '2024-09-12 08:05:50'),
(6, 6, 41, 'adfrs', '2024-09-12 08:06:03'),
(7, 6, 41, 'adfrs', '2024-09-12 08:09:32'),
(8, 19, 41, 'dg', '2024-09-12 08:53:14'),
(9, 19, 41, 'dg', '2024-09-12 08:53:30'),
(10, 28, 41, 'aa', '2024-09-12 11:17:47'),
(11, 28, 41, 'aa', '2024-09-12 11:17:51'),
(12, 28, 41, 'qq', '2024-09-12 11:18:56'),
(13, 28, 41, 'qq', '2024-09-12 11:25:27'),
(14, 28, 41, 'aa', '2024-09-12 11:25:47'),
(15, 27, 41, 'wozaa\r\n', '2024-09-12 11:30:05'),
(16, 28, 41, 'aa', '2024-09-12 11:39:32'),
(17, 28, 41, 'aa', '2024-09-12 11:41:18'),
(18, 53, 41, 'medy', '2024-09-12 11:43:58'),
(19, 55, 42, '111', '2024-09-12 12:44:18');

-- --------------------------------------------------------

--
-- Table structure for table `comment_reactions`
--

CREATE TABLE `comment_reactions` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reaction_type` enum('like','dislike') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment_reactions`
--

INSERT INTO `comment_reactions` (`id`, `comment_id`, `user_id`, `reaction_type`) VALUES
(1, 10, 41, 'like'),
(2, 11, 41, 'like'),
(3, 12, 41, 'like'),
(4, 13, 41, 'like'),
(5, 15, 41, 'like'),
(6, 14, 41, 'like'),
(7, 8, 41, 'like'),
(8, 9, 41, 'like'),
(9, 3, 41, 'like'),
(10, 18, 41, 'like'),
(11, 19, 42, 'like');

-- --------------------------------------------------------

--
-- Table structure for table `customer_feedback`
--

CREATE TABLE `customer_feedback` (
  `id` int(11) NOT NULL,
  `retailer_id` int(11) NOT NULL,
  `feedback_text` text NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `sentiment` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discussionmessages`
--

CREATE TABLE `discussionmessages` (
  `message_id` int(11) NOT NULL,
  `discussion_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discussionmessages`
--

INSERT INTO `discussionmessages` (`message_id`, `discussion_id`, `sender_id`, `message`, `created_at`, `updated_at`) VALUES
(1, NULL, 29, 'ghaik', '2024-08-30 23:49:31', '2024-08-30 23:49:31');

-- --------------------------------------------------------

--
-- Table structure for table `discussionrooms`
--

CREATE TABLE `discussionrooms` (
  `discussion_id` int(11) NOT NULL,
  `retailer_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `topic` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discussionrooms`
--

INSERT INTO `discussionrooms` (`discussion_id`, `retailer_id`, `admin_id`, `topic`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'kwanin maji yanapanga', '2024-08-30 23:37:47', '2024-08-30 23:37:47'),
(2, NULL, NULL, 'kwanin maji yanapanga', '2024-08-30 23:39:31', '2024-08-30 23:39:31'),
(3, NULL, NULL, 'adf', '2024-08-30 23:43:30', '2024-08-30 23:43:30'),
(4, NULL, NULL, 'adf', '2024-08-30 23:46:35', '2024-08-30 23:46:35'),
(5, NULL, NULL, 'sd', '2024-08-30 23:47:00', '2024-08-30 23:47:00'),
(6, NULL, NULL, 'adfg', '2024-08-30 23:48:12', '2024-08-30 23:48:12');

-- --------------------------------------------------------

--
-- Table structure for table `discussions`
--

CREATE TABLE `discussions` (
  `discussion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT NULL,
  `retailer_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `topic` varchar(255) DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `passcode` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `max_participants` int(11) DEFAULT 250
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discussions`
--

INSERT INTO `discussions` (`discussion_id`, `user_id`, `title`, `content`, `created_at`, `status`, `retailer_id`, `admin_id`, `topic`, `is_private`, `passcode`, `description`, `created_by`, `max_participants`) VALUES
(1, 40, '22', '22', '2024-09-11 16:42:05', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(2, 40, '22', '22', '2024-09-11 16:42:08', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(3, 40, '22', '22', '2024-09-11 16:45:18', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(4, 40, '22', '22', '2024-09-11 16:45:21', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(5, 40, '111', '11', '2024-09-11 16:45:46', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(6, 40, '111', '11', '2024-09-11 16:46:27', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(7, 40, '111', '11', '2024-09-11 16:46:32', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(8, 40, '111', '11', '2024-09-11 16:47:18', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(9, 40, '111', '11', '2024-09-11 16:47:56', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(10, 40, '111', '11', '2024-09-11 16:49:20', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(11, 40, 's', 's', '2024-09-11 16:49:39', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(12, 41, 'maji kupanda bei', 'kwanini imekuwa ghafra hivi', '2024-09-12 07:50:17', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(13, 41, 'maji kupanda bei', 'kwanini imekuwa ghafra hivi', '2024-09-12 07:56:08', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(14, 41, 'maji kupanda bei', 'kwanini imekuwa ghafra hivi', '2024-09-12 07:58:18', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(15, 41, 'maji kupanda bei', 'kwanini imekuwa ghafra hivi', '2024-09-12 08:00:29', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(16, 41, 'aa', 'ww', '2024-09-12 08:32:30', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(17, 41, 'aa', 'ww', '2024-09-12 08:32:38', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(18, 41, 'aa', 'ww', '2024-09-12 08:32:47', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(19, 41, 'aa', 'ww', '2024-09-12 08:32:55', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(20, 41, 'aa', 'ww', '2024-09-12 08:33:06', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(21, 41, 'wqe', 'wrt', '2024-09-12 08:52:51', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(22, 41, 'wqe', 'wrt', '2024-09-12 08:53:41', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(23, 41, 'wqe', 'wrt', '2024-09-12 08:53:48', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(24, 41, 'wqe', 'wrt', '2024-09-12 09:03:14', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(25, 41, 'wqe', 'wrt', '2024-09-12 09:03:27', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(26, 41, 'wqe', 'wrt', '2024-09-12 09:08:06', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(27, 41, 'wqe', 'wrt', '2024-09-12 09:08:16', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(28, 41, 'wqe', 'wrt', '2024-09-12 09:08:20', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(53, 41, 'medy bingwa', 'medy', '2024-09-12 11:43:52', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(54, 42, '11', '11', '2024-09-12 12:43:48', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250),
(55, 42, '111', '11', '2024-09-12 12:44:12', 'open', NULL, NULL, NULL, 0, NULL, NULL, NULL, 250);

-- --------------------------------------------------------

--
-- Table structure for table `discussion_participants`
--

CREATE TABLE `discussion_participants` (
  `discussion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discussion_participants`
--

INSERT INTO `discussion_participants` (`discussion_id`, `user_id`) VALUES
(1, 41),
(6, 41),
(9, 40),
(9, 41),
(10, 40),
(10, 41),
(11, 40),
(11, 41),
(12, 41),
(13, 41),
(14, 41),
(15, 41),
(16, 41),
(17, 41),
(18, 41),
(19, 41),
(20, 41),
(21, 41),
(22, 41),
(23, 41),
(24, 41),
(25, 41),
(26, 41),
(27, 41),
(28, 41),
(53, 41),
(53, 42),
(54, 42),
(55, 42);

-- --------------------------------------------------------

--
-- Table structure for table `email_preferences`
--

CREATE TABLE `email_preferences` (
  `preference_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `newsletter_opt_in` tinyint(1) DEFAULT 0,
  `promotional_emails_opt_in` tinyint(1) DEFAULT 0,
  `product_updates_opt_in` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`news_id`, `admin_id`, `title`, `content`, `created_at`, `updated_at`, `image`) VALUES
(5, NULL, 'AFYA COMPANY LIMITED', 'AFYA WAMETOA KINYAJI KIPYA KINACHOITWA JEMBE', '2024-09-12 07:26:37', '2024-09-12 07:26:37', 'AFYA.jpg'),
(6, NULL, 'MO ENERGY', 'MELT COMPANY LIMITED wametoa toleo jipya la MO ENERG', '2024-09-12 07:36:45', '2024-09-12 07:36:45', 'download (1).jpg'),
(7, NULL, 'GBOOST', 'GBOOST yawaacha watu midomo wazi', '2024-09-12 07:37:21', '2024-09-12 07:37:21', 'download (3).jpg'),
(8, NULL, 'TURBO', 'TURBO imekuwa ikiuliziwa sana mtaani je maswali ni kwamba imeenda wapi?', '2024-09-12 07:38:17', '2024-09-12 07:38:17', 'download.jpg'),
(9, NULL, 'AFYA COMPANY LIMITED', 'maji ya AFYA yamekuwa ni maji bora kwa sasa na kukubalika sana na  wanajamii wengiwa kitanzania', '2024-09-12 07:42:45', '2024-09-12 07:42:45', 'download (5).jpg'),
(10, NULL, 'AFYA  COMPANY LIMITED', 'Maji ya afya yametoa toleo jipya la kinywaji chao', '2024-09-12 07:44:06', '2024-09-12 07:44:06', 'download (4).jpg'),
(11, NULL, 'AFYA COMPAMY LIMITED', 'MAJI YA AFYA YAMEKUWA GUMZO MTAAANI', '2024-09-12 07:46:15', '2024-09-12 07:46:15', 'download (7).jpg'),
(12, NULL, 'cocacola', 'cocacola imetoa toleo jipya', '2024-09-12 08:39:10', '2024-09-12 08:39:10', 'download (8).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications_settings`
--

CREATE TABLE `notifications_settings` (
  `setting_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email_notifications` tinyint(1) DEFAULT 1,
  `sms_notifications` tinyint(1) DEFAULT 0,
  `push_notifications` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders_verification`
--

CREATE TABLE `orders_verification` (
  `verification_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `retailer_id` int(11) NOT NULL,
  `user_verified` tinyint(1) DEFAULT 0,
  `retailer_verified` tinyint(1) DEFAULT 0,
  `verified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `privacy_settings`
--

CREATE TABLE `privacy_settings` (
  `setting_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `profile_visibility` enum('public','private','friends-only') DEFAULT 'private',
  `search_visibility` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `retailer_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_requests`
--

CREATE TABLE `product_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `retailer_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `retailers`
--

CREATE TABLE `retailers` (
  `retailer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `business_name` varchar(100) NOT NULL,
  `business_info` text DEFAULT NULL,
  `storage_capacity` int(11) DEFAULT 500,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `retailers`
--

INSERT INTO `retailers` (`retailer_id`, `user_id`, `business_name`, `business_info`, `storage_capacity`, `created_at`, `updated_at`) VALUES
(1, 27, 'hussein\'s Business', NULL, 500, '2024-08-30 19:26:23', '2024-08-30 19:26:23'),
(2, 29, 'salim\'s Business', NULL, 500, '2024-08-30 19:54:00', '2024-08-30 19:54:00'),
(3, 32, 'MEXY\'s Business', NULL, 500, '2024-09-02 17:59:59', '2024-09-02 17:59:59'),
(4, 34, 'juma\'s Business', NULL, 500, '2024-09-02 19:45:28', '2024-09-02 19:45:28'),
(5, 35, 'ahmada\'s Business', NULL, 500, '2024-09-03 01:48:34', '2024-09-03 01:48:34'),
(6, 37, 'dodo\'s Business', NULL, 500, '2024-09-03 05:45:57', '2024-09-03 05:45:57'),
(7, 38, 'yuwena\'s Business', NULL, 500, '2024-09-03 08:13:18', '2024-09-03 08:13:18'),
(8, 39, 'sgr\'s Business', NULL, 500, '2024-09-09 13:23:00', '2024-09-09 13:23:00'),
(9, 40, 'myshop\'s Business', NULL, 500, '2024-09-11 06:54:00', '2024-09-11 06:54:00'),
(10, 41, 'mm\'s Business', NULL, 500, '2024-09-11 17:01:10', '2024-09-11 17:01:10'),
(11, 42, 'kaka\'s Business', NULL, 500, '2024-09-12 12:29:29', '2024-09-12 12:29:29'),
(12, 43, 'kayla\'s Business', NULL, 500, '2024-09-12 13:35:48', '2024-09-12 13:35:48'),
(13, 44, 'm\'s Business', NULL, 500, '2024-09-13 05:51:07', '2024-09-13 05:51:07');

-- --------------------------------------------------------

--
-- Table structure for table `retailer_stock`
--

CREATE TABLE `retailer_stock` (
  `retailer_id` int(11) NOT NULL,
  `total_capacity` int(11) NOT NULL,
  `used_capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `retailer_stock`
--

INSERT INTO `retailer_stock` (`retailer_id`, `total_capacity`, `used_capacity`) VALUES
(39, 1000, 0),
(40, 1000, 0),
(41, 1000, 0),
(43, 1000, 0),
(44, 1000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(3, 'Admin'),
(2, 'Retailer'),
(1, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `sales_forecasting`
--

CREATE TABLE `sales_forecasting` (
  `id` int(11) NOT NULL,
  `retailer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `forecast_date` date NOT NULL,
  `predicted_sales` int(11) NOT NULL,
  `actual_sales` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `setting_key` varchar(100) DEFAULT NULL,
  `setting_value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `action` enum('deposit','withdraw') NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `amount`, `action`, `payment_method`, `transaction_date`, `status`) VALUES
(1, 44, 1111.00, 'deposit', 'Mobile Payment', '2024-09-13 06:21:39', 'pending'),
(2, 44, 11.00, 'deposit', 'Visa', '2024-09-13 06:21:50', 'pending'),
(3, 44, 222.00, 'deposit', 'Visa', '2024-09-13 06:22:32', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `transportation_requests`
--

CREATE TABLE `transportation_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `retailer_id` int(11) DEFAULT NULL,
  `request_details` text DEFAULT NULL,
  `request_status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `two_factor_enabled` tinyint(1) DEFAULT 0,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role_id`, `created_at`, `updated_at`, `two_factor_enabled`, `two_factor_secret`, `profile_picture`, `balance`) VALUES
(19, 'mama', 'mama@gmail.com', 'mama@003', 1, '2024-08-30 18:44:24', '2024-08-30 18:44:24', 0, NULL, NULL, 0.00),
(20, 'ahmed003', 'ahmed003@gmail.com', 'ahmed003', 1, '2024-08-30 18:54:49', '2024-08-30 18:54:49', 0, NULL, NULL, 0.00),
(24, 'ahmed0031', 'ahmed0031@gmail.com', 'ahmed003', 1, '2024-08-30 18:58:30', '2024-08-30 18:58:30', 0, NULL, NULL, 0.00),
(25, 'ahmed0032', 'ahmed0032@gmail.com', 'ahmed0032', 1, '2024-08-30 18:59:55', '2024-08-30 18:59:55', 0, NULL, NULL, 0.00),
(26, 'ahmed0033', 'ahmed0033@gmail.com', '$2y$10$f.X8/qKgxCqqpTgi2/roPeWIgaONmKddfNEJQ8gJ4eHx8ABPf2jZK', 1, '2024-08-30 19:05:55', '2024-08-30 19:05:55', 0, NULL, NULL, 0.00),
(27, 'hussein', 'hussein@gmail.com', '$2y$10$s.F8sqyyIFfbqgnagBZPcO1w8PTNcmFN0Lvh.RndSbRWghhDpGh9q', 2, '2024-08-30 19:26:23', '2024-08-30 19:26:23', 0, NULL, NULL, 0.00),
(28, 'mohamed', 'mohamed@gmail.com', '$2y$10$vUubfHysy/ceVJZ4g307DuzDIrOHB4hADEdTHYR3k7VHXp6m9KG8C', 1, '2024-08-30 19:53:10', '2024-08-30 19:53:10', 0, NULL, NULL, 0.00),
(29, 'salim', 'salim@gmail.com', '$2y$10$gsSw.GRykFcS9XqLXx07H.nvsBkCzui7uMOvNr0yJ9mO76ymV4NVO', 2, '2024-08-30 19:54:00', '2024-08-31 03:26:41', 0, NULL, NULL, 0.00),
(30, 'saidi', 'saidi@gmail.com', '$2y$10$CZZ8KOWBDB.GWy/eMKF1R.bXHm5AEaEM0lUKeb4krFg3G8/R.q9PO', 1, '2024-08-31 00:47:28', '2024-08-31 00:47:28', 0, NULL, NULL, 0.00),
(31, 'ester', 'ester@gmail.com', '$2y$10$0sXTZnP6BujZzMCXn6K7xuBrjc8AL270vdabUNrEtOKpK93pArKLG', 1, '2024-08-31 04:49:02', '2024-08-31 04:49:02', 0, NULL, NULL, 0.00),
(32, 'MEXY', 'meshackemiliantemu@gmail.com', '$2y$10$lfCHihu/FEQbrkn5CUOzT.DTVmcjHG3iIxGNZGSwj5jHhHf2d4RzW', 2, '2024-09-02 17:59:59', '2024-09-02 17:59:59', 0, NULL, NULL, 0.00),
(33, 'kau', 'kau@gmail.com', '$2y$10$s/FOabvzl0NWqggo84rZk.i6vu7wSMzmYATAb1dyAO3AIbzRdNJPq', 1, '2024-09-02 18:26:41', '2024-09-02 18:26:41', 0, NULL, NULL, 0.00),
(34, 'juma', 'juma@gmail.com', '$2y$10$YoMUo.c8HnQiS6gGkxP/Ku/.GzEtLW5/8i9sk5PQ7DWIdyDGLmOmq', 2, '2024-09-02 19:45:28', '2024-09-02 19:45:28', 0, NULL, NULL, 0.00),
(35, 'ahmada', 'ahmada@gmail.com', '$2y$10$uyqQyM67nrDaTXFrHDUuquoiPzkOW9ohmoahRBpkgVKZSKYKVfwge', 2, '2024-09-03 01:48:34', '2024-09-03 01:48:34', 0, NULL, NULL, 0.00),
(36, 'yuyu', 'yuyu@gmail.com', '$2y$10$3Ldt62q2yq5WebI1rxYtSu/.BW9YAzu20LcFexizlcN5CnXgyulIy', 1, '2024-09-03 05:06:30', '2024-09-03 05:06:30', 0, NULL, NULL, 0.00),
(37, 'dodo', 'dodo@gmail.com', '$2y$10$L/H5iUOT1CkbmghJZuvsE.zfe.QSU4odC6T5pbzaJIA.vd9bbAjxG', 2, '2024-09-03 05:45:57', '2024-09-03 05:45:57', 0, NULL, NULL, 0.00),
(38, 'yuwena', 'yuwena@gmail.com', '$2y$10$FNgmAavxtfms1DJiRsy7i.VUk8XXGoFPEFUuxzgwtC4y8iZytXSjO', 2, '2024-09-03 08:13:18', '2024-09-03 08:27:11', 0, NULL, '3685.jpg', 0.00),
(39, 'sgr', 'sgr@gmail.com', '$2y$10$8YdraWkZTgKVLcrCD.vMfOMrvUuHZQ8U7aE/YfibAEbhleChQlccK', 2, '2024-09-09 13:23:00', '2024-09-09 13:23:00', 0, NULL, NULL, 0.00),
(40, 'myshop', 'myshop@gmail.com', '$2y$10$.sgINavUZhFrNoJDUGJhTOILFqLlCaeJK8yThatdZSDK4xDHaKvPO', 2, '2024-09-11 06:54:00', '2024-09-11 06:54:00', 0, NULL, NULL, 0.00),
(41, 'mm', 'm@gmail.com', '$2y$10$fLYvKlEsIz5feqaLraRCt.S57SpqlaynlzlQFRW4sbYCWVnV1y1pO', 2, '2024-09-11 17:01:10', '2024-09-12 12:25:00', 0, NULL, NULL, 1054.00),
(42, 'kaka', 'k@gmail.com', '$2y$10$EaY7raWLIfaQW5n9Laznh.QvVYmkNdi6a3X.SBUPAjmCMvs0fxt3u', 2, '2024-09-12 12:29:29', '2024-09-12 12:48:18', 0, NULL, 'download (8).jpg', 1555.00),
(43, '', '', '$2y$10$gM3POrf5Fja6LW0Xwx7WQ.vxqy6c0Pix7BKJhavH3Vdtq5./mADqW', 2, '2024-09-12 13:35:48', '2024-09-12 14:52:09', 0, NULL, 'download (8).jpg', 222.00),
(44, 'm', 'me@gmail.com', '$2y$10$9fYxE4KpRFw3uIrz9bpQCOOTimmzxDYuv7trUHFGSPnZ2Wx9pmwF6', 2, '2024-09-13 05:51:07', '2024-09-13 06:21:39', 0, NULL, NULL, 1111.00);

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_id` varchar(50) NOT NULL,
  `current_location` point DEFAULT NULL,
  `status` enum('active','inactive','in_transit') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `warehouse_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ai_queries`
--
ALTER TABLE `ai_queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `retailer_id` (`retailer_id`);

--
-- Indexes for table `analysis`
--
ALTER TABLE `analysis`
  ADD PRIMARY KEY (`analysis_id`),
  ADD KEY `retailer_id` (`retailer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `retailer_id` (`retailer_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `discussion_id` (`discussion_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comment_reactions`
--
ALTER TABLE `comment_reactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_reaction` (`comment_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `retailer_id` (`retailer_id`);

--
-- Indexes for table `discussionmessages`
--
ALTER TABLE `discussionmessages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `discussion_id` (`discussion_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `discussionrooms`
--
ALTER TABLE `discussionrooms`
  ADD PRIMARY KEY (`discussion_id`),
  ADD KEY `retailer_id` (`retailer_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`discussion_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `discussion_participants`
--
ALTER TABLE `discussion_participants`
  ADD PRIMARY KEY (`discussion_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `email_preferences`
--
ALTER TABLE `email_preferences`
  ADD PRIMARY KEY (`preference_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications_settings`
--
ALTER TABLE `notifications_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders_verification`
--
ALTER TABLE `orders_verification`
  ADD PRIMARY KEY (`verification_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `retailer_id` (`retailer_id`);

--
-- Indexes for table `privacy_settings`
--
ALTER TABLE `privacy_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `retailer_id` (`retailer_id`);

--
-- Indexes for table `product_requests`
--
ALTER TABLE `product_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `retailer_id` (`retailer_id`);

--
-- Indexes for table `retailers`
--
ALTER TABLE `retailers`
  ADD PRIMARY KEY (`retailer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `retailer_stock`
--
ALTER TABLE `retailer_stock`
  ADD PRIMARY KEY (`retailer_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `sales_forecasting`
--
ALTER TABLE `sales_forecasting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `retailer_id` (`retailer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transportation_requests`
--
ALTER TABLE `transportation_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`warehouse_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ai_queries`
--
ALTER TABLE `ai_queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `analysis`
--
ALTER TABLE `analysis`
  MODIFY `analysis_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `comment_reactions`
--
ALTER TABLE `comment_reactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discussionmessages`
--
ALTER TABLE `discussionmessages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discussionrooms`
--
ALTER TABLE `discussionrooms`
  MODIFY `discussion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `discussion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `email_preferences`
--
ALTER TABLE `email_preferences`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications_settings`
--
ALTER TABLE `notifications_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders_verification`
--
ALTER TABLE `orders_verification`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `privacy_settings`
--
ALTER TABLE `privacy_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_requests`
--
ALTER TABLE `product_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `retailers`
--
ALTER TABLE `retailers`
  MODIFY `retailer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales_forecasting`
--
ALTER TABLE `sales_forecasting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transportation_requests`
--
ALTER TABLE `transportation_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_queries`
--
ALTER TABLE `ai_queries`
  ADD CONSTRAINT `ai_queries_ibfk_1` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`);

--
-- Constraints for table `analysis`
--
ALTER TABLE `analysis`
  ADD CONSTRAINT `analysis_ibfk_1` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`),
  ADD CONSTRAINT `analysis_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `campaigns_ibfk_1` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`discussion_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `comment_reactions`
--
ALTER TABLE `comment_reactions`
  ADD CONSTRAINT `comment_reactions_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`),
  ADD CONSTRAINT `comment_reactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  ADD CONSTRAINT `customer_feedback_ibfk_1` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`),
  ADD CONSTRAINT `fk_retailer` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`);

--
-- Constraints for table `discussionmessages`
--
ALTER TABLE `discussionmessages`
  ADD CONSTRAINT `discussionmessages_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `discussionrooms` (`discussion_id`),
  ADD CONSTRAINT `discussionmessages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `discussionrooms`
--
ALTER TABLE `discussionrooms`
  ADD CONSTRAINT `discussionrooms_ibfk_1` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`),
  ADD CONSTRAINT `discussionrooms_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `discussions`
--
ALTER TABLE `discussions`
  ADD CONSTRAINT `discussions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `discussion_participants`
--
ALTER TABLE `discussion_participants`
  ADD CONSTRAINT `discussion_participants_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`discussion_id`),
  ADD CONSTRAINT `discussion_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `email_preferences`
--
ALTER TABLE `email_preferences`
  ADD CONSTRAINT `email_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications_settings`
--
ALTER TABLE `notifications_settings`
  ADD CONSTRAINT `notifications_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders_verification`
--
ALTER TABLE `orders_verification`
  ADD CONSTRAINT `orders_verification_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `orders_verification_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_verification_ibfk_3` FOREIGN KEY (`retailer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `privacy_settings`
--
ALTER TABLE `privacy_settings`
  ADD CONSTRAINT `privacy_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`);

--
-- Constraints for table `product_requests`
--
ALTER TABLE `product_requests`
  ADD CONSTRAINT `product_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `product_requests_ibfk_2` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`);

--
-- Constraints for table `retailers`
--
ALTER TABLE `retailers`
  ADD CONSTRAINT `retailers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `sales_forecasting`
--
ALTER TABLE `sales_forecasting`
  ADD CONSTRAINT `sales_forecasting_ibfk_1` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`),
  ADD CONSTRAINT `sales_forecasting_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `warehouses_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
