<?php

namespace core\site\models;

use core\base\controllers\Singleton;
use core\base\exceptions\DbException;
use core\base\models\BaseModel;

class Model extends BaseModel
{
    use Singleton;
    /**
     * @throws DbException
     */
    private function __construct()
    {
        $this->connect();
    }
}