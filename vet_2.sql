-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2024 at 07:33 PM
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
  `petID` int(11) NOT NULL,
  `p_name` varchar(25) NOT NULL,
  `date_birth` varchar(30) NOT NULL,
  `animal_type` varchar(50) NOT NULL,
  `species` varchar(50) NOT NULL,
  `photo` varchar(50) NOT NULL,
  `procedureID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet`
--

INSERT INTO `pet` (`petID`, `p_name`, `date_birth`, `animal_type`, `species`, `photo`, `procedureID`) VALUES
(1, 'Fluffy', '2019-01-15', 'Dog', 'Golden Retriever', 'fluffy.jpg', 1),
(2, 'Whiskers', '2020-05-20', 'Cat', 'Siamese', 'whiskers.jpg', 2),
(3, 'Buddy', '2018-08-10', 'Dog', 'Labrador Retriever', 'buddy.jpg', 3),
(4, 'Snowball', '2017-12-03', 'Rabbit', 'Angora', 'snowball.jpg', 4),
(5, 'Max', '2016-06-25', 'Dog', 'German Shepherd', 'max.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `procedures`
--

CREATE TABLE `procedures` (
  `procedureID` int(11) NOT NULL,
  `p_name` varchar(40) NOT NULL,
  `price` decimal(20,0) NOT NULL,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `vetID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `procedures`
--

INSERT INTO `procedures` (`procedureID`, `p_name`, `price`, `date`, `time`, `vetID`) VALUES
(1, 'Checkup', 50, '2024-03-14', NULL, 1),
(2, 'Vaccination', 30, '2024-03-15', NULL, 2),
(3, 'Surgery', 200, '2024-03-16', NULL, 3),
(4, 'Dental Cleaning', 80, '2024-03-17', NULL, 2),
(5, 'X-Ray', 100, '2024-03-18', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `u_fname` varchar(25) NOT NULL,
  `u_lname` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` int(20) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `petID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `u_fname`, `u_lname`, `email`, `password`, `phone`, `photo`, `petID`) VALUES
(1, 'John', 'Doe', 'john@example.com', 'password123', 1234567890, 'john.jpg', 1),
(2, 'Jane', 'Smith', 'jane@example.com', 'letmein', 2147483647, 'jane.jpg', 2),
(3, 'Alice', 'Johnson', 'alice@example.com', 'abc123', 2147483647, 'alice.jpg', 3),
(4, 'Bob', 'Williams', 'bob@example.com', 'password', 1112223333, 'bob.jpg', 4),
(5, 'Emily', 'Brown', 'emily@example.com', 'password321', 2147483647, 'emily.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `veterinarian`
--

CREATE TABLE `veterinarian` (
  `vetID` int(11) NOT NULL,
  `v_fname` varchar(20) NOT NULL,
  `v_lname` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` int(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `photo` varchar(50) NOT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `veterinarian`
--

INSERT INTO `veterinarian` (`vetID`, `v_fname`, `v_lname`, `password`, `phone`, `email`, `photo`, `userID`) VALUES
(1, 'Dr Michael', 'Smith', '', 1234567890, 'michael@example.com', 'vet1.jpg', 1),
(2, 'Dr.Sarah', 'Smith', '', 2147483647, 'jane@example.com', 'jane.jpg', 2),
(3, 'Michael', 'Johnson', '', 2147483647, 'michael@example.com', 'michael.jpg', 3),
(4, 'Dr. Jennifer', 'Davis', '', 2147483647, 'jennifer@example.com', 'vet4.jpg', 4),
(5, 'Dr. Robert', 'Martinez', '', 2147483647, 'robert@example.com', 'vet5.jpg', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`petID`),
  ADD KEY `pet_fk2` (`procedureID`);

--
-- Indexes for table `procedures`
--
ALTER TABLE `procedures`
  ADD PRIMARY KEY (`procedureID`),
  ADD KEY `proc_fk1` (`vetID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `pet_fk1` (`petID`);

--
-- Indexes for table `veterinarian`
--
ALTER TABLE `veterinarian`
  ADD PRIMARY KEY (`vetID`),
  ADD KEY `vet_fk1` (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pet`
--
ALTER TABLE `pet`
  MODIFY `petID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `procedures`
--
ALTER TABLE `procedures`
  MODIFY `procedureID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `veterinarian`
--
ALTER TABLE `veterinarian`
  MODIFY `vetID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `pet_fk2` FOREIGN KEY (`procedureID`) REFERENCES `procedures` (`procedureID`);

--
-- Constraints for table `procedures`
--
ALTER TABLE `procedures`
  ADD CONSTRAINT `proc_fk1` FOREIGN KEY (`vetID`) REFERENCES `veterinarian` (`vetID`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `pet_fk1` FOREIGN KEY (`petID`) REFERENCES `pet` (`petID`);

--
-- Constraints for table `veterinarian`
--
ALTER TABLE `veterinarian`
  ADD CONSTRAINT `vet_fk1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
