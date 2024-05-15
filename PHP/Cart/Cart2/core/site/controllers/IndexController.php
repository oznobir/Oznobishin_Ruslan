<?php

namespace core\site\controllers;

use core\base\controllers\BaseControllers;
use core\base\exceptions\RouteException;
use core\base\models\Crypt;

/**
 * @uses IndexController
 */
class IndexController extends BaseControllers
{

    /**
     * @throws RouteException
     */
    protected function inputData(): string
    {
        $str = '12345';
        $name = Crypt::instance()->encrypt($str);
        $name1 = Crypt::instance()->decrypt($name);
        $header = $this->render(SITE_TEMPLATE . 'header');
        $footer = $this->render(SITE_TEMPLATE . 'footer');
        return $this->render('', compact('name', 'name1','header', 'footer'));
    }

//    protected function outputData(): false|string
//    {
//
////        return func_get_arg(0);
////        $this->page = $this->render(SITE_TEMPLATE .'layout', func_get_arg(0));
//        return $this->render(SITE_TEMPLATE .'layout', func_get_arg(0));
//    }
}