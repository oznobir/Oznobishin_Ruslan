<?php
if (is_numeric($a) && is_numeric($b)
    && is_numeric($c) && is_numeric($d)) {
    // Длину, ширину, высоту сумки
    // по убыванию значений
    if ($a >= $b && $b >= $e) {
        $bag_max = $a;
        $bag_sr = $b;
        $bag_min = $e;
    }
    if ($a >= $e && $e >= $b) {
        $bag_max = $a;
        $bag_sr = $e;
        $bag_min = $b;
    }
    if ($e >= $a && $a >= $b) {
        $bag_max = $e;
        $bag_sr = $a;
        $bag_min = $b;
    }
    if ($e >= $b && $b >= $a) {
        $bag_max = $e;
        $bag_sr = $b;
        $bag_min = $a;
    }
    if ($b >= $a && $a >= $e) {
        $bag_max = $b;
        $bag_sr = $a;
        $bag_min = $e;
    }
    if ($b >= $e && $e >= $a) {
        $bag_max = $b;
        $bag_sr = $e;
        $bag_min = $a;
    }
    // Длину, ширину, высоту товара
    // по убыванию значений
    if ($c >= $d && $d >= $f) {
        $goods_max = $c;
        $goods_sr = $d;
        $goods_min = $f;
    }
    if ($c >= $f && $f >= $d) {
        $goods_max = $c;
        $goods_sr = $f;
        $goods_min = $d;
    }
    if ($f >= $c && $c >= $d) {
        $goods_max = $f;
        $goods_sr = $c;
        $goods_min = $d;
    }
    if ($f >= $d && $d >= $c) {
        $goods_max = $f;
        $goods_sr = $d;
        $goods_min = $c;
    }
    if ($d >= $c && $c >= $f) {
        $goods_max = $d;
        $goods_sr = $c;
        $goods_min = $f;
    }
    if ($d >= $f && $f >= $c) {
        $goods_max = $d;
        $goods_sr = $f;
        $goods_min = $c;
    }
    if ($bag_max >= $goods_max
        && $bag_sr >= $goods_sr
        && $bag_min >= $goods_min) {
        echo "<p style='color: green'> 
                Товар поместится в сумку
              </p>";
    } else {
        echo "<p style='color: red'> 
                Товар не поместится в сумку
              </p>";
    }
} else {
    echo "<p>Не числовые данные</p>";
}