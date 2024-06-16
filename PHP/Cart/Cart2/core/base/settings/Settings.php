<?php

namespace core\base\settings;

use core\base\controllers\Singleton;

class Settings
{
    use Singleton;

    private string $expansion = 'core/admin/expansions/';
    private string $info = 'core/base/messages/';
    private string $defaultTable = 'goods';
    private array $projectTables = [
        'goods' => ['name' => 'Товары', 'img' => 'pages.png'],
        'cat_goods' => ['name' => 'Категории товаров', 'img' => 'pages.png'],
        'filters' => ['name' => 'Характеристики товаров', 'img' => 'pages.png'],
        'cat_filters' => ['name' => 'Категории характеристик', 'img' => 'pages.png'],
        'manufacturer' => ['name' => 'Производители', 'img' => 'pages.png'],
        'color' => ['name' => 'Цвет', 'img' => 'pages.png'],
        'settings' => ['name' => 'Настройки сайта', 'img' => 'pages.png'],
    ];
    private string $formTemplates = PATH . 'core/admin/views/include/form_templates/';
    private array $templateArr = [
        'text' => ['name', 'filters_name', 'price', 'alias', 'phone', 'email',],
        'textarea' => ['content', 'address', 'description', 'keywords'],
        'radio' => ['visible', 'status'],
        'select' => ['position', 'pid'],
        'img' => ['img', 'img_logo'],
        'gallery_img' => ['gallery_img'],
        'checkboxlist' => ['filters', 'manufacturer', 'color'],
    ];
    private array $templateFiles = ['img', 'img_logo', 'gallery_img'];
    private array $translate = [
        'name' => ['Название', 'Не более 70 символов'],
        'filters_name' => ['Название фильтра', 'Не более 50 символов'],
        'content' => ['Описание', 'Не более 70 символов'],
        'visible' => ['Видимость', 'Показать или скрыть отображение на сайте'],
        'status' => ['Статус', 'Видимость с пометкой - нет в наличии'],
        'description' => ['SEO - описание', 'Не более 250 символов'],
        'keywords' => ['SEO - ключевые слова', 'Не более 250 символов'],
        'pid' => ['Родительская категория', ''],
        'price' => ['Цена за единицу', ''],
        'alias' => ['Alias', 'Если не заполнено, формируется автоматически'],
        'position' => ['Позиция в меню', ''],
        'address' => ['Адрес', 'Не более 300 символов'],
        'phone' => ['Телефон', ''],
        'email' => ['Email', ''],
        'gallery_img' => ['Галерея изображений', ''],
        'img' => ['Основное изображение', ''],
        'img_logo' => ['Лого сайта', ''],
    ];
    private array $rootItems = [
        'name' => 'Корневая категория',
        'tables' => ['categories', 'cat_filters'],
    ];
    private array $radio = [
        'visible' => ['Нет', 'Да', 'default' => 'Да'],
        'status' => ['Нет', 'Да', 'default' => 'Нет'],
    ];
    private array $blockNeedle = [
        'vg-rows' => [],
        'vg-img' => ['img', 'img_logo', 'gallery_img'],
        'vg-content' => ['content'],
    ];
    private array $manyToMany = [  // 'type' => 'child' || 'root' || else - all
        'filters_goods' => ['goods', 'filters'],
        'manufacturer_goods' => ['goods', 'manufacturer', 'type' => 'all'],
        'color_goods' => ['goods', 'color', 'type' => 'child'],
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