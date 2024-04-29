<?php

namespace core\base\settings;

use core\base\controllers\Singleton;

class Settings
{
    use Singleton;
    private string $expansion = 'core/admin/expansions/';
    private string $defaultTable = 'products';
    private array $projectTables = [
        'products' => ['name' => 'Товары', 'img' => 'pages.png'],
        'categories' => ['name' => 'Категории товаров', 'img' => 'pages.png'],
    ];
    private array $translate = [
        'name' => ['Название', 'Не более 100 символов'],
    ];
    private array $rootItems = [
        'name' => 'Корневая',
        'tables' => ['products'],
    ];
    private array $blockNeedle = [
        'vg-row' => [],
        'vg_img' => ['img'],
        'vg-content' => ['description'],
    ];

    private array $routes = [
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData',
        ],
        'site' => [
            'pathControllers' => 'core/site/controllers/',
            'hrUrl' => true,
            'routes' => [
                'catalog' => 'index',
            ],
        ],
        'admin' => [
            'alias' => 'admin',
            'pathControllers' => 'core/admin/controllers/',
            'hrUrl' => false,
            'routes' => [],
        ],
        'plugin' => [                                           // если есть NameSettings.php с $routes и ключом 'plugin', им можно изменить default и добавить новые настройки
            'path' => 'core/plugins/',                          // + name (core/plugins/name/NameSettings.php) - файл настроек для изменения default, не изменяется в NameSettings.php
            'pathControllers' => 'core/plugins/controllers/',   // + name - default, можно изменить, если есть NameSettings.php
            'hrUrl' => false,                                   // default, можно изменить, если есть NameSettings.php
            'routes' => [],                                     // default, можно изменить, если есть NameSettings.php
        ],
    ];

    public function joinProperties($class): array
    {
        $baseProperties = [];
        foreach ($this as $name =>
                 $item) {
            $property = $class::get($name);
            if ($property && is_array($property) && is_array($item)) {
                $baseProperties[$name] = $this->arrayMergeRecursive($this->$name, $property);
                continue;
            }
            $baseProperties[$name] = $this->$name;
        }
        return $baseProperties;
    }

    public function arrayMergeRecursive(array $base, array $add): array
    {
        foreach ($add as $key => $value) {
            if (is_array($value) && (isset($base[$key]) && is_array($base[$key]))) {
                $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
            } else {
                if (is_int($key)) {
                    if (in_array($value, $base) && array_push($base, $value)) {
                        continue;
                    }
                }
                $base[$key] = $value;
            }
        }
        return $base;
    }
}