-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 07:50 PM
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
-- Database: `shoestore`
--

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Chưa xác thực','Đã xác thực','Đang giao','Đã giao thành công') NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `user_id`, `order_date`, `total_price`, `status`, `fullname`, `address`, `phone`) VALUES
(3, 3, '2024-12-08 04:59:36', 2000000.00, 'Chưa xác thực', '', 'kkkk', '0556425634'),
(4, 3, '2024-12-08 05:02:19', 2000000.00, 'Đã xác thực', '', '123', '1111'),
(5, 3, '2024-12-08 05:03:55', 2000000.00, 'Chưa xác thực', '', '123', '1'),
(6, 3, '2024-12-08 05:07:37', 2500000.00, 'Đang giao', '', '123', '0556425634'),
(7, 3, '2024-12-08 05:08:09', 2000000.00, 'Chưa xác thực', '', '1', '1'),
(8, 3, '2024-12-08 05:11:42', 4000000.00, 'Đã giao thành công', '', 'kkkk', '1'),
(9, 3, '2024-12-09 00:22:59', 17380000.00, 'Đang giao', '', 'kkkk', '0556425634'),
(10, 5, '2024-12-09 01:08:20', 6690000.00, 'Đang giao', 'haizz ', '123kkk', '12315'),
(11, 3, '2024-12-09 01:37:54', 19970000.00, 'Đã giao thành công', 'Phạm Gia Khải', 'nhà ', '01213');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
