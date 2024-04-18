<?php

namespace core\plugins\shop;

use core\base\settings\Settings;

class ShopSettings
{
    static private ShopSettings $_instance;
    private array $routes = [
        'plugin' => [
            'pathControllers' => 'core/plugins/shop/controllers/',
            'hrUrl' => false,
            'routes' => [
                'product' => 'controller_product/get_hello/set_by',
            ],
        ],
    ];

    static public function get($property)
    {
        if (isset($property)) return self::instance()->$property;
        else return null;
    }

    protected function setSettings($properties): void
    {
        if (isset($properties)) {
            foreach ($properties as $name => $property) {
                $this->$name = $property;
            }
        }
    }

    //Паттерн Singleton
    static public function instance(): ShopSettings
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
            $unionSettings = Settings::instance()->joinProperties(get_class(self::$_instance));
            self::$_instance->setSettings($unionSettings);
        }
        return self::$_instance;
    }

    private function __clone()
    {
    }

    private function __construct()
    {
    }
}