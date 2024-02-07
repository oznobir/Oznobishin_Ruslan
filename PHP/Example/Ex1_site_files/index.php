<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

$page = $_GET['p'] ?? '2';

//$path = "pages/p$page";
$data = include "data/data_p$page.php";
//var_dump($data);
$content = showContent($data, $page);
$content2 = showContent2($page);
include 'template/layout.php';
function showContent($data, $page): string
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
        include "pages/p$page/index.php";
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

function showContent2($page): string
{
    return highlight_file("pages/p$page/index.php", true);
}