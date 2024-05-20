-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2024 at 11:02 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vet`
--

-- --------------------------------------------------------

--
-- Table structure for table `pet`
--

CREATE TABLE `pet` (
  `id_pet` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_vet` int(11) DEFAULT NULL,
  `pet_name` varchar(50) NOT NULL,
  `age` smallint(2) DEFAULT NULL,
  `species` varchar(50) NOT NULL,
  `bried` varchar(50) DEFAULT NULL,
  `photo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id_service` int(11) NOT NULL,
  `service name` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `user_fname` varchar(25) NOT NULL,
  `user_lname` varchar(50) NOT NULL,
  `phone` int(20) NOT NULL,
  `age` int(3) DEFAULT NULL,
  `user_email` varchar(75) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `active` smallint(1) NOT NULL DEFAULT 0,
  `registration_token` char(40) NOT NULL,
  `registration_expires` datetime DEFAULT NULL,
  `is_banned` smallint(1) DEFAULT 0,
  `forgotten_password_token` char(40) DEFAULT NULL,
  `forgotten_password_expires` datetime DEFAULT NULL,
  `registration_date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `user_fname`, `user_lname`, `phone`, `age`, `user_email`, `user_password`, `active`, `registration_token`, `registration_expires`, `is_banned`, `forgotten_password_token`, `forgotten_password_expires`, `registration_date`) VALUES
(16, 'Milana', 'ivanovic', 644544544, NULL, 'sabovljevmilanaaa@gmail.com', '$2y$10$hkq8OFo9WnGL6pvEfiyn8egz8nEkAT9cPAAZT73vY9DsSaPHOTb7q', 1, '', '0000-00-00 00:00:00', 0, NULL, NULL, '2024-05-20 19:24:42'),
(17, 'test1', 'test1', 644544544, NULL, 'skoriclazar1@gmail.com', '$2y$10$N5iw2SgxgrqucCQ4KSsi2.xasO3bstBmkheNbtbfFQ5pwULH1hQP.', 1, '', '0000-00-00 00:00:00', 0, NULL, NULL, '2024-05-20 19:31:16'),
(18, 'test1', 'test1', 644544544, NULL, 'test1@gmail.com', '$2y$10$R44j6.dQdTPrYny4SMG8Bed3Ur0E2tvIpAuzXMTPeNqmYRe7gjsa6', 0, '7cca335ec9957466dcd739dba9017daa88feeb3d', '2024-05-21 19:50:21', 0, NULL, NULL, '2024-05-20 19:50:21'),
(19, 'test2', 'test2', 644544544, NULL, 'test2@gmail.com', '$2y$10$CObZYeH5oWKVMIINq0O.beEmani4anH8scAYj5IB52kvvHKdtzeD6', 1, '', '0000-00-00 00:00:00', 0, NULL, NULL, '2024-05-20 22:14:47');

-- --------------------------------------------------------

--
-- Table structure for table `user_email_failures`
--

CREATE TABLE `user_email_failures` (
  `id_user_email_failure` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date_time_added` datetime NOT NULL,
  `date_time_tried` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vet`
--

CREATE TABLE `vet` (
  `id_vet` int(11) NOT NULL,
  `vet_fname` varchar(30) NOT NULL,
  `vet_lname` varchar(40) NOT NULL,
  `vet_email` varchar(70) NOT NULL,
  `vet_password` varchar(255) NOT NULL,
  `vet_phone` int(20) NOT NULL,
  `specialization` varchar(40) NOT NULL,
  `city` varchar(40) NOT NULL,
  `active` smallint(1) NOT NULL DEFAULT 0,
  `registration_token` char(40) NOT NULL,
  `registration_expires` datetime DEFAULT NULL,
  `forgotten_password_token` char(40) DEFAULT NULL,
  `forgotten_password_expires` datetime DEFAULT NULL,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vet_services`
--

CREATE TABLE `vet_services` (
  `id_vet` int(11) NOT NULL,
  `id_service` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`id_pet`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id_service`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  ADD PRIMARY KEY (`id_user_email_failure`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `vet`
--
ALTER TABLE `vet`
  ADD PRIMARY KEY (`id_vet`);

--
-- Indexes for table `vet_services`
--
ALTER TABLE `vet_services`
  ADD KEY `pomocna` (`id_vet`),
  ADD KEY `pomocna2` (`id_service`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pet`
--
ALTER TABLE `pet`
  MODIFY `id_pet` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  MODIFY `id_user_email_failure` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vet`
--
ALTER TABLE `vet`
  MODIFY `id_vet` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `pet_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  ADD CONSTRAINT `user_email_failures_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vet_services`
--
ALTER TABLE `vet_services`
  ADD CONSTRAINT `pomocna` FOREIGN KEY (`id_vet`) REFERENCES `vet` (`id_vet`),
  ADD CONSTRAINT `pomocna2` FOREIGN KEY (`id_service`) REFERENCES `services` (`id_service`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
