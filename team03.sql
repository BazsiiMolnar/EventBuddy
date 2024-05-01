-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 01, 2024 at 05:41 PM
-- Server version: 10.6.16-MariaDB-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `team03`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `date` varchar(111) NOT NULL,
  `topic` varchar(11) NOT NULL,
  `statusz` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `sender_id`, `receiver_id`, `date`, `topic`, `statusz`) VALUES
(1, 1, 2, '2023-10-23', 'szopás', 'accepted'),
(22, 98, 96, '2024-03-30', 'asd', 'rejected'),
(23, 98, 96, '2024-03-30', 'asd', 'rejected'),
(24, 98, 96, '2024-03-29', 'fasz', 'rejected'),
(27, 109, 108, '2024-04-10', 'Csak ugy', 'pending'),
(28, 108, 109, '', 'Csak úgy', 'pending'),
(30, 97, 103, '2024-04-10', 'Kocsma', 'pending'),
(31, 98, 96, '2024-04-19', 'asdsadsa', 'accepted'),
(32, 98, 96, '2024-04-19', 'okl', 'accepted'),
(33, 98, 96, '2024-06-22', 'Foci', 'accepted'),
(34, 98, 96, '2024-06-22', 'Foci', 'accepted'),
(35, 98, 96, '', '9', 'accepted'),
(36, 112, 98, '2024-04-24', 'Kaja', 'accepted'),
(37, 98, 96, '2024-05-25', 'foci', 'accepted'),
(39, 96, 112, '2024-04-25', 'Edzés', 'accepted'),
(40, 98, 112, '2024-04-28', 'Mozi', 'accepted'),
(41, 112, 96, '2024-04-25', 'Ebéd', 'accepted'),
(42, 122, 112, '2024-04-27', 'kaja', 'accepted'),
(43, 98, 96, '2024-04-25', 'foci', 'accepted'),
(44, 98, 96, '2024-04-25', 'szoj', 'accepted'),
(45, 98, 96, '2024-05-25', 'dik', 'accepted'),
(46, 98, 96, '2024-05-16', 'fasz', 'pending'),
(47, 98, 96, '2024-05-16', 'fasz', 'pending'),
(48, 98, 96, '2024-05-26', 'www', 'pending'),
(49, 98, 96, '2024-05-26', 'www', 'pending'),
(50, 98, 96, '2024-06-15', 'weweere', 'pending'),
(51, 96, 98, '2024-05-18', 'asd', 'accepted'),
(52, 96, 98, '2024-05-31', 'asddd', 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `statusz` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friend_requests`
--

INSERT INTO `friend_requests` (`id`, `sender_id`, `receiver_id`, `statusz`) VALUES
(130, 1, 2, 'accepted'),
(131, 98, 96, 'accepted'),
(139, 103, 99, 'pending'),
(140, 103, 97, 'accepted'),
(141, 104, 99, 'pending'),
(142, 96, 103, 'pending'),
(146, 105, 96, 'accepted'),
(147, 96, 107, 'pending'),
(148, 109, 108, 'accepted'),
(149, 108, 96, 'rejected'),
(150, 109, 96, 'accepted'),
(151, 98, 112, 'accepted'),
(152, 115, 112, 'accepted'),
(153, 114, 112, 'accepted'),
(154, 116, 112, 'accepted'),
(155, 117, 112, 'accepted'),
(156, 118, 112, 'accepted'),
(157, 96, 112, 'accepted'),
(158, 119, 112, 'accepted'),
(160, 112, 99, 'pending'),
(161, 120, 96, 'accepted'),
(162, 121, 112, 'accepted'),
(163, 112, 122, 'accepted'),
(164, 98, 106, 'pending'),
(165, 123, 98, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `prof_img` varchar(100) NOT NULL,
  `cover_img` varchar(100) NOT NULL,
  `mode` varchar(11) NOT NULL,
  `role` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_hungarian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `prof_img`, `cover_img`, `mode`, `role`) VALUES
(96, 'asd', 'asd', 'asd@asd', '660c3d2aeea79_image.jpg', 'blankcover.png', 'light', 'member'),
(97, 'Balázs', 'asd', 'hujber.balazs28@gmail.com', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(98, 'admin', 'admin', 'admin@gmail.com', '661e454ad920a_k8.jpg', '662c05701a677_IMG_4381.jpeg', 'light', 'admin'),
(99, 'asd2', 'asd2', 'asd2@asd2', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(102, 'klvnilvyoqfx', 'js$5X*n21?7G', 'klvnilvyoqfx@mailsac.com', 'blankpfp.png', 'blankcover.png', 'night', 'member'),
(103, 'hsnthvclmnam', 'hsnthvclmnam', 'hsnthvclmnam@test.com', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(104, 'zxc', 'zxc', 'zxc@test.com', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(105, 'Haga Attila', 'Lopos123', 'hagaattilacr7@gmail.com', 'blankpfp.png', 'blankcover.png', 'night', 'member'),
(106, 'Humbi', 'asd', 'test@test.com', 'blankpfp.png', 'blankcover.png', 'night', 'member'),
(108, 'kyozai', 'Secret', 'balazs.molnar@gmail.com', '6611a22fe4647_20240406_190437.jpg', 'blankcover.png', 'light', 'member'),
(109, 'Nati', 'Secret', '2011.natalja@gmail.com', '6611a2e88fdff_inbound3802064678475387921.jpg', 'blankcover.png', 'light', 'member'),
(110, 'tntth', '01234', 'tothtina19@gmail.com', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(111, '!!!!!!!!!!!!!!', '!!!!!!!!!!!!!!', 'teszt@tesz123', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(112, 'adam', 'adam', 'g@g.hu', '661e55380d9f1_x2kz6a7jjmx71.jpg', '661e550dee11c_letöltés.jpg', 'light', 'member'),
(113, 'lajos', 'lajos', 's@s', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(114, 'Tóth Lajos', 'asd', 'a@a', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(115, 'Molnár Balázs', 'asd', 'b@b', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(116, 'Keczeg Jóska', 'c', 'c@c', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(117, 'Oláh Tivadar', 'd', 'd@d', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(118, 'Szipus Alfonz', 'e', 'e@e', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(119, 'Nagy Pál', 'f', 'f@f', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(120, 'tr', 'tr', 'tr@tr', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(121, 'Szlazsánszky ', 'Filip', 't@t', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(122, 'ss', 's', 'aaaa@sss', 'blankpfp.png', 'blankcover.png', 'light', 'member'),
(123, 'Abraham99', 'Rostasabraham2002', 'rostasabraham0206@gmail.com', 'blankpfp.png', 'blankcover.png', 'night', 'member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
