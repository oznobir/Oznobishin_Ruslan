<?php
if (is_numeric($a) && is_numeric($b)) {
    $y = $a**1000;
    var_dump($y);
    $z = sqrt($b);
    var_dump($z);
// Fatal error:
// Uncaught DivisionByZeroError: Division by zero
//$x = 5 / 0;
//var_dump($x);
} else {
    echo "<p>Не числовые данные</p>";
}