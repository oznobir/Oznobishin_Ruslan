<?php
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
