<?php
namespace core;

use Throwable;

session_start();
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

require 'project/config/config.php';
spl_autoload_register(function ($class) {
    $file = str_replace("\\", "/", $class) . '.php';
    if (is_readable($file)) {
        return include $file;
    }
    return false;
});
//try {
    $routes = require 'project/config/routes.php';
    $router = new Router();
    $route = $router->getRoute($routes);
    $route['parameters']['cartCountItems'] = count($_SESSION['cart']);
    $controller = new $route['controller']($route['parameters']);
    $action = $route['action'];
    Model::init(DB_DSN, DB_USER, DB_PASS);
    $controller->$action();
//} catch (Throwable $t) {
//    echo "Ошибка - " . $t->getMessage();
//}
