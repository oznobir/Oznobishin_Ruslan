<?php

namespace core\admin\controllers;


use core\base\exceptions\DbException;
use core\base\settings\Settings;

class AddController extends BaseAdmin
{
    /**
     * @throws DbException
     */
    protected function inputData(): void
    {
        if (!$this->userId) $this->exec();
        $this->createTableData();
        $this->createForeignData();
        $this->createOutputData();

    }

    /**
     * @throws DbException
     */
    protected function createForeignData($settings = false): void
    {
        if (!$settings) $settings = Settings::instance();
        $rootItems = Settings::get('rootItems');
        $keys = $this->model->showForeignKeys($this->table);
        if ($keys) {
            foreach ($keys as $item) {
                if (in_array($this->table, $rootItems['tables'])) {
                    $this->foreignData[$item['COLUMN_NAME']][0]['id'] = 0;
                    $this->foreignData[$item['COLUMN_NAME']][0]['name'] = $rootItems['name'];
                }
                $columns = $this->model->showColumns($item['REFERENCED_TABLE_NAME']);
                $name = '';
                if ($columns['name']) $name = 'name';
                else {
                    foreach ($columns as $key => $value) {
                        if (str_contains($key, 'name')) {
                            $name .= $key . ' as name';
                        }
                    }
                    if (!$name) $name = $columns['pid'] . 'as name';
                }
                $where = [];
                $operand = [];
                if ($this->data) {
                    if ($item['REFERENCED_TABLE_NAME'] === $this->table) {
                        $where[$this->columns['pid']] = $this->columns['pid'];
                        $operand[] = '<>';
                    }
                }
                $foreign[$item['COLUMN_NAME']] = $this->model->select($item['REFERENCED_TABLE_NAME'], [
                    'fields' => [$item['REFERENCED_COLUMN_NAME'] . ' as id', $name],
                    'where' => $where,
                    'operand' => $operand,
                ]);
                if ($foreign[$item['COLUMN_NAME']]) {
                    if ($this->foreignData[$item['COLUMN_NAME']]) {
                        foreach ($foreign[$item['COLUMN_NAME']] as $value) {
                            $this->foreignData[$item['COLUMN_NAME']][] = $value;
                        }
                    } else $this->foreignData[$item['COLUMN_NAME']] = $foreign[$item['COLUMN_NAME']];
                }
            }
        } elseif ($this->columns['pid']) {
            if (in_array($this->table, $rootItems['table'])) {
                $this->foreignData['pid'][0]['id'] = 0;
                $this->foreignData['pid'][0]['name'] = $rootItems['name'];
            }
            $name = '';
            if ($this->columns['name']) $name = 'name';
            else {
                foreach ($this->columns as $key => $value) {
                    if (str_contains($key, 'name')) {
                        $name .= $key . ' as name';
                    }
                }
                if (!$name) $name = $this->columns['pid'] . 'as name';
            }
            $where = [];
            $operand = [];
            if ($this->data) {
                $where[$this->columns['pid']] = $this->columns['pid'];
                $operand[] = '<>';
            }
            $foreign['pid'] = $this->model->select($this->table, [
                'fields' => [$this->columns['pid'] . ' as id', $name],
                'where' => $where,
                'operand' => $operand,
            ]);
            if ($foreign) {
                if ($this->foreignData['pid']) {
                    foreach ($foreign as $value) {
                        $this->foreignData['pid'][] = $value;
                    }
                } else $this->foreignData['pid'] = $foreign;
            }


        }

return;
    }
}