-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 06:36 PM
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
-- Database: `tapauplanet`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `menuId` int(11) NOT NULL,
  `cartQuantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartId`, `userId`, `menuId`, `cartQuantity`, `added_at`) VALUES
(1, 5, 3, 2, '2025-07-06 11:48:02'),
(2, 11, 4, 2, '2025-07-06 11:48:02'),
(3, 18, 4, 2, '2025-07-06 11:48:02'),
(4, 18, 5, 1, '2025-07-06 11:48:02'),
(5, 18, 11, 1, '2025-07-06 11:48:02'),
(6, 7, 17, 1, '2025-07-06 11:48:02'),
(7, 7, 9, 2, '2025-07-06 11:48:02'),
(8, 7, 9, 2, '2025-07-06 11:48:02'),
(20, 24, 1, 1, '2025-07-08 11:11:28');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL,
  `categoryName` varchar(20) NOT NULL,
  `categoryDesc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryId`, `categoryName`, `categoryDesc`) VALUES
(1, 'Malay', 'Traditional Malaysian food such as nasi lemak, satay, and rendang.'),
(2, 'Western', 'Includes pasta, steak, burgers, and other western-style dishes.'),
(3, 'Korean', 'Korean cuisine such as kimchi, bulgogi, and tteokbokki.'),
(4, 'Drinks', 'Various beverages including coffee, tea, juice, and soft drinks.');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `feedbackText` text NOT NULL,
  `serviceRating` int(11) DEFAULT 5,
  `feedbackDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackId`, `userId`, `feedbackText`, `serviceRating`, `feedbackDate`) VALUES
(1, 3, 'Layanan sangat baik dan cepat!', 5, '2025-04-03 02:15:22'),
(2, 4, 'Sistem senang guna. Saya puas hati.', 4, '2025-04-12 05:45:09'),
(3, 5, 'Order sampai tepat waktu. Recommended!', 5, '2025-04-25 01:32:01'),
(4, 6, 'Staff agak kurang senyum, boleh improve sikit.', 3, '2025-05-05 08:18:37'),
(5, 7, 'Interface cantik tapi kadang slow.', 4, '2025-05-17 03:07:58'),
(6, 8, 'Senang buat pesanan. Good job!', 5, '2025-05-29 06:21:45'),
(7, 9, 'Ada isu sikit masa checkout, tapi selesai cepat.', 4, '2025-06-08 09:59:13'),
(8, 10, 'Suka pilihan menu banyak dan variasi harga.', 5, '2025-06-20 00:44:26'),
(33, 40, 'makanan sedap tapi perhantaran lambat sikit. overall ok', 3, '2025-07-16 18:06:34'),
(34, 25, 'bagus, service laju', 2, '2025-07-17 00:57:12'),
(35, 41, 'berehhh', 1, '2025-07-17 03:52:42');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menuId` int(10) NOT NULL,
  `menuName` varchar(100) NOT NULL,
  `menuPrice` decimal(10,0) NOT NULL,
  `menuPic` varchar(255) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `menuDesc` text DEFAULT NULL,
  `salesCount` int(11) DEFAULT 0,
  `userId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menuId`, `menuName`, `menuPrice`, `menuPic`, `categoryId`, `menuDesc`, `salesCount`, `userId`) VALUES
(1, 'Nasi Lemak', 8, 'nasi_lemak.jpg', 1, 'Nasi lemak dengan sambal, telur, kacang, dan ikan bilis.', 0, 21),
(2, 'Nasi Kerabu', 10, 'nasi_kerabu.jpg', 1, 'Nasi biru dengan ulam, sambal kelapa, dan ayam percik.', 0, 21),
(3, 'Nasi Goreng Ayam', 9, 'nasi_goreng_ayam.jpg', 1, 'Nasi goreng pedas dengan ayam goreng rangup.', 0, 21),
(4, 'Mee Goreng', 7, 'mee_goreng.jpg', 1, 'Mee goreng mamak dengan telur dan sayur.', 0, 21),
(5, 'Bihun Goreng', 7, 'bihun_goreng.jpg', 1, 'Bihun goreng kampung dengan telur dan sayur.', 0, 21),
(6, 'Masak Lemak Cili Api', 11, 'masak_lemak_cili_api.jpg', 1, 'Ayam/ikan dimasak dengan santan dan cili api.', 0, 21),
(7, 'Ayam Masak Merah', 10, 'ayam_masak_merah.jpg', 1, 'Ayam goreng dimasak dengan sambal tomato pedas.', 0, 21),
(8, 'Asam Pedas Ikan Pati', 12, 'asam_pedas_ikanpatin.jpg', 1, 'Ikan patin dimasak dengan kuah asam pedas.', 0, 21),
(9, 'Laksam', 9, 'laksam.jpg', 1, 'Laksam gulung dengan kuah lemak dan ulam.', 0, 21),
(10, 'Ayam Goreng', 8, 'ayam_goreng.jpg', 1, 'Ayam goreng rangup berempah.', 0, 21),
(11, 'Satay', 7, 'satay.jpg', 1, 'Satay ayam/daging dengan kuah kacang.', 0, 21),
(12, 'Kuih', 5, 'kuih.jpg', 1, 'Aneka kuih-muih tradisional Melayu.', 0, 21),
(13, 'Grilled Fish Tacos', 15, 'grilled_fish_tacos.jpg', 2, 'Grilled fish wrapped in soft tacos with tangy sauce.', 0, 21),
(14, 'Chicken Caesar Wrap', 12, 'chicken_caesar_wrap.jpg', 2, 'Grilled chicken with Caesar dressing in a tortilla wrap.', 0, 21),
(15, 'chicken with Mashed Potato', 18, 'chicken_ Mashed_Potatoes.jpg', 2, 'Grilled chicken breast served with creamy mashed potatoes.', 0, 21),
(16, 'Mushroom Soup', 8, 'mushroom_soup.jpg', 2, 'Creamy mushroom soup served with garlic bread.', 0, 21),
(17, 'Spaghetti Aglio Olio', 13, 'spaghetti_aglio_olio.jpg', 2, 'Simple spaghetti with garlic, olive oil, and chili flakes.', 0, 21),
(18, 'Fish and Chip', 16, 'fish_and_chip.jpg', 2, 'Fried fish fillet served with fries and tartar sauce.', 0, 21),
(19, 'Lamb Chop', 22, 'lamb_chop.jpg', 2, 'Juicy lamb chops grilled to perfection with black pepper sauce.', 0, 21),
(20, 'Parsley Pesto Spaghe', 14, 'parsley_pesto_spaghetti.jpg', 2, 'Spaghetti tossed with fresh parsley pesto sauce.', 0, 21),
(21, 'Steak', 25, 'steak.jpg', 2, 'Grilled beef steak served with sides and sauce of choice.', 0, 21),
(22, 'Burger', 12, 'burger.jpg', 2, 'Beef or chicken burger with lettuce, cheese, and fries.', 0, 21),
(23, 'Chicken Fried Steak', 17, 'chicken_fried_steak.jpg', 2, 'Crispy fried chicken steak with creamy white gravy.', 0, 21),
(24, 'Soondubu Jjigae', 14, 'Soondubu_Jjigae.jpg\r\n\r\n\r\n', 3, 'Spicy soft tofu stew with vegetables, seafood or meat, and egg.', 0, 21),
(25, 'Tteokbokki', 10, 'tteokbokki.jpg', 3, 'Chewy rice cakes in a sweet and spicy red chili sauce.', 0, 21),
(26, 'Jjajangmyeon', 13, 'jjajangmyeon.jpg', 3, 'Noodles topped with black bean paste, vegetables, and meat.', 0, 21),
(27, 'Gimmari', 7, 'gimmari.jpg', 3, 'Fried seaweed rolls filled with noodles, served as a snack.', 0, 21),
(28, 'Bulgogi Kale Ssambap', 15, 'Bulgogi_Kale_Ssambap.jpg\r\n\r\n', 3, 'Grilled marinated beef served with rice wrapped in kale leaves.', 0, 21),
(29, 'Budae Jjigae', 16, 'budae_jjigae.jpg', 3, 'Korean army stew with sausage, noodles, tofu, kimchi, and more.', 0, 21),
(30, 'Hotteok', 6, 'hotteok.jpg', 3, 'Sweet Korean pancakes filled with brown sugar, nuts, and cinnamon.', 0, 21),
(31, 'Kimchi Fried Rice', 10, 'kimchi_fried_rice.jpg', 3, 'Fried rice stir-fried with kimchi, egg, and optional meat.', 0, 21),
(32, 'Beef Bulgogi', 17, 'beef_bulgogi.jpg', 3, 'Thinly sliced marinated beef grilled to perfection.', 0, 21),
(33, 'Kimchi Jjigae', 12, 'kimchi_jjigae.jpg', 3, 'Spicy stew made with kimchi, tofu, and vegetables.', 0, 21),
(34, 'Bibimbap', 13, 'bibimbap.jpg', 3, 'Mixed rice with vegetables, meat, egg, and spicy gochujang sauce.', 0, 21),
(35, 'Gimbap', 9, 'gimbap.jpg', 3, 'Korean rice rolls wrapped in seaweed, filled with vegetables and meat.', 0, 21),
(36, 'Chicken Francese', 18, 'chicken_francese.jpg', 2, 'Lightly battered chicken cutlet sautéed in lemon butter white wine sauce.', 0, 21),
(37, 'Iced Lemon Tea', 5, 'iced_lemon_tea.jpg', 4, 'Refreshing black tea with lemon and ice, perfect for hot days.', 0, 21),
(38, 'Iced Lychee Black Tea', 6, 'iced_lychee_black_tea.jpg', 4, 'Sweet and floral black tea infused with lychee flavor over ice.', 0, 21),
(39, 'Iced Peach Green Tea', 7, 'iced_peach_green_tea_lemonade.jpg', 4, 'A cool blend of green tea, peach syrup, and lemonade.', 0, 21),
(40, 'Dalgona Latte', 9, 'dalgona_latte.jpg', 4, 'Creamy latte topped with whipped coffee foam.', 0, 21),
(41, 'Ice Americano', 6, 'ice_americano.jpg', 4, 'Chilled black coffee served over ice – strong and bold.', 0, 21),
(42, 'Iced Passion Tea Lem', 7, 'iced_passion_tea_lemonade.jpg', 4, 'Tropical hibiscus tea mixed with lemonade for a tangy twist.', 0, 21),
(43, 'Mango Hojicha Latte', 9, 'mango_hojicha_latte.jpg', 4, 'Smoky roasted green tea combined with sweet mango and milk.', 0, 21),
(44, 'Lychee Soda', 6, 'lychee_soda.jpg', 4, 'Sparkling soda infused with lychee syrup and ice.', 0, 21),
(46, 'White Chocolate Pepp', 10, 'white_chocolate_peppermint_mocha.jpg', 4, 'A rich mocha with white chocolate and peppermint flavor.', 0, 21),
(47, 'Iced Matcha Chai Lat', 9, 'iced_matcha_chai_latte.jpg', 4, 'Fusion of matcha and spiced chai with creamy milk.', 0, 21),
(48, 'Sakura Strawberry La', 9, 'sakura_strawberry_latte.jpg', 4, 'Floral sakura and sweet strawberry blended into a latte.', 0, 21),
(62, 'Mineral bottle', 5, 'mineral_water.jpg', 4, '500ml', 0, 21);

-- --------------------------------------------------------

--
-- Table structure for table `menu_rating`
--

CREATE TABLE `menu_rating` (
  `menuRateId` int(11) NOT NULL,
  `orderId` int(11) DEFAULT NULL,
  `menuId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `menuRateRating` int(11) DEFAULT NULL,
  `menuRateComment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_rating`
--

INSERT INTO `menu_rating` (`menuRateId`, `orderId`, `menuId`, `userId`, `menuRateRating`, `menuRateComment`, `created_at`) VALUES
(1, 1, 3, 3, 5, 'Sedap sangat, portion cukup!', '2025-04-02 03:00:00'),
(2, 2, 4, 4, 4, 'Makanan okay, tapi agak mahal', '2025-04-05 06:20:00'),
(3, 4, 5, 6, 3, 'Rasa biasa je, boleh improve', '2025-04-10 08:40:00'),
(4, 5, 6, 7, 5, 'Sangat puas hati dengan menu ni!', '2025-04-14 04:10:00'),
(5, 7, 1, 9, 2, 'Kurang rasa dan tak panas', '2025-04-18 09:50:00'),
(6, 8, 2, 10, 4, 'Okay je, portion besar dan sedap', '2025-04-21 05:30:00'),
(7, 10, 3, 12, 5, 'Terbaik! Favourite saya', '2025-04-30 01:15:00'),
(8, 11, 7, 13, 3, 'Average, tapi boleh tahan', '2025-05-01 10:05:00'),
(9, 154, 2, 23, 4, NULL, '2025-07-07 14:01:51'),
(10, 47, 3, 25, 3, NULL, '2025-07-10 05:00:18'),
(11, 70, 3, 25, 5, NULL, '2025-07-17 00:55:36');

-- --------------------------------------------------------

--
-- Table structure for table `ordermenu`
--

CREATE TABLE `ordermenu` (
  `orderId` int(11) NOT NULL,
  `menuId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordermenu`
--

INSERT INTO `ordermenu` (`orderId`, `menuId`, `quantity`) VALUES
(1, 2, 1),
(1, 5, 2),
(2, 1, 1),
(2, 4, 1),
(3, 3, 2),
(4, 6, 2),
(4, 8, 1),
(5, 10, 3),
(6, 12, 1),
(6, 14, 1),
(7, 7, 2),
(8, 9, 1),
(8, 11, 1),
(8, 13, 1),
(9, 4, 2),
(10, 6, 1),
(10, 1, 1),
(11, 3, 1),
(11, 9, 2),
(12, 5, 1),
(12, 7, 1),
(13, 8, 2),
(13, 2, 1),
(14, 15, 2),
(15, 16, 1),
(15, 17, 2),
(16, 4, 2),
(17, 10, 2),
(17, 18, 1),
(18, 20, 1),
(19, 2, 1),
(19, 11, 1),
(20, 5, 2),
(21, 6, 1),
(22, 1, 1),
(22, 13, 1),
(23, 17, 1),
(23, 9, 2),
(24, 18, 1),
(24, 8, 1),
(25, 19, 2),
(25, 3, 1),
(15, 16, 1),
(15, 17, 2),
(16, 5, 1),
(16, 11, 1),
(17, 8, 2),
(17, 3, 1),
(18, 6, 1),
(18, 12, 1),
(19, 2, 1),
(19, 10, 2),
(20, 13, 1),
(20, 14, 1),
(21, 7, 2),
(21, 1, 1),
(22, 4, 1),
(22, 6, 1),
(23, 9, 2),
(24, 11, 1),
(24, 15, 1),
(25, 3, 1),
(25, 5, 2),
(41, 4, 2),
(41, 37, 1),
(41, 38, 1),
(42, 4, 1),
(43, 2, 1),
(44, 4, 1),
(46, 4, 1),
(47, 3, 2),
(48, 16, 2),
(48, 22, 2),
(49, 4, 1),
(50, 4, 1),
(51, 2, 3),
(51, 48, 1),
(52, 3, 1),
(53, 3, 1),
(54, 3, 1),
(55, 3, 2),
(56, 6, 1),
(57, 3, 1),
(58, 2, 1),
(59, 3, 1),
(60, 3, 2),
(61, 3, 1),
(62, 3, 1),
(63, 1, 1),
(64, 4, 1),
(65, 1, 1),
(66, 2, 1),
(67, 3, 1),
(68, 3, 1),
(69, 4, 1),
(70, 3, 1),
(71, 2, 1),
(72, 1, 1),
(72, 47, 1),
(73, 2, 1),
(74, 1, 1),
(74, 4, 2),
(75, 33, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `orderStatus` varchar(10) NOT NULL,
  `orderType` varchar(10) NOT NULL,
  `payMethod` varchar(20) DEFAULT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `orderDate`, `orderStatus`, `orderType`, `payMethod`, `userId`) VALUES
(1, '2025-04-02', 'Completed', 'Pickup', 'COD', 3),
(2, '2025-04-05', 'Completed', 'Delivery', 'Card', 4),
(3, '2025-04-07', 'Pending', 'Pickup', 'COD', 5),
(4, '2025-04-10', 'Completed', 'Delivery', 'Card', 6),
(5, '2025-04-14', 'Completed', 'Pickup', 'COD', 7),
(6, '2025-04-15', 'Cancelled', 'Delivery', 'Card', 8),
(7, '2025-04-18', 'Completed', 'Pickup', 'Card', 9),
(8, '2025-04-21', 'Completed', 'Delivery', 'COD', 10),
(9, '2025-04-25', 'Pending', 'Pickup', 'COD', 11),
(10, '2025-04-30', 'Completed', 'Delivery', 'Card', 12),
(11, '2025-05-01', 'Completed', 'Pickup', 'Card', 13),
(12, '2025-05-04', 'Completed', 'Delivery', 'COD', 14),
(13, '2025-05-08', 'Cancelled', 'Pickup', 'Card', 15),
(14, '2025-05-11', 'Completed', 'Delivery', 'COD', 16),
(15, '2025-05-15', 'Completed', 'Pickup', 'COD', 17),
(16, '2025-05-18', 'Pending', 'Delivery', 'Card', 18),
(17, '2025-05-21', 'Completed', 'Pickup', 'COD', 19),
(18, '2025-05-25', 'Completed', 'Delivery', 'Card', 20),
(19, '2025-05-28', 'Completed', 'Pickup', 'COD', 3),
(20, '2025-05-31', 'Completed', 'Delivery', 'Card', 4),
(21, '2025-06-02', 'Completed', 'Pickup', 'COD', 5),
(22, '2025-06-05', 'Completed', 'Delivery', 'Card', 6),
(23, '2025-06-07', 'Pending', 'Pickup', 'COD', 7),
(24, '2025-06-10', 'Completed', 'Delivery', 'Card', 8),
(25, '2025-06-14', 'Completed', 'Pickup', 'COD', 9),
(26, '2025-06-15', 'Cancelled', 'Delivery', 'Card', 10),
(27, '2025-06-18', 'Completed', 'Pickup', 'Card', 11),
(28, '2025-06-21', 'Completed', 'Delivery', 'COD', 12),
(29, '2025-06-25', 'Pending', 'Pickup', 'COD', 13),
(30, '2025-06-30', 'Completed', 'Delivery', 'Card', 14),
(31, '2025-07-01', 'Completed', 'Pickup', 'Card', 15),
(32, '2025-07-02', 'Completed', 'Delivery', 'COD', 16),
(33, '2025-07-03', 'Cancelled', 'Pickup', 'Card', 17),
(34, '2025-07-03', 'Completed', 'Delivery', 'COD', 18),
(35, '2025-07-04', 'Completed', 'Pickup', 'COD', 19),
(36, '2025-07-04', 'Pending', 'Delivery', 'Card', 20),
(37, '2025-07-04', 'Completed', 'Pickup', 'COD', 3),
(38, '2025-07-04', 'Completed', 'Delivery', 'Card', 4),
(39, '2025-07-04', 'Completed', 'Pickup', 'COD', 5),
(40, '2025-07-04', 'Completed', 'Delivery', 'Card', 6),
(41, '2025-07-09', '', 'Delivery', 'cod', 25),
(42, '2025-07-09', 'Confirmed', 'Pickup', 'cod', 25),
(43, '2025-07-09', '', 'Pickup', 'card', 25),
(44, '2025-07-09', '', 'Delivery', 'card', 25),
(45, '2025-07-09', '', 'Pickup', 'cod', 25),
(46, '2025-07-09', 'Completed', 'Delivery', 'cod', 25),
(47, '2025-07-10', 'Processing', 'Pickup', 'cod', 25),
(48, '2025-07-10', '', 'Delivery', 'cod', 27),
(49, '2025-07-10', '', 'Delivery', 'card', 27),
(50, '2025-07-10', '', 'Pickup', 'cod', 27),
(51, '2025-07-10', '', 'Delivery', 'cod', 28),
(52, '2025-07-10', '', 'Pickup', 'cod', 28),
(53, '2025-07-10', '', 'Pickup', 'cod', 29),
(54, '2025-07-10', '', 'Pickup', 'cod', 30),
(55, '2025-07-10', '', 'Pickup', 'cod', 30),
(56, '2025-07-10', '', 'Pickup', 'card', 31),
(57, '2025-07-10', '', 'Pickup', 'cod', 31),
(58, '2025-07-10', '', 'Pickup', 'cod', 32),
(59, '2025-07-10', '', 'Pickup', 'cod', 32),
(60, '2025-07-10', '', 'Pickup', 'cod', 33),
(61, '2025-07-16', '', 'Pickup', 'cod', 34),
(62, '2025-07-16', '', 'Pickup', 'cod', 35),
(63, '2025-07-16', '', 'Pickup', 'cod', 35),
(64, '2025-07-16', '', 'Pickup', 'cod', 35),
(65, '2025-07-16', '', 'Delivery', 'cod', 35),
(66, '2025-07-16', '', 'Pickup', 'cod', 36),
(67, '2025-07-16', '', 'Pickup', 'cod', 36),
(68, '2025-07-16', '', 'Pickup', 'cod', 37),
(69, '2025-07-16', 'Processing', 'Pickup', 'cod', 38),
(70, '2025-07-17', '', 'Pickup', 'cod', 25),
(71, '2025-07-17', '', 'Pickup', 'cod', 39),
(72, '2025-07-17', 'Completed', 'Delivery', 'card', 40),
(73, '2025-07-17', 'Processing', 'Delivery', 'card', 25),
(74, '2025-07-17', '', 'Pickup', 'card', 41),
(75, '2025-07-17', 'Completed', 'Delivery', 'cod', 41);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payId` int(11) NOT NULL,
  `payMethod` varchar(10) NOT NULL,
  `payAmount` decimal(10,0) NOT NULL,
  `payDate` date NOT NULL,
  `payTime` time NOT NULL,
  `payStatus` varchar(10) NOT NULL,
  `orderId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payId`, `payMethod`, `payAmount`, `payDate`, `payTime`, `payStatus`, `orderId`) VALUES
(1, 'COD', 23, '2025-04-02', '00:00:00', 'Completed', 1),
(2, 'Card', 35, '2025-04-05', '00:00:00', 'Completed', 2),
(3, 'Card', 45, '2025-04-10', '00:00:00', 'Completed', 4),
(4, 'COD', 29, '2025-04-14', '00:00:00', 'Completed', 5),
(5, 'Card', 41, '2025-04-18', '00:00:00', 'Completed', 7),
(6, 'COD', 59, '2025-04-21', '00:00:00', 'Completed', 8),
(7, 'Card', 66, '2025-04-30', '00:00:00', 'Completed', 10),
(8, 'Card', 25, '2025-05-01', '00:00:00', 'Completed', 11),
(9, 'COD', 37, '2025-05-04', '00:00:00', 'Completed', 12),
(10, 'COD', 54, '2025-05-11', '00:00:00', 'Completed', 14),
(11, 'COD', 38, '2025-05-15', '00:00:00', 'Completed', 15),
(12, 'COD', 61, '2025-05-21', '00:00:00', 'Completed', 17),
(13, 'Card', 43, '2025-05-25', '00:00:00', 'Completed', 18),
(14, 'COD', 30, '2025-05-28', '00:00:00', 'Completed', 19),
(15, 'Card', 50, '2025-05-31', '00:00:00', 'Completed', 20),
(16, 'COD', 23, '2025-06-02', '00:00:00', 'Completed', 21),
(17, 'Card', 35, '2025-06-05', '00:00:00', 'Completed', 22),
(18, 'Card', 45, '2025-06-10', '00:00:00', 'Completed', 24),
(19, 'COD', 29, '2025-06-14', '00:00:00', 'Completed', 25),
(20, 'Card', 41, '2025-06-18', '00:00:00', 'Completed', 27),
(21, 'COD', 59, '2025-06-21', '00:00:00', 'Completed', 28),
(22, 'Card', 66, '2025-06-30', '00:00:00', 'Completed', 30),
(23, 'Card', 25, '2025-07-01', '00:00:00', 'Completed', 31),
(24, 'COD', 37, '2025-07-02', '00:00:00', 'Completed', 32),
(25, 'COD', 54, '2025-07-03', '00:00:00', 'Completed', 34),
(26, 'COD', 38, '2025-07-04', '00:00:00', 'Completed', 35),
(27, 'COD', 61, '2025-07-04', '00:00:00', 'Completed', 37),
(28, 'Card', 43, '2025-07-04', '00:00:00', 'Completed', 38),
(29, 'COD', 30, '2025-07-04', '00:00:00', 'Completed', 39),
(30, 'Card', 50, '2025-07-04', '00:00:00', 'Completed', 40),
(31, 'cod', 30, '2025-07-08', '19:21:59', 'Pending', 41),
(32, 'cod', 7, '2025-07-08', '19:26:57', 'Pending', 42),
(33, 'card', 10, '2025-07-08', '20:41:06', 'Paid', 43),
(34, 'card', 12, '2025-07-08', '20:42:54', 'Paid', 44),
(35, 'cod', 45, '2025-07-08', '20:43:29', 'Pending', 45),
(36, 'cod', 12, '2025-07-08', '20:43:50', 'Pending', 46),
(37, 'cod', 18, '2025-07-10', '04:19:34', 'Pending', 47),
(38, 'cod', 90, '2025-07-10', '06:32:37', 'Pending', 48),
(39, 'card', 12, '2025-07-10', '06:33:40', 'Paid', 49),
(40, 'cod', 7, '2025-07-10', '06:35:05', 'Pending', 50),
(41, 'cod', 44, '2025-07-10', '07:16:40', 'Pending', 51),
(42, 'cod', 9, '2025-07-10', '07:22:58', 'Pending', 52),
(43, 'cod', 9, '2025-07-10', '07:23:58', 'Pending', 53),
(44, 'cod', 9, '2025-07-10', '07:27:46', 'Pending', 54),
(45, 'cod', 18, '2025-07-10', '07:30:50', 'Pending', 55),
(46, 'card', 11, '2025-07-10', '07:32:24', 'Paid', 56),
(47, 'cod', 9, '2025-07-10', '07:38:47', 'Pending', 57),
(48, 'cod', 10, '2025-07-10', '07:47:52', 'Pending', 58),
(49, 'cod', 9, '2025-07-10', '07:50:13', 'Pending', 59),
(50, 'cod', 18, '2025-07-10', '07:51:56', 'Pending', 60),
(51, 'cod', 9, '2025-07-16', '17:02:48', 'Pending', 61),
(52, 'cod', 9, '2025-07-16', '17:06:30', 'Pending', 62),
(53, 'cod', 18, '2025-07-16', '17:09:38', 'Pending', 63),
(54, 'cod', 7, '2025-07-16', '17:18:57', 'Pending', 64),
(55, 'cod', 23, '2025-07-16', '17:19:35', 'Pending', 65),
(56, 'cod', 10, '2025-07-16', '17:24:36', 'Pending', 66),
(57, 'cod', 9, '2025-07-16', '17:30:27', 'Pending', 67),
(58, 'cod', 9, '2025-07-16', '17:33:11', 'Pending', 68),
(59, 'cod', 7, '2025-07-16', '17:35:33', 'Pending', 69),
(60, 'cod', 9, '2025-07-16', '18:01:32', 'Pending', 70),
(61, 'cod', 10, '2025-07-16', '18:02:34', 'Pending', 71),
(62, 'card', 32, '2025-07-16', '19:44:04', 'Paid', 72),
(63, 'card', 15, '2025-07-17', '02:55:09', 'Paid', 73),
(64, 'card', 22, '2025-07-17', '05:45:53', 'Paid', 74),
(65, 'cod', 17, '2025-07-17', '05:48:37', 'Pending', 75);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffId` int(11) NOT NULL,
  `staffName` varchar(100) NOT NULL,
  `staffAge` int(11) NOT NULL,
  `staffEmail` varchar(100) NOT NULL,
  `staffPhone` varchar(12) NOT NULL,
  `staffRole` varchar(20) NOT NULL,
  `staffPic` varchar(255) NOT NULL,
  `userId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffId`, `staffName`, `staffAge`, `staffEmail`, `staffPhone`, `staffRole`, `staffPic`, `userId`) VALUES
(3, 'Nurul Iman', 25, 'nurul@example.com', '0134567890', 'Manager', '157f05101a01b08f3be4fa54035bc61e.jpg', NULL),
(12, 'Abdul Rahman', 19, 'rahman@example.com', '0112345678', 'Chef', 'staff_1751998288_aef819815b77edf94be4ea5c438d077b.jpg', 21),
(13, 'Ahmad Iman', 23, 'ahmad@example.com', '0134567890', 'Manager', 'staff_1751998374_b666f136d250a158b63e07053654afaa.jpg', 21);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `userPassword` varchar(255) NOT NULL,
  `userEmail` varchar(100) DEFAULT NULL,
  `userRole` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `userFName` varchar(100) DEFAULT NULL,
  `userLName` varchar(100) DEFAULT NULL,
  `userAddress` text DEFAULT NULL,
  `userCity` varchar(100) DEFAULT NULL,
  `userPhone` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `userName`, `userPassword`, `userEmail`, `userRole`, `created_at`, `userFName`, `userLName`, `userAddress`, `userCity`, `userPhone`) VALUES
(1, 'admin', '$2y$10$r8LKMpiuQCB65e8Wwmtptu7Kf8OJXAkVvwCJpn3chGMiCPpM4yDB2', 'admin@tapauplanet.com', 'admin', '2025-07-06 10:09:04', 'Admin', 'Tapau', 'No. 1, Jalan Admin', 'Cyberjaya', '01111222333'),
(2, 'adminfaris', 'admin123', 'admin2@tapauplanet.com', 'admin', '2025-07-06 10:09:04', 'Faris', 'Hakimi', 'Jalan Planet 2', 'Petaling Jaya', '0131234567'),
(3, 'aiman98', 'user123', 'aiman98@example.com', 'user', '2025-07-06 10:09:22', 'Aiman', 'Zulkifli', 'No. 12, Jalan Kenari', 'Klang', '0112233445'),
(4, 'nurul_huda', 'user123', 'huda.nurul@example.com', 'user', '2025-07-06 10:09:22', 'Nurul', 'Huda', '23, Lorong Damai', 'Subang Jaya', '0178899000'),
(5, 'syafiq_azmi', 'user123', 'syafiq.azmi@example.com', 'user', '2025-07-06 10:09:22', 'Syafiq', 'Azmi', '8, Jalan Bukit', 'Kuala Lumpur', '0199988776'),
(6, 'zara_lee', 'user123', 'zara.lee@example.com', 'user', '2025-07-06 10:09:22', 'Zara', 'Lee', '5A, Taman Mega', 'Cheras', '0141122334'),
(7, 'danial_hakim', 'user123', 'danial.hakim@example.com', 'user', '2025-07-06 10:09:22', 'Danial', 'Hakim', '9, Jalan Mawar', 'Seremban', '0126677889'),
(8, 'amira_fatin', 'user123', 'amira.fatin@example.com', 'user', '2025-07-06 10:09:22', 'Amira', 'Fatin', '45, Lorong Jati', 'Putrajaya', '0184567890'),
(9, 'muhd_irfan', 'user123', 'muhd.irfan@example.com', 'user', '2025-07-06 10:09:22', 'Muhammad', 'Irfan', '17, Taman Harmoni', 'Johor Bahru', '0169988776'),
(10, 'farah_najwa', 'user123', 'farah.najwa@example.com', 'user', '2025-07-06 10:09:22', 'Farah', 'Najwa', '66, Jalan Aman', 'Ipoh', '0192211003'),
(11, 'iffah123', 'hashedpassword123', 'iffah@example.com', '', '2025-07-06 10:11:06', 'Iffah', 'Mustari', '123 Jalan Tapau', 'Kota Bharu', '0123456789'),
(12, 'amirfarid', 'pw', 'amirf@example.com', '', '2025-07-06 10:24:10', 'Amir', 'Farid', 'Jalan Merdeka 2', 'Kuala Lumpur', '0137766554'),
(13, 'sara_lynn', 'pw', 'sara@example.com', '', '2025-07-06 10:24:10', 'Sara', 'Lynn', 'Jalan Bahagia', 'Kuantan', '0143344556'),
(14, 'wan_halim', 'pw', 'wanh@example.com', '', '2025-07-06 10:24:10', 'Wan', 'Halim', 'Taman Seri Bayu', 'Kota Bharu', '0198787878'),
(15, 'izzat25', 'pw', 'izzat@example.com', '', '2025-07-06 10:24:10', 'Izzat', 'Azman', 'Lorong Kenari', 'Seremban', '0122323232'),
(16, 'noraisha', 'pw', 'nora@example.com', '', '2025-07-06 10:24:10', 'Nor Aisha', 'Bakar', 'Jalan Mawar', 'Johor Bahru', '0161112233'),
(17, 'azimah92', 'pw', 'azimah@example.com', '', '2025-07-06 10:24:10', 'Azimah', 'Yusof', 'Taman Dahlia', 'Butterworth', '0178899001'),
(18, 'danialhakim', 'pw', 'danial@example.com', '', '2025-07-06 10:24:10', 'Danial', 'Hakim', 'Jalan Kampung Baru', 'Kuala Terengganu', '0187878787'),
(19, 'alia_rose', 'pw', 'alia@example.com', '', '2025-07-06 10:24:10', 'Alia', 'Rose', 'Persiaran Hijau', 'Putrajaya', '0191234567'),
(20, 'rayyanq', 'pw', 'rayyan@example.com', '', '2025-07-06 10:24:10', 'Rayyan', 'Qasim', 'Taman Mega Jaya', 'Alor Setar', '0119988776'),
(21, 'adminiffan', '$2y$10$ezNN72EkBZd.iMSRJQ4wFOec7ciuqyVx2LBRF6qnVsU3mtSB5L0Gu', 'adminiffan@tapauplanet.com', 'admin', '2025-07-06 12:06:19', 'iffan', 'khairul', NULL, NULL, '019-9988776'),
(23, 'wnniqbal', '$2y$10$yV3Aa2zEhPuIiqlVo7tJRetC5iRq.p7C7.V3T65Rrib.V58MTlnT6', 'waniqbal61@gmail.com', 'user', '2025-07-07 13:58:41', 'Wan', 'Iqbal', NULL, NULL, '011-65208488'),
(24, 'iffah', '$2y$10$nSJL9rz5fnxhfSVeqRhr7OYbkHH/m/qVRXJpR/5agYwzItzUVKbe.', 'iffah@gmail.com', 'user', '2025-07-07 15:44:45', 'iffah', 'mustari', 'Jalan Raja, 50050 Kuala Lumpur, Kuala Lumpur, Malaysia', 'Malaysia', '017-9957041'),
(25, 'shfqf', '$2y$10$Yrjmeq.FffKzMVm3bRrHbuO7OCbH2382PBbNvFwLmeNOPhH0.VohC', 'shfqf@gmail.com', 'user', '2025-07-08 17:21:13', 'shafieqaf', 'ayub', 'Jalan Raja, 50050 Kuala Lumpur, Kuala Lumpur, Malaysia', 'Malaysia', '019-3315924'),
(27, 'miyah', '$2y$10$yRJprFiPU3YivCVhCmj9M.b3ZeJPQH7RpkWKX1vSC/aN.ln6WucUy', 'miyah@gmail.com', 'user', '2025-07-10 04:29:33', 'amirah', 'izzati', 'unnamed road, 18500, Kelantan, Malaysia', 'Malaysia', '014-6811887'),
(28, 'din', '$2y$10$SqPj7LdHtyaVTeoZdH3w/epf8Qa/XHtLe2Tb0U8FpvadalMADVMOC', 'din@gmail.com', 'user', '2025-07-10 05:15:39', 'hafizuddin', 'juaini', 'MARA University of Technology Kelantan, Tangga Batu Caves, 18500, Kelantan, Malaysia', 'Malaysia', '019-3315921'),
(29, 'ammar', '$2y$10$NstRaZcq65yPFZsnxm1YNu..yf8H627J8iIc6ClvhtVkJ5aPpdNoe', 'am@gmail.com', 'user', '2025-07-10 05:23:43', 'muhammad', 'ammar', NULL, NULL, '019-3314568'),
(30, 'man', '$2y$10$H1kJsAv2MWxsYRg/OcpN5OBBem6NT6Qu9wx5oPINuSLFH3oyF8f9G', 'man@gmail.com', 'user', '2025-07-10 05:27:02', 'luqman', 'iskandar', NULL, NULL, '019-3315925'),
(31, 'solehah', '$2y$10$6wtSSJFx0vim3yC3jDUp0O3PiY8q/DH2O1yZUcDnJ6k3mC0I.EYkq', 'solheh@gmail.com', 'user', '2025-07-10 05:31:54', 'solehah', 'amani', NULL, NULL, '019-3521301'),
(32, 'sha', '$2y$10$za17578G1I4k2LzZ9gn3we9FECkb38EMuMjoORSTlla9Qs0RsWpMS', 'sha@gmail.com', 'user', '2025-07-10 05:47:28', 'asya', 'shqfieqaf', NULL, NULL, '019-3315926'),
(33, 'bal', '$2y$10$1.2/NbrLUmsIHNp.BqR78ew0NO7BNo2sgA4q8gCwcutI34UKwEUpW', 'bal@gmail.com', 'user', '2025-07-10 05:51:38', 'wan', 'iqbal', NULL, NULL, '019-3642591'),
(34, 'hafiz', '$2y$10$uwOHlI92RlxCeZKTd8YmYe51BoxCNBRzQZ5yyaxq.lJfp9YjKSLhK', 'hafiz@gmail.com', 'user', '2025-07-16 15:01:36', 'hafizuddin', 'juaini', NULL, NULL, '019-3315642'),
(35, 'lin', '$2y$10$34MuyTVsXELM.KdcPOab4eEr9VsIh7goSgynHwCPzQWSybFJ0f3Jm', 'lin@gmail.com', 'user', '2025-07-16 15:06:11', 'amalim', '.', 'Jalan Raja, 50050 Kuala Lumpur, Kuala Lumpur, Malaysia', 'Malaysia', '013-3698521'),
(36, 'fira', '$2y$10$vwwB7.rc0txt/8BtW7T/uOzBA9xCiWFqi8dOzLXMRnN0v.5ASBxVC', 'fira@gmail.com', 'user', '2025-07-16 15:24:13', 'shafira', 'ayub', NULL, NULL, '019-3315647'),
(37, 'naufal', '$2y$10$f.5vFBRsJ.r97OKrZsIp1uchHvWNNZ/i98SOLXqhTrgyhzJrNTrKy', 'nau@gmail.com', 'user', '2025-07-16 15:32:51', 'ahmad', 'naufal', NULL, NULL, '019-3315924'),
(38, 'durra', '$2y$10$Kg0V31NN3XjfEQhe0uaH5uWOw5SRY/F8.VgreS/qqNgFr3WAhkxJi', 'durra@gmail.com', 'user', '2025-07-16 15:35:16', 'durrani', 'khalish', NULL, NULL, '019-3315421'),
(39, 'pikah', '$2y$10$43ec73Z2BQad2DXeQZ5r4e0zcz.YChJBbqLa3ImIpeJnOl89skbDu', 'sha@gmail.com.my', 'user', '2025-07-16 16:02:18', 'asya', 'shafieqaf', NULL, NULL, '019-3315924'),
(40, 'ean', '$2y$10$pne41YrdnDCTtfePvwhBbeVRMEvZHSxgZu3Y2e2./uh9aUN7vBEea', 'ean@gmail.com', 'user', '2025-07-16 17:43:15', 'adrean', 'ruiz', 'Jalan Raja, 50050 Kuala Lumpur, Kuala Lumpur, Malaysia', 'Malaysia', '019-3315924'),
(41, 'sha12', '$2y$10$kgQXKPclcgIMvzvXYoFtq.aQslNU6EKokJrvmV3hv122LnvmHY5gi', 'sha12@gmail.com', 'user', '2025-07-17 03:43:45', 'shafieqaf', 'asya', 'MARA University of Technology Kelantan, Tangga Batu Caves, 18500, Kelantan, Malaysia', 'Malaysia', '019-3315924');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`),
  ADD KEY `user_id` (`userId`),
  ADD KEY `menu_id` (`menuId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedbackId`),
  ADD KEY `user_id` (`userId`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menuId`),
  ADD KEY `categoryId` (`categoryId`),
  ADD KEY `fk_menu_user` (`userId`);

--
-- Indexes for table `menu_rating`
--
ALTER TABLE `menu_rating`
  ADD PRIMARY KEY (`menuRateId`);

--
-- Indexes for table `ordermenu`
--
ALTER TABLE `ordermenu`
  ADD KEY `orderId` (`orderId`),
  ADD KEY `menuId` (`menuId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payId`),
  ADD KEY `orderId` (`orderId`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffId`),
  ADD KEY `fk_staff_user` (`userId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `username` (`userName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedbackId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menuId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `menu_rating`
--
ALTER TABLE `menu_rating`
  MODIFY `menuRateId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staffId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`menuId`) REFERENCES `menu` (`menuId`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_user` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`);

--
-- Constraints for table `ordermenu`
--
ALTER TABLE `ordermenu`
  ADD CONSTRAINT `ordermenu_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`),
  ADD CONSTRAINT `ordermenu_ibfk_2` FOREIGN KEY (`menuId`) REFERENCES `menu` (`menuId`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_user` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
