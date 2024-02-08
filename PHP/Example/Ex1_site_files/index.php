<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

if (file_exists("data/data_menu.php")) {
    $data_menu = include "data/data_menu.php";

    $page = $_GET['p'] ?? '1';

    $p = $data_menu[$page]['dir'];
    if (file_exists("data/data_$p.php")) {
        $data = include "data/data_$p.php";
        $title = "Пример $page. Условные операторы PHP.";
        $desc = $data_menu[$page]['desc'];
        $menu = '';
        foreach ($data_menu as $key => $a) {
            // порядковый номер (ключ массива) в меню
            $menu .= createLinkMenu($key);
        }

        $content = showContent($data, $p);
        $content2 = showContent2($p);
    } else {
        $title = "File 'data/data_$p.php' not found";
        $desc = "File 'data/data_$p.php' not found";
        $menu = '';
        $content = '';
        $content2 = '';
    }

} else {
    $title = "File 'data_menu.php' not found";
    $desc = "File 'data_menu.php' not found";
    $menu = '';
    $content = '';
    $content2 = '';
}
include 'template/layout.php';
function createLinkMenu($href): string
{
    $page = $_GET['p'] ?? '1';
    if ($page == $href) {
        $classLinkMenu = " class='active'";
    } else {
        $classLinkMenu = '';
    }
    return "<div><a$classLinkMenu href=\"?p=$href\">Пример $href</a></div>";
}//  end  function createLinkMenu($href): string
function showContent($data, $p): string
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
    $content = creatForm($data);

    if (isset($_POST['button'])) {

        $content .= "<form><fieldset>";
        ob_start();
        include "pages/$p/index.php";
        $content .= ob_get_clean();
        $content .= "</fieldset></form>";
    }

    return $content;
}

function creatForm($data): string
{
    $content = "<form method=\"POST\"><fieldset>";
    foreach ($data as $arr) {
        // тут подумать !!!!
        if (($arr['name']) && isset($_POST['button'])) {
            $post = $_POST[$arr['name']];
        } else {
            $post = $arr['default'];
        }

        if ($arr['type'] == 'text') {
            $content .= "
              <label for=\"id_{$arr['name']}\">\${$arr['name']}:</label>
              <input type=\"text\" id = \"id_{$arr['name']}\" name=\"{$arr['name']}\" autocomplete=\"off\" value=\"$post\"><br><br> 
            ";
        }
        if ($arr['type'] == 'label') {
            $content .= "
              <label>$post:</label><br><br> 
            ";
        }

        if ($arr['type'] == 'textarea') {
            $content .= "<span>Текст: </span>
                  <textarea name=\"{$arr['name']}\" placeholder=\"Введите текст\"><?= $post ?></textarea><br>
                ";
        }
    }
    $content .= "<input type=\"submit\" name=\"button\" value=\"Результат\" />";
    $content .= " </fieldset></form>";
    return $content;
}

function showContent2($p): string
{
    return highlight_file("pages/$p/index.php", true);
}