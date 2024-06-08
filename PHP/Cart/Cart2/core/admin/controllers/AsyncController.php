<?php

namespace core\admin\controllers;

use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use DOMException;

/**
 * @uses AsyncController
 */
class AsyncController extends BaseAdmin
{
    /**
     * @return array
     * @throws DOMException
     * @throws DbException
     * @throws RouteException
     * @uses async
     */
    public function async(): array
    {
        if (isset($this->asyncData['ajax'])) {
            $this->asyncData = $this->clearTags($this->asyncData);
            if (!$this->userId) $this->exec();
            switch ($this->asyncData['ajax']) {
                case 'sitemap':
                    return (new SitemapController())->inputAsyncData($this->asyncData['linksCounter']);
                case 'editData':
                    $_POST['return_id'] = true;
                    return ['url' => $this->checkPost()];
                case 'changeParent':
                    return ['pos' => $this->changeParent() + $this->asyncData['iteration']];
                case 'search':
                    return $this->search();
                default :
                    return ['success' => '0', 'message' => 'Ajax variable is invalid'];
            }
        } else return ['success' => '0', 'message' => 'No ajax variable'];
    }

    /**
     * @return int|null
     * @throws DbException
     */
    protected function changeParent(): null|int
    {
        return $this->model->select($this->asyncData['table'], [
            'fields' => ['COUNT(*) as count'],
            'where' => ['pid' => $this->asyncData['pid']],
            'no_concat' => true,
        ])[0]['count'];
    }

    /**
     * @return array
     * @throws DbException
     * @throws RouteException
     */
    protected function search(): array
    {
        $data = $this->asyncData['data'];
        $table = $this->asyncData['table'];
        $link = 20;
        return $this->model->searchData($data, $table, $link);
    }

}