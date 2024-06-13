<?php

namespace core\admin\controllers;

use core\base\exceptions\RouteException;

class IndexController extends BaseAdmin
{
    /**
     * @throws RouteException
     */
    protected function inputData(): void
    {
        if (!$this->userId) $this->exec();
        $this->template = ADMIN_TEMPLATE . 'index';
    }
}