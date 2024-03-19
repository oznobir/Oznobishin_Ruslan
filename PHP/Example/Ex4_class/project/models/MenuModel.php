<?php
namespace Project\Models;
use Core\Model;
use PDO;

class MenuModel extends Model
{
    protected function setQueries(): void
    {
        $this->query = [
            'getMenuAll' =>
                'SELECT menu.id, menu.description, menu.parent_id FROM `menu` UNION SELECT example.slug, example.description, example.menu_id FROM `example`',
        ];
    }

    /**
     * @param $parameters - из GET
     * @return array
     */
    public function getData($parameters = null): array
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
            //Если нет вложений
            if (!$node['parent_id']) {
                $tree[$id] = &$node;
                $tree[$id]['children'] = [];
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