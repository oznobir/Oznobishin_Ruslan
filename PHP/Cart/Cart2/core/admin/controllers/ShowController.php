<?php

namespace core\admin\controllers;


use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;

/**
 * @uses ShowController
 */

class ShowController extends BaseAdmin
{
    /**
     * @return void
     * @throws DbException|RouteException
     */
    protected function inputData(): void
    {
        if (!$this->userData['id']) $this->exec();
        $this->createTableData();
        $this->createData();
        $this->template = ADMIN_TEMPLATE . 'show';
        $this->expansionBase();
    }

    /**
     * @param array $data
     * @return array
     * @throws DbException
     */
    protected function createData(array $data = []): array
    {
        $fields = [];
        $order = [];
        $order_direction = [];

        if (!$this->columns['pri'][0]) return $this->data = [];
        $fields[] = $this->columns['pri'][0] . ' as id';
        if (isset($this->columns['name'])) $fields['name'] = 'name';
        if (isset($this->columns['img'])) $fields['img'] = 'img';

        if (count($fields) < 3) {
            foreach ($this->columns as $key => $item) {
                if (!isset($fields['name']) && str_contains($key, 'name')) {
                    $fields['name'] = $key . ' as name';
                }
                if (!isset($fields['img']) && str_starts_with($key, 'img')) {
                    $fields['img'] = $key . ' as img';
                }
            }
        }
        if (isset($data['fields'])) $fields = Settings::instance()->arrayMergeRecursive($fields, $data['fields']);
        if (isset($this->columns['pid'])) {
            if (!in_array('pid', $fields)) $fields[] = 'pid';
            $order[] = 'pid';
        }
        if (isset($this->columns['position'])) {
            $order[] = 'position';
        } elseif (isset($this->columns['date'])) {
            $order[] = 'date';
            if ($order) $order_direction = ['ASC', 'DESC'];
            else $order_direction[] = 'DESC';
        }
        if (isset($data['order']))
            $order = Settings::instance()->arrayMergeRecursive($order, $data['order']);

        if (isset($data['order_direction']))
            $order_direction = Settings::instance()->arrayMergeRecursive($order_direction, $data['order_direction']);

        $res = $this->model->select($this->table, [
            'fields' => $fields,
            'order' => $order,
            'order_direction' => $order_direction,
        ]);
        return $this->data = $res ?: [];
    }
}