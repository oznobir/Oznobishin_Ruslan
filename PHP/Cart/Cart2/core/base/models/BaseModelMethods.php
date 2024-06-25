<?php

namespace core\base\models;

use core\base\exceptions\DbException;

abstract class BaseModelMethods
{
    protected array $sqlFunc = ['NOW()'];
    protected array $columnsTables = [];
    protected array $union = [];

    /**
     * @param array $set массив значений для построения запроса
     * @param string|null $table название таблицы БД
     * @return string результат построения части запроса
     * @throws DbException
     */
    protected function createFields(array $set, null|string $table = null, bool $join = false): string
    {
        if (array_key_exists('fields', $set) && $set['fields'] === null) return '';
        $concatTable = '';
        $aliasTable = $table;
        if (empty($set['no_concat']) && $table) {
            $arrTable = $this->createTableAlias($table);
            $concatTable = $arrTable['alias'] . '.';
            $aliasTable = $arrTable['alias'];
        }
        $fields = '';
        $join_structure = (!empty($set['join_structure']) || $join) && $table;
        if ($join_structure) {
            $this->showColumns($table);
            if (isset($this->columnsTables[$table]['pri']) && count($this->columnsTables[$table]['pri']) > 1) $set['fields'] = [];
        }

        if (empty($set['fields'])) {
            if (!$join) $fields .= $concatTable . '*, ';
            else {
                foreach ($this->columnsTables[$aliasTable] as $key => $item) {
                    if ($key !== 'pri') $fields .= $concatTable . $key . ' AS JT_' . $aliasTable . '_JF_' . $key . ', ';
                }
            }
        } else {
            $id_field = false;
            $set['fields'] = (array)$set['fields'];
            foreach ($set['fields'] as $field) {
                if ($join_structure && !$id_field && $this->columnsTables[$aliasTable]['pri'][0] === $field) $id_field = true;
                if ($field === null) {
                    $fields .= "NULL" . ', ';
                    continue;
                }
                if ($field) {
                    if ($join) {
                        if (preg_match('/^(.+)?\s+AS\s+(.+)/i', $field, $matches)) {
                            $fields .= $concatTable . $matches[1] . ' AS JT_' . $aliasTable . '_JF_' . $matches[2] . ', ';
                        } else $fields .= $concatTable . $field . ' AS JT_' . $aliasTable . '_JF_' . $field . ', ';
                    } else $fields .= (!preg_match('/(\([^()]*\))|(case\s+.+?\s+end)/i', $field) ? $concatTable : '') . $field . ', ';
                }
            }
            if (!$id_field && $join_structure) {
                if ($join) $fields .= $concatTable . $this->columnsTables[$aliasTable]['pri'][0] . ' AS JT_' . $aliasTable . '_JF_' .
                    $this->columnsTables[$aliasTable]['pri'][0] . ', ';
                else $fields .= $concatTable . $this->columnsTables[$aliasTable]['pri'][0] . ', ';
            }
        }
        return $fields;
    }

    /**
     * @param array|null $set массив значений для построения запроса
     * @param string|null $table название таблицы БД
     * @return string|null результат построения части запроса
     */
    protected
    function createOrder(?array $set, ?string $table = null): string|null
    {
        $table = ($table && empty($set['no_concat'])) ? $this->createTableAlias($table)['alias'] . '.' : '';
        if (!empty($set['order'])) {
            $set['order'] = (array)$set['order'];
            $set['order_direction'] = (!empty($set['order_direction'])) ? $set['order_direction'] : ['ASC'];
            if (!is_array($set['order_direction'])) $set['order_direction'] = explode(', ', $set['order_direction']);
            $order_by = ' ORDER BY ';
            $d_count = 0;
            foreach ($set['order'] as $order) {
                if (!empty($set['order_direction'][$d_count])) {
                    $order_direction = strtoupper($set['order_direction'][$d_count]);
                    $d_count++;
                } else $order_direction = strtoupper($set['order_direction'][$d_count - 1]);

                if (in_array($order, $this->sqlFunc)) $order_by .= $order . ', ';
                elseif (is_int($order)) $order_by .= "$order $order_direction, ";
                else $order_by .= "$table$order $order_direction, ";
            }
            return rtrim($order_by, ', ');
        }
        return null;
    }

    /**
     * @param array|null $set массив значений для построения запроса
     * @param string|null $table название таблицы БД
     * @param string $instruction инструкция части запроса, по умолчанию 'WHERE'
     * @return string|null результат построения части запроса
     */
    protected
    function createWhere(?array $set, null|string $table = null, string $instruction = 'WHERE'): string|null
    {
        $table = ($table && empty($set['no_concat'])) ? $this->createTableAlias($table)['alias'] . '.' : '';

        if (!empty($set['where'])) {
            if (is_string($set['where'])) return $instruction . ' ' . trim($set['where']);
            if (is_array($set['where'])) {
                $set['operand'] = (!empty($set['operand'])) ? $set['operand'] : ['='];
                if (!is_array($set['operand'])) $set['operand'] = explode(',', $set['operand']);
                $set['conditions'] = (!empty($set['conditions'])) ? $set['conditions'] : ['AND'];
                if (!is_array($set['conditions'])) $set['conditions'] = explode(',', $set['conditions']);
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

                    if (isset($set['conditions'][$c_count])) {
                        $condition = $set['conditions'][$c_count];
                        $c_count++;
                    } else  $condition = $set['conditions'][$c_count - 1];

                    if ($operand === 'IN' || $operand === 'NOT IN') {
                        $flagArr = false;
                        if (is_string($item) && str_contains($item, 'SELECT')) $str_in = $item;
                        else {
                            if (!is_array($item)) $item = explode(',', $item);
                            $str_in = '';
                            foreach ($item as $value) {
                                if (!is_array($value)) $str_in .= "'" . trim(mb_escape($value)) . "', ";
                                else {
                                    $flagArr = true;
                                    foreach ($value as $v)
                                        $str_in .= "'" . trim(mb_escape($v)) . "', ";
                                    $where .= $table . $key . ' ' . $operand . ' (' . trim($str_in, ', ') . ') ' . $condition . ' ';
                                }
                            }
                        }
                        if (!$flagArr) $where .= $table . $key . ' ' . $operand . ' (' . trim($str_in, ', ') . ') ' . $condition;
                    } elseif (str_contains($operand, 'LIKE')) {
                        $likeTemplate = explode('%', $operand);
                        foreach ($likeTemplate as $keyLike => $itemLike) {
                            if (!$itemLike) {
                                if (!$keyLike) $item = '%' . $item;
                                else $item .= '%';
                            }
                        }
                        $where .= "$table$key LIKE '" . mb_escape($item) . "' $condition";
                    } else {
                        if (is_string($item) && str_starts_with($item, 'SELECT')) {
                            $where .= "$table$key $operand ($item) $condition";
                        } elseif (is_array($item)) {
                            foreach ($item as $value) {
                                if ($where) $where .= ' ';
                                $where .= "$table$key $operand '" . mb_escape($value) . "' $condition";
                            }
                        } elseif ($item === null || $item === 'NULL') {
                            if ($operand === '=') $where .= "$table$key IS NULL $condition";
                            else $where .= "$table$key IS NOT NULL $condition";
                        } else $where .= "$table$key $operand '" . mb_escape($item) . "' $condition";
                    }
                }
                return substr($where, 0, strrpos($where, $condition));
            }
        }
        return null;
    }

    /**
     * @param array|null $set массив значений для построения запроса
     * @param string $table название таблицы БД
     * @param bool $new_wh указывает есть ли часть запроса 'WHERE'
     * @return array|null результат построения части запроса
     * @throws DbException
     */
    protected
    function createJoin(?array $set, string $table, bool $new_wh = false): array|null
    {
        if (!empty($set['join']) && is_array($set['join'])) {
            $where = '';
            $join = '';
            $fields = '';
            $join_table = $table;
            foreach ($set['join'] as $key => $item) {
                if (!isset($item['on'])) continue; // throw
                if (is_int($key) && empty($item['table'])) continue; // throw
                elseif (!empty($item['table'])) $key = $item['table'];

                $concatTable = $this->createTableAlias($key)['alias'];

                if ($join) $join .= ' ';

                if (isset($item['on']['fields']) && count($item['on']['fields']) === 2)
                    $join_fields = $item['on']['fields'];
                elseif (count($item['on']) === 2) $join_fields = $item['on'];
                else continue; // throw

                if (!isset($item['type'])) $join .= 'LEFT JOIN ';
                else  $join .= trim(strtoupper($item['type'])) . ' JOIN ';
                $join .= $key . " ON ";

                $tempTable = $item['on']['table'] ?? $join_table;
                $join .= $this->createTableAlias($tempTable)['alias'];

                $join .= ".$join_fields[0] = $concatTable.$join_fields[1]";
                $join_table = $key;
                if ($new_wh) {
                    if (isset($item['where'])) $new_wh = false;
                    $group_condition = ' WHERE ';
                } else $group_condition = isset($item['group_condition']) ? strtoupper($item['group_condition'][0]) : 'AND';

                $fields .= $this->createFields($item, $key, !empty($set['join_structure']));
                $where .= $this->createWhere($item, $key, $group_condition);
            }
            return compact('fields', 'join', 'where');
        }
        return null;
    }

    /**
     * @param $set
     * @return string
     */
    protected function createGroup($set): string
    {
        $having = isset($set['having']) ? 'HAVING ' . implode(' ', (array)$set['having']) : '';
        return isset($set['group']) ? 'GROUP BY ' . implode(', ', (array)$set['group']) . ' ' . $having : '';
        // HAVING ...
    }

    /**
     * @param false|array $fields ['column' => 'column_value', ...] массив полей
     * @param false|array $files ['name' => 'value', ...] массив files
     * @param false|array $except ['except1', ...] исключает данные из массива
     * @return array
     */
    protected
    function createInsert(false|array $fields, false|array $files, false|array $except): array
    {
        $insert_arr['fields'] = '(';
        $insert_arr['values'] = '';

        $arr_type = array_keys($fields)[0];
        if (is_int($arr_type)) {
            $check_fields = false;
            $count_fields = 0;
            foreach ($fields as $item) {
                $insert_arr['values'] .= '(';
                if (!$count_fields) $count_fields = count($item);
                $j = 0;
                foreach ($item as $row => $value) {
                    if ($except && in_array($row, $except)) continue;
                    if (!$check_fields) $insert_arr['fields'] .= $row . ', ';
                    if (in_array($value, $this->sqlFunc)) $insert_arr['values'] .= $value . ', ';
                    elseif ($value === 'NULL' || $value === NULL) $insert_arr['values'] .= "NULL" . ', ';
                    else  $insert_arr['values'] .= "'" . mb_escape($value) . "'" . ', ';
                    $j++;
                    if ($j === $count_fields) break;
                }
                for (; $j < $count_fields; $j++) {
                    $insert_arr['values'] .= "NULL" . ', ';
                }
                $insert_arr['values'] = rtrim($insert_arr['values'], ', ') . "), ";
                if (!$check_fields) $check_fields = true;
            }
        } else {
            if ($fields) {
                $insert_arr['values'] .= '(';
                foreach ($fields as $row => $field) {
                    if ($except && in_array($row, $except)) continue;
                    $insert_arr['fields'] .= $row . ', ';
                    if (in_array($field, $this->sqlFunc)) $insert_arr['values'] .= $field . ', ';
                    elseif ($field === 'NULL' || $field === NULL) $insert_arr['values'] .= "NULL" . ', ';
                    else  $insert_arr['values'] .= "'" . mb_escape($field) . "'" . ', ';
                }
            }
            if ($files) {
                foreach ($files as $row => $file) {
                    $insert_arr['fields'] .= $row . ', ';
                    if (is_array($file)) $insert_arr['values'] .= "'" . mb_escape(json_encode($file)) . "', ";
                    else $insert_arr['values'] .= "'" . mb_escape($file) . "', ";
                }
            }
            $insert_arr['values'] = rtrim($insert_arr['values'], ', ') . ')';
        }
        $insert_arr['fields'] = rtrim($insert_arr['fields'], ', ') . ')';
        $insert_arr['values'] = rtrim($insert_arr['values'], ', ');
        return $insert_arr;
    }

    /**
     * @param false|array $fields ['column' => 'column_value', ...] массив полей
     * @param false|array $files ['name' => 'value', ...] массив files
     * @param false|array $except ['except1', ...] исключает данные из массива
     * @return string
     */
    protected
    function createUpdate(false|array $fields, false|array $files, false|array $except): string
    {
        $update = '';
        if ($fields) {
            foreach ($fields as $row => $field) {
                if ($except && in_array($row, $except)) continue;
                $update .= $row . '= ';
                if (in_array($field, $this->sqlFunc)) $update .= $field . ', ';
                elseif ($field === NULL || $field === 'NULL') $update .= "NULL" . ', ';
                else $update .= "'" . mb_escape($field) . "', ";
            }
        }
        if ($files) {
            foreach ($files as $row => $file) {
                $update .= $row . '= ';
                if (is_array($file)) $update .= "'" . mb_escape(json_encode($file)) . "', ";
                else $update .= "'" . mb_escape($file) . "', ";
            }
        }
        return rtrim($update, ', ');
    }

    /**
     * @param array $res массив из БД
     * @param string $table имя таблицы
     * @return array преобразованный массив
     */
    protected function joinStructure(array $res, string $table): array
    {
        $joinArr = [];

        $pri = $this->columnsTables[$this->createTableAlias($table)['alias']]['pri'][0];
        foreach ($res as $value) {
            if ($value) {
                if (!isset($joinArr[$value[$pri]])) $joinArr[$value[$pri]] = [];
                foreach ($value as $key => $item) {
                    if (preg_match('/JT_(.+)?_JF/', $key, $matches)) {
                        $table_name = $matches[1];
                        if (isset($this->columnsTables[$table_name]['pri'][0]) && count($this->columnsTables[$table_name]['pri']) == 1) {
                            $join_pri = $value[$matches[0] . '_' . $this->columnsTables[$table_name]['pri'][0]];
                        } else {
                            $join_pri = '';
                            foreach ($this->columnsTables[$table_name]['pri'] as $multi) {
                                $join_pri .= $value[$matches[0] . '_' . $multi];
                            }
                        }
                        $row = preg_replace('/JT_(.+)_JF_/', '', $key);

                        if ($join_pri && !isset($joinArr[$value[$pri]]['join'][$table_name][$join_pri][$row])) {
                            $joinArr[$value[$pri]]['join'][$table_name][$join_pri][$row] = $item;
                        }
                        continue;
                    }
                    $joinArr[$value[$pri]][$key] = $item;
                }
            }
        }
        return $joinArr;
    }

    /** Разбивает имя таблицы на table и alias по пробелу
     * @param string $table имя таблицы
     * @return array массив
     */
    protected function createTableAlias(string $table): array
    {
        $arr = [];
        if (preg_match('/\s+/i', $table)) {
            $table = preg_replace('/\s{2,}/i', ' ', $table);
            $name = explode(' ', $table);
            $arr['table'] = trim($name[0]);
            $arr['alias'] = trim($name[1]);
        } else $arr['table'] = $arr['alias'] = $table;
        return $arr;
    }
}