-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2015 at 03:12 AM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `orsat`
--

-- --------------------------------------------------------

--
-- Table structure for table `plots`
--

CREATE TABLE IF NOT EXISTS `plots` (
  `id` int(2) NOT NULL,
  `filename` varchar(128) COLLATE utf8_bin NOT NULL,
  `Peak` varchar(128) COLLATE utf8_bin NOT NULL,
  `Component` varchar(128) COLLATE utf8_bin NOT NULL,
  `Amount` varchar(128) COLLATE utf8_bin NOT NULL,
  `Time` varchar(128) COLLATE utf8_bin NOT NULL,
  `Area` varchar(128) COLLATE utf8_bin NOT NULL,
  `Method RT` varchar(128) COLLATE utf8_bin NOT NULL,
  `txo_id` int(2) NOT NULL,
  `dateadded` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `txos`
--

CREATE TABLE IF NOT EXISTS `txos` (
  `id` int(2) NOT NULL,
  `filename` varchar(128) COLLATE utf8_bin NOT NULL,
  `Software Version` varchar(128) COLLATE utf8_bin NOT NULL,
  `Date` varchar(128) COLLATE utf8_bin NOT NULL,
  `Reprocess Number` varchar(128) COLLATE utf8_bin NOT NULL,
  `Operator` varchar(128) COLLATE utf8_bin NOT NULL,
  `Sample Name` varchar(128) COLLATE utf8_bin NOT NULL,
  `Sample Number` varchar(128) COLLATE utf8_bin NOT NULL,
  `Study` varchar(128) COLLATE utf8_bin NOT NULL,
  `AutoSampler` varchar(128) COLLATE utf8_bin NOT NULL,
  `Rack/Vial` varchar(128) COLLATE utf8_bin NOT NULL,
  `Instrument Name` varchar(128) COLLATE utf8_bin NOT NULL,
  `Channel` varchar(128) COLLATE utf8_bin NOT NULL,
  `Interface Serial #` varchar(128) COLLATE utf8_bin NOT NULL,
  `A/D mV Range` varchar(128) COLLATE utf8_bin NOT NULL,
  `Delay Time` varchar(128) COLLATE utf8_bin NOT NULL,
  `End Time` varchar(128) COLLATE utf8_bin NOT NULL,
  `Sampling Rate` varchar(128) COLLATE utf8_bin NOT NULL,
  `Sample Volume` varchar(128) COLLATE utf8_bin NOT NULL,
  `Area Reject` varchar(128) COLLATE utf8_bin NOT NULL,
  `Sample Amount` varchar(128) COLLATE utf8_bin NOT NULL,
  `Dilution Factor` varchar(128) COLLATE utf8_bin NOT NULL,
  `Data Acquisition Time` varchar(128) COLLATE utf8_bin NOT NULL,
  `Cycle` varchar(128) COLLATE utf8_bin NOT NULL,
  `Raw Data File` varchar(128) COLLATE utf8_bin NOT NULL,
  `Result File` varchar(128) COLLATE utf8_bin NOT NULL,
  `Inst Method` varchar(128) COLLATE utf8_bin NOT NULL,
  `Proc Method` varchar(128) COLLATE utf8_bin NOT NULL,
  `Calib Method` varchar(128) COLLATE utf8_bin NOT NULL,
  `Report Format File` varchar(128) COLLATE utf8_bin NOT NULL,
  `Sequence File` varchar(128) COLLATE utf8_bin NOT NULL,
  `Noise Threshold` varchar(128) COLLATE utf8_bin NOT NULL,
  `Area Threshold` varchar(128) COLLATE utf8_bin NOT NULL,
  `Bunch Factor` varchar(128) COLLATE utf8_bin NOT NULL,
  `Multiplier` varchar(128) COLLATE utf8_bin NOT NULL,
  `Divisor` int(128) NOT NULL,
  `Addend` int(128) NOT NULL,
  `dateadded` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `plots`
--
ALTER TABLE `plots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `txos`
--
ALTER TABLE `txos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `plots`
--
ALTER TABLE `plots`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `txos`
--
ALTER TABLE `txos`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
