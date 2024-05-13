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
    protected string $contentMenu;
    protected string $contentCenter;
    protected ?Model $model = null;
    protected ?string $table = null;
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
    protected array $messages = [];
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
        if (!$this->messages) $this->messages = include $_SERVER['DOCUMENT_ROOT'] . PATH . Settings::get('messages') . 'informationMessages.php';
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
        $id = ($_POST[$this->columns['pri']]) ?? false;
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
            $_SESSION['res']['answer'] = '<div class="error">' . $this->messages['empty'] . ' ' . $answer . '</div>';
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
    protected function countChar(string $str, int|string $counter,string $answer, array $arr = []): void
    {
        if (mb_strlen($str) > $counter) {
            $_SESSION['res']['answer'] = '<div class="error">' . sprintf($this->messages['count'], $answer, $counter)
                . '</div>';
            $this->addSessionData($arr);
        }

    }

    /**
     * @throws DbException
     */
    protected function editData($returnId = false)
    {
        $id = false;
        $method = 'add';
        $where = [];
        if (isset($_POST[$this->columns['pri']])) {
            $id = $this->num($_POST[$this->columns['pri']]);
            if ($id) {
                $where = [$this->columns['pri'] => $id];
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
        $this->updateMenuPosition();
        $except = $this->checkExceptFields();

        $resId = $this->model->$method($this->table, [
            'files' => $this->fileArray,
            'where' => $where,
            'return_id' => true,
            'except' => $except,
        ]);
        if (!$id && $method === 'add') {
            $_POST[$this->columns['pri']] = $resId;
            $answerSuccess = $this->messages['addSuccess'];
            $answerFail = $this->messages['addFail'];
        } else {
            $answerSuccess = $this->messages['editSuccess'];
            $answerFail = $this->messages['editFail'];
        }
        $this->expansionBase(get_defined_vars());
        $this->checkAlias($_POST[$this->columns['pri']]);
        if ($resId) {
            $_SESSION['res']['answer'] = '<div class="success">' . $answerSuccess . '</div>';
            if (!$returnId) $this->redirect();
            return $_POST[$this->columns['pri']];
        } else {
            $_SESSION['res']['answer'] = '<div class="error">' . $answerFail . '</div>';
            if (!$returnId) $this->redirect();
            return null;
        }
    }

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
        if ($this->columns['alias']) {
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
                $where [$this->columns['pri']] = $id;
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
                    'where' => [$this->columns['pri'] => $id],
                ]);
                return true;
            }
        }
        return false;
    }

    protected function updateMenuPosition()
    {
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
                if (!$this->columns[$key]) $except = $key;
            }
        }
        return $except;
    }
}