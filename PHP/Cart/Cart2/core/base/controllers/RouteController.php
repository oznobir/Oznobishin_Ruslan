<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

class RouteController extends BaseController
{
    use Singleton;
    use BaseMethods;

    protected array $routes;


    /**
     * @throws RouteException
     */
    private function __construct()
    {
        $strUri = $_SERVER['REQUEST_URI'];
        // проверяем слэш в конце uri
        if (str_ends_with($strUri, '/') && $strUri !== PATH)
            $this->redirect(rtrim($strUri, '/'), 301);
        // удаляем в конце uri QUERY_STRING
        if ($_SERVER['QUERY_STRING'])
            $strUri = substr($strUri, 0, strpos($strUri, $_SERVER['QUERY_STRING']) - 1);
        // проверяем в uri const PATH
        if (substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php')) !== PATH)
            throw new RouteException('Не существующая директория сайта', 1);

        $this->routes = Settings::get('routes');
        // проверяем в uri alias admin
        $url = explode('/', substr($strUri, strlen(PATH)));
        if (isset($url[0]) && $url[0] === $this->routes['admin']['alias']) {
            array_shift($url);
            // проверяем в uri название плагина (name), файл настроек плагина - NameSettings.php
            if (isset($url[0]) && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugin']['path'] . $url[0])) {
                $plugin = array_shift($url);
                $pluginSettings = $this->routes['plugin']['path'] . $plugin . '/' . ucfirst($plugin) . 'Settings';
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')) {
                    $pluginSettings = str_replace('/', '\\', $pluginSettings);
                    $this->routes = $pluginSettings::get('routes');
                    $this->controller = $this->routes['plugin']['pathControllers'] ?? $this->routes['admin']['pathControllers'];
                    $hrUrl = $this->routes['plugin']['hrUrl'] ?? $this->routes['admin']['hrUrl'];
                } else {
                    $this->controller = $this->routes['admin']['pathControllers'];
                    $hrUrl = $this->routes['plugin']['hrUrl'];
                }
                $nameRoute = 'plugin';

            } else { // если в uri admin нет плагина (то admin)
                $this->controller = $this->routes['admin']['pathControllers'];
                $hrUrl = $this->routes['admin']['hrUrl'];
                $this->controller = $this->routes['admin']['pathControllers'];
                $nameRoute = 'admin';
            }
        } else { //если в uri нет alias admin (то site)
            $hrUrl = $this->routes['site']['hrUrl'];
            $this->controller = $this->routes['site']['pathControllers'];
            $nameRoute = 'site';
        }
        $this->creatRoute($nameRoute, $url);

        // далее в uri параметры: при hrUrl = false - ключ -> значение, при hrUrl = true - alias, ключ -> значение
        if (isset($url[1])) {
            $count = count($url);
            $key = '';
            if (!$hrUrl) $i = 1;
            else {
                $i = 2;
                $this->parameters['alias'] = $url[1];
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