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
