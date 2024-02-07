<?php
if (is_numeric($a) && is_numeric($b) && is_numeric($c) && is_numeric($d)) {
    if ($a >= $b) {
        $max1 = $a;
    } else {
        $max1 = $b;
    }
    if ($c >= $d) {
        $max2 = $c;
    } else {
        $max2 = $d;
    }
// Теперь сравниваем результаты предыдущих сравнений
    if ($max1 >= $max2) {
        echo "<p>Максимальное значение: $max1</p>";
    } else {
        echo "<p>Максимальное значение: $max2</p>";
    }
}else{
    echo "<p>Не числовые данные</p>";
}