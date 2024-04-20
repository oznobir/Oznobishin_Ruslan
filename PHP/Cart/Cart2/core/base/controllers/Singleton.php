<?php

namespace core\base\controllers;

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
}