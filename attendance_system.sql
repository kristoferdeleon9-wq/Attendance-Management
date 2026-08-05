-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2026 at 09:27 AM
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
-- Database: `attendance_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `attendance_date` date NOT NULL,
  `time_in` time NOT NULL,
  `status` varchar(20) NOT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `attendance_date`, `time_in`, `status`, `class_id`) VALUES
(1, '06-2324-031075', '2026-07-29', '05:44:35', 'Present', NULL),
(2, '123-6582-852206', '2026-07-29', '07:24:27', 'Present', NULL),
(3, '50166465316531', '2026-07-29', '07:31:56', 'Present', NULL),
(4, '0849545', '2026-07-29', '09:16:00', 'Present', NULL),
(5, 'asfadhadjad', '2026-07-29', '10:17:55', 'Present', 2),
(6, 'njdfsnjfkm', '2026-07-30', '03:28:21', 'Present', 1),
(7, '06-2324-52456', '2026-07-30', '11:17:04', 'Present', 3),
(8, 'dgdahrhara', '2026-07-30', '15:51:44', 'Present', 5),
(9, 'sdadgsh', '2026-07-31', '10:10:41', 'Present', 7),
(10, 'sdadgsh', '2026-07-31', '10:11:45', 'Present', 6),
(11, 'bbdbdsd', '2026-07-31', '10:13:28', 'Present', 7),
(12, 'bbdbdsd', '2026-07-31', '10:24:32', 'Present', 8),
(13, 'sdadgsh', '2026-07-31', '10:28:38', 'Present', 8),
(14, 'ntnyurt', '2026-07-31', '16:09:52', 'Present', 8),
(15, 'ntnyurt', '2026-07-31', '16:15:11', 'Present', 7),
(16, 'dnsdnsg', '2026-07-31', '16:29:19', 'Present', 7),
(17, 'safafasas', '2026-08-02', '20:07:43', 'Present', 9),
(18, '1869241294', '2026-08-02', '20:10:48', 'Present', 9),
(19, '1869241294', '2026-08-02', '20:19:21', 'Present', 10),
(20, '1869241294', '2026-08-03', '10:17:41', 'Present', 9),
(21, 'bahadhfdgs', '2026-08-03', '10:17:52', 'Present', 9),
(22, 'safafasas', '2026-08-03', '10:18:08', 'Present', 9),
(23, '0849545', '2026-08-03', '11:06:10', 'Present', 13),
(24, '06-2324-52456', '2026-08-03', '11:59:23', 'Present', 3);

-- --------------------------------------------------------

--
-- Table structure for table `attendance_sessions`
--

CREATE TABLE `attendance_sessions` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `session_date` date NOT NULL,
  `status` enum('OPEN','CLOSED') DEFAULT 'OPEN',
  `opened_at` datetime DEFAULT current_timestamp(),
  `closed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_sessions`
--

INSERT INTO `attendance_sessions` (`id`, `class_id`, `session_date`, `status`, `opened_at`, `closed_at`) VALUES
(1, 2, '2026-07-30', 'CLOSED', '2026-07-30 10:40:07', '2026-07-30 11:12:30'),
(2, 1, '2026-07-30', 'CLOSED', '2026-07-30 10:41:55', '2026-07-30 14:49:11'),
(3, 3, '2026-07-30', 'CLOSED', '2026-07-30 11:16:51', '2026-07-30 16:19:21'),
(4, 4, '2026-07-30', 'CLOSED', '2026-07-30 11:23:09', '2026-07-30 16:19:00'),
(5, 5, '2026-07-30', 'CLOSED', '2026-07-30 11:55:28', '2026-07-30 16:56:52'),
(6, 6, '2026-07-30', 'CLOSED', '2026-07-30 16:21:03', '2026-07-30 16:56:46'),
(7, 6, '2026-07-31', 'CLOSED', '2026-07-31 09:42:34', '2026-07-31 16:44:32'),
(8, 7, '2026-07-31', 'OPEN', '2026-07-31 10:02:24', NULL),
(9, 5, '2026-07-31', 'CLOSED', '2026-07-31 10:23:19', '2026-07-31 10:23:43'),
(10, 8, '2026-07-31', 'CLOSED', '2026-07-31 10:24:27', '2026-07-31 16:36:40'),
(11, 2, '2026-07-31', 'OPEN', '2026-07-31 13:20:24', NULL),
(12, 4, '2026-07-31', 'CLOSED', '2026-07-31 16:44:53', '2026-07-31 16:44:56'),
(13, 9, '2026-08-02', 'OPEN', '2026-08-02 19:36:29', NULL),
(14, 10, '2026-08-02', 'OPEN', '2026-08-02 20:17:31', NULL),
(15, 12, '2026-08-03', 'OPEN', '2026-08-03 09:59:10', NULL),
(16, 9, '2026-08-03', 'OPEN', '2026-08-03 10:15:46', NULL),
(17, 13, '2026-08-03', 'OPEN', '2026-08-03 11:01:56', NULL),
(18, 3, '2026-08-03', 'OPEN', '2026-08-03 11:59:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attendance_table`
--

CREATE TABLE `attendance_table` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `attendance_date` date DEFAULT NULL,
  `time_in` datetime DEFAULT NULL,
  `status` enum('Present','Absent') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `instructor` varchar(100) DEFAULT NULL,
  `day_of_week` varchar(20) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `class_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `section_id`, `subject`, `instructor`, `day_of_week`, `start_time`, `end_time`, `class_date`) VALUES
(1, '4-2', 0, 'Math', 'rodel', 'Wednesday', '14:13:00', '15:02:00', '2026-08-03'),
(2, 'Happy', 7, 'Math', 'rodel', 'Wednesday', '16:15:00', '17:02:00', '2026-08-03'),
(3, 'for today', 2, 'tech', 'lodel', 'Thursday', '11:15:00', '12:00:00', '2026-08-03'),
(4, 'Sciencoe', 2, 'Science Fair', 'John Doe', 'Monday', '08:00:00', '17:00:00', '2026-08-03'),
(5, 'core', 8, 'architecture', 'dole', 'Thursday', '08:00:00', '17:00:00', '2026-08-03'),
(6, 'panda', 10, 'new venture', 'rodel', 'Thursday', '08:00:00', '17:00:00', '2026-08-03'),
(7, 'angkas', 10, 'law', 'curse', 'Friday', '08:00:00', '17:00:00', '2026-08-03'),
(8, 'record', 10, 'new orb', 'lop', 'Friday', '08:00:00', '17:00:00', '2026-08-03'),
(9, 'low', 11, 'new Venture', 'rodel', 'Monday', '08:00:00', '17:00:00', '2026-08-03'),
(10, 'pol', 11, 'new venture', 'rodel', 'Monday', '13:30:00', '15:00:00', '2026-08-03'),
(11, 'asdsasd', 5, 'new venture', 'rodel', 'Monday', '09:50:00', '10:00:00', '2026-08-03'),
(12, 'asdsasd', 5, 'new venture', 'rodel', 'Monday', '09:50:00', '10:00:00', '2026-08-03'),
(13, 'update', 5, 'P.E.', 'John Doe', '', '11:00:00', '11:15:00', '2026-08-03');

-- --------------------------------------------------------

--
-- Table structure for table `class_table`
--

CREATE TABLE `class_table` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(100) DEFAULT NULL,
  `teacher_name` varchar(100) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`) VALUES
(5, 'BSCS 4A'),
(6, 'BSCS 4B'),
(3, 'BSIT 3A'),
(4, 'BSIT 3B'),
(10, 'BSIT 4-10'),
(7, 'BSIT 4-2'),
(11, 'BSIT 4-4'),
(8, 'BSIT 4-9'),
(1, 'BSIT 4A'),
(2, 'BSIT 4B');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `course` varchar(50) NOT NULL,
  `section_id` int(11) NOT NULL,
  `qr_code` varchar(100) NOT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `fullname`, `course`, `section_id`, `qr_code`, `is_deleted`, `photo`) VALUES
(6, '123-6582-852206', 'leonel', 'BSIT', 0, '../qrcodes/123-6582-852206.png', 0, NULL),
(8, '50166465316531', 'vkyjuuiokih', 'biuinool', 0, '../qrcodes/50166465316531.png', 0, NULL),
(9, '06-2324-031075', 'Kristofer Eugene D. de Leon', 'BSIT', 0, '../qrcodes/06-2324-031075.png', 0, NULL),
(10, '85951651', 'afasasgasfsa', 'asdgssd', 0, '../qrcodes/85951651.png', 0, NULL),
(11, '0849545', 'chjvljhv lj', 'BSIT', 5, '../qrcodes/0849545.png', 0, NULL),
(12, 'asfadhadjad', 'adgadhadja', 'asfas', 7, '../qrcodes/asfadhadjad.png', 0, NULL),
(13, 'dgjgfj', 'dg,hmerh', 'BSIT', 7, '../qrcodes/dgjgfj.png', 0, NULL),
(14, 'njdfsnjfkm', 'dnjngjg', 'bf dfs f es', 3, '../qrcodes/njdfsnjfkm.png', 0, NULL),
(15, '06-2324-52456', 'kroft', 'BSIT', 2, '../qrcodes/06-2324-52456.png', 0, NULL),
(16, 'ntnyurt', 'nre ysynbbbbb', 'eabt ', 10, '../qrcodes/ntnyurt.png', 0, NULL),
(17, 'ebvrevw', 'vwe rrey beye', ' wtwetwv ', 8, '../qrcodes/ebvrevw.png', 0, NULL),
(18, 'dgdahrhara', 'ssagargrhrhrrh', 'awrhrh', 8, '../qrcodes/dgdahrhara.png', 0, '1785395161_facebook.png'),
(19, 'dgdagdf', 'sdfsadgsg', 'dsgaaa', 8, '../qrcodes/dgdagdf.png', 0, '1785397964_Screenshot (17).png'),
(20, 'dfhadfhda', 'dfhadfhafh', 'dfshfh', 1, '../qrcodes/dfhadfhda.png', 0, '1785460296_Screenshot (16).png'),
(22, 'bbdbdsd', 'gfbgfbdgb', 'fvssf', 10, '../qrcodes/bbdbdsd.png', 0, '1785463222_Screenshot (1).png'),
(23, 'sdadgsh', 'fasafsfsa', 'dsdgga', 10, '../qrcodes/sdadgsh.png', 0, '1785463729_Screenshot (2).png'),
(24, 'dnsdnsg', 'ndfsnfsdnf', 'sndfsdfsdnf', 10, '../qrcodes/dnsdnsg.png', 0, '1785486551_Screenshot (3).png'),
(26, '1869241294', 'dgskjssdgskh', 'BSIT', 11, '../qrcodes/1869241294.png', 0, '1785670503_Screenshot (1).png'),
(27, 'bahadhfdgs', 'adhdhdfh', 'BSIT', 11, '../qrcodes/bahadhfdgs.png', 0, '1785671522_Screenshot (2).png'),
(28, 'safafasas', 'asfasfasg', 'BSIT', 11, '../qrcodes/safafasas.png', 0, '1785672420_Screenshot (3).png'),
(29, 'jdknldgsl', 'enjwen;n', 'BSCS', 5, '../qrcodes/jdknldgsl.png', 0, '1785726092_Screenshot (4).png'),
(30, 'dahajaj', 'eheshhsrhs', 'rhsrhrh', 2, '../qrcodes/dahajaj.png', 0, '1785729525_Screenshot (6).png');

-- --------------------------------------------------------

--
-- Table structure for table `student_table`
--

CREATE TABLE `student_table` (
  `student_id` int(11) NOT NULL,
  `student_no` varchar(50) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `role`) VALUES
(1, 'System Administrator', 'admin', 'admin123', 'admin'),
(2, 'John Teacher', 'teacher', 'teacher123', 'teacher'),
(3, 'Juan Dela Cruz', 'student', 'student123', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_sessions`
--
ALTER TABLE `attendance_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_id` (`class_id`,`session_date`);

--
-- Indexes for table `attendance_table`
--
ALTER TABLE `attendance_table`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_table`
--
ALTER TABLE `class_table`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `section_name` (`section_name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `student_table`
--
ALTER TABLE `student_table`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `attendance_sessions`
--
ALTER TABLE `attendance_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `attendance_table`
--
ALTER TABLE `attendance_table`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `class_table`
--
ALTER TABLE `class_table`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `student_table`
--
ALTER TABLE `student_table`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
