-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2025 at 01:26 PM
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
-- Database: `sampleattendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

CREATE TABLE `attendance_records` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `time_in` datetime NOT NULL,
  `time_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `card_id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `age` int(4) NOT NULL,
  `dob` date NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `card_id`, `name`, `address`, `age`, `dob`, `image`) VALUES
(1, 3362817637, 'Aliyah C. Calanoc', 'Shiganshina District', 23, '2002-09-26', 'images/Ali.png'),
(2, 3362817638, 'Roice Al Panes', 'San Juan City Batangas', 20, '2005-03-11', 'images/icon.png'),
(3, 3362817640, 'Eren Yeager', 'Shiganshina District', 19, '2002-03-30', 'images/Eren Yeager.jpg'),
(4, 3362817641, 'Clementine Kruczynski', '59 Orient Avenue, Brooklyn, NY 11211. ', 30, '2004-07-24', 'images/Clementine Kruczynski.jpg'),
(5, 3362817642, 'Toy Chica', 'Freddie Fazzbear Pizzeria', 38, '1980-01-14', 'images/Toy Chica.Jpg'),
(6, 3362817643, 'Neil Perry', 'Welton Academy', 17, '1989-06-02', 'images/Neil Perry.Jpg'),
(7, 3362817644, 'Diane Nguyen', 'Boston, Massachusetts', 34, '1980-03-19', 'images/Diane Nguyen.JPEG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_records`
--
ALTER TABLE `attendance_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
