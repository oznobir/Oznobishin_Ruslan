<?php

namespace core\base\models;
// потом trait
abstract class BaseModelMethods
{
    protected array $sqlFunc = ['NOW()'];
    /**
     * @param false|string $table название таблицы БД
     * @param array $set массив значений для построения запроса
     * @return string результат построения части запроса
     */
    protected function creatFields(false|string $table = false, array $set = []): string
    {
        $set['fields'] = $set['fields'] ?? ['*'];
        if (!is_array($set['fields'])) $set['fields'] = explode(',', $set['fields']);
        $table = $table ? $table .'.' :'';
        $fields = '';
        foreach ($set['fields'] as $field)
            $fields .= " $table$field,";
        return $fields;
    }

    /**
     * @param false|string $table название таблицы БД
     * @param array $set массив значений для построения запроса
     * @return string|null результат построения части запроса
     */
    protected function creatOrder(false|string $table = false, array $set = []): string|null
    {
        $table = $table ? $table.'.' :'';
        $set['order'] = (!empty($set['order']) && is_array($set['order'])) ? $set['order'] : null;
        if ($set['order']) {
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
                if (is_int($order)) $order_by .= "$order $order_direction, ";
                else $order_by .= "$table$order $order_direction, ";
            }
            return rtrim($order_by, ', ');
        }
        return null;
    }

    /**
     * @param false|string $table название таблицы БД
     * @param array $set массив значений для построения запроса
     * @param string $instruction инструкция части запроса, по умолчанию 'WHERE'
     * @return string|null результат построения части запроса
     */
    protected function creatWhere(false|string $table = false, array $set = [], string $instruction = 'WHERE'): string|null
    {
        $table = $table ? $table.'.' :'';
        $set['where'] = (!empty($set['where']) && is_array($set['where'])) ? $set['where'] : null;
        if ($set['where']) {
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
                } else  $operand = $set['operand'][$o_count - 1];

                if (isset($set['condition'][$c_count])) {
                    $condition = $set['condition'][$c_count];
                    $c_count++;
                } else  $condition = $set['condition'][$c_count - 1];

                if ($operand === 'IN' || $operand === 'NOT IN') {
                    if (is_string($item) && strpos($item, 'SELECT')) $str_in = $item;
                    else {
                        if (!is_array($item)) $item = explode(',', $item);
                        $str_in = '';
                        foreach ($item as $value)
                            $str_in .= "'" . trim($this->mb_escape($value)) . "', ";
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
                    $where .= "$table$key LIKE '{$this->mb_escape($item)}' $condition";
                } else {
                    if (is_string($item) && str_starts_with($item, 'SELECT')) {
                        $where .= "$table$key $operand ($item) $condition";
                    } elseif (is_array($item)) {
                        foreach ($item as $value) {
                            if ($where) $where .= ' ';
                            $where .= "$table$key $operand '{$this->mb_escape($value)}' $condition";
                        }
                    } else $where .= "$table$key $operand '{$this->mb_escape($item)}' $condition";
                }
            }
            return substr($where, 0, strrpos($where, $condition));
        }
        return null;
    }

    /**
     * @param string $table название таблицы БД
     * @param array $set массив значений для построения запроса
     * @param bool $new_wh указывает есть ли часть запроса 'WHERE'
     * @return array|null результат построения части запроса
     */
    protected function creatJoin(string $table, array $set, bool $new_wh = false): array|null
    {
        $set['join'] = (!empty($set['join']) && is_array($set['join'])) ? $set['join'] : null;
        if ($set['join']) {
            $where = '';
            $join = '';
            $fields = '';
            $tables = '';
            $join_table = $table;
            foreach ($set['join'] as $key => $item) {
                if (!isset($item['on'])) continue; // throw
                if (is_int($key) && !isset($item['table'])) continue; // throw
                else $key = $item['table'];
                if ($join) $join .= ' ';
                $count = isset($item['on']['fields']) ? count($item['on']['fields']) : 0;
                switch (2) {
                    case $count :
                        $join_fields = $item['on']['fields'];
                        break;
                    case count($item['on']): // 2 вариант записи
                        $join_fields = $item['on'];
                        break;
                    default:
                        continue 2; // throw
                }
                if (!empty($item['type'])) $join .= 'LEFT JOIN ';
                else  $join .= trim(strtoupper($item['type'])) . ' JOIN ';
                $join .= $key . " ON ";
                $join .= $item['on']['table'] ?? $join_table;
                $join .= ".$join_fields[0] = $key.$join_fields[1]";
                $join_table = $key;
                $tables .= ', '.trim($join_table);
                if ($new_wh) {
                    if (isset($item['where'])) $new_wh = false;
                    $group_condition = ' WHERE ';
                } else $group_condition = strtoupper($item['group_condition'][0]) ?? 'AND';

                $fields .= $this->creatFields($key, $item);
                $where .= $this->creatWhere($key, $item, $group_condition);
            }
            return compact('fields', 'join', 'where', 'tables' );
        }
        return null;
    }

    /**
     * @param false|array $fields ['column' => 'column_value', ...] массив данных
     * @param false|array $files ['name' => 'value', ...] массив files
     * @param false|array $except ['except1', ...] исключает данные из массива
     * @return array
     */
    protected function creatInsert(false|array $fields, false|array $files, false|array $except): array
    {
        $insert_arr['fields'] = '';
        $insert_arr['values'] = '';
        if($fields) {
            foreach ($fields as $row => $field){
                if ($except && in_array($row, $except)) continue;
                $insert_arr['fields'] .= $row . ', ';
                if (in_array($field, $this->sqlFunc)) $insert_arr['values'] .= $field . ', ';
                else $insert_arr['values'] .= "'" .  $this->mb_escape($field)."', ";
            }

        }
        if($files) {
            foreach ($files as $row => $file){
                $insert_arr['fields'] .= $row . ', ';
                if(is_array($file)) $insert_arr['values'] .= "'" .  $this->mb_escape(json_encode($file))."', ";
                else $insert_arr['values'] .= "'" .  $this->mb_escape($file)."', ";
            }
        }

        foreach ($insert_arr as $key => $arr)
            $insert_arr[$key] = rtrim($arr, ', ');

        return $insert_arr;
    }
    protected function creatUpdate(false|array $fields, false|array $files, false|array $except): string
    {
        $update = '';
        if($fields) {
            foreach ($fields as $row => $field){
                if ($except && in_array($row, $except)) continue;
                $update .= $row . '= ';
                if (in_array($field, $this->sqlFunc)) $update .= $field . ', ';
                elseif ($field === NULL) $update .= "NULL" . ', ';
                else $update .= "'" .  $this->mb_escape($field)."', ";
            }
        }
        if($files) {
            foreach ($files as $row => $file){
                $update .= $row . '= ';
                if(is_array($file)) $update .= "'" .  $this->mb_escape(json_encode($file))."', ";
                else $update .= "'" .  $this->mb_escape($file)."', ";
            }
        }
        return rtrim($update, ', ');
    }
    protected function mb_escape(string $string): string
    {
        return preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $string);
    }
}