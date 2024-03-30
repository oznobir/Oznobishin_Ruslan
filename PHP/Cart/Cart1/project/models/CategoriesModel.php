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
    public function geSubCategoriesById ($parameters) : array
    {
        $query = 'SELECT * FROM `categories` WHERE parent_id=:id';
        return self::selectAll($query, PDO::FETCH_ASSOC, $parameters);
    }

    /** Получение категории по slug
     * @param $parameters - slug
     * @return array|null массив категорией
     */
    public function getCategoryBySlug ($parameters) : array|null
    {
        $query = 'SELECT * FROM `categories` WHERE slug=:slug';
        $data = self::selectRow($query, PDO::FETCH_ASSOC, $parameters);
        If (!$data) return null;
        return $data;
    }

    /** Получение массива категорий с подкатегориями
     * @param $parameters
     * @return array массив категорий с подкатегориями
     */
    public function getCategoriesWithChild($parameters = null): array
    {
        $query = 'SELECT id, parent_id, title, slug FROM `categories`';
        return $this->getTree(self::selectAll($query, PDO::FETCH_UNIQUE, $parameters));
    }

    /**
     * Преобразование массива
     * @param array $dataset
     * @return array
     */
    private function getTree(array $dataset): array
    {
        $tree = array();
        foreach ($dataset as $id => &$node) {
            //от FETCH_UNIQUE
            unset($dataset[$id][1]);
            unset($dataset[$id][2]);
            unset($dataset[$id][3]);
            //Если нет вложений
            if (!$node['parent_id']) {
                $tree[$id] = &$node;
            } else {
                //Если есть потомки, то переберем массив
                $dataset[$node['parent_id']]['children'][$id] = &$node;

            }
        }
        return $tree;
    }
}