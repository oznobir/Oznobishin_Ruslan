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
        $this->data = (new ProductsModel())->getProductBySlug($this->parameters);
        $this->data['menu'] = (new CategoriesModel())->getCategoriesWithChild();
        echo $this->render('project/views/default/shopOneProductCenter.php');
    }
}
