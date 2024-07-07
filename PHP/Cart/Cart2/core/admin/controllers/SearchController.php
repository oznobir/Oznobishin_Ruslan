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
        if (!$this->userData['id']) $this->exec();
        $text = $this->clearTags($_GET['search']);
        $table = $this->clearTags($_GET['search_table']);
        $this->data = $this->model->searchData($text, $table);
        $this->template = ADMIN_TEMPLATE . 'search';
        $this->expansionBase();
    }
}