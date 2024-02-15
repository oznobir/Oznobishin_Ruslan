<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

if (file_exists("data/data_menu.php")) {
    $data_menu = include "data/data_menu.php";
    $page = $_GET['p'] ?? 'all';
    if ($page == 'all'){
        $mainMenu = showMainMenu($data_menu);
        $title = '';
        $desc = "";
        include 'template/layoutMainMenu.php';
        die();
        // Доделать
    }
    if ($menu_parent = getMenuParent($data_menu, $page)) {
        $p = $menu_parent['children'][$page]['dir'];
        if (file_exists("data/data_$p.php")) {
            $mainMenu = showMainMenu($data_menu);
            $title = "Пример $page. {$menu_parent['desc']}";
            $desc = $menu_parent['children'][$page]['desc'];
            $menu = '';
            foreach ($menu_parent['children'] as $key => $a) {
                $menu .= createLinkMenu($key, $a['desc']);
            }
            $data_p = include "data/data_$p.php";
            $content1 = showContent1($data_p, $p);
            $content2 = showContent2($p);
        } else {
            $title = '';
            $desc = "";
//            $mainMenu = showMainMenu($data_menu);
            $menu = '';
            $content1 = "Файл 'data/data_$p.php' не найден. Перейдите в 'Содержание'.";
            $content2 = '';
        }
    } else {
        $title = "";
        $desc = "";
//        $mainMenu = showMainMenu($data_menu);
        $menu = '';
        $content1 = "Файл '$page' не найден. Перейдите в 'Содержание'.";
        $content2 = '';
    }
} else {
    $title = "";
    $desc = "";
//    $mainMenu = '';
    $menu = '';
    $content1 = "File 'data_menu.php' not found";
    $content2 = '';
}
include 'template/layout.php';

function showMainMenu($data): string
{
    $string = '';
    foreach ($data as $key => $value) {
        if (isset($value['children'])) {
            $string .= "<input type=\"checkbox\" name=\"accor\" id=\"accor_$key\"/><label for=\"accor_$key\">{$value['desc']}</label>";
        } else {
            $string .= "<div><a href=\"?p=$key\" title=\"{$value['desc']}\">{$value['desc']}</a></div>";
        }
        if (isset($value['children'])) {
            $string .= '<div class="accor-container">' . showMainMenu($value['children']) . '</div>';
        }
    }
    return $string;
} // end function showMainMenu($data): string
function getMenuParent($data_menu, $page) // get menu parent and 'route'
{
    $preg = '#([0-9-]+)-([0-9-]+)#';
    //$preg ='#([a-z0-9_-]?+)-([a-z0-9_-]+)#'; //если разбор с начала
    if (preg_match($preg, $page, $params[])) {
        for ($i = 0; ($params[$i]); $i++) {
            //    preg_match($preg, $params[$i][2], $params[]); //если разбор с начала
            preg_match($preg, $params[$i][1], $params[]);
        }
        for ($i = count($params) - 2; $i > 0; $i--) {
            if (isset($data_menu[$params[$i][1]]['children'])) {
                $data_menu = $data_menu[$params[$i][1]]['children'];
            }
        }
        if (isset($data_menu[$params[0][1]])) {
            $data_menu = $data_menu[$params[0][1]];
        }
        if (isset($data_menu['children'][$page]['dir'])) {
            return $data_menu;
        } else {
            return null;
        }
    } else {
        return null;
    }

}// function getMenuParent($data_menu, $page)

function createLinkMenu($href, $title): string
{
    $page = $_GET['p'] ?? '1-2-1';
    if ($page == $href) {
        $classLinkMenu = " class='active'";
    } else {
        $classLinkMenu = '';
    }
    return "<div><a$classLinkMenu title=\"$title\" href=\"?p=$href\">Пример $href</a></div>";
}//  end  function createLinkMenu($href, $title): string
function showContent1($data, $p): string
{
    foreach ($data as $arr) {
        if ($arr['name']) {
            $w = $arr['name'];
            if (isset($_POST['button'])) {
                $post = $_POST[$w];
            } else {
                $post = $arr['default'];
            }
            $$w = $post;
        }
    }
    $content1 = creatForm($data);

    if (isset($_POST['button'])) {

        $content1 .= "<form><fieldset>";
        ob_start();
        include "pages/$p/index.php";
        $content1 .= ob_get_clean();
        $content1 .= "</fieldset></form>";
    }

    return $content1;
}// end function showContent($data, $p): string

function creatForm($data): string
{
    $content1 = "<form method=\"POST\"><fieldset>";
    foreach ($data as $arr) {
        // тут подумать !!!!
        if (($arr['name']) && isset($_POST['button'])) {
            $post = $_POST[$arr['name']];
        } else {
            $post = $arr['default'];
        }

        if ($arr['type'] == 'text') {
            $content1 .= "<label for=\"id_{$arr['name']}\">\${$arr['name']}:</label><input type=\"text\" id = \"id_{$arr['name']}\" name=\"{$arr['name']}\" autocomplete=\"off\" value=\"$post\"><br><br>";
        }
        if ($arr['type'] == 'label') {
            $content1 .= "<label>$post:</label><br><br>";
        }

        if ($arr['type'] == 'textarea') {
            $content1 .= "<span>Текст: </span><textarea name=\"{$arr['name']}\" placeholder=\"Введите текст\"><?= $post ?></textarea><br>";
        }
    }
    $content1 .= "<input type=\"submit\" name=\"button\" value=\"Результат\" />";
    $content1 .= " </fieldset></form>";
    return $content1;
} // end function creatForm($data): string

function showContent2($p): string
{
    return highlight_file("pages/$p/index.php", true);
} // end function showContent2($p): string