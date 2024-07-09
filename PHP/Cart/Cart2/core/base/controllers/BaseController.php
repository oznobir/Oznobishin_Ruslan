<?php

namespace core\base\controllers;

use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\base\models\UsersModel;
use core\base\settings\Settings;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

abstract class BaseController
{
    use BaseMethods;

    protected string $header;
    protected string $content;
    protected string $footer;
    protected string|array $page;
    protected string $controller;
    protected string $inputMethod;
    protected string $outputMethod;
    protected ?array $parameters = null;
    protected array $data = [];
    protected ?string $error = null;
    protected ?string $template = null;
    protected array $userData = ['id' => false];
    protected array $styles;
    protected array $scripts;

    /** Подключение контроллера с помощью Reflection
     * @return void метод request
     * @throws RouteException ошибки при подключении
     */

    public function route(): void
    {
        $controller = str_replace('/', '\\', $this->controller);
        try {
            $reflection = new ReflectionMethod($controller, 'request');
            $args = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->inputMethod,
                'outputMethod' => $this->outputMethod,
            ];
            $reflection->invoke(new $controller, $args);
        } catch (ReflectionException $e) {
            throw new RouteException($e->getMessage());
        }
    }

    /**
     * @param array $args массив аргументов обработки Reflection
     * @return void запись в свойства: страницы в виде строки или массива, ошибок
     * @uses request
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
            $class = new ReflectionClass($this);
            $space = str_replace('\\', '/', $class->getNamespaceName() . '\\');
            $routes = Settings::get('routes');

            if ($space === $routes['site']['pathControllers']) $template = SITE_TEMPLATE;
            else $template = ADMIN_TEMPLATE;

//            $path = $template . explode('controller', strtolower($class->getShortName()))[0] . '.php';
            $path = $template . $this->getController();
        }

        if ($data) extract($data);
        ob_start();
        if (!include $path . '.php') throw new RouteException('Нет шаблона - ' . $path . '.php');
        return ob_get_clean();
    }

    /**
     * @param bool $admin при false SITE_TEMPLATE,  при true ADMIN_TEMPLATE
     * @return void
     */
    protected function init(bool $admin = false): void
    {
        $path = $admin ? ADMIN_TEMPLATE : SITE_TEMPLATE;
        $const = $admin ? ADMIN_CSS_JS : SITE_CSS_JS;
        if (!empty($const['styles_external'])) {
            foreach ($const['styles_external'] as $item)
                $this->styles[] = trim($item, '');
        }
        if (!empty($const['scripts_external'])) {
            foreach ($const['scripts_external'] as $item)
                $this->scripts[] = trim($item, '');
        }
        if (!empty($const['styles_our'])) {
            foreach ($const['styles_our'] as $item)
                $this->styles[] = PATH . $path . trim($item, '/');
        }
        if (!empty($const['scripts_our'])) {
            foreach ($const['scripts_our'] as $item)
                $this->scripts[] = PATH . $path . trim($item, '/');
        }
    }

    /**
     * @param bool $type
     * @return void
     * @throws DbException
     * @throws RouteException
     */
    protected function checkAuth(bool $type = false): void
    {
        $userData = UsersModel::instance()->checkUser(false, $type);

        if (!$userData && $type) $this->redirect(PATH);
        if ($userData) $this->userData = $userData;

        if (property_exists($this, 'usersModel')) {
            $this->usersModel = UsersModel::instance();
        }
    }
}