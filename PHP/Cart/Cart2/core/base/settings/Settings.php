<?php

namespace core\base\settings;

class Settings
{
    static private Settings $_instance;


    private array $routes = [
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData',
        ],
        'site' => [
            'pathControllers' => 'core/user/controllers/',
            'hrUrl' => true,
            'routes' => [
                'catalog' => 'catalog/hello/by',
            ],
        ],
        'admin' => [
            'alias' => 'admin',
            'pathControllers' => 'core/admin/controllers/',
            'hrUrl' => false,
            'routes' => [],
        ],
        'plugin' => [
            'path' => 'core/plugins/',                          // core/plugins/name - нельзя изменить
            'pathControllers' => 'core/plugins/controllers/',   // default, можно изменить в core/plugins/name/NameSettings.php
            'hrUrl' => false,                                   // default, можно изменить в core/plugins/name/NameSettings.php
            'routes' => [],                                     // default, можно изменить в core/plugins/name/NameSettings.php
        ],

    ];

    static public function get($property)
    {
        if (isset($property)) return self::instance()->$property;
        else return null;
    }

    //Паттерн Singleton
    static public function instance(): Settings
    {
        if (!isset(self::$_instance)) self::$_instance = new self();
        return self::$_instance;
    }

    public function joinProperties($class): array
    {
        $baseProperties = [];
        foreach ($this as $name => $item) {
            $property = $class::get($name);
            if (!$property) {
                $baseProperties[$name] = $this->$name;
                continue;
            }
            if (is_array($property) && is_array($item)) {
                $baseProperties[$name] = $this->arrayMergeRecursive($this->$name, $property);
            }

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

    private function __clone()
    {
    }

    private function __construct()
    {
    }
}