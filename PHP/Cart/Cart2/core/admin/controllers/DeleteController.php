<?php

namespace core\admin\controllers;


use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use JetBrains\PhpStorm\NoReturn;

/** @uses DeleteController */
class DeleteController extends BaseAdmin
{
    /**
     * @throws RouteException
     * @throws DbException
     */
    protected function inputData(): void
    {
        if (!$this->userId) $this->exec();
        $this->createTableData();
        $url = false;
        if (!empty($this->parameters[$this->table])) {
            if (is_numeric($this->parameters[$this->table]))
                $id = $this->num($this->parameters[$this->table]);
            else $id = $this->clearTags($this->parameters[$this->table]);
            if (!$id) throw new RouteException('Некорректный ID - ' . $id . ' при удалении таблицы ' . $this->table);
            $resData = $this->model->select($this->table, [
                'where' => [$this->columns['pri'][0] => $id],
            ]);
            if (is_array($resData)) {
                $this->data = $resData[0];

                if (count($this->parameters) > 1) $this->checkDeleteFile();

                $settings = $this->settings ?: Settings::instance();
                $files = $settings::get('templateFiles');
                foreach ($files as $file) {
                    $templates = $settings::get('templateArr');
                    foreach ($templates[$file] as $item) {
                        if (!empty($this->data[$item])) {
                            $fileData = json_decode($this->data[$item], true) ?: $this->data[$item];
                            if (is_array($fileData)) {
                                foreach ($fileData as $f)
                                    @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $f);
                            } else @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $fileData);
                        }
                    }
                }
                if (!empty($this->data['position'])) {
                    $where = [];
                    if (!empty($this->data['pid'])) {
                        $pos = $this->model->select($this->table, [
                            'fields' => ['COUNT(*) as count'],
                            'where' => ['pid' => $this->data['pid']],
                            'no_concat' => true,
                        ])[0]['count'];
                        $where = ['where' => 'pid'];
                    } else {
                        $pos = $this->model->select($this->table, [
                            'fields' => ['COUNT(*) as count'],
                            'no_concat' => true,
                        ])[0]['count'];
                    }
                    $this->model->updatePosition($this->table, 'position', [$this->columns['pri'][0] => $id], $pos, $where);
                }
                $tables = $this->model->showTables();
                if (in_array('old_alias', $tables)) {
                    $this->model->delete('old_alias', [
                        'where' => [
                            'table_name' => $this->table,
                            'table_id' => $id,
                        ],
                    ]);
                }
                $manyToMany = $settings::get('manyToMany');
                foreach ($manyToMany as $mTable => $tables) {
                    $targetKey = array_search($this->table, $tables);
                    if ($targetKey !== false) {
                        $this->model->delete($mTable, [
                            'where' => [$tables[$targetKey] . '_' . $this->columns['pri'][0] => $id],
                        ]);
                    }
                }
                $resDel = $this->model->delete($this->table, [
                    'where' => [$this->columns['pri'][0] => $id],
                ]);
                if ($resDel) $url = $this->path . 'show/' . $this->table;
            }
        }
        if ($url) $_SESSION['res']['answer'] = '<div class="success">' . $this->info['deleteSuccess'] . '</div>';
        else $_SESSION['res']['answer'] = '<div class="error">' . $this->info['deleteFail'] . '</div>';
        $this->redirect($url);
    }

    /**
     * @throws DbException
     */
    #[NoReturn] protected function checkDeleteFile(): void
    {
        unset($this->parameters[$this->table]);
        $updateFlag = false;
        foreach ($this->parameters as $row => $item) {
            $item = base64_decode($item);
            if (!empty($this->data[$row])) {
                $data = json_decode($this->data[$row], true);
                if ($data) {
                    foreach ($data as $name => $file) {
                        if ($item === $file) {
                            @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $item);
                            unset($data[$name]);
                            $updateFlag = true;
                            $this->data[$row] = $data ? json_encode($data) : 'NULL';
                            break;
                        }
                    }
                } elseif ($item === $this->data[$row]) {
                    @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $item);
                    $updateFlag = true;
                    $this->data[$row] = 'NULL';
                }
            }
        }
        if ($updateFlag) {
            $this->model->edit($this->table, [
                'fields' => $this->data
            ]);
            $_SESSION['res']['answer'] = '<div class="success">' . $this->info['editSuccess'] . '</div>';
        } else $_SESSION['res']['answer'] = '<div class="error">' . $this->info['editFail'] . '</div>';
        $this->redirect();
    }
}
