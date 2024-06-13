<?php

use core\base\exceptions\RouteException;
use core\base\controllers\BaseRoute;

define('W_ACCESS', true);

header('Content-Type:text/html;charset=utf-8');
session_start();

require_once 'core/base/settings/internal_settings.php';
require_once 'libraries/functions.php';

spl_autoload_register(/** @throws RouteException */ function ($class) {
    $file = str_replace("\\", "/", $class) . '.php';
    if (!@include_once $file) throw new RouteException("Класс $class не подключен");
//    if (is_readable($file)) return include $file; else throw new RouteException("Класс $class не найден");
});

try {
   BaseRoute::routeDirection();
} catch (RouteException $e) {
    exit($e->getMessage());
}
