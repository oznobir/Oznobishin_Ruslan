<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

if (file_exists("data/data_menu.php")) {
    $data_menu = include "data/data_menu.php";
//    var_dump($data_menu);
//    exit();
    $page = $_GET['p'] ?? '1-2-1';
// Временно. Переделать !!!!
//    $reg = '#(\d+-\d+)#';
    $reg = '#(\d+)-(\d+)-(\d+)#';
    preg_match($reg, $page, $match);
    $sup = [$match[1], $match[2], "$match[1]-$match[2]"];

    $p = $data_menu[$sup[0]]['children'][$sup[2]]['children'][$page]['dir'];

    if (file_exists("data/data_$p.php")) {
        $data = include "data/data_$p.php";
        $title = "Пример $page. {$data_menu[$sup[0]]['children'][$sup[2]]['desc']}.";
        $desc = $data_menu[$sup[0]]['children'][$sup[2]]['children'][$page]['desc'];
        $menu = '';
//        $tree_menu = getTree($data_menu);
        foreach ($data_menu[$sup[0]]['children'][$sup[2]]['children'] as $key => $a) {
                $menu .= createLinkMenu($key);
        }
        $content1 = showContent1($data, $p);
        $content2 = showContent2($p);
    } else {
        $title = "File 'data/data_$p.php' not found";
        $desc = "File 'data/data_$p.php' not found";
        $menu = '';
        $content1 = '';
        $content2 = '';
    }

} else {
    $title = "File 'data_menu.php' not found";
    $desc = "File 'data_menu.php' not found";
    $menu = '';
    $content1 = '';
    $content2 = '';
}
include 'template/layout.php';
// Массив переделан как $tree
//function getTree($data): array
//{
//    $tree = array();
//    foreach ($data as $key => &$node) {
//        //Если нет вложений
//        if (!$node['parent']) {
//            $tree[$key] = &$node;
//        } else {
//            //Если есть потомки, то переберем массив
//            $data[$node['parent']]['children'][$key] = &$node;
//        }
//    }
//    return $tree;
//}
function createLinkMenu($href): string
{
    $page = $_GET['p'] ?? '1-2-1';
    if ($page == $href) {
        $classLinkMenu = " class='active'";
    } else {
        $classLinkMenu = '';
    }
    return "<div><a$classLinkMenu href=\"?p=$href\">Пример $href</a></div>";
}//  end  function createLinkMenu($href): string
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
            $content1 .= "
              <label for=\"id_{$arr['name']}\">\${$arr['name']}:</label>
              <input type=\"text\" id = \"id_{$arr['name']}\" name=\"{$arr['name']}\" autocomplete=\"off\" value=\"$post\"><br><br> 
            ";
        }
        if ($arr['type'] == 'label') {
            $content1 .= "
              <label>$post:</label><br><br> 
            ";
        }

        if ($arr['type'] == 'textarea') {
            $content1 .= "<span>Текст: </span>
                  <textarea name=\"{$arr['name']}\" placeholder=\"Введите текст\"><?= $post ?></textarea><br>
                ";
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