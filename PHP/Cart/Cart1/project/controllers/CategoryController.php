<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\CategoriesModel;
use Project\Models\ProductsModel;

class CategoryController extends Controller
{
    /**
     * @return void
     */
    public function index(): void
    {
        $cat =  (new CategoriesModel())->getCategoryBySlug($this->parameters);
        $id = ['id' => $cat['id']];
        $this->data['title'] = "Товары в категории {$cat['title']}";
        $this->data['menu'] =  (new CategoriesModel())->getCategoriesWithChild();
        $this->data['description'] = "Товары в категории {$cat['title']} в myshop.by";
        if ($cat['parent_id'] == 0) {
            $this->data['categories'] = (new CategoriesModel())->geSubCategoriesById($id);
            echo $this->render('project/views/default/shopCategoriesCenter.php');
        } else {
            $this->data['products'] = (new ProductsModel())->getProductsCategoryById($id);
            echo $this->render('project/views/default/shopProductsCenter.php');
        }
    }
}
