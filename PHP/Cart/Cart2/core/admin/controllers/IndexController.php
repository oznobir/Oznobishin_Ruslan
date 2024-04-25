<?php

namespace core\admin\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseControllers;
use core\base\exceptions\DbException;


class IndexController extends BaseControllers
{

    /**
     * @throws DbException
     */
    protected function inputData(): void
    {
        $db = Model::instance();
        $table = 'articles';
        $res5 = $db->deletee($table);
//        $files['gallery_img'] = [];
//        $files['img'] = "";
//        $_POST['id'] = '6';
//        $_POST['name'] = '';
//        $_POST['content'] = "<p>New's 5</p>";
//        $res4 = $db->update($table);
//        $res4 = $db->update($table,[
//            'fields' => [
//                'id' => 2,
//                'name' => 'name2',

//                'content' => 'west',
//            ],
//            'where' => ['id' => 1],
//                'except' => ['name'],
//                'files' => $files,
//            ]);
//        exit();
//        $res3 = $db->showColumns($table);

//        $files['gallery_img'] = ["blue_s1111.png", 'red_234.png', 'black_345.png'];
//        $_POST['name'] = 'name6';
//        $files['img'] = "ups5.png";
//        $res2 = $db->insert($table, [
//            'fields' => [
//                'name' => 'name6',
//                'content' => 'west',
//            ],
//            'except' => ['name'],
//            'files' => $files,
//        ]);
//        exit();
//        $res1 = $db->select($table, [
//            'fields' => ['id', 'name'],
//            'where' => ['name' => "Petr"],
//            'operand' => ['IN', '<>'],
//            'condition' => ['AND', "OR"],
//            'order' => ['name'],
//            'order_direction' => ['DESC'],
//            'limit' => '1',
//            'join' => [
//                'join_table1' => [
//                    'table' => 'join_table1',
//                    'fields' => ['id as j_id', 'name as j_name'],
//                    'type' => 'left',
//                    'where' => ['name' => 'sasha'],
//                    'operand' => ['='],
//                    'conditions' => ['OR'],
//                    'group_condition' => ['AND'],
//                    'on' => [
//                        'table' => 'teachers',
//                        'fields' => ['id', 'parent_id'],
//                    ],
//                ],
//                'join_table2' => [
//                    'table' => 'join_table2',
//                    'fields' => ['id as j2_id', 'name as j2_name'],
//                    'type' => 'left',
//                    'where' => ['name' => 'sasha'],
//                    'operand' => ['<>'],
//                    'conditions' => ['AND'],
//                    'on' => ['id', 'parent_id'],
//                ],
//            ]
//        ]);
    }
}