<?php

namespace core\site\controllers;

use core\base\controllers\BaseAsync;
use core\base\exceptions\DbException;

class AsyncController extends BaseSite
{
    protected array $asyncData = [];

    /**
     * @param $data
     * @return array
     * @uses async
     */
    public function async($data): array
    {
        $this->asyncData = $data;
        if (isset($this->asyncData['ajax'])) {
//            $this->asyncData = $this->clearTags($this->asyncData);
//            $this->inputData();
            switch ($this->asyncData['ajax']) {
                case 'catalog_quantities':
                    $qty = $this->asyncData['qty'] ? $this->num($this->asyncData['qty']) : 0;
                    $qty && $_SESSION['quantities'] = $qty;
                    break;
                default :
                    return ['success' => '0', 'message' => 'Ajax variable is invalid'];
            }
        } else return ['success' => '0', 'message' => 'No ajax variable'];
    }

}