<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

class RouteController extends BaseControllers
{
    use Singleton;
    use BaseMethods;
    protected array $routes;

    private function getURI($baseURI): string
    {
        $requesturi = $_SERVER["REQUEST_URI"];
        if (str_starts_with($requesturi, $baseURI)) {
            $requesturi = substr($requesturi, strlen($baseURI));
        }
        return trim($requesturi, '/');
    }

    private function __construct()
    {
        $strUri = $_SERVER['REQUEST_URI'];

        // слэш в конце uri
        if (str_ends_with($strUri, '/') && $strUri !== '/') {
            $this->redirect(rtrim($strUri, '/'), 301);
        }
        // далее в uri const PATH
        $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));
        if ($path !== PATH)  throw new RouteException('Не существующая директория сайта', 1);

        // есть ли routes Settings
        $this->routes = Settings::get('routes');
        if (!$this->routes) throw new RouteException('Отсутствуют маршруты в базовых настройках', 1);

        // далее в uri == alias admin
        $url = explode('/', substr($strUri, strlen(PATH)));
        if (isset($url[0]) && $url[0] === $this->routes['admin']['alias']) {
            array_shift($url);

            // далее в uri == папке название плагина (name), файл настроек плагина - NameSettings.php
            if (isset($url[0]) && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugin']['path'] . $url[0])) {
                $plugin = array_shift($url);
                $pluginSettings = $this->routes['plugin']['path'] . $plugin . '/' . ucfirst($plugin) . 'Settings';
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')) {
                    $pluginSettings = str_replace('/', '\\', $pluginSettings);
                    $this->routes = $pluginSettings::get('routes');
                    $this->controller = $this->routes['plugin']['pathControllers'];
                } else {
                    $this->controller = $this->routes['plugin']['pathControllers'] . $plugin;
                }
                $hrUrl = $this->routes['plugin']['hrUrl'];
                $nameRoute = 'plugin';

            } else {
                $this->controller = $this->routes['admin']['pathControllers'];
                $hrUrl = $this->routes['admin']['hrUrl'];
                $this->controller = $this->routes['admin']['pathControllers'];
                $nameRoute = 'admin';
            }

            //далее в uri != alias admin
        } else {
            $hrUrl = $this->routes['site']['hrUrl'];
            $this->controller = $this->routes['site']['pathControllers'];
            $nameRoute = 'site';
        }
        $this->creatRoute($nameRoute, $url);

        // далее в uri параметры: при hrUrl = false - ключ -> значение, при hrUrl = true - slug, ключ -> значение
        if (isset($url[1])) {
            $count = count($url);
            $key = '';
            if (!$hrUrl) $i = 1;
            else {
                $i = 2;
                $this->parameters['slug'] = $url[1];
            }
            for (; $i < $count; $i++) {
                if (!$key) {
                    $key = $url[$i];
                    $this->parameters[$key] = '';
                } else {
                    $this->parameters[$key] = $url[$i];
                    $key = '';
                }
            }
        }
    }

    private function creatRoute($nameRoute, $url): void
    {
        $routes = [];
        if (!empty($url[0])) {
            if (isset($this->routes[$nameRoute]['routes'][$url[0]])) {
                $routes = explode('/', $this->routes[$nameRoute]['routes'][$url[0]]);
                $this->controller .= ucfirst($routes[0]) . 'Controller';
            } else {
                $this->controller .= ucfirst($url[0]) . 'Controller';
            }
        } else {
            $this->controller .= ucfirst($this->routes['default']['controller']);
        }
        $this->inputMethod = $routes[1] ?? $this->routes['default']['inputMethod'];
        $this->outputMethod = $routes[2] ?? $this->routes['default']['outputMethod'];
    }
}