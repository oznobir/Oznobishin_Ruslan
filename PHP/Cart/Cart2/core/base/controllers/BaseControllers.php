<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

abstract class BaseControllers
{
    use BaseMethods;

    protected string $controller;
    protected string $inputMethod;
    protected string $outputMethod;
    protected ?array $parameters = null;
    protected array|string $page;
    protected ?string $error = null;
    protected array $styles;
    protected array $scripts;


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

    public function request($args): void
    {
        $this->parameters = $args['parameters'];
        $inputData = $args['inputMethod'];
        $outputData = $args['outputMethod'];

        $data = $this->$inputData();
        if (method_exists($this, $outputData)) {
            if ($this->$outputData($data)) $this->page = ($this->$outputData($data));
        } elseif ($data) $this->page = $data;

        if ($this->error) $this->writeLog($this->error);
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

    protected function init($admin = false): void
    {
        if (!$admin) {
            if (SITE_CSS_JS['styles']) {
                foreach (SITE_CSS_JS['styles'] as $item) $this->styles[] = PATH . SITE_TEMPLATE . trim($item, '/');
            }
            if (SITE_CSS_JS['scripts']) {
                foreach (SITE_CSS_JS['scripts'] as $item) $this->scripts[] = PATH . SITE_TEMPLATE . trim($item, '/');
            }
        } else {
            if (ADMIN_CSS_JS['styles']) {
                foreach (SITE_CSS_JS['styles'] as $item) $this->styles[] = PATH . ADMIN_TEMPLATE . trim($item, '/');
            }
            if (ADMIN_CSS_JS['scripts']) {
                foreach (SITE_CSS_JS['scripts'] as $item) $this->scripts[] = PATH . ADMIN_TEMPLATE . trim($item, '/');
            }
        }
    }
}