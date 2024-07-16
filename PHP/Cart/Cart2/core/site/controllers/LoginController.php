<?php

namespace core\site\controllers;

use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\base\models\UsersModel;
use core\site\helpers\ValidationHelper;

class LoginController extends BaseSite
{
    use ValidationHelper;

    protected array $validation = [

        'name' => [
            'translate' => 'Ваше имя',
            'count' => '40',
            'methods' => ['emptyField', 'countField', 'stringField'],
        ],
        'phone' => [
            'translate' => 'Телефон',
            'count' => '20',
            'methods' => ['emptyField', 'phone375Field'],
        ],
        'email' => [
            'translate' => 'E-mail',
            'count' => '70',
            'methods' => ['emptyField', 'countField', 'emailField'],
        ],
        'password' => [
            'translate' => 'Пароль',
            'count' => '32',
            'countMin' => '2',
            'methods' => ['emptyField', 'countMinField', 'countField', 'md5PassField'],
        ],
    ];

    protected function inputData(): void
    {
        parent::inputData();
        if (!empty($this->parameters['alias'])) {
            switch ($this->parameters['alias']) {
                case 'registration':
                    $this->registration();
                    break;
                case 'auth':
                    $this->auth();
                    break;
                case 'logout':
                    UsersModel::instance()->logout();
                    $this->redirect(PATH);
            }
        }
        throw new RouteException('Неверная ссылка при регистрации/авторизации пользователя', 0);
    }

    /**
     * @return void
     * @throws DbException
     * @throws RouteException
     */
    protected function registration(): void
    {
        if (!$this->isPost())
            throw new RouteException('Отсутствуют POST-данные для регистрации пользователя', 3);
        $_POST['password'] = trim($_POST['password'] ?? '');
        $_POST['confirm_password'] = trim($_POST['confirm_password'] ?? '');
        if ($this->userData['id'] && !$_POST['password']) {
            unset($_POST['password']);
        }
        $resError = [];
        if (isset($_POST['password']) && $_POST['password'] !== $_POST['confirm_password']) {
            $resError['confirm_password'] = $this->sendAnswer('Пароли не совпадают');
        }
        unset($_POST['confirm_password']);
        foreach ($_POST as $key => $item) {
            if (empty($resError[$key]))
                $_POST[$key] = $this->clearFormFields($this->validation[$key], $item, $key, $resError);
        }
        if (!empty($resError)) {
            $resError['form_1'] = 1;
            $_SESSION['res']['answerForm'] = $resError;
            $this->addSessionData();
        }
        $where = [
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
        ];
        $conditions[] = 'OR';
        $resInfo = $this->model->select('visitors', [
            'where' => $where,
            'conditions' => $conditions,
            'limit' => 1,
        ]);
        if ($resInfo) {
            $resInfo = $resInfo[0];
            $field = $resInfo['phone'] ? 'phone' : 'email';
            $translate = $resInfo['phone'] ? 'телефоном' : 'E-mail';
            $_SESSION['res']['answerForm'][$field] = $this->sendAnswer('Пользователь с таким ' . $translate . ' уже существует');
            $_SESSION['res']['answerForm']['form_1'] = 1;
            $this->addSessionData();
        }

        $id = $this->model->add('visitors', [
            'return_id' => true
        ]);
        if (!$id)
            throw new RouteException('Ошибка добавления в таблицу  visitors', 3);
        if (!UsersModel::instance()->checkUser($id))
            throw new RouteException('Ошибка регистрации пользователя', 3);

        $_SESSION['res']['answer'] = $this->sendAnswer('Регистрация прошла успешно', 'success');
        $this->redirect();
    }

    /**
     * @return void
     * @throws DbException
     * @throws RouteException
     */
    protected function auth(): void
    {
        if (!$this->isPost())
            throw new RouteException('Отсутствуют POST-данные для авторизации пользователя', 3);
        $_POST['phone'] = trim($_POST['phone'] ?? '');
        $_POST['email'] = trim($_POST['email'] ?? '');
        if (!empty($_POST['phone']) && empty($_POST['email'])) unset($_POST['email']);
        if (empty($_POST['phone']) && !empty($_POST['email'])) unset($_POST['phone']);
        $resError = [];
        foreach ($_POST as $key => $item) {
            if (empty($resError[$key]))
                $_POST[$key] = $this->clearFormFields($this->validation[$key], $item, $key, $resError);
        }
        if (!empty($resError)) {
            $resError['form_2'] = 2;
            $_SESSION['res']['answerForm'] = $resError;
            $this->addSessionData();
        }
        $where = ['password' => $_POST['password']];
        $conditions[] = 'AND';
        if (!empty($_POST['email']) && !empty($_POST['phone'])) {
            $where = ['email' => $_POST['email'], 'phone' => $_POST['phone']];
            $conditions[] = 'OR';
        } else {
            $v_key = !empty($_POST['email']) ? 'email' : 'phone';
            $where[$v_key] = $_POST[$v_key];
        }
        $visitorInfo = $this->model->select('visitors', [
            'where' => $where,
            'conditions' => $conditions,
            'limit' => 1
        ]);

        if (!$visitorInfo) {
            $_SESSION['res']['answerForm']['phone'] = $this->sendAnswer('Пользователь с такими данными не зарегистрирован');
            $_SESSION['res']['answerForm']['form_2'] = 2;
            $this->addSessionData();
        }
        $visitorInfo = $visitorInfo[0];
        if (UsersModel::instance()->checkUser($visitorInfo['id'])) {
            $_SESSION['res']['answer'] = $this->sendAnswer('Добро пожаловать, ' . $visitorInfo['name'] . '!', 'success');
            $this->redirect();
        } else
            throw new RouteException('Ошибка авторизации пользователя', 3);
    }
}