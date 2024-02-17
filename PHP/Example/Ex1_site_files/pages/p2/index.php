<?php
if (is_numeric($a)
    && is_numeric($b)
    && is_numeric($c)) {
    if ($a > $b) {
        $max = $a;
        $min = $b;
    } else {
        $max = $b;
        $min = $a;
    }
    if ($c > $max) {
        $max = $c;
    } elseif ($c < $min) {
        $min = $c;
    }
    $sum = $min + $max;
    echo "<p>max = $max</p>";
    echo "<p>min = $min</p>";
    echo "<p>Их сумма = $sum</p>";
} else {
    echo "<p>Не числовые данные</p>";
}
