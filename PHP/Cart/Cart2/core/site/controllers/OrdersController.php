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
            'count' => '40',
            'methods' => ['emptyField', 'countField', 'stringField'],
        ],
        'phone' => [
            'translate' => 'Телефон',
            'count' => '20',
            'methods' => ['emptyField', 'phoneField'],
        ],
        'email' => [
            'translate' => 'E-mail',
            'count' => '40',
            'methods' => ['emptyField', 'countField', 'emailField'],
        ],
        'address' => [
            'translate' => 'Адрес',
            'count' => '40',
            'methods' => ['countField', 'stringField'],
        ],
        'delivery_id' => [
            'translate' => 'Способы доставки',
            'count' => '2',
            'methods' => ['emptyField', 'countField', 'numericField'],
        ],
        'payments_id' => [
            'translate' => 'Способы оплаты',
            'count' => '2',
            'methods' => ['emptyField', 'countField', 'numericField'],
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
//        $this->clearFormFields($this->validation);
        foreach ($_POST as $key => $item) {
            if (!empty($this->validation[$key]['methods'])) {
                foreach ($this->validation[$key]['methods'] as $method) {
                    $arr['count'] = $this->validation[$key]['count'] ?? 140;
                    $arr['translate'] = $this->validation[$key]['translate'] ?? $this->clearTags($key);
                    $_POST[$key] = $item = $this->$method($item, $arr);
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
            if (!$order['visitor_id'])
                throw new RouteException('Ошибка добавления в таблицу  visitors', 3);
        }
        $order['total_sum'] = $this->cart['total_sum'];
        $order['total_old_sum'] = $this->cart['total_old_sum'] ?? $this->cart['total_sum'];
        $order['total_qty'] = $this->cart['total_qty'];

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
        if (!$this->setOrdersGoods($order))
            throw new RouteException('Ошибка сохранения данных в таблицу orders_goods', 3);

        $this->sendAnswer('Ваш заказ на сумму ' . $order['total_sum'] . ' руб. сохранен. Спасибо за заказ!', 'success');
        $this->sendOrderEmail(['order' => $order, 'visitor' => $visitor]);
        $this->clearCart();

        $this->redirect(PATH);
    }

    /**
     * @param array $order
     * @return string|int|bool|array
     * @throws DbException
     * @throws RouteException
     */
    protected function setOrdersGoods(array $order): string|int|bool|array
    {
        $tables = $this->model->showTables();
        if (!in_array('orders_goods', $tables))
            throw new RouteException('Отсутствует таблица orders_goods', 3);
        $ordersGoods = [];
        $colOrdersGoods = $this->model->showColumns('orders_goods');
        foreach ($this->cart['goods'] as $key => $item) {
            $ordersGoods[$key]['orders_id'] = $order['id'];
            foreach ($item as $field => $value) {
                if (!empty($colOrdersGoods[$field])) {
                    if ($colOrdersGoods['pri'][0] === $field && isset($colOrdersGoods['goods_id'])) {
                        $ordersGoods[$key]['goods_id'] = $value;
                    } else  $ordersGoods[$key][$field] = $value;
                }
            }
        }
        return
            $this->model->add('orders_goods', [
                'fields' => $ordersGoods,
            ]);
    }

    protected function sendOrderEmail(array $orderData)
    {

    }
}