<?php

namespace core\plugins\shop;

use core\base\controllers\Singleton;
use core\base\settings\Settings;

class ShopSettings
{
    use Singleton;

    private array $routes = [
        'plugin' => [
            'path' => 'core/plugins/shop/',
            'pathControllers' => 'core/plugins/shop/controllers/',
            'hrUrl' => false,
            'routes' => ['product' => 'controller_product/get_hello/set_by', ],

        ],
        'p' => ['1', '2', '3'],
    ];


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