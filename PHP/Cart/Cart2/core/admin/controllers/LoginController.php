<?php

namespace core\admin\controllers;


use core\base\controllers\BaseControllers;
use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\base\models\UsersModel;
use core\base\settings\Settings;
use DateTime;

/** @uses LoginController */
class LoginController extends BaseControllers
{
    protected UsersModel $model;

    /**
     * @throws RouteException
     * @throws DbException
     */
    protected function inputData(): string|false
    {
        $this->model = UsersModel::instance();
        $this->model->setAdmin();
        if (isset($this->parameters['logout'])) {
            $this->checkAuth(true);
            $userLog = 'Выход пользователя ' . $this->userId['name'];
            $this->writeLog($userLog, 'users_log.txt', 'Access user');
            $this->model->logout();
            $this->redirect(PATH);
        }
        if ($this->isPost()) {
            if (empty($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
                exit('Fatal error');
            }
            $timeClean = (new DateTime())->modify('-' . BLOCK_TIME . ' hour')->format('Y-m-d H:i:s');
//            $timeClean = (new DateTime())->modify('-1' . ' seconds')->format('Y-m-d H:i:s');
            $this->model->delete($this->model->getBlockedTable(), [
                'where' => ['time' => $timeClean],
                'operand' => ['<'],
            ]);
            $ipUser = filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP) ?:
                (filter_var(@$_SERVER['HTTP_X_FORWARDED'], FILTER_VALIDATE_IP)) ?:
                    @$_SERVER['REMOTE_ADDR'];
            $trying = $this->model->select($this->model->getBlockedTable(), [
                'fields' => ['trying'],
                'where' => ['ip' => $ipUser],
            ]);
            $trying = !empty($trying) ? $this->num($trying[0]['trying']) : 0;
            $success = 0;
            $user = '';
            if (isset($_POST['login']) && isset($_POST['password']) && $trying < 3) {
                $login = $this->clearTags($_POST['login']);
                $password = md5($_POST['password']);
                $userData = $this->model->select($this->model->getAdminTable(), [
                    'fields' => ['id', 'name'],
                    'where' => ['login' => $login, 'password' => $password],
                ]);
                if (!$userData) {
                    $method = 'add';
                    $where = [];
                    if ($trying) {
                        $method = 'edit';
                        $where = ['ip' => $ipUser];
                    }
                    $this->model->$method($this->model->getBlockedTable(), [
                        'fields' => ['login' => $login, 'ip' => $ipUser,
                            'time' => 'NOW()', 'trying' => ++$trying,],
                        'where' => $where,
                    ]);
                    $info = 'Неверные имя пользователя или пароль - login: ' . $login . ', ip: ' . $ipUser;
                } else {
                    $userData = $userData[0];
                    $user = $userData['name'];
                    if (!$this->model->checkUser($userData['id'])) {
                        $info = $this->model->getLastError();
                    } else {
                        $success = 1;
                        $info = 'Вход пользователя - login: ' . $login;
                    }

                }
            } elseif ($trying >= 3) {
                $this->model->logout();
                $info = 'Превышено максимальное количество попыток входа - ip: ' . $ipUser;
            } else {
                $info = 'Заполните обязательные поля - ip: ' . $ipUser;
            }
            $_SESSION['res']['answer'] = $success ? '<div class="success">Добро пожаловать, '. $user. '!</div>' :
                preg_split('/\s*\-/', $info, 2, PREG_SPLIT_NO_EMPTY)[0];

            $this->writeLog($info, 'users_log.txt', 'Access user');

            $path = $success ? PATH . Settings::get('routes')['admin']['alias'] : false;
            $this->redirect($path);
        }
        return $this->render('', ['adminPath' => Settings::get('routes')['admin']['alias']]);
    }
}