<?php

namespace core\admin\controllers;

use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use DOMException;
use libraries\FileEdit;

/**
 * @uses AsyncController
 */
class AsyncController extends BaseAdmin
{
    protected array $asyncData = [];

    /**
     * @param $data
     * @return array
     * @throws DOMException
     * @throws DbException
     * @throws RouteException
     * @uses async
     */
    public function async($data): array
    {
        $this->asyncData = $data;
        if (isset($this->asyncData['ajax'])) {
            $this->asyncData = $this->clearTags($this->asyncData);
            if (!$this->userId) $this->exec();
            switch ($this->asyncData['ajax']) {
                case 'sitemap':
                    return (new SitemapController())->inputAsyncData($this->asyncData['linksCounter']);
                case 'editData':
                    $_POST['return_id'] = true;
                    return ['success' => '1', 'url' => $this->checkPost()];
                case 'changeParent':
                    return ['success' => '1', 'pos' => $this->changeParent() + $this->asyncData['iteration']];
                case 'search':
                    return ['success' => '1', 'search' => $this->search()];
                case 'tinymceFile':
                    $fileEdit = new FileEdit();
                    $fileEdit->setUniqueFile(false);
                    $file = $fileEdit->addFile($this->asyncData['table'] . '/content_files/');
                    return ['success' => '1', 'url' =>PATH . UPLOAD_DIR . $file['file']];
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