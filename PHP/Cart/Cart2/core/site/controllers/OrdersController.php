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
            'countMin' => '2',
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
        $resError = [];
        $columnsOrders = $this->model->showColumns('orders');
        $columnsVisitors = $this->model->showColumns('visitors');
        if (empty($_POST['address']) && $_POST['delivery_id'] == '2')
            $resError['address'] = $this->sendAnswer('При доставке "По адресу" заполните поле Адрес');
        if (!empty($_POST['password'])) {
            if ($this->userData['id'])
                unset($_POST['password']);
            elseif ($_POST['password'] !== $_POST['confirm_password'])
                $resError['confirm_password'] = $this->sendAnswer('Пароли не совпадают');
        }
        unset($_POST['confirm_password']);
        foreach ($_POST as $key => $item) {
            if (empty($resError[$key])) {
                $_POST[$key] = $item = $this->clearFormFields($this->validation[$key], $item, $key, $resError);
            }
            if (!empty($columnsOrders[$key])) $order[$key] = $item;
            if (!empty($columnsVisitors[$key])) $visitor[$key] = $item;
        }
        if (!empty($resError)) {
            $_SESSION['res']['answerForm'] = $resError;
            $this->addSessionData();
        }
        if (!$this->userData['id']) {
//            $query = "SELECT visitors.id
//                      FROM visitors
//                      WHERE visitors.password = '202cb962ac59075b964b07152d234b70'
//                        AND visitors.email = 'q@ii.ru'
//                        AND visitors.phone = '375331523231'
//                      LIMIT 1";
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
//                $query = "INSERT INTO visitors (name, phone, email, password)
//                            VALUES ('user', '375336012455', 'qw@rttt.rt', '202cb962ac59075b964b07152d234b70')";
                $order['visitor_id'] = $this->model->add('visitors', [
                    'fields' => $visitor,
                    'return_id' => true
                ]);
                if (!$order['visitor_id'])
                    throw new RouteException('Ошибка добавления в таблицу  visitors', 3);
            }
            if (!UsersModel::instance()->checkUser($order['visitor_id']))
                throw new RouteException('Ошибка регистрации пользователя', 3);
        } else  $order['visitor_id'] = $this->userData['id'];

        $order['total_sum'] = $this->cart['total_sum'];
        $order['total_old_sum'] = $this->cart['total_old_sum'] ?? $this->cart['total_sum'];
        $order['total_qty'] = $this->cart['total_qty'];
//        $query = "SELECT orders_statuses.id
//                    FROM orders_statuses
//                    ORDER BY orders_statuses.position ASC
//                    LIMIT 1";
//        // $order['orders_statuses_id'] = 2;
        $baseStatus = $this->model->select('orders_statuses', [
            'fields' => 'id',
            'order' => 'position',
            'limit' => 1
        ]);
        if (!$baseStatus)
            throw new RouteException('Нет данных из таблицы orders_statuses', 3);

        $order['orders_statuses_id'] = $baseStatus[0]['id'];
//        $query = "INSERT INTO orders (payments_id, delivery_id, phone, visitor_id, total_sum, total_old_sum, total_qty, orders_statuses_id)
//                    VALUES ('1', '1', '375336012455', '12', '2250', '2500', '1', '2')";
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
        $_SESSION['res']['answer'] = $this->sendAnswer('Ваш заказ на сумму ' . $order['total_sum'] . ' руб. сохранен. Спасибо за заказ!', 'success');
        if(!empty($visitor['email']))$this->sendOrderEmail(['order' => $order, 'visitor' => $visitor, 'goods' => $goods]);
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
//        $query = "INSERT INTO orders (payments_id, delivery_id, phone, visitor_id,
//                             total_sum, total_old_sum, total_qty, orders_statuses_id)
//                    VALUES ('2', '2', '375336031530', '4', '4908.5', '5430', '3', '2')";
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