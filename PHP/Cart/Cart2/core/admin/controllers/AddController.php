<?php

namespace core\admin\controllers;


use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;

/** @uses AddController */
class AddController extends BaseAdmin
{
    /** @uses $action */
    protected string $action = 'add';

    /**
     * @return void
     * @throws DbException
     * @throws RouteException
     */
    protected function inputData(): void
    {
        if (!$this->userData['id']) $this->exec();

        if ($this->isPost()) $this->checkPost();
        $this->createTableData();
        $this->createForeignData();
        $this->createMenuPosition();
        $this->createRadio();
        $this->createOutputData();
        $this->createManyToMany();
        $this->template = ADMIN_TEMPLATE . 'add';
        $this->expansionBase();

    }
//    protected function outputData(): false|string
//    {
//        $this->contentMenu = $this->render(ADMIN_TEMPLATE . 'include/menu');
//        $this->contentCenter = $this->render($this->template);
//        return parent::outputData();
//    }
}
