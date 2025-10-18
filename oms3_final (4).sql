-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2025 at 07:09 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oms3_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@email.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `userID` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `birth_date` date NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`userID`, `first_name`, `last_name`, `username`, `birth_date`, `email_address`, `phone_number`, `password`, `created_at`) VALUES
(1, 'James', 'Bond', 'jared_ong', '2025-10-08', 'hey31@gmail.com', '0912345678', '$2y$10$zeXR3sL9cyat0KnJaG18vOF80cmJsuDbjCI7kNk5CzvXN5SjzAEbW', '2025-10-14'),
(2, 'James', 'Bond', 'jamesbond', '2005-07-10', 'jamesbond@gmail.com', '09123456789', '$2y$10$qh1zDBkI9ZEa/yL/Z0jtE.J1qgu2ziYkfxw0y1/Yk6.Npdcy5GTP6', '2025-10-16'),
(3, 'John', 'Doe', 'johndoe', '2005-10-14', 'johndoe@gmail.com', '09123456789', '$2y$10$NNgQwxKfbDbYKRM/LKqvO.aHJWqCzgI4AVXFQqR0dYPw6nAoMgd9W', '2025-10-15'),
(4, 'Rick', 'Sanchez', 'rickyy', '2025-10-16', '2088581@g.cu.edu.ph', '09123456789', '$2y$10$oJPIpHcLXAE08pz2j08cB.1/DkDR8NvKd8r7m9Iz58SW3UhWrLhDu', '2025-10-14');

-- --------------------------------------------------------

--
-- Table structure for table `food_details`
--

CREATE TABLE `food_details` (
  `foodID` int(11) NOT NULL,
  `storeID` int(11) DEFAULT NULL,
  `food_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stocks` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `food_details`
--

INSERT INTO `food_details` (`foodID`, `storeID`, `food_name`, `price`, `stocks`, `type`, `image`, `description`) VALUES
(1, 32, 'Sliced Cake', '79.00', 1000, 'dessert', 'c668864327afb575be535071c493415b.jpg', 'A slice of Cake with chocolate flavor'),
(11, 31, 'Buko Salad', '79.00', 999, 'dessert', 'd4691314bc3c1d4532319ce98de73ce9.jpg', '5 oz of freshly made buko salad'),
(12, 29, 'Mango Shake', '89.00', 999, 'drink', 'eb7528f5cc01b344f791107718b90511.png', '5 oz of sweet mango shake'),
(13, 30, 'French Fries', '59.00', 999, 'snack', '588ebb0da91715deaf74941e071ad3c6.png', 'Delicious French Fries with cheesy flavor'),
(14, 33, 'Lechon Baboy', '399.00', 999, 'meal', 'ce4b7d2750c86535e04e5d17d078da9a.jpg', 'Our new special product to satisfy your cravings'),
(15, 33, 'Fried Fish', '230.00', 999, 'meal', '7fd3895122679e85bc446429b12c196a.jpeg', 'delicious friedfrish');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `storeID` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(32) NOT NULL DEFAULT 'Pending',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(32) NOT NULL DEFAULT 'cash',
  `payment_status` varchar(32) NOT NULL DEFAULT 'pending',
  `cart_json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `userID`, `storeID`, `order_date`, `status`, `total_amount`, `payment_method`, `payment_status`, `cart_json`) VALUES
(1, 1, 31, '2025-10-15 21:30:00', 'Completed', '6988.00', 'Cash', 'Paid', '[{\"id\":\"34\",\"name\":\"Tinapa\",\"price\":30,\"img\":\"http://[::1]/oms_final/uploads/e1893904e1751f48dd9b4a07066e7a13.jpg\",\"qty\":4},{\"id\":\"33\",\"name\":\"Cakee\",\"price\":3434,\"img\":\"http://[::1]/oms_final/uploads/08f1bcbf8b7f930f0e3fb92c541cb760.jpg\",\"qty\":2}]'),
(2, 1, 32, '2025-10-16 01:43:00', 'Completed', '327.00', 'Cash', 'Paid', '[{\"id\":\"2\",\"name\":\"Tinapa\",\"price\":30,\"img\":\"http://[::1]/oms_final/uploads/29307da9752f7422fb568161711a596d.jpg\",\"qty\":3},{\"id\":\"1\",\"name\":\"Sliced Cake\",\"price\":79,\"img\":\"http://[::1]/oms_final/uploads/c668864327afb575be535071c493415b.jpg\",\"qty\":3}]'),
(3, 1, 32, '2025-10-16 01:50:00', 'Completed', '109.00', 'Cash', 'Paid', '[{\"qty\":1,\"id\":\"1\",\"name\":\"Sliced Cake\",\"price\":\"79\",\"img\":\"http://[::1]/oms_final/uploads/c668864327afb575be535071c493415b.jpg\",\"desc\":\"A slice of Cake with chocolate flavor\",\"stocks\":\"1000\"},{\"qty\":1,\"id\":\"2\",\"name\":\"Tinapa\",\"price\":\"30\",\"img\":\"http://[::1]/oms_final/uploads/29307da9752f7422fb568161711a596d.jpg\",\"desc\":\"sardines 30g\",\"stocks\":\"10000\"}]'),
(4, 1, 32, '2025-10-16 02:01:23', 'Pending', '30.00', 'Cash', 'Pending', '[{\"qty\":1,\"id\":\"2\",\"name\":\"Tinapa\",\"price\":\"30\",\"img\":\"http://[::1]/oms_final/uploads/29307da9752f7422fb568161711a596d.jpg\",\"desc\":\"sardines 30g\",\"stocks\":\"10000\"}]'),
(5, 1, 32, '2025-10-16 02:04:00', 'In-Transit', '30.00', 'Cash', 'Pending', '[{\"qty\":1,\"id\":\"2\",\"name\":\"Tinapa\",\"price\":\"30\",\"img\":\"http://[::1]/oms_final/uploads/29307da9752f7422fb568161711a596d.jpg\",\"desc\":\"sardines 30g\",\"stocks\":\"10000\"}]'),
(6, 1, 32, '2025-10-16 02:06:24', 'Pending', '30.00', 'Cash', 'Pending', '[{\"qty\":1,\"id\":\"2\",\"name\":\"Tinapa\",\"price\":\"30\",\"img\":\"http://[::1]/oms_final/uploads/29307da9752f7422fb568161711a596d.jpg\",\"desc\":\"sardines 30g\",\"stocks\":\"10000\"}]'),
(7, 1, 32, '2025-10-16 02:12:34', 'Pending', '30.00', 'Cash', 'Pending', '[{\"qty\":1,\"id\":\"2\",\"name\":\"Tinapa\",\"price\":\"30\",\"img\":\"http://[::1]/oms_final/uploads/29307da9752f7422fb568161711a596d.jpg\",\"desc\":\"sardines 30g\",\"stocks\":\"10000\"}]'),
(8, 1, 32, '2025-10-16 02:14:00', 'Cancelled', '79.00', 'Credit Card', 'Failed', '[{\"qty\":1,\"id\":\"1\",\"name\":\"Sliced Cake\",\"price\":\"79\",\"img\":\"http://[::1]/oms_final/uploads/c668864327afb575be535071c493415b.jpg\",\"desc\":\"A slice of Cake with chocolate flavor\",\"stocks\":\"1000\"}]'),
(9, 1, 32, '2025-10-16 02:31:00', 'Cancelled', '109.00', 'Cash', 'Failed', '[{\"qty\":1,\"id\":\"2\",\"name\":\"Tinapa\",\"price\":\"30\",\"img\":\"http://[::1]/oms_final/uploads/29307da9752f7422fb568161711a596d.jpg\",\"desc\":\"sardines 30g\",\"stocks\":\"10000\"},{\"qty\":1,\"id\":\"1\",\"name\":\"Sliced Cake\",\"price\":\"79\",\"img\":\"http://[::1]/oms_final/uploads/c668864327afb575be535071c493415b.jpg\",\"desc\":\"A slice of Cake with chocolate flavor\",\"stocks\":\"1000\"}]'),
(10, 4, 32, '2025-10-16 02:43:00', 'Cancelled', '109.00', 'Cash', 'Failed', '[{\"qty\":1,\"id\":\"2\",\"name\":\"Tinapa\",\"price\":\"30\",\"img\":\"http://[::1]/oms_final/uploads/29307da9752f7422fb568161711a596d.jpg\",\"desc\":\"sardines 30g\",\"stocks\":\"10000\"},{\"qty\":1,\"id\":\"1\",\"name\":\"Sliced Cake\",\"price\":\"79\",\"img\":\"http://[::1]/oms_final/uploads/c668864327afb575be535071c493415b.jpg\",\"desc\":\"A slice of Cake with chocolate flavor\",\"stocks\":\"1000\"}]'),
(11, 4, 32, '2025-10-16 03:04:00', 'Completed', '30.00', 'Cash', 'Paid', '[{\"qty\":1,\"id\":\"2\",\"name\":\"Tinapa\",\"price\":\"30\",\"img\":\"http://[::1]/oms_final/uploads/29307da9752f7422fb568161711a596d.jpg\",\"desc\":\"sardines 30g\",\"stocks\":\"10000\"}]'),
(12, 4, 33, '2025-10-16 06:10:00', 'Completed', '90.00', 'Cash', 'Paid', '[{\"id\":\"2\",\"name\":\"Tinapa\",\"price\":30,\"img\":\"http://[::1]/oms_final/uploads/29307da9752f7422fb568161711a596d.jpg\",\"qty\":3}]'),
(13, 4, 33, '2025-10-16 23:35:24', 'Cancelled', '237.00', 'Cash', 'Failed', '[{\"id\":\"11\",\"name\":\"Buko Salad\",\"price\":79,\"img\":\"http://[::1]/oms_final/uploads/d4691314bc3c1d4532319ce98de73ce9.jpg\",\"qty\":3}]');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `storeID` int(11) NOT NULL,
  `store_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`storeID`, `store_name`, `address`, `created_at`, `updated_at`) VALUES
(29, 'Jollibee', 'Iponan', '2025-10-12 06:05:09', '2025-10-12 12:05:09'),
(30, 'McDonalds', 'gere', '2025-10-14 03:59:29', '2025-10-14 09:59:29'),
(31, 'Mang Inasal', 'New York City', '2025-10-15 15:29:34', '2025-10-15 21:29:34'),
(32, 'Jonas Bakeshop', 'Corrales extn.', '2025-10-16 01:40:53', '2025-10-16 07:40:53'),
(33, 'Crispy King', 'Capitol University', '2025-10-16 04:47:09', '2025-10-16 10:47:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `food_details`
--
ALTER TABLE `food_details`
  ADD PRIMARY KEY (`foodID`),
  ADD KEY `storeID` (`storeID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `idx_userID` (`userID`),
  ADD KEY `idx_storeID` (`storeID`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`storeID`),
  ADD UNIQUE KEY `ux_store_name` (`store_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `food_details`
--
ALTER TABLE `food_details`
  MODIFY `foodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `storeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_store` FOREIGN KEY (`storeID`) REFERENCES `stores` (`storeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`userID`) REFERENCES `customers` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
