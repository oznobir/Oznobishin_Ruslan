<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

class BaseAsync extends BaseControllers
{
    /**
     * @throws RouteException
     */
    public function routeAsync(): void
    {
        $route = Settings::get('routes');
        $controller = $route['site']['pathControllers'] . 'AsyncController';
        $data = $this->isPost() ? $_POST : $_GET;
//        $referer = str_replace('/', '\/', $_SERVER['REQUEST_SCHEMA'].'://'.$_SERVER['SERVER_NAME'].PATH.$route['admin']['alias']);
//        if ((isset($data['ADMIN_MODE'] && $data['ADMIN_MODE'] == 1)
//             || preg_match('/^'.$referer.'(\/?|$)/', $_SERVER['HTTP_REFERER'])) {
        if (isset($data['ADMIN_MODE']) && $data['ADMIN_MODE'] == 1) {
            $controller = $route['admin']['pathControllers'] . 'AsyncController';
            unset($data['ADMIN_MODE']);
        }
        $controller = str_replace('/', '\\', $controller);
        $async = new $controller;
        $async->asyncData = $data;
        $result = $async->async();
        if (is_array($result) || is_object($result)) $result = json_encode($result);
        echo $result;
    }
}