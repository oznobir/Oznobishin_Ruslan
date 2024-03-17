<?php
namespace project\models;
use Core\Model;

class PageModel extends Model
{
    /**
     * @param $slug
     * @return array|null
     */
    public function getBySlug($slug): ?array
    {
        return $this->findOne("SELECT * FROM example WHERE slug='$slug'");
    }

    /**
     * @param $dataPage
     * @return array|null
     */
    public function getAllByMenu_id($menu_id): ?array
    {
        return $this->findMany("SELECT slug, title, description FROM `example` WHERE menu_id = '$menu_id'", 'slug');
    }
}