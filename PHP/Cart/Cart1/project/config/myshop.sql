-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 21 2024 г., 21:00
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
-- База данных: `myshop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `parent_id` int NOT NULL,
  `slug` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `slug`, `title`) VALUES
(1, 0, 'mobile', 'Телефоны'),
(2, 0, 'planshety', 'Планшеты'),
(3, 0, 'noutbuki', 'Ноутбуки'),
(4, 0, 'monitory', 'Мониторы'),
(5, 1, 'phone-xiaomi', 'Телефоны Xiaomi'),
(6, 1, 'phone-apple', 'Телефоны Apple'),
(7, 1, 'phone-samsung', 'Телефоны Samsung');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `slug` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` float NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `category_id`, `slug`, `title`, `description`, `price`, `image`, `status`) VALUES
(1, 5, 'redmi-10c-nfc', 'Смартфон Xiaomi Redmi 10C NFC 4GB/128GB международная версия (серый)', 'Android, экран 6.71\" IPS (720x1650) 60 Гц, Qualcomm Snapdragon 680, ОЗУ 4 ГБ, память 128 ГБ, поддержка карт памяти, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM) ', 430, NULL, 1),
(2, 5, 'redmi-note-12-nfc', 'Смартфон Xiaomi Redmi Note 12 6GB/128GB с NFC международная версия (серый оникс)', 'Android, экран 6.67\" AMOLED (1080x2400) 120 Гц, Qualcomm Snapdragon 685, ОЗУ 6 ГБ, память 128 ГБ, поддержка карт памяти, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM), влагозащита IP53 ', 685.5, NULL, 1),
(3, 5, '13t-pro-black', 'Смартфон Xiaomi 13T Pro 12GB/512GB международная версия (черный)', 'Android, экран 6.67\" AMOLED (1220x2712) 144 Гц, Mediatek Dimensity 9200+, ОЗУ 12 ГБ, память 512 ГБ, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM/eSIM), влагозащита IP68 ', 2501, NULL, 1),
(4, 6, 'iphone-14-128gb-polunochnyi', 'Смартфон Apple iPhone 14 128GB (полуночный)', 'Apple iOS, экран 6.1\" OLED (1170x2532) 60 Гц, Apple A15 Bionic, ОЗУ 6 ГБ, память 128 ГБ, камера 12 Мп, 1 SIM (nano-SIM/eSIM), влагозащита IP68 ', 2935, NULL, 1),
(5, 6, 'iphone-11-128gb-chernyi', 'Смартфон Apple iPhone 11 128GB (черный)', 'Apple iOS, экран 6.1\" IPS (828x1792), Apple A13 Bionic, ОЗУ 4 ГБ, память 128 ГБ, камера 12 Мп, аккумулятор 3046 мАч, 1 SIM (nano-SIM/eSIM), влагозащита IP68', 2115.35, NULL, 1),
(6, 6, 'iphone-15-128gb-chernyi', 'Смартфон Apple iPhone 15 128GB (черный)', 'Apple iOS, экран 6.1\" OLED (1179x2556) 60 Гц, Apple A16 Bionic, ОЗУ 6 ГБ, память 128 ГБ, камера 48 Мп, 1 SIM (nano-SIM/eSIM), влагозащита IP68 ', 3500.5, NULL, 1),
(7, 6, 'iphone-13-128gb-zelenyi', 'Apple iPhone 13 128GB (зеленый)', 'Apple iOS, экран 6.1\" OLED (1170x2532) 60 Гц, Apple A15 Bionic, ОЗУ 4 ГБ, память 128 ГБ, камера 12 Мп, аккумулятор 3227 мАч, 1 SIM (nano-SIM/eSIM), влагозащита IP68 ', 2603, NULL, 1),
(10, 7, 'galaxy-s22-ultra-5g-sm-chernyi', 'Смартфон Samsung Galaxy S22 Ultra 5G SM-S908B/DS 12GB/256GB (черный фантом)', 'Android, экран 6.8\" AMOLED (1440x3088) 120 Гц, Exynos 2200, ОЗУ 12 ГБ, память 256 ГБ, камера 108 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM/eSIM), влагозащита IP68', 2516, NULL, 1),
(11, 7, 'galaxy-a23-sm-a23-persikovyi', 'Samsung Galaxy A23 SM-A235F/DSN 4GB/64GB (персиковый)', 'Android, экран 6.6\" PLS (1080x2408) 90 Гц, Qualcomm Snapdragon 680, ОЗУ 4 ГБ, память 64 ГБ, поддержка карт памяти, камера 50 Мп, аккумулятор 5000 мАч, 2 SIM (nano-SIM) ', 763.5, NULL, 1),
(14, 7, 'galaxy-z-flip5-bezhevyi/', 'Смартфон Samsung Galaxy Z Flip5 SM-F731B/DS 8GB/256GB (бежевый)', ' Android, экран 6.7\" AMOLED (1080x2640) 120 Гц, Qualcomm Snapdragon 8 Gen2 SM8550, ОЗУ 8 ГБ, память 256 ГБ, камера 12 Мп, аккумулятор 3700 мАч, 1 SIM (nano-SIM/eSIM), влагозащита IPX8\r\n', 2976.5, NULL, 1),
(15, 7, 'galaxy-a01-sm-a015fds-chernyi', 'Смартфон Samsung Galaxy A01 SM-A015F/DS (черный)', 'Android, экран 5.7\" PLS (720x1560), Qualcomm Snapdragon 439, ОЗУ 2 ГБ, память 16 ГБ, поддержка карт памяти, камера 13 Мп, аккумулятор 3000 мАч, 2 SIM (nano-SIM) ', 210, NULL, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
