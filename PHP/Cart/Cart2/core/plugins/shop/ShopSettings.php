<?php

namespace core\plugins\shop;

use core\base\controllers\Singleton;
use core\base\settings\Settings;

class ShopSettings
{
    use Singleton {
        instance as traitInstance;
    }

    private Settings $baseSettings;

    private string $expansion = 'core/plugins/shop/';
    private array $routes = [
        'plugin' => [
            'path' => 'core/plugins/shop/',
            'pathControllers' => 'core/plugins/shop/controllers/',
            'hrUrl' => false,
            'routes' => ['product' => 'controller_product/get_hello/set_by',],

        ],
    ];
    private array $templateArr = [
        'text' => ['name', 'price'],

    ];

    static public function instance(): object
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
            self::traitInstance()->baseSettings = Settings::instance();
            $unionSettings = self::$_instance->baseSettings->joinProperties(get_class(self::$_instance));
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