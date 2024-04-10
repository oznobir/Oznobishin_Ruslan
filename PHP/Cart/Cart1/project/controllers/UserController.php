<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\CategoriesModel;
use Project\Models\OrdersModel;
use Project\Models\UsersModel;
use Project\Models\ProductsModel;

class UserController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['user'])) $this->redirect('/');
        $this->data['title'] = 'Личный аккаунт';
        $this->data['description'] = 'Гипермаркет myshop.by Личный аккаунт пользователя';
        $this->data['orders'] = (new OrdersModel())->getOrdersByUser();
        $this->data['menu'] = (new CategoriesModel())->getCategoriesWithChild();
        if (!empty($_SESSION['viewProducts']))
            $this->data['viewProducts'] = (new ProductsModel())->getProductsFromArray($_SESSION['viewProducts']);
        echo $this->render('project/views/shopDefault/userView.php');
    }
    public function unregister(): void
    {
        $_SESSION['user'] = 'unReg';
        $this->redirect('/cart/');
    }

    /** Регистрация нового пользователя fetch
     *
     * проверки в UsersModel()
     * /user/register/
     * @return void передает в json - массив с success и message и имя пользователя
     */
    public function register(): void
    {
        $email = (isset($_POST['email'])) ? htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8') : null;
        $name = (isset($_POST['name'])) ? htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8') : null;
        $pwd1 = (isset($_POST['pwd1'])) ? htmlspecialchars($_POST['pwd1'], ENT_QUOTES, 'UTF-8') : null;
        $pwd2 = (isset($_POST['pwd2'])) ? htmlspecialchars($_POST['pwd2'], ENT_QUOTES, 'UTF-8') : null;
        $phone = (isset($_POST['phone'])) ? htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8') : null;
        $address = (isset($_POST['address'])) ? htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8') : null;

        $newUser = new UsersModel();
        $info = $newUser->checkRegisterParam($email, $pwd1, $pwd2);
        $check = $newUser->checkEmail($email);
        if (!$info && $check) {
            $info['success'] = false;
            $info['message'] = 'Пользователь с таким email уже существует';
        }
        if (!$info) {
            $userData = $newUser->registerNewUser($email, $pwd1, $name, $phone, $address);
            if ($userData['success']) {
                $_SESSION['user'] = $userData['user'];
                $_SESSION['user']['displayName'] = $userData['user']['name'] ? $userData['user']['name'] : $userData['user']['email'];
                $info['success'] = true;
                $info['message'] = 'Пользователь успешно зарегистрирован';
                $info['user'] = $_SESSION['user']['displayName'];
            } else {
                $info['success'] = false;
                $info['message'] = 'Ошибка регистрации';
            }
        }
        echo json_encode($info);
    }

    /** Удаляет из $_SESSION данные о пользователе и данные в корзине
     *
     * /user/logout/
     * @return void redirect "/"
     */
    public function logout(): void
    {
        if ($_SESSION['user']) {
            unset($_SESSION['user']);
            unset($_SESSION['cart']);
        }
        $this->redirect('/');
    }

    /** Авторизация пользователя fetch
     *
     * /user/login/
     * @return void передает в json - массив данных о пользователе
     */
    public function login(): void
    {
        $email = isset($_POST['loginEmail']) ? htmlspecialchars(trim($_POST['loginEmail']), ENT_QUOTES, 'UTF-8') : null;
        $pwd = isset($_POST['loginPwd']) ? htmlspecialchars($_POST['loginPwd'], ENT_QUOTES, 'UTF-8') : null;

        $user = new UsersModel();
        $info = $user->checkLoginParam($email, $pwd);
        if (!$info) {
            $userData = $user->loginUser($email, $pwd);
            if ($userData['success']) {
                $_SESSION['user'] = $userData['user'];
                $_SESSION['user']['displayName'] = $userData['user']['name'] ? $userData['user']['name'] : $userData['user']['email'];
                $info['success'] = true;
                $info['message'] = "Здравствуйте, {$_SESSION['user']['displayName']}";
                $info['user'] = $_SESSION['user']['displayName'];
            } else {
                $info['success'] = false;
                $info['message'] = 'Неверный логин или пароль';
            }
        }
        echo json_encode($info);
    }

    /** Обновление данных пользователя fetch
     *
     * /user/update/
     * @return void в json - массив с success и message и имя пользователя
     */
    public function update(): void
    {
        if (!isset($_SESSION['user'])) $this->redirect('/');

        $name = (isset($_POST['name'])) ? htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8'): null;
        $pwd1 = (isset($_POST['pwd1'])) ? htmlspecialchars($_POST['pwd1'], ENT_QUOTES, 'UTF-8') : null;
        $pwd2 = (isset($_POST['pwd2'])) ? htmlspecialchars($_POST['pwd2'], ENT_QUOTES, 'UTF-8') : null;
        $phone = (isset($_POST['phone'])) ? htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8') : null;
        $address = (isset($_POST['address'])) ? htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8') : null;
        $curPwd = (isset($_POST['curPwd'])) ? htmlspecialchars($_POST['curPwd'], ENT_QUOTES, 'UTF-8') : null;

        $info = (new UsersModel())->checkUpdateParam($curPwd, $pwd1, $pwd2);
        if (!$info) {
            $userData = (new UsersModel())->updateUser($name, $phone, $address, $pwd1);
            if ($userData['result']) {
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['address'] = $address;
                if ($userData['newPwd']) $_SESSION['user']['password'] = $userData['newPwd'];
                $_SESSION['user']['displayName'] = $name ? $name : $_SESSION['user']['email'];
                $info['success'] = true;
                $info['message'] = 'Данные сохранены';
                $info['user'] = $_SESSION['user']['displayName'];
            } else {
                $info['success'] = false;
                $info['message'] = 'Данные не изменены';
            }
        }
        echo json_encode($info);
    }
}
