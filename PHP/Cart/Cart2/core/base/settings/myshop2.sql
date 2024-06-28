-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 28 2024 г., 21:41
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
-- admin/login admin password 123

-- --------------------------------------------------------

--
-- Структура таблицы `advantages`
--

CREATE TABLE `advantages` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `position` int DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `advantages`
--

INSERT INTO `advantages` (`id`, `name`, `img`, `position`, `visible`) VALUES
(1, 'Опыт работы свыше 14 лет', 'advantages/adv1.png', 1, 1),
(2, 'Комплексный подход', 'advantages/adv2.png', 2, 1),
(3, 'Квалифицированные сотрудники', 'advantages/adv3.png', 3, 1),
(4, 'Долгосрочное сотрудничество', 'advantages/adv4.png', 4, 1),
(5, 'Работаем со всеми современными системами', 'advantages/adv5.png', 5, 1),
(6, 'Гарантия качества', 'advantages/adv6.png', 6, 1);

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
(401, 64, 6),
(402, 64, 2),
(403, 64, 5),
(404, 64, 8),
(405, 64, 1),
(481, 63, 4),
(482, 63, 6),
(483, 63, 5),
(484, 63, 1),
(485, 63, 3),
(486, 62, 6),
(487, 62, 2),
(488, 62, 7),
(489, 62, 3),
(490, 23, 8),
(491, 23, 3),
(492, 24, 4),
(493, 24, 6),
(494, 24, 7),
(495, 25, 4),
(496, 25, 8),
(497, 25, 1),
(498, 25, 3),
(499, 61, 1),
(500, 61, 3),
(501, 65, 4),
(502, 65, 6),
(503, 65, 2),
(504, 65, 5),
(505, 65, 8),
(506, 65, 3),
(507, 66, 4),
(508, 66, 6),
(509, 66, 2),
(510, 66, 8),
(511, 66, 1),
(512, 26, 4),
(513, 26, 3);

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
(5, 'RAM1', NULL, 5),
(6, 'Apple A16 Bionic', 1, 6),
(7, 'TN+Film', 2, 7),
(8, '6.67 AMOLED', 2, 11),
(9, 'Intel', 1, 2),
(13, 'HDD 1000 Gb', 5, 3),
(14, 'UFS 256 Gb', 5, 2),
(15, 'SSD 512 Gb', 5, 1),
(16, 'Android', 4, 4),
(17, 'iOS', 4, 3),
(18, 'Windows', 4, 2),
(19, 'AMD', 1, 5),
(21, 'IPS', 2, 6),
(24, 'UFS 128 Gb', 5, 4),
(25, 'UFS 64 Gb', 5, 3),
(26, '4Gb', 3, 2),
(27, '2 Gb', 3, 3),
(28, '6 Gb', 3, 3),
(29, 'Mediatek G91', 1, 1),
(30, 'Mediatek Helio G85', 1, 4),
(31, 'Snapdragon 685', 1, 3),
(32, 'Apple A17 Pro', 1, 7),
(33, 'Apple A14', 1, 8),
(34, 'Exynos 2400', 1, 9),
(35, 'Exynos 1200', 1, 10),
(36, '6.1 AMOLED', 2, 1),
(37, '6.4 AMOLED', 2, 2),
(38, '5 AMOLED', 2, 3),
(39, '6.7 OLED', 2, 4),
(40, '6.1 OLED', 2, 8),
(41, 'Snapdragon 8', 1, 11);

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
(25, 6),
(64, 6),
(65, 6),
(23, 8),
(24, 8),
(25, 8),
(63, 8),
(65, 8),
(26, 13),
(24, 14),
(25, 14),
(61, 14),
(63, 14),
(64, 14),
(25, 15),
(26, 15),
(23, 16),
(24, 16),
(25, 16),
(26, 16),
(61, 16),
(62, 16),
(63, 16),
(66, 16),
(64, 17),
(65, 17),
(26, 21),
(61, 21),
(24, 24),
(25, 24),
(61, 24),
(62, 24),
(63, 24),
(64, 24),
(65, 24),
(23, 25),
(62, 25),
(63, 25),
(65, 25),
(66, 25),
(64, 26),
(65, 27),
(66, 27),
(62, 28),
(63, 28),
(64, 28),
(65, 28),
(66, 28),
(23, 31),
(61, 31),
(62, 31),
(63, 31),
(65, 32),
(25, 33),
(64, 33),
(65, 33),
(24, 34),
(66, 34),
(24, 35),
(66, 35),
(62, 36),
(64, 37),
(65, 39),
(66, 39);

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int NOT NULL,
  `pid` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_general_ci,
  `short_content` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `discount` int DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gallery_img` text COLLATE utf8mb4_general_ci,
  `visible` tinyint DEFAULT '1',
  `position` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hit` tinyint DEFAULT '0',
  `sale` tinyint DEFAULT '0',
  `new` tinyint DEFAULT '0',
  `hot` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `pid`, `name`, `content`, `short_content`, `price`, `discount`, `img`, `gallery_img`, `visible`, `position`, `date`, `datetime`, `alias`, `hit`, `sale`, `new`, `hot`) VALUES
(23, 1, 'Смартфон Xiaomi 13T Pro 12GB/512GB международная версия', '<p>Android, экран 6.67\" AMOLED (1220x2712) 144 Гц, Mediatek Dimensity 9200+, ОЗУ 12 ГБ, память 512 ГБ, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM/eSIM), влагозащита IP68</p>', 'Android, экран 6.67\" AMOLED', '2500.00', 10, 'goods/img_11_1711042128249.webp', '[\"goods\\/img_7_1711072021871_34b58b1e.webp\",\"goods\\/img_11_1711042128249_7785e565.webp\",\"goods\\/img_15_1711131941591_ef03aa96.webp\"]', 1, 3, '2024-06-28', '2024-06-28 22:15:05', 'smartfon-xiaomi-13t-pro-12gb512gb-mezhdunarodnaya-versiya', 1, 1, 1, 0),
(24, 1, 'Смартфон Samsung Galaxy Z Flip5 SM-F731B/DS 8GB/256GB', '<p>Android, экран 6.7\" AMOLED (1080x2640) 120 Гц, Qualcomm Snapdragon 8 Gen2 SM8550, ОЗУ 8 ГБ, память 256 ГБ, камера 12 Мп, аккумулятор 3700 мАч, 1 SIM (nano-SIM/eSIM), влагозащита IPX8</p>', 'Android, экран 6.7\" AMOLED', '2980.00', 15, 'goods/img_14_1711042460430_48eabf6a.webp', '[\"goods\\/img_10_1711041932595_382de75e.webp\",\"goods\\/img_7_1711072021871_7abce657.webp\",\"goods\\/img_11_1711042128249_8afc5df0.webp\",\"goods\\/img_15_1711131941591_ab519c92.webp\"]', 1, 4, '2024-06-28', '2024-06-28 22:15:24', 'smartfon-samsung-galaxy-z-flip5-sm-f731bds-8gb256gb', 0, 1, 1, 1),
(25, 1, 'Смартфон Apple iPhone 15 128GB', '<p>Apple iOS, экран 6.1\" OLED (1179x2556) 60 Гц, Apple A16 Bionic, ОЗУ 6 ГБ, память 128 ГБ, камера 48 Мп, 1 SIM (nano-SIM/eSIM), влагозащита IP68</p>', 'Apple iOS, экран 6.1\" OLED', '3500.05', 20, 'goods/img_7_1711072021871.webp', '[\"goods\\/6_1711041613282_5e431ba5.webp\",\"goods\\/img_10_1711041932595_a0d8c876.webp\",\"goods\\/img_11_1711042128249_b5eddb2a.webp\",\"goods\\/img_15_1711131941591_bd26e2e2.webp\"]', 1, 5, '2024-06-28', '2024-06-28 22:15:42', 'smartfon-apple-iphone-15-128gb', 1, 0, 0, 1),
(26, 2, 'Планшет Lenovo Tab P11 Plus TB-J616X 6GB/128GB LTE', '<p>11\" IPS, 60 Гц (2000x1200), Android, MediaTek Helio G90T, ОЗУ 6 ГБ, флэш-память 128 ГБ, цвет серый</p>', 'Android, MediaTek Helio', '1500.00', 0, 'goods/img_16_1713087551260.webp', '[\"goods\\/17_1713087825459_4ffec7fa.webp\",\"goods\\/img_16_1713087551260_10ab666b.webp\",\"goods\\/img_22_1713090477061_a701baae.webp\"]', 1, 1, '2024-06-28', '2024-06-28 22:17:03', 'planshet-lenovo-tab-p11-plus-tb-j616x-6gb128gb-lte', 1, 1, 1, 1),
(61, 1, 'Смартфон Xiaomi Redmi 10C NFC 4GB/128GB международная версия', '<p>Android, экран 6.71\" IPS (720x1650) 60 Гц, Qualcomm Snapdragon 680, ОЗУ 4 ГБ, память 128 ГБ, поддержка карт памяти, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM)&nbsp;</p>', 'Android, экран 6.71\" IPS (720x1650) 60 Гц, Qualcomm Snapdragon 680, ОЗУ 4 ГБ, память 128 ГБ, поддержка карт памяти, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM) ', '430.00', 5, 'goods/10_1711041932595_4dd8a736.webp', '[\"goods\\/11_1711042128249_add669a6.webp\",\"goods\\/14_1711042460430_56e6281a.webp\",\"goods\\/15_1711131941591_1aa59320.webp\"]', 1, 6, '2024-06-28', '2024-06-28 22:16:01', 'smartfon-xiaomi-redmi-10c-nfc-4gb128gb-mezhdunarodnaya-versiya', 1, 0, 0, 0),
(62, 1, 'Смартфон Xiaomi Redmi Note 12', '<p>Android, экран 6.67\" AMOLED (1080x2400) 120 Гц, Qualcomm Snapdragon 685, ОЗУ 6 ГБ, память 128 ГБ, поддержка карт памяти, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM), влагозащита IP53&nbsp;</p>', 'Смартфон Xiaomi Redmi Note 12 6GB/128GB с NFC международная версия (серый оникс)', '685.00', 5, 'goods/15_1711131941591_f3501e42.webp', '[\"goods\\/10_1711041932595_baa6d2d4.webp\",\"goods\\/11_1711042128249_6fb65f4d.webp\"]', 1, 2, '2024-06-28', '2024-06-28 22:14:47', 'smartfon-xiaomi-redmi-note-12', 0, 1, 0, 0),
(63, 1, 'Смартфон Xiaomi 13T Pro', '<p>Android, экран 6.67\" AMOLED 144 Гц, Mediatek 9200+, ОЗУ 12 ГБ, память 512 ГБ, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM/eSIM), влагозащита IP68</p>', 'Смартфон Xiaomi 13T Pro 12GB/512GB международная версия', '2500.00', 10, 'goods/1_1711039208066_098a6065.png', '[\"goods\\/2_1711039396535_7db3fb92.png\",\"goods\\/3_1711039508491_1106fedd.png\",\"goods\\/4_1711041124918_f3baa1fa.webp\",\"goods\\/5_1711041487549_ed31597a.webp\"]', 1, 1, '2024-06-28', '2024-06-28 22:09:55', 'smartfon-xiaomi-13t-pro', 0, 0, 1, 1),
(64, 1, 'Смартфон Apple iPhone 14 128GB (полуночный)', '<p>Apple iOS, экран 6.1\" OLED (1170x2532) 60 Гц, Apple A15 Bionic, ОЗУ 6 ГБ, память 128 ГБ, камера 12 Мп, 1 SIM (nano-SIM/eSIM), влагозащита IP68</p>', 'Apple iOS, экран 6.1\" OLED (1170x2532) 60 Гц, Apple A15 Bionic, ', '2935.00', 5, 'goods/7_1711072021871_42ec892e.webp', '[\"goods\\/3_1711039508491_8e48c4be.png\",\"goods\\/4_1711041124918_c5d5bfdc.webp\",\"goods\\/5_1711041487549_fc763971.webp\"]', 1, 7, '2024-06-28', '2024-06-28 21:32:29', 'smartfon-apple-iphone-14-128gb-polunochniy', 0, 0, 1, 0),
(65, 1, 'Смартфон Apple iPhone 11 128GB', '<p>Apple iOS, экран 6.1\" IPS (828x1792), Apple A13 Bionic, ОЗУ 4 ГБ, память 128 ГБ, камера 12 Мп, аккумулятор 3046 мАч, 1 SIM (nano-SIM/eSIM), влагозащита IP68</p>', 'Apple iOS, экран 6.1\" IPS (828x1792), Apple A13 Bionic, ОЗУ 4 ГБ', '2530.00', 5, 'goods/4_1711041124918_d015f796.webp', '[\"goods\\/5_1711041487549_fea41d23.webp\",\"goods\\/6_1711041613282_88130192.webp\",\"goods\\/7_1711072021871_24e969f9.webp\"]', 1, 8, '2024-06-28', '2024-06-28 22:16:29', 'smartfon-apple-iphone-11-128gb', 0, 0, 1, 1),
(66, 1, 'Смартфон Samsung Galaxy S22 Ultra 5G', '<p>Android, экран 6.8\" AMOLED (1440x3088) 120 Гц, Exynos 2200, ОЗУ 12 ГБ, память 256 ГБ, камера 108 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM/eSIM), влагозащита IP68</p>', 'Android, экран 6.8\" AMOLED (1440x3088) 120 Гц, Exynos 2200, ОЗУ 12', '1890.00', 0, 'goods/11_1711042128249_ffd43ec1.webp', '[\"goods\\/2_1711039396535_fb8487d2.png\",\"goods\\/10_1711041932595_5742f948.webp\",\"goods\\/11_1711042128249_76f80fae.webp\"]', 1, 9, '2024-06-28', '2024-06-28 22:16:45', 'smartfon-samsung-galaxy-s22-ultra-5g', 0, 1, 0, 1);

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
(64, 1),
(65, 1),
(24, 2),
(66, 2),
(23, 3),
(61, 3),
(62, 3),
(63, 3),
(26, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `short_content` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_general_ci,
  `visible` tinyint(1) DEFAULT '1',
  `alias` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `name`, `date`, `short_content`, `content`, `visible`, `alias`) VALUES
(1, 'В каталог добавлены модели Смартфон Xiaomi 13T-pro', '2024-06-21', 'Для заказа стали доступны\r\nСмартфон Xiaomi 13T! Ну очеееень круть!!!', '<p>Смартфон Xiaomi 13T</p>', 1, 'v-katalog-tovarov-dobavleny-modeli-smartfon-xiaomi-13t-pro'),
(2, 'Новый Смартфон Xiaomi 13T', '2024-06-21', 'Рады сообщить, что в нашем интернет-магазине для заказа стали доступны\r\nСмартфон Xiaomi 13T! Ну вообще отпад!!!', '<p>Смартфон Xiaomi 13T! Ну вообще отпад!!!</p>', 1, 'noviy-smartfon-xiaomi-13t'),
(3, 'Планшет Lenovo Tab P11 Plus - Одни плюсы', '2024-06-21', 'Рады сообщить, что в нашем интернет-магазине для заказа стали доступны\r\nПланшет Lenovo Tab P11 Plus! Одни плююююююююсы!', '<p>Планшет Lenovo Tab P11 Plus. Одни плююююююююсы!</p>', 1, 'planshet-lenovo-tab-p11-plus-odni-plyusy'),
(4, 'Стали доступны Планшет Lenovo Tab', '2024-06-21', 'Рады сообщить, что в нашем интернет-магазине для заказа стали доступны\r\nПланшет Lenovo Tab', '<p>Планшет Lenovo Tab С381 Plus. Только 381 + 1 плююююююююсы!</p>', 1, 'stali-dostupny-planshet-lenovo-tab');

-- --------------------------------------------------------

--
-- Структура таблицы `old_alias`
--

CREATE TABLE `old_alias` (
  `alias` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `table_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Акция 1', 'Продажа', 1, 1, 'sales/services.jpg', '/', 'Два по цене один. Два по цене один. Два по цене один. Два по цене один.Два по цене один. Два по цене один.Два по цене один.'),
(2, 'Акция 2', 'Услуги', 2, 1, 'sales/services-detail1.jpg', '/', 'За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. За это бесплатно то. '),
(3, 'Акция 3', 'Пустышка', 3, 1, 'sales/sservices1.jpg', '/', 'Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . Просто так, дарю . ');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keywords` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(350) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `short_content` text COLLATE utf8mb4_general_ci,
  `content` text COLLATE utf8mb4_general_ci,
  `img_logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `promo_img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `img_years` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `number_years` varchar(63) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `name`, `keywords`, `description`, `address`, `phone`, `email`, `short_content`, `content`, `img_logo`, `promo_img`, `img_years`, `number_years`) VALUES
(1, 'Магазин товаров', '', '', 'г. Москва пер.Машиностроителей, д.12, к.12', '8-800-000-00-00', 'test@myshop2.by', '<p>В основе нашей компании : самые современные информационные технологии, собственные программные разработки, накопленная за годы работы аналитическая и статистическая информация по рынку, высококвалифицированный коллектив — мы делаем все для того, чтобы Вы были довольны нашей работой.</p>', '<p>Начал свою работу в 1999 году. С самого начала главной целью было предложить нашим клиентам самый широкий спектр товаров и аксессуаров, а развитие интернет&ndash;технологий дало возможность максимально упростить и ускорить процесс покупки.</p>\r\n<p>Компания быстро росла, и сегодня, занимая одну из ведущих позиции на этом рынке, мы не стоим на месте. В основе нашей компании : самые современные информационные технологии, собственные программные разработки, накопленная за годы работы аналитическая и статистическая информация по рынку, высококвалифицированный коллектив &mdash; мы делаем все для того, чтобы Вы были довольны нашей работой.</p>', 'settings/myshop_407e28ce.png', 'settings/about.png', 'settings/15.svg', '15');

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
-- Индексы таблицы `advantages`
--
ALTER TABLE `advantages`
  ADD PRIMARY KEY (`id`);

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
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT для таблицы `advantages`
--
ALTER TABLE `advantages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=514;

--
-- AUTO_INCREMENT для таблицы `country_manufacturers`
--
ALTER TABLE `country_manufacturers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `filters`
--
ALTER TABLE `filters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

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
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
