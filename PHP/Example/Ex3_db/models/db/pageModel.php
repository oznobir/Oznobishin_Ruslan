<?php
/**
 * @param array $dataPage массив с данными page
 * @return array|void
 */
function getPageMenu(array $dataPage)
{
    $menu_id = $dataPage['menu_id'];
    $query = "SELECT slug, title, description FROM `example` WHERE menu_id = '$menu_id'";
    $result = mysqli_query(Link, $query) or die(mysqli_error(Link));
    for ($data = []; $row = mysqli_fetch_assoc($result);) {
        $data [$row['slug']] = $row;
    }
    return $data;
}

/**
 * @param array $parameters массив с parameters p
 * @return array|false|void|null
 */
function getDataPage(array $parameters)
{
    $slug = $parameters['parameters']['p'];
    $query = "SELECT * FROM `example` WHERE slug = '$slug'";
    $result = mysqli_query(Link, $query) or die(mysqli_error(Link));
    return mysqli_fetch_assoc($result);
}