<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\OrderModel;
use Project\Models\ProductsModel;
use Project\Models\CategoriesModel;
use Project\Models\PurchaseModel;

/**
 * Контроллер для работы с корзиной
 */
class CartController extends Controller
{
    /** Страница с продуктами в корзине
     *
     * @return void страница с продуктами
     */
    public function index(): void
    {

        if (!empty($_SESSION['cart']))
            $this->data['products'] = (new ProductsModel())->getProductsFromArray($_SESSION['cart']);
        $this->data['menu'] = (new CategoriesModel())->getCategoriesWithChild();
        if (!empty($_SESSION['viewProducts']))
            $this->data['viewProducts'] = (new ProductsModel())->getProductsFromArray($_SESSION['viewProducts']);
        $this->data['title'] = 'Корзина товаров';
        $this->data['description'] = 'Корзина товаров Гипермаркет myshop.by';
        echo $this->render('project/views/default/shopCartView.php');
    }

    /** fetch
     * Добавление в корзину (сессию) id продукта
     * /cart/add/id/itemCount/
     * @return void json - количество в корзине или null
     */
    public function add(): void
    {
        $itemId = intval($this->parameters['id']);
        $itemCount = intval($this->parameters['count']);
        if (!$itemId || !$itemCount) exit();

        $jsData = [];
        $_SESSION['viewProducts'][$itemId] = $itemCount;
        $_SESSION['cart'][$itemId] = $itemCount;
        $jsData['countItems'] = count($_SESSION['cart']);
        $jsData['success'] = true;

        echo json_encode($jsData);
    }

    /** fetch
     * Удаление из корзины (сессии) id продукта
     * /cart/remove/id/
     * @return void json - количество в корзине или null
     */
    public function remove(): void
    {
        $itemId = intval($this->parameters['id']);
        if (!$itemId) exit();
        if (array_key_exists($itemId, $_SESSION['cart'])) {
            unset($_SESSION['cart'][$itemId]);
            $jsData['success'] = true;
        } else {
            $jsData['success'] = false;
        }
        $jsData['countItems'] = count($_SESSION['cart']);
        echo json_encode($jsData);
    }

    /** Формирование страницы заказа
     *
     * @return void страница заказа
     */
    public function order(): void
    {
        $userOrder = isset($_POST['userOrder']) ? htmlspecialchars($_POST['userOrder'], ENT_QUOTES, 'UTF-8') : null;
        $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8') : null;
        $address = isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8') : null;
        $payment = isset($_POST['payment']) ? htmlspecialchars($_POST['payment'], ENT_QUOTES, 'UTF-8') : null;
        $delivery = isset($_POST['delivery']) ? htmlspecialchars($_POST['delivery'], ENT_QUOTES, 'UTF-8') : null;
        $comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8') : null;

        if (!$payment || !$delivery) $this->redirect('/cart/');
        foreach ($_SESSION['cart'] as $id => $count) {
            $sessionCart[intval($id)] = intval($count); // может излишне
        }
        if ($sessionCart != $_POST['products']) $this->redirect('/cart/');

        $info = (new OrderModel())->checkOrderParam($phone, $address, $delivery);
        if ($info) {
            echo json_encode($info);
            exit();
        }
        $arrProducts = (new ProductsModel())->getProductsFromArray($sessionCart);
        if (!$arrProducts) {
            $info['success'] = false;
            $info['message'] = 'В ворзине нет товаров';
            echo json_encode($info);
            exit();
        }
        $idOrder = (new OrderModel())->makeNewOrder($userOrder, $phone, $address, $payment, $delivery, $comment);
        if (!$idOrder) {
            $info['success'] = false;
            $info['message'] = 'Ошибка создания заказа';
            echo json_encode($info);
            exit();
        }
        $totalOrder = 0;
        foreach ($arrProducts as &$item) {
            unset($item['description']);
            unset($item['image']);
            unset($item['title']);
            unset($item['category_id']);
            unset($item['slug']);
            unset($item['status']);
            $item['orderId'] = $idOrder;
            $item['count'] = ($sessionCart[$item['id']]);
            $totalOrder += round($item['price'] * $item['count'], 2);
        }
        $result = (new PurchaseModel())->setPurchaseFormOrder($arrProducts);
        if ($result) {
            $info['success'] = true;
            $info['message'] = "Заказ на сумму $totalOrder руб. сохранен";
            echo json_encode($info);
        } else {
        $info['success'] = false;
        $info['message'] = 'Ошибка создания заказа';
        echo json_encode($info);
        }
    }
}