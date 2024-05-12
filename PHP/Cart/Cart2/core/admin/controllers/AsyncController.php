<?php

namespace core\admin\controllers;

use core\base\controllers\BaseAsync;

class AsyncController extends BaseAsync
{
    public function async(): void
    {
        echo 'ADMIN AsyncController';
    }
}