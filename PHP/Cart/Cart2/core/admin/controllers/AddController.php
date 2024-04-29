<?php

namespace core\admin\controllers;


use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\plugins\shop\ShopSettings;

class AddController extends BaseAdmin
{
    /**
     * @throws DbException
     */
    protected function inputData(): void
    {
        if (!$this->userId) $this->exec();
        $this->createTableData();
        $this->createOutputData();


//        $this->contentMenu = $this->render(ADMIN_TEMPLATE . 'include/menu');
//        $this->contentCenter = $this->render(ADMIN_TEMPLATE . 'index');
    }


}