-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 08:37 PM
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
-- Database: `herts2u_auth`
--

-- --------------------------------------------------------

--
-- Table structure for table `sessions_config`
--

CREATE TABLE `sessions_config` (
  `session_id` varchar(128) NOT NULL,
  `data` text NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `session_lifetime` int(11) NOT NULL DEFAULT 86400
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions_config`
--

INSERT INTO `sessions_config` (`session_id`, `data`, `last_activity`, `session_lifetime`) VALUES
('ai0ecgbfpnaar7li4uf0483ge4', '_validated|b:1;_created|i:1751292376;_ip|s:3:\"::1\";_ua|s:111:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36\";', '2025-06-30 14:16:34', 86400);

-- --------------------------------------------------------

--
-- Table structure for table `session_config`
--

CREATE TABLE `session_config` (
  `session_id` varchar(128) NOT NULL,
  `data` text NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `session_lifetime` int(11) NOT NULL DEFAULT 86400
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `remember_token` varchar(255) DEFAULT NULL,
  `status` enum('active','suspended','pending') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`, `remember_token`, `status`) VALUES
(1, 'testuser', 'test@example.com', '$2y$10$0T2MmeLl3R9lvSGMWdvX2OhoxyB1GT4xWPFDNGmG8UMOaIP3OYLyC', '2025-05-22 01:12:28', '2025-05-22 01:12:28', NULL, 'active'),
(2, 'mary24', 'ma20abv@herts.ac.uk', '$2y$10$kQ/vVtQ1jh130lJC9UVKi.F6A0l7E2T6Jc2tE04Ox.nBIjWIgKklW', '2025-06-24 11:12:39', '2025-06-24 11:12:39', NULL, 'active'),
(3, 'abc', 'abc@gmail.com', '$2y$10$9.rwNeHW8M3x8bc//D6b0eNft.gHyn2ZUnutI5iwPI4XZudMF78EW', '2025-06-28 19:06:00', '2025-06-28 19:06:00', NULL, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sessions_config`
--
ALTER TABLE `sessions_config`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity` (`last_activity`);

--
-- Indexes for table `session_config`
--
ALTER TABLE `session_config`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
