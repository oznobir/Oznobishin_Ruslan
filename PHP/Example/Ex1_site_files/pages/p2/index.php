<?php
/* @var $data ; */

include 'data.php';
// Инициализация переменных $a $b $c со значением из _POST или из default в data.php
foreach ($data as $arr) {
    $w = $arr['name'];
    if (isset($_POST['button'])) {
        $post = $_POST[$w];
    } else {
        $post = $arr['default'];
    }
    $$w = $post;
}
$content = creatForm($data);
if (file_exists('ex_1.php')) {
//    $content2 = file_get_contents('ex_1.php');
    $content2 = highlight_file('ex_1.php', true);
} else {
    $content2 = '';
}
if (isset($_POST['button'])) {
// Отображение результата из ex_1.php
    $content .= "<form><fieldset>";
    ob_start();
    include 'ex_1.php';
    $content .= ob_get_clean();
    $content .= "</fieldset></form>";
}
include 'layout.php';
function creatForm($data): string
{
    $content = "<form method=\"POST\"><fieldset>";
    foreach ($data as $arr) {
        $w = $arr['name'];
        if (isset($_POST['button'])) {
            $post = $_POST[$arr['name']];
            $pattern = $_POST["pattern_{$arr['name']}"];
        } else {
            $post = $arr['default'];
            $pattern = $arr['pattern'];
        }
        $$w = $post;
        if ($arr['type'] == 'text') {
            $content .= "
              <label for=\"id_{$arr['name']}\">\${$arr['name']}:</label>
                <input type=\"text\" id = \"id_{$arr['name']}\" name=\"{$arr['name']}\" pattern=\"$pattern\" autocomplete=\"off\" value=\"$post\">
                <input type=\"text\" name=\"pattern_{$arr['name']}\" size=\"15\" value=\"$pattern\"/>
              <br><br> 
            ";
        } // pattern потом убрать
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