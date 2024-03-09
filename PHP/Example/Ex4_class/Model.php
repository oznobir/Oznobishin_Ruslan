<?php
/**
 * Model
 */
class Model
{
    private $link;

    public function __construct()
    {
        if (!$this->link) {
            $this->link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            mysqli_query($this->link, "SET NAMES 'utf8'");
        }
    }

    /**
     * @param $slug
     * @return bool|array|null
     */
    public function getBySlug($slug): bool|array|null
    {
        $query = "SELECT * FROM example WHERE slug='$slug'";
        $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
        return mysqli_fetch_assoc($result);

    }

    /**
     * @param $dataPage
     * @return array|void
     */
    public function getAllByMenu_id($dataPage)
    {
        $menu_id = $dataPage['menu_id'];
        $query = "SELECT slug, title, description FROM `example` WHERE menu_id = '$menu_id'";
        $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
        for ($data = []; $row = mysqli_fetch_assoc($result);) {
            $data[$row['slug']] = $row;
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $query = "SELECT menu.id, menu.description, menu.parent_id FROM `menu` UNION
              SELECT example.slug, example.description, example.menu_id FROM `example`";
        $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
        for ($data = []; $row = mysqli_fetch_assoc($result);) {
            $data[$row['id']] = $row;
        }
        return $this->getTree($data);
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

//    public function count(){}
//    public function selectRow($id){}
//    public function insert($data){}
//    public function update($data, $id){}
//    public function delete($id){}
}
