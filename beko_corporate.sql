-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2024 at 11:28 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beko_corporate`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `html` longtext NOT NULL,
  `text` longtext NOT NULL,
  `delta` longtext NOT NULL,
  `product_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `html`, `text`, `delta`, `product_id`, `deleted`, `created_at`) VALUES
(17, '&lt;h2&gt;ProSmart Inverter Motor&lt;/h2&gt;&lt;h3&gt;High efficiency, high durability, low noise&lt;/h3&gt;&lt;p&gt;Don&#039;t stress about your energy bill or give yourself a headache every time you wash your clothes. Thanks to a brushless motor design, ProSmart offers energy efficiency, lower sound levels and higher durability – all in a single machine. All so you get the most from your washing machine without disturbing your monthly budget or piece of mind.&lt;/p&gt;', 'ProSmart Inverter Motor\nHigh efficiency, high durability, low noise\nDon\'t stress about your energy bill or give yourself a headache every time you wash your clothes. Thanks to a brushless motor design, ProSmart offers energy efficiency, lower sound levels and higher durability – all in a single machine. All so you get the most from your washing machine without disturbing your monthly budget or piece of mind.\n', '{\"ops\":[{\"insert\":\"ProSmart Inverter Motor\"},{\"attributes\":{\"header\":2},\"insert\":\"\\n\"},{\"insert\":\"High efficiency, high durability, low noise\"},{\"attributes\":{\"header\":3},\"insert\":\"\\n\"},{\"insert\":\"Don\'t stress about your energy bill or give yourself a headache every time you wash your clothes. Thanks to a brushless motor design, ProSmart offers energy efficiency, lower sound levels and higher durability – all in a single machine. All so you get the most from your washing machine without disturbing your monthly budget or piece of mind.\\n\"}]}', 1, 0, '2024-09-18 08:51:42'),
(18, '&lt;h2&gt;StainExpert Programme&lt;/h2&gt;&lt;h3&gt;Spotless cleaning for 24 types of stains&lt;/h3&gt;&lt;p&gt;Put the white vinegar, the lemon juice and the dish soap away. They belong in the kitchen and your stained clothes belong in a washing machine with StainExpert Programme. Whether it&#039;s coffee, ketchup, chocolate, makeup or blood, StainExpert is designed specially to get rid of 24 different kinds of stains so you won’t have to pre-treat any stain anymore.&lt;/p&gt;', 'StainExpert Programme\nSpotless cleaning for 24 types of stains\nPut the white vinegar, the lemon juice and the dish soap away. They belong in the kitchen and your stained clothes belong in a washing machine with StainExpert Programme. Whether it\'s coffee, ketchup, chocolate, makeup or blood, StainExpert is designed specially to get rid of 24 different kinds of stains so you won’t have to pre-treat any stain anymore.\n', '{\"ops\":[{\"insert\":\"StainExpert Programme\"},{\"attributes\":{\"header\":2},\"insert\":\"\\n\"},{\"insert\":\"Spotless cleaning for 24 types of stains\"},{\"attributes\":{\"header\":3},\"insert\":\"\\n\"},{\"insert\":\"Put the white vinegar, the lemon juice and the dish soap away. They belong in the kitchen and your stained clothes belong in a washing machine with StainExpert Programme. Whether it\'s coffee, ketchup, chocolate, makeup or blood, StainExpert is designed specially to get rid of 24 different kinds of stains so you won’t have to pre-treat any stain anymore.\\n\"}]}', 1, 0, '2024-09-18 17:56:37'),
(19, '&lt;h2&gt;AquaWave&lt;/h2&gt;&lt;h3&gt;Wave-like drum action for gentler treatment&lt;/h3&gt;&lt;p&gt;Washing might take its toll on your clothes. So, here is AquaWave system’s curved door glass and specially designed paddles that move the laundry in a wave-like action inside the drum, treating clothes gentler and improving the washing performance. Next time someone compliments your clothes and you reply, &#039;What, this old thing?&#039; you might be telling the truth.&lt;/p&gt;', 'AquaWave\nWave-like drum action for gentler treatment\nWashing might take its toll on your clothes. So, here is AquaWave system’s curved door glass and specially designed paddles that move the laundry in a wave-like action inside the drum, treating clothes gentler and improving the washing performance. Next time someone compliments your clothes and you reply, \'What, this old thing?\' you might be telling the truth.\n', '{\"ops\":[{\"insert\":\"AquaWave\"},{\"attributes\":{\"header\":2},\"insert\":\"\\n\"},{\"insert\":\"Wave-like drum action for gentler treatment\"},{\"attributes\":{\"header\":3},\"insert\":\"\\n\"},{\"insert\":\"Washing might take its toll on your clothes. So, here is AquaWave system’s curved door glass and specially designed paddles that move the laundry in a wave-like action inside the drum, treating clothes gentler and improving the washing performance. Next time someone compliments your clothes and you reply, \'What, this old thing?\' you might be telling the truth.\\n\"}]}', 1, 0, '2024-09-18 17:58:54'),
(20, '&lt;h2&gt;CoolClean Programme&lt;/h2&gt;&lt;h3&gt;Superior cleaning with 75% less energy&lt;/h3&gt;&lt;p&gt;Wash your normally-soiled cottons for the fraction of the energy of a regular wash with CoolClean Programme. Thanks to 2 super-efficient shower nozzles, water and detergent penetrates clothes much better, allowing CoolClean to provide cleaning results of a 40 °C-wash at just 20 °C, saving 75% of the energy. Prepare to be impressed when you see your next electricity bill.&lt;/p&gt;', 'CoolClean Programme\nSuperior cleaning with 75% less energy\nWash your normally-soiled cottons for the fraction of the energy of a regular wash with CoolClean Programme. Thanks to 2 super-efficient shower nozzles, water and detergent penetrates clothes much better, allowing CoolClean to provide cleaning results of a 40 °C-wash at just 20 °C, saving 75% of the energy. Prepare to be impressed when you see your next electricity bill.\n', '{\"ops\":[{\"insert\":\"CoolClean Programme\"},{\"attributes\":{\"header\":2},\"insert\":\"\\n\"},{\"insert\":\"Superior cleaning with 75% less energy\"},{\"attributes\":{\"header\":3},\"insert\":\"\\n\"},{\"insert\":\"Wash your normally-soiled cottons for the fraction of the energy of a regular wash with CoolClean Programme. Thanks to 2 super-efficient shower nozzles, water and detergent penetrates clothes much better, allowing CoolClean to provide cleaning results of a 40 °C-wash at just 20 °C, saving 75% of the energy. Prepare to be impressed when you see your next electricity bill.\\n\"}]}', 1, 0, '2024-09-18 18:00:01'),
(21, '&lt;h2&gt;ProSmart Inverter Motor&lt;/h2&gt;&lt;h3&gt;High efficiency, high durability, low noise&lt;/h3&gt;&lt;p&gt;Don&#039;t stress about your energy bill or give yourself a headache every time you wash your clothes. Thanks to a brushless motor design, ProSmart offers energy efficiency, lower sound levels and higher durability – all in a single machine. All so you get the most from your washing machine without disturbing your monthly budget or piece of mind.&lt;/p&gt;', 'ProSmart Inverter Motor\nHigh efficiency, high durability, low noise\nDon\'t stress about your energy bill or give yourself a headache every time you wash your clothes. Thanks to a brushless motor design, ProSmart offers energy efficiency, lower sound levels and higher durability – all in a single machine. All so you get the most from your washing machine without disturbing your monthly budget or piece of mind.\n', '{\"ops\":[{\"insert\":\"ProSmart Inverter Motor\"},{\"attributes\":{\"header\":2},\"insert\":\"\\n\"},{\"insert\":\"High efficiency, high durability, low noise\"},{\"attributes\":{\"header\":3},\"insert\":\"\\n\"},{\"insert\":\"Don\'t stress about your energy bill or give yourself a headache every time you wash your clothes. Thanks to a brushless motor design, ProSmart offers energy efficiency, lower sound levels and higher durability – all in a single machine. All so you get the most from your washing machine without disturbing your monthly budget or piece of mind.\\n\"}]}', 2, 0, '2024-09-18 19:32:54'),
(22, '&lt;h2&gt;StainExpert Programme&lt;/h2&gt;&lt;h3&gt;Spotless cleaning for 24 types of stains&lt;/h3&gt;&lt;p&gt;Put the white vinegar, the lemon juice and the dish soap away. They belong in the kitchen and your stained clothes belong in a washing machine with StainExpert Programme. Whether it&#039;s coffee, ketchup, chocolate, makeup or blood, StainExpert is designed specially to get rid of 24 different kinds of stains so you won’t have to pre-treat any stain anymore.&lt;/p&gt;', 'StainExpert Programme\nSpotless cleaning for 24 types of stains\nPut the white vinegar, the lemon juice and the dish soap away. They belong in the kitchen and your stained clothes belong in a washing machine with StainExpert Programme. Whether it\'s coffee, ketchup, chocolate, makeup or blood, StainExpert is designed specially to get rid of 24 different kinds of stains so you won’t have to pre-treat any stain anymore.\n', '{\"ops\":[{\"insert\":\"StainExpert Programme\"},{\"attributes\":{\"header\":2},\"insert\":\"\\n\"},{\"insert\":\"Spotless cleaning for 24 types of stains\"},{\"attributes\":{\"header\":3},\"insert\":\"\\n\"},{\"insert\":\"Put the white vinegar, the lemon juice and the dish soap away. They belong in the kitchen and your stained clothes belong in a washing machine with StainExpert Programme. Whether it\'s coffee, ketchup, chocolate, makeup or blood, StainExpert is designed specially to get rid of 24 different kinds of stains so you won’t have to pre-treat any stain anymore.\\n\"}]}', 2, 0, '2024-09-18 19:38:31'),
(23, '&lt;h2&gt;AquaWave&lt;/h2&gt;&lt;h3&gt;Wave-like drum action for gentler treatment&lt;/h3&gt;&lt;p&gt;Washing might take its toll on your clothes. So, here is AquaWave system’s curved door glass and specially designed paddles that move the laundry in a wave-like action inside the drum, treating clothes gentler and improving the washing performance. Next time someone compliments your clothes and you reply, &#039;What, this old thing?&#039; you might be telling the truth.&lt;/p&gt;', 'AquaWave\nWave-like drum action for gentler treatment\nWashing might take its toll on your clothes. So, here is AquaWave system’s curved door glass and specially designed paddles that move the laundry in a wave-like action inside the drum, treating clothes gentler and improving the washing performance. Next time someone compliments your clothes and you reply, \'What, this old thing?\' you might be telling the truth.\n', '{\"ops\":[{\"insert\":\"AquaWave\"},{\"attributes\":{\"header\":2},\"insert\":\"\\n\"},{\"insert\":\"Wave-like drum action for gentler treatment\"},{\"attributes\":{\"header\":3},\"insert\":\"\\n\"},{\"insert\":\"Washing might take its toll on your clothes. So, here is AquaWave system’s curved door glass and specially designed paddles that move the laundry in a wave-like action inside the drum, treating clothes gentler and improving the washing performance. Next time someone compliments your clothes and you reply, \'What, this old thing?\' you might be telling the truth.\\n\"}]}', 2, 0, '2024-09-18 19:38:31'),
(24, '&lt;h2&gt;Daily Xpress Programme&lt;/h2&gt;&lt;h3&gt;Full capacity cleaning in 28 min&lt;/h3&gt;&lt;p&gt;If it feels like ages for the washing machine to finish, try the Daily Xpress Programme. Daily Xpress lets you wash a full load of laundry in just 28 minutes, at 30 °C. Your laundry will be done before you can finish that half-an-hour comedy you&#039;ve been meaning to catch up on.&lt;/p&gt;', 'Daily Xpress Programme\nFull capacity cleaning in 28 min\nIf it feels like ages for the washing machine to finish, try the Daily Xpress Programme. Daily Xpress lets you wash a full load of laundry in just 28 minutes, at 30 °C. Your laundry will be done before you can finish that half-an-hour comedy you\'ve been meaning to catch up on.\n', '{\"ops\":[{\"insert\":\"Daily Xpress Programme\"},{\"attributes\":{\"header\":2},\"insert\":\"\\n\"},{\"insert\":\"Full capacity cleaning in 28 min\"},{\"attributes\":{\"header\":3},\"insert\":\"\\n\"},{\"insert\":\"If it feels like ages for the washing machine to finish, try the Daily Xpress Programme. Daily Xpress lets you wash a full load of laundry in just 28 minutes, at 30 °C. Your laundry will be done before you can finish that half-an-hour comedy you\'ve been meaning to catch up on.\\n\"}]}', 2, 0, '2024-09-18 19:38:32');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `variety_id` int(11) NOT NULL,
  `path` mediumtext NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `variety_id`, `path`, `height`, `width`, `deleted`, `created_at`) VALUES
(1, 1, 'Freeestanding Washing Machine - BAW 101 oblique view.png', 1280, 1042, 0, '2024-09-17 15:47:57'),
(2, 1, 'Freestanding Washing Machine - BAW 101 Front.png', 1280, 869, 0, '2024-09-17 15:59:01'),
(3, 2, 'BAW 201 Freestanding Washing Machine Front.png', 2000, 1333, 0, '2024-09-18 19:29:37'),
(4, 2, 'BAW 201 Freestanding Washing Machine Oblique.png.png', 2000, 1634, 0, '2024-09-18 19:29:38'),
(5, 2, 'BAW 201 Freestanding Washing Machine Open .png.png', 2000, 1975, 0, '2024-09-18 19:29:38'),
(6, 2, 'BAW 201 Freestanding Washing Machine Controls.png.png', 2000, 2879, 0, '2024-09-18 19:29:38');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `description` mediumtext NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `deleted`, `created_at`) VALUES
(1, 'BAW 101 - Freestanding Washing Machines', 'Discover the epitome of laundry excellence with the Beko Freestanding Washing Machine (BAW 101). This 10 kg marvel is not just a washing machine but a testament to innovation, boasting an energy efficiency that surpasses A+++ by 10%. Its ProSmart Inverter Motor ensures a whisper-quiet operation at 56 dBA while washing and 76 dBA during its powerful 1400 rpm spin cycle. The sleek grey bPRO 500 series machine, with dimensions of 84cm in height, 60cm in width, and 64cm in depth, is equipped with SteamCure technology and a StainExpert Programme, guaranteeing spotless results for 24 types of stains. The Wave-like drum action treats your garments with the utmost care, and the CoolClean Programme delivers superior cleaning with significantly reduced energy consumption. A digital display and flexible time delay feature provide ultimate convenience for the discerning user.', 0, '2024-09-17 15:47:55'),
(2, 'BAW 201: Freestanding Washing Machine', 'Elevate your home with the Beko Freestanding Washing Machine (BAW 201), a testament to modern living and impeccable style. This Manhattan Grey marvel boasts a capacious 10kg washing capacity, seamlessly integrating into your life with its flexible time delay feature, allowing you to schedule laundry around your day. With an outstanding A+++ energy rating, it not only meets your expectations but exceeds them, ensuring efficiency with every cycle. The ProSmart Inverter Motor&amp;amp;amp;amp;amp;#039;s high durability and low noise operation, at just 54 dBA, makes it a discreet yet powerful addition to your household. Revel in the Steamcure with Refreshment technology that breathes new life into your fabrics, while the StainExpert Programme ensures a spotless clean for 24 types of stains without compromise. The wave-like drum action cradles your clothes with care, and the full capacity cleaning in just 28 minutes is nothing short of revolutionary. Standing at a convenient 84.5cm height, this freestanding washing machine is not just an appliance; it&amp;amp;amp;amp;amp;#039;s a lifestyle choice that resonates with Beko&amp;amp;amp;amp;amp;#039;s dedication to quality, efficiency, and style.', 0, '2024-09-18 19:29:36');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `parent` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `parent`, `deleted`) VALUES
(1, 'Built In Home Appliances', 0, 0),
(2, 'Solar Panel &amp; Products', 0, 0),
(3, 'AC Solutions', 0, 0),
(4, 'Hotel Concepts', 0, 0),
(5, 'Kitchen Cabinets', 0, 0),
(6, 'Wardrobes', 0, 0),
(7, 'Doors', 0, 0),
(8, 'Corporate Scenting Solutions', 0, 0),
(9, 'EV Chargers', 0, 0),
(10, 'Washing Machines', 1, 0),
(11, 'Washing Machines', 1, 1),
(12, 'Dishwashing Machines', 1, 0),
(13, 'Refridgeration Machines', 1, 0),
(14, 'Cooking Machines', 1, 0),
(15, 'Washer Dryers', 1, 0),
(16, 'Small Appliances', 1, 0),
(17, 'Air Purifiers', 1, 0),
(18, 'Cordless Vacuum Cleaners', 1, 0),
(19, 'Steam Irons', 1, 0),
(20, 'Freestanding Washing Machines', 10, 0),
(21, 'Freestanding Dishwashers', 12, 0),
(22, 'Integrated Dishwashers', 12, 0),
(23, 'Fridges', 13, 0),
(24, 'Freezers', 13, 0),
(25, 'Fridge Freezers', 13, 0),
(26, 'Integrated Fridges', 13, 0),
(27, 'Integrated Freezers', 13, 0),
(28, 'Integrated Fridge Freezers', 13, 0),
(29, 'Freestanding Cookers', 14, 0),
(30, 'Built-in Ovens', 14, 0),
(31, 'Built-in Hobs', 14, 0),
(32, 'Built-in Hoods', 14, 0),
(33, 'Freestanding Washer Dryers', 15, 0),
(34, 'Integrated Washer Dryers', 15, 0),
(35, 'Coffee Maker', 16, 0),
(36, 'Kettles', 16, 0),
(37, 'Blenders', 16, 0),
(38, 'Choppers and Mixers', 16, 0),
(39, 'Toasters', 16, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories_relations`
--

CREATE TABLE `product_categories_relations` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories_relations`
--

INSERT INTO `product_categories_relations` (`id`, `category_id`, `product_id`, `deleted`, `created_at`) VALUES
(1, 1, 1, 1, '2024-09-17 15:48:04'),
(2, 10, 1, 1, '2024-09-17 15:48:04'),
(3, 20, 1, 1, '2024-09-17 15:48:04'),
(4, 1, 1, 1, '2024-09-17 15:59:02'),
(5, 10, 1, 1, '2024-09-17 15:59:02'),
(6, 20, 1, 1, '2024-09-17 15:59:02'),
(7, 1, 1, 1, '2024-09-17 16:17:51'),
(8, 10, 1, 1, '2024-09-17 16:17:51'),
(9, 20, 1, 1, '2024-09-17 16:17:51'),
(10, 1, 1, 1, '2024-09-17 16:31:40'),
(11, 10, 1, 1, '2024-09-17 16:31:40'),
(12, 20, 1, 1, '2024-09-17 16:31:40'),
(13, 1, 1, 1, '2024-09-17 16:33:17'),
(14, 10, 1, 1, '2024-09-17 16:33:17'),
(15, 20, 1, 1, '2024-09-17 16:33:17'),
(16, 1, 1, 1, '2024-09-17 16:34:02'),
(17, 10, 1, 1, '2024-09-17 16:34:02'),
(18, 20, 1, 1, '2024-09-17 16:34:03'),
(19, 1, 1, 1, '2024-09-17 16:34:47'),
(20, 10, 1, 1, '2024-09-17 16:34:47'),
(21, 20, 1, 1, '2024-09-17 16:34:48'),
(22, 1, 1, 1, '2024-09-17 18:46:00'),
(23, 10, 1, 1, '2024-09-17 18:46:01'),
(24, 20, 1, 1, '2024-09-17 18:46:01'),
(25, 1, 1, 1, '2024-09-17 18:47:01'),
(26, 10, 1, 1, '2024-09-17 18:47:01'),
(27, 20, 1, 1, '2024-09-17 18:47:01'),
(28, 1, 1, 1, '2024-09-17 18:47:30'),
(29, 10, 1, 1, '2024-09-17 18:47:30'),
(30, 20, 1, 1, '2024-09-17 18:47:30'),
(31, 1, 1, 1, '2024-09-17 18:50:23'),
(32, 10, 1, 1, '2024-09-17 18:50:24'),
(33, 20, 1, 1, '2024-09-17 18:50:24'),
(34, 1, 1, 1, '2024-09-17 18:51:34'),
(35, 10, 1, 1, '2024-09-17 18:51:34'),
(36, 20, 1, 1, '2024-09-17 18:51:34'),
(37, 1, 1, 1, '2024-09-17 18:53:22'),
(38, 10, 1, 1, '2024-09-17 18:53:22'),
(39, 20, 1, 1, '2024-09-17 18:53:22'),
(40, 1, 1, 1, '2024-09-17 18:54:57'),
(41, 10, 1, 1, '2024-09-17 18:54:57'),
(42, 20, 1, 1, '2024-09-17 18:54:57'),
(43, 1, 1, 1, '2024-09-17 18:58:32'),
(44, 10, 1, 1, '2024-09-17 18:58:32'),
(45, 20, 1, 1, '2024-09-17 18:58:32'),
(46, 1, 1, 1, '2024-09-17 19:02:48'),
(47, 10, 1, 1, '2024-09-17 19:02:48'),
(48, 20, 1, 1, '2024-09-17 19:02:48'),
(49, 1, 1, 1, '2024-09-17 19:04:38'),
(50, 10, 1, 1, '2024-09-17 19:04:38'),
(51, 20, 1, 1, '2024-09-17 19:04:39'),
(52, 1, 1, 1, '2024-09-17 19:04:59'),
(53, 10, 1, 1, '2024-09-17 19:04:59'),
(54, 20, 1, 1, '2024-09-17 19:04:59'),
(55, 1, 1, 1, '2024-09-17 19:08:12'),
(56, 10, 1, 1, '2024-09-17 19:08:12'),
(57, 20, 1, 1, '2024-09-17 19:08:12'),
(58, 1, 1, 1, '2024-09-17 19:16:30'),
(59, 10, 1, 1, '2024-09-17 19:16:31'),
(60, 20, 1, 1, '2024-09-17 19:16:31'),
(61, 1, 1, 1, '2024-09-17 19:17:53'),
(62, 10, 1, 1, '2024-09-17 19:17:53'),
(63, 20, 1, 1, '2024-09-17 19:17:53'),
(64, 1, 1, 1, '2024-09-17 19:23:13'),
(65, 10, 1, 1, '2024-09-17 19:23:13'),
(66, 20, 1, 1, '2024-09-17 19:23:13'),
(67, 1, 1, 1, '2024-09-17 19:28:05'),
(68, 10, 1, 1, '2024-09-17 19:28:05'),
(69, 20, 1, 1, '2024-09-17 19:28:05'),
(70, 1, 1, 1, '2024-09-17 19:29:52'),
(71, 10, 1, 1, '2024-09-17 19:29:52'),
(72, 20, 1, 1, '2024-09-17 19:29:52'),
(73, 1, 1, 1, '2024-09-17 19:31:54'),
(74, 10, 1, 1, '2024-09-17 19:31:54'),
(75, 20, 1, 1, '2024-09-17 19:31:54'),
(76, 1, 1, 1, '2024-09-17 19:32:40'),
(77, 10, 1, 1, '2024-09-17 19:32:40'),
(78, 20, 1, 1, '2024-09-17 19:32:40'),
(79, 1, 1, 1, '2024-09-17 19:33:55'),
(80, 10, 1, 1, '2024-09-17 19:33:55'),
(81, 20, 1, 1, '2024-09-17 19:33:55'),
(82, 1, 1, 1, '2024-09-17 19:35:55'),
(83, 10, 1, 1, '2024-09-17 19:35:55'),
(84, 20, 1, 1, '2024-09-17 19:35:55'),
(85, 1, 1, 1, '2024-09-17 19:42:33'),
(86, 10, 1, 1, '2024-09-17 19:42:33'),
(87, 20, 1, 1, '2024-09-17 19:42:33'),
(88, 1, 1, 1, '2024-09-17 19:43:38'),
(89, 10, 1, 1, '2024-09-17 19:43:38'),
(90, 20, 1, 1, '2024-09-17 19:43:38'),
(91, 1, 1, 1, '2024-09-17 19:45:10'),
(92, 10, 1, 1, '2024-09-17 19:45:10'),
(93, 20, 1, 1, '2024-09-17 19:45:11'),
(94, 1, 1, 1, '2024-09-17 19:46:34'),
(95, 10, 1, 1, '2024-09-17 19:46:34'),
(96, 20, 1, 1, '2024-09-17 19:46:34'),
(97, 1, 1, 1, '2024-09-17 20:42:33'),
(98, 10, 1, 1, '2024-09-17 20:42:33'),
(99, 20, 1, 1, '2024-09-17 20:42:33'),
(100, 1, 1, 1, '2024-09-17 20:43:17'),
(101, 10, 1, 1, '2024-09-17 20:43:17'),
(102, 20, 1, 1, '2024-09-17 20:43:17'),
(103, 1, 1, 1, '2024-09-17 20:43:37'),
(104, 10, 1, 1, '2024-09-17 20:43:37'),
(105, 20, 1, 1, '2024-09-17 20:43:37'),
(106, 1, 1, 1, '2024-09-17 20:45:03'),
(107, 10, 1, 1, '2024-09-17 20:45:03'),
(108, 20, 1, 1, '2024-09-17 20:45:03'),
(109, 1, 1, 1, '2024-09-17 20:45:13'),
(110, 10, 1, 1, '2024-09-17 20:45:13'),
(111, 20, 1, 1, '2024-09-17 20:45:13'),
(112, 1, 1, 1, '2024-09-17 20:46:16'),
(113, 10, 1, 1, '2024-09-17 20:46:16'),
(114, 20, 1, 1, '2024-09-17 20:46:16'),
(115, 1, 1, 1, '2024-09-17 21:11:32'),
(116, 10, 1, 1, '2024-09-17 21:11:32'),
(117, 20, 1, 1, '2024-09-17 21:11:32'),
(118, 1, 1, 1, '2024-09-17 21:12:12'),
(119, 10, 1, 1, '2024-09-17 21:12:12'),
(120, 20, 1, 1, '2024-09-17 21:12:12'),
(121, 1, 1, 1, '2024-09-17 21:13:07'),
(122, 10, 1, 1, '2024-09-17 21:13:07'),
(123, 20, 1, 1, '2024-09-17 21:13:07'),
(124, 1, 1, 1, '2024-09-17 21:13:39'),
(125, 10, 1, 1, '2024-09-17 21:13:39'),
(126, 20, 1, 1, '2024-09-17 21:13:39'),
(127, 1, 1, 1, '2024-09-17 21:16:47'),
(128, 10, 1, 1, '2024-09-17 21:16:47'),
(129, 20, 1, 1, '2024-09-17 21:16:47'),
(130, 1, 1, 1, '2024-09-17 21:21:44'),
(131, 10, 1, 1, '2024-09-17 21:21:44'),
(132, 20, 1, 1, '2024-09-17 21:21:44'),
(133, 1, 1, 1, '2024-09-17 21:25:37'),
(134, 10, 1, 1, '2024-09-17 21:25:37'),
(135, 20, 1, 1, '2024-09-17 21:25:37'),
(136, 1, 1, 1, '2024-09-17 21:26:52'),
(137, 10, 1, 1, '2024-09-17 21:26:52'),
(138, 20, 1, 1, '2024-09-17 21:26:52'),
(139, 1, 1, 1, '2024-09-17 21:29:19'),
(140, 10, 1, 1, '2024-09-17 21:29:19'),
(141, 20, 1, 1, '2024-09-17 21:29:19'),
(142, 1, 1, 1, '2024-09-17 21:31:55'),
(143, 10, 1, 1, '2024-09-17 21:31:55'),
(144, 20, 1, 1, '2024-09-17 21:31:55'),
(145, 1, 1, 1, '2024-09-17 21:33:34'),
(146, 10, 1, 1, '2024-09-17 21:33:34'),
(147, 20, 1, 1, '2024-09-17 21:33:34'),
(148, 1, 1, 1, '2024-09-17 21:35:17'),
(149, 10, 1, 1, '2024-09-17 21:35:18'),
(150, 20, 1, 1, '2024-09-17 21:35:18'),
(151, 1, 1, 1, '2024-09-17 21:37:15'),
(152, 10, 1, 1, '2024-09-17 21:37:15'),
(153, 20, 1, 1, '2024-09-17 21:37:15'),
(154, 1, 1, 1, '2024-09-17 21:39:49'),
(155, 10, 1, 1, '2024-09-17 21:39:49'),
(156, 20, 1, 1, '2024-09-17 21:39:49'),
(157, 1, 1, 1, '2024-09-17 21:42:50'),
(158, 10, 1, 1, '2024-09-17 21:42:50'),
(159, 20, 1, 1, '2024-09-17 21:42:50'),
(160, 1, 1, 1, '2024-09-17 21:44:11'),
(161, 10, 1, 1, '2024-09-17 21:44:11'),
(162, 20, 1, 1, '2024-09-17 21:44:11'),
(163, 1, 1, 1, '2024-09-17 21:45:55'),
(164, 10, 1, 1, '2024-09-17 21:45:55'),
(165, 20, 1, 1, '2024-09-17 21:45:55'),
(166, 1, 1, 1, '2024-09-17 21:50:57'),
(167, 10, 1, 1, '2024-09-17 21:50:57'),
(168, 20, 1, 1, '2024-09-17 21:50:57'),
(169, 1, 1, 1, '2024-09-17 21:58:02'),
(170, 10, 1, 1, '2024-09-17 21:58:02'),
(171, 20, 1, 1, '2024-09-17 21:58:02'),
(172, 1, 1, 1, '2024-09-17 22:03:10'),
(173, 10, 1, 1, '2024-09-17 22:03:10'),
(174, 20, 1, 1, '2024-09-17 22:03:10'),
(175, 1, 1, 1, '2024-09-17 22:04:14'),
(176, 10, 1, 1, '2024-09-17 22:04:14'),
(177, 20, 1, 1, '2024-09-17 22:04:14'),
(178, 1, 1, 1, '2024-09-17 22:06:45'),
(179, 10, 1, 1, '2024-09-17 22:06:45'),
(180, 20, 1, 1, '2024-09-17 22:06:45'),
(181, 1, 1, 1, '2024-09-17 22:07:48'),
(182, 10, 1, 1, '2024-09-17 22:07:48'),
(183, 20, 1, 1, '2024-09-17 22:07:48'),
(184, 1, 1, 1, '2024-09-17 22:09:06'),
(185, 10, 1, 1, '2024-09-17 22:09:06'),
(186, 20, 1, 1, '2024-09-17 22:09:07'),
(187, 1, 1, 1, '2024-09-17 22:11:14'),
(188, 10, 1, 1, '2024-09-17 22:11:14'),
(189, 20, 1, 1, '2024-09-17 22:11:14'),
(190, 1, 1, 1, '2024-09-18 06:13:14'),
(191, 10, 1, 1, '2024-09-18 06:13:14'),
(192, 20, 1, 1, '2024-09-18 06:13:14'),
(193, 1, 1, 1, '2024-09-18 06:19:58'),
(194, 10, 1, 1, '2024-09-18 06:19:58'),
(195, 20, 1, 1, '2024-09-18 06:19:58'),
(196, 1, 1, 1, '2024-09-18 06:21:41'),
(197, 10, 1, 1, '2024-09-18 06:21:41'),
(198, 20, 1, 1, '2024-09-18 06:21:41'),
(199, 1, 1, 1, '2024-09-18 06:22:36'),
(200, 10, 1, 1, '2024-09-18 06:22:36'),
(201, 20, 1, 1, '2024-09-18 06:22:36'),
(202, 1, 1, 1, '2024-09-18 06:42:29'),
(203, 10, 1, 1, '2024-09-18 06:42:29'),
(204, 20, 1, 1, '2024-09-18 06:42:29'),
(205, 1, 1, 1, '2024-09-18 06:45:35'),
(206, 10, 1, 1, '2024-09-18 06:45:35'),
(207, 20, 1, 1, '2024-09-18 06:45:35'),
(208, 1, 1, 1, '2024-09-18 06:46:50'),
(209, 10, 1, 1, '2024-09-18 06:46:50'),
(210, 20, 1, 1, '2024-09-18 06:46:50'),
(211, 1, 1, 1, '2024-09-18 06:49:59'),
(212, 10, 1, 1, '2024-09-18 06:49:59'),
(213, 20, 1, 1, '2024-09-18 06:49:59'),
(214, 1, 1, 1, '2024-09-18 08:49:05'),
(215, 10, 1, 1, '2024-09-18 08:49:05'),
(216, 20, 1, 1, '2024-09-18 08:49:05'),
(217, 1, 1, 1, '2024-09-18 08:51:42'),
(218, 10, 1, 1, '2024-09-18 08:51:42'),
(219, 20, 1, 1, '2024-09-18 08:51:42'),
(220, 1, 1, 1, '2024-09-18 17:51:53'),
(221, 10, 1, 1, '2024-09-18 17:51:53'),
(222, 20, 1, 1, '2024-09-18 17:51:53'),
(223, 1, 1, 1, '2024-09-18 17:56:36'),
(224, 10, 1, 1, '2024-09-18 17:56:36'),
(225, 20, 1, 1, '2024-09-18 17:56:36'),
(226, 1, 1, 1, '2024-09-18 17:58:54'),
(227, 10, 1, 1, '2024-09-18 17:58:54'),
(228, 20, 1, 1, '2024-09-18 17:58:54'),
(229, 1, 1, 1, '2024-09-18 18:00:01'),
(230, 10, 1, 1, '2024-09-18 18:00:01'),
(231, 20, 1, 1, '2024-09-18 18:00:01'),
(232, 1, 1, 1, '2024-09-18 18:17:29'),
(233, 10, 1, 1, '2024-09-18 18:17:29'),
(234, 20, 1, 1, '2024-09-18 18:17:29'),
(235, 1, 1, 1, '2024-09-18 18:21:54'),
(236, 10, 1, 1, '2024-09-18 18:21:54'),
(237, 20, 1, 1, '2024-09-18 18:21:54'),
(238, 1, 1, 1, '2024-09-18 18:44:13'),
(239, 10, 1, 1, '2024-09-18 18:44:13'),
(240, 20, 1, 1, '2024-09-18 18:44:13'),
(241, 1, 1, 1, '2024-09-18 18:48:32'),
(242, 10, 1, 1, '2024-09-18 18:48:32'),
(243, 20, 1, 1, '2024-09-18 18:48:32'),
(244, 1, 1, 1, '2024-09-18 18:50:49'),
(245, 10, 1, 1, '2024-09-18 18:50:49'),
(246, 20, 1, 1, '2024-09-18 18:50:49'),
(247, 1, 1, 1, '2024-09-18 18:52:54'),
(248, 10, 1, 1, '2024-09-18 18:52:54'),
(249, 20, 1, 1, '2024-09-18 18:52:54'),
(250, 1, 1, 0, '2024-09-18 18:54:31'),
(251, 10, 1, 0, '2024-09-18 18:54:31'),
(252, 20, 1, 0, '2024-09-18 18:54:31'),
(253, 1, 2, 1, '2024-09-18 19:29:39'),
(254, 10, 2, 1, '2024-09-18 19:29:39'),
(255, 20, 2, 1, '2024-09-18 19:29:39'),
(256, 1, 2, 1, '2024-09-18 19:32:53'),
(257, 10, 2, 1, '2024-09-18 19:32:53'),
(258, 20, 2, 1, '2024-09-18 19:32:54'),
(259, 1, 2, 1, '2024-09-18 19:38:31'),
(260, 10, 2, 1, '2024-09-18 19:38:31'),
(261, 20, 2, 1, '2024-09-18 19:38:31'),
(262, 1, 2, 1, '2024-09-18 19:41:51'),
(263, 10, 2, 1, '2024-09-18 19:41:51'),
(264, 20, 2, 1, '2024-09-18 19:41:51'),
(265, 1, 2, 1, '2024-09-18 20:48:41'),
(266, 10, 2, 1, '2024-09-18 20:48:41'),
(267, 20, 2, 1, '2024-09-18 20:48:41'),
(268, 1, 2, 0, '2024-09-18 21:08:25'),
(269, 10, 2, 0, '2024-09-18 21:08:25'),
(270, 20, 2, 0, '2024-09-18 21:08:25');

-- --------------------------------------------------------

--
-- Table structure for table `product_specifications`
--

CREATE TABLE `product_specifications` (
  `id` int(11) NOT NULL,
  `group_name` tinytext NOT NULL,
  `product_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_specifications`
--

INSERT INTO `product_specifications` (`id`, `group_name`, `product_id`, `deleted`, `created_at`) VALUES
(1, 'Dimensions and Weight', 1, 0, '2024-09-17 18:47:57'),
(2, 'Key Features', 1, 0, '2024-09-18 21:17:28'),
(3, 'Design', 1, 0, '2024-09-18 21:21:52'),
(4, 'Performance & Consumption', 1, 0, '2024-09-18 21:21:53'),
(5, 'Connectivity', 1, 0, '2024-09-18 21:44:12'),
(6, 'Programmes', 1, 0, '2024-09-18 21:48:30'),
(7, 'Dimensions &Weight', 1, 0, '2024-09-18 21:50:48'),
(8, 'Functions', 1, 0, '2024-09-18 21:52:53'),
(9, 'Safety', 1, 0, '2024-09-18 21:54:29'),
(10, 'Technologies', 1, 0, '2024-09-18 21:54:29'),
(11, 'Dimensions and Weight', 2, 0, '2024-09-18 22:29:38'),
(12, 'Key Features', 2, 0, '2024-09-18 22:41:49'),
(13, 'Design', 2, 0, '2024-09-18 22:41:50'),
(14, 'Connectivity', 2, 0, '2024-09-18 23:48:39'),
(15, 'Performance & Consumption', 2, 0, '2024-09-18 23:48:39'),
(16, 'Programmes', 2, 0, '2024-09-18 23:48:40'),
(17, 'Functions', 2, 0, '2024-09-19 00:08:23'),
(18, 'Safety', 2, 0, '2024-09-19 00:08:24'),
(19, 'Technologies', 2, 0, '2024-09-19 00:08:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_specifications_key_value_pairs`
--

CREATE TABLE `product_specifications_key_value_pairs` (
  `id` int(11) NOT NULL,
  `property` tinytext NOT NULL,
  `value` mediumtext NOT NULL,
  `product_specifications_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_specifications_key_value_pairs`
--

INSERT INTO `product_specifications_key_value_pairs` (`id`, `property`, `value`, `product_specifications_id`, `deleted`, `created_at`) VALUES
(1, 'Length', '60 cm', 1, 0, '2024-09-17 15:47:58'),
(2, 'Width', '64 cm', 1, 0, '2024-09-17 15:47:59'),
(3, 'Height', '84 cm', 1, 0, '2024-09-17 15:47:59'),
(4, 'Washing Capacity', '10 kg', 2, 0, '2024-09-18 18:17:28'),
(5, 'Energy Efficiency Class', '10% More Efficient Than A+++', 2, 0, '2024-09-18 18:17:28'),
(6, 'Maximum Spin Speed', '1400 rpm', 2, 0, '2024-09-18 18:17:28'),
(7, 'Washing Noise Level', '56 dBA', 2, 0, '2024-09-18 18:17:28'),
(8, 'Spinning Noise Level', '76 dBA', 2, 0, '2024-09-18 18:17:28'),
(9, 'ProSmart Inverter Motor', '', 2, 0, '2024-09-18 18:17:28'),
(10, 'Height', '84 cm', 2, 0, '2024-09-18 18:17:28'),
(11, 'Width', '60 cm', 2, 0, '2024-09-18 18:17:28'),
(12, 'Depth', '64 cm', 2, 0, '2024-09-18 18:17:28'),
(13, 'Steam Technology', 'SteamCure', 2, 0, '2024-09-18 18:17:29'),
(14, 'Display Type', 'Digital Display', 2, 0, '2024-09-18 18:17:29'),
(15, 'Colour', 'Grey', 2, 0, '2024-09-18 18:17:29'),
(16, 'Construction Type', 'Freestanding', 2, 0, '2024-09-18 18:17:29'),
(17, 'Volumaxx', '', 3, 0, '2024-09-18 18:21:52'),
(18, 'AquaWave', '', 3, 0, '2024-09-18 18:21:52'),
(19, 'Drum Light', '', 3, 0, '2024-09-18 18:21:52'),
(20, 'XL Door', '', 3, 0, '2024-09-18 18:21:52'),
(21, 'Display Type', 'Digital Display', 3, 0, '2024-09-18 18:21:52'),
(22, 'Colour', 'Grey', 3, 0, '2024-09-18 18:21:52'),
(23, 'Drum Material', 'Stainless Steel', 3, 0, '2024-09-18 18:21:53'),
(24, 'Washing Capacity', '10 kg', 4, 0, '2024-09-18 18:21:53'),
(25, 'Energy Efficiency Class', '10% More Efficient Than A+++', 4, 0, '2024-09-18 18:21:53'),
(26, 'Maximum Spin Speed', '1400 rpm', 4, 0, '2024-09-18 18:21:53'),
(27, 'Washing Noise Level', '56 dBA', 4, 0, '2024-09-18 18:21:53'),
(28, 'Spinning Noise Level', '76 dBA', 4, 0, '2024-09-18 18:21:53'),
(29, 'Annual Energy Consumption (kWh/year)', '190 kWh', 4, 0, '2024-09-18 18:21:53'),
(30, 'Annual Water Consumption (L/year)', '10779 L', 4, 0, '2024-09-18 18:21:53'),
(31, 'Voltage', '230 V', 4, 0, '2024-09-18 18:21:54'),
(32, 'Frequency', '50 Hz', 4, 0, '2024-09-18 18:21:54'),
(33, 'HomeWhiz Connection Type', 'Bluetooth', 5, 0, '2024-09-18 18:44:12'),
(34, 'Downloadable Programme 1', 'Mixed Programme', 5, 0, '2024-09-18 18:44:12'),
(35, 'Downloadable Programme 2', 'Curtains Programme', 5, 0, '2024-09-18 18:44:13'),
(36, 'Downloadable Programme 3', 'Lingerie Programme', 5, 0, '2024-09-18 18:44:13'),
(37, 'Downloadable Programme 4', 'Outdoor / Sports Programme', 5, 0, '2024-09-18 18:44:13'),
(38, 'Downloadable Programme 5', 'Towels Programme', 5, 0, '2024-09-18 18:44:13'),
(39, 'Number of Programmes', '15', 6, 0, '2024-09-18 18:48:30'),
(40, 'Programme 1', 'Cottons Programme', 6, 0, '2024-09-18 18:48:30'),
(41, 'Programme 2', 'Cottons Eco Programme', 6, 0, '2024-09-18 18:48:31'),
(42, 'Programme 3', 'Synthetics Programme', 6, 0, '2024-09-18 18:48:31'),
(43, 'Programme 4', 'Daily Xpress / Xpress Super Short 14 min Programme', 6, 0, '2024-09-18 18:48:31'),
(44, 'Programme 5', 'Woollens / Hand Wash Programme', 6, 0, '2024-09-18 18:48:31'),
(45, 'Programme 6', 'GentleCare Programme', 6, 0, '2024-09-18 18:48:31'),
(46, 'Programme 7', 'Downloadable Programmes', 6, 0, '2024-09-18 18:48:31'),
(47, 'Programme 8', 'Spin & Pump Programme', 6, 0, '2024-09-18 18:48:31'),
(48, 'Programme 9', 'Rinse Programme', 6, 0, '2024-09-18 18:48:31'),
(49, 'Programme 10', 'Dark Wash / Jeans Programme', 6, 0, '2024-09-18 18:48:31'),
(50, 'Programme 11', 'CoolClean Programme', 6, 0, '2024-09-18 18:48:31'),
(51, 'Programme 12', 'StainExpert Programme', 6, 0, '2024-09-18 18:48:31'),
(52, 'Programme 13', 'Hygiene+ Programme', 6, 0, '2024-09-18 18:48:31'),
(53, 'Programme 14', 'Duvet / Down Wear Programme', 6, 0, '2024-09-18 18:48:31'),
(54, 'Programme 15', 'Shirts Programme', 6, 0, '2024-09-18 18:48:31'),
(55, 'Height', '84 cm', 7, 0, '2024-09-18 18:50:48'),
(56, 'Width', '60 cm', 7, 0, '2024-09-18 18:50:48'),
(57, 'Depth', '64 cm', 7, 0, '2024-09-18 18:50:48'),
(58, 'Weight', '75 kg', 7, 0, '2024-09-18 18:50:48'),
(59, 'Packaged Height', '88 cm', 7, 0, '2024-09-18 18:50:48'),
(60, 'Packaged Width', '65 cm', 7, 0, '2024-09-18 18:50:48'),
(61, 'Packaged Depth', '65 cm', 7, 0, '2024-09-18 18:50:48'),
(62, 'Packaged Weight', '76 kg', 7, 0, '2024-09-18 18:50:48'),
(63, 'Function 1', 'Prewash', 8, 0, '2024-09-18 18:52:53'),
(64, 'Function 2', 'Steam', 8, 0, '2024-09-18 18:52:53'),
(65, 'Function 3', 'Fast+', 8, 0, '2024-09-18 18:52:53'),
(66, 'Function 4', 'Bluetooth', 8, 0, '2024-09-18 18:52:53'),
(67, 'Sub-function 1', 'DrumClean', 8, 0, '2024-09-18 18:52:53'),
(68, 'Sub-function 2', 'Extra Rinse', 8, 0, '2024-09-18 18:52:54'),
(69, 'Sub-function 4', 'Bluetooth', 8, 0, '2024-09-18 18:52:54'),
(70, 'Sub-Function 6', 'Anticrease+', 8, 0, '2024-09-18 18:52:54'),
(71, 'Child Lock', '', 9, 0, '2024-09-18 18:54:29'),
(72, 'Overflow Safety', '', 9, 0, '2024-09-18 18:54:29'),
(73, 'Unbalanced Load Control', '', 9, 0, '2024-09-18 18:54:29'),
(74, 'Automatic Water Adjustment', '', 9, 0, '2024-09-18 18:54:29'),
(75, 'Emergency Water Drain Hose', '', 9, 0, '2024-09-18 18:54:29'),
(76, 'ProSmart Inverter Motor', '', 10, 0, '2024-09-18 18:54:29'),
(77, 'AquaTech', '', 10, 0, '2024-09-18 18:54:30'),
(78, 'Steam Technology', 'SteamCure', 10, 0, '2024-09-18 18:54:30'),
(79, 'OptiSense', '', 10, 0, '2024-09-18 18:54:30'),
(80, 'Depth', '58 cm', 11, 0, '2024-09-18 19:29:38'),
(81, 'Width', '60 cm', 11, 0, '2024-09-18 19:29:38'),
(82, 'Height', '84.5 cm', 11, 0, '2024-09-18 19:29:38'),
(83, 'Weight', '75 kg', 11, 0, '2024-09-18 19:29:38'),
(84, 'Packaged Height', '88.5 cm', 11, 0, '2024-09-18 19:29:38'),
(85, 'Packaged Width', '65 cm', 11, 0, '2024-09-18 19:29:39'),
(86, 'Packaged Depth', '60 cm', 11, 0, '2024-09-18 19:29:39'),
(87, 'Packaged Weight', '76 kg', 11, 0, '2024-09-18 19:29:39'),
(88, 'Washing Capacity', '10 kg', 12, 0, '2024-09-18 19:41:49'),
(89, 'Energy Efficiency Class', 'A+++', 12, 0, '2024-09-18 19:41:49'),
(90, 'Maximum Spin Speed', '1400 rpm', 12, 0, '2024-09-18 19:41:49'),
(91, 'Washing Noise Level', '54 dBA', 12, 0, '2024-09-18 19:41:49'),
(92, 'Spinning Noise Level', '76 dBA', 12, 0, '2024-09-18 19:41:49'),
(93, 'ProSmart Inverter Motor', '', 12, 0, '2024-09-18 19:41:49'),
(94, 'Height', '84.5 cm', 12, 0, '2024-09-18 19:41:49'),
(95, 'Width', '60 cm', 12, 0, '2024-09-18 19:41:50'),
(96, 'Depth', '58 cm', 12, 0, '2024-09-18 19:41:50'),
(97, 'Steam Technology', 'Steamcure with Refreshment', 12, 0, '2024-09-18 19:41:50'),
(98, 'Display Type', 'Digital Display', 12, 0, '2024-09-18 19:41:50'),
(99, 'Colour', 'Manhattan Grey', 12, 0, '2024-09-18 19:41:50'),
(100, 'Construction Type', 'Freestanding', 12, 0, '2024-09-18 19:41:50'),
(101, 'Volumaxx', 'X54', 13, 0, '2024-09-18 19:41:50'),
(102, 'AquaWave', '', 13, 0, '2024-09-18 20:48:39'),
(103, 'Drum Light', '', 13, 0, '2024-09-18 20:48:39'),
(104, 'XL Door', 'Beyond w/ Cover', 13, 0, '2024-09-18 20:48:39'),
(105, 'Display Type', 'Digital Display', 13, 0, '2024-09-18 20:48:39'),
(106, 'Colour', 'Manhattan Grey', 13, 0, '2024-09-18 20:48:39'),
(107, 'Drum Material', 'Stainless Steel', 13, 0, '2024-09-18 20:48:39'),
(108, 'HomeWhiz Connection Type', 'Bluetooth', 14, 0, '2024-09-18 20:48:39'),
(109, 'Downloadable Programme 1', 'Mixed Programme', 14, 0, '2024-09-18 20:48:39'),
(110, 'Downloadable Programme 2', 'Towel', 14, 0, '2024-09-18 20:48:39'),
(111, 'Downloadable Programme 3', 'Plush Toys Programme', 14, 0, '2024-09-18 20:48:39'),
(112, 'Downloadable Programme 4', 'Curtains Programme', 14, 0, '2024-09-18 20:48:39'),
(113, 'Washing Capacity', '10 kg', 15, 0, '2024-09-18 20:48:39'),
(114, 'Energy Efficiency Class', 'A+++', 15, 0, '2024-09-18 20:48:39'),
(115, 'Maximum Spin Speed', '1400 rpm', 15, 0, '2024-09-18 20:48:39'),
(116, 'Washing Noise Level', '54 dBA', 15, 0, '2024-09-18 20:48:40'),
(117, 'Spinning Noise Level', '76 dBA', 15, 0, '2024-09-18 20:48:40'),
(118, 'Annual Energy Consumption (kWh/year)', '235 kWh', 15, 0, '2024-09-18 20:48:40'),
(119, 'Annual Water Consumption (L/year)', '10339 L', 15, 0, '2024-09-18 20:48:40'),
(120, 'Voltage', '230 V', 15, 0, '2024-09-18 20:48:40'),
(121, 'Frequency', '50 Hz', 15, 0, '2024-09-18 20:48:40'),
(122, 'Number of Programmes', '15', 16, 0, '2024-09-18 20:48:40'),
(123, 'Programme 1', 'Cottons Programme', 16, 0, '2024-09-18 20:48:40'),
(124, 'Programme 2', 'Cottons Eco Programme', 16, 0, '2024-09-18 20:48:40'),
(125, 'Programme 3', 'Synthetics Programme', 16, 0, '2024-09-18 20:48:40'),
(126, 'Programme 4', 'Daily Xpress / Xpress Super Short 14 min Programme', 16, 0, '2024-09-18 20:48:40'),
(127, 'Programme 5', 'Woollens / Hand Wash Programme', 16, 0, '2024-09-18 20:48:40'),
(128, 'Programme 6', 'DarkWash/Jeans', 16, 0, '2024-09-18 20:48:40'),
(129, 'Programme 7', 'Downloadable Programmes', 16, 0, '2024-09-18 20:48:40'),
(130, 'Programme 8', 'Spin & Pump Programme', 16, 0, '2024-09-18 20:48:40'),
(131, 'Programme 9', 'Rinse Programme', 16, 0, '2024-09-18 20:48:40'),
(132, 'Programme 10', 'Outdoor / Sports Programme', 16, 0, '2024-09-18 20:48:40'),
(133, 'Programme 11', 'StainExpert Programme', 16, 0, '2024-09-18 20:48:40'),
(134, 'Programme 12', 'Hygiene+ Programme', 16, 0, '2024-09-18 20:48:40'),
(135, 'Programme 13', 'Duvet / Down Wear Programme', 16, 0, '2024-09-18 20:48:40'),
(136, 'Programme 14', 'Shirts Programme', 16, 0, '2024-09-18 20:48:40'),
(137, 'Programme 15', 'SteamTherapy Programme', 16, 0, '2024-09-18 20:48:41'),
(138, 'Function 1', 'Prewash', 17, 0, '2024-09-18 21:08:23'),
(139, 'Function 2', 'Steam', 17, 0, '2024-09-18 21:08:24'),
(140, 'Function 3', 'Fast+', 17, 0, '2024-09-18 21:08:24'),
(141, 'Function 4', 'Bluetooth', 17, 0, '2024-09-18 21:08:24'),
(142, 'Sub-function 1', 'DrumClean', 17, 0, '2024-09-18 21:08:24'),
(143, 'Sub-function 2', 'Extra Rinse', 17, 0, '2024-09-18 21:08:24'),
(144, 'Sub-function 4', 'Bluetooth', 17, 0, '2024-09-18 21:08:24'),
(145, 'Sub-Function 6', 'Anticrease+', 17, 0, '2024-09-18 21:08:24'),
(146, 'Child Lock', '', 18, 0, '2024-09-18 21:08:24'),
(147, 'Overflow Safety', '', 18, 0, '2024-09-18 21:08:24'),
(148, 'Unbalanced Load Control', '', 18, 0, '2024-09-18 21:08:24'),
(149, 'Automatic Water Adjustment', '', 18, 0, '2024-09-18 21:08:24'),
(150, 'Emergency Water Drain Hose', '', 18, 0, '2024-09-18 21:08:24'),
(151, 'ProSmart Inverter Motor', '', 19, 0, '2024-09-18 21:08:24'),
(152, 'AquaTech', '', 19, 0, '2024-09-18 21:08:24'),
(153, 'Steam Technology', 'Steamcure with Refreshment', 19, 0, '2024-09-18 21:08:24'),
(154, 'OptiSense', '', 19, 0, '2024-09-18 21:08:25');

-- --------------------------------------------------------

--
-- Table structure for table `product_varieties`
--

CREATE TABLE `product_varieties` (
  `id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `price` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  `product_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_varieties`
--

INSERT INTO `product_varieties` (`id`, `name`, `price`, `description`, `product_id`, `deleted`, `created_at`) VALUES
(1, 'Grey', 1, 'Discover the epitome of laundry excellence with the Beko Freestanding Washing Machine (BAW 101).', 1, 0, '2024-09-17 15:47:55'),
(2, 'Manhattan Grey', 1, 'This Manhattan Grey marvel boasts a capacious 10kg washing capacity, seamlessly integrating into your life with its flexible time delay feature, allowing you to schedule laundry around your day.', 2, 0, '2024-09-18 19:29:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories_relations`
--
ALTER TABLE `product_categories_relations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_specifications_key_value_pairs`
--
ALTER TABLE `product_specifications_key_value_pairs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_varieties`
--
ALTER TABLE `product_varieties`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `product_categories_relations`
--
ALTER TABLE `product_categories_relations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT for table `product_specifications`
--
ALTER TABLE `product_specifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_specifications_key_value_pairs`
--
ALTER TABLE `product_specifications_key_value_pairs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `product_varieties`
--
ALTER TABLE `product_varieties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
