<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\CategoriesModel;
use Project\Models\ProductsModel;
use Project\Models\OrdersModel;

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

    /** Формирование страницы заказов в админке
     * /admin/orders/
     * @return void страница заказов в админке
     */
    public function orders(): void
    {
        $orders = new OrdersModel();
        $this->data['orders'] = $orders->getOrdersWithPurchase();
//        echo '<pre>';
//        print_r($this->data['orders']);
//        echo '</pre>';

//        $this->data['allCategories'] = $categories->getCategoriesAll();
//        $this->data['products'] = (new ProductsModel())->getProductsAll();
        echo $this->render('project/views/admin/ordersView.php');
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
                $jsData['message'] = 'Товар добавлен';
            } else {
                $jsData['success'] = false;
                $jsData['message'] = 'Ошибка добавления товара';
            }
        }
        echo json_encode($jsData);
    }

    /** Формирование сообщения об изменении категории в админке
     * @return void json success, сообщение
     */
    public function updateproduct(): void
    {
        $id = $_POST['id'];
        $slug = $_POST['slug'] ?? null;
        $title = $_POST['title'] ?? null;
        $cid = $_POST['categoryId'] ?? -1;
        $status = $_POST['status'] ?? -1;
        $price = $_POST['price'] ?? null;
        $description = $_POST['description'] ?? null;
        $image = $_POST['image'] ?? null;
        $product = new ProductsModel();
        $jsData = null;
        if ($slug) $jsData = $product->checkSlugProduct($slug);
        if (!$jsData) {
            $info = $product->updateProductData($id, $slug, $title, $description, $price, $image, $status, $cid);
            if ($info) {
                $jsData['success'] = true;
                $jsData['message'] = 'Данные о товаре обновлены';
            } else {
                $jsData['success'] = false;
                $jsData['message'] = 'Ошибка обновления';
            }
        }
        echo json_encode($jsData);
    }

    /** Сохранение изображения товара в админке
     * @return void json success, сообщение
     */
    public function uploadimage(): void
    {
        if ($_FILES['filename']['size'] > 1024 * 250) {
            $jsData['success'] = false;
            $jsData['message'] = 'Размер файла превышает 250кБайт';
            echo json_encode($jsData);
            exit();
        }
        $itemId = $_POST['itemId'];
        $lastModified = $_POST['lastModified'];
        // из-за возможного кэша браузера пользователя
        // idProduct_lastModified.exetension
        $name = $itemId . '_' . $lastModified . '.' . pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
        $path = $_SERVER['DOCUMENT_ROOT'] . '/project/access/img/' . $name;
        if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
            if (move_uploaded_file($_FILES['filename']['tmp_name'], $path)) {
                if ((new ProductsModel())->updateProductImage($itemId, $name)) {
                    $jsData['success'] = true;
                    $jsData['message'] = 'Файл сохранен в базе и project/access/img';
                } else {
                    $jsData['success'] = false;
                    $jsData['message'] = 'Ошибка сохранения файла в базе';
                }
            } else {
                $jsData['success'] = false;
                $jsData['message'] = 'Ошибка сохранения файла в project/access/img';
            }
        } else {
            $jsData['success'] = false;
            $jsData['message'] = 'Ошибка обработки файла';
        }
        echo json_encode($jsData);
    }

    /** Формирование сообщения об изменении статуса заказа в админке
     * @return void json success, сообщение
     */
    public function orderstatus(): void
    {
        $orderId = intval($_POST['id']);
        $status = intval($_POST['status']);
        $info = (new OrdersModel())->updateStatusOrder($orderId, $status);
        if ($info) {
            $jsData['success'] = true;
            $jsData['message'] = 'Статус изменен. Заказ закрыт';
        } else {
            $jsData['success'] = false;
            $jsData['message'] = 'Ошибка изменения статуса';
        }
        echo json_encode($jsData);
    }

    /** Формирование сообщения об изменении даты оплаты в админке
     *
     * @return void json success, сообщение
     */
    public function orderdate(): void
    {
        $orderId = $_POST['id'];
        $date_payment = $_POST['date'];

        $info = (new OrdersModel())->updateDatePaymentOrder($orderId, $date_payment);
        if ($info) {
            $jsData['success'] = true;
            $jsData['message'] = 'Дата оплаты сохранена';
        } else {
            $jsData['success'] = false;
            $jsData['message'] = 'Ошибка сохранения даты оплаты';
        }
        echo json_encode($jsData);
    }
}
