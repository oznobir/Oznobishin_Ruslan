<?php
if (is_numeric($a) && is_numeric($b) && is_numeric($c) && is_numeric($d)) {
    //cdf сравниваем с
    //abe
    //aeb
    //bae
    //bea
    //eab
    //eba
    if (($c <= $a && $d <= $b && $f <= $e)
        || ($c <= $a && $d <= $e && $f <= $b)
        || ($c <= $b && $d <= $a && $f <= $e)
        || ($c <= $b && $d <= $e && $f <= $a)
        || ($c <= $e && $d <= $a && $f <= $b)
        || ($c <= $e && $d <= $b && $f <= $a)
    ) {
        echo "<p style='color: green'> Товар поместится в сумку</p>";
    } else {
        echo "<p style='color: red'> Товар не поместится в сумку</p>";
    }
} else {
    echo "<p>Не числовые данные</p>";
}