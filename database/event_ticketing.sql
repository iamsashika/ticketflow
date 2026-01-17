-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 17, 2026 at 07:45 PM
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
-- Database: `event_ticketing`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`, `is_deleted`) VALUES
(1, 'Conference', 'Professional conferences and seminars', '2026-01-14 17:56:53', NULL, 0),
(2, 'cConcert', 'Music concerts and shows', '2026-01-14 17:56:53', '2026-01-17 07:49:31', 0),
(3, 'Workshop', 'Educational workshops and training sessions', '2026-01-14 17:56:53', NULL, 0),
(4, 'Sports', 'Sporting events and competitions', '2026-01-14 17:56:53', NULL, 0),
(5, 'Festival', 'Cultural festivals and celebrations', '2026-01-14 17:56:53', NULL, 0),
(6, 'Exhibition', 'Art exhibitions and displays', '2026-01-14 17:56:53', NULL, 0),
(7, 'test', NULL, '2026-01-16 11:31:53', '2026-01-16 18:07:39', 1),
(8, 'test', NULL, '2026-01-16 17:38:04', '2026-01-16 17:45:20', 1),
(9, 'test', NULL, '2026-01-16 17:38:32', '2026-01-16 17:45:17', 1),
(10, 'test', NULL, '2026-01-16 17:39:32', '2026-01-16 17:45:12', 1),
(11, 'x', NULL, '2026-01-17 07:48:57', '2026-01-17 07:49:00', 1),
(12, 'x', NULL, '2026-01-17 07:49:07', '2026-01-17 07:49:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `venue` varchar(200) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `ticket_price` decimal(10,2) DEFAULT 0.00,
  `capacity` int(11) NOT NULL,
  `available_seats` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed','cancelled') DEFAULT 'upcoming',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `category_id`, `title`, `description`, `venue`, `event_date`, `event_time`, `ticket_price`, `capacity`, `available_seats`, `image`, `status`, `created_by`, `created_at`, `updated_at`, `is_deleted`) VALUES
(1, 1, 'Tech Conference 2026', 'Annual technology conference featuring latest innovations and trends in tech industry.', 'Colombo Conference Center', '2026-02-15', '09:00:00', 5000.00, 500, 500, NULL, 'upcoming', 1, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(2, 2, 'Music Festival Sri Lanka', 'Three-day music festival featuring local and international artists.', 'Galle Face Green', '2026-03-20', '18:00:00', 2500.00, 1000, 1000, NULL, 'upcoming', 1, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(3, 3, 'Digital Marketing Workshop', 'Hands-on workshop on digital marketing strategies and tools.', 'Hotel Galadari, Colombo', '2026-02-10', '10:00:00', 3000.00, 100, 100, NULL, 'upcoming', 1, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(4, 4, 'Cricket Tournament', 'Inter-school cricket tournament finals.', 'R. Premadasa Stadium', '2026-02-25', '14:00:00', 1000.00, 5000, 5000, NULL, 'upcoming', 1, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(5, 5, 'Vesak Festival Celebration', 'Traditional Vesak festival celebration with cultural performances.', 'Gangaramaya Temple', '2026-05-12', '17:00:00', 0.00, 2000, 2000, NULL, 'upcoming', 1, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(6, 2, 'SHC', 'fasdfas', 'colombo', '2026-01-17', '05:39:00', NULL, 20, 20, 'event_1768424868_696805a4e2b38.webp', 'upcoming', 1, '2026-01-14 21:07:48', '2026-01-14 21:07:48', 0),
(7, 1, 'abc', 'abc description', 'Colombo City Center', '2026-01-16', '12:14:00', NULL, 600, 600, 'default.jpg', 'upcoming', 2, '2026-01-16 15:46:17', '2026-01-16 15:46:17', 0),
(8, 2, 'e1', 'ed', 'v1', '2026-01-16', '23:30:00', NULL, 600, 600, 'default.jpg', 'upcoming', 2, '2026-01-16 16:00:38', '2026-01-16 18:01:52', 1),
(9, 7, 'dfasfdsaffdsfdsfsfas', 'fdafdas', 'fdasfads', '2026-01-17', '05:11:00', NULL, 100, 100, 'default.jpg', 'upcoming', 2, '2026-01-16 18:07:27', '2026-01-16 18:07:27', 0),
(10, 1, 'x', 'xx', 'x', '2026-01-17', '20:52:00', NULL, 60, 60, 'default.jpg', 'upcoming', 2, '2026-01-17 09:23:17', '2026-01-17 09:23:17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ticket_type_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `ticket_number` varchar(50) NOT NULL,
  `ticket_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('confirmed','cancelled') DEFAULT 'confirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `ticket_type_id`, `quantity`, `ticket_number`, `ticket_price`, `registration_date`, `status`) VALUES
(1, 2, 5, 9, 4, 'EVT-5-2-9-1768414271', 0.00, '2026-01-16 13:39:04', 'confirmed'),
(2, 2, 6, 10, 3, 'EVT-6-2-10-1768425217', 0.00, '2026-01-16 13:24:51', 'cancelled'),
(3, 3, 6, 10, 2, 'EVT-6-3-10-1768457761', 0.00, '2026-01-15 06:16:01', 'confirmed'),
(4, 3, 3, 6, 1, 'EVT-3-3-6-1768471280', 0.00, '2026-01-15 10:01:20', 'confirmed'),
(5, 2, 3, 6, 1, 'EVT-3-2-6-1768567681', 0.00, '2026-01-16 12:48:01', 'cancelled'),
(6, 2, 1, 1, 2, 'EVT-1-2-1-1768569194', 0.00, '2026-01-16 13:13:14', 'cancelled'),
(7, 2, 1, 3, 1, 'EVT-1-2-3-1768569195', 0.00, '2026-01-16 13:13:15', 'cancelled'),
(8, 4, 4, 7, 1, 'EVT-4-4-7-1768576474', 0.00, '2026-01-16 15:14:34', 'confirmed'),
(9, 4, 4, 8, 2, 'EVT-4-4-8-1768576474', 0.00, '2026-01-16 15:14:34', 'confirmed'),
(10, 4, 6, 10, 2, 'EVT-6-4-10-1768592843', 0.00, '2026-01-16 19:47:23', 'confirmed'),
(11, 6, 10, 18, 5, 'EVT-10-6-18-1768642008', 0.00, '2026-01-17 09:31:21', 'confirmed'),
(12, 6, 5, 9, 1, 'EVT-5-6-9-1768675248', 0.00, '2026-01-17 18:40:48', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `capacity` int(11) NOT NULL,
  `available_seats` int(11) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_types`
--

INSERT INTO `ticket_types` (`id`, `event_id`, `name`, `description`, `price`, `capacity`, `available_seats`, `display_order`, `created_at`, `updated_at`, `is_deleted`) VALUES
(1, 1, 'VVIP', NULL, 10000.00, 50, 50, 1, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(2, 1, 'VIP', NULL, 7500.00, 100, 100, 2, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(3, 1, 'Standard', NULL, 5000.00, 350, 350, 3, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(4, 2, 'Stage Front', NULL, 5000.00, 200, 200, 1, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(5, 2, 'General Admission', NULL, 2500.00, 800, 800, 2, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(6, 3, 'Workshop Entry', NULL, 3000.00, 100, 100, 1, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(7, 4, 'Grand Stand', NULL, 2000.00, 500, 500, 1, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(8, 4, 'Normal Stand', NULL, 1000.00, 4500, 4500, 2, '2026-01-14 17:56:53', '2026-01-14 17:56:53', 0),
(9, 5, 'Free Entry', NULL, 0.00, 2000, 1998, 1, '2026-01-14 17:56:53', '2026-01-14 18:11:11', 0),
(10, 6, 'General Admission', '', 200.00, 20, 20, 0, '2026-01-14 21:07:48', '2026-01-14 21:07:48', 0),
(11, 7, 'A', '', 0.00, 100, 100, 0, '2026-01-16 15:46:17', '2026-01-16 15:46:17', 0),
(12, 7, 'B', '', 0.00, 200, 200, 1, '2026-01-16 15:46:17', '2026-01-16 15:46:17', 0),
(13, 7, 'A', '', 0.00, 300, 300, 3, '2026-01-16 15:46:17', '2026-01-16 15:46:17', 0),
(14, 8, 'T1', '', 0.00, 100, 100, 0, '2026-01-16 16:00:38', '2026-01-16 16:00:38', 0),
(15, 8, 'T2', '', 200.00, 200, 200, 2, '2026-01-16 16:00:38', '2026-01-16 16:00:38', 0),
(16, 8, 'T1', '', 300.00, 300, 300, 3, '2026-01-16 16:00:38', '2026-01-16 16:00:38', 0),
(17, 9, 'General Admission', '', 100.00, 100, 100, 0, '2026-01-16 18:07:27', '2026-01-16 18:07:27', 0),
(18, 10, 'gold', '', 100.00, 10, 10, 0, '2026-01-17 09:23:17', '2026-01-17 09:23:17', 0),
(19, 10, 'silver', '', 200.00, 20, 20, 2, '2026-01-17 09:23:17', '2026-01-17 09:23:17', 0),
(20, 10, 'Platinum', '', 300.00, 30, 30, 3, '2026-01-17 09:23:17', '2026-01-17 09:23:17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `role`, `created_at`, `updated_at`, `is_deleted`) VALUES
(1, 'Admin', 'User', 'admin@example.com', '0771234567', '$2y$10$/fV2vnSEMJUgdsIZIcxBc.QwYkfALM.azeh3rRndadxfWOxDYeWeG', 'admin', '2026-01-14 17:56:53', '2026-01-14 18:14:55', 0),
(2, 'Sashika', 'Chandrasena', 'iamsashika@hotmail.com', '0779928022', '$2y$10$/fV2vnSEMJUgdsIZIcxBc.QwYkfALM.azeh3rRndadxfWOxDYeWeG', 'admin', '2026-01-14 18:09:05', '2026-01-16 15:40:47', 0),
(3, 'uoc', 'uoc', 'uoc@ucsc.lk', '077123456', '$2y$10$gqS9IIxgW8c/hJiXwH.WMerKGftvVE6ZUBkGqty6eJR7xyi95naPm', 'user', '2026-01-15 06:15:18', '2026-01-15 06:15:18', 0),
(4, 'Darshana', 'Chandrasena', 'darshana@hotmail.com', '0779928023', '$2y$10$ck7DpzT9ZifhG5VhbowaPeCuljksBmHgNcc0s1dXmt3wO6o1Ge0E6', 'user', '2026-01-16 15:12:52', '2026-01-16 15:26:19', 0),
(5, 'New', 'Admin', 'newadmin@hotmail.com', '0779928024', '$2y$10$ncfsAw5eWL3UVrCW3nb.sO8amCeZ7RZKSLBt8UuOnLd7I.MlnSgUG', 'admin', '2026-01-16 19:05:22', '2026-01-16 19:05:48', 1),
(6, 'testtt', 'user', 'testuser@hotmail.com', '0779928025', '$2y$10$AQ4F/9smxHvV3unBK2tPH.Ed1kMlxvXLtJgs06.MMepUkuFkjSLbK', 'user', '2026-01-16 19:06:36', '2026-01-16 19:08:06', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_event_date` (`event_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_category` (`category_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_number` (`ticket_number`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_event` (`event_id`),
  ADD KEY `idx_ticket_type` (`ticket_type_id`),
  ADD KEY `idx_ticket` (`ticket_number`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event` (`event_id`),
  ADD KEY `idx_order` (`display_order`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrations_ibfk_3` FOREIGN KEY (`ticket_type_id`) REFERENCES `ticket_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD CONSTRAINT `ticket_types_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
