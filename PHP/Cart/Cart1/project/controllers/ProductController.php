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
        if (!array_key_exists($this->data['id'], $_SESSION['viewProducts']))
            $_SESSION['viewProducts'][$this->data['id']]= 1;

        $this->data['idInCart'] = false;
        if (array_key_exists($this->data['id'], $_SESSION['cart'])) $this->data['idInCart'] = true;
        $this->data['arrUser'] = $_SESSION['user'] ?? null;
        $this->data['menu'] = (new CategoriesModel())->getCategoriesWithChild();
        echo $this->render('project/views/default/shopOneProductView.php');
    }
}
