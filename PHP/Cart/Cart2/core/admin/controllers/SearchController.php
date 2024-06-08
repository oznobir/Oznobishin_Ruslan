<?php

namespace core\admin\controllers;


use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;


/**
 * @uses SearchController
 */

class SearchController extends BaseAdmin
{
    /**
     * @return void
     * @throws DbException|RouteException
     */
    protected function inputData(): void
    {
        if (!$this->userId) $this->exec();
        $text = $this->clearTags($_GET['search']);
        $table = $this->clearTags($_GET['search_table']);
        $this->data = $this->model->searchData($text, $table);
        $this->template = ADMIN_TEMPLATE . 'search';
        $this->expansionBase();
    }

    /**
     * @throws RouteException
     */
    protected function outputData(): false|string // перенести в parent::outputData()
    {
        $this->contentMenu = $this->render(ADMIN_TEMPLATE . 'include/menu');
        $this->contentCenter = $this->render($this->template);
        return parent::outputData();
    }


}