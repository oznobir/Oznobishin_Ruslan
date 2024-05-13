<?php

namespace core\admin\controllers;

use core\base\controllers\BaseAsync;
use core\base\exceptions\DbException;
use DOMException;

/**
 * @uses AsyncController
 */

class AsyncController extends BaseAsync
{
    /**
     * @throws DOMException
     * @throws DbException
     * @uses async
     */
    public function async(): false|string
    {
        if(isset($this->data['ajax'])){
            switch ($this->data['ajax']){
                case 'sitemap':
                    (new SitemapController())->inputData($this->data['linksCounter'], false);
                    break;
                default :
                   break;
            }
        }
        return json_encode(['success' => '0', 'message' => 'No ajax variable']);
    }
}