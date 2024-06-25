<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

class BaseAsync extends BaseController
{
    /**
     * @return false|string
     * @throws RouteException
     */
    public function routeAsync(): false|string
    {
        $route = Settings::get('routes');
        $controller = $route['site']['pathControllers'] . 'AsyncController';
        $data = $this->isPost() ? $_POST : $_GET;
        if (isset($data['ajax']) && $data['ajax'] === 'token') {
            return $this->generateToken();
        }
//        $referer = str_replace('/', '\/', $_SERVER['REQUEST_SCHEMA'].'://'.$_SERVER['SERVER_NAME'].PATH.$route['admin']['alias']);
//        if ((isset($data['ADMIN_MODE'] && $data['ADMIN_MODE'] == 1)
//             || preg_match('/^'.$referer.'(\/?|$)/', $_SERVER['HTTP_REFERER'])) {
        if (isset($data['ADMIN_MODE']) && $data['ADMIN_MODE'] == 1) {
            $controller = $route['admin']['pathControllers'] . 'AsyncController';
            unset($data['ADMIN_MODE']);
        }
        $controller = str_replace('/', '\\', $controller);
        $async = new $controller;
        $result = $async->async($data);
        if (is_array($result) || is_object($result)) $result = json_encode($result);
        return $result;
    }

    /**
     * @return string
     */
    protected function generateToken(): string
    {
        return $_SESSION['token'] = md5(mt_rand(0, 999999) . microtime());
    }
}