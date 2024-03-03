<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
session_start();

if (file_exists("data/data_menu.php")) {
    $data_menu = include "data/data_menu.php";
    if (isset($_GET['p'])) {
        $page = htmlspecialchars($_GET['p'], ENT_QUOTES, 'UTF-8');
    } else {
        $page = 'all';
    }
    if ($page == 'all' || $page == '') {
        $mainMenu = showMainMenu($data_menu);
        $title = 'Содержание. Скрипты на PHP';
        $desc = "Скрипты на PHP. Изучаем вместе";
        include 'template/layoutMainMenu.php';
        die();
    } else {
        $data_parent = getDataParent($data_menu, $page);
        $data_p = getDataP($data_parent, $page);
        if ($data_p) {
            if (file_exists("pages/{$data_p['dir']}/{$data_p['content2'][0]['path']}")) {
                $title = "Пример $page. {$data_parent['desc']}";
                $desc = $data_p['desc'];
                $menu = showMenuPage($data_parent['children'], $page);
                $content1 = showContent1($data_p);
                $content2 = showContent2($data_p);
            } else {
                $_SESSION ['message'] = [
                    'text' => "Файл 'pages/{$data_p['dir']}/{$data_p['content2'][0]['path']}' не найден.",
                    'status' => "error"
                ];
                header("Location: index.php?p=all");
                die();
            }
        } else {
            $_SESSION ['message'] = [
                'text' => "Нет данных примера $page в 'data/data_menu.php'.",
                'status' => "error"
            ];
            header("Location: index.php?p=all");
            die();
        }
    }
} else {
    $_SESSION ['message'] = [
        'text' => "Файл 'data_menu.php' не найден.",
        'status' => "error"
    ];
    header("HTTP/1.0 404 Not Found");
    $title = "404 Not Found";
    $desc = "404 Not Found";
    $mainMenu = "";
    include 'template/layoutMainMenu.php';
    die();
}
include 'template/layout.php';

function showMenuPage($data, $page): string
{
    $menu = '';
    foreach ($data as $key => $a) {
        $menu .= createLinkMenu($key, $a['desc'], $page);
    }
    return $menu;
} // end function showMenuPage($data, $page): string

function showMainMenu($data): string
{
    $string = '<div class="accor-group">';
    $string .= '<div class="as-title">Содержание</div>';
    $string .= tplMainMenu($data);
    $string .= '</div>';
    return $string;
} // end function showMainMenu($data): string

function tplMainMenu($data): string
{
    $string = '';
    foreach ($data as $key => $value) {
        if (isset($value['children'])) {
            $string .= "<input type=\"checkbox\" name=\"accor\" id=\"accor_$key\"/><label for=\"accor_$key\">{$value['desc']}</label>";
        } else {
            $string .= "<div><a href=\"?p=$key\" title =\"Пример $key\">{$value['desc']}</a></div>";
        }
        if (isset($value['children'])) {
            $string .= '<div class="accor-container">' . tplMainMenu($value['children']) . '</div>';
        }
    }
    return $string;
} // end function tplMainMenu($data): string
function getDataP($data_menu, $page)
{
//    if (isset($data_menu['children'][$page])) {
//        return $data_menu['children'][$page];
//    } else return null;
    return $data_menu['children'][$page] ?? null;
}

function getDataParent($data_menu, $page)
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

function createLinkMenu($href, $title, $page): string
{
    if ($page == $href) {
        $classLinkMenu = " class='active'";
    } else {
        $classLinkMenu = '';
    }
    return "<div><a$classLinkMenu title=\"$title\" href=\"?p=$href\">Пример $href</a></div>";
}//  end  function createLinkMenu($href, $title, $page): string

function showContent1($data): string
{
    $content1 = "<form name =\"form\" method='post'>";
    $content1 .= "<label type=\"text\">Исходные данные</label><br>";
    foreach ($data['content1'] as $arr) {
        if ($arr['type'] == 'text') {
            $content1 .= "<label for=\"{$arr['name']}\">\${$arr['name']}:</label>
<input type=\"text\" id = \"{$arr['name']}\" name=\"{$arr['name']}\" autocomplete=\"off\" value=\"{$arr['default']}\"><br><br>";
        }
        if ($arr['type'] == 'label') {
            $content1 .= "<label>{$arr['default']}:</label><br><br>";
        }
        if ($arr['type'] == 'security') {
            $content1 .= "<input type=\"hidden\" name = \"{$arr['name']}\" value=\"{$arr['default']}\">";
        }
        if ($arr['type'] == 'textarea') {
            $content1 .= "<span>Текст: </span>
<textarea name=\"{$arr['name']}\" placeholder=\"Введите текст\">{$arr['default']}</textarea><br>";
        }
    }
    $content1 .= "<input type=\"button\" value=\"Результат\" onClick=\"sendRequest();\"/>";
    $content1 .= "<label type=\"text\">pages/{$data['dir']}</label><br>";
    $content1 .= "</form>";
    $content1 .= "<div id = \"result\"> ... </div>";
    return $content1;
} // end function showContent1($data, $dir_p): string

function showContent2($data): array
{
    $content2 = array('tabs' => '', 'head' => '', 'foot' => '');
    $i = 1;
    $p = $data['dir'];
    $content2 ['tabs'] .= "<div class=\"text2-tabs\">";
    foreach ($data['content2'] as $arr) {
        if ($i == 1) {
            $checked = 'checked="checked"';
        } else {
            $checked = '';
        }
        $content2 ['tabs'] .= "<input name=\"text2-tabs\" type=\"radio\" id=\"text2-tab-$i\" $checked class=\"text2-input\"/>";
        $content2 ['tabs'] .= "<label for=\"text2-tab-$i\" class=\"text2-label\">{$arr['name']}</label>";

        if ($arr['type'] == 'php') {
            $content2 ['tabs'] .= "<div class=\"text2-panel php\">";
            $content2 ['tabs'] .= "<input type=\"hidden\" name=\"path\" value=\"{$arr['path']}\"/>";
            $content2 ['tabs'] .= highlight_file("pages/$p/{$arr['path']}", true);
            $content2 ['tabs'] .= "</div>";
        }
        if ($arr['type'] == 'css') {
            $content2 ['tabs'] .= "<div class=\"text2-panel\">";
            $str_cont = file_get_contents("pages/$p/{$arr['path']}");
//            $str_hl = highlight_string('<?php'. $str_cont, true);
            $content2 ['tabs'] .= str_replace('&lt;?php', '', highlight_string('<?php' . $str_cont, true));
            $content2 ['head'] .= '<style>' . $str_cont . '</style>';
            $content2 ['tabs'] .= "</div>";
        }
        if ($arr['type'] == 'js') {
            $content2 ['tabs'] .= "<div class=\"text2-panel\">";
            // если js или что-то еще будет, переделать в отдельную функцию
            $str_cont = file_get_contents("pages/$p/{$arr['path']}");
            $content2 ['tabs'] .= preg_replace('#&lt;?php#', '', highlight_string('<?php' . $str_cont, true));
            $content2 ['foot'] .= '<script>' . $str_cont . '</script>';
            $content2 ['tabs'] .= "</div>";
        }
        $i++;
    }
    $content2 ['tabs'] .= "</div>";
    return $content2;
//    return highlight_file("pages/$p/index.php", true);
} // end function showContent2($p): string