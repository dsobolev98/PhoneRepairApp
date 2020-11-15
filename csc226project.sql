-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2020 at 12:51 AM
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
-- Database: `csc226project`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

CREATE TABLE `checkout` (
  `TRANS_ID` int(11) NOT NULL,
  `CUS_ID` int(11) NOT NULL,
  `REPAIR_ID` int(11) NOT NULL,
  `AMOUNT_DUE` double(10,2) NOT NULL,
  `AMOUNT_PAID` double(10,2) NOT NULL,
  `DATE_POSTED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `checkout_billing_info`
--

CREATE TABLE `checkout_billing_info` (
  `TRANS_ID` int(11) NOT NULL,
  `FIRST_NAME` varchar(40) NOT NULL,
  `LAST_NAME` varchar(40) NOT NULL,
  `ADDRESS` varchar(100) NOT NULL,
  `CITY` varchar(40) NOT NULL,
  `STATE` varchar(40) NOT NULL,
  `ZIP` varchar(5) NOT NULL,
  `CARD_NUM` varchar(16) NOT NULL,
  `EXP_DATE` varchar(5) NOT NULL,
  `CCV` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `checkout_shipping_info`
--

CREATE TABLE `checkout_shipping_info` (
  `TRANS_ID` int(11) NOT NULL,
  `FIRST_NAME` varchar(40) NOT NULL,
  `LAST_NAME` varchar(40) NOT NULL,
  `ADDRESS` varchar(100) NOT NULL,
  `CITY` varchar(40) NOT NULL,
  `STATE` varchar(40) NOT NULL,
  `ZIP` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer_profile`
--

CREATE TABLE `customer_profile` (
  `FIRSTNAME` varchar(40) NOT NULL,
  `LASTNAME` varchar(40) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `PHONE_NUM` varchar(10) NOT NULL,
  `ADDRESS` varchar(100) NOT NULL,
  `CITY` varchar(45) NOT NULL,
  `STATE` varchar(2) NOT NULL,
  `ZIP` varchar(5) NOT NULL,
  `CUSTOMER_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employee_profile`
--

CREATE TABLE `employee_profile` (
  `FIRSTNAME` varchar(40) NOT NULL,
  `LASTNAME` varchar(40) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `PHONE_NUM` varchar(10) NOT NULL,
  `PROFILE_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee_profile`
--

INSERT INTO `employee_profile` (`FIRSTNAME`, `LASTNAME`, `EMAIL`, `PHONE_NUM`, `PROFILE_ID`) VALUES
('ADMIN', 'ADMIN', 'ADMIN@IREAIR.COM', '3475559020', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inquiry`
--

CREATE TABLE `inquiry` (
  `REPAIR_ID` int(11) NOT NULL,
  `CUS_ID` int(11) NOT NULL,
  `MAKE` varchar(45) NOT NULL,
  `MODEL` varchar(45) NOT NULL,
  `SERIAL_NUM` varchar(100) NOT NULL,
  `PROBLEM` text NOT NULL,
  `IMAGE` varchar(100) DEFAULT NULL,
  `DATE_POSTED` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `ID` int(11) NOT NULL,
  `USERNAME` varchar(20) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL,
  `TYPE` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `profile` 
--

INSERT INTO `profile` (`ID`, `USERNAME`, `PASSWORD`, `TYPE`) VALUES
(1, 'worker1', '$2y$10$Ugy3aaFGpe03dDeI5dJmmO/fS65cWm3DFuD9d7OZGsEMAGy0FV6Jy', 'e');

-- --------------------------------------------------------

--
-- Table structure for table `status_estimate`
--

CREATE TABLE `status_estimate` (
  `STATUS_ID` int(11) NOT NULL,
  `REPAIR_ID` int(11) NOT NULL,
  `REPAIRABLE` tinyint(1) NOT NULL,
  `ESTIMATE` varchar(100) DEFAULT NULL,
  `DATE_POSTED` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `status_invoice`
--

CREATE TABLE `status_invoice` (
  `STATUS_ID` int(11) NOT NULL,
  `REPAIR_ID` int(11) NOT NULL,
  `INVOICE` varchar(100) DEFAULT NULL,
  `DATE_POSTED` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `status_shipping`
--

CREATE TABLE `status_shipping` (
  `STATUS_ID` int(11) NOT NULL,
  `REPAIR_ID` int(11) NOT NULL,
  `TRACKING_NUM` varchar(22) DEFAULT NULL,
  `DATE_POSTED` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`TRANS_ID`),
  ADD KEY `CUS_ID` (`CUS_ID`),
  ADD KEY `REPAIR_ID` (`REPAIR_ID`);

--
-- Indexes for table `checkout_billing_info`
--
ALTER TABLE `checkout_billing_info`
  ADD UNIQUE KEY `TRANS_ID` (`TRANS_ID`);

--
-- Indexes for table `checkout_shipping_info`
--
ALTER TABLE `checkout_shipping_info`
  ADD UNIQUE KEY `TRANS_ID` (`TRANS_ID`);

--
-- Indexes for table `customer_profile`
--
ALTER TABLE `customer_profile`
  ADD PRIMARY KEY (`FIRSTNAME`,`LASTNAME`,`EMAIL`),
  ADD KEY `CUSTOMER_ID` (`CUSTOMER_ID`);

--
-- Indexes for table `employee_profile`
--
ALTER TABLE `employee_profile`
  ADD PRIMARY KEY (`FIRSTNAME`,`LASTNAME`,`EMAIL`),
  ADD KEY `PROFILE_ID` (`PROFILE_ID`);

--
-- Indexes for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`REPAIR_ID`),
  ADD KEY `CUS_ID` (`CUS_ID`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `USERNAME` (`USERNAME`);

--
-- Indexes for table `status_estimate`
--
ALTER TABLE `status_estimate`
  ADD PRIMARY KEY (`STATUS_ID`),
  ADD KEY `REPAIR_ID` (`REPAIR_ID`);

--
-- Indexes for table `status_invoice`
--
ALTER TABLE `status_invoice`
  ADD PRIMARY KEY (`STATUS_ID`),
  ADD KEY `REPAIR_ID` (`REPAIR_ID`);

--
-- Indexes for table `status_shipping`
--
ALTER TABLE `status_shipping`
  ADD PRIMARY KEY (`STATUS_ID`),
  ADD KEY `REPAIR_ID` (`REPAIR_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkout`
--
ALTER TABLE `checkout`
  ADD CONSTRAINT `checkout_ibfk_1` FOREIGN KEY (`CUS_ID`) REFERENCES `profile` (`ID`),
  ADD CONSTRAINT `checkout_ibfk_2` FOREIGN KEY (`REPAIR_ID`) REFERENCES `inquiry` (`REPAIR_ID`);

--
-- Constraints for table `checkout_billing_info`
--
ALTER TABLE `checkout_billing_info`
  ADD CONSTRAINT `checkout_billing_info_ibfk_1` FOREIGN KEY (`TRANS_ID`) REFERENCES `checkout` (`TRANS_ID`);

--
-- Constraints for table `checkout_shipping_info`
--
ALTER TABLE `checkout_shipping_info`
  ADD CONSTRAINT `checkout_shipping_info_ibfk_1` FOREIGN KEY (`TRANS_ID`) REFERENCES `checkout` (`TRANS_ID`);

--
-- Constraints for table `customer_profile`
--
ALTER TABLE `customer_profile`
  ADD CONSTRAINT `customer_profile_ibfk_1` FOREIGN KEY (`CUSTOMER_ID`) REFERENCES `profile` (`ID`);

--
-- Constraints for table `employee_profile`
--
ALTER TABLE `employee_profile`
  ADD CONSTRAINT `employee_profile_ibfk_1` FOREIGN KEY (`PROFILE_ID`) REFERENCES `profile` (`ID`);

--
-- Constraints for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD CONSTRAINT `inquiry_ibfk_1` FOREIGN KEY (`CUS_ID`) REFERENCES `profile` (`ID`);

--
-- Constraints for table `status_estimate`
--
ALTER TABLE `status_estimate`
  ADD CONSTRAINT `status_estimate_ibfk_1` FOREIGN KEY (`REPAIR_ID`) REFERENCES `inquiry` (`REPAIR_ID`);

--
-- Constraints for table `status_invoice`
--
ALTER TABLE `status_invoice`
  ADD CONSTRAINT `status_invoice_ibfk_1` FOREIGN KEY (`REPAIR_ID`) REFERENCES `inquiry` (`REPAIR_ID`);

--
-- Constraints for table `status_shipping`
--
ALTER TABLE `status_shipping`
  ADD CONSTRAINT `status_shipping_ibfk_1` FOREIGN KEY (`REPAIR_ID`) REFERENCES `inquiry` (`REPAIR_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
