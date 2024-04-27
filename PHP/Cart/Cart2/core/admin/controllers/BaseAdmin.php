<?php

namespace core\admin\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseControllers;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\admin\expansions\ArticlesExpansion;

abstract class BaseAdmin extends BaseControllers
{
    protected $model;
    protected $table;
    protected $columns;
    protected $data;
    protected $alias;
    protected $path;
    protected $menu;
    protected string $title;

    protected function inputData(): void
    {
        $this->init(true);
        $this->title = 'Admin panel';
        if (!$this->model) $this->model = Model::instance();
        if (!$this->menu) $this->menu = Settings::get('projectTables');
        if (!$this->alias) $this->alias = Settings::get('routes')['admin']['alias'];
        if (!$this->path) $this->path = Settings::get('routes')['admin']['alias'].'/';
        $this->sendNoCacheHeaders();
    }
    protected function exec(): void
    {
        self::inputData();
    }

    /**
     * @throws RouteException
     */
    protected function outputData(): false|string
    {
        $this->header = $this->render(ADMIN_TEMPLATE. 'include/header');
        $this->footer = $this->render(ADMIN_TEMPLATE. 'include/footer');

        return $this->render(ADMIN_TEMPLATE.'layout/default');
    }

    protected function sendNoCacheHeaders(): void
    {
        header("Last-Modified: " . gmdate('D, d M Y H:i:s') . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Cache-Control: max-age=0");
        header("Cache-Control: post-check=0, pre-check=0");
    }

    protected function createTableData(): void
    {
        if (!$this->table) {
            if ($this->parameters) $this->table = array_keys($this->parameters)[0];
            else  $this->table = Settings::get('defaultTable');
        }
        $this->columns = $this->model->showColumns($this->table);
        if (!$this->columns) new RouteException('Не найдены поля в таблице - ' . $this->table, 2);

    }


    protected function expansionBase($args = [], $settings = false)
    {
        $fileName = explode('_', $this->table);
        $className = '';
        foreach ($fileName as $name)
            $className .= ucfirst($name);

        if (!$settings) $path = Settings::get('expansion');
        elseif (is_object($settings)) $path = $settings::get('expansion');
        else $path = $settings;

        $class = $path . $className . 'Expansion';
        if (is_readable($_SERVER['DOCUMENT_ROOT'] . PATH . $class . '.php')) {
            $class = str_replace('/', '\\', $class);
            $exp = $class::instance();
            foreach ($this as $name => $value) {
                if (!$this->model || !$this->columns)  // ???????? Model, array to string
                    $exp->$name = &$this->$value;
            }
            return $exp->expansion($args);
        } else {
            $file = $_SERVER['DOCUMENT_ROOT'] . PATH . $this->table . '.php';
            if (is_readable($file)) return include $file;
        }
        return false;
    }
}