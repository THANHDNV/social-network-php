-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2019 at 09:30 PM
-- Server version: 10.3.15-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `social`
--
CREATE DATABASE IF NOT EXISTS `social` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `social`;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_body` text NOT NULL,
  `posted_by` varchar(60) NOT NULL,
  `posted_to` varchar(60) NOT NULL,
  `date_added` datetime NOT NULL,
  `removed` varchar(3) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_body`, `posted_by`, `posted_to`, `date_added`, `removed`, `post_id`) VALUES
(1, 'guess Im alone', 'thanh_dnv_0', 'thanh_dnv_0', '2019-06-24 23:56:44', 'no', 13),
(2, 'now it\'s good', 'thanh_dnv_0', 'thanh_dnv_0', '2019-06-29 12:30:17', 'no', 23),
(3, 'yes it is', 'thanh_dao_0', 'thanh_dnv_0', '2019-06-29 12:31:26', 'no', 23);

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `user_to` varchar(50) NOT NULL,
  `user_from` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `username`, `post_id`) VALUES
(2, 'thanh_dao_0', 23);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_to` varchar(50) NOT NULL,
  `user_from` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `date` datetime NOT NULL,
  `opened` varchar(3) NOT NULL,
  `viewed` varchar(3) NOT NULL,
  `deleted` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_to`, `user_from`, `body`, `date`, `opened`, `viewed`, `deleted`) VALUES
(1, 'thanh_dao_0', 'thanh_dnv_0', 'hello', '2019-06-28 00:19:21', 'yes', 'yes', 'no'),
(2, 'thanh_dao_0', 'thanh_dnv_0', 'hey man', '2019-06-28 00:34:55', 'yes', 'yes', 'no'),
(3, 'thanh_dao_0', 'thanh_dnv_0', 'hey man', '2019-06-28 00:38:28', 'yes', 'yes', 'no'),
(4, 'thanh_dao_0', 'thanh_dnv_0', 'oh I remember...', '2019-06-28 00:38:42', 'yes', 'yes', 'no'),
(5, 'thanh_dao_0', 'thanh_dnv_0', 'oh I remember...', '2019-06-28 00:43:08', 'yes', 'yes', 'no'),
(6, 'thanh_dao_0', 'thanh_dnv_0', 'oh I remember...', '2019-06-28 00:46:44', 'yes', 'yes', 'no'),
(7, 'thanh_dao_0', 'thanh_dnv_0', 'wow the message is overflowing...', '2019-06-28 00:47:03', 'yes', 'yes', 'no'),
(8, 'thanh_dnv_0', 'thanh_dao_0', 'I can see that...', '2019-06-28 00:47:21', 'yes', 'yes', 'no'),
(9, 'thanh_dnv_0', 'thanh_dao_0', 'damn...', '2019-06-28 00:47:26', 'yes', 'yes', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_to` varchar(50) NOT NULL,
  `user_from` varchar(50) NOT NULL,
  `message` int(11) NOT NULL,
  `link` text NOT NULL,
  `datetime` datetime NOT NULL,
  `opened` varchar(3) NOT NULL,
  `viewed` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `body` text NOT NULL,
  `added_by` varchar(60) NOT NULL,
  `user_to` varchar(60) NOT NULL,
  `date` datetime NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `deleted` varchar(3) NOT NULL,
  `likes` int(11) NOT NULL,
  `image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `body`, `added_by`, `user_to`, `date`, `user_closed`, `deleted`, `likes`, `image`) VALUES
(1, 'Hellooooooooooooooooo World!', 'thanh_dao_0', 'none', '2019-06-22 15:40:26', 'no', 'no', 0, ''),
(2, 'Hellllllllo World!', 'thanh_dao_0', 'none', '2019-06-22 15:41:30', 'no', 'no', 0, ''),
(3, 'Helllllllllo World!!!!!!!', 'thanh_dao_0', 'none', '2019-06-22 15:42:08', 'no', 'no', 0, ''),
(4, 'How many is this?', 'thanh_dao_0', 'none', '2019-06-22 15:46:45', 'no', 'no', 0, ''),
(5, 'How many is this?', 'thanh_dao_0', 'none', '2019-06-22 15:49:22', 'no', 'no', 0, ''),
(6, 'How many is this?', 'thanh_dao_0', 'none', '2019-06-22 15:50:09', 'no', 'no', 0, ''),
(7, 'Just filling this up', 'thanh_dao_0', 'none', '2019-06-23 16:07:24', 'no', 'no', 0, ''),
(8, 'Keep going...', 'thanh_dao_0', 'none', '2019-06-23 16:07:39', 'no', 'no', 0, ''),
(9, 'just many does it take...?', 'thanh_dao_0', 'none', '2019-06-23 16:07:59', 'no', 'no', 0, ''),
(10, 'common...', 'thanh_dao_0', 'none', '2019-06-23 16:08:05', 'no', 'no', 0, ''),
(11, 'finally yesssssssssssssssssssssssssssss', 'thanh_dao_0', 'none', '2019-06-23 16:18:47', 'no', 'no', 0, ''),
(12, 'Hey guys', 'thanh_dnv_0', 'none', '2019-06-24 00:07:15', 'no', 'no', 0, ''),
(13, 'Anybody?', 'thanh_dnv_0', 'none', '2019-06-24 00:13:04', 'no', 'no', 0, ''),
(14, 'welcome', 'thanh_dao_0', 'none', '2019-06-24 00:14:44', 'no', 'no', 0, ''),
(15, 'Feel free to post anything on here', 'thanh_dao_0', 'none', '2019-06-24 00:24:16', 'no', 'no', 0, ''),
(16, 'so that I can improve the website', 'thanh_dao_0', 'none', '2019-06-24 00:24:53', 'no', 'no', 0, ''),
(17, 'Lots of bugs are on this site...', 'thanh_dao_0', 'none', '2019-06-24 00:25:28', 'no', 'no', 0, ''),
(18, 'so just tell me if you need any improvement...', 'thanh_dao_0', 'none', '2019-06-24 00:25:50', 'no', 'no', 0, ''),
(19, 'so I just need to post something... right...?', 'david_dnv_2', 'none', '2019-06-24 23:27:07', 'no', 'no', 0, ''),
(20, 'abc', 'thanh_dnv_0', 'thanh_dao_0', '2019-06-26 23:26:18', 'no', 'yes', 0, ''),
(21, 'hey man', 'thanh_dnv_0', 'thanh_dao_0', '2019-06-29 12:27:29', 'no', 'no', 0, ''),
(22, 'so the last one got sth wrong... so I just repost sth :v', 'thanh_dnv_0', 'thanh_dao_0', '2019-06-29 12:29:26', 'no', 'no', 0, ''),
(23, 'it\'s still messed up :v', 'thanh_dnv_0', 'thanh_dao_0', '2019-06-29 12:30:04', 'no', 'no', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `trends`
--

CREATE TABLE `trends` (
  `title` varchar(50) NOT NULL,
  `hits` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `signup_date` date NOT NULL,
  `profile_pic` varchar(100) NOT NULL,
  `num_posts` int(11) NOT NULL,
  `num_likes` int(11) NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `friend_array` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `signup_date`, `profile_pic`, `num_posts`, `num_likes`, `user_closed`, `friend_array`) VALUES
(1, 'Thanh', 'Dao', 'thanh_dao_0', 'test@local.com', 'e99a18c428cb38d5f260853678922e03', '2019-06-17', 'assets/images/profile_pics/default/head_emerald.png', 16, 0, 'no', ',thanh_dnv_0,'),
(2, 'Thanh', 'Dnv', 'thanh_dnv_0', 'thanh@local.com', '827ccb0eea8a706c4c34a16891f84e7b', '2019-06-23', 'assets/images/profile_pics/thanh_dnv_062f67ebc5d317044389217b564a5e63fn.jpeg', 6, 1, 'no', ',thanh_dao_0,'),
(3, 'David', 'Dnv', 'david_dnv', 'dave@local.com', '827ccb0eea8a706c4c34a16891f84e7b', '2019-06-24', 'assets/images/profile_pics/default/head_emerald.png', 0, 0, 'no', ','),
(4, 'David', 'Dnv', 'david_dnv_1', 'dave2@test.com', '827ccb0eea8a706c4c34a16891f84e7b', '2019-06-24', 'assets/images/profile_pics/default/head_pete_river.png', 0, 0, 'no', ',dave_dnv,david_dnv_2,'),
(5, 'Dave', 'Dnv', 'dave_dnv', 'dave3@local.com', '827ccb0eea8a706c4c34a16891f84e7b', '2019-06-24', 'assets/images/profile_pics/default/head_emerald.png', 0, 0, 'no', ',david_dnv_1,'),
(6, 'David', 'Dnv', 'david_dnv_2', 'david3@local.com', '827ccb0eea8a706c4c34a16891f84e7b', '2019-06-24', 'assets/images/profile_pics/default/head_pete_river.png', 1, 0, 'no', ',david_dnv_1,');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trends`
--
ALTER TABLE `trends`
  ADD PRIMARY KEY (`title`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
