-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 30, 2020 at 07:12 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mzq`
--

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `name` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `alias` varchar(20) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`name`, `alias`) VALUES
('بهشهر', 'behshahr'),
('ساری', 'sari');

-- --------------------------------------------------------

--
-- Table structure for table `commuting`
--

CREATE TABLE `commuting` (
  `username` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `ID` varchar(10) COLLATE utf8_persian_ci NOT NULL,
  `city` varchar(20) COLLATE utf8_persian_ci NOT NULL DEFAULT '''unknown''',
  `dateTime` datetime NOT NULL,
  `longitude` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `latitude` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `type` enum('in','out') COLLATE utf8_persian_ci NOT NULL DEFAULT 'in'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE `people` (
  `ID` varchar(10) COLLATE utf8_persian_ci NOT NULL COMMENT 'Code melli',
  `city` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `fName` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `lName` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `father` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `plaque` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `office` text COLLATE utf8_persian_ci NOT NULL,
  `comments` text COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `token` varchar(100) COLLATE utf8_persian_ci NOT NULL,
  `city` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `fName` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `lName` varchar(20) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `token`, `city`, `admin`, `fName`, `lName`) VALUES
('user1', '94a08da1fecbb6e8b46990538c7b50b2', 'بهشهر', 1, 'محمد', 'رسولی'),
('user2', '12cd80d40807d84f440ab8753a2283c1', 'ساری', 0, 'رضا', 'کریمی');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `commuting`
--
ALTER TABLE `commuting`
  ADD PRIMARY KEY (`ID`,`dateTime`);

--
-- Indexes for table `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`ID`,`city`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
