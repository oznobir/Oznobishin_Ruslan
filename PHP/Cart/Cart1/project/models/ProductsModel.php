<?php

namespace Project\Models;

use Core\Model;
use PDO;

class ProductsModel extends Model
{
    /** Получение данных продуктов из корзины по их id
     * @param array $parameters - id продуктов из корзины $_SESSION['cart']
     * @return array|null массив данных продуктов
     */
    public function getProductsFromArray(array $parameters): ?array
    {
        $parameters = array_keys($parameters);
        $query = "SELECT * FROM `products` WHERE id ";
        $query .= 'IN ('. str_repeat('?,', count($parameters) - 1) . '?)';
        $data = self::selectAll($query, PDO::FETCH_ASSOC, $parameters);
        if (!$data) return null;
        return $data;
    }
    /** Получение данных продукта по slug
     * @param $parameters - slug из Router
     * @return array|null массив данных продукта
     */
    public function getProductBySlug($parameters): array|null
    {
        $query = 'SELECT * FROM `products` WHERE slug =:slug';
        $data = self::selectRow($query, PDO::FETCH_ASSOC, $parameters);
        if (!$data) return null;
        return $data;
    }
    /** Получение массива продуктов
     * @param $limit - количество последних по id, null - все
     * @return array массив продуктов
     */
    public function getProductsLast($limit = null): array
    {
        $query ='SELECT * FROM `products` ORDER BY id DESC';
        if ($limit) {
            $query .= " limit $limit";
        }
        return self::selectAll($query, PDO::FETCH_ASSOC);
    }
    /** Получение массива всех продуктов в категории по id
     * @param $id - id категории
     * @return array массив продуктов
     */
    public function getProductsCategoryById($id): array
    {
        $query = 'SELECT * FROM `products` WHERE category_id =:id ORDER BY id DESC';
        return self::selectAll($query, PDO::FETCH_ASSOC, $id);
    }
}