<?php
/**
 * @param array $data
 * @param string $page
 * @return string
 */
function showMenuPage(array $data, string $page): string
{
    $menu = '';
    foreach ($data as $key => $item) {
        $menu .= createLinkMenu($key, $item['description'], $page);
    }
    return $menu;
} // end function showMenuPage($data, $page): string
/**
 * @param $key
 * @param $title
 * @param $page
 * @return string
 */
function createLinkMenu($key, $title, $page): string
{
    if ($page == $key) {
        $classLinkMenu = " class='active'";
    } else {
        $classLinkMenu = '';
    }
    return "<div><a$classLinkMenu title=\"$title\" href=\"?controller=page&p=$key\">Пример $key</a></div>";
}//  end  function createLinkMenu($href, $title, $page): string