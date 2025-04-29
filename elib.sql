-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2025 at 04:15 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elib`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` enum('admin') NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `full_name`, `email`, `phone_number`, `address`, `status`, `avatar`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Admin', '$2y$10$jDPwr8XD52O6c.URhIJNk.dSR/yK8SGUKKyZawKUk30vWAjvaxqqu', 'PSHS ADMIN', 'admin@gmail.com', '0911185418', 'Pila, Laguna', 'Active', '1738589227_@jacx_o.jpg', '2024-10-03 12:53:45', '2025-02-09 01:06:02', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(13) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publication_year` int(11) DEFAULT NULL,
  `edition` varchar(255) DEFAULT NULL,
  `availability` tinyint(1) DEFAULT 1,
  `book_image_path` varchar(255) DEFAULT NULL,
  `quantity` int(1) DEFAULT NULL,
  `bookshelf_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `isbn`, `publisher`, `publication_year`, `edition`, `availability`, `book_image_path`, `quantity`, `bookshelf_code`) VALUES
(23, 'mahal kita', 'mahal', '54871', '0', 2000, '0', 1, 'assets/images/librarybanner.jpg', 2007, '2051as'),
(26, 'efdasfdd', 'ffetwrf', '534535', '0', 2011, '0', 1, 'assets/images/BASIC Vision (linse.Yedlin).png', 20, 'sa432143'),
(27, 'kaw lang', 'sdaad', '3124323', '0', 1998, '0', 1, 'assets/images/The Internet And Email (Mcleese) (Rourke).png', 210, 'sd123'),
(28, 'sdasdae', 'sdahkbd', '31231243', '0', 2000, '0', 1, 'assets/images/C Vision Activity Book (McCloskey.Stack).png', 2132, 'sd12312'),
(29, 'edassdanmfbvcjah', 'dasnbdv', '143243124', '0', 1900, '0', 1, 'assets/images/Earth Science (Cengage).png', 343, 'zACXqwe4'),
(30, 'mahal', 'mahal kita', '3215646', '0', 2000, 'first ', 1, 'assets/images/Fundamental Of Accountancy, Business And Management 2 (Beticon) (Domingo) (Yabut).png', 21, 'sad4564'),
(31, 'ikaw lang', 'mamamo', '32156487', '0', 1999, 'secondone', 1, 'assets/images/General Mathematics (Oronce) 2016.png', 236, 'dsd4'),
(32, 'kaw lang', 'papako', '3218674', '0', 1899, 'sdaw', 1, 'assets/images/Computer Programming Volume 1.png', 216, 'sd35564'),
(35, 'sdasd', 'sdasd', '214444', 'sadsad', 2005, 'sdasds', 1, 'assets/images/pronob.jpg', 1212, 'sad54'),
(36, 'dasdasd', 'sdasd', '21567879756', 'sdas5', 2001, 'dawee', 1, 'assets/images/java.jpg', 213, 'sda45646'),
(37, 'dasdasd', 'dsada', '1646564', 'dsada', 2004, 'asda', 1, 'assets/images/nv2.jpg', 213, 'sdas5'),
(38, 'sdasdasd', 'sdas', '164654', 'dsad112', 1888, 'dasd45a', 1, 'assets/images/nv1.jpg', 3211, 'dsad4654');

-- --------------------------------------------------------

--
-- Table structure for table `book_requests`
--

CREATE TABLE `book_requests` (
  `request_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `request_date` date NOT NULL,
  `due_date` varchar(255) DEFAULT NULL,
  `status` enum('Approved','Pending','Rejected','Returned') NOT NULL,
  `expected_pickup_date` varchar(255) DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `is_returned` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_requests`
--

INSERT INTO `book_requests` (`request_id`, `student_id`, `book_id`, `request_date`, `due_date`, `status`, `expected_pickup_date`, `return_date`, `is_returned`) VALUES
(33, 31, 35, '2024-11-13', '10/01/2025', 'Approved', '10/12/2024', NULL, 0),
(34, 31, 32, '2024-11-13', NULL, 'Rejected', '11/12/2024', NULL, 0),
(37, 31, 38, '2024-11-20', NULL, 'Pending', NULL, NULL, 0),
(40, 25, 23, '2025-02-09', '12/02/2025', 'Approved', '10/02/2025', '2025-02-09', 1),
(41, 25, 26, '2025-02-09', NULL, 'Pending', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text NOT NULL,
  `visible` int(11) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `student_id`, `book_id`, `rating`, `review`, `visible`, `created`, `modified`) VALUES
(1, 25, 23, 4, 'NA', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(2, 25, 23, 3, 'Nice ending.', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(3, 25, 23, 5, 'Very good plot!\r\n', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(4, 25, 23, 5, 'Very good plot!\r\n', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(5, 25, 23, 4, 'NA', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(6, 25, 23, 3, 'Nice ending.', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(7, 25, 23, 5, 'Very good plot!\r\n', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(8, 25, 23, 5, 'Very good plot!\r\n', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(9, 25, 23, 4, 'NA', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(10, 25, 23, 3, 'Nice ending.', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(11, 25, 23, 5, 'Very good plot!\r\n', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(12, 25, 23, 5, 'Very good plot!\r\n', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(13, 25, 23, 4, 'NA', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(14, 25, 23, 3, 'Nice ending.', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(15, 25, 23, 5, 'Very good plot!\r\n', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42'),
(16, 25, 23, 5, 'Very good plot!\r\n', 1, '2025-02-09 13:42:42', '2025-02-09 13:42:42');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `grade_level` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `birthday` varchar(255) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `picture_path` varchar(255) DEFAULT NULL,
  `lrn` varchar(12) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `role` enum('student') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `grade_level`, `email`, `birthday`, `section`, `username`, `password`, `picture_path`, `lrn`, `status`, `role`) VALUES
(25, 'Baby Boy Sins', 9, 'babe@gmail.com', '17/10/2008', 'PILOT', 'BABY', '$2y$10$jDPwr8XD52O6c.URhIJNk.dSR/yK8SGUKKyZawKUk30vWAjvaxqqu', NULL, '3346136545', 1, 'student'),
(31, 'JAYVEE COROLLO', 11, 'jayvee@gmail.com', '10/12/2024', 'BERG', 'JAYVEE', '$2y$10$pzTh7iyNTMFl26nHvUo7fO1qRdhj69nbTCiruPpEQsDE1Gk3Tjf4G', NULL, NULL, 1, 'student'),
(40, 'Software Developer', 12, 'testing@gmail.com', '09/02/2025', '1A', 'dev02', '$2y$10$e9hXINX6P6miFgoS9yBb.uvNa7yv7rQyqGrlUzA1J9r4rC3vBgR62', NULL, '12345123', 1, 'student');

-- --------------------------------------------------------

--
-- Table structure for table `thesis`
--

CREATE TABLE `thesis` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `advisor` varchar(255) NOT NULL,
  `strand` varchar(255) NOT NULL,
  `completion_year` int(4) NOT NULL,
  `bookshelf_code` varchar(50) NOT NULL,
  `abstract_image` varchar(255) NOT NULL,
  `availability` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thesis`
--

INSERT INTO `thesis` (`id`, `title`, `author`, `advisor`, `strand`, `completion_year`, `bookshelf_code`, `abstract_image`, `availability`) VALUES
(1, 'elib', 'jayvee', 'flores', 'BSIT', 2024, 'BSIT21', 'omur.jpg', 1),
(2, 'deped', 'baba', 'mee', 'BSCS', 2024, 'BSCS24', 'General Mathematics (Diwa).png', 0),
(3, 'DEPED', 'MEE', 'MEE', 'BSCS', 2024, 'BSCS24', 'Basic Calculus (Pelias) 2016.png', 1),
(5, 'baby', 'ikaw', 'self', 'CS', 2014, 'CS14', 'tahmid.jpg', 1),
(16, 'little mary', 'SI BABY', 'SYA PO', 'GAS', 2021, 'GAS21', 'GAS.jpg', 1),
(17, 'Maging Sino Ka Man', 'MEETOO', 'MEALSO', 'ICT', 2020, 'ICT20', 'abstract-thesis-1-638.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `unique_isbn` (`isbn`);

--
-- Indexes for table `book_requests`
--
ALTER TABLE `book_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `lrn` (`lrn`);

--
-- Indexes for table `thesis`
--
ALTER TABLE `thesis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `book_requests`
--
ALTER TABLE `book_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `thesis`
--
ALTER TABLE `thesis`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_requests`
--
ALTER TABLE `book_requests`
  ADD CONSTRAINT `book_requests_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_requests_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
