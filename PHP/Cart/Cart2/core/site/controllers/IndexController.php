<?php

namespace core\site\controllers;

/**
 * @uses IndexController
 */
class IndexController extends BaseSite
{
    protected array $sales;
    protected function inputData(): void
    {
        parent::inputData();
        $this->sales = $this->model->select('sales', [
            'where' => ['visible' => 1],
            'order' => ['position']
        ]);

    }
}