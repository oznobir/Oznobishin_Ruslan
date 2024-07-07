<?php

namespace core\admin\controllers;

use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;

class IndexController extends BaseAdmin
{
    /**
     * @throws RouteException|DbException
     */
    protected function inputData(): void
    {
        if (!$this->userData['id']) $this->exec();
        $this->template = ADMIN_TEMPLATE . 'index';
    }
}