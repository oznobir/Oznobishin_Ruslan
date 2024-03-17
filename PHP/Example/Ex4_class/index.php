<?php
namespace core;

require 'project/config/config.php';
spl_autoload_register(function ($class) {
    $file = str_replace("\\", "/", $class) . '.php';
    if (is_readable($file)) {
        return include $file;
    }
    return false;
});
$routes = require 'project/config/routes.php';
$router = new Router();
$route = $router->getRoute($routes);
$controller = new $route['controller']($route['parameters']);
$action = $route['action'];
$controller->$action();
