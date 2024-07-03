<?php

namespace core\site\controllers;

/** @uses CartController */
class CartController extends BaseSite
{
    protected array $delivery;
    protected array $payments;

    protected function inputData(): void
    {
        parent::inputData();
        $this->userData = [
            'name' => 'user',
            'phone' => 12345678,
            'email' => 'user@test.by'
        ];
        $this->delivery = $this->model->select('delivery');
        $this->payments = $this->model->select('payments');
        if (!empty($this->parameters['alias']) && $this->parameters['alias'] === 'remove') {
            if (!empty($this->parameters['id']))
                $this->deleteCartData($this->parameters['id']);
            else $this->clearCart();

            $this->redirect($this->getUrl('cart'));
        }
    }
}