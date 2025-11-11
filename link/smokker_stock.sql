-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 11, 2025 at 09:26 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smokker_stock`
--

-- --------------------------------------------------------

--
-- Table structure for table `capital`
--

CREATE TABLE `capital` (
  `capital_id` int(11) NOT NULL,
  `capital_balance` decimal(10,2) NOT NULL,
  `count_capital` decimal(10,2) NOT NULL,
  `slip_capital` varchar(300) NOT NULL,
  `date_time_ad` datetime NOT NULL,
  `adder_id` int(11) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `capital`
--

INSERT INTO `capital` (`capital_id`, `capital_balance`, `count_capital`, `slip_capital`, `date_time_ad`, `adder_id`, `create_at`) VALUES
(26, 0.00, 50000.00, '', '2025-10-13 15:40:00', 2, '2025-10-13 15:40:11'),
(27, 10000.00, 200000.00, '', '2025-10-15 20:30:00', 2, '2025-10-15 20:30:31'),
(28, 32464.00, 500000.00, '', '2025-10-19 21:12:00', 1, '2025-10-19 21:12:24'),
(29, 12150.35, 200000.00, '', '2025-11-08 17:22:00', 1, '2025-11-08 17:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `custom_debtpaid`
--

CREATE TABLE `custom_debtpaid` (
  `id_debtpaid` int(11) NOT NULL,
  `serial_number` varchar(200) NOT NULL,
  `name_customer` varchar(200) NOT NULL,
  `count_debtpaid` decimal(10,2) NOT NULL,
  `debtpaid_balance` decimal(10,2) NOT NULL,
  `count_order_pay` int(11) NOT NULL,
  `datetime_pays` datetime NOT NULL,
  `text_reason` text NOT NULL,
  `img_debt` varchar(200) NOT NULL,
  `adder_id` int(11) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `custom_debtpaid`
--

INSERT INTO `custom_debtpaid` (`id_debtpaid`, `serial_number`, `name_customer`, `count_debtpaid`, `debtpaid_balance`, `count_order_pay`, `datetime_pays`, `text_reason`, `img_debt`, `adder_id`, `create_at`) VALUES
(18, 'GP65G84MX0', 'HISOKA MONRROW', 16000.00, 540.00, 1, '2025-11-02 23:12:00', 'test', '', 1, '2025-11-02 23:12:41'),
(19, 'MYK6DNMA1X', 'HISOKA MONRROW', 300.00, 240.00, 1, '2025-11-02 23:15:00', 'test2', '', 1, '2025-11-02 23:15:37'),
(20, 'DZC35QEQLM', 'HISOKA MONRROW', 240.00, 0.00, 1, '2025-11-02 23:16:00', 'test 34', '', 1, '2025-11-02 23:16:13');

-- --------------------------------------------------------

--
-- Table structure for table `list_productsell`
--

CREATE TABLE `list_productsell` (
  `list_sellid` int(11) NOT NULL,
  `ordersell_id` int(15) NOT NULL,
  `productname` varchar(250) NOT NULL,
  `level_selltype` int(7) NOT NULL,
  `rate_customertype` decimal(10,2) NOT NULL,
  `type_custom` varchar(100) NOT NULL,
  `tatol_product` int(11) NOT NULL,
  `price_to_pay` decimal(10,2) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `list_productsell`
--

INSERT INTO `list_productsell` (`list_sellid`, `ordersell_id`, `productname`, `level_selltype`, `rate_customertype`, `type_custom`, `tatol_product`, `price_to_pay`, `create_at`) VALUES
(673, 127, '1', 1, 520.00, 'price_customer_frontstore', 10, 5200.00, '2025-10-24 17:15:40'),
(674, 127, '5', 1, 720.00, 'price_customer_frontstore', 7, 5040.00, '2025-10-24 17:15:40'),
(675, 128, '7', 1, 310.00, 'price_levels_one', 12, 3720.00, '2025-10-30 09:20:33'),
(676, 128, '1', 1, 511.00, 'price_levels_one', 12, 6132.00, '2025-10-30 09:20:33'),
(677, 127, '10', 1, 520.00, 'price_customer_frontstore', 10, 5200.00, '2025-10-30 13:24:56'),
(678, 129, '9', 1, 320.00, 'price_customer_frontstore', 20, 6400.00, '2025-10-30 17:35:34'),
(679, 129, '8', 1, 420.00, 'price_customer_frontstore', 15, 6300.00, '2025-10-30 17:35:34'),
(680, 129, '7', 1, 320.00, 'price_customer_frontstore', 12, 3840.00, '2025-10-30 17:35:34'),
(681, 131, '15', 3, 310.00, 'price_levels_one', 10, 3100.00, '2025-11-09 21:09:20'),
(682, 131, '16', 2, 240.00, 'price_customer_deliver', 20, 4800.00, '2025-11-09 21:09:20'),
(683, 132, '1', 4, 544.00, 'price_customer_deliver', 40, 21760.00, '2025-11-09 21:14:58'),
(687, 130, '1', 4, 544.00, 'price_customer_deliver', 23, 12512.00, '2025-11-10 00:56:44');

-- --------------------------------------------------------

--
-- Table structure for table `lot_product`
--

CREATE TABLE `lot_product` (
  `id_lot` int(11) NOT NULL,
  `lot_numbers` varchar(50) NOT NULL,
  `productname_id` int(11) NOT NULL,
  `ordersell_id` int(11) NOT NULL,
  `total_product` int(11) NOT NULL,
  `price_total` decimal(10,2) NOT NULL,
  `level_sell` varchar(70) NOT NULL,
  `customer` varchar(200) NOT NULL,
  `group_sell` varchar(200) NOT NULL,
  `id_adder` int(11) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `name_product`
--

CREATE TABLE `name_product` (
  `id_name` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `price_center` decimal(10,2) NOT NULL,
  `count_cord` int(11) NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL,
  `adder_id` int(11) NOT NULL,
  `status_del` int(2) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `name_product`
--

INSERT INTO `name_product` (`id_name`, `product_name`, `price`, `price_center`, `count_cord`, `shipping_cost`, `adder_id`, `status_del`, `create_at`) VALUES
(1, 'GUDAGARUN', 300.00, 400.00, 50, 7.50, 2, 1, '2025-10-15 13:38:31'),
(2, 'JOHN', 500.00, 700.00, 50, 10.50, 2, 1, '2025-10-15 13:56:05'),
(3, 'VESS', 300.00, 350.00, 40, 0.00, 1, 1, '2025-10-16 13:44:26'),
(4, 'JASS', 400.00, 500.00, 60, 18.15, 2, 1, '2025-10-16 14:14:24'),
(5, 'DOSSS', 570.00, 630.00, 50, 20.00, 2, 1, '2025-10-16 14:18:17'),
(6, 'ROSA', 450.50, 500.75, 60, 4.60, 2, 1, '2025-10-16 14:27:35'),
(7, 'DORARE', 155.50, 177.70, 60, 12.60, 2, 1, '2025-10-16 20:49:30'),
(8, 'คาปิตอล', 270.00, 300.00, 50, 20.23, 1, 1, '2025-10-17 22:09:05'),
(9, '235 เเดง', 200.00, 230.70, 50, 25.00, 1, 1, '2025-10-17 22:09:33'),
(10, '235เขียว', 400.00, 430.50, 50, 30.40, 1, 1, '2025-10-17 22:10:01'),
(11, 'ม่อนรุ่ง', 300.00, 390.00, 50, 27.50, 1, 1, '2025-10-17 22:10:25'),
(12, 'MASOSA', 500.00, 600.00, 50, 25.60, 2, 1, '2025-10-26 21:36:03'),
(13, 'ROSS', 300.00, 400.00, 50, 25.00, 1, 1, '2025-11-02 13:03:13'),
(14, 'SOSATSOSEE', 200.00, 300.00, 50, 30.00, 1, 1, '2025-11-08 17:21:29'),
(15, 'PANDORA', 80.00, 100.00, 40, 25.00, 1, 1, '2025-11-08 17:26:41'),
(16, 'TORAFE', 70.00, 100.00, 30, 25.00, 1, 1, '2025-11-08 17:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `orders_sell`
--

CREATE TABLE `orders_sell` (
  `id_ordersell` int(11) NOT NULL,
  `ordersell_name` varchar(200) NOT NULL,
  `is_totalprice` decimal(10,2) NOT NULL,
  `custome_name` varchar(250) NOT NULL,
  `date_time_sell` datetime NOT NULL,
  `sell_idpeplegroup` int(11) NOT NULL,
  `slip_ordersell` varchar(200) NOT NULL,
  `count_totalpays` decimal(10,2) NOT NULL,
  `count_stuck` decimal(10,2) NOT NULL,
  `adder_id` int(11) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders_sell`
--

INSERT INTO `orders_sell` (`id_ordersell`, `ordersell_name`, `is_totalprice`, `custome_name`, `date_time_sell`, `sell_idpeplegroup`, `slip_ordersell`, `count_totalpays`, `count_stuck`, `adder_id`, `create_at`) VALUES
(127, 'GQW1H58K3M', 15440.00, 'master dev TEST 2', '2025-10-30 17:15:00', 1, 'img_68fb51cc7849a.png', 15440.00, 0.00, 1, '2025-10-30 13:35:14'),
(128, 'XVYAKMZ2JY', 9852.00, 'sora', '2025-10-30 09:20:00', 1, '', 9852.00, 0.00, 1, '2025-10-30 09:20:33'),
(129, 'PL7I5FQYGF', 16540.00, 'HISOKA MONRROW', '2025-10-30 17:28:00', 3, '', 0.00, 16540.00, 1, '2025-11-09 21:21:10'),
(130, 'YE7K01O2VW', 12512.00, 'HISOKA MONRROW', '2025-11-09 20:58:00', 3, '', 12512.00, 0.00, 1, '2025-11-10 00:56:44'),
(131, 'FOJWVCVM0V', 7900.00, 'master dev TEST 2', '2025-11-09 21:09:00', 3, '', 7900.00, 0.00, 1, '2025-11-09 21:09:20'),
(132, '1P6MTJCU83', 20560.00, 'monalisa red', '2025-11-09 21:14:00', 4, '', 20560.00, 0.00, 1, '2025-11-09 21:20:45');

-- --------------------------------------------------------

--
-- Table structure for table `order_box`
--

CREATE TABLE `order_box` (
  `order_id` int(11) NOT NULL,
  `order_name` varchar(300) NOT NULL,
  `lot_numbers` varchar(20) NOT NULL,
  `slip_order` varchar(200) NOT NULL,
  `totalcost_order` decimal(10,2) NOT NULL,
  `count_order` int(11) NOT NULL,
  `id_adder` int(11) NOT NULL,
  `date_time_order` datetime NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_box`
--

INSERT INTO `order_box` (`order_id`, `order_name`, `lot_numbers`, `slip_order`, `totalcost_order`, `count_order`, `id_adder`, `date_time_order`, `create_at`) VALUES
(52, 'FLRIAZ7SKX', 'LOT-A0001', 'img_68f5c1bb20143.png', 205746.00, 5, 1, '2025-10-20 11:59:00', '2025-10-20 11:59:39'),
(53, 'SFQLMAVQWQ', 'LOT-A0002', 'img_68f5d7e7d74b8.jpeg', 96853.00, 3, 1, '2025-10-20 13:33:00', '2025-10-20 13:34:15'),
(54, 'TJDA2ORJQR', 'LOT-A0003', '', 61500.00, 1, 1, '2025-10-31 13:36:00', '2025-10-31 13:37:17'),
(55, 'A894CZA7QC', 'LOT-A0004', '', 73560.50, 3, 1, '2025-11-02 13:03:00', '2025-11-02 13:04:28'),
(56, 'OV8D8340AU', 'LOT-A0005', '', 27426.00, 2, 1, '2025-11-02 13:06:00', '2025-11-02 13:06:13'),
(57, '64M8U5MUWC', 'LOT-A0006', '', 210240.00, 1, 1, '2025-11-02 13:06:00', '2025-11-02 13:06:34'),
(58, 'WFY2PUTAGI', 'LOT-A0007', '', 76380.00, 3, 1, '2025-11-02 22:43:00', '2025-11-02 22:43:33'),
(59, 'LH84LHXEUM', 'LOT-A0008', '', 14231.00, 3, 3, '2025-11-03 20:53:00', '2025-11-03 20:53:53'),
(60, 'SY8SN5Y5QJ', 'LOT-A0009', '', 23000.00, 1, 1, '2025-11-08 17:22:00', '2025-11-08 17:22:43'),
(61, 'HCWBHE62VU', 'LOT-A0010', '', 2100.00, 1, 1, '2025-11-08 17:26:00', '2025-11-08 17:27:18'),
(62, 'K78VNMEU1P', 'LOT-A0011', '', 19000.00, 1, 1, '2025-11-08 17:30:00', '2025-11-08 17:31:06');

-- --------------------------------------------------------

--
-- Table structure for table `order_was_paid`
--

CREATE TABLE `order_was_paid` (
  `waspaid_id` int(11) NOT NULL,
  `debtpaid_id` int(11) NOT NULL,
  `ordersell_ids` int(11) NOT NULL,
  `ordersell_names` varchar(200) NOT NULL,
  `priceto_pay` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `status_pay` varchar(50) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_was_paid`
--

INSERT INTO `order_was_paid` (`waspaid_id`, `debtpaid_id`, `ordersell_ids`, `ordersell_names`, `priceto_pay`, `amount_paid`, `status_pay`, `create_at`) VALUES
(11, 18, 129, 'PL7I5FQYGF', 16540.00, 16000.00, 'จ่ายไม่ครบ', '2025-11-02 23:12:41'),
(12, 19, 129, 'PL7I5FQYGF', 540.00, 300.00, 'จ่ายไม่ครบ', '2025-11-02 23:15:37'),
(13, 20, 129, 'PL7I5FQYGF', 240.00, 240.00, 'ครบถ้วน', '2025-11-02 23:16:13');

-- --------------------------------------------------------

--
-- Table structure for table `peple_groups`
--

CREATE TABLE `peple_groups` (
  `id_peplegroup` int(11) NOT NULL,
  `name_peplegroup` varchar(120) NOT NULL,
  `phone_group` varchar(20) NOT NULL,
  `status_group` varchar(50) NOT NULL,
  `id_adder` int(11) NOT NULL,
  `status_del` int(2) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peple_groups`
--

INSERT INTO `peple_groups` (`id_peplegroup`, `name_peplegroup`, `phone_group`, `status_group`, `id_adder`, `status_del`, `create_at`) VALUES
(1, 'benjamint', '0896788898', 'ลูกจ้าง', 2, 1, '2025-10-26 21:34:39'),
(2, 'issac netero', '', 'ลูกจ้าง', 2, 1, '2025-10-27 11:50:25'),
(3, 'KARN FRESS', '0897865590', 'ลูกจ้าง', 1, 1, '2025-10-30 13:36:19'),
(4, 'BIG JOKKER', '', 'ลูกจ้าง', 1, 1, '2025-11-09 21:13:53');

-- --------------------------------------------------------

--
-- Table structure for table `rate_price`
--

CREATE TABLE `rate_price` (
  `rate_id` int(11) NOT NULL,
  `id_productname` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `id_adder` int(11) NOT NULL,
  `level_sell` int(11) NOT NULL,
  `price_levels_one` decimal(10,2) NOT NULL,
  `price_customer_frontstore` decimal(10,2) NOT NULL,
  `price_customer_deliver` decimal(10,2) NOT NULL,
  `price_customer_dealer` decimal(10,2) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `rate_price`
--

INSERT INTO `rate_price` (`rate_id`, `id_productname`, `product_name`, `id_adder`, `level_sell`, `price_levels_one`, `price_customer_frontstore`, `price_customer_deliver`, `price_customer_dealer`, `create_at`) VALUES
(39, 10, '235เขียว', 1, 1, 510.00, 520.00, 540.00, 530.00, '2025-10-24 16:24:40'),
(40, 11, 'ม่อนรุ่ง', 1, 1, 410.00, 420.00, 440.00, 430.00, '2025-10-24 16:58:17'),
(41, 5, 'DOSSS', 1, 1, 710.00, 720.00, 740.00, 730.00, '2025-10-24 16:59:10'),
(42, 7, 'DORARE', 1, 1, 310.00, 320.00, 340.00, 330.00, '2025-10-24 16:59:54'),
(43, 8, 'คาปิตอล', 1, 1, 410.00, 420.00, 440.00, 430.00, '2025-10-24 17:00:40'),
(44, 9, '235 เเดง', 1, 1, 310.00, 320.00, 340.00, 330.00, '2025-10-24 17:02:01'),
(45, 12, 'MASOSA', 1, 1, 651.00, 701.00, 801.00, 751.00, '2025-11-08 16:02:40'),
(46, 12, 'MASOSA', 1, 2, 662.00, 722.00, 822.00, 772.00, '2025-11-08 16:02:40'),
(47, 12, 'MASOSA', 1, 3, 673.70, 733.70, 833.70, 783.70, '2025-11-08 17:20:17'),
(48, 12, 'MASOSA', 1, 4, 684.50, 744.50, 844.50, 794.50, '2025-11-08 17:19:41'),
(49, 14, 'SOSATSOSEE', 1, 1, 310.00, 320.00, 340.00, 330.00, '2025-11-08 17:24:37'),
(50, 14, 'SOSATSOSEE', 1, 2, 410.00, 420.00, 440.00, 430.00, '2025-11-08 17:24:37'),
(51, 14, 'SOSATSOSEE', 1, 3, 510.00, 520.00, 540.00, 530.00, '2025-11-08 17:24:37'),
(52, 14, 'SOSATSOSEE', 1, 4, 610.00, 620.00, 640.00, 630.00, '2025-11-08 17:24:37'),
(53, 15, 'PANDORA', 1, 1, 110.00, 120.00, 140.00, 130.00, '2025-11-08 17:28:21'),
(54, 15, 'PANDORA', 1, 2, 210.00, 220.00, 240.00, 230.00, '2025-11-08 17:28:21'),
(55, 15, 'PANDORA', 1, 3, 310.00, 320.00, 340.00, 330.00, '2025-11-08 17:28:21'),
(56, 15, 'PANDORA', 1, 4, 410.00, 420.00, 440.00, 430.00, '2025-11-08 17:28:21'),
(57, 16, 'TORAFE', 1, 1, 110.00, 120.00, 140.00, 130.00, '2025-11-08 17:32:10'),
(58, 16, 'TORAFE', 1, 2, 210.00, 220.00, 240.00, 230.00, '2025-11-08 17:32:10'),
(59, 16, 'TORAFE', 1, 3, 310.00, 320.00, 340.00, 330.00, '2025-11-08 17:32:10'),
(60, 16, 'TORAFE', 1, 4, 410.00, 420.00, 440.00, 430.00, '2025-11-08 17:32:10'),
(61, 1, 'GUDAGARUN', 1, 1, 511.00, 512.00, 514.00, 513.00, '2025-11-09 21:12:21'),
(62, 1, 'GUDAGARUN', 1, 2, 521.00, 522.00, 524.00, 523.00, '2025-11-09 21:12:21'),
(63, 1, 'GUDAGARUN', 1, 3, 531.00, 532.00, 534.00, 533.00, '2025-11-09 21:12:21'),
(64, 1, 'GUDAGARUN', 1, 4, 541.00, 542.00, 544.00, 543.00, '2025-11-09 21:12:21');

-- --------------------------------------------------------

--
-- Table structure for table `sell_typepay`
--

CREATE TABLE `sell_typepay` (
  `typepay_id` int(11) NOT NULL,
  `ordersell_id` int(11) NOT NULL,
  `list_typepay` varchar(70) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sell_typepay`
--

INSERT INTO `sell_typepay` (`typepay_id`, `ordersell_id`, `list_typepay`, `create_at`) VALUES
(133, 127, 'จ่ายสด', '2025-10-24 17:15:40'),
(134, 128, 'จ่ายสด', '2025-10-30 09:20:33'),
(135, 127, 'โอน', '2025-10-30 13:24:56'),
(136, 129, 'ติดค้าง', '2025-10-30 17:35:34'),
(137, 130, 'จ่ายสด', '2025-11-09 20:59:06'),
(138, 131, 'จ่ายสด', '2025-11-09 21:09:20'),
(139, 132, 'จ่ายสด', '2025-11-09 21:14:58');

-- --------------------------------------------------------

--
-- Table structure for table `stock_product`
--

CREATE TABLE `stock_product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_count` int(11) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `price_center` decimal(10,2) NOT NULL,
  `count_cord` int(11) NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL,
  `expenses` decimal(10,2) NOT NULL,
  `id_adder` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `lot_number` varchar(20) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `stock_product`
--

INSERT INTO `stock_product` (`product_id`, `product_name`, `product_count`, `product_price`, `price_center`, `count_cord`, `shipping_cost`, `expenses`, `id_adder`, `id_order`, `lot_number`, `create_at`) VALUES
(99, '11', 100, 300.00, 390.00, 5000, 2750.00, 32750.00, 1, 52, 'LOT-A0001', '2025-10-20 11:59:39'),
(100, '10', 120, 400.00, 430.50, 6000, 3648.00, 51648.00, 1, 52, 'LOT-A0001', '2025-10-20 11:59:39'),
(101, '1', 70, 300.00, 400.00, 3500, 525.00, 21525.00, 1, 52, 'LOT-A0001', '2025-10-20 11:59:39'),
(103, '8', 100, 270.00, 300.00, 5000, 2023.00, 29023.00, 1, 52, 'LOT-A0001', '2025-10-20 13:32:53'),
(104, '9', 200, 200.00, 230.70, 10000, 5000.00, 45000.00, 1, 53, 'LOT-A0002', '2025-10-20 13:34:15'),
(106, '7', 130, 155.50, 177.70, 7800, 1638.00, 21853.00, 1, 53, 'LOT-A0002', '2025-10-20 13:36:04'),
(107, '5', 120, 570.00, 630.00, 6000, 2400.00, 70800.00, 1, 52, 'LOT-A0001', '2025-10-24 13:16:43'),
(108, '3', 100, 300.00, 350.00, 4000, 0.00, 30000.00, 1, 53, 'LOT-A0002', '2025-10-30 11:21:09'),
(109, '1', 200, 300.00, 400.00, 10000, 1500.00, 61500.00, 1, 54, 'LOT-A0003', '2025-10-31 13:37:17'),
(110, '13', 120, 300.00, 400.00, 6000, 3000.00, 39000.00, 1, 55, 'LOT-A0004', '2025-11-02 13:04:28'),
(111, '6', 30, 450.50, 500.75, 1800, 138.00, 13653.00, 1, 55, 'LOT-A0004', '2025-11-02 13:04:28'),
(112, '4', 50, 400.00, 500.00, 3000, 907.50, 20907.50, 1, 55, 'LOT-A0004', '2025-11-02 13:04:28'),
(113, '2', 20, 500.00, 700.00, 1000, 210.00, 10210.00, 1, 56, 'LOT-A0005', '2025-11-02 13:06:13'),
(114, '12', 400, 500.00, 600.00, 20000, 10240.00, 210240.00, 1, 57, 'LOT-A0006', '2025-11-02 13:06:34'),
(115, '10', 40, 400.00, 430.50, 2000, 1216.00, 17216.00, 1, 56, 'LOT-A0005', '2025-11-02 13:53:50'),
(116, '5', 50, 570.00, 630.00, 2500, 1000.00, 29500.00, 1, 58, 'LOT-A0007', '2025-11-02 22:43:33'),
(117, '13', 50, 300.00, 400.00, 2500, 1250.00, 16250.00, 1, 58, 'LOT-A0007', '2025-11-02 22:43:33'),
(118, '2', 60, 500.00, 700.00, 3000, 630.00, 30630.00, 1, 58, 'LOT-A0007', '2025-11-02 22:45:18'),
(119, '1', 10, 300.00, 400.00, 500, 75.00, 3075.00, 3, 59, 'LOT-A0008', '2025-11-03 20:53:53'),
(120, '12', 10, 500.00, 600.00, 500, 256.00, 5256.00, 3, 59, 'LOT-A0008', '2025-11-03 20:53:53'),
(121, '5', 10, 570.00, 630.00, 500, 200.00, 5900.00, 3, 59, 'LOT-A0008', '2025-11-03 20:53:53'),
(122, '14', 100, 200.00, 300.00, 5000, 3000.00, 23000.00, 1, 60, 'LOT-A0009', '2025-11-08 17:22:43'),
(123, '15', 20, 80.00, 100.00, 800, 500.00, 2100.00, 1, 61, 'LOT-A0010', '2025-11-08 17:27:18'),
(124, '16', 200, 70.00, 100.00, 6000, 5000.00, 19000.00, 1, 62, 'LOT-A0011', '2025-11-08 17:31:06');

-- --------------------------------------------------------

--
-- Table structure for table `type_paydebt`
--

CREATE TABLE `type_paydebt` (
  `typedebt_id` int(11) NOT NULL,
  `debtpay_id` int(11) NOT NULL,
  `type_pay` varchar(50) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `type_paydebt`
--

INSERT INTO `type_paydebt` (`typedebt_id`, `debtpay_id`, `type_pay`, `create_at`) VALUES
(18, 18, 'จ่ายสด', '2025-11-02 23:12:41'),
(19, 19, 'จ่ายสด', '2025-11-02 23:15:37'),
(20, 20, 'จ่ายสด', '2025-11-02 23:16:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(20) NOT NULL,
  `fullname` varchar(300) NOT NULL,
  `username` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `create_at`) VALUES
(1, 'admin', 'admin.com', 'P@ssw0rd', '2025-08-16 11:32:10'),
(2, 'rokok data', 'rokok24jam.com', 'P@ssw0rd', '2025-10-13 10:21:01'),
(3, 'admin smokker', 'smokker24hours.com', 'P@ssw0rd', '2025-11-02 18:32:59');

-- --------------------------------------------------------

--
-- Table structure for table `withdraw`
--

CREATE TABLE `withdraw` (
  `withdraw_id` int(11) NOT NULL,
  `withdraw_balance` decimal(10,2) NOT NULL,
  `count_withdraw` decimal(10,2) NOT NULL,
  `slip_withdraw` varchar(300) NOT NULL,
  `date_withdrow` datetime NOT NULL,
  `reason` text NOT NULL,
  `id_adder` int(11) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `capital`
--
ALTER TABLE `capital`
  ADD PRIMARY KEY (`capital_id`);

--
-- Indexes for table `custom_debtpaid`
--
ALTER TABLE `custom_debtpaid`
  ADD PRIMARY KEY (`id_debtpaid`);

--
-- Indexes for table `list_productsell`
--
ALTER TABLE `list_productsell`
  ADD PRIMARY KEY (`list_sellid`);

--
-- Indexes for table `lot_product`
--
ALTER TABLE `lot_product`
  ADD PRIMARY KEY (`id_lot`);

--
-- Indexes for table `name_product`
--
ALTER TABLE `name_product`
  ADD PRIMARY KEY (`id_name`);

--
-- Indexes for table `orders_sell`
--
ALTER TABLE `orders_sell`
  ADD PRIMARY KEY (`id_ordersell`);

--
-- Indexes for table `order_box`
--
ALTER TABLE `order_box`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_was_paid`
--
ALTER TABLE `order_was_paid`
  ADD PRIMARY KEY (`waspaid_id`);

--
-- Indexes for table `peple_groups`
--
ALTER TABLE `peple_groups`
  ADD PRIMARY KEY (`id_peplegroup`);

--
-- Indexes for table `rate_price`
--
ALTER TABLE `rate_price`
  ADD PRIMARY KEY (`rate_id`);

--
-- Indexes for table `sell_typepay`
--
ALTER TABLE `sell_typepay`
  ADD PRIMARY KEY (`typepay_id`);

--
-- Indexes for table `stock_product`
--
ALTER TABLE `stock_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `type_paydebt`
--
ALTER TABLE `type_paydebt`
  ADD PRIMARY KEY (`typedebt_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw`
--
ALTER TABLE `withdraw`
  ADD PRIMARY KEY (`withdraw_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `capital`
--
ALTER TABLE `capital`
  MODIFY `capital_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `custom_debtpaid`
--
ALTER TABLE `custom_debtpaid`
  MODIFY `id_debtpaid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `list_productsell`
--
ALTER TABLE `list_productsell`
  MODIFY `list_sellid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=688;

--
-- AUTO_INCREMENT for table `lot_product`
--
ALTER TABLE `lot_product`
  MODIFY `id_lot` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `name_product`
--
ALTER TABLE `name_product`
  MODIFY `id_name` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders_sell`
--
ALTER TABLE `orders_sell`
  MODIFY `id_ordersell` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `order_box`
--
ALTER TABLE `order_box`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `order_was_paid`
--
ALTER TABLE `order_was_paid`
  MODIFY `waspaid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `peple_groups`
--
ALTER TABLE `peple_groups`
  MODIFY `id_peplegroup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rate_price`
--
ALTER TABLE `rate_price`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `sell_typepay`
--
ALTER TABLE `sell_typepay`
  MODIFY `typepay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `stock_product`
--
ALTER TABLE `stock_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `type_paydebt`
--
ALTER TABLE `type_paydebt`
  MODIFY `typedebt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `withdraw`
--
ALTER TABLE `withdraw`
  MODIFY `withdraw_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
