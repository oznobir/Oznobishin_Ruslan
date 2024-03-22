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
        $this->data['menu'] =  (new CategoriesModel())->getCategoriesWithChild();
        $this->data['products'] =  (new ProductsModel())->getProductsLast();
        echo $this->render('project/views/default/shopProductsCenter.php');
    }
}
