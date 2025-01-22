-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2025 at 12:52 PM
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
-- Database: `drtsgovn_assets_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `role`) VALUES
(6, 'DRTS', '$2y$10$ET59OIP6LGLQSpGyqY2NVOWVNnaHlpiCHec.nP6QQjOj6LO3mCbuq', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `airconditioners`
--

CREATE TABLE `airconditioners` (
  `id` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `model` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `no_of_units` int(11) NOT NULL,
  `capacity` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `airconditioners`
--

INSERT INTO `airconditioners` (`id`, `location`, `model`, `type`, `no_of_units`, `capacity`, `status`) VALUES
(9, 'power house', 'sharp', 'Split', 3, '1.5hp', 'Not Operational');

-- --------------------------------------------------------

--
-- Table structure for table `borehole`
--

CREATE TABLE `borehole` (
  `id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borehole`
--

INSERT INTO `borehole` (`id`, `location`, `model`, `status`) VALUES
(2, 'power house', 'downforce', 'Not Operational'),
(3, 'mabushi', 'downforcwe', 'Operational');

-- --------------------------------------------------------

--
-- Table structure for table `fireextinguishers`
--

CREATE TABLE `fireextinguishers` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `weight` float NOT NULL,
  `amount` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `last_service_date` date NOT NULL,
  `expiration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `generator`
--

CREATE TABLE `generator` (
  `id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `no_of_units` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `solar`
--

CREATE TABLE `solar` (
  `id` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `capacity` varchar(50) NOT NULL,
  `battery_type` varchar(50) NOT NULL,
  `no_of_batteries` int(11) NOT NULL,
  `no_of_panels` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `service_rendered` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `can_delete_vehicle` tinyint(1) DEFAULT 0,
  `can_edit_vehicle` tinyint(1) DEFAULT 0,
  `can_add_vehicle` tinyint(1) DEFAULT 0,
  `can_delete_equipment` tinyint(1) DEFAULT 0,
  `can_edit_equipment` tinyint(1) DEFAULT 0,
  `can_add_equipment` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `can_delete_vehicle`, `can_edit_vehicle`, `can_add_vehicle`, `can_delete_equipment`, `can_edit_equipment`, `can_add_equipment`, `created_at`) VALUES
(6, 'user1', '$2y$10$YF5pMa0QpIFGgqIlQZv1fO.s.E8xfvqz6LR8JsfijuC17.nnHxFNW', 'user', 1, 1, 1, 0, 0, 0, '2025-01-07 11:42:14'),
(7, 'user2', '$2y$10$J1njbuGO0ULQHTMtw3NmIexjoMZjP2Sw53kvDlQTW8gDiTK05u94.', 'user', 0, 1, 0, 0, 0, 0, '2025-01-09 13:22:14'),
(9, 'user3', '$2y$10$.5qb2JcyaoFDuRBDOUI0WORrvzVSW3iE9toUdXmtUXO4gGxo1C78m', 'user', 0, 0, 1, 0, 0, 0, '2025-01-20 04:03:16');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `reg_no` varchar(20) NOT NULL,
  `type` varchar(50) NOT NULL,
  `make` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `inspection_date` date NOT NULL,
  `needs_repairs` tinyint(1) NOT NULL DEFAULT 0,
  `repair_type` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `repair_completion_date` date DEFAULT NULL,
  `images` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `reg_no`, `type`, `make`, `location`, `inspection_date`, `needs_repairs`, `repair_type`, `status`, `repair_completion_date`, `images`, `created_at`) VALUES
(206, 'CT001', 'Sedan', 'Peaugot:406', 'mabushi', '2025-01-02', 0, 'cars', 'No Repairs', NULL, '', '2025-01-20 03:37:23'),
(218, '5555', 'Sedan', 'Toyota:  PRADO', 'mabushi', '2025-01-08', 1, 'gears', 'Needs Repairs', NULL, '', '2025-01-20 06:14:53'),
(222, '4564', 'Sedan', 'Toyota', 'mabushi', '2025-01-01', 1, NULL, 'Needs Repairs', NULL, '', '2025-01-20 06:39:05'),
(223, 'CT01RT', 'Sedan', 'Toyota:  PRADO', 'mabushi', '2025-01-09', 1, 'gears', 'Needs Repairs', NULL, '', '2025-01-20 06:43:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `airconditioners`
--
ALTER TABLE `airconditioners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borehole`
--
ALTER TABLE `borehole`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fireextinguishers`
--
ALTER TABLE `fireextinguishers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `generator`
--
ALTER TABLE `generator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `solar`
--
ALTER TABLE `solar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `airconditioners`
--
ALTER TABLE `airconditioners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `borehole`
--
ALTER TABLE `borehole`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fireextinguishers`
--
ALTER TABLE `fireextinguishers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `generator`
--
ALTER TABLE `generator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `solar`
--
ALTER TABLE `solar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
