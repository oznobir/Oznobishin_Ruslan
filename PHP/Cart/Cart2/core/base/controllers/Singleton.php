<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;

trait Singleton
{
    static private object $_instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    static public function instance(): object
    {
        if (!isset(self::$_instance)) self::$_instance = new self();
        return self::$_instance;
    }

    /**
     * @throws RouteException
     */
    static public function get($property)
    {
        if (!empty(self::instance()->$property)) return self::instance()->$property;
        else throw new RouteException('Отсутствует свойство ' . $property . ' в ' . self::class, 2);
    }
}