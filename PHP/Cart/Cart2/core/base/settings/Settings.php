<?php

namespace core\base\settings;

use core\base\controllers\Singleton;

class Settings
{
    use Singleton;

    private string $expansion = 'core/admin/expansions/';
    private string $info = 'core/base/messages/';
    private string $formTemplates = PATH . 'core/admin/views/include/form_templates/';
    private string $defaultTable = 'goods';
    private array $projectTables = [
        'goods' => ['name' => 'Товары', 'img' => 'pages.png'],
        'catalog' => ['name' => 'Категории товаров', 'img' => 'pages.png'],
        'filters' => ['name' => 'Характеристики товаров', 'img' => 'pages.png'],
        'cat_filters' => ['name' => 'Категории характеристик', 'img' => 'pages.png'],
        'manufacturer' => ['name' => 'Производители', 'img' => 'pages.png'],
        'color' => ['name' => 'Цвет', 'img' => 'pages.png'],
        'sales' => ['name' => 'Акции', 'img' => 'pages.png'],
        'news' => ['name' => 'Новости', 'img' => 'pages.png'],
        'information' => ['name' => 'Информация о сайте', 'img' => 'pages.png'],
        'socials' => ['name' => 'Социальные сети', 'img' => 'pages.png'],
        'settings' => ['name' => 'Настройки сайта', 'img' => 'pages.png'],
        'advantages' => ['name' => 'Преимущества', 'img' => 'pages.png'],
    ];
    private array $templateArr = [
        'text' => ['name', 'filters_name', 'price', 'alias', 'phone', 'email', 'external_url',
            'icons_svg', 'sub_title', 'number_years', 'discount',],
        'textarea' => ['content', 'address', 'description', 'keywords', 'short_content'],
        'radio' => ['visible', 'show_top_menu', 'hit', 'sale', 'new', 'hot'],
        'select' => ['position', 'pid'],
        'img' => ['img', 'img_logo', 'img_years', 'promo_img'],
        'gallery_img' => ['gallery_img'],
        'checkboxlist' => ['filters', 'manufacturer', 'color'],
    ];
    private array $templateFiles = ['img', 'img_logo', 'gallery_img'];
    private array $translate = [
        'name' => ['Название', 'Не более 70 символов'],
        'filters_name' => ['Название фильтра', 'Не более 50 символов'],
        'content' => ['Описание', 'Не более 70 символов'],
        'visible' => ['Видимость', 'Показать или скрыть отображение на сайте'],
        'show_top_menu' => ['Видимость в верхнем меню', 'Показать или скрыть отображение в верхнем меню'],
        'description' => ['SEO - описание', 'Не более 250 символов'],
        'keywords' => ['SEO - ключевые слова', 'Не более 250 символов'],
        'pid' => ['Родительская категория', ''],
        'price' => ['Цена за единицу', ''],
        'alias' => ['Alias', 'Если не заполнено, формируется автоматически'],
        'position' => ['Позиция в меню', ''],
        'address' => ['Адрес', 'Не более 300 символов'],
        'phone' => ['Телефон', ''],
        'email' => ['Email', ''],
        'external_url' => ['Внешняя ссылка', ''],
        'sub_title' => ['Подзаголовок', ''],
        'short_content' => ['Краткое описание', 'Не более 200 символов'],
        'gallery_img' => ['Галерея изображений', ''],
        'img' => ['Основное изображение', ''],
        'icons_svg' => ['Путь к иконке в svg', ''],
        'img_logo' => ['Лого сайта', ''],
        'promo_img' => ['Изображение о нашей компании'],
        'img_years' => ['Изображение количества лет на рынке', ''],
        'number_years' => ['Количество лет на рынке', 'Число'],
        'hit' => ['Хит продаж', ''],
        'sale' => ['Акция', ''],
        'new' => ['Новинка', ''],
        'hot' => ['Горячее предложение', ''],
        'discount' => ['Скидка', ''],

    ];
    private array $rootItems = [
        'name' => 'Корневая категория',
        'tables' => ['catalog', 'cat_filters'],
    ];
    private array $radio = [
        'visible' => ['Нет', 'Да', 'default' => 'Да'],
        'show_top_menu' => ['Нет', 'Да', 'default' => 'Да'],
        'hit' => ['Нет', 'Да', 'default' => 'Нет'],
        'sale' => ['Нет', 'Да', 'default' => 'Нет'],
        'new' => ['Нет', 'Да', 'default' => 'Нет'],
        'hot' => ['Нет', 'Да', 'default' => 'Нет'],
    ];
    private array $blockNeedle = [
        'vg-rows' => [],
        'vg-img' => ['img', 'img_logo', 'promo_img', 'gallery_img', 'img_years', 'number_years'],
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
        'discount' => ['int' => true],
        'login' => ['empty' => true, 'trim' => true],
        'password' => ['crypt' => true, 'empty' => true],
        'description' => ['count' => 350, 'trim' => true],
        'keywords' => ['count' => 350, 'trim' => true],
    ];
    private array $marketing = [
        'hit' => ['name' => 'Хиты продаж', 'icon' => '<svg><use xlink:href="' . PATH . SITE_TEMPLATE . 'assets/img/icons.svg#hit"></use></svg>'],
        'hot' => ['name' => 'Горячие предложения', 'icon' => '<svg><use xlink:href="' . PATH . SITE_TEMPLATE . 'assets/img/icons.svg#hot"></use></svg>'],
        'sale' => ['name' => 'Распродажа', 'icon' => '%'],
        'new' => ['name' => 'Новинки', 'icon' => '<span>new</span>'],
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