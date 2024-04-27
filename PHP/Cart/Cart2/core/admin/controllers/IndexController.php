<?php

namespace core\admin\controllers;


use core\base\settings\Settings;
use core\plugins\shop\ShopSettings;

class IndexController extends BaseAdmin
{
    /**
     * @return void
     */
    protected function inputData(): void
    {
        $this->inputDataBase();
        $this->createTableData();
        $this->createData(['fields' => ['price', 'gallery_img']]);
        $this->expansion(get_defined_vars(), false);
    }

    protected function outputData(): void
    {

    }

    /**
     * @param array $data
     * @return array
     */
    protected function createData(array $data = []) : array
    {
        $fields = [];
        $order = [];
        $order_direction = [];

        if (!$this->columns['pri']) return $this->$data = [];
        $fields[] = $this->columns['pri'] . ' as id';
        if ($this->columns['name']) $fields['name'] = 'name';
        if ($this->columns['img']) $fields['img'] = 'img';

        if (count($fields) > 3) {
            foreach ($this->columns as $key => $item) {
                if (!$fields['name'] && str_contains($key, 'name')) {
                    $fields['name'] = $key . 'as name';
                }
                if (!$fields['img'] && str_starts_with($key, 'img')) {
                    $fields['img'] = $key . 'as img';
                }
            }
        }
        if (isset($data['fields'])) $fields = Settings::instance()->arrayMergeRecursive($fields, $data['fields']);
        if ($this->columns['pid']) {
            if (!in_array('pid', $fields)) $fields[] = 'pid';
            $order[] = 'pid';
        }
        if ($this->columns['position']) {
            $order[] = 'position';
        } elseif ($this->columns['date']) {
            $order[] = 'date';
            if ($order) $order_direction = ['ASC', 'DESC'];
            else $order_direction[] = 'DESC';
        }
        if (isset($data['order']))
            $order = Settings::instance()->arrayMergeRecursive($order, $data['order']);
        if (isset($data['order_direction']))
            $order_direction = Settings::instance()->arrayMergeRecursive($order_direction, $data['order_direction']);

        $dataInput = $this->model->select($this->table, [
            'fields' => $fields,
            'order' => $order,
            'order_direction' => $order_direction,
        ]);
        return $this->data = $dataInput;
    }
}