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
    protected array $sQuantities = [6, 9, 12];
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
            $subQuery =  $this->setFilters();
            if($subQuery) {
                $dbWhere['id'] = $subQuery;
                $dbOperand[] = 'IN';
            }
//            foreach ($_GET['filters'] as $key => $item)
//                $_GET['filters'][$key] = $this->num($item);
//            $filters = implode(', ', $_GET['filters']);
//            $dbWhere['id'] = $this->model->select('filters_goods', [
//                'fields' => ['goods_id'],
//                'where' => ['filters_id' => $filters],
//                'return_query' => true
//            ]);
//            $dbOperand[] = 'IN';
        }
        $where = array_merge($dbWhere, $where);
        $dbOperand[] = '=';
        return $dbOperand;
    }

    /**
     * @throws DbException
     */
    private function setFilters(): string
    {
        foreach ($_GET['filters'] as $key => $item) {
            if (!$_GET['filters'][$key]) {
                unset($_GET['filters'][$key]);
                continue;
            }
            $_GET['filters'][$key] = $this->num($item);
        }
        $queryFil = 'SELECT DISTINCT pid FROM filters WHERE id IN(' . implode(', ', $_GET['filters']) . ')';
        $res = $this->model->select('filters', [
            'fields' => ['id'],
            'where' => ['id' => $queryFil],
            'operand' => ['IN'],
            'join' => [
                'filters f_val' => [
                    'fields' => ['id'],
                    'where' => ['id' => $_GET['filters']],
                    'operand' => ['IN'],
                    'on' => ['id', 'pid'],
                ],
            ],
            'join_structure' => true,
        ]);
        if ($res) {
            $arr = [];
            $i = 0;
            foreach ($res as $item) {
                if (isset($item['join']['f_val']))
                    $arr[$i++] = array_column($item['join']['f_val'], 'id');
            }
            $resArr = $this->crossDiffArr($arr);
            if ($resArr) {
                $filtersCount = 0;
                foreach ($resArr as $item) {
                    if ($filtersCount < count($item)) $filtersCount = count($item);
                }
                return $this->model->select('filters_goods', [
                    'fields' => 'goods_id',
                    'where' => ['filters_id' => $resArr],
                    'operand' => ['IN'],
                    'conditions' => ['OR'],
                    'group' => 'goods_id',
                    'having' => ['COUNT(goods_id)', '>=', $filtersCount],
                    'return_query' => true
                ]);
            }

        }
        return '';
    }

    /**
     * @param array $arr
     * @param int $counter
     * @return array
     */
    private function crossDiffArr(array $arr, int $counter = 0): array
    {
        if (count($arr) === 1) return array_chunk(array_shift($arr), 1);
        if ($counter === count($arr) - 1) return $arr[$counter];
        $buffer = $this->crossDiffArr($arr, $counter + 1);
        $res = [];
        foreach ($arr[$counter] as $a) {
            foreach ($buffer as $b) {
                $res[] = is_array($b) ? array_merge([$a], $b) : [$a, $b];
            }
        }
        return $res;
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