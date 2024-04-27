<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

abstract class BaseControllers
{
    use BaseMethods;

    protected $header;
    protected $contentMenu;
    protected $contentCenter;
    protected $footer;
    protected $page;
    protected string $controller;
    protected string $inputMethod;
    protected string $outputMethod;
    protected ?array $parameters = null;
    protected ?string $error = null;
    protected $template;
    protected $styles;
    protected $scripts;

    /** Подключение контроллера с помощью Reflection
     * @return void метод request
     * @throws RouteException ошибки при подключении
     */

    public function route(): void
    {
        $controller = str_replace('/', '\\', $this->controller);
        try {
            $reflection = new \ReflectionMethod($controller, 'request');
            $args = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->inputMethod,
                'outputMethod' => $this->outputMethod,
            ];
            $reflection->invoke(new $controller, $args);
        } catch (\ReflectionException $e) {
            throw new RouteException($e->getMessage());
        }
    }

    /**
     * @param array $args массив аргументов обработки Reflection
     * @return void запись в свойства: страницы в виде строки или массива, ошибок
     */
    public function request(array $args): void
    {
        $this->parameters = $args['parameters'];
        $inputData = $args['inputMethod'];
        $outputData = $args['outputMethod'];

        if (method_exists($this, $outputData)) {
            $this->$inputData();
            $this->page = $this->$outputData();
        } else $this->page = $this->$inputData();

        if ($this->error) $this->writeLog($this->error);
        $this->getPage();
    }

    /**
     * @return void вывод свойства page
     */
    protected function getPage(): void
    {
        if (is_array($this->page)) {
            foreach ($this->page as $part) echo $part;
        } else echo $this->page;
    }

    /**
     * @param string $path путь к шаблону, по умолчанию название контроллера по пути SITE_TEMPLATE или ADMIN_TEMPLATE
     * @param array $data массив данных для формирования шаблона
     * @return false|string страница в виде строки
     * @throws RouteException ошибки
     */
    protected function render(string $path = '', array $data = []): false|string
    {
        if (!$path) {
            $class = new \ReflectionClass($this);
            $space = str_replace('\\', '/', $class->getNamespaceName() . '\\');
            $routes = Settings::get('routes');

            if ($space === $routes['site']['pathControllers']) $template = SITE_TEMPLATE;
            else $template = ADMIN_TEMPLATE;

            $path = $template . explode('controller', strtolower($class->getShortName()))[0] . '.php';
        } else $path .= '.php';

        if ($data) extract($data);
        ob_start();
        if (!@include_once $path) throw new RouteException('Нет шаблона - ' . $path);
        return ob_get_clean();
    }

    /**
     * @param bool $admin при false SITE_TEMPLATE,  при true ADMIN_TEMPLATE
     * @return void
     */
    protected function init(bool $admin = false): void
    {
        if (!$admin) {
            if (SITE_CSS_JS['styles']) {
                foreach (SITE_CSS_JS['styles'] as $item)
                    $this->styles[] = PATH . SITE_TEMPLATE . trim($item, '/');
            }
            if (SITE_CSS_JS['scripts']) {
                foreach (SITE_CSS_JS['scripts'] as $item)
                    $this->scripts[] = PATH . SITE_TEMPLATE . trim($item, '/');
            }
        } else {
            if (ADMIN_CSS_JS['styles']) {
                foreach (ADMIN_CSS_JS['styles'] as $item)
                    $this->styles[] = PATH . ADMIN_TEMPLATE . trim($item, '/');
            }
            if (ADMIN_CSS_JS['scripts']) {
                foreach (ADMIN_CSS_JS['scripts'] as $item)
                    $this->scripts[] = PATH . ADMIN_TEMPLATE . trim($item, '/');
            }
        }
    }
}