<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\CategoriesModel;
use Project\Models\ProductsModel;

class IndexController extends Controller
{
    /**
     * @return void
     */
    public function index(): void
    {
        $this->data['title'] = 'Каталог товаров';
        $this->data['description'] = 'Гипермаркет myshop.by Каталог товаров';
        $this->data['arrUser'] = $_SESSION['user'] ?? null;
        $this->data['menu'] =  (new CategoriesModel())->getCategoriesWithChild();
        if (!empty($_SESSION['viewProducts']))
            $this->data['viewProducts'] = (new ProductsModel())->getProductsFromArray($_SESSION['viewProducts']);
        $this->data['products'] =  (new ProductsModel())->getProductsLast();
        echo $this->render('project/views/default/shopProductsView.php');
    }
    public function d(): void
    {
        echo '$_SESSION:';
        var_dump($_SESSION);
        var_dump(count($_SESSION['cart']));
    }
}
