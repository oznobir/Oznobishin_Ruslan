<?php

namespace core\site\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseControllers;
use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;

/**
 * @uses IndexController
 */
class IndexController extends BaseControllers
{

    /**
     * @throws RouteException|DbException
     */
    protected function inputData(): false|string
    {
        $model = Model::instance();
        $name = $model->select('goods', [
            'fields' => ['id', 'name'],
            'where' => ['id' => '3, 5'],
            'operand' => ['IN'],
            'join' => [
                'color_goods' => [
                    'fields' => ['id as color_goods_id'],
                    'on' => ['id', 'goods_id']
                ],
                'color' => [
                    'fields' => ['id as colorId', 'name as colorName'],
                    'on' => ['color_id', 'id']
                ],
                'manufacturer_goods' => [
//                    'fields' => ['manufacturer_id as manufacturerId'],
                    'on' => ['id', 'goods_id']
                ],
                'manufacturer' => [
                    'fields' => ['name as manufacturerName'],
                    'on' => ['manufacturer_id', 'id']
                ],
            ],
            'join_structure' => true,
        ]);
        $header = $this->render(SITE_TEMPLATE . 'header');
        $footer = $this->render(SITE_TEMPLATE . 'footer');
        return $this->render('', compact('name', 'header', 'footer'));
    }
//    protected function outputData(): false|string
//    {
//
////        return func_get_arg(0);
////        $this->page = $this->render(SITE_TEMPLATE .'layout', func_get_arg(0));
//        return $this->render(SITE_TEMPLATE .'layout', func_get_arg(0));
//    }
}