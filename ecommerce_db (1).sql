-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2025 at 09:28 AM
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
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_session_id`, `product_id`, `quantity`, `order_date`) VALUES
(1, 'k1urogbt924i8vcemi6lrhoib4', 2, 1, '2025-08-03 23:50:54'),
(2, 'k1urogbt924i8vcemi6lrhoib4', 2, 1, '2025-08-04 09:29:47'),
(3, 'k1urogbt924i8vcemi6lrhoib4', 2, 1, '2025-08-04 09:35:09'),
(4, 'k1urogbt924i8vcemi6lrhoib4', 2, 1, '2025-08-04 11:22:29'),
(5, 'k1urogbt924i8vcemi6lrhoib4', 3, 1, '2025-08-04 11:22:29'),
(6, 'k1urogbt924i8vcemi6lrhoib4', 2, 3, '2025-08-04 14:02:55'),
(7, 'k1urogbt924i8vcemi6lrhoib4', 6, 1, '2025-08-04 14:02:55'),
(8, 'k1urogbt924i8vcemi6lrhoib4', 8, 1, '2025-08-04 14:02:55'),
(9, 'k1urogbt924i8vcemi6lrhoib4', 2, 3, '2025-08-04 14:43:21'),
(10, 'k1urogbt924i8vcemi6lrhoib4', 8, 2, '2025-08-04 14:43:21'),
(11, 'k1urogbt924i8vcemi6lrhoib4', 3, 2, '2025-08-04 14:43:21'),
(12, 'k1urogbt924i8vcemi6lrhoib4', 17, 1, '2025-08-04 14:43:21'),
(13, 'k1urogbt924i8vcemi6lrhoib4', 18, 1, '2025-08-04 14:43:21'),
(14, 'k1urogbt924i8vcemi6lrhoib4', 19, 1, '2025-08-04 14:43:21'),
(15, 'k1urogbt924i8vcemi6lrhoib4', 16, 1, '2025-08-04 14:43:21'),
(16, 'k1urogbt924i8vcemi6lrhoib4', 15, 1, '2025-08-04 14:43:21'),
(17, 'k1urogbt924i8vcemi6lrhoib4', 14, 1, '2025-08-04 14:43:21'),
(18, 'k1urogbt924i8vcemi6lrhoib4', 11, 1, '2025-08-04 14:43:21'),
(19, 'k1urogbt924i8vcemi6lrhoib4', 12, 1, '2025-08-04 14:43:21'),
(20, 'k1urogbt924i8vcemi6lrhoib4', 13, 1, '2025-08-04 14:43:21'),
(21, 'k1urogbt924i8vcemi6lrhoib4', 10, 1, '2025-08-04 14:43:21'),
(22, 'k1urogbt924i8vcemi6lrhoib4', 5, 1, '2025-08-04 14:43:21'),
(23, 'k1urogbt924i8vcemi6lrhoib4', 6, 1, '2025-08-04 14:43:21'),
(24, 'k1urogbt924i8vcemi6lrhoib4', 7, 1, '2025-08-04 14:43:21'),
(25, 'k1urogbt924i8vcemi6lrhoib4', 4, 1, '2025-08-04 14:43:21');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'unknown',
  `image_url` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `image_url`, `stock`) VALUES
(2, 'Razer BlackWidow', 'The Razer BlackWidow Mechanical Gaming Keyboard features tactile green switches, customizable RGB lighting, and programmable macros for an immersive gaming experience.', 99.99, 'keyboard', 'img/Keyboard/blackwidow-v3-tenkeyless-usp1-mobile-v2-removebg-preview.png', 150),
(3, 'Logitech G Pro', 'Compact and durable, the Logitech G Pro Keyboard offers fast-actuating mechanical switches and customizable lighting, designed for esports professionals.', 129.99, 'keyboard', 'img/Keyboard/pro-clicky-gallery-1.png', 100),
(4, 'Corsair K70', 'The Corsair K70 RGB MK.2 features Cherry MX switches, per-key RGB lighting, and a durable aluminum frame for precision and style.', 149.99, 'keyboard', 'img/Keyboard/CorsairK70RGBMK.2MechanicalGamingKeyboard-removebg-preview.png', 200),
(5, 'SteelSeries Apex', 'The SteelSeries Apex Pro features adjustable mechanical switches, OLED smart display, and dynamic RGB lighting for ultimate customization.', 199.99, 'keyboard', 'img/Keyboard/www.ubuy.com-removebg-preview.png', 150),
(6, 'HyperX Alloy', 'The HyperX Alloy Origins offers responsive mechanical switches, compact design, and vibrant RGB lighting for gamers seeking performance.', 109.99, 'keyboard', 'img/Keyboard/1RUQIEKG1655873808-1361x711-removebg-preview.png', 200),
(7, 'Ducky One 3', 'The Ducky One 3 features premium Cherry MX switches, PBT keycaps, and customizable RGB lighting for a high-quality typing experience.', 139.99, 'keyboard', 'img/Keyboard/13591_63dac2f683383_One-3-Mini-Classic-removebg-preview.png', 125),
(8, 'Razer DeathAdder', 'The Razer DeathAdder Essential Wired Optical Gaming Mouse features a true 6,400 DPI optical sensor for fast and precise swipes, an ergonomic form for comfortable gaming, and 5 programmable buttons with macro functions.', 49.99, 'mouse', 'img/Mouse/Razer_DeathAdder_Essential_Wired_Optical_Gaming_Mouse_for_PC_5_Buttons.png', 100),
(9, 'Logitech G502', 'The Logitech G502 HERO features a 25,600 DPI sensor, 11 programmable buttons, and adjustable weights for precision and comfort.', 79.99, 'mouse', 'img/Mouse/Logitech_G502_HERO_Wired_822a88cb-a1ca-46ea-8f62-c7a3abe077d0_530x_2x-removebg-preview.png', 130),
(10, 'Corsair Dark Core', 'The Corsair Dark Core RGB features a 16,000 DPI sensor, wireless connectivity, and customizable RGB lighting for versatile gaming.', 89.99, 'mouse', 'img/Mouse/Untitled-5-1-800x800-removebg-preview.png', 150),
(11, 'SteelSeries Rival', 'The SteelSeries Rival 600 features a dual-sensor system, 12,000 DPI, and customizable weight for precise control.', 79.99, 'mouse', 'img/Mouse/20_ed685340-22e0-49a2-ad34-a14a7f4252ce-removebg-preview.png', 160),
(12, 'HyperX Pulsefire', 'The HyperX Pulsefire Surge offers a 16,000 DPI sensor, RGB lighting, and ergonomic design for competitive gaming.', 59.99, 'mouse', 'img/Mouse/hyperx_pulsefire_surge_1_main-removebg-preview.png', 180),
(13, 'Razer Basilisk', 'The Razer Basilisk V3 features a 26,000 DPI sensor, customizable scroll wheel, and 11 programmable buttons for ultimate control.', 69.99, 'mouse', 'img/Mouse/__8886419332831-removebg-preview.png', 200),
(14, 'Razer Kraken', 'The Razer Kraken Ultimate features THX Spatial Audio, a noise-canceling mic, and cooling gel ear cushions for immersive gaming.', 129.99, 'headset', 'img/Headsets/__8886419378570-removebg-preview.png', 150),
(15, 'Logitech G Pro X', 'The Logitech G Pro X offers Blue VO!CE mic technology, DTS Headphone:X 2.0, and memory foam earpads for professional-grade audio.', 129.99, 'headset', 'img/Headsets/4_cf7047c1-bb60-4bc4-8198-147418dced24-removebg-preview.png', 150),
(16, 'Corsair Void', 'The Corsair Void RGB Elite Wireless features 7.1 surround sound, a low-latency wireless connection, and breathable microfiber mesh.', 99.99, 'headset', 'img/Headsets/639b659665154c75afa320479ac4c8c0-removebg-preview.png', 125),
(17, 'SteelSeries Arctis', 'The SteelSeries Arctis 7 features lossless wireless audio, a ClearCast mic, and AirWeave ear cushions for all-day comfort.', 149.99, 'headset', 'img/Headsets/1_35637f36-231a-44c9-8e8e-4efd84e5a534-removebg-preview (1).png', 110),
(18, 'HyperX Cloud', 'The HyperX Cloud Alpha offers dual-chamber drivers, a detachable noise-canceling mic, and durable aluminum frame for superior audio.', 99.99, 'headset', 'img/Headsets/HyperX-Cloud-II-Gaming-Headset_c8f9b99e-5848-4d23-b260-8e70fae00d0d.2b03fe7f1ea05e90d351a3412d7eb94c-removebg-preview.png', 190),
(19, 'Sennheiser GSP', 'The Sennheiser GSP 600 features noise-canceling audio, an adjustable headband, and a broadcast-quality mic for professional gaming.', 149.99, 'headset', 'img/Headsets/slide21_8_1-removebg-preview (1).png', 170);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
