<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\CategoriesModel;

class IndexController extends Controller
{
    /**
     * @return void
     */
    public function index(): void
    {
//        $this->data['page'] =  (new PageModel())->getDataPage($this->parameters);
//        if (!$this->data['page']) $this->redirect('/menu/');
        $this->data['menu'] =  (new CategoriesModel())->getDataWithChild();
        $this->data['content'] = $this->render('project/views/default/shopView.php');
        $this->data['title'] = 'myshop.by Каталог товаров';
        $this->data['description'] = 'Гипермаркет myshop.by Каталог товаров';
        echo $this->render('project/views/default/shopLayout.php');
    }
}
