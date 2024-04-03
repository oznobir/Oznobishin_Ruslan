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
    public
    function remove(): void
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
    public
    function order(): void
    {
        var_dump($_POST);
        var_dump($_SESSION);
    }
}