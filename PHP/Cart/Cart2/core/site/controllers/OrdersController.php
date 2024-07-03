<?php

namespace core\site\controllers;


use core\site\helpers\ValidationHelper;

class OrdersController extends BaseSite
{
    use ValidationHelper;
    protected array $delivery;
    protected array $payments;
    protected function inputData(): void
    {
        parent::inputData();
        if($this->isPost()){
            $this->delivery = $this->model->select('delivery');
            $this->payments = $this->model->select('payments');
        }
    }
}