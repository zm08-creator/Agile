-- phpMyAdmin SQL Dump for XAMPP MySQL/MariaDB
-- Database: `agile`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- DROP existing tables in correct order (foreign keys first)
DROP TABLE IF EXISTS `Bookings`;
DROP TABLE IF EXISTS `Room`;
DROP TABLE IF EXISTS `Doctor`;
DROP TABLE IF EXISTS `Patient`;
DROP TABLE IF EXISTS `users`;

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` ENUM('patient', 'doctor', 'admin') NOT NULL DEFAULT 'patient',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Room`
CREATE TABLE `Room` (
  `RoomID` int(11) NOT NULL AUTO_INCREMENT,
  `RoomType` varchar(100) NOT NULL,
  `Department` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`RoomID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Patient`
CREATE TABLE `Patient` (
  `PatientID` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `PhoneNum` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`PatientID`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `patient_ibfk_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Doctor`
CREATE TABLE `Doctor` (
  `DoctorID` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `PhoneNum` varchar(20) DEFAULT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`DoctorID`),
  UNIQUE KEY `doctor_user_id` (`user_id`),
  CONSTRAINT `doctor_ibfk_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Bookings`
CREATE TABLE `Bookings` (
  `BookingID` int(11) NOT NULL AUTO_INCREMENT,
  `PatientID` int(11) NOT NULL,
  `DoctorID` int(11) NOT NULL,
  `RoomID` int(11) NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `Date` date NOT NULL,
  `Location` varchar(100) NOT NULL,
  `Discussion` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`BookingID`),
  KEY `PatientID` (`PatientID`),
  KEY `DoctorID` (`DoctorID`),
  KEY `RoomID` (`RoomID`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `Patient` (`PatientID`),
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`DoctorID`) REFERENCES `Doctor` (`DoctorID`),
  CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`RoomID`) REFERENCES `Room` (`RoomID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample users (explicit IDs so doctor/patient links are predictable)
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`) VALUES
(1, 'patient', 'patient@healthmatters.com', '$2y$10$J.iiwVNz.tyWx7k.7IHx1OPcXQRaRDTAuPI2mnatQA3AIGAGfAlzi', 'patient'),
(2, 'professional', 'professional@healthmatters.com', '$2y$10$8DqYDOZXiHhJDiys/QtCueVtFb1yMuznlj9RvjqQ7J9ASMcWbflcC', 'doctor'),
(3, 'admin', 'admin@healthmatters.com', '$2y$10$up5KxonpX/Uq8qhABzgv8eUX20JqzxnDriPSKl2FQAOWp9kyD3XlC', 'admin');

-- Insert sample rooms
INSERT INTO `Room` (`RoomType`, `Department`) VALUES
('Consultation Room', 'General'),
('Treatment Room', 'Surgery'),
('Examination Room', 'General');

-- Insert sample doctor (linked to professional user)
INSERT INTO `Doctor` (`FirstName`, `LastName`, `PhoneNum`, `specialty`, `user_id`) VALUES
('Sophie', 'Williams', '0987654321', 'General Practitioner', 2);

-- AUTO_INCREMENT settings
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `Room` MODIFY `RoomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `Doctor` MODIFY `DoctorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `Patient` MODIFY `PatientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `Bookings` MODIFY `BookingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;