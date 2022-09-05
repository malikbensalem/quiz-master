-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 05, 2022 at 06:54 AM
-- Server version: 8.0.27
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz_master`
--

-- --------------------------------------------------------

--
-- Table structure for table `question_category`
--

DROP TABLE IF EXISTS `question_category`;
CREATE TABLE IF NOT EXISTS `question_category` (
  `qc_id` int NOT NULL AUTO_INCREMENT,
  `qc_desc` varchar(255) NOT NULL,
  PRIMARY KEY (`qc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `question_category`
--

INSERT INTO `question_category` (`qc_id`, `qc_desc`) VALUES
(1, 'Maths'),
(2, 'Science'),
(3, 'Computing'),
(4, 'Art'),
(5, 'History');

-- --------------------------------------------------------

--
-- Table structure for table `question_header`
--

DROP TABLE IF EXISTS `question_header`;
CREATE TABLE IF NOT EXISTS `question_header` (
  `qh_id` int NOT NULL AUTO_INCREMENT,
  `qh_title` varchar(255) NOT NULL,
  `qh_u_id` int NOT NULL,
  `qh_qc_id` json NOT NULL,
  `qh_input_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `qh_pass` int NOT NULL,
  `qh_qs_id` int NOT NULL,
  PRIMARY KEY (`qh_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `question_header`
--

INSERT INTO `question_header` (`qh_id`, `qh_title`, `qh_u_id`, `qh_qc_id`, `qh_input_date`, `qh_pass`, `qh_qs_id`) VALUES
(1, 'questionnaire 1', 6, '[1, 2]', '2022-08-31 11:27:41', 0, 2),
(2, 'title', 6, '[]', '2022-09-02 15:39:45', 0, 1),
(3, 'title', 6, '[]', '2022-09-02 15:42:48', 0, 6),
(4, 'questionnaire 1', 5, '[1, 2]', '2022-09-04 16:20:11', 0, 1),
(5, 'title', 5, '[\"1\", \"2\"]', '2022-09-04 16:25:57', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `question_header_assignee`
--

DROP TABLE IF EXISTS `question_header_assignee`;
CREATE TABLE IF NOT EXISTS `question_header_assignee` (
  `qha_qh_id` int NOT NULL,
  `qha_u_id` int NOT NULL,
  `qh_input_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `qh_end_date` date NOT NULL,
  `qh_assigned_by` int NOT NULL,
  `qha_live` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`qha_qh_id`,`qha_u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `question_header_assignee`
--

INSERT INTO `question_header_assignee` (`qha_qh_id`, `qha_u_id`, `qh_input_date`, `qh_end_date`, `qh_assigned_by`, `qha_live`) VALUES
(4, 5, '2022-09-04 15:17:53', '2022-09-02', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `question_line`
--

DROP TABLE IF EXISTS `question_line`;
CREATE TABLE IF NOT EXISTS `question_line` (
  `ql_id` int NOT NULL AUTO_INCREMENT,
  `ql_qh_id` int NOT NULL,
  `ql_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ql_options` json NOT NULL,
  `ql_mark` int NOT NULL,
  `ql_qs_id` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`ql_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `question_line`
--

INSERT INTO `question_line` (`ql_id`, `ql_qh_id`, `ql_title`, `ql_options`, `ql_mark`, `ql_qs_id`) VALUES
(1, 1, 'question 1', '[{\"title\": \"option 1\", \"correct\": \"true\"}]', 2, 3),
(2, 1, 'Question 1', '[{\"title\": \"opt 1\", \"correct\": \"false\"}, {\"title\": \"opt 2\", \"correct\": \"false\"}, {\"title\": \"opt 3\", \"correct\": \"false\"}, {\"title\": \"opt 4\", \"correct\": \"true\"}]', 2, 1),
(3, 4, 'question 1', '[{\"title\": \"opt 1\", \"correct\": \"false\"}, {\"title\": \"opt 2\", \"correct\": \"false\"}, {\"title\": \"opt 3\", \"correct\": \"true\"}]', 2, 3),
(4, 4, 'question 1', '[{\"title\": \"opt 1\", \"correct\": \"false\"}, {\"title\": \"opt 2\", \"correct\": \"false\"}, {\"title\": \"opt 3\", \"correct\": \"false\"}, {\"title\": \"opt 4\", \"correct\": \"true\"}]', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `question_status`
--

DROP TABLE IF EXISTS `question_status`;
CREATE TABLE IF NOT EXISTS `question_status` (
  `qs_id` int NOT NULL AUTO_INCREMENT,
  `qs_desc` varchar(255) NOT NULL,
  PRIMARY KEY (`qs_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `question_status`
--

INSERT INTO `question_status` (`qs_id`, `qs_desc`) VALUES
(1, 'Published'),
(2, 'Drafted'),
(3, 'Deleted');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `u_id` int NOT NULL AUTO_INCREMENT,
  `u_us_id` int NOT NULL DEFAULT '1',
  `u_ut_id` int NOT NULL DEFAULT '1',
  `u_email` varchar(127) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `u_first_name` varchar(255) NOT NULL,
  `u_last_name` varchar(255) NOT NULL,
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `u_email` (`u_email`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `u_us_id`, `u_ut_id`, `u_email`, `u_password`, `u_first_name`, `u_last_name`) VALUES
(6, 3, 1, 'student@webbiskools.co.uk', '378e6ef8f96f29646777acea327e12c5822d4fc1f4893ce7c7d2c31bde61eab26bbc8aedf617eee2aa68dcc3b38d83937df1497dfd614a86ba730493dcccffb7', 'First', 'Last'),
(5, 1, 3, 'malik.bensalem@webbiskools.co.uk', 'f3f7e127bf39ba6e596e84947b4ab4083d29ff33c423a2d1e24e7e1816452a71e45efed9248f50e5a351ea72571a8954430a10f46eed2d1af72457f5b41e5d91', 'Malik', 'Bensalem');

-- --------------------------------------------------------

--
-- Table structure for table `users_question_header`
--

DROP TABLE IF EXISTS `users_question_header`;
CREATE TABLE IF NOT EXISTS `users_question_header` (
  `uqh_id` int NOT NULL AUTO_INCREMENT,
  `uqh_u_id` int NOT NULL,
  `uqh_qh_id` int NOT NULL,
  `uqh_score` int NOT NULL,
  `uqh_total` int NOT NULL,
  `uqh_input_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uqh_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users_question_header`
--

INSERT INTO `users_question_header` (`uqh_id`, `uqh_u_id`, `uqh_qh_id`, `uqh_score`, `uqh_total`, `uqh_input_date`) VALUES
(6, 5, 4, 2, 2, '2022-09-02 15:05:44');

-- --------------------------------------------------------

--
-- Table structure for table `users_status`
--

DROP TABLE IF EXISTS `users_status`;
CREATE TABLE IF NOT EXISTS `users_status` (
  `us_id` int NOT NULL AUTO_INCREMENT,
  `us_desc` varchar(255) NOT NULL,
  PRIMARY KEY (`us_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users_status`
--

INSERT INTO `users_status` (`us_id`, `us_desc`) VALUES
(1, 'Live'),
(2, 'Banned');

-- --------------------------------------------------------

--
-- Table structure for table `users_type`
--

DROP TABLE IF EXISTS `users_type`;
CREATE TABLE IF NOT EXISTS `users_type` (
  `ut_id` int NOT NULL AUTO_INCREMENT,
  `ut_desc` varchar(255) NOT NULL,
  `ut_level` int NOT NULL,
  PRIMARY KEY (`ut_id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users_type`
--

INSERT INTO `users_type` (`ut_id`, `ut_desc`, `ut_level`) VALUES
(1, 'Student', 1),
(3, 'Teacher', 3),
(4, 'Admin', 4),
(2, 'Visitor', 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
