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