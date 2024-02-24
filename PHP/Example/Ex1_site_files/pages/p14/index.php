<?php
//$test1 = file_get_contents('test1.php');
//$test2 = file_get_contents('test2.php');
// Пока не работает через форму - htmlspecialchars
$test1 = 'echo "code ";';
$test2 = 'eval($test1); echo "1234 ";';
eval($test2);