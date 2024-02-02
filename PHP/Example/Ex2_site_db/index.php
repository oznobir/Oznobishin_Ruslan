<?php
//**************
//Потом можно вынести в отдельный файл
error_reporting(E_ALL);
ini_set('display_errors', 'on');

$host = 'localhost';
$user = 'root';
$password = '';
$dbName ='dbPages';

$link = mysqli_connect($host, $user, $password, $dbName);
mysqli_query($link, "SET NAMES 'utf8'");
//*************
$page = $_GET['page'] ?? '1';

$query = "SELECT * FROM pages WHERE url='$page'";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$page = mysqli_fetch_assoc($result);

if ($page) {
    $title = $page['title'];
    $desc = $page['description'];
    $content = $page['text'];
    $content2 = $page['text2'];
    include 'template/layout.php';
} else {
    header("HTTP/1.0 404 Not Found");
    include 'template/404.php';
}