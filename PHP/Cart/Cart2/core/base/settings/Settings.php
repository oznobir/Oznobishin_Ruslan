<?php

namespace core\base\settings;

use core\base\controllers\Singleton;

class Settings
{
    use Singleton;
    private string $expansion = 'core/admin/expansions/';
    private string $messages = 'core/base/messages/';

    private string $defaultTable = 'products';
    private array $projectTables = [
        'products' => ['name' => 'Товары', 'img' => 'pages.png'],
        'categories' => ['name' => 'Категории товаров', 'img' => 'pages.png'],
        'articles' => ['name' => 'Статьи', 'img' => 'pages.png'],
    ];
    private string $formTemplates = PATH.'core/admin/views/include/form_templates/';
    private array $templateArr = [
        'text' => ['name'],
        'textarea' => ['description'],
        'radio' => ['visible', 'status'],
        'select' => ['position', 'pid'],
        'img' => ['img'],
        'gallery_img' => ['gallery_img']
    ];
    private array $translate = [
        'name' => ['Название', 'Не более 70 символов'],
        'visible' => ['Видимость', 'Показать или скрыть отображение на сайте'],
        'status' => ['Статус', 'Видимость с пометкой - нет в наличии'],
        'description' => ['Описание', 'Не более 160 символов'],
        'pid' => ['Категория', ''],
    ];
    private array $rootItems = [
        'name' => 'Корневая категория',
        'tables' => ['categories', 'articles'],
    ];
    private array $radio = [
        'visible' => ['Нет', 'Да', 'default' => 'Да'],
        'status' => ['Нет', 'Да', 'default' => 'Нет'],
    ];
    private array $blockNeedle = [
        'vg-rows' => [],
        'vg-img' => ['img'],
        'vg-content' => ['description'],
    ];
    private array $validation = [
        'name' => ['empty' => true, 'count' => 70, 'trim' => true],
        'price' => ['int' => true],
        'login' => ['empty' => true, 'trim' => true],
        'password' => ['crypt' => true, 'empty' => true],
        'description' => ['count' => 160, 'trim' => true],
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