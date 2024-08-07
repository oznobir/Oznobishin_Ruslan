<?php

namespace core\admin\controllers;

use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;

/** @uses EditController */
class EditController extends BaseAdmin
{
    /** @uses $action */
    protected string $action = 'edit';

    /**
     * @throws RouteException
     * @throws DbException
     */
    protected function inputData(): void
    {
        if (!$this->userData['id']) $this->exec();

        if ($this->isPost()) $this->checkPost();
        $this->createTableData();
        $this->createEditData();
        $this->createForeignData();
        $this->createMenuPosition();
        $this->createRadio();
        $this->createOutputData();
        $this->createManyToMany();
        $this->template = ADMIN_TEMPLATE . 'add';
        $this->expansionBase();

    }

    /**
     * @throws RouteException
     * @throws DbException
     */
    protected function createEditData(): void
    {
        if (is_numeric($this->parameters[$this->table]))
            $id = $this->num($this->parameters[$this->table]);
        else $id = $this->clearTags($this->parameters[$this->table]);

        if (!$id) throw new RouteException('Некорректный ID - ' . $id . 'при редактировании таблицы ' . $this->table);

        $data = $this->model->select($this->table, [
            'where' => [$this->columns['pri'][0] => $id],
        ]);
        if (is_array($data)) $this->data = $data[0];
        else throw new RouteException('Ошибка получения данных при редактировании таблицы ' . $this->table);
    }
}