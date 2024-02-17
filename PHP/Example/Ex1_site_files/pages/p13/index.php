<?php
if (is_numeric($n)) {
$x = 0;
$dx = 1/$n;
for ($i = 1; $i <= $n; $i++) {
    $x += $dx;
}
$y = 1-$x;
echo "<p>(1/$n)*$n = $x</p>";
echo "<p>1 - \$x = $y</p>";
} else {
    echo "<p>Не числовые данные</p>";
}