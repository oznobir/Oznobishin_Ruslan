<?php

namespace core\plugins\shop;

use core\base\controllers\Singleton;
use core\base\settings\Settings;

class ShopSettings
{
    use Singleton;

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

    static public function instance(): ShopSettings
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
            $unionSettings = Settings::instance()->joinProperties(get_class(self::$_instance));
            self::$_instance->setSettings($unionSettings);
        }
        return self::$_instance;
    }
    protected function setSettings($properties): void
    {
        if (isset($properties)) {
            foreach ($properties as $name => $property) {
                $this->$name = $property;
            }
        }
    }

}