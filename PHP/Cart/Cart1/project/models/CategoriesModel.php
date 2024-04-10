<?php

namespace Project\Models;

use Core\Model;
use PDO;

class CategoriesModel extends Model
{
    /** Получение дочерних категорий по id
     * @param $parameters - id
     * @return array массив подкатегорий
     */
    public function getSubCategoriesById($parameters): array
    {
        $query = "SELECT * FROM `categories` WHERE parent_id=:id";
        return self::selectAll($query, PDO::FETCH_ASSOC, $parameters);
    }

    /** Получение категории по slug
     * @param $parameters - slug
     * @return array|null массив категорией
     */
    public function getCategoryBySlug($parameters): array|null
    {
        $query = 'SELECT * FROM `categories` WHERE slug=:slug';
        $data = self::selectRow($query, PDO::FETCH_ASSOC, $parameters);
        if (!$data) return null;
        return $data;
    }
    /** Получение массива категорий с подкатегориями
     * @param $parameters
     * @return array простой массив категорий с вложенными подкатегориями
     */
    public function getCategoriesAll($parameters = null): array
    {
        $query = 'SELECT id, parent_id, title, slug FROM `categories`';
        return self::selectAll($query, PDO::FETCH_ASSOC, $parameters);
    }

    /** Получение массива категорий с подкатегориями
     * @param $parameters
     * @return array многомерный массив категорий с вложенными подкатегориями
     */
    public function getCategoriesWithChild($parameters = null): array
    {
        $query = 'SELECT id, parent_id, title, slug FROM `categories`';
        return $this->getTree(self::selectAll($query, PDO::FETCH_UNIQUE|PDO::FETCH_ASSOC, $parameters));
    }

    /**
     * Преобразование массива
     * @param array $dataset
     * @return array
     */
    private function getTree(array $dataset): array
    {
        $tree = [];
        foreach ($dataset as $id => &$node) {
            if (!$node['parent_id'])  $tree[$id] = &$node;
            else $dataset[$node['parent_id']]['children'][$id] = &$node;
        }
        return $tree;
    }

    /** Добавление новой категории
     * @param string $slug slug категории
     * @param string $name название категории
     * @param int $pid id родительской категории
     * @return int|false id категории
     */
    public function newCategoryData(string $slug, string $name, int $pid): int|false
    {
        $parameters['slug'] = $slug;
        $parameters['title'] = $name;
        $parameters['parent_id'] = $pid;
        $query = "INSERT INTO `categories`(`parent_id`, `slug`, `title`) VALUES (:parent_id,:slug,:title)";
        return self::execId($query, $parameters);
    }

    /** Редактирование категории
     * @param int $id id редактируемой категории
     * @param string|null $slug новый slug категории
     * @param string|null $name новое название категории
     * @param int $pid id родительской категории
     * @return mixed @return mixed результат
     */
    public function updateCategoryData(int $id, string $slug = null, string $name = null, int $pid =-1): mixed
    {
        $parameters['id'] = $id;
        $query = "UPDATE `categories` SET";
        if ($pid >-1) {
            $query .= " `parent_id`=:parent_id,";
            $parameters['parent_id'] = $pid;
        }
        if ($slug) {
            $query .= " `slug`=:slug,";
            $parameters['slug'] = $slug;
        }
        if ($name) {
            $query .= " `title`=:title ";
            $parameters['title'] = $name;
        }
        $query .= " WHERE `id`=:id";
        return self::exec($query, $parameters);
    }
}