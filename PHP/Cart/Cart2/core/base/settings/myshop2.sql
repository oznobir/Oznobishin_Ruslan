-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 25 2024 г., 19:54
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
-- Структура таблицы `cat_goods`
--

CREATE TABLE `cat_goods` (
  `id` int NOT NULL,
  `pid` int DEFAULT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alias` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `cat_goods`
--

INSERT INTO `cat_goods` (`id`, `pid`, `name`, `img`, `alias`) VALUES
(1, 0, 'категория 1', 'img_17_1713087825459.webp', 'category 1'),
(2, 0, 'категория 2', 'img_17_1713087825459.webp', 'category 2'),
(3, 0, 'категория 3', 'img_17_1713087825459.webp', 'category 3'),
(10, 1, 'категория 1-1', 'img_17_1713087825459.webp', 'category 1-1'),
(11, 3, 'категория 3-1', 'img_17_1713087825459.webp', 'category 3-1'),
(12, 1, 'категория 1-2', 'img_17_1713087825459.webp', 'category 1-2');

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
(1, 'красный', 'img_19_1713088786380.webp', 'red'),
(2, 'желтый', 'img_19_1713088786380.webp', 'yellow'),
(3, 'черный', 'img_19_1713088786380.webp', 'black'),
(4, 'белый', 'img_19_1713088786380.webp', 'white'),
(5, 'зеленый', 'img_19_1713088786380.webp', 'green'),
(6, 'голубой', 'img_19_1713088786380.webp', 'blue'),
(7, 'розовый', 'img_19_1713088786380.webp', 'pink'),
(8, 'коричневый', 'img_19_1713088786380.webp', 'brown');

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
(1, 3, 1),
(2, 3, 2),
(3, 3, 3),
(4, 5, 4),
(5, 5, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `filters`
--

CREATE TABLE `filters` (
  `id` int NOT NULL,
  `pid` int DEFAULT NULL,
  `filters_name` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `filters`
--

INSERT INTO `filters` (`id`, `pid`, `filters_name`) VALUES
(1, NULL, 'height'),
(2, NULL, 'width'),
(5, 1, '300mm'),
(6, 1, '400mm'),
(7, 2, '4500mm'),
(8, 2, '3500mm');

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
(3, 5),
(5, 6),
(3, 7),
(5, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_general_ci,
  `price` float DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gallery_img` text COLLATE utf8mb4_general_ci,
  `pid` int DEFAULT NULL,
  `position` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `name`, `content`, `price`, `img`, `gallery_img`, `pid`, `position`, `date`, `datetime`, `alias`) VALUES
(1, 'имя1', 'content name1', 2, 'img_1_1711039208066.png', '[\"img_1_1711039208066.png\",\"img_2_1711039396535.png\"]', 10, 1, NULL, NULL, 'name1'),
(2, 'имя2', 'content name2', 3, 'img_2_1711039396535.png', '[\"img_1_1711039208066.png\",\"img_2_1711039396535.png\"]', 11, 2, NULL, NULL, 'name2'),
(3, 'имя3', 'content name3', 4, 'img_2_1711039396535.png', '[\"img_1_1711039208066.png\",\"img_2_1711039396535.png\"]', 12, 3, NULL, NULL, 'name3'),
(4, 'имя4', 'content name4', 5, 'img_2_1711039396535.png', '[\"img_1_1711039208066.png\",\"img_2_1711039396535.png\"]', 11, 4, NULL, NULL, 'name4'),
(5, 'имя5', 'content name5', 4, 'img_2_1711039396535.png', '[\"img_1_1711039208066.png\",\"img_2_1711039396535.png\"]', 10, 5, NULL, NULL, 'name5'),
(6, 'имя6', 'content name6', 6, 'img_2_1711039396535.png', '[\"img_1_1711039208066.png\",\"img_2_1711039396535.png\"]', 12, 6, NULL, NULL, 'name6'),
(7, 'имя7', 'content name7', 6, 'img_2_1711039396535.png', '[\"img_1_1711039208066.png\",\"img_2_1711039396535.png\"]', 11, 7, NULL, NULL, 'name7');

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer`
--

CREATE TABLE `manufacturer` (
  `id` int NOT NULL,
  `name` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `manufacturer`
--

INSERT INTO `manufacturer` (`id`, `name`) VALUES
(1, 'Apple'),
(2, 'Samsung');

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
(3, 1),
(5, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `parsing_table`
--

CREATE TABLE `parsing_table` (
  `all_links` longtext COLLATE utf8mb4_general_ci,
  `temp_links` longtext COLLATE utf8mb4_general_ci,
  `bad_links` longtext COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `parsing_table`
--

INSERT INTO `parsing_table` (`all_links`, `temp_links`, `bad_links`) VALUES
('', '', ''),
('', '', ''),
('', '', ''),
('', '', ''),
('', '', '');

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
(3, 5, '13t-pro-black', 'Смартфон Xiaomi 13T Pro 12GB/512GB международная версия (черный)', 'Android, экран 6.67\" AMOLED (1220x2712) 144 Гц, Mediatek Dimensity 9200+, ОЗУ 12 ГБ, память 512 ГБ, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM/eSIM), влагозащита IP68 ', 2501, 'img_3_1711039508491.png', 1, 3, 1, NULL),
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

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `categories_categories_id_fk` (`pid`);

--
-- Индексы таблицы `cat_goods`
--
ALTER TABLE `cat_goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

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
-- Индексы таблицы `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `filters_filters_id_fk` (`pid`);

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
-- Индексы таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cat_goods`
--
ALTER TABLE `cat_goods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `color`
--
ALTER TABLE `color`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `color_goods`
--
ALTER TABLE `color_goods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `filters`
--
ALTER TABLE `filters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT для таблицы `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_categories_id_fk` FOREIGN KEY (`pid`) REFERENCES `categories` (`id`);

--
-- Ограничения внешнего ключа таблицы `cat_goods`
--
ALTER TABLE `cat_goods`
  ADD CONSTRAINT `cat_goods_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `cat_goods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
  ADD CONSTRAINT `filters_filters_id_fk` FOREIGN KEY (`pid`) REFERENCES `filters` (`id`);

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
  ADD CONSTRAINT `goods_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `cat_goods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
