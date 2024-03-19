<?php
namespace project\models;
use Core\Model;

class PageModel extends Model
{
    protected function setQueries(): void
    {
        $this->query = [
            'getPageBySlug' => 'SELECT slug, title, description, form, content, menu_id FROM example WHERE slug=:p',
            'getMenuByMenuId' => 'SELECT slug, title, description FROM `example` WHERE menu_id =:menu_id',
        ];
    }
    public function getDataPage($parameters = null)
    {
        return self::selectRow($this->query['getPageBySlug'], \PDO::FETCH_ASSOC, $parameters);
    }

    /**
     * @param $parameters - из GET
     * @return array
     */
    public function getDataMenu($parameters = null): array
    {
        return self::selectAll($this->query['getMenuByMenuId'], \PDO::FETCH_UNIQUE, $parameters);
    }
}
///**
// * @param $slug
// * @return array|null
// */
//public function getBySlug($slug): ?array
//{
//    return $this->findOne("SELECT * FROM example WHERE slug='$slug'");
//}
//
///**
// * @param $menu_id
// * @return array|null
// */
//public function getAllByMenu_id($menu_id): ?array
//{
//    return $this->findMany("SELECT slug, title, description FROM `example` WHERE menu_id = '$menu_id'", 'slug');
//}