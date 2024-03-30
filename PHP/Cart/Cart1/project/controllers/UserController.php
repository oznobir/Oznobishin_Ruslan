<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\CategoriesModel;
use Project\Models\UsersModel;

class UserController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['user'])) $this->redirect('/');
        $this->data['title'] = 'Личный аккаунт';
        $this->data['description'] = 'Гипермаркет myshop.by Личный аккаунт пользователя';
        $this->data['arrUser'] = $_SESSION['user'];
        $this->data['menu'] = (new CategoriesModel())->getCategoriesWithChild();
        echo $this->render('project/views/default/shopUserView.php');
    }

    /** Регистрация нового пользователя fetch
     *
     * проверки в UsersModel()
     * /user/register/
     * @return void передает в json - массив с success и message и имя пользователя
     */
    public function register(): void
    {
        $email = (isset($_POST['email'])) ? trim($_POST['email']) : null;
        $name = (isset($_POST['name'])) ? trim($_POST['name']) : null;
        $pwd1 = (isset($_POST['pwd1'])) ? $_POST['pwd1'] : null;
        $pwd2 = (isset($_POST['pwd2'])) ? $_POST['pwd2'] : null;
        $phone = (isset($_POST['phone'])) ? $_POST['phone'] : null;
        $address = (isset($_POST['address'])) ? $_POST['address'] : null;

        $newUser = new UsersModel();
        $info = $newUser->checkRegisterParam($email, $pwd1, $pwd2); //проверку может быть потом в js
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
                $info['user'] = $_SESSION['user'];
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
        $email = isset($_POST['loginEmail']) ? trim($_POST['loginEmail']) : null;
        $pwd = isset($_POST['loginPwd']) ? trim($_POST['loginPwd']) : null;;

        $user = new UsersModel();
        $info = $user->checkLoginParam($email, $pwd);
        if (!$info) {
            $userData = $user->loginUser($email, $pwd);
            if ($userData['success']) {
                $_SESSION['user'] = $userData['user'];
                $_SESSION['user']['displayName'] = $userData['user']['name'] ? $userData['user']['name'] : $userData['user']['email'];
                $info['success'] = true;
                $info['message'] = "Здравствуйте, {$_SESSION['user']['displayName']}";
                $info['user'] = $_SESSION['user'];
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

        $name = (isset($_POST['name'])) ? trim($_POST['name']) : null;
        $pwd1 = (isset($_POST['pwd1'])) ? $_POST['pwd1'] : null;
        $pwd2 = (isset($_POST['pwd2'])) ? $_POST['pwd2'] : null;
        $phone = (isset($_POST['phone'])) ? $_POST['phone'] : null;
        $address = (isset($_POST['address'])) ? $_POST['address'] : null;
        $curPwd = (isset($_POST['curPwd'])) ? $_POST['curPwd'] : null;
        $curPwdHash = md5($curPwd);

        $info = (new UsersModel())->checkUpdateParam($curPwdHash, $pwd1, $pwd2);
        if (!$info) {
            $userData = (new UsersModel())->updateUser($name, $phone, $address, $curPwd, $pwd1, $pwd2);
            if ($userData) {
                $_SESSION['user']['name'] = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
                $_SESSION['user']['phone'] = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
                $_SESSION['user']['address'] = htmlspecialchars($address, ENT_QUOTES, 'UTF-8');
                $_SESSION['user']['password'] = $curPwdHash;
                $_SESSION['user']['displayName'] = $name ? htmlspecialchars($name, ENT_QUOTES, 'UTF-8') : $_SESSION['user']['email'];
                $info['success'] = true;
                $info['message'] = 'Данные сохранены';
                $info['user'] = $_SESSION['user'];
            } else {
                $info['success'] = false;
                $info['message'] = 'Ошибка при сохранении данных';
            }
        }
        echo json_encode($info);
    }
}
