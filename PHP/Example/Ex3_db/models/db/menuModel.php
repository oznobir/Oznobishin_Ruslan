<?php
/**
 * Модель для главного меню database
 */

function getDataMainMenu()
{
    $query = "SELECT menu.id, menu.description, menu.parent_id FROM `menu` UNION
              SELECT example.slug, example.description, example.menu_id FROM `example`";
    $result = mysqli_query(Link, $query) or die(mysqli_error(Link));
    for ($data = []; $row = mysqli_fetch_assoc($result);) {
        $data [$row['id']] = $row;
    }
    return getTree($data);
}

/**
 * Преобразование массива
 * @param array $dataset
 * @return array
 */
function getTree(array $dataset): array
{
    $tree = array();
    foreach ($dataset as $id => &$node) {
        //Если нет вложений
        if (!$node['parent_id']) {
            $tree[$id] = &$node;
            $tree[$id]['children'] = []; // меню (с 0 parent_id) Подумать!!!
        } else {
            //Если есть потомки, то переберем массив
            $dataset[$node['parent_id']]['children'][$id] = &$node;
        }
    }
    return $tree;
}