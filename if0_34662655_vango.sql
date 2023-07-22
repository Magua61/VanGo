-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql307.infinityfree.com
-- Generation Time: Jul 22, 2023 at 05:02 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_34662655_vango`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_ID` int(11) NOT NULL,
  `C_FName` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `C_MName` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `C_LName` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `C_Gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `C_Address` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `C_Birthdate` date DEFAULT NULL,
  `C_Email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `C_PhoneNo` char(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Customer_ID`, `C_FName`, `C_MName`, `C_LName`, `C_Gender`, `C_Address`, `C_Birthdate`, `C_Email`, `C_PhoneNo`) VALUES
(1, 'Juan', 'Villaceran', 'Dela Cruz', NULL, 'Palapala, San Ildefonso, Bulacan', NULL, 'jdc@gmail.com', '09795482641'),
(2, 'John', 'William', 'Smith', NULL, '123 Main Street', NULL, 'johnsmith@example.com', '09794522641'),
(3, 'Peter', 'Santos', 'Dela Cruz', NULL, '456 Sampaguita St., Makati City, Metro Manila, Philippines', NULL, 'petdc@example.ph', '09123456789'),
(4, 'James', 'Santos', 'Dela Cruz', NULL, '456 Sampaguita St., Makati City, Metro Manila, Philippines', NULL, 'jamesdc@example.ph', '09123456789'),
(5, 'Maria', 'Santos', 'Cruz', NULL, '789 Rizal Ave., Quezon City, Metro Manila, Philippines', NULL, 'maria.cruz@example.ph', '09123458733'),
(6, 'Carlos', 'Cruz', 'Gonzales', NULL, '789 Oak Street, Cebu City            ', '1998-01-17', 'carlos.gonzales@example.com', '09795488973'),
(7, 'Frank', 'Cruz', 'Dela Cruz', 'Male', '422 Sampaguita St., Makati City, Metro Manila, Philippines', '2015-01-17', 'frank.dc@example.ph', '09789456444'),
(8, 'Luis', 'Santos', 'Sanchez', 'Male', '789 Rizal Ave., Quezon City, Metro Manila, Philippines  ', '1985-09-21', 'luis.sanchez@example.com', '09132245213'),
(10, 'Luka', 'Cruz', 'Dela Cruz', 'Male', '389 Oak Street, Baguio City', '1990-07-11', 'luka.cruz@example.com', '09789456122'),
(11, 'Lester', 'Santos', 'Cruz', 'Male', '456 Gumamela St., Makati City, Metro Manila, Philippines', '1988-07-20', 'lester.cruz@example.com', '09324214213');

-- --------------------------------------------------------

--
-- Table structure for table `customer_profile`
--

CREATE TABLE `customer_profile` (
  `Customer_ID` int(11) DEFAULT NULL,
  `C_ProfilePic` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_profile`
--

INSERT INTO `customer_profile` (`Customer_ID`, `C_ProfilePic`) VALUES
(6, '64ba3d9b34a3d_693be7ecc80ccb76e68666c464fee042.jpg'),
(8, '64bb84eb8ebcf_5b9c36323b7e04311d931dd0aeac7173.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `Owner_ID` int(11) NOT NULL,
  `O_FName` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `O_MName` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `O_LName` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `O_Gender` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `O_Address` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `O_Birthdate` date NOT NULL,
  `O_Email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `O_PhoneNo` char(11) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`Owner_ID`, `O_FName`, `O_MName`, `O_LName`, `O_Gender`, `O_Address`, `O_Birthdate`, `O_Email`, `O_PhoneNo`) VALUES
(1, 'Antonio', 'Santos', 'Fernandez', 'Male', '567 Cedar Street, Baguio City   ', '1983-06-13', 'antonio.fernandez@example.com', '09123456888'),
(3, 'Lucas', 'Gomes', 'Garcia', 'Male', '543 Maple Street, Quezon City              ', '1980-03-16', 'lucas.garcia@example.com', '09998765432'),
(6, 'John', 'Cruz', 'Santos', 'Male', '447 Cedar Street, Baguio City', '1987-06-17', 'john.santos@example.ph', '09789456333'),
(9, 'Luis', 'Santos', 'Santos', 'Male', '447 Cedar Street, Baguio City ', '1992-07-19', 'luis.santos@example.com', '09789456444'),
(11, 'EQE', 'EQWEQ', 'EWEQE', 'Male', 'QWEQE', '2023-07-27', 'dasda@gg.com', '09324521345'),
(12, 'Manuel', 'Cruz', 'Dela Cruz', 'Male', '226 Sampaguita St., Makati City, Metro Manila, Philippines ', '1990-07-22', 'manuel.dc@example.com', '09132452314');

-- --------------------------------------------------------

--
-- Table structure for table `owner_profile`
--

CREATE TABLE `owner_profile` (
  `Owner_ID` int(11) DEFAULT NULL,
  `O_ProfilePic` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `owner_profile`
--

INSERT INTO `owner_profile` (`Owner_ID`, `O_ProfilePic`) VALUES
(1, '64ba37e4f0797_0-2263_dark-anime-wallpaper-anime-wallpaper-dark.jpg'),
(3, '64ba3b6f4bb0f_1601172675683.jpg'),
(9, '64ba67e35b168_2_1017943490086044344.webp'),
(12, '64bb956f311da_1604147265046.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `owner_valid_id`
--

CREATE TABLE `owner_valid_id` (
  `Owner_ID` int(11) DEFAULT NULL,
  `O_ValidID` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `owner_valid_id`
--

INSERT INTO `owner_valid_id` (`Owner_ID`, `O_ValidID`) VALUES
(1, 'uploads/validids/64a2a607b6be6_Screenshot 2023-07-03 183148.png'),
(3, 'uploads/validids/64a75508052f0_Valid ID.png'),
(6, 'uploads/validids/64b46e3160c7e_Valid ID.png'),
(9, '64ba65b284ad4_00000IMG_00000_BURST20200209175020789_COVER.jpg'),
(12, '64bb952070d2f_Valid ID.png');

-- --------------------------------------------------------

--
-- Table structure for table `password`
--

CREATE TABLE `password` (
  `User_Email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Hash_Password` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Salt_Password` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password`
--

INSERT INTO `password` (`User_Email`, `Hash_Password`, `Salt_Password`) VALUES
('antonio.fernandez@example.com', '747838fd95b684fac806c20eea228bc81fd58e7e24c3ad70bfe4e42b766cadee', 'db993a7d7d67f73eadd30cee99c8ccc2'),
('carlos.gonzales@example.com', '1bb215a7de20813fc498986b97033faab09f8eaa14ec763b7d54dc6d39803d11', 'b8fc15a7ed3e7b85ec865988b9bdc5b7'),
('dasda@gg.com', '26f716b9c719de68e0e922ccaaa246d9cf1bb7c134f3e3099e75b308ddfe466e', 'b91f3876df5802dcc30c3324bc99f4f0'),
('frank.dc@example.ph', 'c99c7b4528917061a438b07d92a96d0cec953849779411cecfdcdb1e3c11698d', '6c5c338a144f0d44b4f12f253c93a642'),
('jamesdc@example.ph', 'dec213d9a2710457a4de6cf2507495b35c2ee022209aa3fce5bcf35ddbd49c5f', '6388451f6ee781d40e458dd2d76e94b0'),
('jdc@gmail.com', 'e7f9d80665c9a826a0b05da0f2070d32c7f1254b1745ef9ae19e68ff1d87f84e', 'a1b2c3d4e5f6g7h8'),
('john.santos@example.ph', '3f18bbe9c2bb7f1f2e792eb5dcb8e42eaa322e1b04eb2227af70afd062a35766', '2c778e9868f84b43cb69036c67402b90'),
('johnsmith@example.com', '0a3c08075fadf8b36185ef55120422c26615a9ed1ec868a4c18557e601c1e7d6', '0aa47b6117f286bb71dc1a0ec191ed53'),
('lester.cruz@example.com', '859870c87d04d79bde1726736f5f8b62d8555aa9729dd9cf3a41c43187ad49ba', 'a54f376a4be5bbc725ff7de5b67fcab0'),
('lucas.garcia@example.com', 'dfe2d5c3ce5d02ade317dcbff20ed7c7724b27d1fbbc41b3b9d6168cfc56ab9a', '6283eb1441e7133f364102e6919161f6'),
('luis.sanchez@example.com', '9ef51bf1c22e6bf52593549461e90d30e17e2da18311f62a7cc56be4f64d2749', 'e49c7ff326d4779d85a84247807cf409'),
('luis.santos@example.com', 'c79835578dd7a7a45eae85c1ff0f94e429712129d2b72a80a24b4472542f80a7', 'e288f7d3225af8701176e6af876b6c55'),
('luka.cruz@example.com', 'ee72367955f46404b639cbbc6ab1d84f211353cbbcfef86cbfbdc99826dbc282', '9868157aae1ef4dd4c2cdde28cd800e8'),
('manuel.dc@example.com', '544008d8304ba385a3b0e3e8706f62e4a3c29e8fc8d8347706fa54cb672c2701', '9035b413e7ea5035331c6c91e4f5696c'),
('maria.cruz@example.ph', '72ae9fc6eea47ff62027c89ede6a5f02c6b888397294e036e4b6c1c811704d80', '68fa69badef4caba58fc798d586b3aaa'),
('petdc@example.ph', '8d6943814a0b0b49c1b25b9ae566bac4453d1eaf709d510f3d14e9b5aa0a8e47', 'a8c73c5459eb595c4ccea568d508ca41');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payment_ID` int(11) NOT NULL,
  `Rental_ID` int(11) DEFAULT NULL,
  `Payment_Amount` decimal(10,2) NOT NULL,
  `Payment_Date_Time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`Payment_ID`, `Rental_ID`, `Payment_Amount`, `Payment_Date_Time`) VALUES
(1, 2, '18000.00', '2023-07-10 10:59:17'),
(4, 5, '18000.00', '2023-07-12 06:09:17'),
(5, 6, '13500.00', '2023-07-17 06:55:31'),
(6, 7, '12000.00', '2023-07-17 07:06:15'),
(7, 8, '9000.00', '2023-07-19 14:47:51'),
(17, 18, '11000.00', '2023-07-21 19:20:23'),
(18, 19, '5000.00', '2023-07-21 19:22:20'),
(19, 20, '13500.00', '2023-07-21 19:32:57'),
(20, 21, '13500.00', '2023-07-22 06:11:23'),
(21, 22, '15000.00', '2023-07-22 06:24:43'),
(22, 23, '10000.00', '2023-07-22 06:26:51'),
(23, 24, '20000.00', '2023-07-22 15:06:34'),
(24, 25, '15000.00', '2023-07-22 15:26:44'),
(25, 26, '27500.00', '2023-07-22 16:50:25'),
(26, 27, '13500.00', '2023-07-22 16:51:23');

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `History_ID` int(11) NOT NULL,
  `Payment_ID` int(11) NOT NULL,
  `Rental_ID` int(11) DEFAULT NULL,
  `Payment_Amount` decimal(10,2) NOT NULL,
  `Payment_Date_Time` datetime NOT NULL,
  `Action` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Action_Datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`History_ID`, `Payment_ID`, `Rental_ID`, `Payment_Amount`, `Payment_Date_Time`, `Action`, `Action_Datetime`) VALUES
(1, 7, 8, '9000.00', '2023-07-19 14:47:51', 'Insert', '2023-07-19 14:47:51'),
(2, 8, 9, '25000.00', '2023-07-21 03:44:39', 'Insert', '2023-07-21 03:44:39'),
(3, 9, 10, '4500.00', '2023-07-21 04:18:31', 'Insert', '2023-07-21 04:18:31'),
(4, 10, 11, '4500.00', '2023-07-21 04:19:22', 'Insert', '2023-07-21 04:19:22'),
(5, 11, 12, '4500.00', '2023-07-21 04:19:59', 'Insert', '2023-07-21 04:19:59'),
(6, 12, 13, '5500.00', '2023-07-21 04:21:47', 'Insert', '2023-07-21 04:21:47'),
(7, 13, 14, '90000.00', '2023-07-21 04:30:13', 'Insert', '2023-07-21 04:30:13'),
(8, 14, 15, '90000.00', '2023-07-21 04:31:07', 'Insert', '2023-07-21 04:31:07'),
(9, 15, 16, '27500.00', '2023-07-21 04:38:34', 'Insert', '2023-07-21 04:38:34'),
(10, 16, 17, '16500.00', '2023-07-21 04:40:45', 'Insert', '2023-07-21 04:40:45'),
(11, 17, 18, '11000.00', '2023-07-21 19:20:23', 'Insert', '2023-07-21 19:20:23'),
(12, 18, 19, '5000.00', '2023-07-21 19:22:20', 'Insert', '2023-07-21 19:22:20'),
(13, 19, 20, '13500.00', '2023-07-21 19:32:57', 'Insert', '2023-07-21 19:32:57'),
(14, 20, 21, '13500.00', '2023-07-22 06:11:23', 'Insert', '2023-07-22 06:11:23'),
(15, 21, 22, '15000.00', '2023-07-22 06:24:43', 'Insert', '2023-07-22 06:24:43'),
(16, 22, 23, '10000.00', '2023-07-22 06:26:51', 'Insert', '2023-07-22 06:26:51'),
(17, 23, 24, '20000.00', '2023-07-22 15:06:34', 'Insert', '2023-07-22 03:06:35'),
(18, 24, 25, '15000.00', '2023-07-22 15:26:44', 'Insert', '2023-07-22 03:26:45'),
(19, 25, 26, '27500.00', '2023-07-22 16:50:25', 'Insert', '2023-07-22 04:50:26'),
(20, 26, 27, '13500.00', '2023-07-22 16:51:23', 'Insert', '2023-07-22 04:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `rental`
--

CREATE TABLE `rental` (
  `Rental_ID` int(11) NOT NULL,
  `Van_ID` int(11) DEFAULT NULL,
  `Customer_ID` int(11) DEFAULT NULL,
  `Destination` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Pickup_Address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Pickup_Date` date DEFAULT NULL,
  `Pickup_Time` time DEFAULT NULL,
  `Return_Date` date DEFAULT NULL,
  `Return_Time` time DEFAULT NULL,
  `Rental_Status` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rental`
--

INSERT INTO `rental` (`Rental_ID`, `Van_ID`, `Customer_ID`, `Destination`, `Pickup_Address`, `Pickup_Date`, `Pickup_Time`, `Return_Date`, `Return_Time`, `Rental_Status`) VALUES
(2, 2, 6, 'Ilocos Norte', 'SM Manila', '2023-07-12', '10:00:00', '2023-07-15', '10:00:00', 'Cancelled'),
(5, 2, 6, 'Bagaiso', 'aaeqweqew', '2023-07-17', '10:00:00', '2023-07-20', '10:00:00', 'Completed'),
(6, 6, 7, 'Morong, Bataan', 'PUP Manila', '2023-07-25', '05:00:00', '2023-07-28', '05:00:00', 'Pending'),
(7, 2, 7, 'Batangas', 'PUP Manila', '2023-07-25', '08:00:00', '2023-07-27', '08:00:00', 'Pending'),
(8, 6, 6, 'Baler', 'PUP Sta. Mesa', '2023-07-06', '04:00:00', '2023-07-08', '04:00:00', 'Completed'),
(18, 1, 8, 'Baguio', 'PUP Sta. Mesa', '2023-07-06', '21:19:00', '2023-07-08', '21:19:00', 'Cancelled'),
(19, 2, 8, 'Laguna', 'Luneta', '2023-07-13', '21:22:00', '2023-07-14', '21:22:00', 'Completed'),
(20, 1, 8, 'Bataan', 'Luneta', '2023-06-26', '13:32:00', '2023-06-29', '13:32:00', 'Completed'),
(21, 6, 6, 'Baguio', 'PUP Sta. Mesa', '2023-08-02', '06:00:00', '2023-08-05', '06:00:00', 'Cancelled'),
(22, 2, 8, 'La Union', 'PUP Manila', '2023-08-22', '06:00:00', '2023-08-25', '06:00:00', 'Cancelled'),
(23, 2, 8, 'Bataan', 'PUP Manila', '2023-08-17', '06:00:00', '2023-08-19', '06:00:00', 'Cancelled'),
(24, 2, 6, 'Batangas', 'Cainta Elementary School', '2023-08-11', '15:05:00', '2023-08-15', '15:05:00', 'Pending'),
(25, 2, 8, 'Baler', 'PUP Manila', '2023-09-22', '03:00:00', '2023-09-25', '03:00:00', 'Cancelled'),
(26, 1, 11, 'Albay', 'PUP Manila', '2023-07-28', '05:00:00', '2023-08-02', '05:00:00', 'Pending'),
(27, 1, 11, 'Cavite', 'PUP Manila', '2023-07-04', '18:51:00', '2023-07-07', '18:51:00', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `rental_without_driver`
--

CREATE TABLE `rental_without_driver` (
  `Rental_ID` int(11) DEFAULT NULL,
  `Return_Address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rental_without_driver`
--

INSERT INTO `rental_without_driver` (`Rental_ID`, `Return_Address`) VALUES
(6, 'PUP Manila'),
(8, 'PUP Sta. Mesa'),
(19, 'Luneta'),
(20, 'Luneta'),
(21, 'PUP Sta. Mesa'),
(22, 'PUP Manila'),
(23, 'PUP Manila'),
(24, 'Cainta Elementary School'),
(25, 'PUP Manila'),
(27, 'PUP Manila');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `Review_ID` int(11) NOT NULL,
  `Review_Rating` int(11) NOT NULL,
  `Review_Comment` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Review_Datetime` datetime NOT NULL,
  `Rental_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`Review_ID`, `Review_Rating`, `Review_Comment`, `Review_Datetime`, `Rental_ID`) VALUES
(5, 5, 'Excellent service!!!', '2023-07-12 09:17:49', 5),
(6, 5, 'Excellent van, excellent service!!', '2023-07-19 06:37:12', 5),
(32, 5, 'Excellent van!!', '2023-07-19 14:50:28', 8),
(38, 5, 'Sobrang tindi netoo. Rent nyo na guyssss!!', '2023-07-22 03:33:20', 19),
(39, 5, 'Excellent!!', '2023-07-22 04:52:31', 27);

-- --------------------------------------------------------

--
-- Table structure for table `review_photo`
--

CREATE TABLE `review_photo` (
  `Review_ID` int(11) DEFAULT NULL,
  `Review_Photo` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review_photo`
--

INSERT INTO `review_photo` (`Review_ID`, `Review_Photo`) VALUES
(6, '64b714186452a_4_694361646142849078.webp'),
(32, '64b787b44ee01_3c1a26e92d0b419b0025f77ea6b1a352.jpg'),
(38, '64bb863e9488a_1601172523823.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_Email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `User_Type` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `User_RegiDatetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`User_Email`, `User_Type`, `User_RegiDatetime`) VALUES
('antonio.fernandez@example.com', 'owner', '2023-06-18 01:32:02'),
('carlos.gonzales@example.com', 'customer', '2023-07-01 01:32:02'),
('dasda@gg.com', 'owner', '2023-07-21 23:44:54'),
('frank.dc@example.ph', 'customer', NULL),
('jamesdc@example.ph', 'customer', NULL),
('jdc@gmail.com', 'customer', NULL),
('john.santos@example.ph', 'owner', '2023-06-18 01:32:02'),
('johnsmith@example.com', 'customer', NULL),
('lester.cruz@example.com', 'customer', '2023-07-22 16:48:54'),
('lucas.garcia@example.com', 'owner', '2023-06-18 01:32:02'),
('luis.sanchez@example.com', 'customer', '2023-07-21 18:50:34'),
('luis.santos@example.com', 'owner', '2023-07-21 19:01:22'),
('luka.cruz@example.com', 'customer', '2023-07-21 18:58:37'),
('manuel.dc@example.com', 'owner', '2023-07-22 16:33:39'),
('maria.cruz@example.ph', 'customer', NULL),
('petdc@example.ph', 'customer', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `van`
--

CREATE TABLE `van` (
  `Van_ID` int(11) NOT NULL,
  `V_PlateNo` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `V_Make` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `V_Model` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `V_Year` int(11) NOT NULL,
  `V_Capacity` int(11) NOT NULL,
  `Owner_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `van`
--

INSERT INTO `van` (`Van_ID`, `V_PlateNo`, `V_Make`, `V_Model`, `V_Year`, `V_Capacity`, `Owner_ID`) VALUES
(1, 'EG 7777', 'Toyota', 'Hiace', 2019, 16, 1),
(2, 'GG 8231', 'Toyota', 'Hiace Super Grandia', 2023, 16, 3),
(6, 'VB 4231', 'Nissan', 'NV350', 2023, 15, 6),
(9, 'VB 4222', 'Nissan', 'NV350', 2023, 15, 9),
(11, 'PQ 5555', 'Toyota', 'Hiace', 2020, 16, 12);

-- --------------------------------------------------------

--
-- Table structure for table `van_document`
--

CREATE TABLE `van_document` (
  `Van_ID` int(11) DEFAULT NULL,
  `V_OR` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `V_CR` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `van_document`
--

INSERT INTO `van_document` (`Van_ID`, `V_OR`, `V_CR`) VALUES
(1, '64bb87bd0418a_OR.jpg', '64ba386a5fa8e_CR.jpg'),
(2, '64bb85a132a38_OR.jpg', '64ba3bafde280_CR.jpg'),
(6, 'uploads/receipts/64b46e316167d_or.png', 'uploads/certificates/64b46e3161177_CR.png'),
(9, '64ba65b2867bb_00000IMG_00000_BURST20200209175020789_COVER.jpg', '64ba65b2859f0_00000IMG_00000_BURST20200209175020789_COVER.jpg'),
(11, '64bb9520716cf_OR.jpg', '64bb9520712da_64ba3814a74ac_CR.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `van_photo`
--

CREATE TABLE `van_photo` (
  `Van_ID` int(11) DEFAULT NULL,
  `V_Photo` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `van_photo`
--

INSERT INTO `van_photo` (`Van_ID`, `V_Photo`) VALUES
(1, '64bb87bd03df6_hiace-not-superr.jpg'),
(2, '64ba3bafdd44f_64a7540de37aa_Toyota_Hiace_Super_Grandia.jpg'),
(6, '64b46e3162d0b_nissan.jpg'),
(9, '64ba65b2872a1_00000IMG_00000_BURST20200209175020789_COVER.jpg'),
(11, '64bb952074015_hiacee.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `van_rate`
--

CREATE TABLE `van_rate` (
  `Van_ID` int(11) NOT NULL,
  `V_Rate` decimal(20,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `van_rate`
--

INSERT INTO `van_rate` (`Van_ID`, `V_Rate`) VALUES
(1, '4500.00'),
(2, '5000.00'),
(6, '4500.00'),
(11, '4500.00');

-- --------------------------------------------------------

--
-- Table structure for table `van_unavailable_date`
--

CREATE TABLE `van_unavailable_date` (
  `XDate_ID` int(11) NOT NULL,
  `Van_ID` int(11) DEFAULT NULL,
  `Start_Date` date NOT NULL,
  `End_Date` date DEFAULT NULL,
  `Status` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT 'Unavailable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `van_unavailable_date`
--

INSERT INTO `van_unavailable_date` (`XDate_ID`, `Van_ID`, `Start_Date`, `End_Date`, `Status`) VALUES
(1, 1, '2023-07-10', '2023-07-14', 'Unavailable'),
(2, 1, '2023-07-18', '2023-07-21', 'Unavailable'),
(4, 1, '2023-07-24', '2023-07-26', 'Unavailable'),
(11, 2, '2023-07-17', '2023-07-20', 'Completed'),
(12, 2, '2023-07-31', '2023-08-02', 'Unavailable'),
(13, 6, '2023-07-25', '2023-07-28', 'Booked'),
(14, 2, '2023-07-25', '2023-07-27', 'Booked'),
(28, 9, '2023-07-11', '2023-07-13', 'Unavailable'),
(29, 9, '2023-07-19', '2023-07-21', 'Unavailable'),
(31, 2, '2023-07-13', '2023-07-14', 'Completed'),
(32, 1, '2023-06-26', '2023-06-29', 'Completed'),
(37, 2, '2023-08-04', '2023-08-05', 'Unavailable'),
(38, 2, '2023-08-11', '2023-08-15', 'Booked'),
(40, 11, '2023-07-11', '2023-07-13', 'Unavailable'),
(41, 11, '2023-07-17', '2023-07-19', 'Unavailable'),
(42, 1, '2023-07-28', '2023-08-02', 'Booked'),
(43, 1, '2023-07-04', '2023-07-07', 'Completed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_ID`),
  ADD UNIQUE KEY `C_Email_UNIQUE` (`C_Email`);

--
-- Indexes for table `customer_profile`
--
ALTER TABLE `customer_profile`
  ADD KEY `FK_C_ProfilePic_idx` (`Customer_ID`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`Owner_ID`),
  ADD UNIQUE KEY `O_Email_UNIQUE` (`O_Email`);

--
-- Indexes for table `owner_profile`
--
ALTER TABLE `owner_profile`
  ADD KEY `FK_O_ProfilePic_idx` (`Owner_ID`);

--
-- Indexes for table `owner_valid_id`
--
ALTER TABLE `owner_valid_id`
  ADD KEY `FK_O_ValidID_idx` (`Owner_ID`);

--
-- Indexes for table `password`
--
ALTER TABLE `password`
  ADD UNIQUE KEY `C_Email_UNIQUE` (`User_Email`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payment_ID`),
  ADD KEY `FK_Rental_ID_idx` (`Rental_ID`);

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`History_ID`);

--
-- Indexes for table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`Rental_ID`),
  ADD KEY `FK_Van_ID_idx` (`Van_ID`),
  ADD KEY `FKK_Customer_ID_idx` (`Customer_ID`);

--
-- Indexes for table `rental_without_driver`
--
ALTER TABLE `rental_without_driver`
  ADD KEY `FK_WODriver_Rental_ID_idx` (`Rental_ID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`Review_ID`),
  ADD KEY `FK_RentalID_idx` (`Rental_ID`);

--
-- Indexes for table `review_photo`
--
ALTER TABLE `review_photo`
  ADD KEY `FK_Review_Photo_idx` (`Review_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `User_Email_UNIQUE` (`User_Email`);

--
-- Indexes for table `van`
--
ALTER TABLE `van`
  ADD PRIMARY KEY (`Van_ID`),
  ADD KEY `FK_Van_idx` (`Owner_ID`);

--
-- Indexes for table `van_document`
--
ALTER TABLE `van_document`
  ADD KEY `FK_Document_idx` (`Van_ID`);

--
-- Indexes for table `van_photo`
--
ALTER TABLE `van_photo`
  ADD KEY `FK_V_Photo_idx` (`Van_ID`);

--
-- Indexes for table `van_rate`
--
ALTER TABLE `van_rate`
  ADD KEY `FK_VanRate_Van_ID_idx` (`Van_ID`);

--
-- Indexes for table `van_unavailable_date`
--
ALTER TABLE `van_unavailable_date`
  ADD PRIMARY KEY (`XDate_ID`),
  ADD KEY `FK_Van_ID_idx` (`Van_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Customer_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `Owner_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `History_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `rental`
--
ALTER TABLE `rental`
  MODIFY `Rental_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `Review_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `van`
--
ALTER TABLE `van`
  MODIFY `Van_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `van_unavailable_date`
--
ALTER TABLE `van_unavailable_date`
  MODIFY `XDate_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_profile`
--
ALTER TABLE `customer_profile`
  ADD CONSTRAINT `FK_C_ProfilePic` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `owner_profile`
--
ALTER TABLE `owner_profile`
  ADD CONSTRAINT `FK_O_ProfilePic` FOREIGN KEY (`Owner_ID`) REFERENCES `owner` (`Owner_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `owner_valid_id`
--
ALTER TABLE `owner_valid_id`
  ADD CONSTRAINT `FK_O_ValidID` FOREIGN KEY (`Owner_ID`) REFERENCES `owner` (`Owner_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `password`
--
ALTER TABLE `password`
  ADD CONSTRAINT `FK_Password` FOREIGN KEY (`User_Email`) REFERENCES `user` (`User_Email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `FK_Rental_ID` FOREIGN KEY (`Rental_ID`) REFERENCES `rental` (`Rental_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `FK_Rental_Customer_ID` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Rental_Van_ID` FOREIGN KEY (`Van_ID`) REFERENCES `van` (`Van_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rental_without_driver`
--
ALTER TABLE `rental_without_driver`
  ADD CONSTRAINT `FK_WODriver_Rental_ID` FOREIGN KEY (`Rental_ID`) REFERENCES `rental` (`Rental_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `FK_RentalID` FOREIGN KEY (`Rental_ID`) REFERENCES `rental` (`Rental_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_photo`
--
ALTER TABLE `review_photo`
  ADD CONSTRAINT `FK_Review_Photo` FOREIGN KEY (`Review_ID`) REFERENCES `review` (`Review_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `van_document`
--
ALTER TABLE `van_document`
  ADD CONSTRAINT `FK_Document` FOREIGN KEY (`Van_ID`) REFERENCES `van` (`Van_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `van_photo`
--
ALTER TABLE `van_photo`
  ADD CONSTRAINT `FK_V_Photo` FOREIGN KEY (`Van_ID`) REFERENCES `van` (`Van_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `van_rate`
--
ALTER TABLE `van_rate`
  ADD CONSTRAINT `FK_VanRate_Van_ID` FOREIGN KEY (`Van_ID`) REFERENCES `van` (`Van_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `van_unavailable_date`
--
ALTER TABLE `van_unavailable_date`
  ADD CONSTRAINT `FK_Van_ID` FOREIGN KEY (`Van_ID`) REFERENCES `van` (`Van_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
