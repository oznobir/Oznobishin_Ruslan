<?php

header("Content-type: text/plain; charset=UTF-8");

if (isset($_POST['htmlspecialchars']) && $_POST['htmlspecialchars'] == 'ENT_NOQUOTES') {
    $flagENT = ENT_NOQUOTES;
} else $flagENT = ENT_QUOTES;
foreach ($_POST as $key => $item) {
    if ($key != 'htmlspecialchars') {
        if ($item != '') {
            $w = $key;
//        $$w = htmlspecialchars($_POST[$w], ENT_QUOTES, 'UTF-8');
            $$w = htmlspecialchars($_POST[$w], $flagENT, 'UTF-8');
        } else {
            echo 'Нет введенных данных';
            exit();
        }
    }
}
include $_FILES['php0']['tmp_name'];
//include "../$path";
