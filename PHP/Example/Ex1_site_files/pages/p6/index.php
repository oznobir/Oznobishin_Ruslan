<?php
if (is_numeric($a) && is_numeric($b) && is_numeric($c) && is_numeric($d)) {
    if ($a >= $b) {
        $bag_max = $a;
        $bag_min = $b;
    } else {
        $bag_max = $b;
        $bag_min = $a;
    }
    if ($c >= $d) {
        $goods_max = $c;
        $goods_min = $d;
    } else {
        $goods_max = $d;
        $goods_min = $c;
    }
    if ($bag_max >= $goods_max && $bag_min >= $goods_min) {
        echo "<p style='color: green'> Товар поместится в сумку</p>";
    } else {
        echo "<p style='color: red'> Товар не поместится в сумку</p>";
    }
} else {
    echo "<p>Не числовые данные</p>";
}