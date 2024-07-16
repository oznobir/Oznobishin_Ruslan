<?php

namespace core\site\controllers;
use core\base\exceptions\RouteException;

/**
 * @uses LkController
 */
class LkController extends BaseSite
{
    protected function inputData(): void
    {
        parent::inputData();
        if (!$this->userData['id'])
            throw new RouteException('Попытка входа без данных пользователя', 3);
        $this->styles[] = PATH . SITE_TEMPLATE . 'assets/css/lk.css';
//        $query = "SELECT
//                    orders.*,
//                    orders_goods.id AS JT_orders_goods_JF_id,
//                    orders_goods.goods_id AS JT_orders_goods_JF_goods_id,
//                    orders_goods.qty AS JT_orders_goods_JF_qty,
//                    orders_goods.price AS JT_orders_goods_JF_price,
//                    orders_goods.discount AS JT_orders_goods_JF_discount,
//                    orders_goods.orders_id AS JT_orders_goods_JF_orders_id,
//                    goods.name AS JT_goods_JF_name,
//                    goods.id AS JT_goods_JF_id,
//                    payments.name AS JT_payments_JF_name,
//                    payments.id AS JT_payments_JF_id,
//                    delivery.name AS JT_delivery_JF_name,
//                    delivery.id AS JT_delivery_JF_id,
//                    orders_statuses.name AS JT_orders_statuses_JF_name,
//                    orders_statuses.id AS JT_orders_statuses_JF_id
//                FROM orders
//                    LEFT JOIN orders_goods ON orders.id = orders_goods.orders_id
//                    LEFT JOIN goods ON orders_goods.goods_id = goods.id
//                    LEFT JOIN payments ON orders.payments_id = payments.id
//                    LEFT JOIN delivery ON orders.delivery_id = delivery.id
//                    LEFT JOIN orders_statuses ON orders.orders_statuses_id = orders_statuses.id
//                WHERE orders.visitor_id = '2'
//                ORDER BY orders.date DESC ";
        $this->data['all_orders'] = $this->model->select('orders', [
            'where' => ['visitor_id' => $this->userData['id']],
            'order' => ['date'],
            'order_direction' => ['DESC'],
            'join' => [
                'orders_goods' => [
                    'on' => ['id', 'orders_id']],
                'goods' => [
                    'on' => ['table' => 'orders_goods', 'fields' => ['goods_id', 'id']],
                    'fields' => ['name']
                ],
                'payments' => [
                    'on' => ['table' => 'orders', 'fields' => ['payments_id', 'id']],
                    'fields' => ['name']
                ],
                'delivery' => [
                    'on' => ['table' => 'orders', 'fields' => ['delivery_id', 'id']],
                    'fields' => ['name']
                ],
                'orders_statuses' => [
                    'on' => ['table' => 'orders', 'fields' => ['orders_statuses_id', 'id']],
                    'fields' => ['name']
                ],
            ],
            'join_structure' => true,
        ]);
        if (!empty($this->data['all_orders'])) {
            if (!empty($this->parameters['id']) && !empty($this->data['all_orders'][$this->parameters['id']])) {
                $this->data['current_order'] = $this->data['all_orders'][$this->parameters['id']];
            }
        }
    }
}