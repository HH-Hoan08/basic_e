-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 14, 2026 at 10:57 AM
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
-- Database: `basic_shop`
--

-- --------------------------------------------------------
-- Bảng admins ĐÃ ĐƯỢC XÓA BỎ THEO YÊU CẦU
-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `website`, `is_active`, `sort_order`) VALUES
(1, 'Levis', 'levis', 'brand_levis.png', 'https://www.levi.com', 1, 1),
(2, 'Adidas', 'adidas', 'brand_adidas.png', 'https://www.adidas.com', 1, 2),
(3, 'Nike', 'nike', 'brand_nike.png', 'https://www.nike.com', 1, 3),
(4, 'H&M', 'hm', 'brand_hm.png', 'https://www.hm.com', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `gender` enum('all','male','female','unisex') NOT NULL DEFAULT 'all',
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `gender`, `image`, `icon`, `is_active`, `sort_order`) VALUES
(1, NULL, 'Cua nam', 'cua-nam', 'male', NULL, NULL, 1, 1),
(2, NULL, 'Cua nu', 'cua-nu', 'female', NULL, NULL, 1, 2),
(3, NULL, 'Unisex', 'unisex', 'unisex', NULL, NULL, 1, 3),
(10, 1, 'Ao thun nam', 'ao-thun-nam', 'male', NULL, NULL, 1, 1),
(11, 1, 'Ao so mi nam', 'ao-so-mi-nam', 'male', NULL, NULL, 1, 2),
(12, 1, 'Ao polo nam', 'ao-polo-nam', 'male', NULL, NULL, 1, 3),
(13, 1, 'Ao khoac nam', 'ao-khoac-nam', 'male', NULL, NULL, 1, 4),
(15, 1, 'Quan jeans nam', 'quan-jeans-nam', 'male', NULL, NULL, 1, 5),
(16, 1, 'Quan short nam', 'quan-short-nam', 'male', NULL, NULL, 1, 6),
(18, 1, 'Quan the thao nam', 'quan-the-thao-nam', 'male', NULL, NULL, 1, 7),
(30, 2, 'Ao thun nu', 'ao-thun-nu', 'female', NULL, NULL, 1, 1),
(31, 2, 'Ao so mi nu', 'ao-so-mi-nu', 'female', NULL, NULL, 1, 2),
(32, 2, 'Ao croptop', 'ao-croptop', 'female', NULL, NULL, 1, 3),
(33, 2, 'Ao khoac nu', 'ao-khoac-nu', 'female', NULL, NULL, 1, 4),
(35, 2, 'Quan jeans nu', 'quan-jeans-nu', 'female', NULL, NULL, 1, 5),
(38, 2, 'Chan vay', 'chan-vay', 'female', NULL, NULL, 1, 6),
(39, 2, 'Dam va Vay', 'dam-vay-lien', 'female', NULL, NULL, 1, 7),
(50, 3, 'Ao hoodie', 'ao-hoodie', 'unisex', NULL, NULL, 1, 1),
(53, 3, 'Sneakers', 'sneakers', 'unisex', NULL, NULL, 1, 2),
(54, 3, 'Balo', 'balo', 'unisex', NULL, NULL, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) NOT NULL,
  `sent_by` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `recipient_type` varchar(50) NOT NULL DEFAULT 'all',
  `sent_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `voucher_id` int(11) DEFAULT NULL,
  `confirmed_by` int(11) DEFAULT NULL,
  `total_price` decimal(12,0) NOT NULL,
  `discount_amount` decimal(12,0) NOT NULL DEFAULT 0,
  `status` enum('pending','confirmed','shipping','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `ordered_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `voucher_id`, `confirmed_by`, `total_price`, `discount_amount`, `status`, `ordered_at`) VALUES
(5, 12, NULL, NULL, 33650000, 0, 'cancelled', '2026-06-12 18:53:05'),
(6, 12, NULL, NULL, 12490000, 0, 'confirmed', '2026-06-12 18:53:22'),
(7, 12, NULL, NULL, 12530000, 0, 'delivered', '2026-06-12 19:24:47');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 5, 1, 2, 1290000),
(2, 5, 12, 13, 2390000),
(3, 6, 2, 2, 1790000),
(4, 6, 4, 1, 1890000),
(5, 6, 6, 1, 1990000),
(6, 6, 3, 1, 1350000),
(7, 6, 1, 1, 1290000),
(8, 6, 12, 1, 2390000),
(9, 7, 2, 2, 1790000),
(10, 7, 2, 5, 1790000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `gender` enum('male','female','unisex') NOT NULL DEFAULT 'unisex',
  `name` varchar(255) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,0) NOT NULL,
  `sale_price` decimal(12,0) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `avg_rating` decimal(2,1) NOT NULL DEFAULT 0.0,
  `sold_count` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `created_by`, `gender`, `name`, `brand`, `slug`, `description`, `price`, `sale_price`, `stock`, `image`, `is_active`, `is_featured`, `avg_rating`, `sold_count`, `created_at`, `updated_at`) VALUES
(1, 15, 1, 1, 'male', 'Levis 511 Slim Fit Jeans', 'Levis', 'levis-511-slim-fit', 'Quan jeans nam Levis 511 form slim fit kinh dien, chat denim co gian nhe 4 chieu.', 1590000, 1290000, 79, '1781267786_Levis 501 Original Straight.webp', 1, 1, 4.8, 321, '2026-06-07 09:48:39', '2026-06-12 19:36:26'),
(2, 15, 1, 1, 'male', 'Levis 501 Original Straight', 'Levis', 'levis-501-original', 'Quan jeans Levis 501 huyen thoai, dang straight co dien khong bao gio loi mot.', 1790000, NULL, 51, 'levis_501_straight.jpg', 1, 1, 4.9, 289, '2026-06-07 09:48:39', '2026-06-12 19:24:47'),
(3, 35, 1, 1, 'female', 'Levis 721 High Rise Skinny', 'Levis', 'levis-721-skinny', 'Quan jeans nu Levis 721 lung cao, form skinny ton dang toi da.', 1690000, 1350000, 89, 'levis_721_skinny.jpg', 1, 1, 4.8, 411, '2026-06-07 09:48:39', '2026-06-12 18:53:22'),
(4, 13, 1, 1, 'male', 'Levis Trucker Jacket Classic', 'Levis', 'levis-trucker-jacket', 'Ao jacket denim Levis Trucker kinh dien tu thap nien 60.', 2290000, 1890000, 39, 'levis_trucker_jacket.jpg', 1, 1, 4.9, 151, '2026-06-07 09:48:39', '2026-06-12 18:53:22'),
(5, 10, 1, 1, 'male', 'Levis Graphic Tee Logo', 'Levis', 'levis-graphic-tee', 'Ao thun nam Levis in logo co dien, chat cotton 100% mem mai.', 590000, 490000, 120, 'levis_graphic_tee.jpg', 1, 0, 4.4, 260, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(6, 53, 2, 1, 'unisex', 'Adidas Stan Smith Sneakers', 'Adidas', 'adidas-stan-smith', 'Giay Adidas Stan Smith huyen thoai mau trang voi logo 3 la xanh la kinh dien.', 2490000, 1990000, 59, 'adidas_stan_smith.jpg', 1, 1, 4.9, 521, '2026-06-07 09:48:39', '2026-06-12 18:53:22'),
(7, 53, 2, 1, 'unisex', 'Adidas Ultraboost 22', 'Adidas', 'adidas-ultraboost-22', 'Giay chay bo Adidas Ultraboost 22 voi dem Boost sieu nhe tra luc toi da.', 3990000, 3290000, 40, 'adidas_ultraboost_22.jpg', 1, 1, 4.8, 380, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(8, 53, 2, 1, 'unisex', 'Adidas Superstar Shell Toe', 'Adidas', 'adidas-superstar', 'Giay Adidas Superstar voi mui giay hinh vo so dac trung.', 2290000, NULL, 50, 'adidas_superstar.jpg', 1, 1, 4.7, 290, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(9, 50, 2, 1, 'unisex', 'Adidas Trefoil Hoodie', 'Adidas', 'adidas-trefoil-hoodie', 'Ao hoodie Adidas Originals voi logo Trefoil theu noi bat.', 1290000, 990000, 100, 'adidas_trefoil_hoodie.jpg', 1, 1, 4.6, 420, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(10, 10, 2, 1, 'male', 'Adidas Essentials 3-Stripes Tee', 'Adidas', 'adidas-3stripes-tee', 'Ao thun nam Adidas Essentials voi 3 soc dac trung o tay ao.', 690000, 550000, 150, 'adidas_3stripes_tee.jpg', 1, 0, 4.5, 340, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(11, 54, 2, 1, 'unisex', 'Adidas Classic Backpack', 'Adidas', 'adidas-classic-backpack', 'Balo Adidas Classic dung tich 21L, chong nuoc nhe.', 990000, NULL, 70, 'adidas_classic_backpack.jpg', 1, 0, 4.5, 210, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(12, 53, 3, 1, 'unisex', 'Nike Air Force 1 Low White', 'Nike', 'nike-air-force-1', 'Giay Nike Air Force 1 Low mau trang toan than bieu tuong.', 2790000, 2390000, 69, 'nike_af1_white.jpg', 1, 1, 4.9, 681, '2026-06-07 09:48:39', '2026-06-12 18:59:46'),
(13, 53, 3, 1, 'unisex', 'Nike Air Max 270', 'Nike', 'nike-air-max-270', 'Giay Nike Air Max 270 voi buong Air lon nhat, de phan luc em ai.', 3490000, 2890000, 50, 'nike_airmax_270.jpg', 1, 1, 4.8, 390, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(14, 53, 3, 1, 'female', 'Nike Court Legacy Lift', 'Nike', 'nike-court-legacy', 'Giay nu Nike Court Legacy Lift de platform cao 4cm ton dang.', 2490000, NULL, 45, 'nike_court_legacy.jpg', 1, 1, 4.7, 310, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(15, 10, 3, 1, 'male', 'Nike Dri-FIT Legend Tee', 'Nike', 'nike-dri-fit-tee', 'Ao thun nam Nike Dri-FIT cong nghe thoat mo hoi toc do nhanh.', 790000, 650000, 130, 'nike_dri_fit_tee.jpg', 1, 0, 4.5, 450, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(16, 30, 3, 1, 'female', 'Nike Sportswear Crop Top', 'Nike', 'nike-crop-top', 'Ao croptop nu Nike Sportswear Essential, logo Swoosh theu noi.', 890000, 720000, 100, 'nike_crop_top.jpg', 1, 1, 4.6, 380, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(17, 13, 3, 1, 'male', 'Nike Windrunner Jacket', 'Nike', 'nike-windrunner', 'Ao khoac gio Nike Windrunner voi duong may hinh chu V dac trung.', 1890000, 1590000, 55, 'nike_windrunner.jpg', 1, 1, 4.7, 240, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(18, 54, 3, 1, 'unisex', 'Nike Heritage Backpack', 'Nike', 'nike-heritage-backpack', 'Balo Nike Heritage Eugene dung tich 25L, dung laptop 15 inch.', 1290000, NULL, 55, 'nike_heritage_backpack.jpg', 1, 0, 4.5, 175, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(19, 10, 4, 1, 'male', 'H&M Slim Fit Cotton T-shirt', 'H&M', 'hm-slim-fit-tshirt', 'Ao thun nam H&M slim fit chat cotton 100% mem min.', 299000, NULL, 200, 'hm_slim_tshirt.jpg', 1, 0, 4.3, 580, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(20, 11, 4, 1, 'male', 'H&M Regular Fit Oxford Shirt', 'H&M', 'hm-oxford-shirt', 'Ao so mi nam H&M chat Oxford cotton, co button-down lich su.', 599000, 499000, 90, 'hm_oxford_shirt.jpg', 1, 1, 4.4, 270, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(21, 30, 4, 1, 'female', 'H&M Fitted Rib-knit T-shirt', 'H&M', 'hm-rib-knit-tshirt', 'Ao thun nu H&M chat rib-knit co gian om dang.', 249000, NULL, 180, 'hm_rib_knit_tshirt.jpg', 1, 1, 4.5, 620, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(22, 32, 4, 1, 'female', 'H&M Cropped Cotton Jersey Top', 'H&M', 'hm-cropped-top', 'Ao croptop nu H&M cotton jersey nhe mem, do dai crop vua phai.', 279000, 219000, 160, 'hm_cropped_top.jpg', 1, 0, 4.2, 440, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(23, 39, 4, 1, 'female', 'H&M Floral Wrap Dress', 'H&M', 'hm-floral-wrap-dress', 'Dam wrap hoa nhi H&M duyen dang, chat viscose mem ru.', 699000, 549000, 75, 'hm_floral_wrap_dress.jpg', 1, 1, 4.6, 390, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(24, 39, 4, 1, 'female', 'H&M Linen-blend Shirt Dress', 'H&M', 'hm-linen-dress', 'Dam so mi H&M chat linen pha cotton, dang midi thanh lich.', 799000, NULL, 60, 'hm_linen_dress.jpg', 1, 0, 4.3, 210, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(25, 35, 4, 1, 'female', 'H&M Wide Leg High-waist Jeans', 'H&M', 'hm-wide-leg-jeans', 'Quan jeans nu H&M ong rong lung cao, form wide leg thoi thuong.', 799000, 649000, 85, 'hm_wide_leg_jeans.jpg', 1, 1, 4.5, 310, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(26, 33, 4, 1, 'female', 'H&M Oversized Blazer', 'H&M', 'hm-oversized-blazer', 'Blazer nu H&M form oversized, chat tweed nhe 1 khuy 2 tui.', 1299000, 990000, 50, 'hm_oversized_blazer.jpg', 1, 1, 4.7, 185, '2026-06-07 09:48:39', '2026-06-07 09:48:39'),
(27, 50, 4, 1, 'unisex', 'H&M Relaxed Fit Hoodie', 'H&M', 'hm-relaxed-hoodie', 'Ao hoodie H&M form relaxed, chat French terry bong mem am.', 799000, 649000, 120, 'hm_relaxed_hoodie.jpg', 1, 0, 4.4, 360, '2026-06-07 09:48:39', '2026-06-07 09:48:39');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(10) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `color_hex` varchar(7) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `extra_price` decimal(12,0) NOT NULL DEFAULT 0,
  `sku` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revenue_reports`
--

CREATE TABLE `revenue_reports` (
  `id` int(11) NOT NULL,
  `generated_by` int(11) NOT NULL,
  `period` enum('daily','weekly','monthly','yearly') NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `total_revenue` decimal(15,0) NOT NULL DEFAULT 0,
  `total_orders` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `moderated_by` int(11) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `managed_by` int(11) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `membership` enum('silver','gold','diamond') NOT NULL DEFAULT 'silver',
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `username` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'default.png',
  `role` varchar(50) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `managed_by`, `fullname`, `email`, `password`, `membership`, `is_locked`, `created_at`, `username`, `image`, `role`) VALUES
(2, NULL, 'Tran Thi B', 'user2@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gold', 0, '2026-06-07 09:48:38', NULL, 'default.png', 'user'),
(3, NULL, 'Le Van C', 'user3@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'silver', 0, '2026-06-07 09:48:38', NULL, 'default.png', 'user'),
(7, NULL, 'Dung Thu Lang', 'DTL@ne', '1234', 'silver', 0, '2026-06-09 04:43:42', 'Dung', 'default.png', 'user'),
(10, NULL, 'DungSL', 'vana.nguyen@gmail.com', '$2y$10$3fhbCxlTcO8n7GE1in.EmeiWYbbBT9u/eVrIx1IG2ekjsHywqTmh2', 'silver', 0, '2026-06-10 17:00:52', 'DungSL', 'default.png', 'user'),
(11, NULL, 'hoan', 'hoan@gmail.com', '$2y$10$LlNRWD6ELiIBKlTotWtGRuPEH/Tvsouaiu6H5VAUpNwal8nBsg8Ca', 'silver', 0, '2026-06-11 11:01:01', 'hoan', '1781150922_meme.jpeg', 'admin'),
(12, NULL, 'test123', 'test123@gmail.com', '$2y$10$.bLV3AmuLq6pgoJop07Fv.vkB1aU0YR9L6J7xGj0y/xrGhEGdKSDG', 'silver', 0, '2026-06-12 18:08:45', 'test123', 'default.png', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('percent','fixed') NOT NULL DEFAULT 'percent',
  `value` decimal(10,0) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `created_by`, `code`, `type`, `value`, `quantity`, `used_count`, `expires_at`, `is_active`) VALUES
(1, 1, 'WELCOME20', 'percent', 20, 100, 0, '2025-12-31', 1),
(2, 1, 'SAVE50K', 'fixed', 50000, 50, 0, '2025-06-30', 1),
(3, 1, 'VIP30', 'percent', 30, 30, 0, '2026-12-31', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cart` (`user_id`,`product_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_cat_parent` (`parent_id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_email_admin` (`sent_by`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_user` (`user_id`),
  ADD KEY `fk_order_voucher` (`voucher_id`),
  ADD KEY `fk_order_admin` (`confirmed_by`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_order` (`order_id`),
  ADD KEY `fk_item_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_brand` (`brand_id`),
  ADD KEY `idx_gender` (`gender`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `fk_product_admin` (`created_by`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pimg_product` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_variant_product` (`product_id`);

--
-- Indexes for table `revenue_reports`
--
ALTER TABLE `revenue_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_report_admin` (`generated_by`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_review_user` (`user_id`),
  ADD KEY `fk_review_product` (`product_id`),
  ADD KEY `fk_review_admin` (`moderated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_user_admin` (`managed_by`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `fk_voucher_admin` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revenue_reports`
--
ALTER TABLE `revenue_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_cat_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_item_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_pimg_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_variant_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_review_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;