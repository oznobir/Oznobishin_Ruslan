<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\CategoriesModel;


class adminController extends Controller
{
    /**
     * @return void
     */
    public function index(): void
    {

        $this->data['categories'] = (new CategoriesModel())->getCategoriesWithChild();
        echo $this->render('project/views/admin/centerView.php');
    }

    /**
     * @return void json success, сообщение
     */
    public function addcategory(): void
    {
        $slug = $_POST['newCategorySlug'];
        $name = $_POST['newCategoryName'];
        $pid = $_POST['mainCategoryId'];

        $info = (new CategoriesModel())->newCategory($slug, $name, $pid);
        if ($info) {
            $jsData['success'] = true;
            $jsData['message'] = 'Категория добавлена';
        } else {
            $jsData['success'] = false;
            $jsData['message'] = 'Ошибка добавления';
        }
        echo json_encode($jsData);
    }
}
