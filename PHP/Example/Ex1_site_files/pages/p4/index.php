<?php
if (is_numeric($a) && is_numeric($b) && is_numeric($c) && is_numeric($d)) {
    /* Сравниваем сначала a и b, затем c и d.
        В отдельную переменную записываем название переменной.
        Если значения переменных равны, то записываем названия обеих*/
    if ($a > $b) {
        $max1 = $a;
        $str_max1 = 'a';
    } elseif ($a < $b) {
        $max1 = $b;
        $str_max1 = 'b';
    } else {
        $max1 = $a;
        $str_max1 = 'a, b';
    }
    if ($c > $d) {
        $max2 = $c;
        $str_max2 = 'c';
    } elseif ($c < $d) {
        $max2 = $d;
        $str_max2 = 'd';
    } else {
        $max2 = $c;
        $str_max2 = 'c, d';
    }
    // Теперь сравниваем результаты предыдущих сравнений
    if ($max1 > $max2) {
        echo "<p>Максимальное - перем. $str_max1 со значением $max1</p>";
    } elseif ($max1 < $max2) {
        echo "<p>Максимальное - перем. $str_max2 со значением $max2</p>";
    } else {
        echo "<p>Максимальное - перем. $str_max1, $str_max2 со значением $max2</p>";
    }
} else {
    echo "<p>Не числовые данные</p>";
}