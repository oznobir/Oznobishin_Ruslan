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
     * @return array|void
     * @throws DOMException
     * @throws DbException
     * @throws RouteException
     * @uses async
     */
    public function async()
    {
        if (isset($this->asyncData['ajax'])) {
            $this->asyncData = $this->clearTags($this->asyncData);
            switch ($this->asyncData['ajax']) {
                case 'sitemap':
                    (new SitemapController())->inputData($this->asyncData['linksCounter'], false);
                    break;
                case 'editData':
                    if (!$this->userId) $this->exec();
                    $_POST['return_id'] = true;
                    $this->checkPost();
                    return ['success' => '1', 'message' => 'OK'] ;
//                    break;
                case 'changeParent':
                    if (!$this->userId) $this->exec();
                    $count = $this->model->select($this->asyncData['table'], [
                        'fields' => ['COUNT(*) as count'],
                        'where' => ['pid' => $this->asyncData['pid']],
                        'no_concat' => true,
                    ])[0]['count'] + $this->asyncData['iteration'];
                    return ['pos' => $count];
//                    break;
                default :
                    return ['success' => '0', 'message' => 'Ajax variable is invalid'];
//                    break;
            }
        } else return ['success' => '0', 'message' => 'No ajax variable'];
    }

}