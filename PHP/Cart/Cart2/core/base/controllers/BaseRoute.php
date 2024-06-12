<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;

class BaseRoute
{
    use Singleton, BaseMethods;

    /**
     * @throws RouteException
     */
    public static function routeDirection(): void
    {
        if (self::instance()->isAjax()) {
            echo (new BaseAsync())->routeAsync();
            exit();
        }
        RouteController::instance()->route();
    }
}