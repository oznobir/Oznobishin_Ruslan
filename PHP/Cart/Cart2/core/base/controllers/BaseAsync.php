<?php

namespace core\base\controllers;

use core\base\settings\Settings;

class BaseAsync extends BaseControllers
{
    public function routeAsync()
    {
        $route = Settings::get('routes');
        $controller = $route['site']['pathControllers'] . 'AsyncController';
        $data = $this->isPost() ? $_POST : $_GET;
        if ($data['ADMIN_MODE'] == 1) {
            $controller = $route['admin']['pathControllers'] . 'AsyncController';
            unset($data['ADMIN_MODE']);
        }
        $controller = str_replace('/', '\\', $controller);
        $async = new $controller;
        $async->createAsyncData($data);
        return $async->async();

    }

    /**
     * @param array $data
     * @return void
     */
    protected function createAsyncData(array $data): void
    {
        $this->data = $data;
    }
}