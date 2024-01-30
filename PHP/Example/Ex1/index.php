<?php
error_reporting(E_ALL);
ini_set('display_errors','on');

// Только file exists. Без проверки $_GET['page']
$page = $_GET['page'];
$path = "pages/page$page.php";
if (file_exists($path)) {
    include ($path);
} else {
    echo 'file not found';
}
echo '<br>';