<?php

namespace core\base\models;

use core\base\exceptions\DbException;
use mysqli;

abstract class BaseModel extends BaseModelMethods
{


    protected mysqli $db;

    /**
     * Устанавливает соединение с БД, флаги, кодировку
     * @throws DbException ошибки при соединении с БД
     */

    protected function connect(): void
    {
        mysqli_report(MYSQLI_REPORT_OFF);
        $this->db = @new mysqli(HOST, USER, PASS, DB);
        if ($this->db->connect_error) {
            throw new DbException("Ошибка подключения к базе данных: {$this->db->connect_errno} {$this->db->connect_error}");
        }
        $this->db->set_charset("utf8");
    }

    /**
     * @param string $query - запрос в виде строки
     * @param string $act insert - 'ins', select - 'sel', update - 'upd ', delete - 'del'
     * @param bool $return_id true - возвращает id (ins, ...)
     * @return array|bool|int|string - результат запроса из БД или ошибки
     * @throws DbException - ошибки
     */
    final public function query(string $query, string $act = 'sel', bool $return_id = false): array|bool|int|string
    {
        $result = $this->db->query($query);
        if ($this->db->affected_rows === -1) {
            throw new DbException("Ошибка в SQL запросе: $query - {$this->db->errno} {$this->db->error}");
        }
        switch ($act) {
            case 'sel':
                if ($result->num_rows) {
                    $res = [];
                    for ($i = 0; $i < $result->num_rows; $i++) $res[] = $result->fetch_assoc();
                    return $res;
                }
                return false;
            case 'ins':
                if ($return_id) return $this->db->insert_id;
                return true;
            default:
                return true;
        }
    }

    /**
     * @param string $table название таблицы БД
     * @param array|null $set массив значений для построения запроса
     * 'fields' => ['column', ...],
     * 'no_concat' => false or true не присоединять имя таблицы, false - присоединять
     * 'where' => ['column' => 'column_value', ...],
     * 'operand' => ['=', '<>', ...],
     * 'condition' => ['AND', 'OR', ...],
     * 'order' => ['column', ...],
     * 'order_direction' => ['ASC' or 'DESC'],
     * 'limit' => '1' or ...
     * // 1 вариант записи join (может быть несколько вложенных массивов):
     * 'join' =>
     * [
     *   'name_table' => [
     *      'table' => 'name_table',
     *      'fields' => ['column as alias_column', ...],
     *      'type' => 'left' or ...,
     *      'where' => ['column' => 'column_value', ...],
     *      'operand' => ['=', ...],
     *      'conditions' => ['OR', ...],
     *      'group_conditions' => ['AND' or ...],
     *      'on' => [
     *        'table' => 'name_table',
     *        'fields' => ['column', 'parent_column']
     *      ],
     * // 2 вариант записи join:
     *   [
     *      'table' => 'name_table',
     *      'fields' => ['column as alias_column', ...],
     *      'type' => 'left' or ...,
     *      'where' => ['column' => 'column_value', ...],
     *      'operand' => ['=', ...],
     *      'conditions' => ['AND', ...],
     *      'group_conditions' => ['AND' or ...]
     *      'on' => ['column', 'parent_column']
     *   ]
     *  ]
     * ]
     * @return int|bool|array|string результат запроса
     * @throws DbException ошибки
     */
    final public function select(string $table, ?array $set): int|bool|array|string
    {
        $fields = $this->creatFields($set, $table);
        $where = $this->creatWhere($set, $table);
        if (!$where) $new_wh = true;
        else $new_wh = false;
        $join_arr = $this->creatJoin($set, $table, $new_wh);
        $fields .= $join_arr['fields'] ?? '';
        $fields = rtrim($fields, ',');
        $where .= $join_arr['where'] ?? '';
        $join = $join_arr['join'] ?? '';
        $order = $this->creatOrder($set, $table) ?? '';
        $limit = isset($set['limit']) ? 'LIMIT ' . $set['limit'] : '';
        $query = "SELECT $fields FROM $table $join $where $order $limit";

        return $this->query($query);
    }

    /**
     * @param string $table название таблицы БД
     * @param array $set массив значений для построения запроса
     *  'fields' => ['column' => 'column_value', ...], если не указан, из $_POST
     *  разрешена передача MySQL-функции строкой, например NOW()
     *  'files' => ['name' => 'value', ...], массив files
     *  'except' => ['except1', ...], исключает данные из массива
     *  'return_id' => true|false возвращать id вставленной записи
     * @return array|bool|int|string
     * @throws DbException ошибки
     */
    final public function add(string $table, array $set = []): array|bool|int|string
    {
        $set = $this->getArr($set);
        if (!$set['fields'] && !$set['files']) return false;
        $insertArr = $this->creatInsert($set['fields'], $set['files'], $set['except']);
        $query = "INSERT INTO $table {$insertArr['fields']} VALUES {$insertArr['values']}";
        return $this->query($query, 'ins', $set['return_id']);

    }

    /**
     * @param string $table название таблицы БД
     * @param array $set массив значений для построения запроса
     *  'fields' => ['column' => 'column_value', ...], если не указан, из $_POST
     *  'files' => ['name' => 'value', ...], массив files
     *  'except' => ['except1', ...], исключает данные из массива
     *  'return_id' => true|false возвращать id вставленной записи
     * @return array|bool|int|string
     * @throws DbException ошибки
     */
    final public function edit(string $table, array $set = []): array|bool|int|string
    {
        $set = $this->getArr($set); //htmlspecialchars()
        if ($set['fields'] || $set['files']) {
            $where = '';
            if (empty($set['all_row'])) {
                $where = $this->creatWhere($set);
                if (!$where) {
                    $columns = $this->showColumns($table);
                    if (!$columns) return false;
                    if ($columns['pri'] && $set['fields'][$columns['pri']]) {
                        $where = "WHERE {$columns['pri']} = {$set['fields'][$columns['pri']]}";
                        unset($set['fields'][$columns['pri']]);
                    }
                }
            }
            $update = $this->creatUpdate($set['fields'], $set['files'], $set['except']);
            $query = "UPDATE $table SET $update $where";
            return $this->query($query, 'ins', $set['return_id']);
        }
        return false;
    }

    /**
     * @param string $table название таблицы БД
     * @param array $set массив значений для построения запроса
     * 'fields' => ['column', ...],
     * 'where' => ['column' => 'column_value', ...],
     * // 1 вариант записи join (может быть несколько вложенных массивов):
     * 'join' =>
     * [
     *   'name_table' => [
     *      'table' => 'name_table',
     *      'where' => ['column' => 'column_value', ...],
     *      'on' => [
     *        'table' => 'name_table',
     *        'fields' => ['column', 'parent_column']
     *      ],
     * // 2 вариант записи join:
     *   [
     *      'table' => 'name_table',
     *      'where' => ['column' => 'column_value', ...],
     *      'on' => ['column', 'parent_column']
     *   ]
     * ]
     * @return array|bool|int|string
     * @throws DbException
     */
    final public function delete(string $table, array $set = []): array|bool|int|string
    {
        $table = trim($table);
        $where = $this->creatWhere($set, $table);
        $columns = $this->showColumns($table);
        if (!$columns) return false;
        $set['fields'] = (!empty($set['fields']) && is_array($set['fields'])) ? $set['fields'] : null;
        if ($set['fields']) {
            if ($columns['pri']) {
                $key = array_search($columns['pri'], $set['fields']);
                if ($key !== false) unset($set['fields'][$key]);
            }
            $fields = [];
            foreach ($set['fields'] as $field) {
                $fields[$field] = $columns[$field]['Default'];
            }
            $update = $this->creatUpdate($fields, false, false);
            $query = "UPDATE $table SET $update $where";

        } else {
            $joinArr = $this->creatJoin($set, $table);
            $join = $joinArr['join'] ?? '';
            $tables = $joinArr['tables'] ?? '';
            $query = "DELETE $table$tables FROM $table $join $where";
        }
        return $this->query($query, 'default');
    }

    /**
     * @param string $table название таблицы БД
     * @return array массив с инфо о колонках таблицы БД
     * @throws DbException ошибки
     */
    final public function showColumns(string $table): array
    {
        $query = "SHOW COLUMNS FROM $table";
        $res = $this->query($query);
        $columns = [];
        if ($res) {
            foreach ($res as $row) {
                $columns[$row['Field']] = $row;
                if ($row['Key'] === 'PRI') $columns['pri'] = $row['Field'];
            }
        }
        return $columns;
    }

    /**
     * @return array массив с инфо о таблицах БД
     * @throws DbException
     */
    final public function showTables(): array
    {
        $query = "SHOW TABLES";
        $res = $this->query($query);
        $tables = [];
        if ($res) {
            foreach ($res as $table) {
                $tables[] = reset($table);
            }
        }
        return $tables;
    }

    /**
     * @param array $set
     * @return array
     */
    private function getArr(array $set): array
    {
        $set['fields'] = (!empty($set['fields']) && is_array($set['fields'])) ? $set['fields'] : $_POST; //htmlspecialchars()
        $set['files'] = (!empty($set['files']) && is_array($set['files'])) ? $set['files'] : false;
        $set['except'] = (!empty($set['except']) && is_array($set['except'])) ? $set['except'] : false;
        $set['return_id'] = $set['return_id'] ?? false;
        return $set;
    }
}