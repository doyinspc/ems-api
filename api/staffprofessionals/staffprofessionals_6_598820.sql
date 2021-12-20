-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 30, 2020 at 07:51 PM
-- Server version: 5.6.41-84.1
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
-- Database: `stresert_hms_staff`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_categorys`
--

CREATE TABLE `user_categorys` (
  `id` int(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_categorys`
--

INSERT INTO `user_categorys` (`id`, `name`, `description`, `is_active`, `is_delete`) VALUES
(1, 'Houskeeping', '', 0, 0),
(2, 'Admin', 'null', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_transactions`
--

CREATE TABLE `user_transactions` (
  `id` int(200) NOT NULL,
  `clientId` int(200) NOT NULL,
  `roomId` int(200) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_booked` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` int(100) NOT NULL,
  `employment_no` varchar(100) NOT NULL,
  `passwd` varchar(150) NOT NULL,
  `categoryid` int(10) NOT NULL,
  `title` varchar(20) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `gender` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `doe` date NOT NULL,
  `access` text NOT NULL,
  `phone1` varchar(15) NOT NULL,
  `phone2` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `office` varchar(200) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `employment_no`, `passwd`, `categoryid`, `title`, `surname`, `firstname`, `middlename`, `gender`, `dob`, `doe`, `access`, `phone1`, `phone2`, `email`, `address`, `office`, `is_active`, `is_delete`) VALUES
(6, 'admin', '21232f297a57a5a743894a0e4a801fc3', 2, 'Mr.', 'Ben', 'Maurice', '', '1', '2020-10-27', '2020-10-27', '[[3,1,2],[0,5,6],[0,1,2,3,4],[0,1,2,3,4,5,6],[0,1,2,3,4,5,6],[0,1,2,3,4,5,6]]', '08012345678', '0809876543', 'do@gmail.com', '', 'Administrator', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_categorys`
--
ALTER TABLE `user_categorys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_transactions`
--
ALTER TABLE `user_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_categorys`
--
ALTER TABLE `user_categorys`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_transactions`
--
ALTER TABLE `user_transactions`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
