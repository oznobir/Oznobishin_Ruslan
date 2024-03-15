<?php
namespace Project\Models;
use Core\Model;

class MenuModel extends Model
{
    /**
     * @return array массив с вложенными в children по parent_id menu и example
     */
    public function getAll(): array
    {
        $menu = $this->findMany("SELECT menu.id, menu.description, menu.parent_id FROM `menu` UNION
              SELECT example.slug, example.description, example.menu_id FROM `example`", 'id');
        return $this->getTree($menu);
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