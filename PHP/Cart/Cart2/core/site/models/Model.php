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
        if (empty($set['join_structure'])) $set['join_structure'] = true;
        if (empty($set['where'])) $set['where'] = [];
        if (empty($set['order'])) {
            $set['order'] = [];
            if (!empty($this->showColumns('goods')['pid'])) $set['order'][] = 'pid';
            if (!empty($this->showColumns('goods')['price'])) $set['order'][] = 'price';
        }

        $goods = $this->select('goods', $set);
        if ($goods) {
            unset($set['join'], $set['join_structure'], $set['pagination']);
            if ($catalogPrice !== false && !empty($this->showColumns('goods')['price'])) {
                $set['fields'] = ['MIN(price) as min_price', 'MAX(price) as max_price'];
                $catalogPrice = $this->select('goods', $set);
                if (!empty($catalogPrice[0])) $catalogPrice = $catalogPrice[0];
            }
            if ($catalogFilters !== false && in_array('filters', $this->showTables())) {
                $parentFiltersFields = [];
                $filtersWhere = [];
                $filtersOrder = [];
                foreach ($this->showColumns('filters') as $name => $item) {
                    if (!empty($item) && $name !== 'pri') $parentFiltersFields[] = $name . ' as f_' . $name;
                }
                if (!empty($this->showColumns('filters')['visible'])) $filtersWhere['visible'] = 1;
                if (!empty($this->showColumns('filters')['position'])) $filtersOrder[] = 'position';

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
                                    'fields' => [$this->showColumns('goods')['pri'][0]],
                                    'where' => $set['where'],
                                    'return_query' => true
                                ]),
                            ],
                            'operand' => ['IN'],
                        ],
                    ],
                ]);
                // скидки
                if (!empty($this->showColumns('goods')['discount'])) {
                    foreach ($goods as $key => $item) {
                        $this->applyDiscount($goods[$key], $item['discount']);
                    }
                }
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
                                $name = preg_replace('/^f_/','' , $row);
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
                        if(isset($goods[$item['goods_id']])){
                            if(empty($goods[$item['goods_id']]['filters'][$parent['id']])){
                                $goods[$item['goods_id']]['filters'][$parent['id']] = $parent;
                                $goods[$item['goods_id']]['filters'][$parent['id']]['values'] = [];
                            }
                            $goods[$item['goods_id']]['filters'][$parent['id']]['values'][$item['id']] = $child;
                        }
                    }
                }
            }
        }
        return  $goods ?: null;
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