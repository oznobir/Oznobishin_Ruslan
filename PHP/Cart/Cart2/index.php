<?php

use core\base\exceptions\RouteException;
use core\base\controllers\RouteController;

const W_ACCESS = true;

header('Content-Type:text/html;charset:utf-8');
session_start();

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';

spl_autoload_register(function ($class) {
    $file = str_replace("\\", "/", $class) . '.php';
//        if (!@include_once $file) {
//            throw new RouteException("Класс $class не подключен");
//        }
    if (is_readable($file)) return include $file;
    else throw new RouteException("Класс $class не подключен");
});

try {
    RouteController::instance()->route();

} catch (RouteException $e) {
    exit($e->getMessage());
}