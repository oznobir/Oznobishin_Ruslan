<?php

namespace core\admin\models;

use core\base\controllers\Singleton;
use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\base\models\BaseModel;
use core\base\settings\Settings;


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

                if (isset($_POST[$updateRows['where']]) && $oldData[$updateRows['where']] != $_POST[$updateRows['where']]) {
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
            if (array_key_exists($updateRows['where'], $_POST)) $whereEqual = $_POST[$updateRows['where']];
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
        if ($start < $end)
            $query = "UPDATE $table SET $field = $field - 1 $dbWhere $field <= $end AND $field > $start";
        elseif ($start > $end)
            $query = "UPDATE $table SET $field = $field + 1 $dbWhere $field >= $end AND $field < $start";
        else return null;
        return $this->query($query, 'default');
    }

    /**
     * @param string $data
     * @param string|false $curTable
     * @param int|false $qty
     * @return array|false
     * @throws DbException
     * @throws RouteException
     */
    public function searchData(string $data, string|false $curTable = false, int|false $qty = false): array|false
    {
        $dbTables = $this->showTables();
        $data = addslashes($data);
        $arr = preg_split('/([,.])?\s+/', $data, 0, PREG_SPLIT_NO_EMPTY);
        $searchArr = [];
        for (; ;) {
            if (!$arr) break;
            $searchArr[] = implode(' ', $arr);
            unset($arr[count($arr) - 1]);
        }
        $order = [];
        $correctCurTable = false;
        $projectTables = Settings::get('projectTables');
        foreach ($projectTables as $table => $item) {
            if (!in_array($table, $dbTables)) continue;
            $searchRows = [];
            $orderRows = ['name'];
            $fields = [];
            $columns = $this->showColumns($table);
            $fields[] = $columns['pri'][0] . ' as id';
            $fieldName = isset($columns['name']) ? "CASE WHEN $table.name <> '' THEN $table.name " : '';
            foreach ($columns as $col => $value) {
                if ($col !== 'name' && stripos($col, 'name') !== false) {
                    if (!$fieldName) $fieldName = 'CASE ';
                    $fieldName .= "WHEN $table.$col <> '' THEN $table.$col ";
                }
                if (isset($value['Type']) &&
                    (stripos($value['Type'], 'char') !== false ||
                        stripos($value['Type'], 'text') !== false)) {
                    $searchRows[] = $col;
                }
            }
            if ($fieldName) $fields[] = $fieldName . 'END as name';
            else $fields[] = $columns['pri'][0] . ' as name';
            $fields[] = "('$table') as table_name";

            $res = $this->createWhereOrder($searchRows, $searchArr, $orderRows, $table);
            $where = $res['where'];
            !$order && $order = $res['order'];
            if ($table === $curTable) {
                $correctCurTable = true;
                $fields[] = "('current_table') as current_table";
            }
            if ($where) $this->buildUnion($table, [
                'fields' => $fields,
                'where' => $where,
                'no_concat' => true,
            ]);
        }
        $orderDirection = '';
        if ($order) {
            $order = ($correctCurTable ? 'current_table DESC, ' : '') . ' (' . implode('+', $order) . ') ';
            $orderDirection = 'DESC';
        }
        $result = $this->getUnion([
//            'type' => 'all',
//            'pagination' => [],
//            'limit' => $qty,
            'order' => $order,
            'order_direction' => $orderDirection,
        ]);
        if ($result) {
            foreach ($result as $index => $item) {
                $result[$index]['table_alias'] = $projectTables[$item['table_name']]['name'] ?? $item['table_name'];
                $result[$index]['path_edit'] = PATH . Settings::get('routes')['admin']['alias'] . '/edit/' . $item['table_name'] . '/' . $item['id'];
            }
        }
        return $result ?:[];
    }

    /**
     * @throws DbException
     */
    protected function createWhereOrder($searchRows, $searchArr, $orderRows, $table): array
    {
        $where = '';
        $order = [];
        if ($searchRows && $searchArr) {
            $columns = $this->showColumns($table);
            if ($columns) {
                $where = '(';
                foreach ($searchRows as $row) {
                    $where .= '(';
                    foreach ($searchArr as $item) {
                        if (in_array($row, $orderRows)) {
                            $str = "($row LIKE '%$item%')";
                            if (!in_array($str, $order)) $order[] = $str;
                        }
                        if (isset($columns[$row])) {
                            $where .= "$table.$row LIKE '%$item%' OR ";
                        }
                    }
                    $where = preg_replace('/\)?\s*or\s*\(?$/i', '', $where) . ') OR ';
                }
                $where && $where = preg_replace('/\s*or\s*$/i', '', $where) . ')';
            }
        }
        return compact('where', 'order');
    }
}