<?php
namespace Project\Models;
use Core\Model;
use PDO;

class CategoriesModel extends Model
{
    protected function setQueries(): void
    {
        $this->query = [
            'getMenuAll' =>
                'SELECT id, parent_id, title FROM `categories`',
        ];
    }

    /** Получение массива категорий с подкатегориями
     * @param $parameters
     * @return array массив категорий с подкатегориями
     */
    public function getDataWithChild($parameters = null): array
    {
        return $this->getTree(self::selectAll($this->query['getMenuAll'], PDO::FETCH_UNIQUE, $parameters));
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
//    /**
//     * @return array массив с вложенными в children по parent_id menu и example
//     */
//    public function getAll(): array
//    {
//        $menu = $this->findMany("SELECT menu.id, menu.description, menu.parent_id FROM `menu` UNION
//              SELECT example.slug, example.description, example.menu_id FROM `example`", 'id');
//        return $this->getTree($menu);
//    }