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

-- Insert users
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`) VALUES
(1,  'JBennet',   'jbennet@healthmatters.com',   '$2b$12$8CEGARFSqrXewHc5gSJsyeTfCOMkUMiYV6Nul0VuWDtgotDOEww5a', 'patient'),
(2,  'CAnderson', 'canderson@healthmatters.com', '$2b$12$dgToLQFnefoDMlYW588fZ.4hdAT0GWGgrVOtryyw5RkHiU/Mbyph2', 'patient'),
(3,  'SIqbal',    'siqbal@healthmatters.com',    '$2b$12$hOotVg335TRHRwqE4/iTW.r7bKtWzGdUsyzBTrgUIVKOhAu3DkeBi', 'patient'),
(4,  'AKhan',     'akhan@healthmatters.com',     '$2b$12$nd6epsjWeDRYhgce358OL.7f2Z/89equl/TV7jjOyHcqmbdxsgDdO', 'doctor'),
(5,  'GPalmer',   'gpalmer@healthmatters.com',   '$2b$12$qEJiJ6UHqt2gixEXh81woO.PqSTQbYuQere5sLc3AEp0PynBuzS7K', 'doctor'),
(6,  'RWhite',    'rwhite@healthmatters.com',    '$2b$12$cjIHu7qNBfHH9r4HYcFWcekm5pMAZLn4LHDJodWbPr4HclO.G2Al2', 'doctor'),
(7,  'ECarter',   'ecarter@healthmatters.com',   '$2b$12$BMLi7hlzJgcdAYOt7X4mY.3Vi9TBDvASDtcwyGnIbz.e5ItknkDHa', 'admin'),
(8,  'SDesai',    'sdesai@healthmatters.com',    '$2b$12$tTY9FGcH47tWacMAnmgQceexdt3MajtEzx41.faeYstpCnIy4tlW6', 'admin'),
(9,  'FMalik',    'fmalik@healthmatters.com',    '$2b$12$G4G00ciT5UMvB7Aew/4oWOfpr9hxgSI1wCikkKjcfJjN4BZIZXNZG', 'admin');

-- Insert doctors (linked to their user accounts)
INSERT INTO `Doctor` (`FirstName`, `LastName`, `PhoneNum`, `specialty`, `user_id`) VALUES
('Adil',    'Khan',   '01772100001', 'General Practitioner', 4),
('Grace',   'Palmer', '01772100002', 'General Practitioner', 5),
('Rebecca', 'White',  '01772100003', 'General Practitioner', 6);

-- Insert rooms
INSERT INTO `Room` (`RoomType`, `Department`) VALUES
('Consultation Room 1', 'General Practice'),
('Consultation Room 2', 'General Practice'),
('Consultation Room 3', 'General Practice');

-- AUTO_INCREMENT settings
ALTER TABLE `users`    MODIFY `id`        int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
ALTER TABLE `Doctor`   MODIFY `DoctorID`  int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `Patient`  MODIFY `PatientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `Bookings` MODIFY `BookingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `Room`     MODIFY `RoomID`    int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;