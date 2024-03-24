<?php

namespace Project\Controllers;

use Core\Controller;


/**
 * Контроллер для работы с корзиной
 *  /cart/id
 */
class CartController extends Controller
{
    public function add(): void
    {
        $itemId = intval($this->parameters['id']);
        if (!$itemId) exit();
        $jsData = [];
        if (isset($_SESSION['cart']) && !in_array($itemId, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $itemId;
            if (count($_SESSION['cart'])) {
                $jsData['countItems'] = count($_SESSION['cart']);
            } else {
                $jsData['countItems'] = 'Пусто';
            }
            $jsData['success'] = 1;
        } else {
            $jsData['success'] = 0;
        }
        echo json_encode($jsData);
    }
    public function remove(): void
    {
        $itemId = intval($this->parameters['id']);
        if (!$itemId) exit();
        $jsData = [];
        $key = array_search($itemId, $_SESSION['cart']);
        if ($key !== false) {
            unset($_SESSION['cart'][$key]);
            if (count($_SESSION['cart'])) {
                $jsData['countItems'] = count($_SESSION['cart']);
            } else {
                $jsData['countItems'] = 'Пусто';
            }
            $jsData['success'] = 1;
        } else {
            $jsData['success'] = 0;
        }
        echo json_encode($jsData);
    }
}