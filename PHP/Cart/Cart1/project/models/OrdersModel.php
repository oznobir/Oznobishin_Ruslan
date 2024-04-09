<?php

namespace Project\Models;

use Core\Model;
use PDO;

class OrdersModel extends Model
{

    public function getOrdersByUser(): array
    {
        $parameters['userId'] = intval($_SESSION['user']['id']);
        $query = "SELECT * FROM `orders` WHERE `user_id`=:userId ORDER BY id DESC";
        $orders = self::selectAll($query, PDO::FETCH_ASSOC, $parameters);
        $orderWithChildren =[];
        foreach ($orders as $order) {
            $child = $this->getPurchaseFormOrder($order['id']);
            if($child){
                $order['children'] = $child;
                $orderWithChildren [] = $order;
            }
        }
        return $orderWithChildren;
//        return self::selectAll($query, PDO::FETCH_ASSOC, $parameters);
    }
    private function getPurchaseFormOrder($id)
    {
        $parameters['orderId'] = $id;
        $query = "SELECT `pe`.product_id, `pe`.price, `pe`.amount, `ps`.slug FROM `purchase` as `pe` 
                    JOIN `products` as `ps` ON `pe`.product_id= `ps`.id
                      WHERE `pe`.order_id=:orderId";
        return self::selectAll($query, PDO::FETCH_ASSOC, $parameters);
    }
    public function makeNewOrder($userOrder, $phone, $address, $payment, $delivery, $comment)
    {
        if (isset($_SESSION['user']['id'])) $userId = intval($_SESSION['user']['id']);
        else $userId = 1;
        $parameters['userId'] = $userId;
        $parameters['commentOrder'] = "оплата: $payment<br>
доставка: $delivery<br>
имя получателя: $userOrder<br>
тел: $phone<br>
адрес: $address<br>
комментарий: $comment<br>";
        $parameters['dataCreated'] = date('Y.m.d H:i:s');
        $parameters['dataPayment'] = null;
        $parameters['status'] = '0';
        $parameters['userIP'] = $_SERVER['REMOTE_ADDR'];

        $query = "INSERT INTO `orders`(`user_id`, `data_created`, `data_payment`, `status`, `comment`, `user_ip`)
                VALUES (:userId,:dataCreated,:dataPayment,:status,:commentOrder,:userIP)";
        $orderId = self::execId($query, $parameters);
        if ($orderId) return $orderId;
        else return null;
    }
    public function setPurchaseFormOrder($parameters)
    {
        $query = "INSERT INTO `purchase`(`order_id`, `product_id`, `price`, `amount`) VALUES(:orderId,:id,:price,:count)";
        return self::execMulti($query, $parameters);
    }
    /** Проверка введены ли телефон, адрес при оформлении заказа
     *
     * @param string|null $phone телефон получателя
     * @param string|null $address адрес получателя
     * @param string|null $delivery способ доставки
     * @return array|null массив с success (false), message (сообщение) или null если все ОК
     */
    public function checkOrderParam(string|null $phone, string|null $address, string|null $delivery): array|null
    {

        $resultCheck = null;

        if (!$phone) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Введите телефон';
        }
        if (!$address && $delivery == 'courier') {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Введите адрес';
        }
        if (!$phone && (!$address && $delivery == 'courier')) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Введите телефон и адрес';
        }

        return $resultCheck;
    }
}