<?php

namespace core\base\models;

use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
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
     * @param array $set массив значений для построения запроса
     * 'fields' => ['column', ...],
     * 'no_concat' => false or true не присоединять имя таблицы, false - присоединять
     * 'where' => ['column' => 'column_value', ...],
     * 'operand' => ['=', '<>', ...],
     * 'condition' => ['AND', 'OR', ...],
     * 'order' => ['column', ...],
     * 'order_direction' => ['ASC' or 'DESC'],
     * 'limit' => '1' or ...
     * 'join_structure' => false or true - возвращать обработанный массив данных
     * // 1 вариант записи join (может быть несколько вложенных массивов):
     * 'join' =>
     * ['name_table' => [
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
     *   ['table' => 'name_table',
     *      'fields' => ['column as alias_column', ...],
     *      'type' => 'left' or ...,
     *      'where' => ['column' => 'column_value', ...],
     *      'operand' => ['=', ...],
     *      'conditions' => ['AND', ...],
     *      'group_conditions' => ['AND' or ...]
     *      'on' => ['column', 'parent_column']
     *   ]]]
     * @return int|bool|array|string результат запроса
     * @throws DbException ошибки
     */
    final public function select(string $table, array $set = []): int|bool|array|string
    {
        $fields = $this->createFields($set, $table);
        $where = $this->createWhere($set, $table);
        $join_arr = $this->createJoin($set, $table, !$where);
        $fields .= $join_arr['fields'] ?? '';
        $fields = rtrim($fields, ', ');
        $where .= $join_arr['where'] ?? '';
        $join = $join_arr['join'] ?? '';
        $order = $this->createOrder($set, $table) ?? '';
        $limit = isset($set['limit']) ? 'LIMIT ' . $set['limit'] : '';
        $query = "SELECT $fields FROM $table $join $where $order $limit";

        if (!empty($set['return_query'])) return $query;
        $res = $this->query(trim($query));
        if (!empty($set['join_structure']) && $res) {
            $res = $this->joinStructure($res, $table);
        }
        return $res;
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
        $insertArr = $this->createInsert($set['fields'], $set['files'], $set['except']);
        $query = "INSERT INTO $table {$insertArr['fields']} VALUES {$insertArr['values']}";
        $return_id = !empty($set['return_id']) ? $set['return_id'] : false;

        return $this->query(trim($query), 'ins', $return_id);

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
                $where = $this->createWhere($set);
                if (!$where) {
                    $columns = $this->showColumns($table);
                    if (!$columns) return false;
                    if (isset($columns['pri'][0]) && $set['fields'][$columns['pri'][0]]) {
                        $where = "WHERE {$columns['pri'][0]} = {$set['fields'][$columns['pri'][0]]}";
                        unset($set['fields'][$columns['pri'][0]]);
                    }
                }
            }
            $update = $this->createUpdate($set['fields'], $set['files'], $set['except']);
//            $return_id = !empty($set['return_id']) ? $set['return_id'] : false;
            $query = "UPDATE $table SET $update $where";
            return $this->query(trim($query), 'ins');
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
     * @noinspection SqlWithoutWhere
     */
    final public function delete(string $table, array $set = []): array|bool|int|string
    {
        $table = trim($table);
        $where = $this->createWhere($set, $table);
        $columns = $this->showColumns($table);
        if (!$columns) return false;
        $set['fields'] = (!empty($set['fields']) && is_array($set['fields'])) ? $set['fields'] : null;
        if ($set['fields']) {
            if ($columns['pri']) {
                $key = array_search($columns['pri'][0], $set['fields']);
                if ($key !== false) unset($set['fields'][$key]);
            }
            $fields = [];
            foreach ($set['fields'] as $field) {
                $fields[$field] = $columns[$field]['Default'];
            }
            $update = $this->createUpdate($fields, false, false);
            $query = "UPDATE $table SET $update $where";

        } else {
            $joinArr = $this->createJoin($set, $table);
            $join = $joinArr['join'] ?? '';
            $tables = $joinArr['tables'] ?? '';
            $query = "DELETE $table$tables FROM $table $join $where";
        }
        return $this->query(trim($query), 'default');
    }

    /**
     * @throws DbException
     */
    public function buildUnion($table, $set): static
    {
        if ((array_key_exists('fields', $set)) && $set['fields'] === null) return $this;
        if (empty($set['fields'])) {
            $set['fields'] = [];
            $columns = $this->showColumns($table);
            unset($columns['pri']);
            foreach ($columns as $column => $item)
                $set['fields'][] = $column;
        }
        $this->union[$table] = $set;
        $this->union[$table]['return_query'] = true;
        return $this;
    }

    /**
     * @param array $set
     * 'type' => 'all',
     * 'pagination' => [],
     * 'limit' => 5,
     * 'order' => $order,
     * 'order_direction' => $orderDirection,
     * @return array|bool|int|string
     * @throws DbException
     * @throws RouteException
     */
    public function getUnion(array $set = []): array|bool|int|string
    {
        if (!$this->union) throw new RouteException('Отсутствует свойство "union" модели ' . $this::class);
        $unionType = ' UNION ' . (!empty($set['type']) ? strtoupper($set['type']) . ' ' : '');
        $maxCount = 0;
        $maxTableCount = '';
        foreach ($this->union as $key => $item) {
            $count = count($item['fields']);
            $joinFields = '';
            if (!empty($item['join'])) {
                foreach ($item['join'] as $table => $data) {
                    if (array_key_exists('fields', $data) && $data['fields']) {
                        $count += count($data['fields']);
                        $joinFields = $table;
                    } elseif (array_key_exists('fields', $data) || $data['fields'] === null) {
                        $columns = $this->showColumns($table);
                        unset($columns['pri']);
                        $count += count($columns);
                        foreach ($columns as $field => $value)
                            $this->union[$key]['join'][$table]['fields'][] = $field;
                        $joinFields = $table;
                    }
                }
            } else {
                $this->union[$key]['no_concat'] = true;
            }
            if ($count > $maxCount || ($count == $maxCount && $joinFields)) {
                $maxCount = $count;
                $maxTableCount = $key;
            }
            $this->union[$key]['lastJoinTable'] = $joinFields;
            $this->union[$key]['countFields'] = $count;
        }
        $query = '';
        if ($maxCount && $maxTableCount) {
            $query .= '(' . $this->select($maxTableCount, $this->union[$maxTableCount]) . ')';
            unset($this->union[$maxTableCount]);
        }
        foreach ($this->union as $key => $item) {
            if (isset($item['countFields']) && $item['countFields'] < $maxCount) {
                for ($i = 0; $i < $maxCount - $item['countFields']; $i++) {
                    if ($item['lastJoinTable']) $item['join'][$item['lastJoinTable']]['fields'][] = null;
                    else $item['fields'][] = null;
                }
            }
            $query && $query .= $unionType;
            $query .= '(' . $this->select($key, $item) . ')';
        }
        $order = $this->createOrder($set);
        $limit = !empty($set['limit']) ? 'LIMIT ' . $set['limit'] : '';
        if (method_exists($this, 'createPagination'))
            $this->createPagination($set, "($query)", $limit);
        $query .= " $order $limit";
        $this->union = [];
        return $this->query(trim($query));
    }

    /**
     * @param string $table название таблицы БД
     * @return array массив с инфо о колонках таблицы БД
     * @throws DbException ошибки
     */
    final public function showColumns(string $table): array
    {
        if (empty($this->columnsTables[$table])) {
            $arrTable = $this->createTableAlias($table);
            if (!empty($this->columnsTables[$arrTable['table']])) {
                return $this->columnsTables[$arrTable['alias']] = $this->columnsTables[$arrTable['table']];
            }
            $query = "SHOW COLUMNS FROM {$arrTable['table']}";
            $res = $this->query($query);
            $this->columnsTables[$arrTable['table']] = [];
            if ($res) {
                foreach ($res as $row) {
                    $this->columnsTables[$arrTable['table']][$row['Field']] = $row;
                    if ($row['Key'] === 'PRI') $this->columnsTables[$arrTable['table']]['pri'][] = $row['Field'];
                }
            }
        }
        if (isset($arrTable) && $arrTable['table'] !== $arrTable['alias']) {
            return $this->columnsTables[$arrTable['alias']] = $this->columnsTables[$arrTable['table']];
        }
        return $this->columnsTables[$table];
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