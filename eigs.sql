-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 04, 2021 at 11:44 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eigs`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
CREATE TABLE IF NOT EXISTS `administrator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(30) NOT NULL,
  `Surname` varchar(30) NOT NULL,
  `Mtitle` varchar(30) NOT NULL,
  `Phone` varchar(30) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `Email` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`id`, `Firstname`, `Surname`, `Mtitle`, `Phone`, `Password`, `Email`) VALUES
(1, 'Olaleye', 'Akinuli', 'Mr', '265999107724', 'admin', 'leyeaa@unimed.edu.ng');

-- --------------------------------------------------------

--
-- Table structure for table `faculties_dept`
--

DROP TABLE IF EXISTS `faculties_dept`;
CREATE TABLE IF NOT EXISTS `faculties_dept` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `dept` varchar(100) NOT NULL,
  `faculty` varchar(100) NOT NULL,
  `dept_id` varchar(12) NOT NULL,
  `faculty_id` varchar(12) NOT NULL,
  `refDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 COMMENT='Faculties and Dept';

--
-- Dumping data for table `faculties_dept`
--

INSERT INTO `faculties_dept` (`id`, `dept`, `faculty`, `dept_id`, `faculty_id`, `refDate`) VALUES
(1, 'Medicine and Surgery', 'Clinical Sciences', '1', '1', '0000-00-00'),
(2, 'Dentistry', 'Dentistry', '1', '2', '0000-00-00'),
(3, 'Anatomy', 'Basic Medical Sciences', '1', '3', '0000-00-00'),
(4, 'Biochemistry', 'Basic Medical Sciences', '2', '3', '0000-00-00'),
(5, 'Physiology', 'Basic Medical Sciences', '3', '3', '0000-00-00'),
(6, 'Nursing Science', 'Nursing Science', '1', '7', '0000-00-00'),
(7, 'Physiotherapy', 'Medical Rehabilitation', '1', '12', '0000-00-00'),
(8, 'Physics(Electronics Physics)', 'Sciences', '1', '5', '0000-00-00'),
(10, 'Chemistry', 'Sciences', '3', '5', '0000-00-00'),
(11, 'Biological Sciences(Animal and Environmental)', 'Sciences', '4', '5', '0000-00-00'),
(12, 'Biological Sciences(Microbiology)', 'Sciences', '5', '5', '0000-00-00'),
(13, 'Biological Sciences(Plant Biology and Biotechnology)', 'Sciences', '6', '5', '0000-00-00'),
(14, 'Mathematics', 'Sciences', '7', '5', '0000-00-00'),
(15, 'Medical Laboratory Science', 'Allied Health Sciences', '3', '4', '0000-00-00'),
(16, 'Prosthetics and Orthotics', 'Medical Rehabilitation', '2', '12', '2021-01-21'),
(17, 'Science Laboratory Technology', 'Sciences', '8', '5', '2021-01-22'),
(18, 'Food Science', 'Sciences', '9', '5', '2021-01-23'),
(19, 'Human Nutrition and Dietetics', 'Allied Health Sciences', '4', '4', '2021-01-24'),
(20, 'Radiography and Radiation Science', 'Allied Health Sciences', '5', '4', '2021-01-25'),
(21, 'Environmental Health Science', 'Public Health', '1', '11', '2021-01-26'),
(22, 'Community Health Science', 'Public Health', '2', '11', '2021-01-27'),
(23, 'Environmental Management and Toxicology', 'Sciences', '8', '5', '2021-02-08'),
(24, 'Information Technology', 'Sciences', '10', '5', '2021-02-08'),
(25, 'Computer Science', 'Sciences', '11', '5', '2021-02-08'),
(26, 'Physics', 'Sciences', '13', '5', '2021-02-08'),
(27, 'Statistics', 'Sciences', '14', '5', '2021-02-08');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(300) NOT NULL,
  `Name` varchar(1000) NOT NULL,
  `Type` varchar(30) NOT NULL,
  `Size` decimal(10,0) DEFAULT NULL,
  `content` longblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inorg`
--

DROP TABLE IF EXISTS `inorg`;
CREATE TABLE IF NOT EXISTS `inorg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `Phone` varchar(300) NOT NULL,
  `email` varchar(100) NOT NULL,
  `website` varchar(300) NOT NULL,
  `year` varchar(10) NOT NULL,
  `pname` varchar(1000) NOT NULL,
  `type` varchar(30) NOT NULL,
  `size` decimal(10,0) NOT NULL,
  `content` longblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `profilepictures`
--

DROP TABLE IF EXISTS `profilepictures`;
CREATE TABLE IF NOT EXISTS `profilepictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` varchar(30) NOT NULL,
  `Category` varchar(30) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `type` varchar(30) NOT NULL,
  `Size` decimal(10,0) NOT NULL,
  `content` longblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(300) NOT NULL,
  `Surname` varchar(300) NOT NULL,
  `Mtitle` varchar(30) NOT NULL,
  `Faculty` varchar(300) NOT NULL,
  `Department` varchar(300) NOT NULL,
  `Level` varchar(20) NOT NULL,
  `Staffid` varchar(300) NOT NULL,
  `Gender` varchar(20) NOT NULL,
  `Picname` varchar(1000) NOT NULL,
  `Time` bigint(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Staffid` (`Staffid`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Firstname`, `Surname`, `Mtitle`, `Faculty`, `Department`, `Level`, `Staffid`, `Gender`, `Picname`, `Time`) VALUES
(1, 'OLUWATOBI DAVID', 'MUYIWA', 'Mr', 'Clinical Sciences', 'Medicine and Surgery', '400', 'MED/17/2021', 'Male', '76409901BI.jpg', 145010),
(2, 'IFEOLUWA VICTORIA', 'AJISE', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1924', 'F', '20430196FA.jpg', 145010),
(3, 'OLABISI VICTORIA', 'AJIBOLA', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1923', 'F', '97045513DH.jpg', 145010),
(4, 'JUDITH OLUWASEYIFUNMI', 'SHABA', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1959', 'F', '97008254HA.jpg', 145010),
(5, 'ADEBOLA EMMANUEL', 'AYODELE', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/2236', 'M', '97047149HG.jpg', 145010),
(6, 'AMEDU ODION', 'SULE', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1960', 'M', '95684046FJ.jpg', 145010),
(7, 'OLUWASEUN TEMITOPE', 'OGUNBADEWA', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1942', 'F', '97020068DD.jpg', 145010),
(8, 'OMOLOLA KUDIRAT', 'AJAKAYE', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1922', 'F', '96528480AJ.jpg', 145010),
(9, 'IBRAHEEM ADEBAYO', 'OKE', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1944', 'M', '96540011EF.jpg', 145010),
(10, 'COLLINS OLUWASEYI', 'OLAPADE', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1949', 'M', '97151053CF.jpg', 145010),
(11, 'OMOBOLAJI AISHAT', 'FATAI', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1936', 'F', '97039752FE.jpg', 145010),
(12, 'TAYE STELLA', 'OGIDAN', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1941', 'F', '97042771BI.jpg', 145010),
(13, 'DAVID ADEBORI', 'OLOGUNYE', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1951', 'M', '96856773GI.jpg', 145010),
(14, 'EBUNOLUWA MOYOSOLA', 'OLALEYE', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1948', 'F', '97047084HH.jpg', 145010),
(15, 'EBUNOLUWA ESTHER', 'OKUNOLA', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1946', 'F', '97039287JB.jpg', 145010),
(16, 'ETIOSA ABEL', 'IGBINIJESU', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1938', 'M', '97046798GC.jpg', 145010),
(17, 'WALTER EMMANUEL', 'IKEMEFUNA', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1939', 'M', '97049540IB.jpg', 145010),
(18, 'BOLADE ROCK', 'ADEKANBI', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1919', 'M', '97000875JB.jpg', 145010),
(19, 'SEIDOUGHA', 'SORIWEI', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/2326', 'F', '97038141DH.jpg', 145010),
(20, 'AYODEJI DENNIS', 'AKINRINLOLA', '', 'Basic Medical Sciences', 'Anatomy', '200', 'ANA/19/1927', 'M', '97000114EG.jpg', 145010),
(21, 'TEMILOLUWA', 'FADEYI', 'Miss', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1978', 'F', '96432255BC .jpg', 231258),
(22, 'FAVOUR LOVE', 'OYEBADE', 'Miss', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1991', 'F', '97197512DH.jpg', 231258),
(23, 'BOUNTY OGHENEFEJIRO', 'AKPOFURE', 'Miss', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1972', 'F', '96485667CA.jpg', 231258),
(24, 'SAMUEL NELSON', 'AGUNBIADE', 'Mr', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1966', 'M', '97036972FC.jpg', 231258),
(25, 'OLANREWAJU', 'WALE-SADEEQ', 'Mr', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1994', 'M', '97185009AH.jpg', 231258),
(26, 'ADERINSOLA CHRISTIANA', 'AKINFENWA', 'Miss', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1969', 'F', '97077284HF.jpg', 231258),
(27, 'JESUTOMISOLA SUCCESS ', 'OLU-ANTHONY', 'Miss', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1985', 'F', '96550515FI.jpg', 231258),
(28, 'JAMES OLUKUNLE', 'FAGBOHUNLU', 'Mr', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1979', 'M', '95749270DG.jpg', 231258),
(29, 'OLAKUNMI BOLAFOLUWA', 'BAMIDELE', 'Miss', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1975', 'F', '97002034AJ.jpg', 231258),
(30, 'AMOS ODUNAYO', 'IDOWU', 'Mr', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1980', 'M', '95724122BE.jpg', 231258),
(31, 'OLUMIDE TEMITOPE', 'AYEYEMI', 'Mr', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1974', 'M', '97018078IG.jpg', 231258),
(32, 'IFEDAYO ISAAC', 'OMOLE', 'Mr', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1989', 'M', '97002679BE.jpg', 231258),
(33, 'MICHAEL OLASUNKANMI', 'ALADESUYI', 'Mr', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1973', 'M', '97007802GI.jpg', 231258),
(34, 'MOFIYINFOLUWA EBUNOLUWA', 'ADENIBUYAN', 'Miss', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1963', 'F', '96521291EC.jpg', 231258),
(35, 'VICTOR AYOMIDE', 'ADEDEJI', 'Mr', 'Basic Medical Sciences', 'Biochemistry', '200', 'BCM/19/1962', 'M', '97039036GI.jpg', 231258),
(36, 'AYOMIDE EMMANUEL', 'MAKINDE', '', 'Basic Medical Sciences', 'Biochemistry', '300', 'BCM/17/0872', 'M', '20733190EF.jpg', 231258),
(37, 'SAMUEL OLUWATOBI', 'ADESEYE', 'Mr', 'Basic Medical Sciences', 'Biochemistry', '300', 'BCM/18/1392', 'M', '86052629EH.jpg', 231258),
(38, 'Racheal Temitopeoluwa', 'Akindiose', 'Miss', 'Basic Medical Sciences', 'Biochemistry', '300', 'BCM/18/1397', 'F', '86070057EG.jpg', 231258),
(39, 'Stella Sefunmi', 'Ojo', 'Miss', 'Basic Medical Sciences', 'Biochemistry', '300', 'BCM/18/1412', 'F', '86054003JG.jpg', 231258),
(40, 'Nathaniel Babatunde', 'Agboh', 'Mr', 'Basic Medical Sciences', 'Biochemistry', '300', 'BCM/18/1395', 'M', '85832889FF.jpg', 231258),
(41, 'ENIOLA BLESSING', 'ORUKOTAN', 'Mr', 'Basic Medical Sciences', 'Biochemistry', '300', 'BCM/18/1416', 'M', '86067501FB.jpg', 231258);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
