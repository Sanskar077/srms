-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `srms`
-- Updated for MSBTE Diploma College
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `fullname`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$XOmMblNy8tmuSrMKiR5vBekwxmLw3WK3vtqmzJgGFDdFdwqb.JULi', 'MSBTE Administrator', 'admin@msbtecollege.edu', '2023-05-10 09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(50) NOT NULL,
  `section` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `classes` for MSBTE Diploma Courses
--

INSERT INTO `classes` (`id`, `class_name`, `section`, `created_at`) VALUES
(1, 'Computer Engineering', 'First Year', '2023-05-10 09:00:00'),
(2, 'Computer Engineering', 'Second Year', '2023-05-10 09:00:00'),
(3, 'Computer Engineering', 'Third Year', '2023-05-10 09:00:00'),
(4, 'Mechanical Engineering', 'First Year', '2023-05-10 09:00:00'),
(5, 'Mechanical Engineering', 'Second Year', '2023-05-10 09:00:00'),
(6, 'Mechanical Engineering', 'Third Year', '2023-05-10 09:00:00'),
(7, 'Electrical Engineering', 'First Year', '2023-05-10 09:00:00'),
(8, 'Electrical Engineering', 'Second Year', '2023-05-10 09:00:00'),
(9, 'Electrical Engineering', 'Third Year', '2023-05-10 09:00:00'),
(10, 'Civil Engineering', 'First Year', '2023-05-10 09:00:00'),
(11, 'Civil Engineering', 'Second Year', '2023-05-10 09:00:00'),
(12, 'Civil Engineering', 'Third Year', '2023-05-10 09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `marks` float NOT NULL,
  `posting_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `class_id` (`class_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `roll_id` varchar(30) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` enum('M','F') DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  `dob` date DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roll_id` (`roll_id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(100) NOT NULL,
  `subject_code` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `subject_code` (`subject_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjects` for MSBTE Diploma Courses
--

INSERT INTO `subjects` (`id`, `subject_name`, `subject_code`, `created_at`) VALUES
(1, 'Engineering Mathematics', 'MT101', '2023-05-10 09:00:00'),
(2, 'Engineering Physics', 'PH101', '2023-05-10 09:00:00'),
(3, 'Engineering Chemistry', 'CH101', '2023-05-10 09:00:00'),
(4, 'Engineering Drawing', 'ED101', '2023-05-10 09:00:00'),
(5, 'Communication Skills', 'CS101', '2023-05-10 09:00:00'),
(6, 'Programming in C', 'PC201', '2023-05-10 09:00:00'),
(7, 'Data Structures', 'DS202', '2023-05-10 09:00:00'),
(8, 'Database Management Systems', 'DB203', '2023-05-10 09:00:00'),
(9, 'Computer Networks', 'CN301', '2023-05-10 09:00:00'),
(10, 'Web Development', 'WD302', '2023-05-10 09:00:00'),
(11, 'Mechanics', 'ME201', '2023-05-10 09:00:00'),
(12, 'Thermodynamics', 'TD202', '2023-05-10 09:00:00'),
(13, 'Electric Circuits', 'EC201', '2023-05-10 09:00:00'),
(14, 'Civil Construction', 'CC201', '2023-05-10 09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblnotice`
--

CREATE TABLE IF NOT EXISTS `tblnotice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `noticeTitle` varchar(255) NOT NULL,
  `noticeDetails` text DEFAULT NULL,
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblnotice`
--

INSERT INTO `tblnotice` (`id`, `noticeTitle`, `noticeDetails`, `creationDate`) VALUES
(1, 'Diploma Summer 2025 Exam Schedule Released', 'All students are hereby informed that the Summer 2025 examination schedule for all diploma courses has been released. Please check the MSBTE portal for detailed timetable. Students with backlogs must submit their exam forms before the deadline.', '2025-03-20 09:00:00'),
(2, 'MSBTE Online Verification Portal Open', 'The MSBTE online document verification portal is now open for diploma certificate verification. Students who need their documents verified for higher education or employment purposes can apply through the portal.', '2025-03-20 10:00:00'),
(3, 'Industrial Visit Schedule Updated', 'The schedule for industrial visits for the final year diploma students has been updated. Please check with your respective department coordinators for details about industry partners and visit dates.', '2025-03-20 11:00:00');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;
COMMIT;