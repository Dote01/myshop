-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2024 at 06:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`news_id`, `admin_id`, `title`, `content`, `created_at`, `updated_at`) VALUES
(1, 29, 'bbb', 'kkkk', '2024-08-30 21:00:00', '2024-08-31 03:17:20'),
(2, 29, 'bbb', 'kkkk', '2024-08-30 21:00:00', '2024-08-31 03:17:41'),
(3, 31, 'MMAHINDI', 'MAHINDI YAMEPANDA BEI KWA ASILIMA 13', '2024-09-01 21:00:00', '2024-09-02 17:57:55');

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
  `category_id` int(11) DEFAULT NULL
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
(7, 38, 'yuwena\'s Business', NULL, 500, '2024-09-03 08:13:18', '2024-09-03 08:13:18');

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
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role_id`, `created_at`, `updated_at`, `two_factor_enabled`, `two_factor_secret`, `profile_picture`) VALUES
(19, 'mama', 'mama@gmail.com', 'mama@003', 1, '2024-08-30 18:44:24', '2024-08-30 18:44:24', 0, NULL, NULL),
(20, 'ahmed003', 'ahmed003@gmail.com', 'ahmed003', 1, '2024-08-30 18:54:49', '2024-08-30 18:54:49', 0, NULL, NULL),
(24, 'ahmed0031', 'ahmed0031@gmail.com', 'ahmed003', 1, '2024-08-30 18:58:30', '2024-08-30 18:58:30', 0, NULL, NULL),
(25, 'ahmed0032', 'ahmed0032@gmail.com', 'ahmed0032', 1, '2024-08-30 18:59:55', '2024-08-30 18:59:55', 0, NULL, NULL),
(26, 'ahmed0033', 'ahmed0033@gmail.com', '$2y$10$f.X8/qKgxCqqpTgi2/roPeWIgaONmKddfNEJQ8gJ4eHx8ABPf2jZK', 1, '2024-08-30 19:05:55', '2024-08-30 19:05:55', 0, NULL, NULL),
(27, 'hussein', 'hussein@gmail.com', '$2y$10$s.F8sqyyIFfbqgnagBZPcO1w8PTNcmFN0Lvh.RndSbRWghhDpGh9q', 2, '2024-08-30 19:26:23', '2024-08-30 19:26:23', 0, NULL, NULL),
(28, 'mohamed', 'mohamed@gmail.com', '$2y$10$vUubfHysy/ceVJZ4g307DuzDIrOHB4hADEdTHYR3k7VHXp6m9KG8C', 1, '2024-08-30 19:53:10', '2024-08-30 19:53:10', 0, NULL, NULL),
(29, 'salim', 'salim@gmail.com', '$2y$10$gsSw.GRykFcS9XqLXx07H.nvsBkCzui7uMOvNr0yJ9mO76ymV4NVO', 2, '2024-08-30 19:54:00', '2024-08-31 03:26:41', 0, NULL, NULL),
(30, 'saidi', 'saidi@gmail.com', '$2y$10$CZZ8KOWBDB.GWy/eMKF1R.bXHm5AEaEM0lUKeb4krFg3G8/R.q9PO', 1, '2024-08-31 00:47:28', '2024-08-31 00:47:28', 0, NULL, NULL),
(31, 'ester', 'ester@gmail.com', '$2y$10$0sXTZnP6BujZzMCXn6K7xuBrjc8AL270vdabUNrEtOKpK93pArKLG', 1, '2024-08-31 04:49:02', '2024-08-31 04:49:02', 0, NULL, NULL),
(32, 'MEXY', 'meshackemiliantemu@gmail.com', '$2y$10$lfCHihu/FEQbrkn5CUOzT.DTVmcjHG3iIxGNZGSwj5jHhHf2d4RzW', 2, '2024-09-02 17:59:59', '2024-09-02 17:59:59', 0, NULL, NULL),
(33, 'kau', 'kau@gmail.com', '$2y$10$s/FOabvzl0NWqggo84rZk.i6vu7wSMzmYATAb1dyAO3AIbzRdNJPq', 1, '2024-09-02 18:26:41', '2024-09-02 18:26:41', 0, NULL, NULL),
(34, 'juma', 'juma@gmail.com', '$2y$10$YoMUo.c8HnQiS6gGkxP/Ku/.GzEtLW5/8i9sk5PQ7DWIdyDGLmOmq', 2, '2024-09-02 19:45:28', '2024-09-02 19:45:28', 0, NULL, NULL),
(35, 'ahmada', 'ahmada@gmail.com', '$2y$10$uyqQyM67nrDaTXFrHDUuquoiPzkOW9ohmoahRBpkgVKZSKYKVfwge', 2, '2024-09-03 01:48:34', '2024-09-03 01:48:34', 0, NULL, NULL),
(36, 'yuyu', 'yuyu@gmail.com', '$2y$10$3Ldt62q2yq5WebI1rxYtSu/.BW9YAzu20LcFexizlcN5CnXgyulIy', 1, '2024-09-03 05:06:30', '2024-09-03 05:06:30', 0, NULL, NULL),
(37, 'dodo', 'dodo@gmail.com', '$2y$10$L/H5iUOT1CkbmghJZuvsE.zfe.QSU4odC6T5pbzaJIA.vd9bbAjxG', 2, '2024-09-03 05:45:57', '2024-09-03 05:45:57', 0, NULL, NULL),
(38, 'yuwena', 'yuwena@gmail.com', '$2y$10$FNgmAavxtfms1DJiRsy7i.VUk8XXGoFPEFUuxzgwtC4y8iZytXSjO', 2, '2024-09-03 08:13:18', '2024-09-03 08:27:11', 0, NULL, '3685.jpg');

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
-- AUTO_INCREMENT for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `email_preferences`
--
ALTER TABLE `email_preferences`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_requests`
--
ALTER TABLE `product_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `retailers`
--
ALTER TABLE `retailers`
  MODIFY `retailer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT for table `transportation_requests`
--
ALTER TABLE `transportation_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
-- Constraints for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  ADD CONSTRAINT `customer_feedback_ibfk_1` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`);

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
