<?php

namespace core\site\controllers;

use core\base\controllers\BaseAsync;

class AsyncController extends BaseAsync
{
    public function async(): void
    {
        echo 'SITE AsyncController';
    }
}