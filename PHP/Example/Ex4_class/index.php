<?php
require 'config/config.php';

spl_autoload_register(function ($class) {
        $file = $class . '.php';
        if (is_readable($file)) {
            include $file;
            return true;
        }
        else return false;
    });

$router = new Router();
$route = $router->getRoute();
$controller = new $route['controller']($route['parameters']);
$action = $route['action'];
$controller->$action();