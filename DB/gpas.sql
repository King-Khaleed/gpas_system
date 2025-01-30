-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 09:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gpas`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `interest_rate` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `product_name`, `product_price`, `interest_rate`) VALUES
(4, 6, 'rice', 20000.00, '32'),
(5, 6, 'beans ', 15000.00, '0.5'),
(6, 6, 'Yam', 5000.00, '20'),
(7, 6, 'Indomie', 500.00, NULL),
(8, 6, 'tea', 765.00, NULL),
(9, 6, 'rice', 43534.00, NULL),
(10, 7, 'ytrew', 1200.00, '2'),
(11, 10, 'hp', 10000.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'stemlab', 'stemlab@gmail.com', '$2y$10$B/soMSwDUP8PoWazMDyuze16iU1f4P8oB08t5xuJKMulaaxP00Rr.'),
(2, 'stemlab', 'murtalakhalid12@gmail.com', '$2y$10$bDW9pL2bEcgcxhjYwNzttOh3Jop.lspUqbqfpflRMEoNXAalWjAge'),
(6, 'stemlab1', 'murtalakhalid121@gmail.com', '$2y$10$E4jttxxe31E6I3L/N02NYO8eBGZx6d9rhrgvpLWYHJdLcgeKXUIeG'),
(7, 'amtech', 'amtech@gmail.com', '$2y$10$RmFZAr01nnzuOJ2/yIDLzeyTzk8Owjq94HPr.htFN9mz3BYFNPseO'),
(10, 'masud ', 'ummasud00@gmail.com', '$2y$10$UauxTKFUg4WLJTVADFEcsOazpDv6w0AwuJcuXr2QsImqsdVp/J6Jm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
