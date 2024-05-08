<?php

namespace core\admin\controllers;

use core\base\exceptions\DbException;

class EditController extends BaseAdmin
{
    protected function inputData(): void
    {
        if (!$this->userId) $this->exec();

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
                'where' => [$this->columns['pri'] => $id],
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