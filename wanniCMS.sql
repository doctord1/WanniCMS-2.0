-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 02, 2018 at 01:44 AM
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
-- Database: `andrew`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int(6) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `actor` varchar(150) NOT NULL,
  `action` varchar(255) NOT NULL,
  `subject_name` varchar(150) NOT NULL,
  `actor_path` varchar(255) NOT NULL,
  `subject_path` varchar(255) NOT NULL,
  `date` varchar(30) NOT NULL,
  `parent` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `parent` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `description`, `parent`) VALUES
(4, 'none', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(6) NOT NULL,
  `path` varchar(250) NOT NULL,
  `parent_type` varchar(50) NOT NULL,
  `parent_id` int(65) NOT NULL,
  `parent_page_author` varchar(150) NOT NULL,
  `comment_author` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `created` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `path`, `parent_type`, `parent_id`, `parent_page_author`, `comment_author`, `content`, `created`) VALUES
(1, 'happy birthday amaka', '', 0, '', 'biscogirl', 'Wish you many more years.', '');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(1) NOT NULL,
  `application_name` varchar(35) NOT NULL,
  `welcome_message` varchar(50) NOT NULL DEFAULT 'Welcome to Wanni CMS',
  `base_path` varchar(30) DEFAULT NULL,
  `admin_folder_name` varchar(30) NOT NULL DEFAULT 'admin',
  `sub_folder_name` varchar(50) NOT NULL,
  `default_functions` varchar(150) NOT NULL DEFAULT '/includes/functions.php',
  `stylesheet` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `application_name`, `welcome_message`, `base_path`, `admin_folder_name`, `sub_folder_name`, `default_functions`, `stylesheet`) VALUES
(1, 'Wanni Vue', 'Wanni CMS', 'http://localhost/wanni-vue/', 'admin', '/', '/opt/lampp/htdocs/wanni-vue/core/core.php', 'fim.css');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `id` int(11) NOT NULL,
  `exchange_rate` text NOT NULL,
  `retrieval_date` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `exchange_rate`, `retrieval_date`) VALUES
(1, '{\n  \"disclaimer\": \"Usage subject to terms: https://openexchangerates.org/terms\",\n  \"license\": \"https://openexchangerates.org/license\",\n  \"timestamp\": 1513209600,\n  \"base\": \"USD\",\n  \"rates\": {\n    \"AED\": 3.673097,\n    \"AFN\": 69.033535,\n    \"ALL\": 112.772999,\n    \"AMD\": 484.459839,\n    \"ANG\": 1.784998,\n    \"AOA\": 165.9235,\n    \"ARS\": 17.303,\n    \"AUD\": 1.310714,\n    \"AWG\": 1.786821,\n    \"AZN\": 1.6895,\n    \"BAM\": 1.665172,\n    \"BBD\": 2,\n    \"BDT\": 82.36534,\n    \"BGN\": 1.65582,\n    \"BHD\": 0.377229,\n    \"BIF\": 1755.267406,\n    \"BMD\": 1,\n    \"BND\": 1.350339,\n    \"BOB\": 6.859113,\n    \"BRL\": 3.310104,\n    \"BSD\": 1,\n    \"BTC\": 0.000060516512,\n    \"BTN\": 64.423471,\n    \"BWP\": 10.352,\n    \"BYN\": 2.03545,\n    \"BZD\": 2.009975,\n    \"CAD\": 1.281921,\n    \"CDF\": 1562.881563,\n    \"CHF\": 0.985518,\n    \"CLF\": 0.02388,\n    \"CLP\": 646.777985,\n    \"CNH\": 6.6077,\n    \"CNY\": 6.6196,\n    \"COP\": 2985.73975,\n    \"CRC\": 565.260435,\n    \"CUC\": 1,\n    \"CUP\": 25.5,\n    \"CVE\": 94.1,\n    \"CZK\": 21.6926,\n    \"DJF\": 178.97,\n    \"DKK\": 6.288625,\n    \"DOP\": 47.970705,\n    \"DZD\": 115.340212,\n    \"EGP\": 17.8795,\n    \"ERN\": 15.228279,\n    \"ETB\": 27.120382,\n    \"EUR\": 0.844828,\n    \"FJD\": 2.071754,\n    \"FKP\": 0.745784,\n    \"GBP\": 0.745784,\n    \"GEL\": 2.636276,\n    \"GGP\": 0.745784,\n    \"GHS\": 4.496023,\n    \"GIP\": 0.745784,\n    \"GMD\": 47.5,\n    \"GNF\": 9009.133333,\n    \"GTQ\": 7.3499,\n    \"GYD\": 206.150183,\n    \"HKD\": 7.80475,\n    \"HNL\": 23.56,\n    \"HRK\": 6.376794,\n    \"HTG\": 63.858,\n    \"HUF\": 265.846,\n    \"IDR\": 13568.807977,\n    \"ILS\": 3.5231,\n    \"IMP\": 0.745784,\n    \"INR\": 64.329582,\n    \"IQD\": 1183.477494,\n    \"IRR\": 35201.793807,\n    \"ISK\": 103.908402,\n    \"JEP\": 0.745784,\n    \"JMD\": 125.245,\n    \"JOD\": 0.709001,\n    \"JPY\": 112.769,\n    \"KES\": 103.2,\n    \"KGS\": 69.734397,\n    \"KHR\": 4083.333333,\n    \"KMF\": 418.65,\n    \"KPW\": 900,\n    \"KRW\": 1085.16,\n    \"KWD\": 0.302197,\n    \"KYD\": 0.833355,\n    \"KZT\": 333.035,\n    \"LAK\": 8263.8,\n    \"LBP\": 1504.687443,\n    \"LKR\": 152.057514,\n    \"LRD\": 125.486146,\n    \"LSL\": 13.547422,\n    \"LYD\": 1.359251,\n    \"MAD\": 9.4349,\n    \"MDL\": 17.216242,\n    \"MGA\": 3194.576613,\n    \"MKD\": 52.0695,\n    \"MMK\": 1349.799087,\n    \"MNT\": 2434.866458,\n    \"MOP\": 8.040513,\n    \"MRO\": 355.391236,\n    \"MUR\": 34.174,\n    \"MVR\": 15.409873,\n    \"MWK\": 726.11,\n    \"MXN\": 19.016498,\n    \"MYR\": 4.082967,\n    \"MZN\": 60.004931,\n    \"NAD\": 13.545587,\n    \"NGN\": 360.115424,\n    \"NIO\": 30.9,\n    \"NOK\": 8.317083,\n    \"NPR\": 103.074806,\n    \"NZD\": 1.424877,\n    \"OMR\": 0.384996,\n    \"PAB\": 1,\n    \"PEN\": 3.23255,\n    \"PGK\": 3.193584,\n    \"PHP\": 50.49,\n    \"PKR\": 108.674285,\n    \"PLN\": 3.56338,\n    \"PYG\": 5672.702124,\n    \"QAR\": 3.684999,\n    \"RON\": 3.913812,\n    \"RSD\": 100.823851,\n    \"RUB\": 58.5532,\n    \"RWF\": 851.92,\n    \"SAR\": 3.7506,\n    \"SBD\": 7.775606,\n    \"SCR\": 14.003609,\n    \"SDG\": 6.676036,\n    \"SEK\": 8.4104,\n    \"SGD\": 1.347295,\n    \"SHP\": 0.745784,\n    \"SLL\": 7660.273512,\n    \"SOS\": 574.843879,\n    \"SRD\": 7.458,\n    \"SSP\": 130.2634,\n    \"STD\": 20717.098928,\n    \"SVC\": 8.749901,\n    \"SYP\": 514.96999,\n    \"SZL\": 13.540168,\n    \"THB\": 32.502,\n    \"TJS\": 8.814964,\n    \"TMT\": 3.509961,\n    \"TND\": 2.510091,\n    \"TOP\": 2.30228,\n    \"TRY\": 3.815935,\n    \"TTD\": 6.68049,\n    \"TWD\": 30.024,\n    \"TZS\": 2239.9,\n    \"UAH\": 27.059102,\n    \"UGX\": 3596.35,\n    \"USD\": 1,\n    \"UYU\": 28.95634,\n    \"UZS\": 8032.6,\n    \"VEF\": 10.555762,\n    \"VND\": 22713.435659,\n    \"VUV\": 106.89821,\n    \"WST\": 2.547451,\n    \"XAF\": 554.170527,\n    \"XAG\": 0.06234235,\n    \"XAU\": 0.00079745,\n    \"XCD\": 2.70255,\n    \"XDR\": 0.709315,\n    \"XOF\": 554.170527,\n    \"XPD\": 0.00098091,\n    \"XPF\": 100.81474,\n    \"XPT\": 0.0011262,\n    \"YER\": 250.25,\n    \"ZAR\": 13.4671,\n    \"ZMW\": 9.951436,\n    \"ZWL\": 322.355011\n  }\n}', '2017-12-14');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
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
-- Table structure for table `installed`
--

CREATE TABLE `installed` (
  `id` int(1) NOT NULL,
  `value` varchar(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `installed`
--

INSERT INTO `installed` (`id`, `value`) VALUES
(1, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `installer`
--

CREATE TABLE `installer` (
  `id` int(1) NOT NULL,
  `value` varchar(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `installer`
--

INSERT INTO `installer` (`id`, `value`) VALUES
(1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(150) NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
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

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `page_name`, `parent_id`, `child_page_number`, `page_type`, `visible`, `content`, `created`, `last_updated`, `author`, `editor`, `allow_comments`, `path`, `show_author`, `show_in_streams`, `enrollment_fee`) VALUES
(1, 'home', 0, 5, 'page', 1, 'GeniusAid+is+a+community+of+people+who+help+each+other+achieve+their+goals.+We+love+making+money+and+we+think+you+can+too.', '', '2016-01-31T06:36:04+00:00', 'superadmin', 'obinna', 'no', 'https://friendsinmoney.com/?page_name=home', 'yes', 'yes', 0);

-- --------------------------------------------------------

--
-- Table structure for table `page_reactions`
--

CREATE TABLE `page_reactions` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `reactor_id` int(11) NOT NULL,
  `amount` int(6) NOT NULL,
  `reason` varchar(150) NOT NULL,
  `date` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `page_reactions`
--

INSERT INTO `page_reactions` (`id`, `page_id`, `owner_id`, `reactor_id`, `amount`, `reason`, `date`) VALUES
(1, 431, 2, 0, 10, 'like', '2017-06-14');

-- --------------------------------------------------------

--
-- Table structure for table `page_type`
--

CREATE TABLE `page_type` (
  `id` int(11) NOT NULL,
  `page_type_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `page_type`
--

INSERT INTO `page_type` (`id`, `page_type_name`) VALUES
(2, 'blog'),
(4, 'contest'),
(3, 'events'),
(1, 'page');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user_name` varchar(12) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `created_time` varchar(50) NOT NULL,
  `last_login` varchar(50) NOT NULL,
  `login_count` int(11) NOT NULL DEFAULT '2',
  `logged_in` varchar(3) NOT NULL,
  `phone` varchar(14) NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `region_name` varchar(255) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `last_login_ip` varchar(15) NOT NULL,
  `preferred_currency` varchar(3) NOT NULL,
  `site_funds_amount` int(11) NOT NULL DEFAULT '0',
  `role` varchar(50) NOT NULL DEFAULT 'authenticated',
  `account_type` varchar(10) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `picture_thumbnail` varchar(255) NOT NULL,
  `secret_question` varchar(150) NOT NULL,
  `secret_answer` varchar(150) NOT NULL,
  `status` varchar(15) NOT NULL,
  `bank_account_no` varchar(255) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `full_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_name`, `password`, `email`, `created_time`, `last_login`, `login_count`, `logged_in`, `phone`, `country_name`, `region_name`, `ip_address`, `last_login_ip`, `preferred_currency`, `site_funds_amount`, `role`, `account_type`, `picture`, `picture_thumbnail`, `secret_question`, `secret_answer`, `status`, `bank_account_no`, `bank_name`, `full_name`) VALUES
(1, 'system', '3ec1483c41072d8e50b72a9147e7fecca2143355', '', '2015-09-23 07:12:31', '1503592655', 7, 'no', '07016566148', '', '', '', '', '', 118270, 'admin', 'Bronze', 'https://friendsinmoney.com/uploads/files/user/medium-size/system.jpg', 'https://friendsinmoney.com/uploads/files/user/small-size/system.jpg', '', '', 'subscribed', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_counters`
--

CREATE TABLE `user_activity_counters` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `month` varchar(10) NOT NULL,
  `year` int(4) NOT NULL,
  `draws` int(2) NOT NULL,
  `posts_created` int(2) NOT NULL,
  `comments_made` int(2) NOT NULL,
  `contests_entered` int(2) NOT NULL,
  `total_contest_votes` int(6) NOT NULL,
  `fundraisers_created` int(2) NOT NULL,
  `fundraisers_supported` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_activity_counters`
--

INSERT INTO `user_activity_counters` (`id`, `user_id`, `month`, `year`, `draws`, `posts_created`, `comments_made`, `contests_entered`, `total_contest_votes`, `fundraisers_created`, `fundraisers_supported`) VALUES
(5, 2, '', 0, 1, 3, 31, 0, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `installed`
--
ALTER TABLE `installed`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `installer`
--
ALTER TABLE `installer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_reactions`
--
ALTER TABLE `page_reactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_type`
--
ALTER TABLE `page_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_type` (`page_type_name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`user_name`);

--
-- Indexes for table `user_activity_counters`
--
ALTER TABLE `user_activity_counters`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1604;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;
--
-- AUTO_INCREMENT for table `installed`
--
ALTER TABLE `installed`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129981;
--
-- AUTO_INCREMENT for table `installer`
--
ALTER TABLE `installer`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=494;
--
-- AUTO_INCREMENT for table `page_reactions`
--
ALTER TABLE `page_reactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `page_type`
--
ALTER TABLE `page_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;
--
-- AUTO_INCREMENT for table `user_activity_counters`
--
ALTER TABLE `user_activity_counters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
