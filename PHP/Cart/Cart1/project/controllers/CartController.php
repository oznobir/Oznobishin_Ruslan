<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\ProductsModel;
use Project\Models\CategoriesModel;

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
        $this->data['title'] = 'Корзина товаров';
        $this->data['description'] = 'Корзина товаров Гипермаркет myshop.by';
        echo $this->render('project/views/default/shopCartView.php');
    }

    /** fetch
     * Добавление в корзину (сессию) id продукта
     * /cart/add/id/
     * @return void json - success, количество в корзине или "Пусто"
     */
    public function add(): void
    {
        $itemId = intval($this->parameters['id']);
        if (!$itemId) exit();
        $jsData = [];

        if (!in_array($itemId, $_SESSION['cart'])) {
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
    /** fetch
     * Удаление из корзины (сессии) id продукта
     * /cart/remove/id/
     * @return void json - success, количество в корзине или "Пусто"
     */
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