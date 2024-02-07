<?php
if (is_numeric($a) && is_numeric($b) && is_numeric($c) && is_numeric($d)) {
    if (($c <= $a && $d <= $b) || ($c <= $b && $d <= $a)) {
        echo "<p style ='color: green'> Товар поместится в сумку </p>";
    } else {
        echo "<p style ='color: red'> Товар не поместится в сумку</p>";
    }
} else {
    echo "<p>Не числовые данные</p>";
}