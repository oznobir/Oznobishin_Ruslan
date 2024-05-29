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

    /**
     * @throws DbException
     */
    public function updatePosition($table, $field, $where, $end, $updateRows = []): int|bool|array|string|null
    {
        if (!empty($updateRows) && isset($updateRows['where'])) {
            $updateRows['operand'] = $updateRows['operand'] ?? ['='];
            if ($where) {
                $oldData = $this->select($table, [
                    'fields' => [$updateRows['where'], $field],
                    'where' => $where,
                ])[0];
                $start = $oldData[$field];
                if ($oldData[$updateRows['where']] !== $_POST[$updateRows['where']]) {
                    $pos = $this->select($table, [
                        'fields' => ['COUNT(*) as count'],
                        'where' => [$updateRows['where'] => $oldData[$updateRows['where']]],
                        'no_concat' => true,
                    ])[0]['count'];
                    if ($start != $pos) {
                        $updateWhere = $this->createWhere([
                            'where' => [$updateRows['where'] => $oldData[$updateRows['where']]],
                            'operand' => $updateRows['operand'],
                        ]);
                        $query = "UPDATE $table SET $field = $field - 1 $updateWhere AND $field <= $pos AND $field > $start";
                        $this->query($query, 'default');
                    }
                    $start = $this->select($table, [
                            'fields' => ['COUNT(*) as count'],
                            'where' => [$updateRows['where'] => $_POST[$updateRows['where']]],
                            'no_concat' => true,
                        ])[0]['count'] + 1;
                }
            } else {
                $start = $this->select($table, [
                        'fields' => ['COUNT(*) as count'],
                        'where' => [$updateRows['where'] => $_POST[$updateRows['where']]],
                        'no_concat' => true,
                    ])[0]['count'] + 1;
            }
            if(array_key_exists($updateRows['where'], $_POST)) $whereEqual = $_POST[$updateRows['where']];
            elseif (isset($oldData[$updateRows['where']])) $whereEqual = $oldData[$updateRows['where']];
            else $whereEqual = null;
            $dbWhere = $this->createWhere([
                'where' => [$updateRows['where'] => $whereEqual],
                'operand' => $updateRows['operand'],
            ]);
        } else {
            if ($where) {
                $start = $this->select($table, [
                    'fields' => [$field],
                    'where' => $where,
                ])[0][$field];
            } else {
                $start = $this->select($table, [
                        'fields' => ['COUNT(*) as count'],
                        'no_concat' => true,
                    ])[0]['count'] + 1;
            }
        }
        $dbWhere = isset($dbWhere) ? $dbWhere . ' AND' : 'WHERE';
        if($start < $end)
            $query = "UPDATE $table SET $field = $field - 1 $dbWhere $field <= $end AND $field > $start";
        elseif ($start > $end)
            $query = "UPDATE $table SET $field = $field + 1 $dbWhere $field >= $end AND $field < $start";
        else return null;
        return $this->query($query, 'default');
    }

}