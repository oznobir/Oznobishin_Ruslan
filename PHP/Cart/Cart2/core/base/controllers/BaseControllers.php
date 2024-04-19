<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

abstract class BaseControllers
{
    protected string $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;
    protected $page;
    protected $error = null;
    protected $writeLog = null;

    public function route()
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

    public function request($args): void
    {
        $this->parameters = $args['parameters'];
        $inputData = $args['inputMethod'];
        $outputData = $args['outputMethod'];

        $data = $this->$inputData();
        if (method_exists($this, $outputData)) {
            if ($this->$outputData($data)) $this->page = ($this->$outputData($data));
        } elseif ($data) $this->page = $data;

        if ($this->error) $this->writeLog();
        $this->getPage();
    }

    protected function getPage(): void
    {
        if (is_array($this->page)) {
            foreach ($this->page as $part) echo $part;
        } else echo $this->page;
    }

    protected function render($path = '', $data = []): false|string
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

    protected function writeLog()
    {

    }
}