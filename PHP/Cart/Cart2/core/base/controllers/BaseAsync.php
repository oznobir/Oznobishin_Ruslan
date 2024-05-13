<?php

namespace core\base\controllers;

use core\base\settings\Settings;

class BaseAsync extends BaseControllers
{
    /**
     * @return mixed
     */
    public function routeAsync(): mixed
    {
        $route = Settings::get('routes');
        $this->controller = $route['site']['pathControllers'] . 'AsyncController';
        $data = $this->isPost() ? $_POST : $_GET;
        if ($data['ADMIN_MODE'] == 1) {
            $this->controller = $route['admin']['pathControllers'] . 'AsyncController';
            unset($data['ADMIN_MODE']);
        }
        $controller = str_replace('/', '\\', $this->controller);
        $async = new $controller;
        $async->createAsyncData($data);
        return $async->async();
    }

    /**
     * @param array|null $data
     * @return void
     */
    protected function createAsyncData(?array $data): void
    {
        $this->data = $data;
    }
}