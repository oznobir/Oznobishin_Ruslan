<?php
function showMenuPage($data, $page)
{
    $menu = '';
    foreach ($data as $key => $item) {
        $menu .= createLinkMenu($key, $item['description'], $page);
    }
    return $menu;
} // end function showMenuPage($data, $page): string
function createLinkMenu($key, $title, $page)
{
    if ($page == $key) {
        $classLinkMenu = " class='active'";
    } else {
        $classLinkMenu = '';
    }
    return "<div><a$classLinkMenu title=\"$title\" href=\"?p=$key\">Пример $key</a></div>";
}//  end  function createLinkMenu($href, $title, $page): string