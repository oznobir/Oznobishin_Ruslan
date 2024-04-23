<?php

namespace core\base\models;

use core\base\controllers\Singleton;
use core\base\exceptions\DbException;

class BaseModel
{
    use Singleton;

    protected \mysqli $db;

    private function __construct()
    {
        mysqli_report(MYSQLI_REPORT_OFF);
        $this->db = @new \mysqli(HOST, USER, PASS, DB);
        if ($this->db->connect_error) {
            throw new DbException("Ошибка подключения к базе данных: {$this->db->connect_errno} {$this->db->connect_error}");
        }
        $this->db->set_charset("utf8");
    }

    //ins sel upd del
    final public function query($query, $act = 'sel', $insert_id = false): array|bool|int|string
    {
        $result = $this->db->query($query);
        if ($this->db->affected_rows === -1) {
            throw new DbException("Ошибка в SQL запросе: $query - {$this->db->errno} {$this->db->error}");
        }
        switch ($act) {
            //ins sel upd del
            case 'sel':
                if ($result->num_rows) {
                    $res = [];
                    for ($i = 0; $i < $result->num_rows; $i++) $res[] = $result->fetch_assoc();
                    return $res;
                }
                return false;
            case 'ins':
                if ($insert_id) return $this->db->insert_id;
                return true;
            default:
                return true;
        }
    }
    //ins sel upd del

    /**
     * @param $table
     * @param array $set
     * 'fields' => ['column1', 'column2'],
     * 'where' => ['column1' => 'column1_value1', 'column2' => 'column2_value1'],
     * 'operand' => ['=', '<>'],
     * 'condition' => ['AND', 'OR'],
     * 'order' => ['column1', 'column2'],
     * 'order_direction' => ['ASC', 'DESC'],
     *'limit' => '1'
     * @return array|bool|int|string
     * @throws DbException
     */
    final public function sel($table, array $set = []): array|bool|int|string
    {
        $fields = $this->creatFields($table, $set);
        $where = $this->creatWhere($table, $set);
        $order = $this->creatOrder($table, $set);
        $join_arr = $this->creatJoin($table, $set);
        $fields .= $join_arr['fields'];
        $where .= $join_arr['where'];
        $fields = rtrim($fields, ', ');
        $join = $join_arr['join'];
//        $order = $this->creatOrder($table, $set);
        $limit = $set['limit'] ?? '';
        $query = "SELECT $fields FROM $table $join $where $order $limit";
        return $this->query($query);
    }

    protected function creatFields($table = false, $set = []): string
    {
        $set['fields'] = $set['fields'] ?? ['*'];
        if (!is_array($set['fields'])) $set['fields'] = explode(',', $set['fields']);
        $table = $table ? $table . '.' : '';
        $fields = '';
        foreach ($set['fields'] as $field)
            $fields .= $table . $field . ', ';
        return rtrim($fields, ', ');
    }

    protected function creatOrder($table = false, $set = []): string
    {
        $table = $table ? $table . '.' : '';
        $order_by = '';
        if (isset($set['order'])) {
            $set['order_direction'] = $set['order_direction'] ?? ['ASC'];
            if (!is_array($set['order_direction'])) $set['order_direction'] = explode(',', $set['order_direction']);
            $order_by = 'ORDER BY ';
            $d_count = 0;
            foreach ($set['order'] as $order) {
                if (isset($set['order_direction'][$d_count])) {
                    $order_direction = strtoupper($set['order_direction'][$d_count]);
                    $d_count++;
                } else {
                    $order_direction = strtoupper($set['order_direction'][$d_count - 1]);
                }
                if (is_int($order)) $order_by .= $order . ' ' . $order_direction . ', ';
                else $order_by .= $table . $order . ' ' . $order_direction . ', ';
            }

            $order_by = rtrim($order_by, ', ');
        }
        return $order_by;
    }

    protected function creatWhere($table = false, $set = [], $instruction = 'WHERE'): string
    {
        $table = $table ? $table . '.' : '';
        $where = '';
        if (isset($set['where'])) {
            $set['operand'] = $set['operand'] ?? ['='];
            if (!is_array($set['operand'])) $set['operand'] = explode(',', $set['operand']);
            $set['condition'] = $set['condition'] ?? ['AND'];
            if (!is_array($set['condition'])) $set['condition'] = explode(',', $set['condition']);
            $where = $instruction;
            $o_count = 0;
            $c_count = 0;
            $condition = '';
            foreach ($set['where'] as $key => $item) {
                $where .= ' ';
                if (isset($set['operand'][$o_count])) {
                    $operand = $set['operand'][$o_count];
                    $o_count++;
                } else {
                    $operand = $set['operand'][$o_count - 1];
                }
                if (isset($set['condition'][$c_count])) {
                    $condition = $set['condition'][$c_count];
                    $c_count++;
                } else {
                    $condition = $set['condition'][$c_count - 1];
                }
                if ($operand === 'IN' || $operand === 'NOT IN') {
                    if (is_string($item) && strpos($item, 'SELECT')) $str_in = $item;
                    else {
                        if (!is_array($item)) $item = explode(',', $item);
                        $str_in = '';
                        foreach ($item as $value)
                            $str_in .= "'" . trim($value) . "', ";
                    }
                    $where .= $table . $key . ' ' . $operand . ' (' . trim($str_in, ', ') . ') ' . $condition;
                } elseif (str_contains($operand, 'LIKE')) {
                    $likeTemplate = explode('%', $operand);
                    foreach ($likeTemplate as $keyLike => $itemLike) {
                        if (!$itemLike) {
                            if (!$keyLike) $item = '%' . $item;
                            else $item .= '%';
                        }
                    }
                    $where .= "$table$key LIKE '$item' $condition";
                } else {
                    if (is_string($item) && str_starts_with($item, 'SELECT')) {
                        $where .= "$table$key $operand ($item) $condition";
                    } elseif (is_array($item)) {
                        foreach ($item as $value)
                            $where .= "$table$key $operand '$value' $condition ";
                    } else $where .= "$table$key $operand '$item' $condition";

                }
            }
            $where = substr($where, 0, strrpos($where, $condition));
        }
        return $where;
    }
}