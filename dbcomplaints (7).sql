-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 06:58 PM
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
-- Database: `dbcomplaints`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `complainant` varchar(255) NOT NULL,
  `complaint_person` varchar(255) DEFAULT NULL,
  `respondent` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `certificate_text` text NOT NULL,
  `secretary` varchar(255) NOT NULL,
  `captain` varchar(255) NOT NULL,
  `left_logo` varchar(255) DEFAULT NULL,
  `right_logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `barangay`, `municipality`, `province`, `complainant`, `complaint_person`, `respondent`, `date_created`, `certificate_text`, `secretary`, `captain`, `left_logo`, `right_logo`) VALUES
(8, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-20 00:32:00', '&quot;I need a certificate for a user who successfully uploaded a file to our secure server. The certificate should be issued by &#039;SecureFile Solutions&#039; and include the user&#039;s name, the file name, and the date and time of the upload. The purpose is to provide proof of successful upload.&quot;\r\n\r\n\r\n', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(9, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-20 00:32:00', '&quot;I need a certificate for a user who successfully uploaded a file to our secure server. The certificate should be issued by &#039;SecureFile Solutions&#039; and include the user&#039;s name, the file name, and the date and time of the upload. The purpose is to provide proof of successful upload.&quot;\r\n\r\n\r\n', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(10, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-20 00:32:00', '&quot;I need a certificate for a user who successfully uploaded a file to our secure server. The certificate should be issued by &#039;SecureFile Solutions&#039; and include the user&#039;s name, the file name, and the date and time of the upload. The purpose is to provide proof of successful upload.&quot;\r\n\r\n\r\n', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(11, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-20 00:32:00', '&quot;I need a certificate for a user who successfully uploaded a file to our secure server. The certificate should be issued by &#039;SecureFile Solutions&#039; and include the user&#039;s name, the file name, and the date and time of the upload. The purpose is to provide proof of successful upload.&quot;\r\n\r\n\r\n', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(12, 'San Fabian', 'sds', 'sds', 'sds', 'sdsd', 'sds', '2025-03-01 00:39:00', 'sdsdsvfdsghdturtyjfthrdfddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd', 'sds', 'sdsd', 'uploads/1.png', 'uploads/1.png'),
(13, 'San Fabian', 'sds', 'sds', 'sds', 'sdsd', 'sds', '2025-03-01 00:39:00', 'sdsdsvfdsghdturtyjfthrdfddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd', 'sds', 'sdsd', 'uploads/1.png', 'uploads/1.png'),
(14, 'San Fabian', 'sds', 'sds', 'sds', 'sdsd', 'sds', '2025-03-01 00:39:00', 'sdsdsvfdsghdturtyjfthrdfddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd', 'sds', 'sdsd', 'uploads/1.png', 'uploads/1.png'),
(15, 'San Fabian', 'sds', 'sds', 'sds', 'sdsd', 'sds', '2025-03-01 00:39:00', 'sdsdsvfdsghdturtyjfthrdfddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd', 'sds', 'sdsd', 'uploads/1.png', 'uploads/1.png'),
(16, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 12:40:00', 'zsdfgsdxghrstdfjfrthsefgsddfsdfdfxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(17, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 12:40:00', 'zsdfgsdxghrstdfjfrthsefgsddfsdfdfxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(18, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-21 00:45:00', 'asfeger htwfkhjidfjwadojksaoljsadfikjdskkkkkkkkkkkkkkkb vv', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(19, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-21 00:45:00', 'asfeger htwfkhjidfjwadojksaoljsadfikjdskkkkkkkkkkkkkkkb vv', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(20, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-21 00:45:00', 'asfeger htwfkhjidfjwadojksaoljsadfikjdskkkkkkkkkkkkkkkb vv', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(21, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-21 00:45:00', 'asfeger htwfkhjidfjwadojksaoljsadfikjdskkkkkkkkkkkkkkkb vv', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(22, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 00:53:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/5.jpg', 'uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg'),
(23, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 00:53:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/5.jpg', 'uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg'),
(24, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 00:53:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/5.jpg', 'uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg'),
(25, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 00:53:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/5.jpg', 'uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg'),
(26, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-15 00:59:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(27, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 01:04:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/6.jfif', 'uploads/6.jfif'),
(28, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 01:04:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(29, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 01:04:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(30, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-21 01:05:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdfzdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/5.jpg', 'uploads/6.jfif'),
(31, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-21 01:05:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdfzdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/5.jpg', 'uploads/6.jfif'),
(32, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-21 01:12:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdfzdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(33, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 01:15:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdfzdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(34, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-06 01:22:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdfzdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(35, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-06 01:22:00', 'zdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdfzdsfsadfsedfsegrterogjaodksaspdkasdofjdxlcvmszdlcmdzlfjdssigjsodla;dles0udfodka:xzslcjdxfkjsdlkA;dkzdgodjgosdfsdfdfdsfgdf', 'Lance', 'haryl', NULL, NULL),
(36, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-22 13:23:00', 'zdaSDdddddcfzdaSDdddddcfzdaSDdddddcfzdaSDdddddcfzdaSDdddddcfzdaSDdddddcfzdaSDdddddcfzdaSDdddddcfzdaSDdddddcfzdaSDdddddcfzdaSDdddddcfzdaSDdddddcf', 'Lance', 'haryl', NULL, NULL),
(37, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 01:28:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(38, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 01:32:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(39, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 13:54:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(40, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-26 01:00:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(41, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-07 02:04:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', 'uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg', 'uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg'),
(42, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 02:08:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(43, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 02:08:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(44, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 02:08:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(45, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 02:08:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(46, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 02:08:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(47, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-02-28 02:08:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', 'uploads/1.png', 'uploads/1.png'),
(48, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(49, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(50, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(51, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(52, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(53, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(54, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(55, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(56, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(57, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(58, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(59, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(60, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(61, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(62, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(63, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(64, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(65, 'San Fabian', 'Echsgue', 'Isabela', 'Bj', 'Princess Mae  V  Aquino', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'Lance', 'haryl', NULL, NULL),
(66, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Mae  V  Aquino', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'desday  D Nasyfa', 'Mark  haryl B conception', NULL, NULL),
(67, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Mae  V  Aquino', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'desday  D Nasyfa', 'Mark  haryl B conception', NULL, NULL),
(68, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'desday  D Nasyfa', 'Mark  haryl B conception', NULL, NULL),
(69, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'desday  D Nasyfa', 'Mark  haryl B conception', NULL, NULL),
(70, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'BArangay', '2025-03-01 02:22:00', 'zxcszfs zxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfszxcszfs', 'desday  D Nasyfa', 'Mark  haryl B conception', 'uploads/1.png', 'uploads/1.png'),
(71, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'BArangay', '2025-03-08 02:48:00', 'efjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdokso', 'desday  D Nasyfa', 'Mark  haryl B conception', 'uploads/1.png', 'uploads/1.png'),
(72, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'BArangay', '2025-03-08 02:48:00', 'efjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdokso', 'desday  D Nasyfa', 'Mark  haryl B conception', 'uploads/1.png', 'uploads/1.png'),
(73, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'BArangay', '2025-03-08 02:48:00', 'efjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdoksoefjwedokawpdokso', 'desday  D Nasyfa', 'Mark  haryl B conception', 'uploads/1.png', 'uploads/1.png'),
(74, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'BArangay', '2025-03-08 07:55:00', '&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n', 'desday  D Nasyfa', 'Mark  haryl B conception', 'uploads/1.png', 'uploads/1.png'),
(75, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'BArangay', '2025-03-08 07:55:00', '&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n&lt;script src=&quot;https://cdn.jsdelivr.net/npm/sweetalert2@11&quot;&gt;&lt;/script&gt;\r\n', 'desday  D Nasyfa', 'Mark  haryl B conception', 'uploads/1.png', 'uploads/1.png'),
(76, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'BArangay', '2025-03-08 03:04:00', 'fsdgsdfs', 'desday  D Nasyfa', 'Mark  haryl B conception', 'uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg', 'uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg'),
(77, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'denver gorospe', 'trisha nicole', '2025-02-28 23:14:00', 'iwrrrbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb', 'desday  D Nasyfa', 'Mark  haryl B conception', 'uploads/1.png', 'uploads/1.png'),
(78, 'Barangay Aromin', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'trisha nicole', '2025-03-08 11:29:00', ';zcdszfjsoe[wqpe\\qe[3]pw494-64ro3prepriepti', 'desday  D Nasyfa', 'Mark  haryl B conception', 'uploads/1.png', 'uploads/1.png'),
(79, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'trisha nicole', '2025-03-08 12:17:00', 'juggggguvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv000000000000000000000000000000000000000000000000000', 'desday  D Nasyfa', 'Mark  haryl B conception', 'uploads/1.png', 'uploads/1.png'),
(80, 'San Fabian', 'Echsgue', 'Isabela', 'Brayan John V aquino', 'Princess Buraot  C Rosario', 'trisha nicole', '2025-03-08 00:00:00', 'juggggguvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv000000000000000000000000000000000000000000000000000', 'desday  D Nasyfa', 'Mark  haryl B conception', 'uploads/1.png', 'uploads/1.png'),
(81, 'Gamis', 'Saguday', 'quirino', 'Reyven pili', 'Brauan john aquino', 'ivy pili', '2025-02-28 00:00:00', 'siojsepgkdprhl[pfthjl[ftjlf[hlfgggggggggggggggg;gfdgbdfbthrtjrtjtr6jt6', 'jong ojadas', 'Fernando poe  jr', 'uploads/1.png', 'uploads/1.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_announcement`
--

CREATE TABLE `tbl_announcement` (
  `announcement_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `date_posted` date NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `share_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_announcement`
--

INSERT INTO `tbl_announcement` (`announcement_id`, `title`, `content`, `date_posted`, `image_path`, `deleted`, `share_count`) VALUES
(1, 'ddsdsmd', 'excel', '2024-07-19', 'uploads/profile_6697a2b8e17de.jpg', 1, 0),
(2, 'ddsdsmd', 'xnzm xzmxz', '2024-07-19', '', 1, 0),
(4, 'pnp logo', 'newxzxzxx', '2024-07-19', NULL, 1, 0),
(7, 'princess', 'mahilig sa kape at idol niya akoccxx', '2024-07-20', NULL, 1, 0),
(8, 'excel', 'crush ko', '2024-07-21', 'uploads/profile_669af75c84736.jpg', 1, 0),
(9, 'pnp logo', 'try lang', '2024-07-21', 'uploads/pnplogo.png', 1, 0),
(10, 'Fiesta Caravan in one piece Night', 'The Fista caravan will be on August 14, 2024', '2024-07-23', 'uploads/wampis.jpg', 1, 0),
(11, 'kior centrum', 'edit time', '2024-07-24', 'uploads/kior centrum.jpg', 1, 5),
(12, 'esodsdsds', 'eyyyyyyyy', '2024-07-27', 'uploads/kior centrum.jpg', 1, 0),
(13, 'weeeeeeeeeeeeeeeeeeeee', 'jbjbbhbn ', '2024-07-28', '', 1, 0),
(14, 'dfdfd', 'redfdfgftgfgdfgfd', '2024-08-01', 'uploads/441799097_1205342757295168_7001114824101829619_n.jpg', 1, 0),
(15, 'Interview', 'Interview for valifation and adding of features', '2024-09-03', 'uploads/458491959_1003199601607500_4565557339938371083_n.jpg', 1, 0),
(16, 'interview', 'nag interview\r\n', '2024-09-05', '../uploadspnp interview.jpg', 1, 0),
(17, 'princess', 'laging tulog walang ambag', '2024-09-08', '../uploadsuploads456864062_827865415993505_5703441178082039818_n.jpg', 1, 0),
(18, 'interview', 'pnp', '2024-09-12', '../uploadsuploadspnp interview.jpg', 1, 0),
(19, 'interview', 'pnp', '2024-09-12', '../uploadsuploads458491959_1003199601607500_4565557339938371083_n.jpg', 1, 0),
(26, 'xcxc', 'xcxcxc', '2024-09-12', '', 1, 0),
(27, ' z z  ', ' x z z', '2024-09-12', '', 1, 0),
(28, 'ssccsc', 'zz', '2024-09-12', '', 1, 0),
(29, ' z z z ', 'zz zz', '2024-09-12', '', 1, 0),
(30, 'x xx ', 'xcxcxcc', '2024-09-12', '', 1, 0),
(31, 'scscc', 'scsscs', '2024-09-12', '', 1, 0),
(32, 'Interview', 'PNP', '2024-09-12', '../uploads/uploads458491959_1003199601607500_4565557339938371083_n.jpg', 0, 0),
(33, 'Brain Storming', 'Centrum', '2024-09-12', '../uploads/kior centrum.jpg', 0, 0),
(34, 'edewdw', 'deqdq', '2024-09-12', '../uploads/8.jpg', 1, 0),
(35, 'Wanpis', 'We, the pirate group', '2024-09-12', '../uploads/3.jpg', 0, 0),
(36, 'To be continue', 'Ang pagbabalik', '2024-09-12', '../uploads/Screenshot (47).png', 0, 0),
(37, 'sfsfsfs', 'adssd', '2024-09-14', '../uploads/uploads456864062_827865415993505_5703441178082039818_n.jpg', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brg_official`
--

CREATE TABLE `tbl_brg_official` (
  `official_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `position` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `barangays_id` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_brg_official`
--

INSERT INTO `tbl_brg_official` (`official_id`, `name`, `position`, `image`, `barangays_id`, `is_deleted`) VALUES
(73, 'Jay Ar Gumabon', 'Barangay Captain', '../uploads/Selfie ni jj.jpg', 526, 0),
(74, 'James Lemuel Taming', 'Kagawad 1', '../uploads/lems.jpg', 526, 0),
(75, 'Rodeemer Velazquez', 'Kagawad 2', '../uploads/rod.jpg', 526, 0),
(76, 'Yra Lei Garcia', 'Kagawad 3', '../uploads/yra.jpg', 526, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_complaintcategories`
--

CREATE TABLE `tbl_complaintcategories` (
  `category_id` int(10) NOT NULL,
  `complaints_category` varchar(255) NOT NULL,
  `cert_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_complaintcategories`
--

INSERT INTO `tbl_complaintcategories` (`category_id`, `complaints_category`, `cert_path`) VALUES
(442, 'Using False Certificates (Art. 175)', ''),
(443, 'Physical Injuries Inflicted in a Tumultuous Affray (Art. 252)', ''),
(444, 'Theft (if the value of the property stolen does not exceed Php50.00) (Art. 309)', ''),
(445, 'Grave coercion (Art. 286)', ''),
(446, 'Qualified trespass to dwelling (without the use of violence and intimidation) (Art. 280)', ''),
(447, 'Alarms and Scandals (Art. 155)', ''),
(448, 'Light threats (Art. 283)', ''),
(449, 'Using Fictitious Names and Concealing True Names (Art. 178)', ''),
(450, 'Revealing secrets with abuse of authority (Art. 291)', ''),
(451, 'Simple seduction (Art. 338)', ''),
(452, 'Issuing checks without sufficient funds (B.P. 22)', ''),
(453, 'Occupation of real property or usurpation of real rights in property (Art. 312)', ''),
(454, 'Other', ''),
(455, 'Unlawful Use of Means of Publication and Unlawful Utterances (Art. 154)', ''),
(456, 'Fencing of stolen properties if the property involved is not more than Php50.00 (P.D. 1612)', ''),
(457, 'Slight physical injuries and maltreatment (Art. 266)', ''),
(458, 'Illegal Use of Uniforms and Insignias (Art. 179)', ''),
(459, 'sdsdsds', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_complaints`
--

CREATE TABLE `tbl_complaints` (
  `complaints_id` int(15) NOT NULL,
  `complaint_name` varchar(255) NOT NULL,
  `complaints_person` varchar(15) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '''Inprogress''',
  `complaints` text NOT NULL,
  `responds` enum('barangay','pnp') NOT NULL,
  `date_filed` date NOT NULL,
  `category_id` int(1) NOT NULL,
  `barangays_id` int(10) NOT NULL,
  `image_id` int(10) NOT NULL,
  `info_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ano` varchar(255) DEFAULT NULL,
  `barangay_saan` varchar(255) DEFAULT NULL,
  `kailan_date` date DEFAULT NULL,
  `kailan_time` varchar(255) NOT NULL,
  `paano` text DEFAULT NULL,
  `bakit` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `cert_id` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_complaints`
--

INSERT INTO `tbl_complaints` (`complaints_id`, `complaint_name`, `complaints_person`, `status`, `complaints`, `responds`, `date_filed`, `category_id`, `barangays_id`, `image_id`, `info_id`, `user_id`, `ano`, `barangay_saan`, `kailan_date`, `kailan_time`, `paano`, `bakit`, `file_path`, `cert_id`) VALUES
(588, 'Cessy Moon Montefalco ', 'Jay Ar Gumabon', 'Approved', 'I was passing by the cafe store when he punched me in the face', '', '2025-02-18', 443, 543, 0, 0, 423, 'He punch me', 'Aromin', '2025-02-19', '03:40:00 PM', 'I was walking and he punch me without any reason', 'I actually dont know why he did that', NULL, NULL),
(592, 'James Lemuel Taming ', 'Rodeemer Velazq', 'inprogress', 'I was doing something and I put my wallet on the table while he was watching TV and after a few hours my money got stolen', '', '2025-02-18', 444, 547, 0, 0, 427, 'He stole my money', 'Aromin', '2025-02-18', '05:30:00 PM', 'He was the only person inside my house', 'He was asking for money to buy motorcycle parts', NULL, NULL),
(593, 'lily villanueva Aquino ', 'Princess', 'settled_in_barangay', 'pinilit pumirma  ng  papeles', 'barangay', '2025-02-18', 445, 546, 0, 0, 426, 'pinilit  pumira  ng papeles  sa  office', 'Angoluan', '2025-02-28', '02:24:00 PM', 'pinuntahan  siya  sa office', 'binantaan siya  ', NULL, NULL),
(594, 'Rodeemer Mooneh Velazquez ', 'Jay jay Gumable', 'settled_in_barangay', 'He stole my money while we are practicing for our PE class', 'barangay', '2025-02-18', 444, 550, 0, 0, 428, 'He stole money', 'Aromin', '2025-02-16', '02:30:00 PM', 'We are practicing when he stole my money', 'He needs it bcos they are poor', NULL, NULL),
(595, 'Yra Lei Garcia ', 'Franz Butac', 'inprogress', 'I&#039;m writing to complain about repeated trespassing on my property. ', '', '2025-02-18', 446, 553, 0, 0, 432, 'He is trespassing my proporty', 'Aromin', '2025-02-05', '04:00:00 PM', 'idk', 'he wants a land property ', NULL, NULL),
(596, 'Mark Harryl Concepcion ', 'Robert Gamet', 'Rejected', 'I was busy having a conversation with a friend at the bar when he suddenly interrupted us and  spitting bad words and he&#039;s making a scene  ', '', '2025-02-18', 447, 554, 0, 0, 433, 'he make scene at the bar', 'Aromin', '2025-02-16', '03:15:00 PM', 'He&#039;s staring at us and he approach us', 'he wants to get attention from the stranger', NULL, NULL),
(597, 'Kathlene Anne Salvador ', 'John Michael Ne', 'pnp', 'She was my friend before and now she is threatening me, every 3 in the afternoon she gives me a note that she will hurt me', 'pnp', '2025-02-18', 448, 558, 0, 0, 437, 'She&#039;s threatening me', 'Aromin', '2025-02-15', '03:00:00 PM', 'I saw her dropping the note in front of our house', 'she&#039;s jealous because I have new friends', NULL, NULL),
(598, 'Florie Mae Nicolas ', 'Kimberly Montem', 'pnp', 'She was stealing my name for the past 5years ', 'pnp', '2025-02-18', 449, 560, 0, 0, 439, 'Shes stealing my name', 'Aromin', '2024-02-15', '05:50:00 PM', 'I saw her using my name ', 'She wants to get famous', NULL, NULL),
(599, 'Angelica Agunos Rosario ', 'Amica Rosario', 'inprogress', 'She&#039;s revealing my secrets and fabricating additional false information', '', '2025-02-18', 450, 561, 0, 0, 440, 'Shes exposed my secrets', 'Maligaya', '2025-02-18', '06:55:00 PM', 'I heard her revealing my secrets ', 'She wants to destroy me', NULL, NULL),
(600, 'lily villanueva Aquino ', 'Aldrin Damance', 'inprogress', 'nakita ko siya  gumamit ng pekeng  pangallan', '', '2025-02-18', 449, 546, 0, 0, 426, 'gumagamit  siya  ng ivang pangalan', 'Villa Ysmael (formerly T. Belen)', '2025-02-21', '04:00:00 PM', 'kumuha  siya  ng pangalan sa facebook', 'ginamit niya  para kumuha  ng ayuda', NULL, NULL),
(601, 'Yra Lei Garcia ', 'Jay Ar Gumabon', 'inprogress', 'He touches my body parts and I&#039;m not comfortable with it ', '', '2025-02-18', 451, 553, 0, 0, 432, 'He seduce me', 'Fugu', '2025-02-17', '04:01:00 PM', 'I was in the backyard of my house when he did it', 'He wants something from me that I cannot give it', NULL, NULL),
(602, 'lily villanueva Aquino ', 'Des Nayga', 'Approved', 'nag nakaw  sa mall  kumuha ng mga alahas', '', '2025-02-18', 444, 546, 0, 0, 426, 'ninakawan  niya ng alahas', 'Aromin', '2025-02-15', '06:05:00 PM', 'nag panggap siyang bibili ng alahas ', 'sinimplehan niyang kinuha ang mga   alahas na naka display', NULL, NULL),
(603, 'Yra Lei Garcia ', 'Maki Delacruz', 'Approved', 'She was buying my iPhone and she gave me a check money, I was about to withdraw the funds but she didn&#039;t have enough funds in her bank', '', '2025-02-18', 452, 553, 0, 0, 432, 'She did not paid my phone', 'Aromin', '2025-02-10', '04:10:00 PM', 'We were at the restaurant when she bought my iPhone', 'She wanted my phone but didn&#039;t have enough money', NULL, NULL),
(604, 'lily villanueva Aquino ', 'princesss', 'Approved', 'nang aakit  si princess ng mga lalake para maka pang huthut ng pera', '', '2025-02-18', 451, 546, 0, 0, 426, 'humahanap siya ng mga mayayaman para akitin', 'Aromin', '2025-02-22', '05:14:00 AM', 'ginagamit niya  ang  kanyang  facebook account para mang akit', 'para  kumita siya ng malaking halaga', NULL, NULL),
(605, 'Yra Lei Garcia ', 'Daniela Isabela', 'inprogress', 'She kicked and stomped my face bcos I didn&#039;t give her my makeup', '', '2025-02-18', 443, 553, 0, 0, 432, 'She kicked me', 'Malibago', '2025-01-20', '08:15:00 PM', 'She wants my makeup and i didn&#039;t want her to use it', 'She wants my makeup', NULL, NULL),
(606, 'Yra Lei Garcia ', 'Jay Ar Gumabon', 'pnp', 'He didn&#039;t want to give my land paper bcos he wanted to sell it with bigger money and use it for a casino', 'pnp', '2025-02-18', 453, 553, 0, 0, 432, 'He wants to sold my property', 'Aromin', '2025-01-30', '04:25:00 AM', 'my dads gave my paper to my brother and my brother didn&#039;t want to give my land paper', 'he wants money', NULL, NULL),
(608, 'brayan john villanueva Aquino ', 'dfd', 'Approved', 'dfdf', '', '2025-02-22', 455, 529, 0, 0, 413, 'dfd', 'Aromin', '2025-02-27', '01:06:00 PM', 'dfdfd', 'dfd', NULL, NULL),
(610, 'Princess Cadiente Rosario ', 'sdsds', 'Approved', 'sdsd', '', '2025-02-23', 447, 526, 0, 0, 410, 'sdsdsd', 'Aromin', '2025-02-20', '11:58:00 PM', 'sdsd', 'sdsds', NULL, NULL),
(611, 'Reyven Ojadas Pili ', 'sdsds', 'Approved', 'dwd', '', '2025-02-23', 455, 568, 0, 0, 451, 'sdsds', 'Aromin', '2025-02-27', '12:03:00 AM', 'sdsdsdsdsdsds', 'sds', NULL, NULL),
(612, 'Neal fgfgf Concepcion ', 'fgf', 'Approved', 'fgfg', '', '2025-02-23', 442, 569, 0, 0, 453, 'fgfgf', 'Aromin', '2025-01-29', '12:03:00 AM', 'fgf', 'fgfg', NULL, NULL),
(614, 'John Lloyd Colobong Manuel ', 'sdsd', 'Approved', 'dsd', '', '2025-02-24', 457, 570, 0, 0, 454, 'dsds', 'Aromin', '2025-02-28', '11:08:00 AM', 'sdsd', 'sdsds', NULL, NULL),
(615, 'denver Deo Gorospe ', 'ghghg', 'Approved', 'ghgh', '', '2025-02-24', 458, 571, 0, 0, 455, 'ghg', 'Aromin', '2025-03-05', '11:09:00 AM', 'gh', 'cgddf', NULL, NULL),
(616, 'Eugene G Tobias ', 'dfdf', 'Approved', 'dfdv', '', '2025-02-24', 443, 557, 0, 0, 456, 'dcdfdc', 'Aromin', '2025-02-20', '05:07:00 PM', 'fdfdfd', 'fddfvd', NULL, NULL),
(617, 'Prinsesa Cadiente Rosario ', 'Jay Ar Gumabon', 'Approved', 'Family Family is one of the greatest gift and blessing from the Lord. Without family you can not feel love, care , satisfaction and happiness. They are the most treasured blessing that no one can take away from us. It is also like a fragile thing you consider as the most precious gem in your whole life. Family is a basic unit in the society traditionally consisting of parents and children. It does not need to be blood related in order to be a family. Family is home, they are the ones who gives you unconditional love. They are the people who comforts you towards difficulties in life. The ones who I can count on in times of love problem. ', '', '2025-02-24', 442, 527, 0, 0, 457, 'BASTA GANYAN NA', 'Aromin', '2025-02-22', '10:50:00 AM', 'WHAUHAHAHAH BASTA UN NA', 'HAYS BASTA UN NA', NULL, NULL),
(618, 'Prinsesa Cadiente Rosario ', 'Jay Ar Gumabon', 'Approved', 'example lang to wag lang tanga', '', '2025-02-24', 454, 527, 0, 0, 457, 'nirape ako sa ilog pero nagustuha ko malaki e', 'Aromin', '2025-02-21', '06:30:00 PM', 'sumama kase ako e wala ako pamasahe may motor sia', 'gusto nia ako matikman', NULL, NULL),
(619, 'brayan john villanueva Aquino ', 'dfdfd', 'inprogress', 'dfdf', '', '2025-02-24', 455, 529, 0, 0, 413, 'ddfd', 'Castillo', '2025-02-26', '11:28:00 PM', 'dfd', 'dfdf', NULL, NULL),
(620, 'brayan john villanueva Aquino ', 'sdsd', 'pnp', 'dsds', 'pnp', '2025-02-24', 459, 529, 0, 0, 413, 'sdsds', 'Angoluan', '2025-02-27', '02:25:00 PM', 'sdsds', 'sdsd', NULL, NULL),
(621, 'Prinsesa Cadiente Rosario ', 'Tom Yasay', 'inprogress', 'I was passing by the sports complex in the afternoon when I saw Tom and I told him that her motorcycle was slow like a turtle and suddenly he kicked, stomped and punched me  ', '', '2025-02-24', 443, 527, 0, 0, 457, 'He punch, stomped and kick me like a dog', 'Aromin', '2025-02-23', '04:30:00 PM', 'Namakyu na nga sia pa may gana magalit kingina ', 'He did it because of jealousy and he wants money from me', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_complaints_certificates`
--

CREATE TABLE `tbl_complaints_certificates` (
  `cert_id` int(11) NOT NULL,
  `complaints_id` int(11) NOT NULL,
  `cert_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_complaints_certificates`
--

INSERT INTO `tbl_complaints_certificates` (`cert_id`, `complaints_id`, `cert_path`, `uploaded_at`) VALUES
(13, 606, '../uploads/certificates/1739910289_cert file.jpg', '2025-02-18 20:24:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_evidence`
--

CREATE TABLE `tbl_evidence` (
  `evidence_id` int(20) NOT NULL,
  `complaints_id` int(20) NOT NULL,
  `cert_path` varchar(255) DEFAULT NULL,
  `evidence_path` varchar(255) NOT NULL,
  `date_uploaded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_evidence`
--

INSERT INTO `tbl_evidence` (`evidence_id`, `complaints_id`, `cert_path`, `evidence_path`, `date_uploaded`) VALUES
(0, 588, NULL, '../uploads/punch.jpg', '2025-02-18'),
(0, 592, NULL, '../uploads/theft money.jpg', '2025-02-18'),
(0, 593, NULL, '../uploads/images (4).jfif', '2025-02-18'),
(0, 594, NULL, '../uploads/bulsa kwarta.jpg', '2025-02-18'),
(0, 595, NULL, '../uploads/trespassing.jpg', '2025-02-18'),
(0, 596, NULL, '../uploads/corruption.png', '2025-02-18'),
(0, 597, NULL, '../uploads/threat.jpg', '2025-02-18'),
(0, 598, NULL, '../uploads/threat.jpg', '2025-02-18'),
(0, 599, NULL, '../uploads/revilsecrets.jpg', '2025-02-18'),
(0, 600, NULL, '../uploads/20190617_225140_0000.png', '2025-02-18'),
(0, 601, NULL, '../uploads/threat.jpg', '2025-02-18'),
(0, 602, NULL, '../uploads/images (5).jfif', '2025-02-18'),
(0, 603, NULL, '../uploads/checke.jpg', '2025-02-18'),
(0, 604, NULL, '../uploads/457368489_528714303027535_3753519382645185170_n.jpg', '2025-02-18'),
(0, 605, NULL, '../uploads/kick.jpg', '2025-02-18'),
(0, 606, NULL, '../uploads/trespassing.jpg', '2025-02-18'),
(0, 608, NULL, '../uploads/uploads456864062_827865415993505_5703441178082039818_n.jpg', '2025-02-22'),
(0, 610, NULL, '../uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg', '2025-02-23'),
(0, 611, NULL, '../uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg', '2025-02-23'),
(0, 612, NULL, '../uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg', '2025-02-23'),
(0, 614, NULL, '../uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg', '2025-02-24'),
(0, 615, NULL, '../uploads/7.jfif', '2025-02-24'),
(0, 616, NULL, '../uploads/6.jfif', '2025-02-24'),
(0, 617, NULL, '../uploads/checke.jpg', '2025-02-24'),
(0, 618, NULL, '../uploads/received_1686464038944287.jpeg', '2025-02-24'),
(0, 619, NULL, '../uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg', '2025-02-24'),
(0, 620, NULL, '../uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg', '2025-02-24'),
(0, 621, NULL, '../uploads/mutur.jpg', '2025-02-24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hearing_history`
--

CREATE TABLE `tbl_hearing_history` (
  `id` int(11) NOT NULL,
  `complaints_id` int(11) NOT NULL,
  `hearing_date` date NOT NULL,
  `hearing_time` varchar(255) NOT NULL,
  `hearing_type` varchar(50) NOT NULL,
  `hearing_status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_hearing_history`
--

INSERT INTO `tbl_hearing_history` (`id`, `complaints_id`, `hearing_date`, `hearing_time`, `hearing_type`, `hearing_status`, `created_at`) VALUES
(61, 608, '2025-02-26', '', 'First Hearing', '', '2025-02-23 15:22:22'),
(64, 615, '2025-02-26', '03:58:00 AM', 'First Hearing', '', '2025-02-24 03:58:29'),
(65, 614, '2025-02-25', '12:03:00 PM', 'First Hearing', '', '2025-02-24 04:00:41'),
(66, 616, '2025-02-25', '01:00:00 AM', 'First Hearing', 'Attended', '2025-02-24 04:08:26'),
(67, 612, '2025-02-25', '02:20:00 PM', 'First Hearing', '', '2025-02-24 04:19:28'),
(68, 617, '2025-02-26', '12:51:00 PM', 'First Hearing', '', '2025-02-24 13:48:36'),
(69, 617, '2025-03-01', '11:50:00 PM', 'Second Hearing', '', '2025-02-24 13:49:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login_logs`
--

CREATE TABLE `tbl_login_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `login_time` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_login_logs`
--

INSERT INTO `tbl_login_logs` (`log_id`, `user_id`, `login_time`) VALUES
(271, 409, '2025-02-18 23:38:54.000000'),
(272, 410, '2025-02-18 23:42:22.000000'),
(273, 420, '2025-02-19 01:14:54.000000'),
(274, 421, '2025-02-19 01:28:28.000000'),
(275, 410, '2025-02-19 01:28:55.000000'),
(276, 423, '2025-02-19 01:31:59.000000'),
(277, 426, '2025-02-19 02:12:51.000000'),
(278, 427, '2025-02-19 02:14:46.000000'),
(279, 428, '2025-02-19 02:34:05.000000'),
(280, 410, '2025-02-19 02:43:01.000000'),
(281, 432, '2025-02-19 02:54:32.000000'),
(282, 410, '2025-02-19 03:03:09.000000'),
(283, 433, '2025-02-19 03:10:10.000000'),
(284, 410, '2025-02-19 03:17:37.000000'),
(285, 437, '2025-02-19 03:28:00.000000'),
(286, 409, '2025-02-19 03:31:38.000000'),
(287, 426, '2025-02-19 03:34:26.000000'),
(288, 410, '2025-02-19 03:35:18.000000'),
(289, 409, '2025-02-19 03:40:32.000000'),
(290, 439, '2025-02-19 03:43:27.000000'),
(291, 410, '2025-02-19 03:46:38.000000'),
(292, 440, '2025-02-19 03:51:37.000000'),
(293, 410, '2025-02-19 03:58:41.000000'),
(294, 432, '2025-02-19 03:59:50.000000'),
(295, 410, '2025-02-19 04:23:42.000000'),
(296, 409, '2025-02-19 08:55:00.000000'),
(297, 409, '2025-02-19 09:21:02.000000'),
(298, 409, '2025-02-19 10:37:07.000000'),
(299, 410, '2025-02-19 11:30:33.000000'),
(300, 409, '2025-02-19 11:33:47.000000'),
(301, 437, '2025-02-19 11:36:52.000000'),
(302, 410, '2025-02-19 14:47:25.000000'),
(303, 409, '2025-02-19 14:49:52.000000'),
(304, 410, '2025-02-19 23:17:32.000000'),
(305, 410, '2025-02-20 09:44:25.000000'),
(306, 410, '2025-02-20 11:07:40.000000'),
(307, 410, '2025-02-21 10:16:41.000000'),
(308, 409, '2025-02-21 10:29:52.000000'),
(309, 415, '2025-02-21 10:32:12.000000'),
(310, 410, '2025-02-22 12:33:49.000000'),
(311, 413, '2025-02-22 13:00:54.000000'),
(316, 451, '2025-02-23 18:08:57.000000'),
(318, 451, '2025-02-23 18:28:14.000000'),
(319, 451, '2025-02-23 18:29:01.000000'),
(321, 410, '2025-02-23 18:36:07.000000'),
(322, 451, '2025-02-24 00:00:32.000000'),
(323, 410, '2025-02-24 00:02:29.000000'),
(324, 453, '2025-02-24 00:58:21.000000'),
(327, 410, '2025-02-24 10:47:04.000000'),
(328, 454, '2025-02-24 11:02:48.000000'),
(329, 455, '2025-02-24 11:09:30.000000'),
(330, 456, '2025-02-24 12:06:56.000000'),
(331, 456, '2025-02-24 12:16:16.000000'),
(332, 456, '2025-02-24 13:17:41.000000'),
(333, 410, '2025-02-24 21:12:39.000000'),
(334, 410, '2025-02-24 21:17:49.000000'),
(335, 457, '2025-02-24 21:21:48.000000'),
(336, 456, '2025-02-24 21:28:27.000000'),
(337, 409, '2025-02-24 22:37:17.000000'),
(338, 457, '2025-02-24 23:15:20.000000'),
(339, 457, '2025-02-24 23:15:52.000000'),
(340, 457, '2025-02-24 23:16:12.000000'),
(341, 457, '2025-02-24 23:21:54.000000'),
(342, 413, '2025-02-24 23:23:41.000000'),
(343, 410, '2025-02-24 23:26:31.000000'),
(344, 410, '2025-02-24 23:40:08.000000'),
(345, 457, '2025-02-24 23:50:54.000000'),
(346, 410, '2025-02-24 23:54:15.000000'),
(347, 457, '2025-02-24 23:57:36.000000'),
(348, 410, '2025-02-24 23:57:46.000000'),
(349, 457, '2025-02-25 00:27:13.000000'),
(350, 410, '2025-02-25 00:29:17.000000'),
(351, 410, '2025-02-25 00:29:31.000000'),
(352, 410, '2025-02-25 00:41:04.000000'),
(353, 409, '2025-02-25 00:46:15.000000'),
(354, 409, '2025-02-25 00:48:47.000000'),
(355, 409, '2025-02-25 00:49:25.000000'),
(356, 457, '2025-02-25 00:50:29.000000'),
(357, 410, '2025-02-25 00:50:50.000000'),
(358, 410, '2025-02-25 00:51:38.000000'),
(359, 410, '2025-02-25 00:55:09.000000'),
(360, 410, '2025-02-25 00:56:30.000000'),
(361, 456, '2025-02-25 00:59:34.000000'),
(362, 456, '2025-02-25 01:00:21.000000'),
(363, 456, '2025-02-25 01:01:21.000000');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(15) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `extension_name` varchar(255) DEFAULT NULL,
  `gender` varchar(255) NOT NULL,
  `age` int(255) NOT NULL,
  `birth_date` date NOT NULL,
  `selfie_path` varchar(255) NOT NULL,
  `image_type` enum('ID') NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `date_uploaded` datetime(6) NOT NULL,
  `cp_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `accountType` enum('Resident','Barangay Official','PNP Officer') NOT NULL,
  `barangays_id` int(11) DEFAULT NULL,
  `place_of_birth` varchar(255) NOT NULL,
  `purok` varchar(255) NOT NULL,
  `civil_status` enum('Single','Married','Separated','Live-in','Divorced','Widowed') NOT NULL,
  `educational_background` enum('No Formal Education','Elementary','Highschool','College','Post Graduate') NOT NULL,
  `nationality` varchar(255) NOT NULL,
  `pic_data` varchar(255) NOT NULL,
  `security_question` varchar(255) NOT NULL,
  `security_answer` varchar(255) NOT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `lockout_time` datetime DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `gender`, `age`, `birth_date`, `selfie_path`, `image_type`, `image_path`, `date_uploaded`, `cp_number`, `password`, `accountType`, `barangays_id`, `place_of_birth`, `purok`, `civil_status`, `educational_background`, `nationality`, `pic_data`, `security_question`, `security_answer`, `login_attempts`, `lockout_time`, `is_verified`) VALUES
(409, 'Marizen', '', 'Benabese', '', '', 0, '0000-00-00', '', 'ID', '', '0000-00-00 00:00:00.000000', '09141414141', '$2y$10$O8XKPT6jQlLAX3UrUqeAd.TIFLPi7oKjZPuwVtmdBMn9jF4vf92Pm', 'PNP Officer', 525, '', '', 'Single', 'No Formal Education', '', '../uploads/cartoon-police-woman-in-uniform-vector-45168846.jpg', 'What was your childhood nickname?', '$2y$10$gYNc8cGDrnRwgkm28Ln3NOQWj/g4Wjo.xL21bG57wEH2n6bGCVb8S', 0, NULL, 0),
(410, 'Cess', 'Cadiente', 'Rosario', '', '', 0, '0000-00-00', '', 'ID', '', '0000-00-00 00:00:00.000000', '09602752579', '$2y$10$CWKX7qKlyHUf1LhuQHtcdOP3cDTpMO70o28XZT2YKFc26pO6WLUNO', 'Barangay Official', 526, '', '', 'Single', 'No Formal Education', '', '../uploads/ses.jpg', 'What is the name of your first pet?', '$2y$10$HtZvhHUkdEgAZ/cbwKe.Seyt71sN1rB1kKk30Zy.94WkgGwqW531.', 0, NULL, 0),
(411, '', '', '', NULL, 'Male', 2024, '0001-02-12', '', 'ID', '', '0000-00-00 00:00:00.000000', '09000000000', '', 'Resident', NULL, 'Bohol', 'Purok 3', 'Single', 'Post Graduate', '', '', '', '', 0, NULL, 0),
(412, 'Brayan John', 'Villanueva', 'Aquino', '', 'Male', 23, '2001-07-26', '../uploads/selfie_67b4aeed862a3_467481047_1363514587948463_7463127039056127664_n.jpg', 'ID', '', '0000-00-00 00:00:00.000000', '09098503472', '$2y$10$Tgis6TPKtCkJjyzt/ltGk.qTDwvyd/DvA5kMc6ARB6oehEVUhk7By', 'Resident', 528, 'San marcos', 'Purok 2', 'Single', 'College', 'filipino', '../uploads/profile_67b4aeed85cca.jpg', 'What was your childhood nickname?', '$2y$10$0OwidES1gTBbNRUupRDN9ucGxlb7MllaS9fWd0yE5YLlqZPl7kipW', 1, '2025-02-24 13:17:08', 0),
(413, 'brayan john', 'villanueva', 'Aquino', '', 'Male', 23, '2001-07-26', '../uploads/selfie_67b4b052c1950_467481047_1363514587948463_7463127039056127664_n.jpg', 'ID', '', '0000-00-00 00:00:00.000000', '09098003472', '$2y$10$3eV/mUGIkRszFgS433eCFe5uggJFNaPi74eZIBo1PBGNGoXEBL.RW', 'Resident', 529, 'Aromin', 'Purok 1', 'Single', 'No Formal Education', 'Filipino', '../uploads/profile_67b4b052c149e.jpg', 'What was your childhood nickname?', '$2y$10$I8rDQO7slKVm/EMclFWnf.Ji5mOjkEbqArkyoEumUD5BZ5l/ZKEwu', 0, NULL, 0),
(415, 'Jay', 'Sumibcay', 'Gumabon', 'Jr.', 'Male', 22, '2002-11-03', '../uploads/selfie_67b4b6708576a_Selfie ni jj.jpg', 'ID', '', '0000-00-00 00:00:00.000000', '09675446934', '$2y$10$r6fWDi1qQBCVjagcqOW.3enNLIoniyDoJTgxSI/t62j8oLaD/AErK', 'Resident', 531, 'Maligaya, Echague, Isabela', 'Purok 3', 'Single', 'College', 'Pilipino', '../uploads/profile_67b4b670854fc.jpg', 'What was your childhood nickname?', '$2y$10$.NOEZUii0.P5s0J0uEWEeu4936w3SAln7pZtaLzMcNjr.71nQH5BO', 0, NULL, 0),
(416, 'sdsds', 'sdsds', 'sdsd', '', 'Male', 24, '2001-02-11', '../uploads/default_selfie.png', 'ID', '', '0000-00-00 00:00:00.000000', '09999999999', '$2y$10$FB7XBE/qAl1wE6Dy0nsZFOd5c6EvuHVv.H3g6Uwi3F1jtWHsXS2FW', 'Resident', 535, 'Bohol', 'Purok 1', 'Single', 'No Formal Education', 'sdsd', '../uploads/default_profile_picture.png', 'What was your childhood nickname?', '$2y$10$VKLMbPrPHc1CUdSPydZjFOLGoUlA3re69H9mOXB9.zKoJa4gkJzVS', 0, NULL, 0),
(417, 'Cess', 'Cadiente', 'Rosario', '', 'Female', 23, '2001-08-28', '../uploads/default_selfie.png', 'ID', '', '0000-00-00 00:00:00.000000', '09605487501', '$2y$10$gB86ANTX76bZkzRGQeAYSOA3suNSE6l6HjCjmLBepWnP6Apl19Wk.', 'Resident', 536, 'Alfonso Lista', 'Purok 3', 'Single', 'Highschool', 'Pilipino', '../uploads/default_profile_picture.png', 'What was your childhood nickname?', '$2y$10$VOf4A79BIOJ3uC1SqdA33ODCnuU4Nw51PKcuVHbmCzQuD4oQ/kZzG', 0, NULL, 0),
(418, 'Jaymayouts', 'Sumibcay', 'Gumabon', 'Jr.', 'Male', 22, '2002-11-03', '../uploads/selfie_67b4bf41912c7_Selfie ni jj.jpg', 'ID', '', '0000-00-00 00:00:00.000000', '09123455955', '$2y$10$BS1ruA6fDAUKcYnIUkG1EeIZ9csWLP7TJAL73EHGBGG0GTbDA02J6', 'Resident', 537, 'Maligaya, Echague, Isabela', 'Purok 3', 'Single', 'College', 'Pilipino', '../uploads/profile_67b4bf41910d5.jpg', 'What was your childhood nickname?', '$2y$10$wBi2yyQfBVSq4P.ZbDfPAOjFoxru9WZm5nPVM.QqXcrpGu0qScAba', 0, NULL, 0),
(419, 'fgvfg', 'fgfg', 'fgfg', '', 'Male', 24, '2001-02-12', '../uploads/default_selfie.png', 'ID', '', '0000-00-00 00:00:00.000000', '09272344235', '$2y$10$8byaLYUuCElV2MmJLgTxvOrrf6.jFnxLTBumlKCK/QHLBISR6LLMO', 'Resident', 538, 'asasa', 'Purok 1', 'Single', 'No Formal Education', 'dfdfd', '../uploads/default_profile_picture.png', 'What was your childhood nickname?', '$2y$10$CfPNsTtpBGq5tWMmVp4mM.Lix7CcB74/w6oBwClBlltLxSw8Wfn7G', 0, NULL, 0),
(420, 'Jaymayouts', 'Sumibcay', 'Gumabon', 'Jr.', 'Male', 22, '2002-11-03', '../uploads/default_selfie.png', 'ID', '', '0000-00-00 00:00:00.000000', '09123456789', '$2y$10$V/hWH/gjyfaY5fXuEWjxzO2viDw73BP.TUph/VYHre6AWM8D1vRyy', 'Resident', 539, 'Maligaya, Echague, Isabela', 'Purok 3', 'Single', 'College', 'Pilipino', '../uploads/default_profile_picture.png', 'What was your childhood nickname?', '$2y$10$naEW4yF35e287rIClscIJe4ClhYIkPYnLIjOg9YmiJNWWBHvaWL3.', 0, NULL, 0),
(421, 'dfdf', 'dfd', 'dfdf', '', 'Male', 23, '2001-02-22', '../uploads/default_selfie.png', 'ID', '', '0000-00-00 00:00:00.000000', '09264335355', '$2y$10$jbxyk0KWjXPvO0W8nk7J.uP../26hI.FOw9o3RVoFlMwYJW72Umb2', 'Resident', 541, 'asas', 'Purok 2', 'Single', 'College', 'sds', '../uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg', 'What was your childhood nickname?', '$2y$10$1sw1kXcvfGE3TH1PorTjtO4hGbHVym8akNKp7KX2z0b.cN7ryuOAC', 0, NULL, 0),
(422, 'Cess', 'Moon', 'Rosario', '', 'Female', 22, '2002-08-28', '../uploads/default_selfie.png', 'ID', '', '0000-00-00 00:00:00.000000', '0960252579', '$2y$10$kUbxk6DsKt74yueqjHeASOF0JU.roZdRzbJRz8yB6eYdB.ikzW2EC', 'Resident', 542, 'Alfonso Lista', 'Purok 1', 'Single', 'College', 'Pilipino', '../uploads/default_profile_picture.png', 'What was your childhood nickname?', '$2y$10$y5N/g6fOyWgiQYoLEmBRPeR8AcScLjvhTyJ44Cr95XKMFaXEdQqY2', 0, NULL, 0),
(423, 'Cessy', 'Moon', 'Montefalco', '', 'Female', 23, '2001-08-28', '../uploads/default_selfie.png', 'ID', '', '0000-00-00 00:00:00.000000', '09898912121', '$2y$10$GKr1aMpIOjyrTHUnVkFh9eKOn9ylqIEbSeqfsDBmxVCzUj5UMhooW', 'Resident', 543, 'Alfonso Lista', 'Purok 3', 'Single', 'College', 'Pilipino', '../uploads/default_profile_picture.png', 'What was your childhood nickname?', '$2y$10$5p5OsmaxgNSlPnJrrMr68OmfLmVvAfYK9d7sU0AaJqAIrGAm5hAm6', 0, NULL, 0),
(424, 'Trisha Nicole', 'Dulnuan', 'Yaranon', '', 'Male', 22, '2002-11-08', '', 'ID', '', '0000-00-00 00:00:00.000000', '09537823982', '$2y$10$8VRs3wlYTKjs2OoSD1svv.BE4/cxTfTqDmGoNueRLNzlbCy4HBNJ2', 'Resident', 544, 'La Union', 'Purok 6', 'Single', 'College', 'Filipino', '', 'What was your childhood nickname?', '$2y$10$psOQ9elw.cxnPeHM5QnToeMVEJ.a2Rm25f3iOgMcSJI9mAqt6nCgO', 0, NULL, 0),
(425, 'dfdf', 'villanueva', 'dfdfd', '', 'Male', 23, '2001-02-22', '../uploads/', 'ID', '', '0000-00-00 00:00:00.000000', '09256243434', '$2y$10$DAF1EW5a.MeCa0S0JInbeOIdmPyzPUgycxHJusIAx.McYS8EvzmGK', 'Resident', 545, 'Bohol', 'Purok 1', 'Single', 'No Formal Education', 'dfdfd', '../uploads/', 'What was your childhood nickname?', '$2y$10$skHdY9Aqa52n3mYcjImuTO8sgYr3c/PCWEd9x4pDRwjQJgvxqUM.y', 0, NULL, 0),
(426, 'lily', 'villanueva', 'Aquino', '', 'Male', 24, '2001-02-08', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09253343343', '$2y$10$Xz/w2aCp2WLm6Y0jUZxLaOKRbipME..LSK1TCM/80SmHLgD1t5Xo.', 'Resident', 546, 'Bohol', 'Purok 1', 'Single', 'No Formal Education', 'dfdfd', '../uploads/457368489_528714303027535_3753519382645185170_n.jpg', 'What was your childhood nickname?', '$2y$10$mlcWV3Ln9py0b12fg/U4d.Q6GSWQQI/4zGl4GNVO2Mvg9alLm0USW', 0, NULL, 0),
(427, 'James', 'Lemuel', 'Taming', '', 'Male', 22, '2002-06-28', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09131313131', '$2y$10$mFAdTAlAIaWvixIeQCqugekfUWMeA6WTC87533bU1uE6tGrDH3X8i', 'Resident', 547, 'Jones, Isabela', 'Purok 5', 'Single', 'College', 'Pilipino', 'null', 'What was your childhood nickname?', '$2y$10$nH6/Pt1PP7IfJs2RQuN9nuudJ8CKxfDISfPxjdf1Lx6SeROD0ZlwO', 0, NULL, 0),
(428, 'Rodeemer', 'Mooneh', 'Velazquez', '', 'Male', 23, '2001-08-01', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09141414142', '$2y$10$bwluObb9gbLjguxBGgDvq.HgRjU5qoC1/KrmymDFhfeFZKskNAXem', 'Resident', 550, 'Alicia, Isabela', 'Purok 4', 'Single', 'Highschool', 'Pilipino', 'null', 'What was your childhood nickname?', '$2y$10$NCOUZXFlYEAlvL624tVL2eS7r37MNbmHLE4euCZh51t/c3wfP4VXO', 0, NULL, 0),
(430, 'dfdfd', 'dfdfd', 'dfdfd', '', 'Female', 24, '2001-02-09', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09262432424', '$2y$10$ZutiJP5hdCW40SWvzepAB.PcSJPMDVyDn2/wiQg32aXz41guoZGYi', 'Resident', 551, 'fdfdfd', 'Purok 1', 'Single', 'No Formal Education', 'asdsd', 'null', 'What was your childhood nickname?', '$2y$10$Gi8bOFvqXc/Tp8fHsXo9bO7Og9m9frFOzcdrugm9Knr3DuKSMvWiS', 0, NULL, 0),
(431, 'dfdfddfdfdf', 'dfdfd', 'dfdfd33534', '', 'Female', 24, '2001-02-09', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09648239242', '$2y$10$M51lMMYnJe.I5aKphS43x.JYCHOmufdlNqiRMiLElJc/fNzHSpIsq', 'Resident', 552, 'fdfdfd', 'Purok 1', 'Single', 'No Formal Education', 'asdsd', 'null', 'What was your childhood nickname?', '$2y$10$/PHQguTAtQJhiODWXhvKUOda3cdZzt9GyomhnDDyxMHkovZQyUE3W', 0, NULL, 0),
(432, 'Yra', 'Lei', 'Garcia', '', 'Female', 24, '2001-02-03', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09151515151', '$2y$10$vm0tBnjbRz6FHqpX9I2Yeuq4SmVWr/cP3NyYE06RlK9m65wl9vApK', 'Resident', 553, 'Calaocan, Isabela', 'Purok 2', 'Single', 'College', 'Pilipino', 'null', 'What was your childhood nickname?', '$2y$10$3j5nHrs5BX0OPciEMyK1KeJReWsmGu3h7kFYrs4YM5wFfjaZt9q2W', 0, NULL, 0),
(433, 'Mark', 'Harryl', 'Concepcion', '', 'Male', 23, '2001-12-09', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09161616161', '$2y$10$kSODM9UJm49XH3OCspNPgu9kQdyZBa/38FHpj1nK3cWvhcmxn/cSS', 'Resident', 554, 'Bohol', 'Purok 1', 'Single', 'Elementary', 'Pilipino', 'null', 'What was your childhood nickname?', '$2y$10$vCRF.ANQ7sCWmPUfAgk8dOHq0hVAckCMutIoyG6sulyXLaMxgAS2K', 1, NULL, 0),
(434, 'dfdfdddfd3353535fdfdf', 'dfdfddfd', 'dfdfd33534', NULL, 'Female', 24, '2001-02-09', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09239242343', '$2y$10$yIHwREMl9.p2zhctEYnBke9930cUGG5tDHKF0AXMSCrnio0tL6bnm', 'Resident', 555, 'fdfdfd', 'Purok 1', 'Single', 'No Formal Education', 'asdsd', 'null', 'What was your childhood nickname?', '$2y$10$LRqD1N5N44Y3tvAifYuzt.N361lU3lMEBAZQEw4BZQshqBkcgPVW.', 0, NULL, 0),
(435, 'dfdfdddfd3353535fdfdf', 'dfdfddfd', 'dfdfd33534', NULL, 'Female', 24, '2001-02-09', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09239242343', '$2y$10$QY.s4X9zumYhj0d8.R.EfO8HrlRH7jIKKxd9hXzocpalSD4Ftlx82', 'Resident', 556, 'fdfdfd', 'Purok 1', 'Single', 'No Formal Education', 'asdsd', 'null', 'What was your childhood nickname?', '$2y$10$.rfZFjioI70QeJnBuarxVupb0utnX6Cv5nPwD/oUNlLMreQ5lRYXK', 0, NULL, 0),
(436, 'fghngngn', 'fgfgfg', 'ghghgh', NULL, 'Male', 20, '2005-02-08', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '08923323232', '$2y$10$tNtInS/Gvn/UKvmn1FPjmeCD6hxrKTGIgI4LObAzTld5vex0amlbu', 'Resident', 557, 'gamis', 'Purok 3', 'Single', 'Highschool', 'pilipino', 'null', 'What was your childhood nickname?', '$2y$10$1yZRFdsQa6/DHpX3.Fe4lefE9M3r/EP6K5luqQR6P7YGn2y6lqpta', 0, NULL, 0),
(437, 'Kathlene', 'Anne', 'Salvador', NULL, 'Female', 23, '2001-05-10', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09171717171', '$2y$10$AHk7tEhqVFmEmGbuuuHFAeFfizO6Cb6s2Uq4P2VOB1WBCqpSVdzFG', 'Resident', 558, 'Jones, Isabela', 'Purok 5', 'Single', 'Elementary', 'Pilipino', 'null', 'What was your childhood nickname?', '$2y$10$fC2sO81Oudc/OlsO9lFozO2yWzrDMdRaRFvR/0uNWYUjpadOzuaAa', 0, NULL, 0),
(438, 'sdgfdg', 'fgfgf', 'fgfg', 'fggfgfg', 'Male', 25, '2000-02-09', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09952857343', '$2y$10$Zw2OYXSLieQpIL.FvbU/y.gV7lW.kuQyT.tX/JX6uaapodr3oNHMW', 'Resident', 559, 'Bohol', 'Purok 1', 'Single', 'No Formal Education', 'pilipino', 'null', 'What was your childhood nickname?', '$2y$10$rnEOOdjgM30XkbJ.SZCvnOBIX8AyOQjnHRhoHm73liKhH3/pZFD/C', 0, NULL, 0),
(439, 'Florie', 'Mae', 'Nicolas', '', 'Female', 27, '1997-03-05', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09171717172', '$2y$10$Tyb8Fdu9JHDIflRgVS4oee9dPAPIgzEOTM9ute0.vMHxqzQXRP.c2', 'Resident', 560, 'Saranay, Isabela', 'Purok 6', 'Married', '', 'Pilipino', 'null', 'What was your childhood nickname?', '$2y$10$RCLLSiSBSbBQa1TeqTFNF..MtjjAG3ZMicBcKC6b0N/a4uWSkjUmO', 0, NULL, 0),
(440, 'Angelica', 'Agunos', 'Rosario', '', 'Female', 26, '1998-10-28', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09171717173', '$2y$10$XP7PnLu7cPgZw4Tn0mJJH.dFIiPbTQdZT/DTqGzpqs3azvTIXq/tW', 'Resident', 561, 'San Guillermo, Isabela', 'Purok 2', 'Single', 'College', 'Pilipino', 'null', 'What was your childhood nickname?', '$2y$10$SWDIrMWB67O03VljM7jIL.FjRVPv47cHNbqoreuf53gER5F03V7U2', 0, NULL, 0),
(441, 'PLOX', 'plxo', 'asdsds', 'dsd', 'Male', 23, '2001-02-21', 'null', 'ID', '', '0000-00-00 00:00:00.000000', '09363021244', '$2y$10$MRwEG3Xhfx3rBBUQTvqoMer9VyAY3TdMZ7jxv2ObMqAvdTD.fz9nO', 'Resident', 562, 'Bohol', 'Purok 2', 'Single', '', 'dfdfd', 'null', 'What was your childhood nickname?', '$2y$10$/.l51GN6eN3Gy8SIhboRt.YRlAaKxIR7v3RK4zUDazG0xC7S7tbTa', 0, NULL, 0),
(442, 'PLOXfefgddfdfdfd', 'plxo', 'asdsds', 'dsd', 'Male', 23, '2001-02-21', '../uploads/', 'ID', '', '0000-00-00 00:00:00.000000', '09363343424', '$2y$10$koy8dOhpPnxVH630TGxnIenTGAvcUTRYVDf.ke66onFNT3IAJTa72', 'Resident', 563, 'Bohol', 'Purok 2', 'Single', '', 'dfdfd', '../uploads/', 'What was your childhood nickname?', '$2y$10$sm8WLcGWMtrQqI5vd/nPX..RP9rB/kX7I17sk71sWiuEHflG8JP9m', 0, NULL, 0),
(451, 'Reyven', 'Ojadas', 'Pili', '', 'Male', 23, '2002-02-23', '../uploads/434309021_1132446914738848_2867123597804432849_n.jpg', 'ID', '', '0000-00-00 00:00:00.000000', '09812834617', '$2y$10$jQjhbduh8qihbyWFwx3fIeswaS8CjMugdm8gDDb0y3K3E5mjM3HIm', 'Resident', 568, 'San Pascual, Diffun, Quirino', 'Purok 1', 'Married', 'College', 'Filipino', '../uploads/IMG_20250220_141248_BURST096.jpg', 'What was your childhood nickname?', '$2y$10$eTLav1UQr51QKE2f4dV02els94wG9TzEvhYQE8lzWssoBpk2Euapa', 0, NULL, 0),
(453, 'Neal', 'fgfgf', 'Concepcion', '', 'Male', 24, '2001-02-22', '', 'ID', '', '0000-00-00 00:00:00.000000', '09569945422', '$2y$10$iIURUFvaHfv2qXcGyxP4g.wIpj9Kf8I3qHVbIXlYeCp59FvqeP7Se', 'Resident', 569, 'Santiago', 'Purok 2', 'Married', 'College', 'Filipino', '', 'What was your childhood nickname?', '$2y$10$DjWVlEnFMsiVSrtISFnkAO0O4GIRjQaX0G3VEcrY8Cgm/0IfGiktS', 0, NULL, 0),
(454, 'John Lloyd', 'Colobong', 'Manuel', '', 'Male', 22, '2002-07-13', '../uploads/459116026_2167533256980735_4089694213094330184_n.jpg', 'ID', '', '0000-00-00 00:00:00.000000', '09071119169', '$2y$10$tqlwN39fLhCCn/IUYBPziugroe/nEpnFyLy4MtS9p3PcZ.N3Q8ZLi', 'Resident', 570, 'gamis', 'Purok 1', 'Single', 'No Formal Education', 'Filipino', '../uploads/dinbir.jpg', 'What was your childhood nickname?', '$2y$10$.AB81hbpbP1TA3epyUFsfOY87TWeARS4HYQ.ciN071QcGnQceQ1Hm', 0, NULL, 0),
(455, 'denver', 'Deo', 'Gorospe', '', 'Male', 24, '2001-02-22', '', 'ID', '', '0000-00-00 00:00:00.000000', '09686448079', '$2y$10$B4tLNWooIoJU68ON6a1wG.YjfGwdShUcixztYWJ5nLSgfYR.TUHTK', 'Resident', 571, 'Bohol', 'Purok 2', 'Single', 'No Formal Education', 'Filipino', '', 'What was your childhood nickname?', '$2y$10$/xShNJ6VqdLrFVNCds5sNuVPNNGrBoKhYupZ5f9JsdoIMtZHGy3oG', 0, NULL, 0),
(456, 'Eugene', 'G', 'Tobias', '', 'Male', 24, '2001-02-22', '', 'ID', '', '0000-00-00 00:00:00.000000', '09050845168', '$2y$10$HAMqMy6HfSDViOBXqZOH8uBXfj7hRheTYuMgYeJSvVRPfx9zY.oDa', 'Resident', 557, 'Bohol', 'Purok 2', 'Single', 'No Formal Education', 'Filipino', '', 'What was your childhood nickname?', '$2y$10$hudWn2YfOe3muxktTyK9O.LSg3RUzpa1LsYNHnWfV2U0JCw3E5vXS', 0, NULL, 0),
(457, 'Prinsesa', 'Cadiente', 'Rosario', '', 'Female', 23, '2001-08-28', '../uploads/kick.jpg', 'ID', '', '0000-00-00 00:00:00.000000', '09675487502', '$2y$10$azkynbZm4uyGZX3RuxMJKOtIm6TV5uASmKayfZ9uuUV/Z5WVzrH2G', 'Resident', 527, 'Alfonso Lista', 'Purok 1', 'Single', 'College', 'Pilipino', '../uploads/6c4df07d-c979-4676-bf55-e2062404e9a6.jpg', 'What was your childhood nickname?', '$2y$10$ZFHrn7yw6WwQBOulgw5XKuHVOxXr/xwbteADT1vQ41IsasSjpfzqy', 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_barangay`
--

CREATE TABLE `tbl_users_barangay` (
  `barangays_id` int(11) NOT NULL,
  `barangay_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users_barangay`
--

INSERT INTO `tbl_users_barangay` (`barangays_id`, `barangay_name`) VALUES
(525, 'Angoluan'),
(526, 'Aromin'),
(527, 'Cabatuan'),
(528, 'Gamis Saguday Quirino'),
(529, 'Fugu'),
(530, 'Cabatuan'),
(531, 'Maligaya'),
(532, 'Maligaya'),
(533, 'Angoluan'),
(534, 'Angoluan'),
(535, 'Angoluan'),
(536, 'Maligaya'),
(537, 'Maligaya'),
(538, 'Bacradal'),
(539, 'Maligaya'),
(540, 'Maligaya'),
(541, 'Angoluan'),
(542, 'Maligaya'),
(543, 'Maligaya'),
(544, 'Angoluan'),
(545, 'Buneg'),
(546, 'Buneg'),
(547, 'Pangal Sur'),
(548, 'Angoluan'),
(549, 'Pangal Norte'),
(550, 'Pangal Norte'),
(551, 'Angoluan'),
(552, 'Angoluan'),
(553, 'Aromin'),
(554, 'Quirino'),
(555, 'Gamis'),
(556, 'Gamis'),
(557, 'San Fabian'),
(558, 'Aromin'),
(559, 'Maligaya'),
(560, 'Aromin'),
(561, 'Maligaya'),
(562, 'San Fabian'),
(563, 'San Fabian'),
(564, 'sdsd'),
(565, 'Adsd'),
(566, 'dsdsdsds'),
(567, 'swfsf'),
(568, 'Gamis, Saguday, Quirino'),
(569, 'Santiago City'),
(570, 'San Isidro'),
(571, 'tfdfdd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_announcement`
--
ALTER TABLE `tbl_announcement`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `tbl_brg_official`
--
ALTER TABLE `tbl_brg_official`
  ADD PRIMARY KEY (`official_id`),
  ADD KEY `tbl_brg_official_ibfk_1` (`barangays_id`);

--
-- Indexes for table `tbl_complaintcategories`
--
ALTER TABLE `tbl_complaintcategories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  ADD PRIMARY KEY (`complaints_id`),
  ADD KEY `tbl_complaints_ibfk_2` (`category_id`),
  ADD KEY `tbl_complaints_ibfk_3` (`image_id`),
  ADD KEY `tbl_complaints_ibfk_4` (`info_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tbl_complaints_ibfk_5` (`barangays_id`),
  ADD KEY `cert_id` (`cert_id`);

--
-- Indexes for table `tbl_complaints_certificates`
--
ALTER TABLE `tbl_complaints_certificates`
  ADD PRIMARY KEY (`cert_id`),
  ADD KEY `tbl_complaints_certificates_ibfk_1` (`complaints_id`);

--
-- Indexes for table `tbl_evidence`
--
ALTER TABLE `tbl_evidence`
  ADD KEY `tbl_evidence_ibfk_1` (`complaints_id`);

--
-- Indexes for table `tbl_hearing_history`
--
ALTER TABLE `tbl_hearing_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_id` (`complaints_id`);

--
-- Indexes for table `tbl_login_logs`
--
ALTER TABLE `tbl_login_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `tbl_users_ibfk_2` (`barangays_id`),
  ADD KEY `tbl_users_ibfk_3` (`pic_data`);

--
-- Indexes for table `tbl_users_barangay`
--
ALTER TABLE `tbl_users_barangay`
  ADD PRIMARY KEY (`barangays_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `tbl_announcement`
--
ALTER TABLE `tbl_announcement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_brg_official`
--
ALTER TABLE `tbl_brg_official`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `tbl_complaintcategories`
--
ALTER TABLE `tbl_complaintcategories`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=460;

--
-- AUTO_INCREMENT for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  MODIFY `complaints_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=622;

--
-- AUTO_INCREMENT for table `tbl_complaints_certificates`
--
ALTER TABLE `tbl_complaints_certificates`
  MODIFY `cert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_hearing_history`
--
ALTER TABLE `tbl_hearing_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `tbl_login_logs`
--
ALTER TABLE `tbl_login_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=364;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=458;

--
-- AUTO_INCREMENT for table `tbl_users_barangay`
--
ALTER TABLE `tbl_users_barangay`
  MODIFY `barangays_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=572;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_brg_official`
--
ALTER TABLE `tbl_brg_official`
  ADD CONSTRAINT `tbl_brg_official_ibfk_1` FOREIGN KEY (`barangays_id`) REFERENCES `tbl_users_barangay` (`barangays_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  ADD CONSTRAINT `tbl_complaints_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `tbl_complaintcategories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_complaints_ibfk_5` FOREIGN KEY (`barangays_id`) REFERENCES `tbl_users_barangay` (`barangays_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_complaints_ibfk_6` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_complaints_certificates`
--
ALTER TABLE `tbl_complaints_certificates`
  ADD CONSTRAINT `tbl_complaints_certificates_ibfk_1` FOREIGN KEY (`complaints_id`) REFERENCES `tbl_complaints` (`complaints_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_evidence`
--
ALTER TABLE `tbl_evidence`
  ADD CONSTRAINT `tbl_evidence_ibfk_1` FOREIGN KEY (`complaints_id`) REFERENCES `tbl_complaints` (`complaints_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_hearing_history`
--
ALTER TABLE `tbl_hearing_history`
  ADD CONSTRAINT `tbl_hearing_history_ibfk_1` FOREIGN KEY (`complaints_id`) REFERENCES `tbl_complaints` (`complaints_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_login_logs`
--
ALTER TABLE `tbl_login_logs`
  ADD CONSTRAINT `tbl_login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_ibfk_2` FOREIGN KEY (`barangays_id`) REFERENCES `tbl_users_barangay` (`barangays_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
