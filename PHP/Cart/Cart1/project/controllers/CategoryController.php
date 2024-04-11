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
        $cat =  (new CategoriesModel())->getCategoryBySlug(['slug'=>$this->parameters['slug']]);
        if (!$cat) $this->redirect('/');
        $id = ['id' => $cat['id']];
        $this->data['title'] = "Товары в категории {$cat['title']}";
        $this->data['description'] = "Товары в категории {$cat['title']} в myshop.by";
        $this->data['menu'] =  (new CategoriesModel())->getCategoriesWithChild();
        if (!empty($_SESSION['viewProducts']))
            $this->data['viewProducts'] = (new ProductsModel())->getProductsFromArray($_SESSION['viewProducts']);
        if ($cat['parent_id'] == 0) {
            $this->data['categories'] = (new CategoriesModel())->getSubCategoriesById($id);
            echo $this->render('project/views/shopDefault/categoriesView.php');
        } else {
            $this->data['products'] = (new ProductsModel())->getProductsCategoryById($id);
            echo $this->render('project/views/shopDefault/productsView.php');
        }
    }
}
