<?php

namespace core\admin\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseControllers;


class IndexController extends BaseControllers
{

    protected function inputData()
    {
        $db = Model::instance();
        //ins sel upd del
        $table = 'teaches';
        $color = ['white', 'red', 'black'];
        $res = $db->sel($table, [
            'fields' => ['id', 'name'],
            'where' => [ 'name' => 'Petr', 'surname' => 'Petrovich', 'friend' => 'Andrey', 'car' => 'audi', 'color' => $color],
            'operand' => ['IN', 'LIKE%', '<>', '=', 'NOT IN'],
            'condition' => ['AND', "OR"],
            'order' => ['fio','name'],
            'order_direction' => ['ASC', 'DESC'],
            'limit' => '1',
        ]);
    }
}