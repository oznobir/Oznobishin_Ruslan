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
            'methods' => ['emptyField', 'phone375Field'],
        ],
        'email' => [
            'translate' => 'E-mail',
            'count' => '40',
            'methods' => ['emptyField', 'countField', 'emailField'],
        ],
        'address' => [
            'translate' => 'Адрес',
            'count' => '140',
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
        'password' => [
            'translate' => 'Пароль',
            'count' => '140',
            'countMin' => '5',
            'methods' => ['emptyField', 'countMinField', 'countField', 'md5PassField'],
        ],
    ];
    protected array $delivery;
    protected array $payments;

    protected function inputData(): void
    {
        parent::inputData();
        if ($this->isPost()) {
            $this->delivery = $this->model->select('delivery', ['join_structure' => true]);
            $this->payments = $this->model->select('payments', ['join_structure' => true]);
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
//        $this->clearFormFieldsOld($this->validation);
        if (empty($_POST['address']) && intval($_POST['delivery_id']) != 2) unset($_POST['address']);
        else $this->sendAnswer('При доставке "По адресу" заполните поле Адрес', 'error', 'address');
        if (!empty($_POST['password'])) {
            if ($this->userData['id'])
                unset($_POST['password']);
            elseif ($_POST['password'] !== $_POST['confirm_password'])
                $this->sendAnswer('Пароли не совпадают', 'error', 'confirm_password');
        }
        unset($_POST['confirm_password']);
        foreach ($_POST as $key => $item) {
            $_POST[$key] = $item = $this->clearFormFields($this->validation[$key], $item, $key);
            if (!empty($columnsOrders[$key])) $order[$key] = $item;
            if (!empty($columnsVisitors[$key])) $visitor[$key] = $item;
        }
//        $visitorWhere = ['password' => $visitor['password']];
//        $visitorCondition[] = 'AND';
//        if (!empty($visitor['email']) && !empty($visitor['phone'])) {
//            $visitorWhere = ['email' => $visitor['email'], 'phone' => $visitor['phone']];
//            $visitorCondition[] = 'OR';
//        } else {
//            $visitorKey = !empty($visitor['email']) ? 'email' : 'phone';
//            $visitorWhere[$visitorKey] = $visitor[$visitorKey];
//        }
        $visitorWhere = [
            'password' => $visitor['password'],
            'email' => $visitor['email'],
            'phone' => $visitor['phone']];
        $resVisitor = $this->model->select('visitors', [
            'fields' => 'id',
            'where' => $visitorWhere,
//            'conditions' => $visitorCondition,
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
        if (!UsersModel::instance()->checkUser($order['visitor_id']))
            throw new RouteException('Ошибка регистрации пользователя', 3);
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

        if (!($goods = $this->setOrdersGoods($order)))
            throw new RouteException('Ошибка сохранения данных в таблицу orders_goods', 3);
        $order['delivery'] = $this->delivery[$order['delivery_id']]['name'] ?? '';
        $order['payments'] = $this->payments[$order['payments_id']]['name'] ?? '';
        $this->sendAnswer('Ваш заказ на сумму ' . $order['total_sum'] . ' руб. сохранен. Спасибо за заказ!', 'success');
        $this->sendOrderEmail(['order' => $order, 'visitor' => $visitor, 'goods' => $goods]);
        $this->clearCart();

        $this->redirect(PATH);
    }

    /**
     * @param array $order
     * @return array|null
     * @throws DbException
     * @throws RouteException
     */
    protected function setOrdersGoods(array $order): array|null
    {
        $tables = $this->model->showTables();
        if (!in_array('orders_goods', $tables))
            throw new RouteException('Отсутствует таблица orders_goods', 3);
        $ordersGoods = [];
        $preparedGoods = [];
        $colOrdersGoods = $this->model->showColumns('orders_goods');
        foreach ($this->cart['goods'] as $key => $item) {
            $ordersGoods[$key]['orders_id'] = $order['id'];
            $preparedGoods[$key] = $item;
            $preparedGoods[$key]['total_sum'] = $item['qty'] * $item['price'];
            foreach ($item as $field => $value) {
                if (!empty($colOrdersGoods[$field])) {
                    if ($colOrdersGoods['pri'][0] === $field && isset($colOrdersGoods['goods_id'])) {
                        $ordersGoods[$key]['goods_id'] = $value;
                    } else  $ordersGoods[$key][$field] = $value;
                }
            }
        }
        if ($this->model->add('orders_goods', ['fields' => $ordersGoods,])) {
            return $preparedGoods;
        }
        return null;
    }

    /**
     * @param array $orderData
     * @return array
     * @throws DbException|RouteException
     */
    protected function sendOrderEmail(array $orderData): array
    {
        $dir = SITE_TEMPLATE . 'include/orderTemplates/';
        $templatesArr = [];
        if (is_dir($dir)) {
            $list = scandir($dir);
            foreach ($orderData as $name => $item) {
                if (($file = preg_grep('/^' . $name . '\./', $list))) {
                    $file = array_shift($file);
                    $template = file_get_contents($dir . $file);
                    if (!is_numeric(key($item)))
                        $templatesArr[] = $this->renderOrderMailTemplate($template, $item);
                    else {
                        if ($common = preg_grep('/' . $name . 'Header\./', $list)) {
                            $common = array_shift($common);
                            $templatesArr[] = $this->renderOrderMailTemplate(file_get_contents($dir . $common), []);
                        }
                        foreach ($item as $i) {
                            $templatesArr[] = $this->renderOrderMailTemplate($template, $i);
                        }
                        if ($common = preg_grep('/' . $name . 'Footer\./', $list)) {
                            $common = array_shift($common);
                            $templatesArr[] = $this->renderOrderMailTemplate(file_get_contents($dir . $common), []);
                        }

                    }

                }
            }
            $sender = new SendMailController();
            $info = $sender->setMailBody($templatesArr)->send($orderData['visitor']['email']);
            if (!$info) $this->error = $sender->getErrorInfo();
        }
        return $templatesArr;
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    protected function renderOrderMailTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = preg_replace('/#' . $key . '#/i', $value, $template);
        }
        return $template;
    }
}