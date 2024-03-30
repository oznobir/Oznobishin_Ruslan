<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\CategoriesModel;
use Project\Models\ProductsModel;

class ProductController extends Controller
{
    /**
     * @return void
     */
    public function index(): void
    {

        $this->data = (new ProductsModel())->getProductBySlug(['slug'=>$this->parameters['slug']]);
        if (!$this->data) $this->redirect('/');
        $this->data['idInCart'] = 0;
        if (in_array($this->data['id'], $_SESSION['cart'])) $this->data['idInCart'] = 1;
        $this->data['arrUser'] = $_SESSION['user'] ?? null;
        $this->data['menu'] = (new CategoriesModel())->getCategoriesWithChild();
        echo $this->render('project/views/default/shopOneProductView.php');
    }
}
