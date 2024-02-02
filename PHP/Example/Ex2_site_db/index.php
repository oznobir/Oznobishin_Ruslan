<?php
//**************
//Потом можно вынести в отдельный файл
error_reporting(E_ALL);
ini_set('display_errors', 'on');

$host = 'localhost';
$user = 'root';
$password = '';
$dbName = 'dbPages';

$link = mysqli_connect($host, $user, $password, $dbName);
mysqli_query($link, "SET NAMES 'utf8'");
//*************
$page = $_GET['p'] ?? '1';

$query = "SELECT * FROM pages WHERE url = '$page'";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$page = mysqli_fetch_assoc($result);
//var_dump($page);
if (!$page) {
    header("HTTP/1.0 404 Not Found");
    include 'template/404.php';
} else {
    $title = $page['title'];
    $desc = $page['description'];
    $content = $page['text'];
    $content2 = $page['text2'];

    $query = "SELECT url FROM pages";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data [] = $row) ;
//    var_dump($data);
//    Пока в базе url в том порядке как нужно

    $menu = '';
    foreach ($data as $linkMenu) {
        $menu .= createLinkMenu($linkMenu['url']);
    }

    include 'template/layout.php';
}
//Создание линков в меню createLinkMenu($href)
function createLinkMenu($href): string
{
    $page = $_GET['p'] ?? '1';
    if ($page == $href) {
        $classLinkMenu = " class='active'";
    } else {
        $classLinkMenu = '';
    }
    return "<div><a$classLinkMenu href=\"?p=$href\">Page $href</a></div>";
}//  end  function createLinkMenu($href): string

