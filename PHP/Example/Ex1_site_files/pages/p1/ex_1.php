<?php

$x = $input1;
echo "<p>Результат выполнения:</p>";
if ($x > 0) {
    echo "<p class ='color_green'>$x</p>";
} elseif ($x < 0) {
    echo "<p class ='color_red'>$x</p>";
} else {
    echo "<p class ='color_yellow'>$x</p>";
}