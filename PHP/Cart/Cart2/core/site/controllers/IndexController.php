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
    protected array $advantages;
    protected array $news;

    /**
     * @throws RouteException
     * @throws DbException
     */
    protected function inputData(): void
    {
        parent::inputData();
        $this->sales = $this->model->select('sales', [
            'where' => ['visible' => 1],
            'order' => ['position'],
        ]);
        $this->advantages = $this->model->select('advantages', [
            'where' => ['visible' => 1],
            'order' => ['position'],
            'limit' => 6
        ]);
        $this->news = $this->model->select('news', [
            'where' => ['visible' => 1],
            'order' => ['date'],
            'order_direction' => ['DESC'],
            'limit' => 3
        ]);
        foreach ($this->marketing['all'] as $type => $item) {
            $this->marketing['goods'][$type] = $this->model->getGoods([
                'where' => [$type => 1, 'visible' => 1],
                'limit' => 6,
            ]);
        }
    }
}