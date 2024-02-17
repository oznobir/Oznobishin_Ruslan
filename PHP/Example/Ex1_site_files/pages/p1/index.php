<?php
if (is_numeric($x)) {
    echo "<div>Результат выполнения:</div>";
    if ($x > 0) {
        echo "<div class ='green'>$x</div>";
    } elseif ($x < 0) {
        echo "<div class ='red'>$x</div>";
    } else {
        echo "<div class ='yellow'>$x</div>";
    }
} else {
    echo '<div>$x - не число</div>';
}