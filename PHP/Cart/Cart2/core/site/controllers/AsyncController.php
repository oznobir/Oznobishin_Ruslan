<?php

namespace core\site\controllers;

use core\base\exceptions\DbException;
use core\site\models\Model;

class AsyncController extends BaseSite
{
    /**
     * @param $data
     * @return array|void
     * @throws DbException
     * @uses async
     */
    public function async($data)
    {
        if (isset($data['ajax'])) {
            switch ($data['ajax']) {
                case 'catalog_quantities':
                    $qty = $data['qty'] ? $this->num($data['qty']) : 0;
                    $qty && $_SESSION['quantities'] = $qty;
                    break;
                case 'add_to_cart':
                    $this->model = Model::instance();
                    $id = $data['id'] ? $this->num($data['id']) : 0;
                    $qty = $data['qty'] ? $this->num($data['qty']) : 1;
                    return $this->addToCart($id, $qty);
                default :
                    return ['success' => '0', 'message' => 'Ajax variable is invalid'];
            }
        } else return ['success' => '0', 'message' => 'No ajax variable'];
    }

}