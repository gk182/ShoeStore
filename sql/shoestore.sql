-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 08:35 PM
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
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`) VALUES
(3, 2),
(13, 3),
(1, 4),
(12, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cart_detail`
--

CREATE TABLE `cart_detail` (
  `cart_detail_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_detail`
--

INSERT INTO `cart_detail` (`cart_detail_id`, `cart_id`, `product_id`, `size_id`, `quantity`) VALUES
(19, 12, 2, 7, 1),
(20, 12, 6, 31, 1),
(21, 12, 11, 61, 1),
(22, 12, 11, 63, 1);

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

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`order_detail_id`, `order_id`, `product_id`, `size_id`, `quantity`, `price`) VALUES
(4, 3, 1, 1, 1, 2000000.00),
(5, 4, 1, 1, 1, 2000000.00),
(6, 5, 1, 1, 1, 2000000.00),
(7, 6, 2, 7, 1, 2500000.00),
(8, 7, 1, 1, 1, 2000000.00),
(9, 8, 1, 1, 2, 2000000.00),
(10, 9, 5, 25, 2, 8690000.00),
(11, 10, 8, 44, 1, 6690000.00),
(12, 11, 2, 7, 1, 2500000.00),
(13, 11, 7, 37, 1, 10090000.00),
(14, 11, 11, 64, 2, 3690000.00);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `price`, `description`, `image_url`) VALUES
(1, 'Nike Air Max 1', 2000000.00, 'Nike Air Max 1 là một trong những mẫu giày thể thao nổi tiếng với đế Air Cushion giúp mang lại cảm giác êm ái, thoải mái khi di chuyển. Phù hợp cho cả chạy bộ và đi chơi.', 'giay_1733517153.jpg'),
(2, 'Adidas Ultra Boost', 2500000.00, 'Adidas Ultra Boost với công nghệ đế Boost nổi bật giúp tăng cường độ đàn hồi, mang lại sự thoải mái tối đa cho người sử dụng, thích hợp cho các vận động viên.', 'giay_1733517153.jpg'),
(3, 'Puma Suede', 1500000.00, 'Puma Suede có thiết kế cổ điển với chất liệu da lộn mềm mại, dễ phối đồ, tạo nên phong cách thời trang đơn giản nhưng không kém phần nổi bật.', 'puma_suede.jpg'),
(4, 'Giày Air Jordan 1 Mid ‘Coral Chalk’ (GS) 554725-662', 4990000.00, 'Giày Air Jordan 1 Mid ‘Coral Chalk’ với thiết kế đậm chất thể thao, chất liệu da cao cấp và sự kết hợp màu sắc nổi bật, mang lại sự ấn tượng cho người sử dụng.', 'giay1.jpg'),
(5, 'Giày Nike Air Jordan 4 Retro ‘Bred Reimagined’ FV5029-006', 8690000.00, 'Air Jordan 4 Retro ‘Bred Reimagined’ có thiết kế mạnh mẽ, sử dụng chất liệu da tổng hợp cao cấp, đế giày bền bỉ, thích hợp cho các tín đồ yêu thích thể thao và phong cách đường phố.', 'giay2.jpg'),
(6, 'Giày nam Dior x Air Jordan 1 High CN8607-002', 99999999.99, 'Dior x Air Jordan 1 High là sự kết hợp hoàn hảo giữa thương hiệu thời trang cao cấp Dior và Jordan, với chất liệu da sang trọng và thiết kế độc đáo, tạo nên sự đẳng cấp.', 'giay3.jpg'),
(7, 'Giày Air Jordan 1 Mid SE ‘All Star 2021 Carbon Fiber’ (GS) DD2192-001', 10090000.00, 'Air Jordan 1 Mid SE ‘All Star 2021’ mang đến sự kết hợp giữa chất liệu sợi carbon nhẹ và phong cách hiện đại, là lựa chọn tuyệt vời cho các fan của thương hiệu Jordan.', 'giay4.jpg'),
(8, 'Giày Air Jordan 1 Mid Carbon Fiber / All-Star Black DD1649-001', 6690000.00, 'Air Jordan 1 Mid Carbon Fiber với chất liệu sợi carbon tạo nên sự chắc chắn, phối màu đen tinh tế dễ dàng kết hợp với nhiều phong cách thời trang.', 'giay5.jpg'),
(9, 'Giày nam Off-White x Air Jordan 1 Retro High OG ‘UNC’ AQ0818-148', 60090000.00, 'Off-White x Air Jordan 1 Retro High ‘UNC’ mang phong cách thời trang đường phố với thiết kế sáng tạo, kết hợp giữa chất liệu vải và da cao cấp, nổi bật với chi tiết chữ Off-White.', 'giay6.jpg'),
(10, 'Giày Nike Air Jordan 1 Mid ‘Dark Iris’ 554724-095', 6290000.00, 'Giày Air Jordan 1 Mid ‘Dark Iris’ với tông màu tím nổi bật, là sự lựa chọn lý tưởng cho những ai yêu thích sự cá tính và khác biệt.', 'giay7.jpg'),
(11, 'Giày Nike Air Jordan 1 Mid ‘Smoke Grey’ 554724-092', 3690000.00, 'Air Jordan 1 Mid ‘Smoke Grey’ có màu sắc trung tính, kết hợp giữa da và vải, tạo cảm giác dễ chịu và dễ phối đồ.', 'giay8.jpg'),
(12, 'Giày Air Jordan 1 Low Triple White 553558-130', 3390000.00, 'Giày Air Jordan 1 Low Triple White với thiết kế đơn giản nhưng sang trọng, dễ dàng kết hợp với nhiều phong cách thời trang khác nhau.', 'giay9.jpg'),
(13, 'Giày Air Jordan 1 Low ‘Bred Toe’ 553558-161', 2890000.00, 'Air Jordan 1 Low ‘Bred Toe’ nổi bật với phối màu đỏ, đen và trắng, là biểu tượng của phong cách thể thao và thời trang đường phố.', 'giay10.jpg'),
(14, 'Giày Spider-Man × Nike Air Jordan 1 Retro High OG SP ‘Next Chapter’ DV1748-601', 6390000.00, 'Giày Spider-Man × Nike Air Jordan 1 với thiết kế độc đáo, màu sắc bắt mắt, là sản phẩm hợp tác giữa Nike và Marvel, dành cho các tín đồ yêu thích Spider-Man.', 'giay11.jpg'),
(15, 'Giày Dior x Jordan 1 Low Grey CN8608-002', 99999999.99, 'Dior x Jordan 1 Low Grey mang lại sự pha trộn giữa thời trang cao cấp và thể thao, với chất liệu da cao cấp và thiết kế sang trọng.', 'giay12.jpg'),
(16, 'Giày nam Air Jordan 1 Low ‘Smoke Grey V3’ 553558-040', 4890000.00, 'Giày Air Jordan 1 Low ‘Smoke Grey V3’ với màu sắc tinh tế, dễ dàng kết hợp với nhiều phong cách thời trang khác nhau.', 'giay13.jpg'),
(17, 'Giày Nike Air Jordan 1 Low ‘Panda’ DC0774-101', 3590000.00, 'Nike Air Jordan 1 Low ‘Panda’ với thiết kế tối giản, màu sắc đen trắng dễ phối đồ, là lựa chọn hoàn hảo cho những ai yêu thích sự thanh lịch.', 'giay14.jpg'),
(18, 'Giày Air Jordan 1 Low ‘White Wolf Grey’ DC0774-105', 3290000.00, 'Air Jordan 1 Low ‘White Wolf Grey’ mang đến vẻ đẹp đơn giản, nhẹ nhàng với màu trắng chủ đạo, dễ dàng kết hợp với mọi trang phục.', 'giay15.jpg'),
(19, 'Giày Nike Air Jordan 1 Mid SE ‘Ice Blue’ DV1308-104', 3690000.00, 'Air Jordan 1 Mid SE ‘Ice Blue’ nổi bật với màu xanh lạnh đặc trưng, là sự lựa chọn tinh tế và sang trọng cho các tín đồ thời trang.', 'giay16.jpg'),
(20, 'Giày Air Jordan 1 Mid SE ‘Diamond’ DH6933-100', 4890000.00, 'Giày Air Jordan 1 Mid SE ‘Diamond’ có thiết kế sang trọng với các chi tiết như kim cương, mang lại sự khác biệt cho người sử dụng.', 'giay17.jpg'),
(21, 'Giày nam Air Jordan 1 x Travis Scott x Fragment Retro High OG ‘Military Blue’ DH3227-105', 99999999.99, 'Giày Air Jordan 1 x Travis Scott x Fragment Retro High OG ‘Military Blue’ là sự hợp tác độc quyền giữa Nike và các tên tuổi lớn trong giới thời trang, tạo nên một sản phẩm đẳng cấp với thiết kế bắt mắt.', 'giay18.jpg'),
(22, 'Giày nam Air Jordan 1 Low ‘Light Smoke Grey V2’ 553558-030', 4890000.00, 'Air Jordan 1 Low ‘Light Smoke Grey V2’ với màu xám nhạt, mang đến vẻ đẹp thanh thoát và nhẹ nhàng, dễ dàng phối hợp với nhiều phong cách.', 'giay19.jpg'),
(23, 'Giày Nike Air Jordan 1 Low ‘Shadow Toe’ 553558-052', 6490000.00, 'Air Jordan 1 Low ‘Shadow Toe’ có phối màu đen và xám, phù hợp cho những ai yêu thích sự cá tính và nổi bật.', 'giay20.jpg'),
(24, 'Giày Nike Air Jordan 1 Low ‘Beaded Swoosh’ DV1762-001', 6890000.00, 'Giày Air Jordan 1 Low ‘Beaded Swoosh’ với thiết kế độc đáo, logo Swoosh được làm từ các hạt kim loại, tạo điểm nhấn cho sản phẩm.', 'giay21.jpg'),
(25, 'Giày Nike Air Jordan 1 Low ‘Aluminum’ DC0774-141', 5490000.00, 'Giày Air Jordan 1 Low ‘Aluminum’ có phối màu đen và xám, tạo nên sự tinh tế và mạnh mẽ cho người sử dụng.', 'giay22.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE `size` (
  `size_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `size`
--

INSERT INTO `size` (`size_id`, `product_id`, `size`, `quantity`) VALUES
(1, 1, 39, 10),
(2, 1, 40, 10),
(3, 1, 41, 10),
(4, 1, 42, 10),
(5, 1, 43, 10),
(6, 1, 44, 10),
(7, 2, 39, 10),
(8, 2, 40, 10),
(9, 2, 41, 10),
(10, 2, 42, 10),
(11, 2, 43, 10),
(12, 2, 44, 10),
(13, 3, 39, 10),
(14, 3, 40, 10),
(15, 3, 41, 10),
(16, 3, 42, 10),
(17, 3, 43, 10),
(18, 3, 44, 10),
(19, 4, 39, 10),
(20, 4, 40, 10),
(21, 4, 41, 10),
(22, 4, 42, 10),
(23, 4, 43, 10),
(24, 4, 44, 10),
(25, 5, 39, 10),
(26, 5, 40, 10),
(27, 5, 41, 10),
(28, 5, 42, 10),
(29, 5, 43, 10),
(30, 5, 44, 10),
(31, 6, 39, 10),
(32, 6, 40, 10),
(33, 6, 41, 10),
(34, 6, 42, 10),
(35, 6, 43, 10),
(36, 6, 44, 10),
(37, 7, 39, 10),
(38, 7, 40, 10),
(39, 7, 41, 10),
(40, 7, 42, 10),
(41, 7, 43, 10),
(42, 7, 44, 10),
(43, 8, 39, 10),
(44, 8, 40, 10),
(45, 8, 41, 10),
(46, 8, 42, 10),
(47, 8, 43, 10),
(48, 8, 44, 10),
(49, 9, 39, 10),
(50, 9, 40, 10),
(51, 9, 41, 10),
(52, 9, 42, 10),
(53, 9, 43, 10),
(54, 9, 44, 10),
(55, 10, 39, 10),
(56, 10, 40, 10),
(57, 10, 41, 10),
(58, 10, 42, 10),
(59, 10, 43, 10),
(60, 10, 44, 10),
(61, 11, 39, 10),
(62, 11, 40, 10),
(63, 11, 41, 10),
(64, 11, 42, 10),
(65, 11, 43, 10),
(66, 11, 44, 10),
(67, 12, 39, 10),
(68, 12, 40, 10),
(69, 12, 41, 10),
(70, 12, 42, 10),
(71, 12, 43, 10),
(72, 12, 44, 10),
(73, 13, 39, 10),
(74, 13, 40, 10),
(75, 13, 41, 10),
(76, 13, 42, 10),
(77, 13, 43, 10),
(78, 13, 44, 10),
(79, 14, 39, 10),
(80, 14, 40, 10),
(81, 14, 41, 10),
(82, 14, 42, 10),
(83, 14, 43, 10),
(84, 14, 44, 10),
(85, 15, 39, 10),
(86, 15, 40, 10),
(87, 15, 41, 10),
(88, 15, 42, 10),
(89, 15, 43, 10),
(90, 15, 44, 10),
(91, 16, 39, 10),
(92, 16, 40, 10),
(93, 16, 41, 10),
(94, 16, 42, 10),
(95, 16, 43, 10),
(96, 16, 44, 10),
(97, 17, 39, 10),
(98, 17, 40, 10),
(99, 17, 41, 10),
(100, 17, 42, 10),
(101, 17, 43, 10),
(102, 17, 44, 10),
(103, 18, 39, 10),
(104, 18, 40, 10),
(105, 18, 41, 10),
(106, 18, 42, 10),
(107, 18, 43, 10),
(108, 18, 44, 10),
(109, 19, 39, 10),
(110, 19, 40, 10),
(111, 19, 41, 10),
(112, 19, 42, 10),
(113, 19, 43, 10),
(114, 19, 44, 10),
(115, 20, 39, 10),
(116, 20, 40, 10),
(117, 20, 41, 10),
(118, 20, 42, 10),
(119, 20, 43, 10),
(120, 20, 44, 10),
(121, 21, 39, 10),
(122, 21, 40, 10),
(123, 21, 41, 10),
(124, 21, 42, 10),
(125, 21, 43, 10),
(126, 21, 44, 10),
(127, 22, 39, 10),
(128, 22, 40, 10),
(129, 22, 41, 10),
(130, 22, 42, 10),
(131, 22, 43, 10),
(132, 22, 44, 10),
(133, 23, 39, 10),
(134, 23, 40, 10),
(135, 23, 41, 10),
(136, 23, 42, 10),
(137, 23, 43, 10),
(138, 23, 44, 10),
(139, 24, 39, 10),
(140, 24, 40, 10),
(141, 24, 41, 10),
(142, 24, 42, 10),
(143, 24, 43, 10),
(144, 24, 44, 10),
(145, 25, 39, 10),
(146, 25, 40, 10),
(147, 25, 41, 10),
(148, 25, 42, 10),
(149, 25, 43, 10),
(150, 25, 44, 10);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('customer','staff') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `fullname`, `password`, `email`, `role`) VALUES
(2, 'giakhai182', 'Phạm Gia Khải', '$2y$10$o5j1/qBWuem/ofWP5jyDCu90UFOow8s5BdYhhsT7KGfUe.S2qBoYi', 'khaipham182@gmail.com', 'staff'),
(3, 'admin', 'Phạm Gia Khải', '$2y$10$ifl0B8JPh7uOji.ecrw.yeICUWFei1OyG95NQo/K0QzaN3U7sps7u', 'aaff@a.com', 'customer'),
(4, 'kkk', 'Phạm Gia Khải', '$2y$10$S8n.JaiTxipQNV.IvQ7aBOzeihu7ClgDcPGfTUQpJ9GjzIWJAAeXC', 'aaf1f@a.com', 'customer'),
(5, 'admin1', '123', '$2y$10$0ZCtzhgPOzm7khw3yxVtRur1ZJZdp0cC4.1uVXPBTr3BFTYC8RPa.', 'aaff@a1.com', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_detail`
--
ALTER TABLE `cart_detail`
  ADD PRIMARY KEY (`cart_detail_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`size_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cart_detail`
--
ALTER TABLE `cart_detail`
  MODIFY `cart_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `size`
--
ALTER TABLE `size`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_detail`
--
ALTER TABLE `cart_detail`
  ADD CONSTRAINT `cart_detail_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_detail_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `size` (`size_id`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_detail_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `size` (`size_id`) ON DELETE CASCADE;

--
-- Constraints for table `size`
--
ALTER TABLE `size`
  ADD CONSTRAINT `size_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
