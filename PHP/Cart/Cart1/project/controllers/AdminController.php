<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\CategoriesModel;


class AdminController extends Controller
{
    /**
     * @return void
     */
    public function index(): void
    {
        $categories = new CategoriesModel();
        $this->data['categories'] = $categories->getCategoriesWithChild();
        $this->data['allCategories'] = $categories->getCategoriesAll();
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

        $info = (new CategoriesModel())->newCategoryData($slug, $name, $pid);
        if ($info) {
            $jsData['success'] = true;
            $jsData['message'] = 'Категория добавлена';
        } else {
            $jsData['success'] = false;
            $jsData['message'] = 'Ошибка добавления';
        }
        echo json_encode($jsData);
    }
    /**
     * @return void json success, сообщение
     */
    public function updatecategory(): void
    {
        $id = $_POST['id'];
        $slug = $_POST['slug'];
        $name = $_POST['title'];
        $pid = $_POST['parentId'];
        $info = (new CategoriesModel())->updateCategoryData(intval($id), $slug, $name, $pid);
        if ($info) {
            $jsData['success'] = true;
            $jsData['message'] = 'Категория обновлена';
        } else {
            $jsData['success'] = false;
            $jsData['message'] = 'Ошибка обновления';
        }
        echo json_encode($jsData);
    }
}
