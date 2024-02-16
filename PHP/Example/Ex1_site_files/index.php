<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
session_start();

if (file_exists("data/data_menu.php")) {
    $data_menu = include "data/data_menu.php";
    $page = htmlspecialchars($_GET['p']) ?? 'all';
    if ($page !== 'all') {
        if ($data_parent = getDataParent($data_menu, $page)) {
            $dir_p = $data_parent['children'][$page]['dir'];
            if (file_exists("data/data_$dir_p.php")) {
                $title = "Пример $page. {$data_parent['desc']}";
                $desc = $data_parent['children'][$page]['desc'];
                $menu = showMenuPage($data_parent['children'], $page);
                $data_p = include "data/data_$dir_p.php";
                $content1 = showContent1($data_p, $dir_p);
                $content2 = showContent2($dir_p);
            } else {
                $_SESSION ['message'] = [
                    'text' => "Файл 'data/data_$dir_p.php' не найден.",
                    'status' => "error"
                ];
                header("Location: index.php?p=all");
                die();
            }
        } else {
            $_SESSION ['message'] = [
                'text' => "Файл '$page' не найден.",
                'status' => "error"
            ];
            header("Location: index.php?p=all");
            die();
        }
    }
    if ($page == 'all') {
        $mainMenu = showMainMenu($data_menu);
        $title = 'Содержание. Скрипты на PHP';
        $desc = "Скрипты на PHP. Изучаем вместе";
        include 'template/layoutMainMenu.php';
        die();
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
} // end function showMenuPage($data): string
function showMainMenu($data): string
{
    $string = '<div class="accor-group">';
    $string .= '<div class="as-title">Содержание</div>';
    $string .= tplMainMenu($data);
    $string .= '</div>';
    return $string;
}
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
} // end function showMainMenu($data): string
function getDataParent($data_menu, $page) // get menu parent and 'route'
{
    $preg = '#([0-9-]+)-([0-9-]+)#';
    if (preg_match($preg, $page, $params[])) {
        for ($i = 0; ($params[$i]); $i++) {
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

function createLinkMenu($href, $title, $page): string
{
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