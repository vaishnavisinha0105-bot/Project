-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 01:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doctor_directory`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(2, 'Vaishnavi Sinha', 'vaishnavi@gmail.com', '$2y$10$4uwbmuUxgNnLJeBbdug7Vuw4LXk3G0kOlNaJLaaJNCPi28PKWCzwO', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Pending','Confirmed','Completed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cancel_reason` text DEFAULT NULL,
  `cancelled_by` enum('user','doctor') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `status`, `created_at`, `cancel_reason`, `cancelled_by`) VALUES
(13, 44, 1, '2025-09-03', '11:00:00', 'Cancelled', '2025-09-02 15:30:41', 'Feeling Better', 'user'),
(14, 45, 2, '2025-09-04', '10:00:00', 'Cancelled', '2025-09-03 14:29:56', NULL, 'doctor'),
(15, 45, 2, '2025-09-05', '10:00:00', 'Completed', '2025-09-03 14:39:12', NULL, NULL),
(16, 46, 1, '2025-09-05', '11:00:00', 'Confirmed', '2025-09-04 14:07:34', NULL, NULL),
(17, 44, 1, '2025-09-07', '09:00:00', 'Cancelled', '2025-09-06 08:38:06', 'Not able to Come', 'user'),
(18, 46, 17, '2025-09-09', '10:00:00', 'Completed', '2025-09-06 09:54:02', NULL, NULL),
(19, 50, 9, '2025-09-15', '11:00:00', 'Cancelled', '2025-09-13 13:20:23', 'Feeling Better', 'user'),
(27, 52, 9, '2025-10-15', '12:00:00', 'Cancelled', '2025-10-05 05:19:06', 'feel good now', 'user'),
(28, 53, 23, '2025-10-10', '10:00:00', 'Cancelled', '2025-10-09 14:45:20', 'feeling better now', 'user'),
(29, 53, 10, '2025-10-10', '11:00:00', 'Cancelled', '2025-10-09 14:50:06', 'Cancelled by Doctor', 'doctor'),
(30, 53, 15, '2025-10-10', '12:00:00', 'Cancelled', '2025-10-09 14:57:03', 'Cancelled by doctor', 'doctor');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(5) NOT NULL,
  `name` varchar(25) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`) VALUES
(10, 'General Physician', 'uploads/image/general physician.webp'),
(11, 'Dermatology', 'uploads/image/dermatology.webp'),
(12, 'Obstetrics & Gynaecology', 'uploads/image/obstetrics & gynaecology.webp'),
(13, 'Orthopaedics', 'uploads/image/orthopaedics.webp'),
(14, 'Neurology', 'uploads/image/neurology.webp'),
(15, 'Cardiology', 'uploads/image/cardiology.webp');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `first_name`, `last_name`, `mobile`, `email`, `message`, `created_at`) VALUES
(3, 'Aarti', 'Khetiya', '8765445678', 'aarti@gmail.com', 'Not able to login ', '2025-09-06 08:00:31');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `speciality` varchar(100) NOT NULL,
  `experience` varchar(50) DEFAULT NULL,
  `degree` varchar(100) NOT NULL,
  `hospital` varchar(100) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `email`, `name`, `speciality`, `experience`, `degree`, `hospital`, `image`, `category_id`) VALUES
(1, 'vivan@gmail.com', 'Dr. Vivan Singh', 'General Physician/ Internal Medicine', '15 years', 'MBBS, MD (General medicine)', 'Avadh Multispeciality Hospital', 'uploads/image/img4.webp', 10),
(2, 'shakti@gmail.com', 'Dr. Shakti Khanna', 'General Physician/ Internal Medicine ', '8 years ', 'MBBS, MD (General medicine)', 'Jeevandhara Hospital ', 'uploads/image/img2.webp', 10),
(3, 'aditya@gmail.com', 'Dr. Aditya Mehta', 'General Physician/ Internal Medicine ', '5 years', 'MBBS, MD (Internal Medicine)', 'Shraddha Multispeciality Hospital', 'uploads/image/1757147316_img6.webp', 10),
(4, 'hitesh@gmail.com', 'Dr. Hitesh Khetiya', 'General Practitioner', '10 years', 'MBBS', 'Avadh Multispeciality Hospital', 'uploads/image/img3.webp', 10),
(5, 'diya@gmail.com', 'Dr. Diya Joshi', 'General Practitioner', '8 years', 'MBBS, MD', 'Jeevandhara Hospital ', 'uploads/image/img5.webp', 10),
(6, 'rupal@gmail.com', 'Dr. Rupal Vaghela', 'General Practitioner', '5 years', 'MBBS', 'Kalyan Multispeciality Hospital', 'uploads/image/img7.webp', 10),
(7, 'raghavon@gmail.com', 'Dr. Raghavon U N', 'Dermatology', '5 years', 'MBBS,MD (Dermatology, Venereology & Leprosy)', 'Relief Skin and Cosmetic Clinic', 'uploads/image/img8.webp', 11),
(8, 'bhagyeshree@gmail.com', 'Dr. Bhagyeshree', 'Dermatology', '5 years', 'MBBS,MD (Dermatology, Venereology & Leprosy)', 'Sakhiya Skin Clinic', 'uploads/image/img16.webp', 11),
(9, 'hinal@gmail.com', 'Dr. Hinal Prajapati', 'Dermatology', '3 years', 'MBBS,MD (Dermatology, Venereology & Leprosy)', 'Nikhar Skin Care', 'uploads/image/img13.webp', 11),
(10, 'harsh@gmail.com', 'Dr. Harsh Khetiya', 'Dermatology', '3 years', 'MBBS, MD, DNB', 'Skin Care Clinic', 'uploads/image/img10.webp', 11),
(11, 'hiren@gmail.com', 'Dr. Hiren Dholariya', 'Dermatology', '3 years', 'MBBS, MD, DNB', 'Sakhiya Skin Clinic', 'uploads/image/img11.webp', 11),
(12, 'ankita@gmail.com', 'Dr. Ankita Dwivedi', 'Dermatology', '3 years', 'MBBS, MD, DNB', 'Relief Skin and Cosmetic Clinic', 'uploads/image/img15.webp', 11),
(14, 'gayatri@gmail.com', 'Dr. Gayatri Thaker', 'Obstetrician & Gynecologist', '10 years', 'MBBS, MS', 'Siddhi Vinayak Hospital', 'uploads/image/img14.webp', 12),
(15, 'purvi@gmail.com', 'Dr. Purvi Apoorva', 'Obstetrician & Gynecologist', '10 years', 'MBBS, MS', 'Dodia Women\'s Hospital', 'uploads/image/img12.webp', 12),
(16, 'jayeshkumar@gmail.com', 'Dr. Jayeshkumar Solanki', 'Gynecologist', '8 years', 'MBBS, DGO', 'Shreeji Women\'s Hospital', 'uploads/image/img9.webp', 12),
(17, 'vidya@gmail.com', ' Dr. Vidya Ambatkar', 'Gynecologist, Infertility Specialist', '10 years', 'MBBS, DGO', 'Rathi Hospital', 'uploads/image/img17.webp', 12),
(18, 'nita@gmail.com', 'Dr. Nita Mandhai Sata', 'Obstetrics & Gynaecology', '5 years', 'MBBS, MD ', 'Siddhi Vinayak Hospital', 'uploads/image/img20.webp', 12),
(19, 'rkgoyal@gmail.com', 'Dr R.K.Goyal', 'Obstetrician & Gynecologist', '5 years', 'MBBS, DGO', 'Shreeji Womens Hospital', 'uploads/image/img18.webp', 12),
(20, 'ankit@gmail.com', 'Dr. Ankit V Dodia ', 'Orthopedician', '15 years', 'MBBS , MS (Orthopedics)', 'Maheshwari Orthopedic Hospital', 'uploads/image/img19.webp', 13),
(21, 'mahesh@gmail.com', 'Dr. Mahesh Diwan', 'Orthopedician', '15 years', 'MBBS, MS(ORTHO)', 'Avadh Multispecialty Hospital', 'uploads/image/img21.webp', 13),
(22, 'vaishali@gmail.com', ' Dr. Vaishali M Ambatkar', 'Orthopedic surgeon', '10 years', 'MBBS, Diploma in Orthopaedics', 'Divya Orthopedic Hospital', 'uploads/image/img28.webp', 13),
(23, 'harshika@gmail.com', 'Dr. Harshika Sanghani', 'Spine Surgeon (Ortho)', '10 years', 'MBBS, MS - Orthopaedics', 'Halar Orthopaedic Hospital', 'uploads/image/img25.webp', 13),
(24, 'vipul@gmail.com', 'Dr. Vipul M Shah', 'Spine Surgeon (Ortho)', '10 years', 'MBBS, MS - Orthopaedics', 'Sharda Hospital', 'uploads/image/img29.webp', 13),
(25, 'sonali@gmail.com', 'Dr. Sonali Khetiya', 'Orthopedician', '10 years', 'MBBS, MS - Orthopaedics', 'Orthopedics Hospital', 'uploads/image/img23.webp', 13),
(26, 'kanishka@gmail.com', 'Dr. Kanishka Singh', 'Neurologist', '15 years', 'MBBS', 'Avadh Multispeciality Hospital', 'uploads/image/img26.webp', 14),
(27, 'vihan@gmail.com', 'Dr. Vihan Mehta', 'Neurologist', '17 years', 'MBBS , DNB', 'Avadh Multispeciality Hospital', 'uploads/image/img30.webp', 14),
(28, 'mannat@gmail.com', 'Dr. Mannat Joshi', 'Neurologist', '8 years', 'MBBS , DNB', 'Ruparelia Neuro Hospital', 'uploads/image/img23.webp', 14),
(29, 'rohit@gmail.com', 'Dr. Rohit Khanna', 'Neurologist', '8 years', 'MBBS, MD', 'Pravish Neurodent', 'uploads/image/img22.webp', 14),
(30, 'krupa@gmail.com', 'Dr. Krupa Verma', 'Neurologist', '10 years', 'MBBS, MD', 'Udani Multispeciality Hospital', 'uploads/image/img27.webp', 14),
(31, 'kirti@gmail.com', 'Dr. Kirti Joshi', 'Neurologist', '5 years', 'MBBS, MD', 'Kalyan Hospital', 'uploads/image/img32.webp', 14),
(32, 'vaibhav@gmail.com', 'Dr.Vaibhav Sharma', 'Cardiologist ', '15 years', 'MBBS, MD, DM', 'Shraddha Hospital', 'uploads/image/img37.webp', 15),
(33, 'vineeta@gmail.com', 'Dr.Vineeta Malik', 'Cardiologist ', '8 years', 'MBBS, MD, DM', 'Jeevandhara Hospital ', 'uploads/image/img34.webp', 15),
(34, 'vinayak@gmail.com', 'Dr.Vinayak Agarwal', 'Cardiologist ', '10 years', 'MBBS, MD, DM', 'Avadh Multispeciality Hospital ', 'uploads/image/img31.webp', 15),
(35, 'vanshika@gmail.com', 'Dr. Vanshika Sharma', 'Cardiologist ', '5 years', 'MBBS, MD, DM', 'Baroda Heart Institute', 'uploads/image/img33.webp', 15),
(36, 'anil@gmail.com', 'Dr.Anil Choudhary', 'Cardiologist ', '12 years', 'MBBS, MD, DM', 'Krishna Hospital ', 'uploads/image/img36.webp', 15),
(37, 'anshuman@gmail.com', 'Dr.Anshuman Gupta', 'Cardiologist ', '8 years', 'MBBS, MD, DM', 'Udani Hospital ', 'uploads/image/img35.webp', 15);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `age` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `past_history` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `name`, `gender`, `age`, `email`, `phone`, `address`, `past_history`, `created_at`) VALUES
(44, 21, 'Aarti', 'Female', 28, 'aarti@gmail.com', '8765745678', 'Jamnagar', 'Eye Infection', '2025-09-02 15:30:20'),
(45, 24, 'Rahi', 'Female', 25, 'rahi@gmail.com', '2345665432', 'Jamnagar', 'Nothing', '2025-09-03 14:29:43'),
(46, 26, 'Muskan', 'Female', 25, 'musku@gmail.com', '9876543219', 'Jamnagar', 'Nothing', '2025-09-04 14:07:10'),
(47, 25, 'Tina', 'Female', 30, 'tina@gmail.com', '9878798778', 'Jamnagar', 'Nothing', '2025-09-04 14:46:08'),
(48, 31, 'Vaishu', 'Female', 25, 'vaishu@gmail.com', '9876543236', 'Jamnagar', 'Nothing', '2025-09-10 14:41:50'),
(49, 27, 'Vaishu', 'Female', 25, 'munnu@gmail.com', '9876543237', 'Jamnagar', 'Nothing', '2025-09-13 13:12:17'),
(50, 32, 'Guunu', 'Female', 40, 'gunnu@gmail.com', '9876967876', 'Jamnagar', 'No past history', '2025-09-13 13:17:39'),
(51, 33, 'Seema', 'Female', 25, 'seema@gmail.com', '9876543265', 'Jamnagar', '', '2025-09-15 14:49:19'),
(52, 36, 'Pingala Bharti', 'Female', 22, 'pingala@gmail.com', '9876978654', 'Jamnagar', '', '2025-10-05 05:18:26'),
(53, 38, 'Kanishka Sinha', 'Female', 22, 'kanishkaa@gmail.com', '9876578654', 'Jamnagar', '', '2025-10-09 14:44:48'),
(56, 71, 'Akanksha Sinha', 'Female', 22, 'durga@gmail.com', '9876876545', 'Jamnagar', '', '2025-11-15 12:40:55');

--
-- Triggers `patients`
--
DELIMITER $$
CREATE TRIGGER `set_patient_userid` BEFORE INSERT ON `patients` FOR EACH ROW BEGIN
    DECLARE uid INT;
    -- find the user id based on email
    SELECT id INTO uid FROM user WHERE email = NEW.email LIMIT 1;
    -- set it to patient
    SET NEW.user_id = uid;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(20, 'Vaishnavi Sinha', 'vs@gmail.com', '1234', 'admin'),
(21, 'Aarti', 'aarti@gmail.com', '$2y$10$cCwyer.4LRR6c1bJK7sf4OqxxIAAb2REwjQGGuF.eSz/5iOA0DpgK', 'patient'),
(22, 'Dr. Vivan Singh', 'vivan@gmail.com', '$2y$10$G84gdslsybvgtJfqG8R6.eknoQwOETpUuGzzajk7bLB7DEwDvQC5u', 'doctor'),
(23, 'Dr. Shakti Khanna', 'shakti@gmail.com', '$2y$10$CDlggo23W6X.bUyvyHk6wuEjtEDTW.8FSeglb1kf.e7Blgu3AO7dq', 'doctor'),
(24, 'Rahi', 'rahi@gmail.com', '$2y$10$LP.nTFLXU3fGSTYOB.CO..XymMCa9OG.Alxmo40lFE2e2VkYjdeK6', 'patient'),
(25, 'Tina', 'tina@gmail.com', '$2y$10$1ORq/tJOPTMM.C4ZCo2WqOf82w/Ccb.sp0VXXY.koXU64hh1FZ.h2', 'patient'),
(26, 'Muskan', 'musku@gmail.com', '$2y$10$2UvAi7YjtAsDSewXnKZN3OyVxupyPK5657szx.PbTaqQ6l2BId1Pe', 'patient'),
(27, 'Munnu', 'munnu@gmail.com', '$2y$10$cBpOtu0pVzhpU.HapvMaDOW.ICKyOxnEnKnoqKlTZqENWMFMtmiNS', 'patient'),
(28, 'Aditya', 'aditya@gmail.com', '$2y$10$CzWQWW3O5UOroMLPVcqz1.W5MGI8nGPZO5ArDgfBgqSIqsHGa0d/i', 'doctor'),
(29, 'Hinal', 'hinal@gmail.com', '$2y$10$/BCHE5a4NYhyeKj97YE79O9k9otYA7ZHpvX6bCzLfFLenJ7W9yuV6', 'doctor'),
(30, 'Vidya', 'vidya@gmail.com', '$2y$10$re/5AEzqGA0gVm3wSOKmn.s7bOd7uK4vr7QvDn1YGnMFayMgxlqEO', 'doctor'),
(31, 'Vaishu', 'vaishu@gmail.com', '$2y$10$OyUQQ2SmGdjOUfgv3FFiieCCJZ5ISGklD/C6YrW2uESEyoWl3g1iq', 'patient'),
(32, 'Gunnu', 'gunnu@gmail.com', '$2y$10$Hm8YeVBHYqRyIOknOa9QzOqVBuOUBg6eYvvXiWJtsjgboCAyHy152', 'patient'),
(33, 'Seema', 'seema@gmail.com', '$2y$10$G.sGcM00q6fL/g7pOTWe1.uc2mTIbvFYDBl41mpvhLl7MtzVKEes.', 'patient'),
(34, 'Archana', 'archana@gmail.com', '$2y$10$vZsm0wVS1NLdmNBdFxxmUeTm.3CcQlEGsW1r2e7bKoCy6LhGuaLB.', 'patient'),
(35, 'Mansi', 'mansi@gmail.com', '$2y$10$UrFTY7tI3P7lRLU/skJzxObPLvvybX3/B0nUkz0VuX9NNlZ2.PX.O', 'patient'),
(36, 'Pingala Bharti', 'pingala@gmail.com', '$2y$10$WdDT5yeDIzHavbbmeaeIK.W0roNNhViK2gNZkQZxEahG1fCrtvaRS', 'patient'),
(37, 'Prashil Khandar', 'prashil@gmail.com', '$2y$10$3MjChKEvUas02XFJObtdBeAxUIPFMCbqUS44uZYx.QLH0t5w9SCTq', 'patient'),
(38, 'Kanishka Sinha', 'kanishkaa@gmail.com', '$2y$10$ZbAftDY.dQ4ESKadEH0jgeHTXfXeyRoiUio9sc0Xxcb5RoeLzZzM.', 'patient'),
(39, 'Hitesh Khetiya', 'hitesh@gmail.com', '$2y$10$3xJNMaarzLWEmniouS2mAuGmSRkv7nd7lFr2CnjcfArb0nkfT0m3e', 'doctor'),
(40, 'Diya Joshi', 'diya@gmail.com', '$2y$10$sbt8M5DFvbgheOn17hSD/.UsDQzVfNkW3pN50PDJVUybyia1X67Em', 'doctor'),
(41, 'Rupal Vaghela', 'rupal@gmail.com', '$2y$10$285WLqFDCsTC7wB7hLICneQfkDM2GXkcSvcxn4mdCdUhytxyvBQp2', 'doctor'),
(42, 'Raghavon U N', 'raghavon@gmail.com', '$2y$10$cr9T73zq4BrUSOFX8aK84ulpeRhqtvQ.zR9gv7Pki.ikmRhM/EzyW', 'doctor'),
(43, 'Bhagyeshree', 'bhagyeshree@gmail.com', '$2y$10$u7qbU94OkOZue5sChnnqJeiA0s7fgYTIcSjfQIE.xLYC749FHISxu', 'doctor'),
(44, 'Harsh Khetiya', 'harsh@gmail.com', '$2y$10$WsUdkZh2IzWlzUXrzXloU.XPxXvKLOEhjNxJgHN/24c1QQRKwbANC', 'doctor'),
(45, 'Hiren Dholariya', 'hiren@gmail.com', '$2y$10$px5WMH8b9iU.C1XDjwwGUOVqzgo9GhgRd.vbdjM8IhC9GKlzf3Lqu', 'doctor'),
(46, 'Ankita Dwivedi', 'ankita@gmail.com', '$2y$10$4F2iyLAlU.W5Wu/qyRivaeOQuETjEod/BQRZRtogbTHGXyD4ADbYq', 'doctor'),
(47, 'Gayatri Thaker', 'gayatri@gmail.com', '$2y$10$7fAs3DxHEdupG6VZJDqJFORW90LLpMbxYW9.5aY9LfULj8dTORCZ.', 'doctor'),
(48, 'Purvi Apoorva', 'purvi@gmail.com', '$2y$10$2FIdh6BD8cw/djbQPEIKoOSvlTo.b0No.M9ZuMAe9sHQjiL0vpvV2', 'doctor'),
(49, 'Jayeshkumar Solanki', 'jayeshkumar@gmail.com', '$2y$10$3iwpQt30AYcO9J4uHu1fOeiacfxc4u/ubIXHlgE9tUx/xHz89BAEq', 'doctor'),
(50, 'Nita Mandhai Sata', 'nita@gmail.com', '$2y$10$U9cm4gVT4a/16t8MUR/g9uO7Vvzs8x8RSpfowoBTXlYaH/YADzpgO', 'doctor'),
(51, 'R K Goyal', 'rkgoyal@gmail.com', '$2y$10$i56I8XHdfvSXxU/UQaxXUuTotcjsbjGLw4/W.Rb8X6ejrOVZlw64W', 'doctor'),
(52, 'Ankit V Dodia', 'ankit@gmail.com', '$2y$10$IwI4f.T0MVCGj7p3rgzMOOvhc8Fs2F0IdPJW6J93CnxFTZ2EFHeoa', 'doctor'),
(53, 'Mahesh Diwan', 'mahesh@gmail.com', '$2y$10$oK83.gFFIcYLi/8reNj1COqNvFx3RlKa1rdrvYb8nYxmKC8bclWpu', 'doctor'),
(54, 'Vaishali M Ambatkar', 'vaishali@gmail.com', '$2y$10$Q4AujGGG6JeRi73iywvo0uOEoTjto2h/i5GliQTRdCFFNewwyzvRe', 'doctor'),
(55, 'Harshika Sanghani', 'harshika@gmail.com', '$2y$10$gdt7rMGHPUdznP.FdT7J6ONjf8h3/Y0KWleoC66sMW9Vek6Yjf/Fa', 'doctor'),
(56, 'Vipul M Shah', 'vipul@gmail.com', '$2y$10$x6ypBQCKxLySRU0jGLo..Ou7hbKVQehfDdMcLiNSLZ.pvboFnGvXm', 'doctor'),
(57, 'Sonali Khetiya', 'sonali@gmail.com', '$2y$10$DMyX9JkioyU1aEGtNj4vwet0VAzDEmMB0dXWPyqCtX7fViAHxc6X.', 'doctor'),
(58, 'Kanishka Singh', 'kanishka@gmail.com', '$2y$10$r6clM1bludVw067b7uwyrOxic9iRfK..xkr2TtVqgpW.p0E9MefV2', 'doctor'),
(59, 'Vihan Mehta', 'vihan@gmail.com', '$2y$10$BmCCRobpPoXBpZlA4jJuHuV4z59A9pBDQFouP9RXBQXyPNGZvyuU2', 'doctor'),
(60, 'Mannat Joshi', 'mannat@gmail.com', '$2y$10$.C2NgOSGSJglzv/HKJ2eW.5NPLn..IZHoF5PDJ7UmGeONN.crmPXe', 'doctor'),
(61, 'Rohit Khanna', 'rohit@gmail.com', '$2y$10$FQ.gcRXpny1V6Rgx77CteOVxXZeoHBnr.8ClRCRD5k.VvUDpsaqHK', 'doctor'),
(62, 'Krupa Verma', 'krupa@gmail.com', '$2y$10$5Nz9knvqGhgR9nl0P0nnC.WjYGTPMZ9PjAyjiW.GNR1h9K5.F/t2i', 'doctor'),
(63, 'Kirti Joshi', 'kirti@gmail.com', '$2y$10$uqucnbIR8Ncf2o4k72tr3O3yYDebITvTnOhaDK9b5AX.Fj55tkfwS', 'doctor'),
(64, 'Vaibhav Sharma', 'vaibhav@gmail.com', '$2y$10$8zu1Sg66O2ffEsH60q67Y.WFyF56Lrzyyvp65tHTgBeg/0BbOxzpe', 'doctor'),
(65, 'Vineeta Malik', 'vineeta@gmail.com', '$2y$10$5miaBCkFq0fPD9ME8ZRsFuShWE9zmMKIj39lE3efYwF/g//S29gLm', 'doctor'),
(66, 'Vinayak Agarwal', 'vinayak@gmail.com', '$2y$10$HA918o0SoQaR8dMMQeN5heD8hesmKitJ0VhcVzSH7/MpJI5zVVW6C', 'doctor'),
(67, 'Vanshika Sharma', 'vanshika@gmail.com', '$2y$10$ajcDtVW7T37Wr8f8k9h1a.9EWt7JjqAYVlkD.lzxctSdzxY2p9Ole', 'doctor'),
(68, 'Anil Choudhary', 'anil@gmail.com', '$2y$10$ToQNBFvK1LzASP2YMfuhfO3fDF8lrARt0Yg63f2ucwoI1sEZizYti', 'doctor'),
(69, 'Anshuman Gupta', 'anshuman@gmail.com', '$2y$10$fUffbCD6b8SQ55meSbf/SexUPgzne9bKcHrUj7xcHpTpk2TJrmDl6', 'doctor'),
(71, 'Durga', 'durga@gmail.com', '$2y$10$8N7CWbMG4.kC19BSk9mwhuKjkocBpH8swRS6tPftjprCEoIfuzYR2', 'patient');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_patient_user` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `fk_patient_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
