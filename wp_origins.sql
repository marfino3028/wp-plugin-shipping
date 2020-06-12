-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2020 at 11:13 AM
-- Server version: 5.7.30
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `actfreig_wp`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_origins`
--

CREATE TABLE `wp_origins` (
  `oriId` int(11) NOT NULL,
  `oriName` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `wp_origins`
--

INSERT INTO `wp_origins` (`oriId`, `oriName`) VALUES
(1, 'Busan'),
(2, 'Hongkong'),
(3, 'KEE / KAOH / TAIC'),
(4, 'Shenzhen Via HKG'),
(5, 'Hochiminh via SIN'),
(7, 'Singapore'),
(8, 'Shanghai');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_origins`
--
ALTER TABLE `wp_origins`
  ADD PRIMARY KEY (`oriId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_origins`
--
ALTER TABLE `wp_origins`
  MODIFY `oriId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
