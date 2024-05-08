<?php

namespace core\admin\models;

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

    /**
     * @param string $table
     * @param string|false $key
     * @return int|bool|array|string
     * @throws DbException
     */
    public function showForeignKeys(string $table, string|false $key = false): int|bool|array|string
    {
        $db = DB;
        $where = '';
        if ($key) $where = " AND COLUMN_NAME ='$key' LIMIT 1";
        // innoDB
        $query = "SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                  FROM information_schema.KEY_COLUMN_USAGE 
                  WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table' 
                    AND CONSTRAINT_NAME <> 'PRIMERY' AND REFERENCED_TABLE_NAME is not null" . $where;
        return $this->query($query);
    }

}