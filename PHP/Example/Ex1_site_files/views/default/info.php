<?php
if (isset($_SESSION ['message'])) {
    $status = $_SESSION ['message']['status'];
    $text = $_SESSION ['message']['text'];
    echo "<div class='$status'>$text</div>";
    unset ($_SESSION ['message']);
}
