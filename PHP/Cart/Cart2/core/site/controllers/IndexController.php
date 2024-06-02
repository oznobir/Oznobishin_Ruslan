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
//            'fields' => ['id', 'name'],
            'where' => ['id' => '23, 24, 25'],
            'operand' => ['IN'],
            'join' => [
                'color_goods' => [
                    'fields' => null,
                    'on' => ['id', 'goods_id']
                ],
                'color' => [
//                    'fields' => ['id as colorId', 'name as colorName'],
                    'on' => ['color_id', 'id']
                ],
                'manufacturer_goods' => [
                    'fields' => null,
                    'on' => [
                        'table' => 'goods',
                        'fields' => ['id', 'goods_id']]
                ],
                'manufacturer' => [
//                    'fields' => ['name as manufacturerName'],
                    'on' => ['manufacturer_id', 'id']
                ],
                'filters_goods' => [
                    'fields' => null,
                    'on' => [
                        'table' => 'goods',
                        'fields' => ['id', 'goods_id']]
                ],
                'filters fil' => [
                    'fields' => ['filters_name as filtersName'],
                    'on' => ['filters_id', 'id']
                ],
                'filters' => [
                    'fields' => ['filters_name as filtersNamePID'],
                    'on' => ['pid', 'id']
                ],
            ],
            'join_structure' => true,
            'order' => 'id',
            'order_direction' => 'ASC'
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