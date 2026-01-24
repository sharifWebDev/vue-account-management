-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 25, 2019 at 05:34 AM
-- Server version: 10.2.24-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `imeshbd_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `company_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `cheque_book` text NOT NULL,
  `opening_balance` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `account_name`, `account_number`, `company_id`, `bank_id`, `branch_id`, `cheque_book`, `opening_balance`, `created`, `modified`, `status`) VALUES
(1, 'iMesh Limited', '198.110.10172', 1, 1, 1, '', '7604', '2017-11-12 14:52:42', '2017-11-12 08:52:42', 1),
(2, 'e-Sufiana.com', '136.110.17642', 2, 1, 2, '', '136450', '2017-11-12 14:52:54', '2017-11-12 08:52:54', 1),
(3, 'Imaginative', '136.110.16874', 3, 1, 2, '', '225572', '2017-11-12 14:53:09', '2017-11-12 08:53:09', 1),
(4, 'Triple S Trade Link', '224.120.566', 4, 1, 3, '', '154216', '2017-11-12 14:53:24', '2017-11-12 08:53:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `bank_id` int(11) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`bank_id`, `bank_name`, `address`, `phone`, `mobile`, `fax`, `email`, `website`, `created`, `modified`, `status`) VALUES
(1, 'Dutch Bangla Bank Ltd.', '', '', '', '', '', '', '2017-11-11 08:36:01', '2017-11-11 08:36:01', 1),
(2, 'City Bank Ltd.', 'Dhaka', '79876541', '01676717945', '', 'masud@imeshbd.com', '', '2018-07-31 07:54:42', '2018-07-31 07:54:42', 1);

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branch_id`, `branch_name`, `created`, `modified`, `status`) VALUES
(1, 'Karanigonj ', '2017-11-11 08:35:32', '2017-11-11 08:35:32', 1),
(2, 'Naya bazar', '2017-11-11 08:35:40', '2017-11-11 08:35:40', 1),
(3, 'Wari ', '2017-11-11 08:35:47', '2017-11-11 08:35:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company_name`, `address`, `phone`, `mobile`, `email`, `website`, `logo`, `created`, `modified`, `status`) VALUES
(1, 'i-Mesh Limited', '', '', '', '', '', '', '2017-11-11 08:34:17', '2017-11-11 08:34:17', 1),
(2, 'e-Sufiana.com', '', '', '', '', '', '', '2017-11-11 08:34:29', '2017-11-11 08:34:29', 1),
(3, 'Imaginative', '', '', '', '', '', '', '2017-11-11 08:34:57', '2017-11-11 08:34:57', 1),
(4, 'Triple S Trade Link', '', '', '', '', '', '', '2017-11-11 08:35:08', '2017-11-11 08:35:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `deposit` varchar(255) NOT NULL,
  `withdraw` varchar(255) NOT NULL,
  `receive_from` varchar(255) NOT NULL,
  `payment_to` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `bounce_transaction_id` int(11) NOT NULL,
  `bounce_details` varchar(255) NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `date`, `type`, `details`, `deposit`, `withdraw`, `receive_from`, `payment_to`, `attachment`, `user_id`, `account_id`, `bounce_transaction_id`, `bounce_details`, `created`, `modified`, `status`) VALUES
(1, '2017-11-12 14:52:42', 'Balance', 'Opening Balance', '7604', '', '-', '', '', 1, 1, 0, '', NULL, NULL, 1),
(2, '2017-11-12 14:52:54', 'Balance', 'Opening Balance', '136450', '', '-', '', '', 1, 2, 0, '', NULL, NULL, 1),
(3, '2017-11-12 14:53:09', 'Balance', 'Opening Balance', '225572', '', '-', '', '', 1, 3, 0, '', NULL, NULL, 1),
(4, '2017-11-12 14:53:24', 'Balance', 'Opening Balance', '154216', '', '-', '', '', 1, 4, 0, '', NULL, NULL, 1),
(11, '2018-08-02 07:45:47', 'Debit/Credit Card', '0198-0001-26408', '', '5000', '', 'Office expenses purpose', '', 12, 1, 0, '', '2017-11-13 00:18:47', '2017-11-13 01:35:40', 2),
(12, '2018-08-02 07:50:44', 'Cheque', 'Triple S ,DBBL,7684117', '', '100000', '', 'Cash withdraw by shuvojit Shaha .', '', 12, 4, 0, '', '2017-11-18 23:27:11', '2017-11-18 23:27:34', 2),
(13, '2018-08-02 07:45:31', 'Cheque', 'Provati Insurance ltd,Bank Asia,AA-4078441', '78000', '', 'Provati Insurance Ltd.', '', '', 12, 1, 0, '', '2017-11-22 23:52:32', '2017-11-22 23:52:43', 2),
(14, '2018-08-02 07:45:42', 'Cheque', 'I mesh ltd , DBBL , 4687904', '', '15000', '', ' MD.Sohel  Income Tax Purpose', '', 12, 1, 0, '', '2017-11-26 00:46:52', '2017-11-26 00:47:12', 2),
(15, '2018-08-02 07:45:38', 'Cheque', 'I mesh ltd , DBBL ,4687905', '', '18000', '', 'Tasnim Printing & Packaging', '', 12, 1, 0, '', '2017-11-29 07:10:30', '2017-11-29 07:10:42', 2),
(16, '2018-08-02 07:45:27', 'Cash', 'shuvojit shaha', '800000', '', 'T C Sir', '', '', 12, 1, 0, '', '2017-12-16 22:36:09', '2017-12-16 22:36:17', 2),
(17, '2018-08-02 07:45:35', 'Cheque', 'i-mesh ltd ,Dbbl , 4687909', '', '12000', '', 'Tasnim Printing & Packaging', '', 12, 1, 0, '', '2017-12-21 06:37:49', '2017-12-21 06:37:49', 2),
(18, '2018-08-02 07:45:51', 'Cheque', 'i-mesh ltd ,Dbbl , 4687910', '', '40000', '', 'M/s Link Thai Aluminium', '', 12, 1, 0, '', '2017-12-31 01:05:21', '2017-12-31 01:05:32', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `joining_date` date NOT NULL,
  `image` varchar(255) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `instagram` varchar(255) NOT NULL,
  `google_plus` varchar(255) NOT NULL,
  `linkedin` varchar(255) NOT NULL,
  `company_ids` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `address`, `phone`, `mobile`, `email`, `password`, `date_of_birth`, `joining_date`, `image`, `facebook`, `twitter`, `instagram`, `google_plus`, `linkedin`, `company_ids`, `user_type`, `created`, `modified`, `status`) VALUES
(1, 'Sahed', 'Ali', '29, Subash Bose Avenue (1st Floor), Luxmibazar, Dhaka-1100, Bangladesh.', '02 957 6958', '+88 01711 055617', 'sahed@imeshbd.com', '123786', '2017-10-31', '2017-10-31', '', '', '', '', '', '', '', 'admin', '2017-10-22 09:53:25', '2017-10-31 09:25:25', 1),
(11, 'Masudul', 'Kabir', 'Dhaka', '9588952', '01676717945', 'masud@imeshbd.com', '123456', '2017-11-11', '2017-11-11', 'Reverse-Image-Search-Engines-Apps-And-Its-Uses-20169.jpg', '', '', '', '', '', '[\"1\",\"2\",\"3\",\"4\"]', 'user', '2017-11-20 14:57:36', '2017-11-20 08:57:36', 1),
(12, 'Shuvojit', 'Shaha', '43, Ranking Street, Wari-1203', '', '01711660812', 'shuvojitshaha660@gmail.com', '123456', '1989-07-04', '2017-10-23', 'Shuvojit-Shaha.jpg', '', '', '', '', '', '[\"1\",\"2\",\"3\",\"4\"]', 'user', '2018-08-01 04:41:15', '2017-11-13 00:16:53', 1),
(13, 'Tamal', 'Chakraborty', 'Wari', '', '', 'i.org.T19@gmail.com', '01819214268', '2017-11-20', '2017-11-20', 'Tamal-Chakraborty.jpg', '', '', '', '', '', '[\"1\",\"2\",\"3\",\"4\"]', 'user', '2017-12-16 12:21:42', '2017-12-16 06:21:42', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
