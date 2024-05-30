<?php

namespace core\admin\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseControllers;
use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use JetBrains\PhpStorm\NoReturn;
use libraries\FileEdit;
use libraries\TextModify;


abstract class BaseAdmin extends BaseControllers
{
    /** @uses $notDelete */
    protected string $contentMenu;
    protected string $contentCenter;
    protected ?Model $model = null;
    protected ?string $table = null;
    protected ?object $settings = null;
    protected array $columns = [];
    protected array $foreignData = [];
    protected ?string $adminAlias = null;
    protected ?string $alias = null;
    protected ?string $path = null;
    protected array $menu = [];
    protected string|int|null $userId = null;
    protected array $fileArray = [];
    protected array $translate = [];
    protected string $title;
    protected array $info = [];
    protected array $blocks = [];
    protected ?string $formTemplates = null;
    protected array $templateArr = [];
    protected bool $notDelete = false;

    /**
     * @return void
     */
    protected function inputData(): void
    {
        $this->init(true);
        $this->title = 'Admin panel';
        if (!$this->model) $this->model = Model::instance();
        if (!$this->menu) $this->menu = Settings::get('projectTables');
        if (!$this->adminAlias) $this->adminAlias = Settings::get('routes')['admin']['alias'];
        if (!$this->path) $this->path = PATH . Settings::get('routes')['admin']['alias'] . '/';
        if (!$this->templateArr) $this->templateArr = Settings::get('templateArr');
        if (!$this->formTemplates) $this->formTemplates = Settings::get('formTemplates');
        if (!$this->info) $this->info = include $_SERVER['DOCUMENT_ROOT'] . PATH . Settings::get('info') . 'informationMessages.php';
        $this->sendNoCacheHeaders();
    }

    /**
     * @return void
     */
    protected function exec(): void
    {
        self::inputData();
    }

    /**
     * @return false|string
     * @throws RouteException
     */
    protected function outputData(): false|string
    {
        $this->header = $this->render(ADMIN_TEMPLATE . 'include/header');
        $this->footer = $this->render(ADMIN_TEMPLATE . 'include/footer');

        return $this->render(ADMIN_TEMPLATE . 'layout/default');
    }

    /**
     * @return void
     */
    protected function sendNoCacheHeaders(): void
    {
        header("Last-Modified: " . gmdate('D, d M Y H:i:s') . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Cache-Control: max-age=0");
        header("Cache-Control: post-check=0, pre-check=0");
    }

    /**
     * @param object|string|false $setting
     * @return void
     * @throws DbException
     */
    protected function createTableData(object|string|false $setting = false): void
    {
        if (!$this->table) {
            if ($this->parameters) $this->table = array_keys($this->parameters)[0];
            else {
                if (!$setting) $setting = Settings::instance();
                $this->table = $setting::get('defaultTable');
            }
        }
        $this->columns = $this->model->showColumns($this->table);
        if (!$this->columns) new RouteException('Не найдены поля в таблице - ' . $this->table, 2);

    }

    /**
     * @param object|string|false $settings
     * @return void
     * @throws DbException
     * @throws RouteException
     */
    protected function createForeignData(object|string|false $settings = false): void
    {
        if (!$settings) $settings = Settings::instance();
        $rootItems = $settings::get('rootItems');
        $keys = $this->model->showForeignKeys($this->table);
        if ($keys) {
            foreach ($keys as $item) {
                $this->createForeignProperty($item, $rootItems);
            }
        } elseif (isset($this->columns['pid'])) {
            $arr['COLUMN_NAME'] = 'pid';
            $arr['REFERENCED_COLUMN_NAME'] = $this->columns['pri'][0];
            $arr['REFERENCED_TABLE_NAME'] = $this->table;
            $this->createForeignProperty($arr, $rootItems);
        }
    }

    /**
     * @param array $columnsTable
     * @param array $rootItems
     * @return void
     * @throws DbException
     * @throws RouteException
     */
    protected function createForeignProperty(array $columnsTable, array $rootItems): void
    {
        if (in_array($this->table, $rootItems['tables'])) {
            $this->foreignData[$columnsTable['COLUMN_NAME']][0]['id'] = 'NULL';
            $this->foreignData[$columnsTable['COLUMN_NAME']][0]['name'] = $rootItems['name'];
        }
        $orderData = $this->createOrderData($columnsTable['REFERENCED_TABLE_NAME']);

        $where = [];
        $operand = [];
        if ($this->data) {
            if ($columnsTable['REFERENCED_TABLE_NAME'] === $this->table) {
                $where[$this->columns['pri'][0]] = $this->data[$this->columns['pri'][0]];
                $operand[] = '<>';
            }
        }
        $foreign = $this->model->select($columnsTable['REFERENCED_TABLE_NAME'], [
            'fields' => [$columnsTable['REFERENCED_COLUMN_NAME'] . ' as id', $orderData['name'], $orderData['pid']],
            'where' => $where,
            'operand' => $operand,
            'order' => $orderData['order'],
        ]);
        if ($foreign) {
            if (!empty($this->foreignData[$columnsTable['COLUMN_NAME']])) {
                foreach ($foreign as $value) {
                    $this->foreignData[$columnsTable['COLUMN_NAME']][] = $value;
                }
            } else $this->foreignData[$columnsTable['COLUMN_NAME']] = $foreign;
        }
    }

    /**
     * @param object|string|false $settings
     * @return void
     * @throws DbException
     */
    protected function createMenuPosition(object|string|false $settings = false): void
    {
        if (isset($this->columns['position'])) {
            if (!$settings) $settings = Settings::instance();
            $rootItems = $settings::get('rootItems');
            $where = '';
            if (isset($this->columns['pid'])) {
                if (in_array($this->table, $rootItems['tables'])) $where = 'pid IS NULL OR pid = 0';
                else {
                    $parent = $this->model->showForeignKeys($this->table, 'pid')[0];
                    if ($parent) {
                        if ($this->table === $parent['REFERENCED_TABLE_NAME'])
                            $where = 'pid IS NULL OR pid = 0';
                        else {
                            $columns = $this->model->showColumns($parent['REFERENCED_TABLE_NAME']);
                            if (isset($columns['pid'])) $order[] = 'pid';
                            else $order[] = $parent['REFERENCED_COLUMN_NAME'];
                            $id = $this->model->select($parent['REFERENCED_TABLE_NAME'], [
                                'fields' => [$parent['REFERENCED_COLUMN_NAME']],
                                'order' => $order,
//                                'order_direction' => ['DESC'],
                                'limit' => '1',
                            ])[0][$parent['REFERENCED_COLUMN_NAME']];
                            if ($id) $where = ['pid' => $id];
                        }
                    } else $where = 'pid IS NULL OR pid =0';
                }
            }
            $menuPosition = $this->model->select($this->table, [
                    'fields' => ['COUNT(*) as count'],
                    'where' => $where,
                    'no_concat' => true,
                ])[0]['count'] + (int)!$this->data;
            for ($i = 1; $i <= $menuPosition; $i++) {
                $this->foreignData['position'][$i - 1]['id'] = $i;
                $this->foreignData['position'][$i - 1]['name'] = $i;
            }
        }
    }

    /**
     * @param array $args
     * @param object|string|bool $settings
     * @return false|mixed
     */

    protected function expansionBase(array $args = [], object|string|bool $settings = false): mixed
    {
        $fileName = explode('_', $this->table);
        $className = '';
        foreach ($fileName as $name)
            $className .= ucfirst($name);

        if (!$settings) $path = Settings::get('expansion');
        elseif (is_object($settings)) $path = $settings::get('expansion');
        else $path = $settings;

        $class = $path . $className . 'Expansion';
        if (is_readable($_SERVER['DOCUMENT_ROOT'] . PATH . $class . '.php')) {
            $class = str_replace('/', '\\', $class);
            $exp = $class::instance();
            foreach ($this as $name => $value) {
                if (!$this->model)
                    $exp->$name = &$this->$value;
            }
            return $exp->expansion($args);
        } else {
            $file = $_SERVER['DOCUMENT_ROOT'] . PATH . $this->table . '.php';
            if (is_readable($file)) return include $file;
        }
        return false;
    }

    /**
     * @param object|string|bool $setting
     * @return void
     */
    protected function createOutputData(object|string|bool $setting = false): void
    {
        if (!$setting) $setting = Settings::instance();
        $blocks = $setting::get('blockNeedle');
        $this->translate = $setting::get('translate');
        if (!$blocks || !is_array($blocks)) {
            foreach ($this->columns as $name => $item) {
                if ($name === 'pri') continue;
                if (!isset($this->translate[$name])) $this->translate[$name][] = $name;
                $this->blocks[0][] = $name;
            }
            return;
        }
        $default = array_keys($blocks)[0];

        foreach ($this->columns as $column => $item) {
            if ($column === 'pri') continue;
            $insert = false;
            foreach ($blocks as $block => $value) {
                if (!array_key_exists($block, $this->blocks)) $this->blocks[$block] = [];
                if (in_array($column, $value)) {
                    $this->blocks[$block][] = $column;
                    $insert = true;
                    break;
                }
            }
            if (!$insert) $this->blocks[$default][] = $column;
            if (!isset($this->translate[$column])) $this->translate[$column][] = $column;
        }
    }

    /**
     * @param object|string|bool $setting
     * @return void
     */
    protected function createRadio(object|string|bool $setting = false): void
    {
        if (!$setting) $setting = Settings::instance();
        $radio = $setting::get('radio');
        if ($radio) {
            foreach ($radio as $name => $item)
                if ($item) $this->foreignData[$name] = $item;
        }
    }

    /**
     * @param object|string|bool $settings
     * @return void
     * @throws DbException
     */
    protected function checkPost(object|string|bool $settings = false): void
    {
        $this->table = $this->clearTags($_POST['table']);
        unset($_POST['table']);
        if ($this->table) {
            $this->createTableData($settings);
            $this->clearPostFields($settings);
            $this->editData();
        }
    }

    /**
     * @param object|string|bool $settings
     * @param array $arr
     * @return void
     */
    protected function clearPostFields(object|string|bool $settings, array &$arr = []): void
    {
        if (!$arr) $arr = &$_POST;
        if (!$settings) $settings = Settings::instance();
        $id = ($_POST[$this->columns['pri'][0]]) ?? false;
        $validate = $settings::get('validation');
        if (!$this->translate) $this->translate = $settings::get('translate');
        foreach ($arr as $key => $item) {
            if (is_array($item)) {
                $this->clearPostFields($settings, $item);
            } else {
                if (is_numeric($item)) $arr[$key] = $this->num($item);
                if (!empty($validate[$key])) {
                    $answer = $this->translate[$key][0] ?? $key;

                    if (!empty($validate[$key]['crypt'])) {
                        if ($id) {
                            if (empty($item)) {
                                unset($arr[$key]);
                                continue;
                            }
                            $arr[$key] = md5($item);
                        }
                    }
                    if (!empty($validate[$key]['empty']))
                        $this->emptyFields($item, $answer, $arr);
                    if (!empty($validate[$key]['trim']))
                        $arr[$key] = trim($item);
                    if (!empty($validate[$key]['int']))
                        $arr[$key] = $this->num($item);
                    if (!empty($validate[$key]['count']))
                        $this->countChar($item, $validate[$key]['count'], $answer, $arr);
                }
            }
        }
    }

    /**
     * @param string $str
     * @param string $answer
     * @param array $arr
     * @return void
     */
    protected function emptyFields(string $str, string $answer, array $arr = []): void
    {
        if (empty($str)) {
            $_SESSION['res']['answer'] = '<div class="error">' . $this->info['empty'] . ' ' . $answer . '</div>';
            $this->addSessionData($arr);
        }
    }

    /**
     * @param array|null $arr
     * @return void
     */
    #[NoReturn] protected function addSessionData(?array $arr): void
    {
        if (!$arr) $arr = $_POST;
        foreach ($arr as $key => $item)
            $_SESSION['res'][$key] = $item;
        $this->redirect();
    }

    /**
     * @param string $str
     * @param int|string $counter
     * @param string $answer
     * @param array $arr
     * @return void
     */
    protected function countChar(string $str, int|string $counter, string $answer, array $arr = []): void
    {
        if (mb_strlen($str) > $counter) {
            $_SESSION['res']['answer'] = '<div class="error">' . sprintf($this->info['count'], $answer, $counter)
                . '</div>';
            $this->addSessionData($arr);
        }

    }

    /**
     * @param bool $returnId
     * @return mixed|string|null
     * @throws DbException
     */

    protected function editData(bool $returnId = false): mixed
    {
        $id = false;
        $method = 'add';
        $where = [];
        if (isset($_POST[$this->columns['pri'][0]])) {
            $id = $this->num($_POST[$this->columns['pri'][0]]);
            if ($id) {
                $where = [$this->columns['pri'][0] => $id];
                $method = 'edit';
            }
        }
        foreach ($this->columns as $key => $item) {
            if (isset($item['Type'])) {
                if ($item['Type'] === 'date' || $item['Type'] === 'datetime') {
                    if (empty($_POST[$key])) $_POST[$key] = 'NOW()';
                }
            }
        }
        $this->createFile();
        $this->createAlias($id);
        $this->updateMenuPosition($id);
        $except = $this->checkExceptFields();

        $resId = $this->model->$method($this->table, [
            'files' => $this->fileArray,
            'where' => $where,
            'return_id' => true,
            'except' => $except,
        ]);
        if (!$id && $method === 'add') {
            $_POST[$this->columns['pri'][0]] = $resId;
            $answerSuccess = $this->info['addSuccess'];
            $answerFail = $this->info['addFail'];
        } else {
            $answerSuccess = $this->info['editSuccess'];
            $answerFail = $this->info['editFail'];
        }
        $resMany = $this->checkManyToMany();
        $this->expansionBase(get_defined_vars());
        $resAlias = $this->checkAlias($_POST[$this->columns['pri'][0]]);
        if ($resId || $resMany || $resAlias) {
            $_SESSION['res']['answer'] = '<div class="success">' . $answerSuccess . '</div>';
            if (!$returnId) $this->redirect();
            return $_POST[$this->columns['pri'][0]];
        } else {
            $_SESSION['res']['answer'] = '<div class="error">' . $answerFail . '</div>';
            if (!$returnId) $this->redirect();
            return null;
        }
    }

    /**
     * @return void
     */
    protected function createFile(): void
    {
        $fileEdit = new FileEdit();
        $this->fileArray = $fileEdit->addFile();
    }

    /**
     * @param int|string|false $id
     * @return void
     * @throws DbException
     */
    protected function createAlias(int|string|false $id = false): void
    {
        if (isset($this->columns['alias'])) {
            $aliasStr = '';
            if (empty($_POST['alias'])) {
                if ($_POST['name']) $aliasStr = $this->clearTags($_POST['name']);
                else {
                    foreach ($_POST as $key => $item) {
                        if (str_contains($key, 'name') && $item) {
                            $aliasStr = $this->clearTags($item);
                            break;
                        }
                    }
                }
            } else $aliasStr = $_POST['alias'] = $this->clearTags($_POST['alias']);

            $textModify = new TextModify;
            $alias = $textModify->translit($aliasStr);

            $where ['alias'] = $alias;
            $operand[] = '=';
            if ($id) {
                $where [$this->columns['pri'][0]] = $id;
                $operand[] = '<>';
            }
            $resAlias = $this->model->select($this->table, [
                'fields' => 'alias',
                'where' => $where,
                'operand' => $operand,
                'limit' => '1'
            ]);
            if (!$resAlias) $_POST['alias'] = $alias;
            else {
                $this->alias = $alias;
                $_POST['alias'] = '';
            }
            if ($_POST['alias'] && $id) {
                method_exists($this, 'checkOldAlias') && $this->checkOldAlias($id);
            }
        }
    }


    /**
     * @param int|string $id
     * @return bool
     * @throws DbException
     */
    protected function checkAlias(int|string $id): bool
    {
        if ($id) {
            if ($this->alias) {
                $this->alias .= '-' . $id;
                $this->model->edit($this->table, [
                    'fields' => ['alias' => $this->alias],
                    'where' => [$this->columns['pri'][0] => $id],
                ]);
                return true;
            }
        }
        return false;
    }

    protected function updateMenuPosition(int|false $id = false)
    {
        if (isset($_POST['position'])) {
            $where = false;
            if ($id && $this->columns['pri'][0]) $where = [$this->columns['pri'][0] => $id];

            $updateRows = !empty($_POST['pid']) ? ['where' => 'pid'] : [];
            $this->model->updatePosition($this->table, 'position', $where, $_POST['position'], $updateRows);


        }


    }

    /**
     * @param array $arr
     * @return array|int|string
     */
    protected function checkExceptFields(array $arr = []): array|int|string
    {
        if (!$arr) $arr = $_POST;
        $except = [];
        if ($arr) {
            foreach ($arr as $key => $item) {
                if (!isset($this->columns[$key])) $except[] = $key;
            }
        }
        return $except;
    }

    /**
     * @param $table
     * @return array
     * @throws DbException
     * @throws RouteException
     */

    protected function createOrderData($table): array
    {
        $columns = $this->model->showColumns($table);
        if (empty($columns)) throw new RouteException ('Отсутствуют поля в таблице ' . $table);
        $name = '';
        $orderName = '';
        if (isset($columns['name'])) $orderName = $name = 'name';
        else {
            foreach ($columns as $key => $value) {
                if (str_contains($key, 'name')) {
                    $orderName = $key;
                    $name .= $key . ' as name';
                }
            }
            if (!$name) $name = $columns['pri'][0] . ' as name';
        }
        $pid = '';
        $order = [];
        if (isset($columns['pid'])) $order[] = $pid = 'pid';

        if (isset($columns['position'])) $order[] = 'position';
        else $order[] = $orderName;

        return compact('name', 'pid', 'order', 'columns');
    }

    /**
     * @throws RouteException
     * @throws DbException
     */
    protected function createManyToMany(object|bool $settings = false): void
    {
        if (!$settings) $settings = $this->settings ?: Settings::instance();
        $manyToMany = $settings::get('manyToMany');
        $blocks = $settings::get('blockNeedle');
        if ($manyToMany) {
            foreach ($manyToMany as $mTable => $tables) {
                $targetKey = array_search($this->table, $tables);
                if ($targetKey !== false) {
                    $otherKey = $targetKey ? 0 : 1;
                    $checkboxList = $settings::get('templateArr')['checkboxlist'];
                    if (!$checkboxList || !in_array($tables[$otherKey], $checkboxList)) continue;
                    if (!isset($this->translate[$tables[$otherKey]])) {
                        if (isset($settings::get('projectTables')[$tables[$otherKey]])) {
                            $this->translate[$tables[$otherKey]] = [$settings::get('projectTables')[$tables[$otherKey]]['name']];
                        }
                    }
                    $orderData = $this->createOrderData($tables[$otherKey]);
                    $insert = false;
                    if ($blocks) {
                        foreach ($blocks as $key => $item) {
                            if (in_array($tables[$otherKey], $item)) {
                                $this->blocks[$key][] = $tables[$otherKey];
                                $insert = true;
                                break;
                            }
                        }
                    }
                    if (!$insert) $this->blocks[array_keys($this->blocks)[0]][] = $tables[$otherKey];
                    $foreign = [];
                    if ($this->data) {
                        $res = $this->model->select($mTable, [
                            'fields' => [$tables[$otherKey] . '_' . $orderData['columns']['pri'][0]],
                            'where' => [$this->table . '_' . $this->columns['pri'][0] => $this->data[$this->columns['pri'][0]]],
                        ]);
                        if ($res) {
                            foreach ($res as $item) {
                                $foreign[] = $item[$tables[$otherKey] . '_' . $orderData['columns']['pri'][0]];
                            }
                        }
                    }
                    if (isset($tables['type'])) {
                        $data = $this->model->select($tables[$otherKey], [
                            'fields' => [$orderData['columns']['pri'][0] . ' as id', $orderData['name'], $orderData['pid']],
                            'order' => $orderData['order'],
                        ]);
                        if ($data) {
                            $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['name'] = 'Выбрать';
                            foreach ($data as $value) {
                                if ($tables['type'] === 'root' && $orderData['pid']) {
                                    if ($value[$orderData['pid']] === null)
                                        $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['sub'][] = $value;
                                } elseif ($tables['type'] === 'child' && $orderData['pid']) {
                                    if ($value[$orderData['pid']] !== null)
                                        $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['sub'][] = $value;
                                } else $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['sub'][] = $value;

                                if (in_array($value['id'], $foreign))
                                    $this->data[$tables[$otherKey]][$tables[$otherKey]][] = $value['id'];
                            }
                        }
                    } elseif ($orderData['pid']) {
                        $parent = $tables[$otherKey];
                        $keys = $this->model->showForeignKeys($tables[$otherKey]);
                        if ($keys) {
                            foreach ($keys as $key) {
                                if ($key['COLUMN_NAME'] === 'pid') {
                                    $parent = $key['REFERENCED_TABLE_NAME'];
                                    break;
                                }
                            }
                        }
                        if ($parent === $tables[$otherKey]) {
                            $data = $this->model->select($tables[$otherKey], [
                                'fields' => [$orderData['columns']['pri'][0] . ' as id', $orderData['name'], $orderData['pid']],
                                'order' => $orderData['order'],
                            ]);
                            if ($data) {
                                while (($key = key($data)) !== null) {
                                    if (!$data[$key]['pid']) {
                                        $this->foreignData[$tables[$otherKey]][$data[$key]['id']]['name'] = $data[$key]['name'];

                                        unset($data[$key]);
                                        reset($data);
                                        //continue;
                                    } else {
                                        if (isset($this->foreignData[$tables[$otherKey]][$data[$key][$orderData['pid']]])) {
                                            $this->foreignData[$tables[$otherKey]][$data[$key][$orderData['pid']]]['sub'][$data[$key]['id']] = $data[$key];
                                            if (in_array($data[$key]['id'], $foreign))
                                                $this->data[$tables[$otherKey]][$data[$key][$orderData['pid']]][] = $data[$key]['id'];

                                            unset($data[$key]);
                                            reset($data);
                                            continue;
                                        } else {
                                            foreach ($this->foreignData[$tables[$otherKey]] as $id => $item) {
                                                if (isset($item['sub'][$data[$key][$orderData['pid']]])) {
                                                    $this->foreignData[$tables[$otherKey]][$id]['sub'][$data[$key]['id']] = $data[$key];
                                                    if (in_array($data[$key]['id'], $foreign))
                                                        $this->data[$tables[$otherKey]][$id][] = $data[$key]['id'];
                                                    unset($data[$key]);
                                                    reset($data);
                                                    continue 2;
                                                }
                                            }
                                        }
                                        next($data);
                                    }
                                }
                            }
                        } else {
                            $parentOrderData = $this->createOrderData($parent);
                            $data = $this->model->select($parent, [
                                'fields' => [$parentOrderData['name']],
                                'join' => [
                                    $tables[$otherKey] => [
                                        'fields' => [$orderData['columns']['pri'][0] . ' as id', $orderData['name']],
                                        'on' => [$parentOrderData['columns']['pri'][0], $orderData['pid']],
                                    ],
                                ],
                                'join_structure' => true,
                            ]);
                            foreach ($data as $key => $item) {
                                if (isset($item['join'][$tables[$otherKey]])) {
                                    $this->foreignData[$tables[$otherKey]][$key]['name'] = $item['name'];
                                    $this->foreignData[$tables[$otherKey]][$key]['sub'] = $item['join'][$tables[$otherKey]];
                                    foreach ($item['join'][$tables[$otherKey]] as $value) {
                                        if (in_array($value['id'], $foreign))
                                            $this->data[$tables[$otherKey]][$key][] = $value['id'];
                                    }
                                }
                            }
                        }
                    } else {
                        $data = $this->model->select($tables[$otherKey], [
                            'fields' => [$orderData['columns']['pri'][0] . ' as id', $orderData['name'], $orderData['pid']],
                            'order' => $orderData['order'],
                        ]);
                        if ($data) {
                            $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['name'] = 'Выбрать';
                            foreach ($data as $item) {
                                $this->foreignData[$tables[$otherKey]][$tables[$otherKey]]['sub'][] = $item;
                                if (in_array($item['id'], $foreign))
                                    $this->data[$tables[$otherKey]][$tables[$otherKey]][] = $item['id'];
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @throws DbException
     */
    protected function checkManyToMany(object|bool $settings = false) : bool|int
    {
        if (!$settings) $settings = $this->settings ?: Settings::instance();
        $manyToMany = $settings::get('manyToMany');
        $res = false;
        if ($manyToMany) {
            foreach ($manyToMany as $mTable => $tables) {
                $targetKey = array_search($this->table, $tables);
                if ($targetKey !== false) {
                    $otherKey = $targetKey ? 0 : 1;
                    $checkboxList = $settings::get('templateArr')['checkboxlist'];
                    if (!$checkboxList || !in_array($tables[$otherKey], $checkboxList)) continue;
                    $columns = $this->model->showColumns($tables[$otherKey]);
                    $targetRow = $this->table . '_' . $this->columns['pri'][0];
                    $otherRow = $tables[$otherKey] . '_' . $columns['pri'][0];
                    $this->model->delete($mTable, [
                        'where' => [$targetRow => $_POST[$this->columns['pri'][0]]],
                    ]);
                    if (isset($_POST[$tables[$otherKey]])) {
                        $insertArr = [];
                        $i = 0;
                        foreach ($_POST[$tables[$otherKey]] as $value) {
                            foreach ($value as $item) {
                                if ($item) {
                                    $insertArr[$i][$targetRow] = $_POST[$this->columns['pri'][0]];
                                    $insertArr[$i][$otherRow] = $item;
                                    $i++;
                                }
                            }
                        }
                        if (!empty($insertArr)) {
                            $res = $this->model->add($mTable, [
                                'fields' => $insertArr,
                                'return_id' => true,
                            ]);
                        }
                    }
                }
            }
        }
       return $res;
    }
}