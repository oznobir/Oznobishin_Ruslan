<?php

namespace Project\Models;

use Core\Model;
use PDO;

class ProductsModel extends Model
{
    protected function setQueries(): void
    {
        $this->query = [
            'getProductsLast' =>
                'SELECT * FROM `products` ORDER BY id DESC',
            'getProductsCategory' =>
                'SELECT * FROM `products` WHERE category_id =:id ORDER BY id DESC',
        ];
    }

    /** Получение массива продуктов
     * @param $limit - количество последних по id, null - все
     * @return array массив продуктов
     */
    public function getProductsLast($limit = null): array
    {
        if ($limit) {
            $this->query['getProductsLast'] .= " limit $limit";
        }
        return self::selectAll($this->query['getProductsLast'], PDO::FETCH_ASSOC);
    }
    /** Получение массива всех продуктов в категории по id
     * @param $id - id категории
     * @return array массив продуктов
     */
    public function getProductsCategoryById($id): array
    {
        return self::selectAll($this->query['getProductsCategory'], PDO::FETCH_ASSOC, $id);
    }
}