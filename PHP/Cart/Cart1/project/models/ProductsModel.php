<?php

namespace Project\Models;

use Core\Model;
use PDO;

class ProductsModel extends Model
{
    protected function setQueries(): void
    {
        $this->query = [
            '3' =>
                'SELECT * FROM `products` WHERE slug =:slug',
            '2' =>
                'SELECT * FROM `products` ORDER BY id DESC',
            '1' =>
                'SELECT * FROM `products` WHERE category_id =:id ORDER BY id DESC',
        ];
    }

    /** Получение данных продукта по slug
     * @param $parameters - slug из Router
     * @return array|null массив данных продукта
     */
    public function getProductBySlug($parameters): array|null
    {
        $data = self::selectRow($this->query['3'], PDO::FETCH_ASSOC, $parameters);
        If (!$data) return null;
        return $data;
    }
    /** Получение массива продуктов
     * @param $limit - количество последних по id, null - все
     * @return array массив продуктов
     */
    public function getProductsLast($limit = null): array
    {
        if ($limit) {
            $this->query['2'] .= " limit $limit";
        }
        return self::selectAll($this->query['2'], PDO::FETCH_ASSOC);
    }
    /** Получение массива всех продуктов в категории по id
     * @param $id - id категории
     * @return array массив продуктов
     */
    public function getProductsCategoryById($id): array
    {
        return self::selectAll($this->query['1'], PDO::FETCH_ASSOC, $id);
    }
}