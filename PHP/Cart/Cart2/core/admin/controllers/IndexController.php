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
        $this->contentMenu = $this->render(ADMIN_TEMPLATE . 'include/menu');
        $this->contentCenter = $this->render(ADMIN_TEMPLATE . 'index');
    }
}