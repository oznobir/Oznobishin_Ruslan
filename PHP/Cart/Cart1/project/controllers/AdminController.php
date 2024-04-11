<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\CategoriesModel;
use Project\Models\ProductsModel;


class AdminController extends Controller
{
    /** Формирование страницы категорий в админке
     * /admin/
     * @return void страница категорий в админке
     */
    public function index(): void
    {
        $categories = new CategoriesModel();
        $this->data['categories'] = $categories->getCategoriesWithChild();
        $this->data['allCategories'] = $categories->getCategoriesAll();
        echo $this->render('project/views/admin/categoriesView.php');
    }

    /** Формирование страницы товаров в админке
     * /admin/products/
     * @return void страница товаров в админке
     */
    public function products(): void
    {
        $categories = new CategoriesModel();
        $this->data['categories'] = $categories->getCategoriesWithChild();
        $this->data['allCategories'] = $categories->getCategoriesAll();
        $this->data['products'] = (new ProductsModel())->getProductsAll();
        echo $this->render('project/views/admin/productsView.php');
    }

    /** Формирование сообщения о добавлении категории в админке
     * @return void json success, сообщение
     */
    public function addcategory(): void
    {
        $slug = $_POST['newCategorySlug'];
        $name = $_POST['newCategoryName'];
        $pid = $_POST['mainCategoryId'];
        $jsData = (new CategoriesModel())->checkSlugCategory($slug);
        if (!$jsData) {
            $info = (new CategoriesModel())->newCategoryData($slug, $name, $pid);
            if ($info) {
                $jsData['success'] = true;
                $jsData['message'] = 'Категория добавлена';
            } else {
                $jsData['success'] = false;
                $jsData['message'] = 'Ошибка добавления';
            }
        }
        echo json_encode($jsData);
    }

    /** Формирование сообщения об изменении категории в админке
     * @return void json success, сообщение
     */
    public function updatecategory(): void
    {
        $id = intval($_POST['id']);
        $slug = $_POST['slug'] ?? null;
        $name = $_POST['title'] ?? null;
        $pid = $_POST['parentId'] ?? -1;
        $category = new CategoriesModel();
        $jsData = null;
        if ($slug) $jsData = $category->checkSlugCategory($slug);
        if (!$jsData) {
            $info = $category->updateCategoryData($id, $slug, $name, $pid);
            if ($info) {
                $jsData['success'] = true;
                $jsData['message'] = 'Категория обновлена';
            } else {
                $jsData['success'] = false;
                $jsData['message'] = 'Ошибка обновления';
            }
        }
        echo json_encode($jsData);
    }

    /** Формирование сообщения о добавлении товара в админке
     * @return void json success, сообщение
     */
    public function addproduct(): void
    {
        $title = $_POST['title'];
        $slug = $_POST['slug'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $cid = intval($_POST['cid']);

        $product = new ProductsModel();
        $jsData = $product->checkSlugProduct($slug);
        if (!$jsData) {
            $info = $product->newProductData($title, $slug, $price, $description, $cid);
            if ($info) {
                $jsData['success'] = true;
                $jsData['message'] = 'Товар добавлена';
            } else {
                $jsData['success'] = false;
                $jsData['message'] = 'Ошибка добавления товара';
            }
        }
        echo json_encode($jsData);
    }
}
