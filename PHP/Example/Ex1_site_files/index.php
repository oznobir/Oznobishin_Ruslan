<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

$page = $_GET['page'] ?? '1';

$path = "pages/page$page.php";

if (file_exists($path)) {
    $content = file_get_contents($path);
} else {
    $content = file_get_contents("pages/404.php");
    header("HTTP/1.0 404 Not Found");
    // прежде надо проверить, что 404.php есть
}
// сделать потом функцию+-
$reg = '#\{\{title:(.*?)\}\}#';
if (preg_match($reg, $content, $match)) {
    $title = $match[1];
    $content = trim(preg_replace($reg, '', $content));
} else {
    $title = '';
    // прежде надо проверить, что title есть и не пустой
}
$reg = '#\{\{description:(.*?)\}\}#';
if (preg_match($reg, $content, $match)) {
    $desc = $match[1];
    $content = trim(preg_replace($reg, '', $content));
} else {
    $desc = '';
    // прежде надо проверить, что description есть и не пустой
}
include 'template/layout.php';
