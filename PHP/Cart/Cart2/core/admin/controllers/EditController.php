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
        if (!$this->userId) $this->exec();

        if ($this->isPost()) $this->checkPost();
        else $this->createTableData();

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
     * @return false|string
     * @throws RouteException
     */
    protected function outputData(): false|string // перенести в parent::outputData()
    {
        $this->contentMenu = $this->render(ADMIN_TEMPLATE . 'include/menu');
        $this->contentCenter = $this->render($this->template);
        return parent::outputData();
    }

    /**
     * @throws RouteException
     * @throws DbException
     */
    protected function createEditData(): void
    {
        if(is_numeric($this->parameters[$this->table]))
            $id = $this->num($this->parameters[$this->table]);
        else $id = $this->clearTags($this->parameters[$this->table]);

        if (!$id) throw new RouteException('Некорректный ID - ' . $id . 'при редактировании таблицы ' . $this->table);
        $this->data = $this->model->select($this->table, [
            'where' => [$this->columns['pri'][0] => $id],
        ]);
        $this->data && $this->data = $this->data[0];
    }

    /** Таблица old_alias
     * поля: alias, table_name, table_id
     * @param $id
     * @return void
     * @throws DbException
     */
    protected function checkOldAlias($id): void
    {
        $tables = $this->model->showTables();
        if (in_array('old_alias', $tables)) {
            $oldAlias = $this->model->select($this->table, [
                'fields' => ['alias'],
                'where' => [$this->columns['pri'][0] => $id],
            ])[0]['alias'];
            if ($oldAlias && $oldAlias !== $_POST['alias']) {
                $this->model->delete('old_alias', [
                    'where' => ['alias' => $oldAlias, 'table_name' => $this->table]
                ]);
                $this->model->delete('old_alias', [
                    'where' => ['alias' => $_POST['alias'], 'table_name' => $this->table]
                ]);
                $this->model->add('old_alias', [
                    'fields' => ['alias' => $oldAlias, 'table_name' => $this->table, 'table_id' => $id]
                ]);
            }
        }
    }
}