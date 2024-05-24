<?php

namespace core\admin\controllers;


use core\base\exceptions\DbException;
use core\base\settings\Settings;

/**
 * @uses AddController
 */
class AddController extends BaseAdmin
{
//    protected string $action = 'add';

    /**
     * @return void
     * @throws DbException
     */
    protected function inputData(): void
    {
        if (!$this->userId) $this->exec();

        if ($this->isPost()) $this->checkPost();
        else $this->createTableData();

        $this->createForeignData();
        $this->createMenuPosition();
        $this->createRadio();
        $this->createOutputData();

    }

    protected function outputData(): false|string
    {
        if (!$this->template) $this->template = ADMIN_TEMPLATE . 'add';
        $this->contentMenu = $this->render(ADMIN_TEMPLATE . 'include/menu');
        $this->contentCenter = $this->render($this->template);
        return parent::outputData();
    }

    /**
     * @param object|string|false $settings
     * @return void
     * @throws DbException
     */
    protected function createForeignData(object|string|false $settings = false): void
    {
        if (!$settings) $settings = Settings::instance();
        $rootItems = $settings::get('rootItems');
        $keys = $this->model->showForeignKeys($this->table);
        if ($keys) {
            foreach ($keys as $item) {
                $this->createForeignProperty($item, $rootItems);
            }
        } elseif ($this->columns['pid']) {
            $arr['COLUMN_NAME'] = 'pid';
            $arr['REFERENCED_COLUMN_NAME'] = $this->columns['pri'][0];
            $arr['REFERENCED_TABLE_NAME'] = $this->table;
            $this->createForeignProperty($arr, $rootItems);
        }
    }

    /**
     * @param array $columnsTable
     * @param array $rootItems
     * @return void
     * @throws DbException
     */
    protected function createForeignProperty(array $columnsTable, array $rootItems): void
    {
        if (in_array($this->table, $rootItems['tables'])) {
            $this->foreignData[$columnsTable['COLUMN_NAME']][0]['id'] = 0;
            $this->foreignData[$columnsTable['COLUMN_NAME']][0]['name'] = $rootItems['name'];
        }
        $columns = $this->model->showColumns($columnsTable['REFERENCED_TABLE_NAME']);
        $name = '';
        if ($columns['name']) $name = 'name';
        else {
            foreach ($columns as $key => $value) {
                if (str_contains($key, 'name')) $name .= $key . ' as name';
            }
            if (!$name) $name = $columns['pri'][0] . ' as name';
        }
        $where = [];
        $operand = [];
        if ($this->data) {
            if ($columnsTable['REFERENCED_TABLE_NAME'] === $this->table) {
                $where[$this->columns['pri'][0]] = $this->data[$this->columns['pri'][0]];
                $operand[] = '<>';
            }
        }
        $foreign = $this->model->select($columnsTable['REFERENCED_TABLE_NAME'], [
            'fields' => [$columnsTable['REFERENCED_COLUMN_NAME'] . ' as id', $name],
            'where' => $where,
            'operand' => $operand,
        ]);
        if ($foreign) {
            if (!empty($this->foreignData[$columnsTable['COLUMN_NAME']])) {
                foreach ($foreign as $value) {
                    $this->foreignData[$columnsTable['COLUMN_NAME']][] = $value;
                }
            } else $this->foreignData[$columnsTable['COLUMN_NAME']] = $foreign;
        }
    }

    /**
     * @param object|string|false $settings
     * @return void
     * @throws DbException
     */
    protected function createMenuPosition(object|string|false $settings = false): void
    {
        if ($this->columns['position']) {
            if (!$settings) $settings = Settings::instance();
            $rootItems = $settings::get('rootItems');
            $where = '';
            if ($this->columns['pid']) {
                if (in_array($this->table, $rootItems['tables'])) $where = 'pid IS NULL OR pid = 0';
                else {
                    $parent = $this->model->showForeignKeys($this->table, 'pid')[0];
                    if ($parent) {
                        if ($this->table === $parent['REFERENCED_TABLE_NAME'])
                            $where = 'pid IS NULL OR pid = 0';
                        else {
                            $columns = $this->model->showColumns($parent['REFERENCED_TABLE_NAME']);
                            if ($columns['pid']) $order[] = 'pid';
                            else $order[] = $parent['REFERENCED_COLUMN_NAME'];
                            $id = $this->model->select($parent['REFERENCED_TABLE_NAME'], [
                                'fields' => [$parent['REFERENCED_COLUMN_NAME']],
                                'order' => $order,
//                                'order_direction' => ['DESC'],
                                'limit' => '1',
                            ])[0][$parent['REFERENCED_COLUMN_NAME']];
                            if ($id) $where = ['pid' => $id];
                        }
                    } else $where = 'pid IS NULL OR pid =0';
                }
            }
            $menuPosition = $this->model->select($this->table, [
                    'fields' => ['COUNT(*) as count'],
                    'where' => $where,
                    'no_concat' => true,
                ])[0]['count'] + 1;
            for ($i = 1; $i <= $menuPosition; $i++) {
                $this->foreignData['position'][$i - 1]['id'] = $i;
                $this->foreignData['position'][$i - 1]['name'] = $i;
            }
        }
    }
}
