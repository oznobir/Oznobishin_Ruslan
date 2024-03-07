<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
session_start();
include_once 'config/config.php'; // начальные настройки
include_once 'functions/mainFunctions.php'; // основные функции

$parameters =[];
foreach ($_GET as $param => $value){
    $parameters[$param] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
$route = getRoute($parameters);
loadPage($route);

//if (file_exists("data/data_menu.php")) {
//    $data_menu = include "data/data_menu.php";
//    if (isset($_GET['p'])) {
//        $page = htmlspecialchars($_GET['p'], ENT_QUOTES, 'UTF-8');
//    } else {
//        $page = 'all';
//    }
//    if ($page == 'all' || $page == '') {
//        $mainMenu = showMainMenu($data_menu);
//        $title = 'Содержание. Скрипты на PHP';
//        $desc = "Скрипты на PHP. Изучаем вместе";
//        include 'template/layoutMainMenu.php';
//        die();
//    } else {
//        $data_parent = getDataParent($data_menu, $page);
//        $data_p = getDataP($data_parent, $page);
//        if ($data_p) {
//            if (file_exists("pages/{$data_p['dir']}/{$data_p['content2'][0]['path']}")) {
//                $title = "Пример $page. {$data_parent['desc']}";
//                $desc = $data_p['desc'];
//                $menu = showMenuPage($data_parent['children'], $page);
//                $content1 = showContent1($data_p);
//                $content2 = showContent2($data_p);
//            } else {
//                $_SESSION ['message'] = [
//                    'text' => "Файл 'pages/{$data_p['dir']}/{$data_p['content2'][0]['path']}' не найден.",
//                    'status' => "error"
//                ];
//                header("Location: index.php?p=all");
//                die();
//            }
//        } else {
//            $_SESSION ['message'] = [
//                'text' => "Нет данных примера $page в 'data/data_menu.php'.",
//                'status' => "error"
//            ];
//            header("Location: index.php?p=all");
//            die();
//        }
//    }
//} else {
//    $_SESSION ['message'] = [
//        'text' => "Файл 'data_menu.php' не найден.",
//        'status' => "error"
//    ];
//    header("HTTP/1.0 404 Not Found");
//    $title = "404 Not Found";
//    $desc = "404 Not Found";
//    $mainMenu = "";
//    include 'template/layoutMainMenu.php';
//    die();
//}
//include 'template/layout.php';
