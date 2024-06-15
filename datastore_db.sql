-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2024 at 07:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datastore_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `A_ID` int(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `Password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`A_ID`, `Name`, `UserName`, `Password`) VALUES
(1, 'ชนาธิป ชัยภักดี', 'admin111', 'evo1234');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `T_ID` int(255) NOT NULL,
  `T_Name` varchar(255) NOT NULL,
  `T_Status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`T_ID`, `T_Name`, `T_Status`) VALUES
(1, 'นำส่งการไฟฟ้า', 'นำส่งการไฟฟ้า'),
(2, 'นำส่งการไฟฟ้า', 'นำส่งการไฟฟ้า');

-- --------------------------------------------------------

--
-- Table structure for table `view`
--

CREATE TABLE `view` (
  `V_ID` int(255) NOT NULL,
  `V_Name` varchar(255) NOT NULL,
  `V_Province` varchar(255) NOT NULL,
  `V_District` varchar(255) NOT NULL,
  `V_SubDistrict` varchar(255) NOT NULL,
  `V_ExecName` varchar(255) NOT NULL,
  `V_ExecPhone` varchar(255) NOT NULL,
  `V_ExecMail` varchar(255) NOT NULL,
  `V_CoordName1` varchar(255) NOT NULL,
  `V_CoordPhone1` varchar(255) NOT NULL,
  `V_CoordMail1` varchar(255) NOT NULL,
  `V_CoordName2` varchar(255) NOT NULL,
  `V_CoordPhone2` varchar(255) NOT NULL,
  `V_CoordMail2` varchar(255) NOT NULL,
  `V_Sale` varchar(255) NOT NULL,
  `V_Date` varchar(255) NOT NULL,
  `V_Electric_per_year` varchar(255) NOT NULL,
  `V_Electric_per_month` varchar(255) NOT NULL,
  `V_comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `view`
--

INSERT INTO `view` (`V_ID`, `V_Name`, `V_Province`, `V_District`, `V_SubDistrict`, `V_ExecName`, `V_ExecPhone`, `V_ExecMail`, `V_CoordName1`, `V_CoordPhone1`, `V_CoordMail1`, `V_CoordName2`, `V_CoordPhone2`, `V_CoordMail2`, `V_Sale`, `V_Date`, `V_Electric_per_year`, `V_Electric_per_month`, `V_comment`) VALUES
(1, 'โรงเรียนห้วยเหล็กไฟ', 'สกลนคร', 'นิคมน้ำอูน', 'นิคมน้ำอูน', 'นายคำนวน แก้วคำสอน', '095-1686231', '', 'นายศิริศักดิ์ ผลาจันทร์', '085-4630312', '', '', '', '', 'คุณพิเย็น', '11/6/2567', '85,102.73', '7,091.89', 'ขาดบิล 10/66'),
(2, 'โรงเรียนโนนโพธิ์ศรีวิทยาคม\r\n', 'ขอนแก่่น\r\n', 'ชำสูง\r\n', 'บ้านโนน\r\n', 'นายกนต์ธร เหล่ากุนทา\r\n', '083-430-4359\r\n', 'krudon28102513@gmail.com\r\n', 'นางจงกล เวียนปัญญา', '098-128-5339', 'chongkol_noi@gmail.com', 'นายนำชัย ผัดชาสุวรร์', '089-575-1245', 'nam_15@hotmail.com', 'คุณพิเย็น', '11/6/2567\r\n', '321,185.00\r\n', '26,765.42\r\n', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`A_ID`);

--
-- Indexes for table `view`
--
ALTER TABLE `view`
  ADD PRIMARY KEY (`V_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
