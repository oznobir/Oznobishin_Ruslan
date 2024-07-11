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
            'count' => '40',
            'methods' => ['emptyField', 'countField', 'emailField'],
        ],
        'password' => [
            'translate' => 'Пароль',
            'count' => '140',
            'countMin' => '5',
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

                    break;
            }
        }
        throw new RouteException('Неверная ссылка при регистрации/авторизации пользователя', 0);
    }

    /**
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
        if (isset($_POST['password']) && $_POST['password'] !== $_POST['confirm_password']) {
            $this->sendAnswer('Пароли не совпадают', 'error', 'confirm_password');
        }
        unset($_POST['confirm_password']);
        foreach ($_POST as $key => $item) {
            $_POST[$key] = $this->clearFormFields($this->validation[$key], $item, $key);
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
            $this->sendAnswer('Пользователь с таким ' . $translate . ' уже существует', 'error', $field);
        }
        $id = $this->model->add('visitors', [
            'return_id' => true
        ]);
        if (!$id)
            throw new RouteException('Ошибка добавления в таблицу  visitors', 3);
        if (!UsersModel::instance()->checkUser($id))
            throw new RouteException('Ошибка регистрации пользователя', 3);

        $this->sendAnswer('Регистрация прошла успешно', 'success');
    }
}