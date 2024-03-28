<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\UsersModel;

class UserController extends Controller
{
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
        $info = $user->checkLoginParam($email, $pwd); //проверку может быть потом в js
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
}
