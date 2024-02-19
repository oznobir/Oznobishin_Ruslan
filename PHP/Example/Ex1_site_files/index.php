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
                $content1 = showContent1($data_p);
                $content2 = showContent2($data_p['content2'], $dir_p);
                if (!empty($_POST)) {
                    runAjax($_POST, $data_p, $dir_p);
                    exit();
                }
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

function runAjax($POST, $array, $dir_p): void
{
    $listPOST = array();
    foreach ($POST as $key => $item) {
        $listPOST[] = $key;
    }
    header("Content-type: text/plain; charset=UTF-8");
    if (listData_p($array) == $listPOST) {
        foreach ($array['content1'] as $item) {
            if ($item['name']) {
                $w = $item['name'];
                $$w = htmlspecialchars($POST[$w], ENT_QUOTES, 'UTF-8');
            }
        }
        include "pages/$dir_p/index.php";
    } else  echo "<p>Данные не найдены</p>";
}
function listData_p($array): array
{
    $listArray = array();
    foreach ($array['content1'] as $item) {
        if ($item['name']) {
            $listArray[] = $item['name'];
        }
    }
    return $listArray;
}
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

function getDataParent($data_menu, $page)
{
    $preg = '#([0-9-]+)-([0-9-]+)#';
    if (preg_match($preg, $page, $params[])) {
        for ($i = 0; ($params[$i]); $i++) {
            preg_match($preg, $params[$i][1], $params[]);
        }
        for ($i = count($params) - 2; $i > 0; $i--) {
            if (isset($data_menu[$params[$i][1]]['children'])) {
                $data_menu = $data_menu[$params[$i][1]]['children'];
            } else {
                return null;
            }
        }
        if (isset($data_menu[$params[0][1]])) {
            $data_menu = $data_menu[$params[0][1]];
        } else {
            return null;
        }
        if (isset($data_menu['children'][$page]['dir'])) {
            return $data_menu;
        } else {
            return null;
        }
    } else {
        return null;
    }
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

function showContent1($data_p): string
{
    $content1 = creatForm($data_p['content1']);
    $content1 .= "<div id = \"result\">?</div>";


    return $content1;
}// end function showContent1($data, $p): string

function creatForm($data): string
{
    $content1 = "<form name =\"page\" method='post'><fieldset>";
    foreach ($data as $arr) {
        // тут подумать !!!!
//        if (($arr['name']) && isset($_POST['button'])) {
//            $post = $_POST[$arr['name']];
//        } else {
//            $post = $arr['default'];
//        }

        if ($arr['type'] == 'text') {
            $content1 .= "<label for=\"{$arr['name']}\">\${$arr['name']}:</label>
<input type=\"text\" id = \"{$arr['name']}\" name=\"{$arr['name']}\" autocomplete=\"off\" value=\"{$arr['default']}\"><br><br>";
        }
        if ($arr['type'] == 'label') {
            $content1 .= "<label>{$arr['default']}:</label><br><br>";
        }

        if ($arr['type'] == 'textarea') {
            $content1 .= "<span>Текст: </span>
<textarea name=\"{$arr['name']}\" placeholder=\"Введите текст\">{$arr['default']}</textarea><br>";
        }
    }
    $content1 .= "<input type=\"button\" value=\"Результат\" onClick=\"sendRequest();\"/>";
    $content1 .= " </fieldset></form>";
    return $content1;
} // end function creatForm($data): string

function showContent2($data, $p): array
{
    $content2 = array('tabs' => '', 'head' => '', 'foot' => '');
    $i = 1;
    $content2 ['tabs'] .= "<div class=\"text2-tabs\">";
    foreach ($data as $arr) {

        if ($i == 1) {
            $checked = 'checked="checked"';
        } else {
            $checked = '';
        }
        $content2 ['tabs'] .= "<input name=\"text2-tabs\" type=\"radio\" id=\"text2-tab-$i\" $checked class=\"text2-input\"/>";
        $content2 ['tabs'] .= "<label for=\"text2-tab-$i\" class=\"text2-label\">{$arr['name']}</label>";
        $content2 ['tabs'] .= "<div class=\"text2-panel\">";
        if ($arr['type'] == 'php') {
            $content2 ['tabs'] .= highlight_file("pages/$p/{$arr['path']}", true);
        }
        if ($arr['type'] == 'css') {
            $str_cont = file_get_contents("pages/$p/{$arr['path']}");
//            $str_hl = highlight_string('<?php'. $str_cont, true);
            $content2 ['tabs'] .= str_replace ('&lt;?php', '', highlight_string('<?php'. $str_cont, true));
            $content2 ['head'] .= '<style>'. $str_cont .'</style>';
        }
        if ($arr['type'] == 'js') {
            // если js или что-то еще будет, переделать в отдельную функцию
            $str_cont = file_get_contents("pages/$p/{$arr['path']}");
            $content2 ['tabs'] .= preg_replace ('#&lt;?php#', '', highlight_string('<?php'. $str_cont, true));
            $content2 ['foot'] .='<script>'. $str_cont .'</script>';
        }
        $content2 ['tabs'] .= "</div>";
        $i++;
    }
    $content2 ['tabs'] .= "</div>";
    return $content2;
//    return highlight_file("pages/$p/index.php", true);
} // end function showContent2($p): string