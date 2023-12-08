-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 23, 2023 at 11:18 AM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sarisaristore`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_table`
--

DROP TABLE IF EXISTS `cart_table`;
CREATE TABLE IF NOT EXISTS `cart_table` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart_table`
--

INSERT INTO `cart_table` (`product_id`, `unit_price`, `quantity`, `timestamp`) VALUES
(7, '4.99', 20, '2023-11-07 11:43:15'),
(3, '8.49', 4, '2023-11-07 11:41:41'),
(20, '9.49', 75, '2023-12-07 14:56:48'),
(2, '5.99', 5, '2023-12-07 14:26:12'),
(1, '10.00', 5, '2023-12-08 14:26:00'),
(1, '10.00', 2, '2023-12-08 12:48:39'),
(38, '1.00', 3, '2023-12-08 12:16:51'),
(46, '5.00', 40, '2023-12-08 03:22:41'),
(3, '8.49', 40, '2023-12-08 11:19:38');
-- --------------------------------------------------------

--
-- Table structure for table `products_table`
--

DROP TABLE IF EXISTS `products_table`;
CREATE TABLE IF NOT EXISTS `products_table` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products_table`
--

INSERT INTO `products_table` (`product_id`, `product_name`, `unit_price`, `stock_quantity`) VALUES
(1, 'Product1', '5', 50),
(2, 'Product2', '5', 100),
(3, 'Product3', '5', 75),
(4, 'Product4', '5', 30),
(5, 'Product5', '5', 90),
(6, 'Product6', '10', 40),
(7, 'Product7', '10', 120),
(8, 'Product8', '10', 60),
(9, 'Product9', '10', 0),
(10, 'Product10', '10', 110),
(11, 'Product11', '15', 25),
(12, 'Product12', '15', 95),
(13, 'Product13', '15', 0),
(14, 'Product14', '15', 70),
(15, 'Product15', '15', 45),
(16, 'Product16', '20', 85),
(17, 'Product17', '20', 55),
(18, 'Product18', '20', 65),
(19, 'Product19', '20', 0),
(20, 'Product20', '20', 75);

-- --------------------------------------------------------

--
-- Table structure for table `record_sale_table`
--

DROP TABLE IF EXISTS `record_sale_table`;
CREATE TABLE IF NOT EXISTS `record_sale_table` (
  `record_sale_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `sale_date` date DEFAULT NULL,
  `total_items` int DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`record_sale_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `record_sale_table`
--

INSERT INTO `record_sale_table` (`record_sale_id`, `user_id`, `sale_date`, `total_items`, `total_price`, `timestamp`) VALUES
(1, 1, '2023-11-24', 99, '1188.00', '2023-11-25 12:33:22'),
(2, 1, '2023-11-25', 12, '81.88', '2023-11-25 12:34:58'),
(3, 1, '2023-11-25', 20, '219.90', '2023-11-25 12:43:20'),
(4, 1, '2023-11-25', 6, '70.00', '2023-11-25 12:46:20'),
(5, 1, '2023-11-25', 6, '70.00', '2023-11-25 12:50:07'),
(6, 1, '2023-11-25', 10, '84.90', '2023-11-25 14:06:57'),
(7, 1, '2023-11-24', 10, '106.40', '2023-11-25 14:07:43'),
(8, 1, '2023-12-01', 5, '44.95', '2023-11-01 15:50:43'),
(9, 1, '2023-12-02', 25, '162.25', '2023-11-02 02:34:24'),
(10, 1, '2023-12-03', 14, '99.90', '2023-11-03 09:59:46'),
(11, 1, '2023-12-03', 20, '229.85', '2023-11-27 10:01:15'),
(12, 1, '2023-12-04', 18, '139.90', '2023-11-28 11:49:23'),
(13, 1, '2023-12-04', 1, '10.00', '2023-11-29 12:00:16'),
(14, 1, '2023-12-04', 14, '112.40', '2023-11-30 12:02:11'),
(15, 1, '2023-12-04', 10, '79.95', '2023-12-01 12:02:45'),
(16, 1, '2023-12-04', 5, '50.00', '2023-12-02 12:03:21'),
(17, 1, '2023-12-04', 15, '122.40', '2023-12-03 12:04:29'),
(18, 1, '2023-12-05', 25, '250.00', '2023-12-04 13:43:20'),
(19, 1, '2023-12-06', 21, '210.00', '2023-12-05 16:34:30'),
(20, 1, '2023-12-06', 1, '10.00', '2023-12-06 16:44:18'),
(21, 1, '2023-12-06', 33, '254.75', '2023-12-07 11:19:07'),
(22, 1, '2023-12-08', 30, '250.00', '2023-12-08 03:21:51');
-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

DROP TABLE IF EXISTS `user_table`;
CREATE TABLE IF NOT EXISTS `user_table` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`user_id`, `username`, `password`) VALUES
(1, 'Ivan', '$2y$10$Y5ovNDLnXeJ1LGhPIL7WUeoVExde6MXDmKTigPWbPUsiTtvV0kneK'),
(2, 'Irish', '$2y$10$Y5ovNDLnXeJ1LGhPIL7WUeoVExde6MXDmKTigPWbPUsiTtvV0kneK');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
