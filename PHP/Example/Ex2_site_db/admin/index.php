<?php
include 'connectDbPages.php';
/** @var $link */

deletePage($link);
showPageTable($link);

function showPageTable($link): void
{
    $query = "SELECT id, title, description, url FROM pages";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result);){
        $data [] = $row;
    }
    ob_start();
    include 'tablePages.php';
    $contentAdm = ob_get_clean();
    $titleAdm = 'admin page';
    $descAdm = 'admin page';

    include 'layoutAdm.php';
} // end function showPageTable($link, $info): void

function deletePage($link): void
{
    if (isset($_GET['delete-id'])) {
        $id = $_GET['delete-id'];

        $query = "DELETE FROM pages WHERE id = '$id'";
        mysqli_query($link, $query) or die(mysqli_error($link));
        $_SESSION ['message'] =  [
            'text' => "Page with Id \"$id\" delete successfully!",
            'status' => "success"
        ];
        header("Location: index.php");
        die();
    }
} // end function deletePage($link)

//
//-- Хост: 127.0.0.1:3306
//-- Время создания: Фев 02 2024 г., 20:56
//-- Версия сервера: 8.0.30
//-- Версия PHP: 8.1.9
//
//SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
//START TRANSACTION;
//SET time_zone = "+00:00";
//
//--
//-- База данных: `dbPages`
//--
//
//-- --------------------------------------------------------
//
//--
//-- Структура таблицы `pages`
//--
//
//CREATE TABLE `pages` (
//`id` int NOT NULL,
//  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
//  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
//  `url` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
//  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
//  `text2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
//) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
//
//--
//-- Дамп данных таблицы `pages`
//--
//
//INSERT INTO `pages` (`id`, `title`, `description`, `url`, `text`, `text2`) VALUES
//(1, 'title1', 'description1', '1', 'text1 page1', 'text2 page1'),
//(2, 'title2', 'description2', '2', 'text1 page2', 'text2 page2'),
//(3, 'title3', 'description3', '3', 'text1 page3', 'text2 page3'),
//(4, 'title4', 'description4', '4', 'text1 page4', 'text2 page4'),
//(5, 'title5', 'description5', '5', 'text1 page5', 'text2 page5'),
//(6, 'title5', 'description5', '5', 'text1 page5', 'text2 page5'),
//(7, 'title5', 'description5', '5', 'text1 page5', 'text2 page5');
//
//--
//-- Индексы сохранённых таблиц
//--
//
//--
//-- Индексы таблицы `pages`
//--
//ALTER TABLE `pages`
//  ADD PRIMARY KEY (`id`);
//
//--
//-- AUTO_INCREMENT для сохранённых таблиц
//--
//
//--
//-- AUTO_INCREMENT для таблицы `pages`
//--
//ALTER TABLE `pages`
//  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
//COMMIT;
