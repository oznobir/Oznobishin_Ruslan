<?php
$path ='';
header("Content-type: text/plain; charset=UTF-8");
foreach ($_POST as $key => $item) {
    if ($item !== '') {
        if ($key == 'path') {
            $path = $item;
        } else {
            $w = $key;
            $$w = htmlspecialchars($_POST[$w], ENT_QUOTES, 'UTF-8');
        }
    } else {
        echo 'Нет введенных данных';
        exit();
    }
}
include "../$path";