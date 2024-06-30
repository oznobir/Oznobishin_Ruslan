<?php

namespace core\site\models;

use core\base\controllers\Singleton;
use core\base\exceptions\DbException;
use core\base\models\BaseModel;

class Model extends BaseModel
{
    use Singleton;

    /**
     * @throws DbException
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * @param array $set
     * @param $catalogFilters
     * @param $catalogPrice
     * @return array|bool|int|string|null
     * @throws DbException
     */
    public function getGoods(array $set = [], &$catalogFilters = null, &$catalogPrice = null): array|bool|int|string|null
    {
        $columnsGoods = $this->showColumns('goods');
        if (empty($set['join_structure'])) $set['join_structure'] = true;
        if (empty($set['where'])) $set['where'] = [];
        if (empty($set['order'])) {
            $set['order'] = [];
            if (!empty($columnsGoods['pid'])) $set['order'][] = 'pid';
            if (!empty($columnsGoods['price'])) $set['order'][] = 'price';
        }

        $goods = $this->select('goods', $set);
        if ($goods) {
            unset($set['join'], $set['join_structure'], $set['pagination']);

            if ($catalogPrice !== false && !empty($columnsGoods['price'])) {
                $set['fields'] = ['MIN(price) as min_price', 'MAX(price) as max_price'];
                $catalogPrice = $this->select('goods', $set);
                if (!empty($catalogPrice[0])) {
                    $catalogPrice = $catalogPrice[0];
                    $catalogPrice['min_price'] = $_GET['min_price'] ?? floor($catalogPrice['min_price']);
                    $catalogPrice['max_price'] = $_GET['max_price'] ?? ceil($catalogPrice['max_price']);
                }
            }
            // скидки
            if (!empty($columnsGoods['discount'])) {
                foreach ($goods as $item) {
                    $this->applyDiscount($item, $item['discount']);
                }
            }
            if ($catalogFilters !== false && in_array('filters', $this->showTables())) {

                $filtersWhere = [];
                $filtersOrder = [];

                $columnsFilters = $this->showColumns('filters');
                if (!empty($columnsFilters['visible'])) $filtersWhere['visible'] = 1;
                if (!empty($columnsFilters['position'])) $filtersOrder[] = 'position';

                $parentFiltersFields = [];
                foreach ($columnsFilters as $name => $item) {
                    if (!empty($item) && $name !== 'pri') $parentFiltersFields[] = $name . ' as f_' . $name;
                }
                $filters = $this->select('filters', [
                    'where' => $filtersWhere,
                    'join' => [
                        'filters f_name' => [
                            'type' => 'INNER',
                            'fields' => $parentFiltersFields,
                            'where' => $filtersWhere,
                            'order' => $filtersOrder,
                            'on' => ['pid', 'id']
                        ],
                        'filters_goods' => [
                            'on' => [
                                'table' => 'filters',
                                'fields' => ['id', 'filters_id']
                            ],
                            'where' => [
                                'goods_id' => $this->select('goods', [
                                    'fields' => [$columnsGoods['pri'][0]],
                                    'where' => $set['where'],
                                    'operand' => $set['operand'] ?? null,
                                    'return_query' => true
                                ]),
                            ],
                            'operand' => ['IN'],
                        ],
                    ],
                ]);

                if ($filters) {
                    // подсчет товаров в фильтре
                    $filtersIds = array_unique(array_column($filters, 'id'));
                    $goodsIds = array_unique(array_filter(array_column($filters, 'goods_id')));
                    $countArr = $this->select('filters_goods', [
                        'fields' => ['filters_id as id', 'COUNT(goods_id) as count'],
                        'where' => ['filters_id' => $filtersIds, 'goods_id' => $goodsIds],
                        'operand' => ['IN'],
                        'group' => 'filters_id',
                    ]);
                    $goodsCount = [];
                    if ($countArr) {
                        foreach ($countArr as $item) {
                            $goodsCount[$item['id']] = $item;
                        }
                    }
                    $catalogFilters = [];
                    foreach ($filters as $item) {
                        $parent = [];
                        $child = [];
                        foreach ($item as $row => $rowValue) {
                            if (str_starts_with($row, 'f_')) {
                                $name = preg_replace('/^f_/', '', $row);
                                $parent[$name] = $rowValue;
                            } else $child[$row] = $rowValue;
                        }
                        if (isset($goodsCount[$child['id']]['count'])) {
                            $child['count'] = $goodsCount[$child['id']]['count'];
                        }
                        if (empty($catalogFilters[$parent['id']])) {
                            $catalogFilters[$parent['id']] = $parent;
                            $catalogFilters[$parent['id']]['values'] = [];
                        }
                        $catalogFilters[$parent['id']]['values'][$child['id']] = $child;
                        if (isset($goods[$item['goods_id']])) {
                            if (empty($goods[$item['goods_id']]['filters'][$parent['id']])) {
                                $goods[$item['goods_id']]['filters'][$parent['id']] = $parent;
                                $goods[$item['goods_id']]['filters'][$parent['id']]['values'] = [];
                            }
                            $goods[$item['goods_id']]['filters'][$parent['id']]['values'][$item['id']] = $child;
                            uasort($goods[$item['goods_id']]['filters'], function ($a, $b) {
                                return (int)$a['position'] < (int)$b['position'] ? -1 : 1;
                            });
                        }
                    }
                }
            }
        }
        return $goods ?: null;
    }

    /**
     * @param $data
     * @param $discount
     * @return void
     */
    public function applyDiscount(&$data, $discount): void
    {
        if ($discount) {
            $data['old_price'] = $data['price'];
            $data['discount'] = $discount;
            $data['price'] = $data['old_price'] - ($data['old_price'] / 100 * $discount);
        }

    }
}