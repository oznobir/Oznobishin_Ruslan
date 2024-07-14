<?php

namespace core\site\controllers;


use core\base\exceptions\DbException;

class SearchController extends BaseSite
{
    protected ?array $goods;

    protected function inputData(): void
    {
        parent::inputData();
        $search = $this->clearTags($_GET['search'] ?? '');
        $this->data['name'] = 'Поиск товаров по запросу: "<span>' . $search . '</span>"';
        $this->goods = $this->search($search);
        $this->template = SITE_TEMPLATE . 'catalog';

    }

    /**
     * @param string|null $search
     * @return array
     * @throws DbException
     */
    public function search(?string $search): array
    {
        $data = [];
        if ($search) {
//            $query = "SELECT DISTINCT goods.id FROM goods  WHERE
//                                         goods.name LIKE '%qw%'
//                                        OR goods.short_content LIKE '%qw%'
//                                        OR goods.content LIKE '%qw%'
//                                        OR goods.id IN (SELECT  filters_goods.goods_id FROM filters_goods INNER JOIN filters ON filters_goods.filters_id = filters.id AND  filters.filters_name LIKE '%qw%')";
            $filters_query = $this->model->select('filters_goods', [
                'fields' => 'goods_id',
                'join' => [
                    'filters' => [
                        'type' => 'INNER',
                        'on' => ['filters_id', 'id'],
                        'fields' => null,
                        'where' => ['filters_name' => $search],
                        'operand' => ['%LIKE%'],
                    ],
                ],
                'return_query' => true
            ]);
            $goodsIds = $this->model->select('goods', [
                'fields' => 'id',
                'where' => [
                    'visible' => 1,
                    'id' => $filters_query,
                    'name' => $search,
                    'short_content' => $search,
                    'content' => $search
                ],
                'operand' => ['=', 'IN', '%LIKE%'],
                'conditions' => ['AND', 'OR'],
            ], 'DISTINCT');
            $var = false;
            $goodsIds = $goodsIds ? array_column($goodsIds, 'id') : [];
            if ($goodsIds) {
                $data = $this->model->getGoods([
                    'where' => ['visible' => 1, 'id' => $goodsIds],
                    'operand' => ['=', 'IN'],
                    'pagination' => [
                        'qty' => QTY,
                        'page' => $this->num($_GET['page'] ?? 1) ?: 1
                    ],
                ], $var, $var);
                $this->sPagination = $this->model->getPagination();
            }
        }
        return $data;
    }
}