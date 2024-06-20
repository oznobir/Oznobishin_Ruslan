-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 20 2024 г., 18:28
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `myshop2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `blocked_access`
--

CREATE TABLE `blocked_access` (
  `id` int NOT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trying` tinyint(1) DEFAULT NULL,
  `time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `catalog`
--

CREATE TABLE `catalog` (
  `id` int NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `keywords` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pid` int DEFAULT NULL,
  `position` int DEFAULT NULL,
  `visible` tinyint DEFAULT '1',
  `alias` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `catalog`
--

INSERT INTO `catalog` (`id`, `name`, `keywords`, `description`, `pid`, `position`, `visible`, `alias`, `img`) VALUES
(1, 'Телефоны', '', '', NULL, 1, 1, 'telefony', 'catalog/telefony.webp'),
(2, 'Планшеты', '', '', NULL, 2, 1, 'planshety', 'catalog/planshety.webp'),
(3, 'Мониторы', '', '', NULL, 4, 1, 'monitory', 'catalog/monitory.webp'),
(4, 'Ноутбуки', '', '', NULL, 3, 1, 'noutbuki', 'catalog/noutbuki.webp');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `pid` int DEFAULT NULL,
  `slug` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `position` int DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `pid`, `slug`, `name`, `position`, `img`) VALUES
(1, 0, 'mobile', 'Телефоны', 1, ''),
(2, 0, 'planshety', 'Планшеты', 2, ''),
(3, 0, 'noutbuki', 'Ноутбуки', 3, ''),
(4, 0, 'monitory', 'Мониторы', 4, ''),
(5, 1, 'phone-xiaomi', 'Телефоны Xiaomi', 2, ''),
(6, 1, 'phone-apple', 'Телефоны Apple', 1, ''),
(7, 1, 'phone-samsung', 'Телефоны Samsung', 3, ''),
(8, 2, 'planshety-lenovo', 'Планшеты Lenovo', 1, ''),
(9, 3, 'noutbuki-asus', 'Ноутбуки Asus', 3, ''),
(10, 3, 'noutbuki-digma', 'Ноутбуки digma', 2, ''),
(11, 3, 'noutbuki-apple', 'Ноутбуки apple', 1, '');

-- --------------------------------------------------------

--
-- Структура таблицы `cat_filters`
--

CREATE TABLE `cat_filters` (
  `id` int NOT NULL,
  `name` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `cat_filters`
--

INSERT INTO `cat_filters` (`id`, `name`) VALUES
(1, 'CPU'),
(2, 'Display'),
(3, 'ROM'),
(4, 'OS'),
(5, 'RAM');

-- --------------------------------------------------------

--
-- Структура таблицы `color`
--

CREATE TABLE `color` (
  `id` int NOT NULL,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `alias` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `color`
--

INSERT INTO `color` (`id`, `name`, `img`, `alias`) VALUES
(1, 'красный', 'color/img_red.jpg', 'krasniy'),
(2, 'желтый', 'color/img_yellow.jpg', 'zheltiy'),
(3, 'черный', 'color/img_black.jpg', 'cherniy'),
(4, 'белый', 'color/img_white.jpg', 'beliy'),
(5, 'зеленый', 'color/img_green.jpg', 'zeleniy'),
(6, 'голубой', 'color/img_blue.jpg', 'goluboy'),
(7, 'розовый', 'color/img_pink.jpg', 'rozoviy'),
(8, 'коричневый', 'color/img_brown.jpg', 'korichneviy');

-- --------------------------------------------------------

--
-- Структура таблицы `color_goods`
--

CREATE TABLE `color_goods` (
  `id` int NOT NULL,
  `goods_id` int NOT NULL,
  `color_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `color_goods`
--

INSERT INTO `color_goods` (`id`, `goods_id`, `color_id`) VALUES
(301, 23, 8),
(302, 23, 3),
(311, 26, 4),
(312, 26, 3),
(317, 25, 4),
(318, 25, 8),
(319, 25, 1),
(320, 25, 3),
(321, 24, 4),
(322, 24, 6),
(323, 24, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `country_manufacturers`
--

CREATE TABLE `country_manufacturers` (
  `id` int NOT NULL,
  `name` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `country_manufacturers`
--

INSERT INTO `country_manufacturers` (`id`, `name`) VALUES
(1, 'USA'),
(2, 'Korea'),
(3, 'China');

-- --------------------------------------------------------

--
-- Структура таблицы `filters`
--

CREATE TABLE `filters` (
  `id` int NOT NULL,
  `filters_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `pid` int DEFAULT NULL,
  `position` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `filters`
--

INSERT INTO `filters` (`id`, `filters_name`, `pid`, `position`) VALUES
(1, 'CPU1', NULL, 1),
(2, 'Display1', NULL, 2),
(3, 'ROM1', NULL, 3),
(4, 'OS1', NULL, 4),
(5, 'Snapdragon', 1, 4),
(6, 'Apple', 1, 3),
(7, 'TN+Film', 2, 4),
(8, 'Amoled', 2, 2),
(9, 'Intel', 1, 1),
(10, 'RAM1', NULL, 5),
(13, 'HDD 1000', 3, 4),
(14, 'UFS 256', 3, 3),
(15, 'SSD 512', 3, 2),
(16, 'Android', 4, 4),
(17, 'iOS', 4, 3),
(18, 'Windows', 4, 2),
(19, 'AMD', 1, 2),
(21, 'IPS', 2, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `filters_goods`
--

CREATE TABLE `filters_goods` (
  `goods_id` int NOT NULL,
  `filters_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `filters_goods`
--

INSERT INTO `filters_goods` (`goods_id`, `filters_id`) VALUES
(23, 5),
(24, 5),
(26, 5),
(25, 6),
(23, 8),
(24, 8),
(25, 8),
(23, 14),
(24, 15),
(25, 15),
(26, 15),
(23, 16),
(24, 16),
(25, 16),
(26, 16),
(26, 21);

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int NOT NULL,
  `pid` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_general_ci,
  `price` float DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gallery_img` text COLLATE utf8mb4_general_ci,
  `visible` tinyint DEFAULT '1',
  `position` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount` int DEFAULT NULL,
  `hit` tinyint DEFAULT '0',
  `sale` tinyint DEFAULT '0',
  `new` tinyint DEFAULT '0',
  `hot` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `pid`, `name`, `content`, `price`, `img`, `gallery_img`, `visible`, `position`, `date`, `datetime`, `alias`, `discount`, `hit`, `sale`, `new`, `hot`) VALUES
(23, 1, 'Смартфон Xiaomi 13T Pro 12GB/512GB международная версия', '<p>Android, экран 6.67\" AMOLED (1220x2712) 144 Гц, Mediatek Dimensity 9200+, ОЗУ 12 ГБ, память 512 ГБ, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM/eSIM), влагозащита IP68</p>', 2500, 'goods/img_11_1711042128249.webp', '[\"goods\\/img_7_1711072021871_34b58b1e.webp\",\"goods\\/img_11_1711042128249_7785e565.webp\",\"goods\\/img_15_1711131941591_ef03aa96.webp\"]', 1, 1, '2024-06-20', '2024-06-20 18:17:19', 'smartfon-xiaomi-13t-pro-12gb512gb-mezhdunarodnaya-versiya', 0, 0, 1, 1, 0),
(24, 1, 'Смартфон Samsung Galaxy Z Flip5 SM-F731B/DS 8GB/256GB', '<p>Android, экран 6.7\" AMOLED (1080x2640) 120 Гц, Qualcomm Snapdragon 8 Gen2 SM8550, ОЗУ 8 ГБ, память 256 ГБ, камера 12 Мп, аккумулятор 3700 мАч, 1 SIM (nano-SIM/eSIM), влагозащита IPX8</p>', 2980, 'goods/img_14_1711042460430_48eabf6a.webp', '[\"goods\\/img_10_1711041932595_382de75e.webp\",\"goods\\/img_7_1711072021871_7abce657.webp\",\"goods\\/img_11_1711042128249_8afc5df0.webp\",\"goods\\/img_15_1711131941591_ab519c92.webp\"]', 1, 2, '2024-06-20', '2024-06-20 18:20:00', 'smartfon-samsung-galaxy-z-flip5-sm-f731bds-8gb256gb', 0, 0, 0, 1, 1),
(25, 1, 'Смартфон Apple iPhone 15 128GB', '<p>Apple iOS, экран 6.1\" OLED (1179x2556) 60 Гц, Apple A16 Bionic, ОЗУ 6 ГБ, память 128 ГБ, камера 48 Мп, 1 SIM (nano-SIM/eSIM), влагозащита IP68</p>', 3500.05, 'goods/img_7_1711072021871.webp', '[\"goods\\/6_1711041613282_5e431ba5.webp\",\"goods\\/img_10_1711041932595_a0d8c876.webp\",\"goods\\/img_11_1711042128249_b5eddb2a.webp\",\"goods\\/img_15_1711131941591_bd26e2e2.webp\"]', 1, 3, '2024-06-20', '2024-06-20 18:19:26', 'smartfon-apple-iphone-15-128gb', 0, 0, 0, 0, 1),
(26, 2, 'Планшет Lenovo Tab P11 Plus TB-J616X 6GB/128GB LTE', '<p>11\" IPS, 60 Гц (2000x1200), Android, MediaTek Helio G90T, ОЗУ 6 ГБ, флэш-память 128 ГБ, цвет серый</p>', 1500.15, 'goods/img_16_1713087551260.webp', '[\"goods\\/17_1713087825459_4ffec7fa.webp\",\"goods\\/img_16_1713087551260_10ab666b.webp\",\"goods\\/img_22_1713090477061_a701baae.webp\"]', 1, 1, '2024-06-20', '2024-06-20 18:18:29', 'planshet-lenovo-tab-p11-plus-tb-j616x-6gb128gb-lte', 0, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `information`
--

CREATE TABLE `information` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keywords` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `visible` tinyint DEFAULT NULL,
  `position` int DEFAULT NULL,
  `show_top_menu` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `information`
--

INSERT INTO `information` (`id`, `name`, `alias`, `keywords`, `description`, `visible`, `position`, `show_top_menu`) VALUES
(1, 'Оплата и доставка', 'oplata-i-dostavka', '', '', 1, 1, 1),
(2, 'Акции и скидки', 'aktsii-i-skidki', '', '', 1, 2, 1),
(3, 'Политика конфиденциальности', 'politika-konfidentsialnosti', '', '', 1, 3, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer`
--

CREATE TABLE `manufacturer` (
  `id` int NOT NULL,
  `name` text COLLATE utf8mb4_general_ci,
  `pid` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `manufacturer`
--

INSERT INTO `manufacturer` (`id`, `name`, `pid`) VALUES
(1, 'Apple', 1),
(2, 'Samsung', 2),
(3, 'Xiaomi', 3),
(4, 'Lenovo', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer_goods`
--

CREATE TABLE `manufacturer_goods` (
  `goods_id` int NOT NULL,
  `manufacturer_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `manufacturer_goods`
--

INSERT INTO `manufacturer_goods` (`goods_id`, `manufacturer_id`) VALUES
(25, 1),
(24, 2),
(23, 3),
(26, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `old_alias`
--

CREATE TABLE `old_alias` (
  `alias` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `table_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `old_alias`
--

INSERT INTO `old_alias` (`alias`, `table_name`, `table_id`) VALUES
('green', 'color', 5),
('red', 'color', 1),
('yellow', 'color', 2),
('white', 'color', 4),
('black', 'color', 3),
('pink', 'color', 7),
('brown', 'color', 8),
('blue', 'color', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `parsing_table`
--

CREATE TABLE `parsing_table` (
  `all_links` longtext COLLATE utf8mb4_general_ci,
  `temp_links` longtext COLLATE utf8mb4_general_ci,
  `bad_links` longtext COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `pid` int NOT NULL,
  `slug` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` float NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `position` int DEFAULT NULL,
  `visible` tinyint DEFAULT NULL,
  `gallery_img` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `pid`, `slug`, `name`, `description`, `price`, `img`, `status`, `position`, `visible`, `gallery_img`) VALUES
(1, 5, 'redmi-10c-nfc', 'Смартфон Xiaomi Redmi 10C NFC 4GB/128GB международная версия (серый)', 'Android, экран 6.71\" IPS (720x1650) 60 Гц, Qualcomm Snapdragon 680, ОЗУ 4 ГБ, память 128 ГБ, поддержка карт памяти, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM) ', 430, 'img_1_1711039208066.png', 1, 1, 1, NULL),
(2, 5, 'redmi-note-12-nfc', 'Смартфон Xiaomi Redmi Note 12 6GB/128GB с NFC международная версия (серый оникс)', 'Android, экран 6.67\" AMOLED (1080x2400) 120 Гц, Qualcomm Snapdragon 685, ОЗУ 6 ГБ, память 128 ГБ, поддержка карт памяти, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM), влагозащита IP53 ', 685.5, 'img_2_1711039396535.png', 1, 2, 1, NULL),
(3, 5, '13t-pro-black', 'Смартфон Xiaomi 13T Pro 12GB/512GB международная версия (черный)', '<p>Android, экран 6.67\" AMOLED 144 Гц, Mediatek 9200+, ОЗУ 12 ГБ, память 512 ГБ, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM/eSIM), влагозащита IP68</p>', 2501, 'img_3_1711039508491.png', 1, 3, 1, NULL),
(4, 6, 'iphone-14-128gb-polunochnyi', 'Смартфон Apple iPhone 14 128GB (полуночный)', 'Apple iOS, экран 6.1\" OLED (1170x2532) 60 Гц, Apple A15 Bionic, ОЗУ 6 ГБ, память 128 ГБ, камера 12 Мп, 1 SIM (nano-SIM/eSIM), влагозащита IP68 ', 2935, 'img_4_1711041124918.webp', 1, 1, 1, NULL),
(5, 6, 'iphone-11-128gb-chernyi', 'Смартфон Apple iPhone 11 128GB (черный)', 'Apple iOS, экран 6.1\" IPS (828x1792), Apple A13 Bionic, ОЗУ 4 ГБ, память 128 ГБ, камера 12 Мп, аккумулятор 3046 мАч, 1 SIM (nano-SIM/eSIM), влагозащита IP68', 2115.35, 'img_5_1711041487549.webp', 1, 2, 1, NULL),
(6, 6, 'iphone-15-128gb-chernyi', 'Смартфон Apple iPhone 15 128GB (черный)', 'Apple iOS, экран 6.1\" OLED (1179x2556) 60 Гц, Apple A16 Bionic, ОЗУ 6 ГБ, память 128 ГБ, камера 48 Мп, 1 SIM (nano-SIM/eSIM), влагозащита IP68 ', 3500.5, 'img_6_1711041613282.webp', 1, 3, 1, NULL),
(7, 6, 'iphone-13-128gb-zelenyi', 'Apple iPhone 13 128GB (зеленый)', 'Apple iOS, экран 6.1\" OLED (1170x2532) 60 Гц, Apple A15 Bionic, ОЗУ 4 ГБ, память 128 ГБ, камера 12 Мп, аккумулятор 3227 мАч, 1 SIM (nano-SIM/eSIM), влагозащита IP68 ', 2603, 'img_7_1711072021871.webp', 1, 4, 1, NULL),
(10, 7, 'galaxy-s22-ultra-5g-sm-chernyi', 'Смартфон Samsung Galaxy S22 Ultra 5G SM-S908B/DS 12GB/256GB (черный фантом)', 'Android, экран 6.8\" AMOLED (1440x3088) 120 Гц, Exynos 2200, ОЗУ 12 ГБ, память 256 ГБ, камера 108 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM/eSIM), влагозащита IP68                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         ', 2516, 'img_10_1711041932595.webp', 1, 1, 1, NULL),
(11, 7, 'galaxy-a23-sm-a23-persikovyi', 'Samsung Galaxy A23 SM-A235F/DSN 4GB/64GB (персиковый)', 'Android, экран 6.6\" PLS (1080x2408) 90 Гц, Qualcomm Snapdragon 680, ОЗУ 4 ГБ, память 64 ГБ, поддержка карт памяти, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM) ', 763.5, 'img_11_1711042128249.webp', 1, 2, 1, NULL),
(14, 7, 'galaxy-z-flip5-bezhevyi', 'Смартфон Samsung Galaxy Z Flip5 SM-F731B/DS 8GB/256GB (бежевый)', 'Android, экран 6.7\" AMOLED (1080x2640) 120 Гц, Qualcomm Snapdragon 8 Gen2 SM8550, ОЗУ 8 ГБ, память 256 ГБ, камера 12 Мп, аккумулятор 3700 мАч, 1 SIM (nano-SIM/eSIM), влагозащита IPX8\r\n', 2976.5, 'img_14_1711042460430.webp', 1, 3, 1, NULL),
(15, 7, 'galaxy-a01-sm-a015fds-chernyi', 'Смартфон Samsung Galaxy A01 SM-A015F/DS (черный)', 'Android, экран 5.7\" PLS (720x1560), Qualcomm Snapdragon 439, ОЗУ 2 ГБ, память 16 ГБ, поддержка карт памяти, камера 13 Мп, аккумулятор 3000 мАч, 2 SIM (nano-SIM) ', 210, 'img_15_1711131941591.webp', 1, 4, 1, NULL),
(16, 8, 'lenovo-tab-m10-plus-3rd-gen-tb-128xu', 'Планшет Lenovo Tab M10 Plus 3rd Gen TB-128XU 4GB/128GB LTE (серый)', '10.61\" IPS, 60 Гц (2000x1200), Android, Qualcomm Snapdragon 680, ОЗУ 4 ГБ, флэш-память 128 ГБ, цвет серый', 782.7, 'img_16_1713087551260.webp', 1, 1, 1, NULL),
(17, 8, 'lenovo-tab-p11-plus-tb-j616x-6gb128gb', 'Планшет Lenovo Tab P11 Plus TB-J616X 6GB/128GB LTE (серый)', '11\" IPS, 60 Гц (2000x1200), Android, MediaTek Helio G90T, ОЗУ 6 ГБ, флэш-память 128 ГБ, цвет серый', 1482, 'img_17_1713087825459.webp', 1, 2, 1, NULL),
(18, 9, 'asus-rog-strix-g15-g513rc-hn133', 'Игровой ноутбук ASUS ROG Strix G15 G513RC-HN133', '15.6\" 1920 x 1080, IPS, 144 Гц, AMD Ryzen 7 6800H, 16 ГБ DDR5, SSD 512 ГБ, видеокарта NVIDIA GeForce RTX 3050 4 ГБ (TGP 95 Вт), без ОС, цвет крышки черный, аккумулятор 56 Вт·ч', 4005, 'img_18_1713088418727.webp', 1, 1, 1, NULL),
(19, 9, 'asus-tuf-gaming-a17-fa706ihrb-hx050', 'Игровой ноутбук ASUS TUF Gaming A17 FA706IHRB-HX050', '17.3\" 1920 x 1080, IPS, 144 Гц, AMD Ryzen 5 4600H, 16 ГБ DDR4, SSD 512 ГБ, видеокарта NVIDIA GeForce GTX 1650 4 ГБ GDDR6, без ОС, цвет крышки черный, аккумулятор 48 Вт·ч', 3222, 'img_19_1713088786380.webp', 0, 2, 1, NULL),
(20, 10, 'digma-eve-c5403-dn15cn-4bxw02', 'Ноутбук Digma EVE C5403 DN15CN-4BXW02', '15.6\" 1920 x 1080, IPS, 60 Гц, Intel Celeron N4020, 4 ГБ LPDDR4, SSD 128 ГБ, видеокарта встроенная, Windows 11 Pro, цвет крышки серебристый, аккумулятор 37 Вт·ч', 1000, 'img_20_1713089106633.webp', 1, 1, 1, NULL),
(21, 10, 'digma-pro-fortis-m-dn15p5-8cxn01', 'Ноутбук Digma Pro Fortis M DN15P5-8CXN01', '15.6\" 1920 x 1080, IPS, 60 Гц, Intel Core i5 10210U 1600 МГц, 8 ГБ, SSD 512 ГБ, видеокарта встроенная, без ОС, цвет крышки серебристый', 1705, 'img_21_1713089225500.webp', 1, 2, 1, NULL),
(22, 11, 'apple-macbook-air-13-m1-2020-mgn63', 'Ноутбук Apple Macbook Air 13\" M1 2020 MGN63', '13.3\" 2560 x 1600, IPS, 60 Гц, Apple M1, 8 ГБ, SSD 256 ГБ, видеокарта встроенная, Mac OS, цвет крышки серый, аккумулятор 49.9 Вт·ч', 3627, 'img_22_1713090477061.webp', 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sub_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `position` int DEFAULT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `external_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `short_content` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `sales`
--

INSERT INTO `sales` (`id`, `name`, `sub_title`, `position`, `visible`, `img`, `external_url`, `short_content`) VALUES
(1, 'Акция 1', 'Продажа', 1, 1, 'sales/services.jpg', '/catalog', 'Два по цене один. Два по цене один. Два по цене один. Два по цене один.Два по цене один. Два по цене один.Два по цене один.'),
(2, 'Акция 2', 'Услуги', 2, 1, 'sales/services-detail1.jpg', '/catalog', 'За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. '),
(3, 'Акция 3', 'Пустышка', 3, 1, 'sales/sservices1.jpg', '/catalog/null1', 'Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . ');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keywords` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `img_logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `img_years` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `number_years` varchar(63) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `name`, `keywords`, `description`, `phone`, `email`, `img_logo`, `address`, `img_years`, `number_years`) VALUES
(1, 'Магазин товаров', '', '', '8-800-000-00-00', 'test@myshop2.by', 'settings/myshop_407e28ce.png', 'г. Москва пер.Машиностроителей, д.12, к.12', 'settings/15.svg', '15');

-- --------------------------------------------------------

--
-- Структура таблицы `socials`
--

CREATE TABLE `socials` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icons_svg` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `external_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '1',
  `position` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `socials`
--

INSERT INTO `socials` (`id`, `name`, `icons_svg`, `external_url`, `visible`, `position`) VALUES
(1, 'instagram', 'assets/img/icons.svg#instagram', 'https://instagram.com', 1, 1),
(2, 'vk', 'assets/img/icons.svg#vk', 'https://vk.com', 1, 2),
(3, 'facebook', 'assets/img/icons.svg#facebook', 'https://facebook.com', 1, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `credentials` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `login`, `password`, `credentials`) VALUES
(1, 'admin', 'admin', '202cb962ac59075b964b07152d234b70', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `blocked_access`
--
ALTER TABLE `blocked_access`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `catalog`
--
ALTER TABLE `catalog`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `categories_categories_id_fk` (`pid`);

--
-- Индексы таблицы `cat_filters`
--
ALTER TABLE `cat_filters`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `color_goods`
--
ALTER TABLE `color_goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_articles` (`goods_id`),
  ADD KEY `id_manyarticles` (`color_id`);

--
-- Индексы таблицы `country_manufacturers`
--
ALTER TABLE `country_manufacturers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `filters_cat_filters_id_fk` (`pid`);

--
-- Индексы таблицы `filters_goods`
--
ALTER TABLE `filters_goods`
  ADD PRIMARY KEY (`goods_id`,`filters_id`),
  ADD KEY `filters_id` (`filters_id`);

--
-- Индексы таблицы `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Индексы таблицы `information`
--
ALTER TABLE `information`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manufacturer_country_manufacturers_id_fk` (`pid`);

--
-- Индексы таблицы `manufacturer_goods`
--
ALTER TABLE `manufacturer_goods`
  ADD PRIMARY KEY (`goods_id`,`manufacturer_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`pid`);

--
-- Индексы таблицы `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `socials`
--
ALTER TABLE `socials`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `blocked_access`
--
ALTER TABLE `blocked_access`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `catalog`
--
ALTER TABLE `catalog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `cat_filters`
--
ALTER TABLE `cat_filters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `color`
--
ALTER TABLE `color`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `color_goods`
--
ALTER TABLE `color_goods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=324;

--
-- AUTO_INCREMENT для таблицы `country_manufacturers`
--
ALTER TABLE `country_manufacturers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `filters`
--
ALTER TABLE `filters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT для таблицы `information`
--
ALTER TABLE `information`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `socials`
--
ALTER TABLE `socials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_categories_id_fk` FOREIGN KEY (`pid`) REFERENCES `categories` (`id`);

--
-- Ограничения внешнего ключа таблицы `color_goods`
--
ALTER TABLE `color_goods`
  ADD CONSTRAINT `color_goods_ibfk_1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `color_goods_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `color` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `filters`
--
ALTER TABLE `filters`
  ADD CONSTRAINT `filters_cat_filters_id_fk` FOREIGN KEY (`pid`) REFERENCES `cat_filters` (`id`);

--
-- Ограничения внешнего ключа таблицы `filters_goods`
--
ALTER TABLE `filters_goods`
  ADD CONSTRAINT `filters_goods_ibfk_1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `filters_goods_ibfk_2` FOREIGN KEY (`filters_id`) REFERENCES `filters` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `goods`
--
ALTER TABLE `goods`
  ADD CONSTRAINT `goods_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `catalog` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD CONSTRAINT `manufacturer_country_manufacturers_id_fk` FOREIGN KEY (`pid`) REFERENCES `country_manufacturers` (`id`);

--
-- Ограничения внешнего ключа таблицы `manufacturer_goods`
--
ALTER TABLE `manufacturer_goods`
  ADD CONSTRAINT `manufacturer_goods_ibfk_1` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `manufacturer_goods_ibfk_2` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_categories_id_fk` FOREIGN KEY (`pid`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
