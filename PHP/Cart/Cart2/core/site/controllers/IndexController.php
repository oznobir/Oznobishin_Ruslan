<?php

namespace core\site\controllers;

use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;

/**
 * @uses IndexController
 */
class IndexController extends BaseSite
{
    protected array $sales;
    protected array $marketing;


    /**
     * @throws RouteException
     * @throws DbException
     */
    protected function inputData(): void
    {
        parent::inputData();
        $this->sales = $this->model->select('sales', [
            'where' => ['visible' => 1],
            'order' => ['position']
        ]);
        $this->marketing['all'] = Settings::get('marketing');
        foreach ($this->marketing['all'] as $type => $item) {
            $this->marketing['goods'][$type] = $this->model->getGoods([
                'where' => [$type => 1, 'visible' => 1],
                'limit' => 6,
            ]);
        }
    }
}