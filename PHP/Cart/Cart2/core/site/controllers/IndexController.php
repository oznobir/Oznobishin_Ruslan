<?php

namespace core\site\controllers;

use \core\base\controllers\BaseControllers;
use core\base\exceptions\RouteException;

class IndexController extends BaseControllers
{

    /**
     * @throws RouteException
     */
    protected function inputData(): array
    {
        $name = 'Это страница';
        $content = $this->render('', compact('name'));
        $header = $this->render(SITE_TEMPLATE . 'header');
        $footer = $this->render(SITE_TEMPLATE . 'footer');
        return compact('header', 'content','footer');
    }

//    protected function outputData(): false|string
//    {
//
////        return func_get_arg(0);
////        $this->page = $this->render(SITE_TEMPLATE .'layout', func_get_arg(0));
//        return $this->render(SITE_TEMPLATE .'layout', func_get_arg(0));
//    }
}