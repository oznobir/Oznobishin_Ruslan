<?php

namespace core\admin\controllers;


use core\base\controllers\BaseControllers;
use core\base\models\UsersModel;

class LoginController extends BaseControllers
{
    protected ?UsersModel $model = null;
    protected function inputData(): void
    {
        $this->model = UsersModel::instance();

    }
}