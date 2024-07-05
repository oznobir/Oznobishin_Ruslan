<?php

namespace core\site\controllers;

use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;

/**
 * @uses ProductController
 */
class ProductController extends BaseSite
{
    protected array $delivery;
    /**
     * @throws DbException
     * @throws RouteException
     */
    protected function inputData(): void
    {
        parent::inputData();
        if (empty($this->parameters['alias']))
            throw new RouteException('Отсутствует ссылка на товар', 3);
        $data = $this->model->getGoods([
            'where' => ['alias' => $this->parameters['alias'], 'visible' => 1]
        ]);
        if (!$data)
            throw new RouteException('Отсутствует товар по ссылке ' . $this->parameters['alias'], 3);
        else $this->data = array_shift($data);
        $delivery = $this->model->select('information', [
            'where' => [
                'visible' => 1,
                'name.0' => 'доставка',
                'name.1' => 'оплата',
            ],
            'operand' => ['=', '%LIKE%'],
            'conditions' => ['AND', 'OR'],
            'limit' => 1
        ]);
        if($delivery) $this->delivery = $delivery[0];
    }
}