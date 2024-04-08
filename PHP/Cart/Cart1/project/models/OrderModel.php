<?php

namespace Project\Models;

use Core\Model;

class OrderModel extends Model
{
    public function makeNewOrder($userOrder, $phone, $address, $payment, $delivery, $comment)
    {
        $parameters['userId'] = intval($_SESSION['user']['id']);
        $parameters['commentOrder'] = "id пользователя: {$parameters['userId']}<br>
оплата: $payment<br>
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