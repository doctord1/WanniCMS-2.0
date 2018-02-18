-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 18, 2018 at 05:59 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `roomshare`
--

-- --------------------------------------------------------

--
-- Table structure for table `core_abuse`
--

CREATE TABLE `core_abuse` (
  `id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `response` varchar(150) NOT NULL,
  `responder_user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_addons`
--

CREATE TABLE `core_addons` (
  `id` int(11) NOT NULL,
  `addon_name` varchar(150) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_comments`
--

CREATE TABLE `core_comments` (
  `id` int(6) NOT NULL,
  `path` varchar(250) NOT NULL,
  `parent_type` varchar(50) NOT NULL,
  `parent_id` int(65) NOT NULL,
  `parent_page_author` varchar(150) NOT NULL,
  `comment_author` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `created` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_currency`
--

CREATE TABLE `core_currency` (
  `id` int(11) NOT NULL,
  `exchange_rate` text NOT NULL,
  `retrieval_date` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_files`
--

CREATE TABLE `core_files` (
  `id` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `large_path` tinytext NOT NULL,
  `medium_path` tinytext NOT NULL,
  `small_path` tinytext NOT NULL,
  `original_path` tinytext NOT NULL,
  `parent` varchar(150) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL,
  `destination_url` varchar(255) NOT NULL,
  `owner_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_installed`
--

CREATE TABLE `core_installed` (
  `id` int(1) NOT NULL,
  `value` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `core_installed`
--

INSERT INTO `core_installed` (`id`, `value`) VALUES
(1, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `core_page`
--

CREATE TABLE `core_page` (
  `id` int(11) NOT NULL,
  `page_name` varchar(150) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `child_page_number` int(3) NOT NULL DEFAULT '5',
  `page_type` varchar(150) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `created` varchar(50) NOT NULL,
  `last_updated` varchar(50) NOT NULL,
  `author` varchar(150) NOT NULL,
  `editor` varchar(150) NOT NULL,
  `allow_comments` varchar(3) NOT NULL,
  `path` varchar(255) NOT NULL,
  `show_author` varchar(3) NOT NULL DEFAULT 'yes',
  `show_in_streams` varchar(3) NOT NULL DEFAULT 'yes',
  `enrollment_fee` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_page_reactions`
--

CREATE TABLE `core_page_reactions` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `reactor_id` int(11) NOT NULL,
  `amount` int(6) NOT NULL,
  `reason` varchar(150) NOT NULL,
  `date` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_permissions`
--

CREATE TABLE `core_permissions` (
  `id` int(6) NOT NULL,
  `action` varchar(150) NOT NULL,
  `addon_name` varchar(150) NOT NULL,
  `allowed_roles` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_roles`
--

CREATE TABLE `core_roles` (
  `id` int(3) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `core_user`
--

CREATE TABLE `core_user` (
  `id` int(11) NOT NULL,
  `user_name` varchar(12) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `created_time` varchar(50) NOT NULL,
  `last_login` varchar(50) NOT NULL,
  `login_count` int(11) NOT NULL DEFAULT '2',
  `logged_in` varchar(3) NOT NULL,
  `phone` varchar(14) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `last_login_ip` varchar(15) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'authenticated',
  `picture` varchar(255) NOT NULL,
  `picture_thumbnail` varchar(255) NOT NULL,
  `secret_question` varchar(150) NOT NULL,
  `secret_answer` varchar(150) NOT NULL,
  `status` varchar(15) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `gender` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `core_user`
--

INSERT INTO `core_user` (`id`, `user_name`, `password`, `email`, `created_time`, `last_login`, `login_count`, `logged_in`, `phone`, `ip_address`, `last_login_ip`, `role`, `picture`, `picture_thumbnail`, `secret_question`, `secret_answer`, `status`, `full_name`, `gender`) VALUES
(1, 'system', '889a3a791b3875cfae413574b53da4bb8a90d53e', '', '2015-09-23 07:12:31', '1518854450', 7, 'no', '07016566148', '', '', 'admin', 'http://localhost/roomshare/files/user/medium-size/system.jpg', 'http://localhost/roomshare/files/user/small-size/system.jpg', '', '', 'subscribed', '', 'Male'),
(2, 'charles', 'b0a188671c9b17c62ab548a65773b6c80bbf7815', 'icycharly@gmail.com', '2018-02-15T06:48:34+01:00', '2018-02-15T06:48:34+01:00', 0, 'no', '08130968429', '::1', '', 'authenticated', '', '', 'gini', 'gini', 'not verified', '', 'Male'),
(3, 'qq', 'bed4eb698c6eeea7f1ddf5397d480d3f2c0fb938', 'udiudh@iuiu.uh', '2018-02-15T06:51:59+01:00', '2018-02-15T06:51:59+01:00', 0, 'no', '011', '::1', '', 'authenticated', '', '', 'qq', 'qq', 'not verified', '', 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `dating_preferences`
--

CREATE TABLE `dating_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gender` text NOT NULL,
  `age_range` varchar(10) NOT NULL,
  `occupation` varchar(150) NOT NULL,
  `location` varchar(255) NOT NULL,
  `religion` varchar(150) NOT NULL,
  `height` int(11) NOT NULL,
  `complexion` int(11) NOT NULL,
  `hobies` int(11) NOT NULL,
  `likes` text NOT NULL,
  `dislikes` text NOT NULL,
  `ethnicity` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `installed`
--

CREATE TABLE `installed` (
  `id` int(1) NOT NULL,
  `value` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_personal_data`
--

CREATE TABLE `user_personal_data` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_birth` varchar(10) NOT NULL,
  `occupation` varchar(150) NOT NULL,
  `interests` text NOT NULL,
  `ethnicity` varchar(150) NOT NULL,
  `religion` int(11) NOT NULL,
  `hobbies` text NOT NULL,
  `complexion` varchar(20) NOT NULL,
  `disabilities` varchar(150) NOT NULL,
  `place_of_primary_assignment` varchar(255) NOT NULL,
  `current_location` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `core_abuse`
--
ALTER TABLE `core_abuse`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_addons`
--
ALTER TABLE `core_addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_comments`
--
ALTER TABLE `core_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_currency`
--
ALTER TABLE `core_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_files`
--
ALTER TABLE `core_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_page`
--
ALTER TABLE `core_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_page_reactions`
--
ALTER TABLE `core_page_reactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_permissions`
--
ALTER TABLE `core_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_roles`
--
ALTER TABLE `core_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_user`
--
ALTER TABLE `core_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`user_name`);

--
-- Indexes for table `dating_preferences`
--
ALTER TABLE `dating_preferences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_personal_data`
--
ALTER TABLE `user_personal_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `core_abuse`
--
ALTER TABLE `core_abuse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_addons`
--
ALTER TABLE `core_addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_comments`
--
ALTER TABLE `core_comments`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_currency`
--
ALTER TABLE `core_currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `core_files`
--
ALTER TABLE `core_files`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_page`
--
ALTER TABLE `core_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_page_reactions`
--
ALTER TABLE `core_page_reactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `core_permissions`
--
ALTER TABLE `core_permissions`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_roles`
--
ALTER TABLE `core_roles`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `core_user`
--
ALTER TABLE `core_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `dating_preferences`
--
ALTER TABLE `dating_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_personal_data`
--
ALTER TABLE `user_personal_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
