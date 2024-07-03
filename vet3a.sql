-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2024 at 12:02 PM
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
-- Database: `vet3`
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
(3, 'Milana', 'test1', 'sabovljevmi@gmail.com', '$2y$10$4mIAErdJzHRd/L3LFI/p9.eVhQhHKH8KVIfwBnHCzK/S40BFxOiv6', 1, '', '0000-00-00 00:00:00'),
(5, 'Mi', 'Bar', 'mibar@gmail.com', '$2y$10$WGhnCyGrrEe94RS36baDNOUsI6CEurTDxxO9cv6rS1GXXGbqH.u9a', 1, '', '0000-00-00 00:00:00');

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
  `procedure_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `done_procedures`
--

INSERT INTO `done_procedures` (`id_done_procedure`, `id_reservation`, `diagnosis`, `prescribed_medication`, `pet_condition`, `procedure_duration`, `procedure_date`) VALUES
(1, 15, 'Test', 'Pills', 'Good', '00:59:00', '2024-06-28');

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
(3, 16, 1, 'Masa', 1, 'macka', 'sijamska', ''),
(4, 16, 1, 'Koja', 7, 'Kornjaca', 'Recna', ''),
(5, 16, 1, 'ff', 2, 'Pas', 'German Shepard', ''),
(6, 16, 1, 'test', 2, 'Pas', 'German Shepard', ''),
(7, 16, NULL, 'Pera', 3, 'Pas', 'German Shepard', '20240610115546-Pera.jpg'),
(16, 16, NULL, 'ddddddd', 0, 'ddddddddd', 'dddddddddddd', '20240610134557-ddddddd.jpg'),
(17, 16, 1, 'novi', 0, 'novi', 'novi', '20240610134809-novi.jpg'),
(18, 22, 1, 'Roki', 6, 'Pas', 'German Shepard', '20240610142408-Roki.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` int(11) NOT NULL,
  `id_pet` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `id_vet` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `treatment_price` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `code_verified` smallint(1) NOT NULL DEFAULT 0,
  `reservation_added` datetime NOT NULL DEFAULT current_timestamp(),
  `service_duration` time NOT NULL,
  `cancel` smallint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `id_pet`, `id_service`, `id_vet`, `reservation_date`, `reservation_time`, `treatment_price`, `code`, `code_verified`, `reservation_added`, `service_duration`, `cancel`) VALUES
(1, 18, 5, 1, '2024-06-20', '09:00:00', 500, '', 0, '2024-06-12 22:44:23', '00:30:00', 0),
(3, 18, 5, 1, '2024-06-21', '09:00:00', 50, '', 0, '2024-06-12 22:48:54', '00:30:00', 0),
(6, 3, 5, 3, '2024-06-28', '15:00:00', 50, 'XmnCal', 0, '2024-06-16 18:59:56', '00:30:00', 0),
(9, 17, 1, 2, '2024-06-25', '08:30:00', 500, '', 0, '2024-06-16 20:19:45', '00:59:00', 0),
(12, 17, 1, 1, '2024-06-21', '08:30:00', 500, '', 0, '2024-06-16 23:30:57', '00:59:00', 0),
(14, 2, 5, 3, '2024-06-29', '08:00:00', 50, 'XmzncK', 0, '2024-06-17 11:39:08', '00:30:00', 0),
(15, 2, 1, 3, '2024-06-28', '13:00:00', 500, 'RwpXth', 1, '2024-06-17 11:42:37', '00:59:00', 0),
(16, 18, 1, 3, '2024-06-29', '16:00:00', 40, 'Dfgsdy', 0, '2024-06-28 12:36:17', '00:00:15', 0),
(17, 18, 1, 3, '2024-06-29', '16:00:00', 40, 'Vtzium', 0, '2024-06-28 12:36:21', '00:00:15', 0),
(18, 18, 1, 3, '2024-06-29', '16:00:00', 40, 'WbnJsc', 0, '2024-06-28 12:36:24', '00:00:15', 0),
(19, 18, 1, 3, '2024-06-28', '16:00:00', 40, 'Izmywj', 1, '2024-06-28 12:36:28', '00:00:15', 0),
(20, 18, 1, 3, '2024-06-29', '16:00:00', 40, 'IxeUbh', 0, '2024-06-28 12:36:31', '00:00:15', 0),
(21, 18, 1, 3, '2024-06-29', '16:00:00', 40, 'Fsmbah', 0, '2024-06-28 12:36:35', '00:00:15', 0),
(22, 18, 1, 3, '2024-06-29', '16:00:00', 40, 'IjbkWh', 0, '2024-06-28 12:36:39', '00:00:15', 0),
(23, 18, 1, 3, '2024-06-29', '07:45:00', 40, 'TfjcWr', 0, '2024-06-28 13:50:05', '00:00:15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id_service` int(11) NOT NULL,
  `service_name` varchar(70) NOT NULL,
  `service_description` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `service_duration` time NOT NULL,
  `price` int(11) DEFAULT NULL,
  `discount` int(2) DEFAULT NULL,
  `service_date` date DEFAULT NULL,
  `service_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id_service`, `service_name`, `service_description`, `photo`, `service_duration`, `price`, `discount`, `service_date`, `service_time`) VALUES
(1, 'Microchipping', 'Implanting a microchip under the pet\'s skin for identification purposes.', '20240617124618-Microchipping.jpg', '00:00:15', 40, 0, NULL, NULL),
(2, 'General Check-Up', 'Routine health examinations for pets to ensure they are healthy.', '20240617124421-General Check-Up.jpg', '00:00:30', 50, 0, NULL, NULL),
(4, 'Emergency Care', 'Immediate medical attention for pets in critical condition.', '20240617125340-Emergency Care.jpg', '00:00:00', 150, 0, NULL, NULL),
(5, 'Neuter Surgery', 'Surgical procedures to sterilize pets and prevent unwanted litters.', '20240617124551-Neuter Surgery.jpg', '00:00:02', 200, 0, NULL, NULL),
(13, 'Vaccinations', 'Administering vaccines to prevent diseases like rabies, distemper, and parvovirus.', '20240617124455-Vaccinations.jpg', '00:00:20', 25, 0, NULL, NULL),
(14, 'Nutritional Counseling', 'Providing dietary advice and customized meal plans for pets.', '20240617124651-Nutritional Counseling.jpg', '00:00:30', 30, 0, NULL, NULL),
(15, 'Dental Cleaning', 'Professional teeth cleaning to prevent dental diseases and maintain oral health.', '20240617124522-Dental Cleaning.jpg', '00:00:45', 100, 0, NULL, NULL),
(23, 'Parasite Control', 'Treatments to prevent and eliminate parasites like fleas, ticks, and worms.', '20240617125406-Parasite Control.jpg', '00:00:20', 20, 0, NULL, NULL),
(24, 'Behavioral Consultation', 'Assessing and addressing behavioral issues in pets.', '20240617125435-Behavioral Consultation.jpg', '00:00:01', 75, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `user_fname` varchar(25) NOT NULL,
  `user_lname` varchar(50) NOT NULL,
  `user_phone` int(20) NOT NULL,
  `age` int(3) DEFAULT NULL,
  `photo` varchar(70) DEFAULT NULL,
  `user_email` varchar(75) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `active` smallint(1) NOT NULL DEFAULT 0,
  `registration_token` char(40) NOT NULL,
  `registration_expires` datetime DEFAULT NULL,
  `is_banned` smallint(1) DEFAULT 0,
  `forgotten_password_token` char(40) DEFAULT NULL,
  `forgotten_password_expires` datetime DEFAULT NULL,
  `registration_date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `negative_points` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `user_fname`, `user_lname`, `user_phone`, `age`, `photo`, `user_email`, `user_password`, `active`, `registration_token`, `registration_expires`, `is_banned`, `forgotten_password_token`, `forgotten_password_expires`, `registration_date`, `negative_points`) VALUES
(16, 'Milana', 'ivanovic', 644544544, NULL, '', 'sabovljevmilanaaa@gmail.com', '$2y$10$vOyevPJtMZD/tb3a2BkapuEMBEaoLCA4eoBnYeMDzk7AmKN.lT7.u', 1, '', '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '2024-06-28 01:22:43', 0),
(17, 'test1', 'test1', 644544544, NULL, '', 'skoriclazar1@gmail.com', '$2y$10$N5iw2SgxgrqucCQ4KSsi2.xasO3bstBmkheNbtbfFQ5pwULH1hQP.', 1, '', '0000-00-00 00:00:00', 0, NULL, NULL, '2024-05-20 19:31:16', 0),
(18, 'test1', 'test1', 644544544, NULL, '', 'test1@gmail.com', '$2y$10$R44j6.dQdTPrYny4SMG8Bed3Ur0E2tvIpAuzXMTPeNqmYRe7gjsa6', 0, '7cca335ec9957466dcd739dba9017daa88feeb3d', '2024-05-21 19:50:21', 0, NULL, NULL, '2024-05-20 19:50:21', 0),
(19, 'test2', 'test2', 644544544, NULL, '', 'test2@gmail.com', '$2y$10$CObZYeH5oWKVMIINq0O.beEmani4anH8scAYj5IB52kvvHKdtzeD6', 1, '', '0000-00-00 00:00:00', 0, NULL, NULL, '2024-05-20 22:14:47', 0),
(20, 'Web', 'Programiranje', 628480677, NULL, '', 'webprogramer@gmail.com', '$2y$10$h4tsl.oaTQpQw3goP1v7lukBPJ4gmziloHw1EaKDqsHch4gQNVwka', 1, '', '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '2024-06-03 17:31:11', 0),
(21, 'Milana', 'test1', 644544544, NULL, '', 'sabovljevmi@gmail.com', '$2y$10$wKgWHWpqJdo0P8GxDt4aYer32zu9FgHCzbg8LHhznfkwO44jU295a', 1, '', '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', '2024-06-09 14:29:51', 0),
(22, 'Mihajlo', 'Baranovski', 628480677, 20, '', 'mihajlobar03@gmail.com', '$2y$10$X2g1JLpfGuUsOwcsRp3A/.rTQZnxVL.XEkbp.YT/Dzlvyn1.GX6lK', 1, '', '0000-00-00 00:00:00', 0, NULL, NULL, '2024-06-28 13:43:07', 1),
(23, '', '', 0, NULL, '', '', '', 0, '', NULL, 0, NULL, NULL, '2024-06-23 23:44:22', 0);

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
  `photo` varchar(80) DEFAULT NULL,
  `specialization` varchar(40) NOT NULL,
  `city` varchar(40) NOT NULL,
  `active` smallint(1) NOT NULL DEFAULT 0,
  `registration_token` char(40) NOT NULL,
  `registration_expires` datetime DEFAULT NULL,
  `is_banned` smallint(1) DEFAULT 0,
  `forgotten_password_token` char(40) DEFAULT NULL,
  `forgotten_password_expires` datetime DEFAULT NULL,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `work_start` time(4) NOT NULL,
  `work_end` time(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vet`
--

INSERT INTO `vet` (`id_vet`, `vet_fname`, `vet_lname`, `vet_email`, `vet_password`, `vet_phone`, `photo`, `specialization`, `city`, `active`, `registration_token`, `registration_expires`, `is_banned`, `forgotten_password_token`, `forgotten_password_expires`, `registration_date`, `work_start`, `work_end`) VALUES
(1, 'Nikola', 'Spasojević', 'nikolas@gmail.com', '$2y$10$WGhnCyGrrEe94RS36baDNOUsI6CEurTDxxO9cv6rS1GXXGbqH.u9a', 644544544, '', 'Emergency and critical care', '', 1, '', '0000-00-00 00:00:00', 0, NULL, NULL, '2024-06-28 12:20:48', '08:00:00.0000', '16:00:00.0000'),
(2, 'Ivana', 'Milivojević', 'ivanamilivojevic@gmail.com', '$2y$10$Qcxn0/iIEkCExb5Yx4dVE.cbWg5zL7Q3eJo2ZtJsMdXhV5YmKVy/e', 654544541, NULL, 'General vet', 'Belgrade', 1, '', NULL, 0, NULL, NULL, '2024-06-16 17:43:52', '07:00:00.0000', '15:00:00.0000'),
(3, 'Mi', 'Bar', 'mibar@gmail.com', '$2y$10$d5Tqu2/AjG99iNFco3EtmevlmkyeVf6S20Q3mhmWSGVuMRupjnf42', 628480677, NULL, '', '', 1, '', '0000-00-00 00:00:00', 0, NULL, NULL, '2024-06-28 12:34:02', '07:00:00.0000', '20:00:00.0000');

-- --------------------------------------------------------

--
-- Table structure for table `vet_email_failures`
--

CREATE TABLE `vet_email_failures` (
  `id_vet_email_failure` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date_time_added` datetime NOT NULL,
  `date_time_tried` datetime DEFAULT NULL
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
-- Dumping data for table `vet_services`
--

INSERT INTO `vet_services` (`id_vet`, `id_service`) VALUES
(1, 1),
(1, 5),
(2, 5),
(2, 1),
(3, 5),
(3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

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
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_pet` (`id_pet`),
  ADD KEY `id_service` (`id_service`),
  ADD KEY `id_vet` (`id_vet`);

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
-- Indexes for table `vet`
--
ALTER TABLE `vet`
  ADD PRIMARY KEY (`id_vet`);

--
-- Indexes for table `vet_email_failures`
--
ALTER TABLE `vet_email_failures`
  ADD PRIMARY KEY (`id_vet_email_failure`),
  ADD KEY `id_user` (`id_user`);

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
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `done_procedures`
--
ALTER TABLE `done_procedures`
  MODIFY `id_done_procedure` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pet`
--
ALTER TABLE `pet`
  MODIFY `id_pet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `vet`
--
ALTER TABLE `vet`
  MODIFY `id_vet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vet_email_failures`
--
ALTER TABLE `vet_email_failures`
  MODIFY `id_vet_email_failure` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `done_procedures`
--
ALTER TABLE `done_procedures`
  ADD CONSTRAINT `done_procedures_ibfk_1` FOREIGN KEY (`id_reservation`) REFERENCES `reservations` (`id_reservation`);

--
-- Constraints for table `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `pet_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_pet`) REFERENCES `pet` (`id_pet`),
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`id_service`) REFERENCES `services` (`id_service`),
  ADD CONSTRAINT `reservations_ibfk_4` FOREIGN KEY (`id_vet`) REFERENCES `vet` (`id_vet`);

--
-- Constraints for table `vet_email_failures`
--
ALTER TABLE `vet_email_failures`
  ADD CONSTRAINT `vet_email_failures_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

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
