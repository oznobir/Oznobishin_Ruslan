<?php

namespace core\admin\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseControllers;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;

abstract class BaseAdmin extends BaseControllers
{
    protected $model;
    protected $table;
    protected $columns;
    protected $data;
    protected $menu;
    protected string $title;

    protected function inputData(): void
    {
        $this->init(true);
        $this->title = 'Admin panel';
        if (!$this->model) $this->model = Model::instance();
        if (!$this->menu) $this->menu = Settings::get('projectTables');
        $this->sendNoCacheHeaders();
    }

    protected function outputData(): void
    {

    }

    protected function sendNoCacheHeaders(): void
    {
        header("Last-Modified: " . gmdate('D, d M Y H:i:s') . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Cache-Control: max-age=0");
        header("Cache-Control: post-check=0, pre-check=0");
    }

    protected function inputDataBase(): void
    {
        self::inputData();
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

    /**
     * @param array $args
     * @param false|string|object $settings
     * @return false|mixed
     */
    protected function expansion(array $args = [], false|string|object $settings = false): mixed
    {
        $fileName = explode('_', $this->table);
        $className = '';
        foreach ($fileName as $name) $className .= ucfirst($name);

        if(!$settings) $path = Settings::get('expansion');
        elseif (is_object($settings)) $path = $settings::get('expansion');
        else $path = $settings ;

        $class = $path . $className . 'Expansion';
        if (is_readable($_SERVER['DOCUMENT_ROOT'] . PATH . $class . '.php')) {
            $class = str_replace('/', '\\', $class);
            $exp = $class::instance();
            foreach ($this as $name => $value){
                $exp->$name = &$this->$value;
            }
            return $exp->expansionBase($args);
        } else {
            $file = $_SERVER['DOCUMENT_ROOT']. PATH . $this->table . '.php';
            if(is_readable($file)) return include $file;
        }
        return false;
    }
}