<?php

namespace core\plugins\shop;

use core\base\settings\BaseSettings;

class ShopSettings
{
    use BaseSettings;

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
        'textarea' => ['goods_content', 'goods_description'],
    ];

}