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
//        $searchWord = 'смарт';
//        $query = "
//SELECT
//    id AS id,
//    CASE WHEN goods.name <> '' THEN goods.name END AS NAME,
//    'goods' AS TABLE_NAME
//FROM goods
//WHERE
//    goods.name LIKE '%$searchWord%'
//    OR goods.content LIKE '%$searchWord%'
//    OR goods.short_content LIKE '%$searchWord%'
//    OR goods.img LIKE '%$searchWord%'
//    OR goods.gallery_img LIKE '%$searchWord%'
//    OR goods.alias LIKE '%$searchWord%'
//UNION
//SELECT
//    id AS id,
//    CASE WHEN catalog.name <> '' THEN catalog.name END AS NAME,
//    'catalog'  AS TABLE_NAME
//FROM catalog
//WHERE
//    catalog.name LIKE '%$searchWord%'
//    OR catalog.keywords LIKE '%$searchWord%'
//    OR catalog.description LIKE '%$searchWord%'
//    OR catalog.alias LIKE '%$searchWord%'
//    OR catalog.img LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN filters.filters_name <> '' THEN filters.filters_name END AS NAME,
//    'filters'  AS TABLE_NAME
//FROM filters
//WHERE
//    filters.filters_name LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN cat_filters.name <> '' THEN cat_filters.name END AS NAME,
//    'cat_filters' AS TABLE_NAME
//FROM cat_filters
//WHERE
//    cat_filters.name LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN manufacturer.name <> '' THEN manufacturer.name END AS NAME,
//    'manufacturer'  AS TABLE_NAME
//FROM manufacturer
//WHERE
//    manufacturer.name LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN color.name <> '' THEN color.name END AS NAME,
//    'color' AS TABLE_NAME
//FROM color
//WHERE
//   color.name LIKE '%$searchWord%'
//   OR color.img LIKE '%$searchWord%'
//   OR color.alias LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN sales.name <> '' THEN sales.name END AS NAME,
//    'sales'  AS TABLE_NAME
//FROM sales
//WHERE
//    sales.name LIKE '%$searchWord%'
//    OR sales.sub_title LIKE '%$searchWord%'
//    OR sales.img LIKE '%$searchWord%'
//    OR sales.external_url LIKE '%$searchWord%'
//    OR sales.short_content LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN news.name <> '' THEN news.name END AS NAME,
//    'news'  AS TABLE_NAME
//FROM news
//WHERE
//    news.name LIKE '%$searchWord%'
//    OR news.short_content LIKE '%$searchWord%'
//    OR news.content LIKE '%$searchWord%'
//    OR news.alias LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN information.name <> '' THEN information.name END AS NAME,
//    'information'  AS TABLE_NAME
//FROM information
//WHERE
//    information.content LIKE '%$searchWord%'
//    OR information.name LIKE '%$searchWord%'
//    OR information.alias LIKE '%$searchWord%'
//    OR information.keywords LIKE '%$searchWord%'
//    OR information.description LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN socials.name <> '' THEN socials.name END AS NAME,
//    'socials' AS TABLE_NAME
//FROM socials
//WHERE
//    socials.name LIKE '%$searchWord%'
//    OR socials.icons_svg LIKE '%$searchWord%'
//    OR socials.external_url LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN settings.name <> '' THEN settings.name END AS NAME,
//    'settings'  AS TABLE_NAME
//FROM settings
//WHERE
//    settings.name LIKE '%$searchWord%'
//    OR settings.keywords LIKE '%$searchWord%'
//    OR settings.description LIKE '%$searchWord%'
//    OR settings.address LIKE '%$searchWord%'
//    OR settings.phone LIKE '%$searchWord%'
//    OR settings.email LIKE '%$searchWord%'
//    OR settings.short_content LIKE '%$searchWord%'
//    OR settings.content LIKE '%$searchWord%'
//    OR settings.img_logo LIKE '%$searchWord%'
//    OR settings.promo_img LIKE '%$searchWord%'
//    OR settings.img_years LIKE '%$searchWord%'
//    OR settings.number_years LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN advantages.name <> '' THEN advantages.name END AS NAME,
//    'advantages'  AS TABLE_NAME
//FROM advantages
//WHERE
//    advantages.name LIKE '%$searchWord%'
//    OR advantages.img LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN delivery.name <> '' THEN delivery.name END AS NAME,
//    'delivery'  AS TABLE_NAME
//FROM delivery
//WHERE
//    delivery.name LIKE '%$searchWord%'
//    OR delivery.alias LIKE '%$searchWord%'
//
//UNION
//SELECT
//    id AS id,
//    CASE WHEN payments.name <> '' THEN payments.name END AS NAME,
//    'payments'  AS TABLE_NAME
//FROM payments
//WHERE
//    payments.name LIKE '%$searchWord%'
//    OR payments.alias LIKE '%$searchWord%'
//ORDER BY
//    NAME LIKE '%$searchWord%'
//DESC";

        if ($result) {
            foreach ($result as $index => $item) {
                $result[$index]['table_alias'] = $projectTables[$item['table_name']]['name'] ?? $item['table_name'];
                $result[$index]['path_edit'] = PATH . Settings::get('routes')['admin']['alias'] . '/edit/' . $item['table_name'] . '/' . $item['id'];
            }
        }

        return $result ?: [];
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