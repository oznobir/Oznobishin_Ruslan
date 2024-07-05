<?php

namespace core\site\controllers;


use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\base\models\UsersModel;
use core\site\helpers\ValidationHelper;

/**
 * @uses OrdersController
 */
class OrdersController extends BaseSite
{
    use ValidationHelper;

    protected array $validation = [
        'name' => [
            'translate' => 'Ваше имя',
            'methods' => ['emptyField'],
        ],
        'phone' => [
            'translate' => 'Телефон',
            'methods' => ['emptyField', 'phoneField', 'numericField'],
        ],
        'email' => [
            'translate' => 'E-mail',
            'methods' => ['emptyField', 'emailField'],
        ],
        'deliveryId' => [
            'translate' => 'Способы доставки',
            'methods' => ['emptyField', 'numericField'],
        ],
        'paymentsId' => [
            'translate' => 'Способы оплаты',
            'methods' => ['emptyField', 'numericField'],
        ],
    ];
    protected array $delivery;
    protected array $payments;

    protected function inputData(): void
    {
        parent::inputData();
        if ($this->isPost()) {
            $this->delivery = $this->model->select('delivery');
            $this->payments = $this->model->select('payments');
            $this->order();
        }

    }

    /**
     * @return void
     * @throws DbException
     * @throws RouteException
     */
    protected function order(): void
    {
        if (empty($this->cart['goods']))
            throw new RouteException('Отсутствуют данные в корзине для оформления заказа', 3);

        $order = [];
        $visitor = [];
        $columnsOrders = $this->model->showColumns('orders');
        $columnsVisitors = $this->model->showColumns('visitors');
        foreach ($_POST as $key => $item) {
            if (!empty($this->validation[$key]['methods'])) {
                foreach ($this->validation[$key]['methods'] as $method) {
                    $_POST[$key] = $item = $this->$method($item, $this->validation[$key]['translate'] ?? $key);
                }
            }
            if (!empty($columnsOrders[$key])) $order[$key] = $item;
            if (!empty($columnsVisitors[$key])) $visitor[$key] = $item;
        }
        if (empty($visitor['email']) && empty($visitor['phone']))
            throw new RouteException('Отсутствуют данные email и phone для оформления заказа', 3);


        $visitorWhere = $visitorCondition = [];
        if (!empty($visitor['email']) && !empty($visitor['phone'])) {
            $visitorWhere = ['email' => $visitor['email'], 'phone' => $visitor['phone']];
            $visitorCondition = ['OR'];
        } else {
            $visitorKey = !empty($visitor['email']) ? 'email' : 'phone';
            $visitorWhere[$visitorKey] = $visitor[$visitorKey];
        }

        $resVisitor = $this->model->select('visitors', [
            'fields' => 'id',
            'where' => $visitorWhere,
            'conditions' => $visitorCondition,
            'limit' => 1
        ]);
        if ($resVisitor) {
            $order['visitor_id'] = $resVisitor[0]['id'];
        } else {
            $order['visitor_id'] = $this->model->add('visitors', [
                'fields' => $visitor,
                'return_id' => true
            ]);
            if (!is_array($order['visitor_id']))
                throw new RouteException('Ошибка добавления в таблицу  visitors', 3);
        }
        $order['total_sum'] = $this->cart['total_sum'];
        $order['total_old_sum'] = $this->cart['total_old_sum'] ?? $this->cart['total_sum'];
        $order['total_qty'] = $this->cart['total_old_sum'];

        $baseStatus = $this->model->select('orders_statuses', [
            'fields' => 'id',
            'order' => 'position',
            'limit' => 1
        ]);
        if (!$baseStatus)
            throw new RouteException('Нет данных из таблицы orders_statuses', 3);

        $order['orders_statuses_id'] = $baseStatus[0]['id'];

        $order['id'] = $this->model->add('orders', [
            'fields' => $order,
            'return_id' => true
        ]);
        if (!$order['id'])
            throw new RouteException('Ошибка сохранения данных в таблицу orders', 3);

        if (!$resVisitor) UsersModel::instance()->checkUser($order['visitor_id']);

        $this->sendAnswer('Ваш заказ на сумму ' . $order['total_sum'] . ' руб. сохранен.' . PHP_EOL . ' Спасибо!', 'success');
        $this->sendOrderEmail(['order' => $order, 'visitor' => $visitor]);
        $this->clearCart();

        $this->redirect(PATH);
    }

    protected function sendOrderEmail(array $orderData)
    {

    }
}