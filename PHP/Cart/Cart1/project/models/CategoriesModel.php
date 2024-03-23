<?php
namespace Project\Models;
use Core\Model;
use PDO;

class CategoriesModel extends Model
{
    protected function setQueries(): void
    {
        $this->query = [
            '3' =>
                'SELECT id, parent_id, title, slug FROM `categories`',
            '2' =>
                'SELECT * FROM `categories` WHERE slug=:slug',
            '1' =>
                'SELECT * FROM `categories` WHERE parent_id=:id',
        ];
    }
    /** Получение дочерних категорий по id
     * @param $parameters - id
     * @return array массив подкатегорий
     */
    public function geSubCategoriesById ($parameters) : array
    {
        return self::selectAll($this->query['1'], PDO::FETCH_ASSOC, $parameters);
    }

    /** Получение категории по slug
     * @param $parameters - slug
     * @return array|null массив категорией
     */
    public function getCategoryBySlug ($parameters) : array|null
    {
        $data = self::selectRow($this->query['2'], PDO::FETCH_ASSOC, $parameters);
        If (!$data) return null;
        return $data;
    }

    /** Получение массива категорий с подкатегориями
     * @param $parameters
     * @return array массив категорий с подкатегориями
     */
    public function getCategoriesWithChild($parameters = null): array
    {
        return $this->getTree(self::selectAll($this->query['3'], PDO::FETCH_UNIQUE, $parameters));
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