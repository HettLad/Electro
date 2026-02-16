-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 11:52 AM
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
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(1, 3, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Electronic'),
(2, 'Kitchen'),
(3, 'Sports'),
(4, 'Clothing');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','paid','shipped','delivered') DEFAULT 'pending',
  `address` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `address`, `created_at`) VALUES
(7, 2, 10000.00, 'delivered', '8, Dharmayug Society, Near Blind School, Opp. Tribhuvan Complex, Ghod dod Road, Surat.', '2026-02-16 08:13:18'),
(8, 2, 7999.00, 'paid', '8, Dharmayug Society, Near Blind School, Opp. Tribhuvan Complex, Ghod dod Road, Surat.', '2026-02-16 09:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(7, 7, 1, 2, 5000.00),
(8, 8, 22, 1, 7999.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','success','failed') DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `payment_status`, `transaction_id`, `created_at`) VALUES
(1, 7, 'razorpay', 'success', 'pay_SGkFfIG1V26Cux', '2026-02-16 08:13:18'),
(2, 8, 'razorpay', 'success', 'pay_SGlyvxSnjy3R3s', '2026-02-16 09:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `image`, `stock`, `created_at`) VALUES
(1, 'Headphone', 'best quality product', 5000.00, 1, 'asus.jpg', 0, '2026-02-16 06:11:38'),
(22, 'Coffee Maker', 'Automatic coffee machine.', 7999.00, 2, 'coffee_maker.jpg', 12, '2026-02-16 09:33:12'),
(52, 'iPhone 14 Pro', 'Apple smartphone with A16 chip and 48MP camera.', 129999.00, 1, 'iphone14pro.jpg', 20, '2026-02-16 10:06:33'),
(53, 'Samsung Galaxy S23', 'Flagship Android phone with Snapdragon processor.', 89999.00, 1, 'galaxy_s23.jpg', 25, '2026-02-16 10:06:33'),
(54, 'OnePlus 11', 'Fast performance smartphone with AMOLED display.', 56999.00, 1, 'oneplus11.jpg', 18, '2026-02-16 10:06:33'),
(55, 'Sony WH-1000XM5', 'Noise cancelling premium headphones.', 24999.00, 1, 'sony_xm5.jpg', 15, '2026-02-16 10:06:33'),
(56, 'JBL Flip 6', 'Portable Bluetooth speaker waterproof.', 8999.00, 1, 'jbl_flip6.jpg', 30, '2026-02-16 10:06:33'),
(57, 'Dell XPS 13 Laptop', 'Lightweight laptop with Intel i7 processor.', 109999.00, 1, 'dell_xps13.jpg', 10, '2026-02-16 10:06:33'),
(58, 'HP Pavilion Laptop', 'Powerful laptop for daily use.', 65999.00, 1, 'hp_pavilion.jpg', 12, '2026-02-16 10:06:33'),
(59, 'Asus Gaming Laptop', 'Gaming laptop with RTX graphics.', 119999.00, 1, 'asus_gaming.jpg', 8, '2026-02-16 10:06:33'),
(60, 'Logitech MX Mouse', 'Wireless productivity mouse.', 7999.00, 1, 'logitech_mx.jpg', 40, '2026-02-16 10:06:33'),
(61, 'Mechanical Keyboard', 'RGB mechanical gaming keyboard.', 4999.00, 1, 'keyboard.jpg', 35, '2026-02-16 10:06:33'),
(62, 'Samsung 4K TV', '55 inch Ultra HD smart TV.', 52999.00, 1, 'samsung_tv.jpg', 9, '2026-02-16 10:06:33'),
(63, 'LG OLED TV', 'Premium OLED smart TV.', 89999.00, 1, 'lg_oled.jpg', 6, '2026-02-16 10:06:33'),
(64, 'Amazon Echo Dot', 'Smart speaker with Alexa.', 4499.00, 1, 'echo_dot.jpg', 50, '2026-02-16 10:06:33'),
(65, 'Apple AirPods Pro', 'Wireless earbuds with ANC.', 24999.00, 1, 'airpods.jpg', 28, '2026-02-16 10:06:33'),
(66, 'Canon DSLR Camera', 'Professional photography camera.', 58999.00, 1, 'canon_dslr.jpg', 7, '2026-02-16 10:06:33'),
(67, 'Smart Watch', 'Fitness smartwatch with heart rate monitor.', 6999.00, 1, 'smartwatch.jpg', 22, '2026-02-16 10:06:33'),
(68, 'External SSD 1TB', 'Fast portable SSD storage.', 8999.00, 1, 'ssd.jpg', 18, '2026-02-16 10:06:33'),
(69, 'Monitor 27 Inch', 'Full HD monitor.', 14999.00, 1, 'monitor.jpg', 14, '2026-02-16 10:06:33'),
(70, 'Webcam HD', 'High quality webcam.', 2999.00, 1, 'webcam.jpg', 20, '2026-02-16 10:06:33'),
(71, 'Power Bank 20000mAh', 'Fast charging power bank.', 2499.00, 1, 'powerbank.jpg', 45, '2026-02-16 10:06:33'),
(72, 'Coffee Maker', 'Automatic coffee machine.', 7999.00, 2, 'coffee_maker.jpg', 12, '2026-02-16 10:06:33'),
(73, 'Mixer Grinder', 'Powerful kitchen mixer.', 4999.00, 2, 'mixer.jpg', 18, '2026-02-16 10:06:33'),
(74, 'Air Fryer', 'Oil-free cooking air fryer.', 6999.00, 2, 'air_fryer.jpg', 15, '2026-02-16 10:06:33'),
(75, 'Microwave Oven', 'Convection microwave.', 10999.00, 2, 'microwave.jpg', 10, '2026-02-16 10:06:33'),
(76, 'Toaster', 'Bread toaster.', 1999.00, 2, 'toaster.jpg', 22, '2026-02-16 10:06:33'),
(77, 'Electric Kettle', 'Fast boiling kettle.', 1499.00, 2, 'kettle.jpg', 25, '2026-02-16 10:06:33'),
(78, 'Rice Cooker', 'Automatic rice cooker.', 2999.00, 2, 'rice_cooker.jpg', 14, '2026-02-16 10:06:33'),
(79, 'Induction Cooktop', 'Electric induction stove.', 3499.00, 2, 'induction.jpg', 20, '2026-02-16 10:06:33'),
(80, 'Juicer', 'Fruit juicer machine.', 3999.00, 2, 'juicer.jpg', 16, '2026-02-16 10:06:33'),
(81, 'Blender', 'Kitchen blender.', 2499.00, 2, 'blender.jpg', 19, '2026-02-16 10:06:33'),
(82, 'Football', 'Professional football.', 999.00, 3, 'football.jpg', 50, '2026-02-16 10:06:33'),
(83, 'Cricket Bat', 'English willow bat.', 3999.00, 3, 'cricket_bat.jpg', 20, '2026-02-16 10:06:33'),
(84, 'Tennis Racket', 'Professional racket.', 2999.00, 3, 'racket.jpg', 18, '2026-02-16 10:06:33'),
(85, 'Running Shoes', 'Comfortable sports shoes.', 4999.00, 3, 'running_shoes.jpg', 25, '2026-02-16 10:06:33'),
(86, 'Gym Dumbbells', 'Fitness dumbbell set.', 2499.00, 3, 'dumbbells.jpg', 30, '2026-02-16 10:06:33'),
(87, 'Yoga Mat', 'Non-slip yoga mat.', 999.00, 3, 'yoga_mat.jpg', 40, '2026-02-16 10:06:33'),
(88, 'Basketball', 'Official basketball.', 1499.00, 3, 'basketball.jpg', 28, '2026-02-16 10:06:33'),
(89, 'Treadmill', 'Home fitness treadmill.', 24999.00, 3, 'treadmill.jpg', 5, '2026-02-16 10:06:33'),
(90, 'Skipping Rope', 'Fitness skipping rope.', 499.00, 3, 'rope.jpg', 35, '2026-02-16 10:06:33'),
(91, 'Cycling Helmet', 'Safety helmet.', 1999.00, 3, 'helmet.jpg', 20, '2026-02-16 10:06:33'),
(92, 'Men T-Shirt', 'Cotton casual t-shirt.', 999.00, 4, 'tshirt.jpg', 50, '2026-02-16 10:06:33'),
(93, 'Men Jeans', 'Denim jeans.', 1999.00, 4, 'jeans.jpg', 30, '2026-02-16 10:06:33'),
(94, 'Women Dress', 'Stylish dress.', 2499.00, 4, 'dress.jpg', 22, '2026-02-16 10:06:33'),
(95, 'Hoodie', 'Warm hoodie.', 1799.00, 4, 'hoodie.jpg', 18, '2026-02-16 10:06:33'),
(96, 'Jacket', 'Winter jacket.', 3499.00, 4, 'jacket.jpg', 12, '2026-02-16 10:06:33'),
(97, 'Sneakers', 'Casual sneakers.', 2999.00, 4, 'sneakers.jpg', 25, '2026-02-16 10:06:33'),
(98, 'Formal Shirt', 'Office wear shirt.', 1499.00, 4, 'formal_shirt.jpg', 20, '2026-02-16 10:06:33'),
(99, 'Track Pants', 'Sports track pants.', 1299.00, 4, 'trackpants.jpg', 24, '2026-02-16 10:06:33'),
(100, 'Cap', 'Stylish cap.', 499.00, 4, 'cap.jpg', 40, '2026-02-16 10:06:33'),
(101, 'Socks Pack', 'Pack of socks.', 399.00, 4, 'socks.jpg', 60, '2026-02-16 10:06:33');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`) VALUES
(1, 2, 1, 3, 'it is amazing product', '2026-02-16 07:05:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','blocked') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `status`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.ogJt4v9giU2Y0Qya', 'admin', '2026-02-16 04:43:42', 'active'),
(2, 'Hett', 'hettplad06@gmail.com', '$2y$10$1uG0MWdRuZMREjbOxAM6d.rv3I03EhQs2SfNUy.DE8Pr2Gw5f3on6', 'customer', '2026-02-16 05:30:28', 'active'),
(3, 'neel', 'neel@gmail.com', '$2y$10$Wnzl3UG.fcQRapDZgAs6Hee1y6eVnr4en1BQ6C1ipX.5beHwDGT9W', 'admin', '2026-02-16 05:44:30', 'active'),
(4, 'Parv', 'shahparv4327@gmail.com', '$2y$10$AhIzAkQUJGAYoVGAPK7i.uQupjDC1eR60yrNK5/oOQvO4jNLq1Nje', 'customer', '2026-02-16 10:48:09', 'active'),
(5, 'jhon deo', 'jhon@gmail.com', '$2y$10$MrhfwEd/VwUizwIJD00rb.sWC2M.Ac2tZoRN/km.kmsAG8jinxuy.', 'customer', '2026-02-16 10:48:50', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
