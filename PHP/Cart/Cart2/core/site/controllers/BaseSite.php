<?php

namespace core\site\controllers;

use core\base\controllers\BaseController;
use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\site\models\Model;

abstract class BaseSite extends BaseController
{
    protected ?Model $model = null;
    protected ?string $table = null;
    protected array $set;
    protected array $menu;

    /**
     * @throws DbException
     */
    protected function inputData(): void
    {
        $this->init();
        if (!$this->model) $this->model = Model::instance();
        $this->set = $this->model->select('settings', [
            'order' => ['id'], 'limit' => 1
        ]);
        if ($this->set) $this->set = $this->set[0];
        $this->menu['catalog'] = $this->model->select('catalog', [
            'where' => ['visible' => '1', 'pid' => null],
            'order' => ['position']
        ]);
        $this->menu['information'] = $this->model->select('information', [
            'where' => ['visible' => 1, 'show_top_menu' => 1],
            'order' => ['position']
        ]);
    }

    /**
     * @throws RouteException
     */
    protected function outputData(): false|string
    {
        $this->content = $this->render();
        $this->header = $this->render(SITE_TEMPLATE . 'include/header');
        $this->footer = $this->render(SITE_TEMPLATE . 'include/footer');

        return $this->render(SITE_TEMPLATE . 'layout/default');
    }

    /**
     * @param string $img
     * @param bool $tag
     * @return string
     */
    protected function img(string $img = '', bool $tag = false): string
    {
        //        return PATH . IMAGES_DIR . $img;
        $path = '';
        if (!$img && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . IMAGES_DIR)) {
            $dir = scandir($_SERVER['DOCUMENT_ROOT'] . PATH . IMAGES_DIR);
            $imgArr = preg_grep('/' . $this->getController() . '\./i', $dir) ?:
                preg_grep('/default\./i', $dir);
            if ($imgArr) $path = PATH . IMAGES_DIR . array_shift($imgArr);
        } elseif ($img)
            $path = PATH . UPLOAD_DIR . $img;

        if ($tag) return '<img src="' . $path . '" alt="image" title="image">';

        return $path;
    }

    /**
     * @param array|string $url
     * @param array|string $queryString
     * @return string
     */
    protected function getUrl(array|string $url = '', array|string $queryString = ''): string
    {
        $str = '';
        if ($queryString) {
            if (is_array($queryString)) {
                foreach ($queryString as $key => $item) {
                    $str .= !$str ? '?' : '&';
                    if (is_array($item)) {
                        $key .= '[]';
                        foreach ($item as $v) $str .= $key . '=' . $v;
                    } else $str .= $key . '=' . $item;
                }
            } else {
                if (!str_starts_with($queryString, '?')) $str = '?' . $str;
                else $str .= $queryString;
            }
        }

        if (is_array($url)) {
            $urlStr = '';
            foreach ($url as $key => $item) {
                if ($item) {
                    if (!is_numeric($key)) $urlStr .= $key . '/' . $item . '/';
                    else $urlStr .= $item . '/';
                }
            }
            $url = $urlStr;
        }
        if (!$url || $url === '/')
            return PATH . $str;
        if (preg_match('/^\s*https?:\/\//i', $url))
            return $url . $str;

        return preg_replace('/\/{2,}/', '/', PATH . trim($url, '/') . END_SLASH . $str);
    }
}