-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 02, 2020 at 02:04 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecom_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `cat_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_title`) VALUES
(9, 'veggies'),
(13, 'Fruits');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `farmer_name` varchar(255) NOT NULL,
  `farmer_email` text NOT NULL,
  `phone_number` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `farmer_name`, `farmer_email`, `phone_number`) VALUES
(1, 'Jerry', 'jerry@farmers.com', '000-000-0000'),
(2, 'Nacho', 'nacho_email@farmers.com', '111-111-1111');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_price` float NOT NULL,
  `order_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_price`, `order_date`) VALUES
(32, 4, '2020-05-19 12:21:57'),
(33, 4, '2020-05-19 12:23:40'),
(34, 4, '2020-05-19 12:24:30'),
(35, 4, '2020-05-19 12:24:54'),
(36, 4, '2020-05-19 12:26:15'),
(37, 4, '2020-05-19 12:27:37'),
(38, 8, '2020-05-19 15:08:16'),
(39, 4, '2020-05-19 20:07:15'),
(40, 4, '2020-05-19 20:12:33'),
(41, 4, '2020-05-19 20:14:57'),
(42, 4, '2020-05-19 20:15:41'),
(43, 4, '2020-05-19 20:16:20'),
(44, 4, '2020-05-19 20:16:38'),
(45, 4, '2020-05-19 20:16:53'),
(46, 2, '2020-05-19 20:17:29'),
(47, 2, '2020-05-19 20:18:07'),
(48, 2, '2020-05-25 22:24:51'),
(49, 2, '2020-05-25 22:26:13'),
(50, 6, '2020-05-25 22:30:59'),
(51, 2, '2020-05-25 22:33:05'),
(52, 2, '2020-05-25 22:34:13'),
(53, 4, '2020-05-25 22:35:41'),
(54, 1, '2020-05-25 22:44:36'),
(55, 2, '2020-05-25 22:48:05'),
(56, 1, '2020-05-25 22:48:48'),
(57, 1, '2020-05-26 09:55:14'),
(58, 1, '2020-05-26 09:56:33'),
(59, 2, '2020-05-26 09:58:44'),
(60, 1, '2020-05-26 10:02:28'),
(61, 1, '2020-05-26 10:03:30'),
(62, 1, '2020-05-26 10:21:20'),
(63, 1, '2020-05-26 10:23:16'),
(64, 1, '2020-05-26 10:26:35'),
(65, 2, '2020-05-29 14:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_details_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_details_id`, `order_id`, `product_id`, `quantity`, `user_email`) VALUES
(8, 32, 8, 1, 'manishkannan125@gmail.com'),
(9, 32, 9, 1, 'manishkannan125@gmail.com'),
(10, 33, 8, 1, 'manishkannan125@gmail.com'),
(11, 33, 9, 1, 'manishkannan125@gmail.com'),
(12, 34, 8, 1, 'manishkannan125@gmail.com'),
(13, 34, 9, 1, 'manishkannan125@gmail.com'),
(14, 35, 8, 1, 'manishkannan125@gmail.com'),
(15, 35, 9, 1, 'manishkannan125@gmail.com'),
(16, 36, 8, 1, 'manishkannan125@gmail.com'),
(17, 36, 9, 1, 'manishkannan125@gmail.com'),
(18, 37, 8, 1, 'manishkannan125@gmail.com'),
(19, 37, 9, 1, 'manishkannan125@gmail.com'),
(20, 38, 10, 2, 'manishkannan125@gmail.com'),
(21, 39, 8, 2, 'manishkannan125@gmail.com'),
(22, 46, 9, 1, 'manishkannan125@gmail.com'),
(23, 48, 11, 2, 'manishkannan125@gmail.com'),
(24, 49, 9, 1, 'manish.kannan@yahoo.com'),
(25, 50, 9, 3, 'manish.kannan@yahoo.com'),
(26, 51, 9, 1, 'manish.kannan@yahoo.com'),
(27, 52, 8, 1, 'manish.kannan@yahoo.com'),
(28, 53, 12, 2, 'manish.kannan@yahoo.com'),
(29, 54, 11, 1, 'manish.kannan@yahoo.com'),
(30, 55, 11, 2, 'manish.kannan@yahoo.com'),
(31, 56, 11, 1, 'manish.kannan@yahoo.com'),
(32, 57, 11, 1, 'manish.kannan@yahoo.com'),
(33, 58, 11, 1, 'manishkannan125@gmail.com'),
(34, 59, 12, 1, 'manishkannan125@gmail.com'),
(35, 60, 11, 1, 'manishkannan125@gmail.com'),
(36, 61, 11, 1, 'manishkannan125@gmail.com'),
(37, 62, 11, 1, 'manishkannan125@gmail.com'),
(38, 63, 11, 1, 'manishkannan125@gmail.com'),
(39, 64, 11, 1, 'manishkannan125@gmail.com'),
(40, 65, 9, 1, 'manishkannan125@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `Unit` varchar(11) NOT NULL,
  `product_title` varchar(255) NOT NULL,
  `product_category_id` int(11) NOT NULL,
  `product_price` float NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_description` text NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `short_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `farmer_id`, `Unit`, `product_title`, `product_category_id`, `product_price`, `product_quantity`, `product_description`, `product_image`, `short_desc`) VALUES
(9, 1, 'lb', 'Brocollio', 9, 2, 2, 'Vegetable', 'Screen Shot 2020-05-18 at 2.55.29 PM.png', ''),
(10, 2, 'bunch', 'beet root', 9, 4, 3, 'veggie', 'Screen Shot 2020-05-18 at 3.35.12 PM.png', 'short desc'),
(11, 1, 'lb', 'apple', 13, 1, 7, 'This is a fruit', 'Screen Shot 2020-05-25 at 10.59.34 AM.png', 'fruit'),
(12, 2, 'bunch', 'carrot', 9, 2, 27, 'veggie', 'Screen Shot 2020-05-12 at 2.53.28 PM.png', 'root veggie');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'rico', 'rico@hotmail.com', '123'),
(26, 'mka1', 'manishkannan125@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b'),
(40, 'sury', 'emeskannan@gmail.com', '202cb962ac59075b964b07152d234b70'),
(41, 'mka123', 'manish.kannan@yahoo.com', '202cb962ac59075b964b07152d234b70');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_details_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
