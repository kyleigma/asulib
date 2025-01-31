-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 30, 2025 at 09:04 AM
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
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `created_on` date NOT NULL,
  `role` int(11) NOT NULL COMMENT 'admin-0 staff-1',
  `status` int(11) NOT NULL COMMENT '0 - active 1 - inactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `firstname`, `lastname`, `photo`, `created_on`, `role`, `status`) VALUES
(2, 'admin', '$2y$10$4r4I7PZq9Thz99qgKzT1cu/rTDSLWI5fCPP1pShvdwbuA65Lqpi0e', 'Library', 'Admin', 'default.jpg', '0000-00-00', 0, 0),
(4, 'staff', '$2y$10$Wzl8frNxPPbwAfugXND9M.tkSlZsegqNzwqx7Ev3z469kETvoviW2', 'Lib', 'Staff', 'default.jpg', '2024-09-18', 1, 0),
(6, 'staff2', '$2y$10$MWWuH5XYBsEAUoG4oADomOBgqwGKGnS.MwA3AkuuPcAlOf5QOFNly', 'Lib', 'Staff 2', 'EV0PcBUVAAQnsdX.jpg', '2024-09-18', 1, 1),
(7, 'staff3', '$2y$10$SK/Z0T/giJx3k82kHSs3G.BEPVb9FhZmEeMkI9rp4Hfhp.kLPCPlO', 'Lib', 'Staff 3', 'default.jpg', '2024-11-07', 1, 0),
(8, 'staff4', '$2y$10$wCyP5Uji0F1DVGk3oZkPO.TLRTbPiist9V97r.4VOejrbUGc7w5sC', 'Lib', 'Staff 4', 'default.jpg', '2024-12-07', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `accession` varchar(20) NOT NULL,
  `volume` varchar(100) NOT NULL,
  `category_no` varchar(50) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` text NOT NULL,
  `author` varchar(150) NOT NULL,
  `publisher` varchar(150) NOT NULL,
  `publish_date` int(4) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '2 - reserved  \r\n1 - borrowed 0 - available'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `book_id` int(11) NOT NULL,
  `date_borrow` date NOT NULL,
  `status` int(1) NOT NULL COMMENT '1 = borrowed  0 = returned',
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'General Knowledge'),
(2, 'Philosophy and Psychology'),
(3, 'Religion'),
(4, 'Social Sciences'),
(5, 'Languages'),
(6, 'Science'),
(7, 'Technology'),
(8, 'Arts and Recreation'),
(9, 'Literature'),
(10, 'History and Geography');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `faculty_id` varchar(15) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL COMMENT '0 - faculty\r\n1 - cos \r\n2 - external\r\n3 - Non-teaching',
  `program_id` int(11) DEFAULT NULL,
  `created_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `id` int(255) NOT NULL,
  `student_id` int(100) DEFAULT NULL,
  `faculty_id` int(100) DEFAULT NULL,
  `book_id` int(100) NOT NULL,
  `borrow_id` int(100) NOT NULL,
  `date_borrow` date NOT NULL,
  `overdue_days` int(100) NOT NULL,
  `fine_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('unpaid','paid') DEFAULT 'unpaid',
  `date_paid` date DEFAULT NULL,
  `last_updated` date DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `code` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `title`, `code`) VALUES
(1, 'Bachelor of Technical-Vocational Teacher Education', 'BTVTED'),
(2, 'Bachelor of Science in Architecture', 'BSA'),
(3, 'Bachelor of Science in Civil Engineering', 'BSCE'),
(4, 'Bachelor of Science in Information Technology', 'BSInfoTech'),
(5, 'Bachelor of Science in Information Systems', 'BSIS'),
(6, 'Bachelor of Science in Entertainment and Multimedia Computing', 'BSEMC'),
(7, 'Bachelor of Science in Hospitality Management', 'BSHM'),
(8, 'Bachelor of Science in Industrial Technology', 'BSIT'),
(9, 'Master of Arts in Education', 'MAED');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `book_id` int(11) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `decision_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `book_id` int(11) NOT NULL,
  `date_return` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(15) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `program_id` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL COMMENT '0 - undergraduate\r\n1 - graduate',
  `created_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_books_category` (`category_id`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_borrow_student` (`student_id`),
  ADD KEY `fk_borrow_faculty` (`faculty_id`),
  ADD KEY `fk_borrow_book` (`book_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faculty_id` (`faculty_id`),
  ADD KEY `fk_faculty_program` (`program_id`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_fines_student` (`student_id`),
  ADD KEY `fk_fines_faculty` (`faculty_id`),
  ADD KEY `fk_fines_book` (`book_id`),
  ADD KEY `fk_fines_borrow` (`borrow_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_returns_student` (`student_id`),
  ADD KEY `fk_returns_faculty` (`faculty_id`),
  ADD KEY `fk_returns_book` (`book_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_program_id` (`program_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_books_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrow`
--
ALTER TABLE `borrow`
  ADD CONSTRAINT `fk_borrow_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_borrow_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_borrow_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `fk_faculty_program` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fk_fines_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fines_borrow` FOREIGN KEY (`borrow_id`) REFERENCES `borrow` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fines_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_fines_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`),
  ADD CONSTRAINT `requests_ibfk_3` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `fk_returns_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_returns_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_returns_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_program_id` FOREIGN KEY (`program_id`) REFERENCES `program` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
