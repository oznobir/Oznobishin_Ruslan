<?php

namespace Project\Models;

use Core\Model;
use PDO;

class ProductsModel extends Model
{
    protected function setQueries(): void
    {

        $this->query = [
            '4' =>
                "SELECT * FROM `products` WHERE id", //for IN
            '3' =>
                'SELECT * FROM `products` WHERE slug =:slug',
            '2' =>
                'SELECT * FROM `products` ORDER BY id DESC',
            '1' =>
                'SELECT * FROM `products` WHERE category_id =:id ORDER BY id DESC',
        ];
    }
    /** Получение данных продуктов из корзины по id
     * @param $parameters - id продуктов из корзины $_SESSION['cart']
     * @return array|null массив данных продуктов
     */
    public function getProductsFromArray($parameters, $count): ?array
    {
        $this->query['4'] .= ' IN ('. str_repeat('?,', $count - 1) . '?)';
        $data = self::selectAll($this->query['4'], PDO::FETCH_ASSOC, $parameters);
        if (!$data) return null;
        return $data;
    }
    /** Получение данных продукта по slug
     * @param $parameters - slug из Router
     * @return array|null массив данных продукта
     */
    public function getProductBySlug($parameters): array|null
    {
        $data = self::selectRow($this->query['3'], PDO::FETCH_ASSOC, $parameters);
        if (!$data) return null;
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