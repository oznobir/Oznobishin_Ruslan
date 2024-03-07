<?php
/**
 * @return mixed|null
 */
function getDataMainMenu(): mixed
{
    if (file_exists(DATA_PATH)) {
        return include DATA_PATH;
    } else {
//        $_SESSION ['message'] = [
//            'text' => "Файл 'data_menu.php' не найден.",
//            'status' => "error"
//        ];
//        header("HTTP/1.0 404 Not Found");
        return null;
    }
}

/**
 * @param $data_menu
 * @param $page
 * @return mixed|null
 */
function getDataP($data_menu, $page): mixed
{
    if (isset($data_menu['children'][$page]['dir'])) {
        return $data_menu['children'][$page];
    } else return null;
//    return $data_menu['children'][$page] ?? null;
}

/**
 * @param $data_menu
 * @param $page
 * @return mixed|null
 */
function getDataParent($data_menu, $page): mixed
{
    $params = explode("-", $page);
    $string = $params[0];
    for ($i = 1; $i < count($params); $i++) {
        $string .= '-' . $params[$i];
        $params[$i] = $string;
    }
    for ($i = 0; $i < count($params) - 2; $i++) {
        if (isset($data_menu[$params[$i]]['children'])) {
            $data_menu = $data_menu[$params[$i]]['children'];
        } else return null;
    }
    if (isset($data_menu[$params[count($params) - 2]]['children'])) {
        return $data_menu[$params[count($params) - 2]];
    } else return null;
}// function getDataParent($data_menu, $page)
