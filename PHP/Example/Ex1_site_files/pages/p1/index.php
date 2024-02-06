<?php
$input1=$_POST['num1']?? -1;
include 'form.php';

if (isset($_POST['button'])) {
    $input1 = $_POST['num1'];
    include 'ex_1.php';
}
