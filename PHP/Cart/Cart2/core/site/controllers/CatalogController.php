<?php

namespace core\site\controllers;

use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;

/**
 * @uses CatalogController
 */
class CatalogController extends BaseSite
{
    /**
     * @uses $order
     */

    protected ?array $goods;
    protected array $sOrders = [
        'Цене' => 'price_asc',
        'Названию' => 'name_asc',
    ];
    protected ?array $sFilters;
    protected ?array $sPrices;

    /**
     * @throws DbException
     * @throws RouteException
     */
    protected function inputData(): void
    {
        parent::inputData();
        if (!empty($this->parameters['alias'])) {
            $data = $this->model->select('catalog', [
                'where' => ['alias' => $this->parameters['alias'], 'visible' => 1],
                'limit' => 1
            ]);
            if (!$data)
                throw new RouteException('Нет данных в таблице catalog по ссылке ' . $this->parameters['alias']);
            $this->data = $data[0];
        }
        $where = ['visible' => 1];

        if ($this->data) $where = ['pid' => $this->data['id']];
        else $this->data['name'] = 'Каталог';

        $this->sFilters = $this->sPrices = $order = null;
        $this->createSOrder($order);
        $operand = $this->checkPricesAndFilters($where);
        $this->goods = $this->model->getGoods([
            'where' => $where,
            'operand' => $operand,
            'order' => $order['order'],
            'order_direction' => $order['order_direction'],
        ], $this->sFilters, $this->sPrices);
    }

    /**
     * @param array $where
     * @return array
     * @throws DbException
     */
    protected function checkPricesAndFilters(array &$where): array
    {
        $dbWhere = [];
        $dbOperand = [];
        if (isset($_GET['min_price'])) {
            $dbWhere['price'] = $this->num($_GET['min_price']);
            $dbOperand[] = '>=';
        }
        if (isset($_GET['max_price'])) {
            $dbWhere['price  '] = $this->num($_GET['max_price']);
            $dbOperand[] = '<=';
        }
        if (!empty($_GET['filters'])) {
            $filters = implode(', ', $this->clearTags($_GET['filters']));
            $dbWhere['id'] = $this->model->select('filters_goods', [
                'fields' => ['goods_id'],
                'where' => ['filters_id' => $filters],
                'return_query' => true
            ]);
            $dbOperand[] = 'IN';
        }
        $where = array_merge($dbWhere, $where);
        $dbOperand[] = '=';
        return $dbOperand;
    }

    /**
     * @param $order
     * @return void
     * @throws DbException
     */
    protected function createSOrder(&$order): void
    {
        $order['order'] = $order['order_direction'] = null;
        if (isset($_GET['order'])) {
            $orderArr = preg_split('/_/', $this->clearTags($_GET['order']), 0, PREG_SPLIT_NO_EMPTY);
            if ($this->model->showColumns('goods')[$orderArr[0]]) {
                $order['order'] = $orderArr[0];
                $order['order_direction'] = $orderArr[1] ?? null;
                foreach ($this->sOrders as $key => $item) {
                    if (str_starts_with($item, $orderArr[0])) {
                        $this->sOrders[$key] = $orderArr[0] . '_' . ($order['order_direction'] === 'asc' ? 'desc' : 'asc');
                        break;
                    }
                }
            }
        }
    }
}