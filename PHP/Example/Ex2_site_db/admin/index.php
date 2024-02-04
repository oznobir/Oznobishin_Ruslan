<?php
include 'connectDbPages.php';
/** @var $link * */
$info = deletePage($link);
if (isset($_GET['add-p'])) {
    $url = $_GET['add-p'];
    $info = [
        'text' => "Page \"$url\" added successfully!",
        'status' => "success"
    ];
}
if (isset($_GET['edit-p'])) {
    $url = $_GET['edit-p'];
    $info = [
        'text' => "Page \"$url\" edited successfully!",
        'status' => "success"
    ];
}
showPageTable($link, $info);

function showPageTable($link, $info): void
{
    $contentAdm =  '';
    if ($info){
        $contentAdm .=  "<div class='{$info['status']}'>{$info['text']}</div>";
    }

    $query = "SELECT id, title, description, url FROM pages";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result);){
        $data [] = $row;
    }

    $contentAdm .= "<table><thead><tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Url</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr></thead>
                <tbody>";
    foreach ($data as $pageAdm) {
        $contentAdm .=
            "<tr>
                <td>{$pageAdm['id']}</td>
                <td>{$pageAdm['title']}</td>
                <td>{$pageAdm['description']}</td>
                <td>{$pageAdm['url']}</td>
                <td><a href='editPage.php?edit-p={$pageAdm['url']}'>edit</a></td>
                <td><a href='?delete-p={$pageAdm['url']}'>delete</a></td>
             </tr>";
    }
    $contentAdm .= "</tbody></table>";
    $titleAdm = 'admin page';
    $descAdm = 'admin page';
    include 'layoutAdm.php';
} // end function showPageTable($link, $info): void

function deletePage($link)
{
    if (isset($_GET['delete-p'])) {
        $url = $_GET['delete-p'];
        $query = "DELETE FROM pages WHERE url = '$url'";
        mysqli_query($link, $query) or die(mysqli_error($link));
        return [
            'text' => "Page \"$url\" delete successfully!",
            'status' => "success"
        ];
    } else {
        return '';
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
