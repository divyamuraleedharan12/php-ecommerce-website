-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 07, 2025 at 09:15 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` text,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_email`, `name`, `address`, `total`, `created_at`) VALUES
(1, 'muralidivya823@gmail.com', 'dsfsdf', 'dfsdf', 149.98, '2025-06-06 22:27:57'),
(2, 'muralidivya823@gmail.com', 'Divya Muraleedharan', 'PayPal Address', 79.98, '2025-06-07 08:03:50'),
(3, 'muralidivya823@gmail.com', 'Divya Muraleedharan', 'PayPal Address', 179.97, '2025-06-07 08:21:12');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text,
  `category` varchar(100) DEFAULT 'General',
  `stock` int DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`, `category`, `stock`) VALUES
(1, 'Elegant Watch', 89.99, 'watch.jpg', 'Stylish and water-resistant watch.', 'Watch', 5),
(2, 'Wireless Headphones', 59.99, 'headphones.jpg', 'Bluetooth headphones with noise cancellation.', 'Headphones', 8),
(3, 'Backpack', 39.99, 'backpack.jpg', 'Durable backpack with laptop compartment.', 'Bags', 0),
(4, 'Miss Dior Perfume', 200.00, 'perfume.jpg', 'Long lasting Perfume', 'Perfume', 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Divya Muraleedharan', 'muralidivya823@gmail.com', '$2y$10$1e7Su7ZpU8W5EEO9IgkaEeer6/Tn53BPrWsVuCHEgNvVUlG5r0IxS', '2025-06-06 22:26:28');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
