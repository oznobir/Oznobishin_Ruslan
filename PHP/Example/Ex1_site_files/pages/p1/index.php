<?php
if (is_numeric($x)) {
    echo "<div>Результат выполнения:</div>";
    if ($x > 0) {
        echo "<div style ='color: green'>$x</div>";
    } elseif ($x < 0) {
        echo "<div style ='color: red'>$x</div>";
    } else {
        echo "<div style ='color: yellow'>$x</div>";
    }
} else {
    echo '<div>$x - не число</div>';
}