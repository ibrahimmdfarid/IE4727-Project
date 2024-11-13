-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 02:42 AM
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
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_item_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_item_id`, `user_id`, `product_id`, `quantity`) VALUES
(25, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Mobile Devices'),
(2, 'Laptops'),
(3, 'Tablets');

-- --------------------------------------------------------

--
-- Table structure for table `contact_enquiries`
--

CREATE TABLE `contact_enquiries` (
  `enquiry_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `enquiry` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_enquiries`
--

INSERT INTO `contact_enquiries` (`enquiry_id`, `user_id`, `enquiry`) VALUES
(1, 1, 'Subject: Test\nBody: This is to test if the enquiry will be inserted into the database table.');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `status`, `total_amount`, `shipping_address`) VALUES
(1, 1, '2024-11-04 12:16:10', 'pending', 999.00, NULL),
(2, 1, '2024-11-04 12:30:56', 'pending', 12480.00, NULL),
(3, 1, '2024-11-04 13:06:47', 'pending', 3993.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 999.00),
(2, 2, 1, 5, 999.00),
(3, 2, 2, 15, 499.00),
(4, 3, 1, 1, 999.00),
(5, 3, 2, 6, 499.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock_quantity`, `category_id`, `image_url`) VALUES
(1, 'iPad', 'The iPad is a versatile tablet developed by Apple, offering a sleek, lightweight design with a high-resolution Retina display. It combines the power of a computer with the convenience of a portable device, making it ideal for tasks ranging from web browsing and media consumption to productivity and creative work. Powered by Apple\'s custom chips, such as the A-series or M-series, the iPad delivers impressive performance for apps, games, and multitasking. It supports a range of accessories, including the Apple Pencil and Magic Keyboard, transforming it into a tool for note-taking, drawing, and even full-fledged productivity tasks. Available in multiple sizes and configurations, the iPad runs on iPadOS, offering a fluid and user-friendly interface with access to a vast ecosystem of apps through the App Store. Whether for work, school, or entertainment, the iPad is designed to cater to a wide range of needs with both power and portability', 999.00, 1000, 3, 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSnV4gEnyCqLl-HKYqkQHH8VbXoKsOxFzSdDRGCT1F2FvwYw1cHCQ2XCW9uiVTj-b7UIma5YPzd0vRrXd6b564WIO48Hohn_Q'),
(2, 'iPhone', 'The iPhone is a line of smartphones designed and manufactured by Apple, renowned for its sleek design, cutting-edge technology, and seamless integration with Apple\'s ecosystem. Since its launch in 2007, the iPhone has set the standard for mobile devices, featuring a high-quality Retina display, advanced camera systems, and powerful processors, such as the A-series chips. It runs on iOS, a user-friendly operating system that offers smooth performance, security, and access to millions of apps via the App Store. The iPhone is known for its innovations in mobile technology, including Face ID, wireless charging, and 5G connectivity. With a range of models catering to different preferences and budgets, the iPhone combines powerful hardware with an intuitive software experience, making it a popular choice for users worldwide for communication, entertainment, productivity, and more.', 499.00, 1000, 1, 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcStHUd94afMgsQL7g6hps2jF4mT3VI5OL7JUaIScElRADmaFSheSbz7hSAOFjD9TTGRq7UPI6h-NEeEut9OGAZMC5u9iW8aoP46clEszIiZlGTYYxlQmFddggM'),
(3, 'MacBook', 'The MacBook is a line of laptops designed by Apple, combining sleek, ultra-portable designs with powerful performance and long battery life. Available in various models, including the MacBook Air and MacBook Pro, these laptops are known for their Retina displays, delivering crisp, vibrant visuals with True Tone technology for a more natural viewing experience. The MacBook is powered by Apple’s custom silicon chips, such as the M1, M2, or M3, which provide impressive speed and energy efficiency, enabling users to handle everything from basic tasks to demanding creative workflows with ease. With macOS as the operating system, MacBooks offer a smooth, intuitive user experience with tight integration into the Apple ecosystem, allowing seamless connections with devices like the iPhone, iPad, and Apple Watch. The MacBook also features a comfortable keyboard, a precision trackpad, and enhanced security through Touch ID or Face ID, depending on the model. Whether for work, school, or entertainment, the MacBook is designed to provide a premium computing experience in a thin, lightweight form factor, perfect for users who need both portability and power.', 1099.00, 120, 2, 'https://store.storeimages.cdn-apple.com/8756/as-images.apple.com/is/mba13-midnight-config-202402?wid=820&hei=498&fmt=jpeg&qlt=90&.v=1708371033110');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `card_details` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `name`, `address`, `card_details`) VALUES
(1, 'tey.zijian@gmail.com', '$2y$10$MDD/N3Uclzu/KHT4O4uDg.3rYK0C9bKxYT6lr9PcNrdU/BOGjL9mu', 'Tey Zijian', '12345 Main City Street', '1234567891234561'),
(7, 'admin@project.com', '$2y$10$8GQDYYmgOIXk3FXLdRecNOMB1ONcih7ZqqFBh8bT9NrRlDEMjrQb6', 'admin', '12345', '1234567891234567');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  ADD PRIMARY KEY (`enquiry_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  MODIFY `enquiry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  ADD CONSTRAINT `contact_enquiries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
