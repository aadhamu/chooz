-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 02:19 AM
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
-- Database: `chooz`
--

-- --------------------------------------------------------

--
-- Table structure for table `nominees_details`
--

CREATE TABLE `nominees_details` (
  `id` int(11) NOT NULL,
  `nominee_username` varchar(255) NOT NULL,
  `poll_id` int(11) DEFAULT NULL,
  `nominee_image` varchar(255) NOT NULL,
  `nominee_bio` text NOT NULL,
  `nominee_statement` text NOT NULL,
  `nominee_qualifications` text NOT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nominees_details`
--

INSERT INTO `nominees_details` (`id`, `nominee_username`, `poll_id`, `nominee_image`, `nominee_bio`, `nominee_statement`, `nominee_qualifications`, `linkedin`, `twitter`, `instagram`, `created_at`) VALUES
(8, 'qwe2#', 37, '67514b8993614.jpg', 'dvckj', 'asdfl', 'sdkn', '', '', '', '2024-12-05 06:43:21'),
(9, 'emmysmallz@7', 37, '67514b8993614.jpg', 'fkdn', 'sdfkjvndf', 'dfcvm dc', NULL, NULL, NULL, '2024-12-10 10:14:54'),
(10, 'adamu.bm3', 40, '6760d583edfab.jpg', 'asdfghjk', 'fsdgfgjhk', 'asdftgyui', '', '', '', '2024-12-17 01:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `username`, `poll_id`, `message`, `created_at`, `is_read`) VALUES
(2, 'qwe2#', 37, 'Congratulations! You have been added as a participant in the poll titled face of lincoln. Voting starts on 2024-12-04. Best of luck!', '2024-12-04 19:03:19', 1),
(3, 'emmysmallz@7', 37, 'Congratulations! You have been added as a participant in the poll titled face of lincoln. Voting starts on 2024-12-04. Best of luck!', '2024-12-04 19:03:19', 1),
(4, 'qwe2#', 40, 'Congratulations! You have been added as a participant in the poll titled Best developer. Best of luck!', '2024-12-17 01:31:13', 1),
(5, 'adamu.bm3', 40, 'Congratulations! You have been added as a participant in the poll titled Best developer. Best of luck!', '2024-12-17 01:31:13', 1),
(6, 'qwe2#', 41, 'Congratulations! You have been added as a participant in the poll titled Who is the best developer. Best of luck!', '2024-12-17 01:34:03', 0),
(7, 'adamu.bm3', 41, 'Congratulations! You have been added as a participant in the poll titled Who is the best developer. Best of luck!', '2024-12-17 01:34:03', 0);

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE `polls` (
  `id` int(11) NOT NULL,
  `poll_creator` int(11) NOT NULL,
  `poll_title` varchar(255) NOT NULL,
  `poll_description` text DEFAULT NULL,
  `poll_question` varchar(255) NOT NULL,
  `polling_method` enum('single_choice','multiple_choice') NOT NULL,
  `polling_payment` enum('poll-participant','poll-creator','free_vote') NOT NULL,
  `voter_pay_amount` decimal(10,2) DEFAULT NULL,
  `creator_polling_type` enum('bulk_voting','per_vote') DEFAULT NULL,
  `price_per_vote` decimal(10,2) DEFAULT NULL,
  `poll_package` enum('50','100','200') DEFAULT NULL,
  `poll_image` varchar(255) NOT NULL,
  `poll_visibility` enum('public','private') NOT NULL,
  `poll_category` enum('sports','politics','entertainment','education','other') NOT NULL,
  `anonymous_poll` tinyint(1) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_username` tinyint(1) DEFAULT 0,
  `poll_key` varchar(255) DEFAULT NULL,
  `poll_status` enum('not_started','ongoing','completed') DEFAULT 'not_started',
  `polling_type` enum('single_vote','multiple_vote') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `polls`
--

INSERT INTO `polls` (`id`, `poll_creator`, `poll_title`, `poll_description`, `poll_question`, `polling_method`, `polling_payment`, `voter_pay_amount`, `creator_polling_type`, `price_per_vote`, `poll_package`, `poll_image`, `poll_visibility`, `poll_category`, `anonymous_poll`, `start_date`, `end_date`, `created_at`, `updated_at`, `is_username`, `poll_key`, `poll_status`, `polling_type`) VALUES
(37, 30, 'face of lincoln', 'this is my name', 'dfjb', 'multiple_choice', 'poll-participant', 78.00, '', 0.00, '', 'poll_image_6750a7774dfcd4.48909630.jpg', 'public', 'entertainment', 1, '2024-12-04 10:56:00', '2024-12-10 10:56:00', '2024-12-04 19:03:19', '2024-12-12 07:12:17', 1, NULL, 'ongoing', 'multiple_vote'),
(39, 30, 'vxbc', 'dfg', 'sdfg', 'single_choice', 'free_vote', 0.00, '', 0.00, '', 'poll_image_675410c312b745.11382106.jpg', 'public', 'politics', 0, '2024-12-07 01:09:00', '2024-12-17 01:09:00', '2024-12-07 09:09:23', '2024-12-11 22:11:43', 0, NULL, 'ongoing', 'multiple_vote'),
(40, 32, 'Best developer', 'looking out for the best developer in lincoln college', 'Who is the best developer in lincoln', 'single_choice', 'free_vote', 0.00, '', 0.00, '', 'poll_image_6760d461141632.10882702.png', 'private', 'education', 0, '2024-12-16 17:29:00', '2024-12-20 17:29:00', '2024-12-17 01:31:13', '2024-12-17 01:36:54', 1, 'NYIP', 'ongoing', 'single_vote'),
(41, 32, 'Who is the best developer', 'fghj', 'Who is the best developer in lincoln', 'single_choice', 'free_vote', 0.00, '', 0.00, '', 'poll_image_6760d50ab91a03.45684139.png', 'private', 'education', 0, '2024-12-16 17:33:00', '2024-12-20 17:33:00', '2024-12-17 01:34:02', '2024-12-17 01:34:02', 1, '7OWD', '', 'single_vote');

-- --------------------------------------------------------

--
-- Table structure for table `poll_options`
--

CREATE TABLE `poll_options` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) DEFAULT NULL,
  `option_text` varchar(255) NOT NULL,
  `is_username` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poll_options`
--

INSERT INTO `poll_options` (`id`, `poll_id`, `option_text`, `is_username`) VALUES
(21, 37, 'qwe2#,emmysmallz@7', 1),
(23, 39, 'd,f', 0),
(24, 40, 'qwe2#,adamu.bm3', 1),
(25, 41, 'qwe2#,adamu.bm3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `organization` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `email`, `username`, `password`, `profile_image`, `phone_number`, `organization`, `middle_name`, `last_name`, `province`, `city`, `created_at`) VALUES
(30, 'Happy', 'ogbenihappy05@gmail.com', 'qwe2#', '$2y$10$0cNcnfKrYQGiDhViTLcfnOUKpdZe1Cw07uwH/N.I3muIWTHm8Lrou', NULL, '+2349059827374', '', '', '', '', '', '2024-12-03 21:05:12'),
(31, 'Emmanuel', 'owonaroemmanuel5@gmail.com', 'emmysmallz@7', '$2y$10$C1Wz3C21WmuuwZcUFvqHz.sWEfxR0kz/zWfBWu8oqWA89lqmJkMFe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-03 21:07:08'),
(32, 'Adamu', 'balaaadhamu45@gmail.com', 'adamu.bm3', '$2y$10$IiGX7uwQ6vitTlrbXdb7SuCxu2QVtzhaZa9nCRKepkIKELD3IEtsC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-17 01:25:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_vote_key`
--

CREATE TABLE `user_vote_key` (
  `id` int(11) NOT NULL,
  `vote_key` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_vote_key`
--

INSERT INTO `user_vote_key` (`id`, `vote_key`, `created_at`, `user_id`) VALUES
(31, 'G21M', '2024-12-07 09:09:46', 30),
(32, 'p8p8', '2024-12-07 09:10:00', 30),
(34, 'NYIP', '2024-12-17 01:37:03', 32);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `option` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `voted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `num_vote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `poll_id`, `user_id`, `option`, `username`, `voted_at`, `num_vote`) VALUES
(48, 37, 30, NULL, 'qwe2#', '2024-12-12 07:12:28', 67),
(49, 37, 30, NULL, 'emmysmallz@7', '2024-12-12 07:12:33', 78),
(50, 39, 30, 'd', NULL, '2024-12-13 15:22:08', 786),
(51, 40, 32, NULL, 'qwe2#', '2024-12-17 01:37:47', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nominees_details`
--
ALTER TABLE `nominees_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_poll_id` (`poll_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `poll_key` (`poll_key`),
  ADD KEY `fk_poll_creator` (`poll_creator`);

--
-- Indexes for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poll_id` (`poll_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_vote_key`
--
ALTER TABLE `user_vote_key`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poll_id` (`poll_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nominees_details`
--
ALTER TABLE `nominees_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `poll_options`
--
ALTER TABLE `poll_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user_vote_key`
--
ALTER TABLE `user_vote_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nominees_details`
--
ALTER TABLE `nominees_details`
  ADD CONSTRAINT `fk_poll_id` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `polls`
--
ALTER TABLE `polls`
  ADD CONSTRAINT `fk_poll_creator` FOREIGN KEY (`poll_creator`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD CONSTRAINT `poll_options_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_vote_key`
--
ALTER TABLE `user_vote_key`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
