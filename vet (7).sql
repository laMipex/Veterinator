-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2024 at 01:53 PM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` smallint(1) NOT NULL DEFAULT 0,
  `registration_token` char(40) NOT NULL,
  `registration_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `firstname`, `lastname`, `email`, `password`, `active`, `registration_token`, `registration_expires`) VALUES
(1, 'Milana', 'Sabovljev', 'sabovljevmilanaaa@gmail.com', '$2y$10$J4MoFxfFLzn.tjRURFphYu/tA9zP/YAqstnmKP2/2ComHtVJvRt/O', 1, '', '0000-00-00 00:00:00'),
(2, 'Web', 'Programiranje', 'student@gmail.com', '$2y$10$Q1nk0im0tXiVwD.74sbj..imR2G9we3L.OoCTbp4KK8E87lKjcF42', 1, '', '0000-00-00 00:00:00'),
(3, 'Milana', 'test1', 'sabovljevmi@gmail.com', '$2y$10$4mIAErdJzHRd/L3LFI/p9.eVhQhHKH8KVIfwBnHCzK/S40BFxOiv6', 1, '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `available_procedure`
--

CREATE TABLE `available_procedure` (
  `id_available_procedure` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `price` float NOT NULL,
  `discount` int(2) DEFAULT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `done_procedures`
--

CREATE TABLE `done_procedures` (
  `id_done_procedure` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `diagnosis` varchar(70) NOT NULL,
  `prescribed_medication` varchar(70) NOT NULL,
  `pet_condition` varchar(65) NOT NULL,
  `procedure_duration` time NOT NULL,
  `procedure_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `breed` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet`
--

INSERT INTO `pet` (`id_pet`, `id_user`, `id_vet`, `pet_name`, `age`, `species`, `breed`, `photo`) VALUES
(1, 16, NULL, 'Pera', 3, 'Papagaj', 'plava oboa', ''),
(2, 16, NULL, 'Max', 2, 'Pas', 'German Shepard', ''),
(3, 16, NULL, 'Masa', 1, 'macka', 'sijamska', ''),
(4, 16, NULL, 'Koja', 7, 'Kornjaca', 'Recna', ''),
(5, 16, NULL, 'ff', 2, 'Pas', 'German Shepard', ''),
(6, 16, NULL, 'test', 2, 'Pas', 'German Shepard', ''),
(7, 16, NULL, 'Pera', 3, 'Pas', 'German Shepard', '20240610115546-Pera.jpg'),
(16, 16, NULL, 'ddddddd', 0, 'ddddddddd', 'dddddddddddd', '20240610134557-ddddddd.jpg'),
(17, 16, NULL, 'novi', 0, 'novi', 'novi', '20240610134809-novi.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id_service` int(11) NOT NULL,
  `service_name` varchar(70) NOT NULL,
  `service_description` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `service_duration` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id_service`, `service_name`, `service_description`, `photo`, `service_duration`) VALUES
(1, 'Surgery', 'Most aggressive procedure, only if necessary.', NULL, '10:27:02'),
(2, 'Dental', 'Dental hygiene and systematic checkup.', '9490.jpg', '00:30:00'),
(3, 'Anesthesia', 'Anesthesia is always safe.', NULL, '00:15:00'),
(4, 'Vaccinations', 'Vaccination for everything', NULL, '00:05:00'),
(5, 'Sterilization', 'Very efficient', '20240608222847-Sterilization.jpg', '30 min'),
(13, 'Proba', 'hhh', NULL, '30 min'),
(14, 'Test', 'proba', '20240609000241-Test.jpg', '30 min'),
(15, 's', 's', '20240610130957-s.jpg', 's'),
(16, 'd', 'd', '20240610131135-d.jpg', 'd');

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
(16, 'Milana', 'ivanovic', 644544544, NULL, 'sabovljevmilanaaa@gmail.com', '$2y$10$Qcxn0/iIEkCExb5Yx4dVE.cbWg5zL7Q3eJo2ZtJsMdXhV5YmKVy/e', 1, '', '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '2024-06-09 01:51:40'),
(17, 'test1', 'test1', 644544544, NULL, 'skoriclazar1@gmail.com', '$2y$10$N5iw2SgxgrqucCQ4KSsi2.xasO3bstBmkheNbtbfFQ5pwULH1hQP.', 1, '', '0000-00-00 00:00:00', 0, NULL, NULL, '2024-05-20 19:31:16'),
(18, 'test1', 'test1', 644544544, NULL, 'test1@gmail.com', '$2y$10$R44j6.dQdTPrYny4SMG8Bed3Ur0E2tvIpAuzXMTPeNqmYRe7gjsa6', 0, '7cca335ec9957466dcd739dba9017daa88feeb3d', '2024-05-21 19:50:21', 0, NULL, NULL, '2024-05-20 19:50:21'),
(19, 'test2', 'test2', 644544544, NULL, 'test2@gmail.com', '$2y$10$CObZYeH5oWKVMIINq0O.beEmani4anH8scAYj5IB52kvvHKdtzeD6', 1, '', '0000-00-00 00:00:00', 0, NULL, NULL, '2024-05-20 22:14:47'),
(20, 'Web', 'Programiranje', 628480677, NULL, 'webprogramer@gmail.com', '$2y$10$h4tsl.oaTQpQw3goP1v7lukBPJ4gmziloHw1EaKDqsHch4gQNVwka', 1, '', '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '2024-06-03 17:31:11'),
(21, 'Milana', 'test1', 644544544, NULL, 'sabovljevmi@gmail.com', '$2y$10$wKgWHWpqJdo0P8GxDt4aYer32zu9FgHCzbg8LHhznfkwO44jU295a', 1, '', '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '2024-06-09 14:29:51');

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
-- Table structure for table `user_reservation`
--

CREATE TABLE `user_reservation` (
  `id_reservation` int(11) NOT NULL,
  `id_available_procedure` int(11) NOT NULL,
  `id_pet` int(11) NOT NULL,
  `reservation_date` datetime NOT NULL DEFAULT current_timestamp()
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
  `is_banned` smallint(1) DEFAULT 0,
  `forgotten_password_token` char(40) DEFAULT NULL,
  `forgotten_password_expires` datetime DEFAULT NULL,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vet`
--

INSERT INTO `vet` (`id_vet`, `vet_fname`, `vet_lname`, `vet_email`, `vet_password`, `vet_phone`, `specialization`, `city`, `active`, `registration_token`, `registration_expires`, `is_banned`, `forgotten_password_token`, `forgotten_password_expires`, `registration_date`) VALUES
(1, 'Nikola', 'Spasojević', 'nikolaspasojevic@gmail.com', '$2y$10$dAOaSNOipGq2B/l.ofRbtu2vwzsTZPgy.y8O4AEiHp8H.N7knykIa', 644544544, '', '', 1, '', '0000-00-00 00:00:00', 0, NULL, NULL, '2024-06-09 14:49:37');

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
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `available_procedure`
--
ALTER TABLE `available_procedure`
  ADD PRIMARY KEY (`id_available_procedure`),
  ADD KEY `id_service` (`id_service`);

--
-- Indexes for table `done_procedures`
--
ALTER TABLE `done_procedures`
  ADD PRIMARY KEY (`id_done_procedure`),
  ADD KEY `id_reservation` (`id_reservation`);

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
-- Indexes for table `user_reservation`
--
ALTER TABLE `user_reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_available_procedure` (`id_available_procedure`),
  ADD KEY `id_pet` (`id_pet`);

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `available_procedure`
--
ALTER TABLE `available_procedure`
  MODIFY `id_available_procedure` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `done_procedures`
--
ALTER TABLE `done_procedures`
  MODIFY `id_done_procedure` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pet`
--
ALTER TABLE `pet`
  MODIFY `id_pet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_email_failures`
--
ALTER TABLE `user_email_failures`
  MODIFY `id_user_email_failure` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_reservation`
--
ALTER TABLE `user_reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vet`
--
ALTER TABLE `vet`
  MODIFY `id_vet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `available_procedure`
--
ALTER TABLE `available_procedure`
  ADD CONSTRAINT `available_procedure_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `services` (`id_service`);

--
-- Constraints for table `done_procedures`
--
ALTER TABLE `done_procedures`
  ADD CONSTRAINT `done_procedures_ibfk_1` FOREIGN KEY (`id_reservation`) REFERENCES `user_reservation` (`id_reservation`);

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
-- Constraints for table `user_reservation`
--
ALTER TABLE `user_reservation`
  ADD CONSTRAINT `user_reservation_ibfk_1` FOREIGN KEY (`id_available_procedure`) REFERENCES `available_procedure` (`id_available_procedure`),
  ADD CONSTRAINT `user_reservation_ibfk_2` FOREIGN KEY (`id_pet`) REFERENCES `pet` (`id_pet`);

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
