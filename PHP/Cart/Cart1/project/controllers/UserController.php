<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\UsersModel;

class UserController extends Controller
{
    /**
     * @return void
     */
    public function register(): void
    {
        $email = (isset($_REQUEST['email'])) ? trim($_REQUEST['email']) : null;
        $name = (isset($_REQUEST['name'])) ? trim($_REQUEST['name']) : null;
        $pwd1 = (isset($_REQUEST['pwd1'])) ? $_REQUEST['pwd1'] : null;
        $pwd2 = (isset($_REQUEST['pwd2'])) ? $_REQUEST['pwd2'] : null;
        $phone = (isset($_REQUEST['phone'])) ? $_REQUEST['phone'] : null;
        $address = (isset($_REQUEST['address'])) ? $_REQUEST['address'] : null;

//        $email = (isset($_REQUEST['email'])) ? htmlspecialchars(trim($_REQUEST['email']), ENT_QUOTES, 'UTF-8') : null;
//        $name = (isset($_REQUEST['name'])) ? htmlspecialchars(trim($_REQUEST['name']), ENT_QUOTES, 'UTF-8') : null;
//        $pwd1 = (isset($_REQUEST['pwd1'])) ? htmlspecialchars($_REQUEST['pwd1'], ENT_QUOTES, 'UTF-8') : null;
//        $pwd2 = (isset($_REQUEST['pwd2'])) ? htmlspecialchars($_REQUEST['pwd2'], ENT_QUOTES, 'UTF-8') : null;
//        $phone = (isset($_REQUEST['phone'])) ? htmlspecialchars($_REQUEST['phone'], ENT_QUOTES, 'UTF-8') : null;
//        $address = (isset($_REQUEST['address'])) ? htmlspecialchars($_REQUEST['address'], ENT_QUOTES, 'UTF-8') : null;

        $newUser = new UsersModel();
        $info = $newUser->checkRegisterParam($email, $pwd1, $pwd2);
        $check = $newUser->checkEmail($email);
        if (!$info && $check) {
            $info['success'] = false;
            $info['message'] = 'Пользователь с таким email уже существует';
        }
        if (!$info) {
            $pwdHash = password_hash($pwd1, 'argon2id');
            $userData = $newUser->registerNewUser($email, $pwdHash, $name, $phone, $address);
            if ($userData['success']) {
                $info['success'] = true;
                $info['message'] = 'Пользователь успешно зарегистрирован';
                $info['userName'] = ($userData['user']['name']) ? $userData['user']['name'] : $userData['user']['email'];
                $_SESSION['user'] = $userData['user'];
                $_SESSION['user']['displayName'] = $info['userName'];
            } else {
                $info['success'] = false;
                $info['message'] = 'Ошибка регистрации';
            }
        }
        echo json_encode($info);
    }
}