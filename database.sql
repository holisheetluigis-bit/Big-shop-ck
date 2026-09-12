-- Ck Shop168 Database Schema & Initial Data
CREATE DATABASE IF NOT EXISTS `ck_shop` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ck_shop`;

-- Users Table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('customer', 'admin') DEFAULT 'customer',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `icon` VARCHAR(50) DEFAULT 'fa-laptop',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Products Table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL,
    `description` TEXT,
    `price` DECIMAL(10, 2) NOT NULL,
    `old_price` DECIMAL(10, 2) DEFAULT NULL,
    `rating` DECIMAL(2, 1) DEFAULT 5.0,
    `reviews_count` INT DEFAULT 0,
    `image` VARCHAR(255) NOT NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `stock` INT DEFAULT 50,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Orders Table
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT DEFAULT NULL,
    `order_number` VARCHAR(50) NOT NULL UNIQUE,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `company` VARCHAR(100) DEFAULT NULL,
    `address` VARCHAR(255) NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `country` VARCHAR(100) NOT NULL,
    `postcode` VARCHAR(20) NOT NULL,
    `phone` VARCHAR(30) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `notes` TEXT DEFAULT NULL,
    `shipping_method` VARCHAR(50) DEFAULT 'Free Shipping',
    `shipping_cost` DECIMAL(10, 2) DEFAULT 0.00,
    `payment_method` VARCHAR(50) DEFAULT 'Cash On Delivery',
    `subtotal` DECIMAL(10, 2) NOT NULL,
    `total_amount` DECIMAL(10, 2) NOT NULL,
    `status` ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Order Items Table
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT DEFAULT NULL,
    `product_name` VARCHAR(150) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `quantity` INT NOT NULL,
    `total` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Contact Messages Table
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(30) DEFAULT NULL,
    `project` VARCHAR(100) DEFAULT NULL,
    `subject` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default Admin Account (Username: admin, Password: admin123)
INSERT INTO `users` (`username`, `email`, `password`, `role`)
VALUES ('admin', 'admin@ckshop.com', '$2y$10$/nBxAq./cZnyBsiHsZ77JOHm3zPsyOhjvkX.EgbVUmIkjLHqzxdH.', 'admin')
ON DUPLICATE KEY UPDATE `password`=VALUES(`password`), `role`='admin';

-- Seed Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`) VALUES
(1, 'Accessories', 'accessories', 'fa-headphones'),
(2, 'Electronics & Computer', 'electronics-computer', 'fa-desktop'),
(3, 'Laptops & Desktops', 'laptops-desktops', 'fa-laptop'),
(4, 'Mobiles & Tablets', 'mobiles-tablets', 'fa-mobile-alt'),
(5, 'SmartPhone & Smart TV', 'smartphone-smart-tv', 'fa-tv'),
(6, 'Cameras & Audio', 'cameras-audio', 'fa-camera')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Seed Products using existing assets in img/
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `old_price`, `rating`, `reviews_count`, `image`, `is_featured`, `stock`) VALUES
(1, 4, 'Apple iPhone 6', 'apple-iphone-6', 'High-performance Apple smartphone featuring Retina display and A8 chip with sleek design.', 269.00, 399.00, 4.8, 124, 'img/product-1.png', 1, 35),
(2, 6, 'Canon EOS 5D DSLR', 'canon-eos-5d', 'Professional full-frame DSLR camera offering exceptional image resolution and 4K recording.', 899.00, 1199.00, 4.9, 86, 'img/product-2.png', 1, 15),
(3, 6, 'Sigma 24-70mm f/2.8 Lens', 'sigma-24-70mm-lens', 'Versatile zoom lens engineered for outstanding optical performance across all focal lengths.', 450.00, 599.00, 4.7, 42, 'img/product-3.png', 1, 20),
(4, 1, 'Sony WH-1000XM3', 'sony-wh-1000xm3', 'Industry-leading noise canceling wireless headphones with premium sound and comfort.', 299.00, 349.00, 4.9, 210, 'img/product-4.png', 1, 40),
(5, 6, 'Polaroid OneStep 2', 'polaroid-onestep-2', 'Analog instant camera combining classic vintage look with contemporary photographic fun.', 99.00, 129.00, 4.6, 68, 'img/product-5.png', 0, 50),
(6, 4, 'Samsung Galaxy S21', 'samsung-galaxy-s21', 'Dynamic AMOLED 2X display, 8K video capture, and high performance processor.', 699.00, 799.00, 4.8, 195, 'img/product-6.png', 1, 28),
(7, 3, 'Dell XPS 13 Laptop', 'dell-xps-13-laptop', 'InfinityEdge display, 11th Gen Intel Core processors, with premium aluminum chassis.', 999.00, 1299.00, 4.7, 54, 'img/product-7.png', 1, 12),
(8, 5, 'Sony 55 Inch 4K Smart TV', 'sony-55-inch-4k-smart-tv', 'Ultra HD HDR smart television with Google TV and cinematic Dolby Atmos audio.', 749.00, 899.00, 4.8, 77, 'img/product-8.png', 1, 10),
(9, 1, 'Logitech MX Master 3', 'logitech-mx-master-3', 'Advanced wireless mouse designed for coders and creatives with hyper-fast scrolling.', 79.00, 99.00, 4.9, 312, 'img/product-9.png', 0, 65),
(10, 3, 'Apple MacBook Pro 14', 'apple-macbook-pro-14', 'Powered by Apple Silicon with Liquid Retina XDR display and groundbreaking battery life.', 1599.00, 1799.00, 5.0, 140, 'img/product-10.png', 1, 8),
(11, 2, 'Gaming Desktop RTX 3080', 'gaming-desktop-rtx-3080', 'Ultimate gaming tower with liquid cooling, 32GB RAM, and blazing-fast NVMe storage.', 1499.00, 1899.00, 4.8, 39, 'img/product-11.png', 0, 7),
(12, 1, 'Apple Watch Series 7', 'apple-watch-series-7', 'Always-On Retina display, crack-resistant front crystal, and advanced health tracking.', 329.00, 399.00, 4.7, 185, 'img/product-12.png', 1, 45),
(13, 6, 'GoPro HERO 10 Black', 'gopro-hero-10-black', 'Revolutionary GP2 processor records 5.3K video with HyperSmooth 4.0 stabilization.', 399.00, 499.00, 4.8, 92, 'img/product-13.png', 0, 22),
(14, 1, 'Bose SoundLink Revolve+', 'bose-soundlink-revolve-plus', 'Deep, loud, and immersive 360-degree wireless portable Bluetooth speaker.', 199.00, 249.00, 4.6, 73, 'img/product-14.png', 0, 30),
(15, 4, 'iPad Air 5th Gen', 'ipad-air-5th-gen', 'With the breakthrough Apple M1 chip, 12MP Ultra Wide front camera with Center Stage.', 549.00, 599.00, 4.9, 118, 'img/product-15.png', 1, 19),
(16, 1, 'Anker PowerCore 26800', 'anker-powercore-26800', 'Massive capacity portable charger with dual input ports and 3 high-speed USB outputs.', 49.00, 65.00, 4.7, 340, 'img/product-16.png', 0, 80),
(17, 2, 'ASUS ROG 27 165Hz Monitor', 'asus-rog-27-monitor', 'WQHD Fast IPS gaming monitor with NVIDIA G-SYNC compatibility and 1ms response time.', 349.00, 429.00, 4.8, 64, 'img/product-17.png', 0, 16),
(18, 1, 'HyperX Cloud II Headset', 'hyperx-cloud-ii-headset', 'Comfortable memory foam ear cushions and virtual 7.1 surround sound audio.', 89.00, 119.00, 4.8, 275, 'img/product-18.png', 0, 55)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);
