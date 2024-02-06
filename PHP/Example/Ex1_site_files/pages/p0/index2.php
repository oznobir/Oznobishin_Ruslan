<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            width: 500px;
        }

        .color_green {
            color: green;
        }

        .color_red {
            color: red;
        }

        .color_yellow {
            color: yellow;
        }

        input {
            display: inline;
        }
    </style>
</head>

<body>
    <!------------- Задание 1 ----------->
    <h3>Задание 1</h3>
    <form method="POST">
        <fieldset>
            <legend>Введите число</legend>
            <label>x:
                <input type="number" name="z1_x" size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value="<?php echo $_POST['z1_x'] ?? -1; ?>">
            </label>
            <input style="cursor:pointer;" type="submit" name="button_1" value="Результат" />
        </fieldset>
    </form>
    <?php
    # Если кнопка нажата

    if (isset($_POST['button_1'])) {
        var_dump($_POST['button_1']);
        $x = $_POST['z1_x'];
        var_dump($_POST['z1_x']);
        if ($x > 0) {
            echo "<p class ='color_green'>$x</p>";
        } elseif ($x < 0) {
            echo "<p class ='color_red'>$x</p>";
        } else {
            echo "<p class ='color_yellow'>$x</p>";
        }
    }
    ?>
    <hr>
    <!------------- Задание 2 ----------->
    <h3>Задание 2</h3>
    <form method="POST">
        <fieldset>
            <legend>Введите числа</legend>
            <label>a:
                <input type="number" name="z2_a" size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value="<?php echo $_POST['z2_a'] ?? 1; ?>">
            </label>
            <label>b:
                <input type="number" name='z2_b' size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value='<?php echo $_POST['z2_b'] ?? 2; ?>'>
            </label>
            <label>c:
                <input type="number" name='z2_c' size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value='<?php echo $_POST['z2_c'] ?? 3; ?>'>
            </label>
            <input style="cursor:pointer;" type="submit" name="button_2" value="Результат" />
        </fieldset>
    </form>
    <?php
    # Если кнопка нажата
    if (isset($_POST['button_2'])) {
        $a = $_POST['z2_a'];
        $b = $_POST['z2_b'];
        $c = $_POST['z2_c'];
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
        echo "<p>их сумма = $sum</p>";
    }
    ?>

    <hr>
    <!------------- Задание 3 ----------->
    <h3>Задание 3</h3>
    <form method="POST">
        <fieldset>
            <legend>Введите числа</legend>
            <label>a:
                <input type="number" name="z3_a" size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value="<?php echo $_POST['z3_a'] ?? 1; ?>">
            </label>
            <label>b:
                <input type="number" name='z3_b' size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value='<?php echo $_POST['z3_b'] ?? 2; ?>'>
            </label>
            <label>c:
                <input type="number" name='z3_c' size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value='<?php echo $_POST['z3_c'] ?? 3; ?>'>
            </label>
            <label>d:
                <input type="number" name='z3_d' size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value='<?php echo $_POST['z3_d'] ?? 3; ?>'>
            </label>
            <input style="cursor:pointer;" type="submit" name="button_3" value="Результат" />
        </fieldset>
    </form>
    <?php
    if (isset($_POST['button_3'])) {
        $a = $_POST['z3_a'];
        $b = $_POST['z3_b'];
        $c = $_POST['z3_c'];
        $d = $_POST['z3_d'];
        // Сравниваем сначала a и b, затем c и d
        if ($a >= $b) {
            $max1 = $a;
        } else {
            $max1 = $b;
        }
        if ($c >= $d) {
            $max2 = $c;
        } else {
            $max2 = $d;
        }
        // Теперь сравниваем результаты предыдущих сравнений
        if ($max1 >= $max2) {
            echo "<p>Максимальное значение: $max1</p>";
        } else {
            echo "<p>Максимальное значение: $max2</p>";
        }
    }
    ?>
    <hr>
    <!------------- Задание 3 ----------->
    <!-----С выведением названий переменных---->
    <h3>Задание 3. С выведением названий переменных</h3>
    <form method="POST">
        <fieldset>
            <legend>Введите числа</legend>
            <label>a:
                <input type="number" name="z32_a" size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value="<?php echo $_POST['z32_a'] ?? 1; ?>">
            </label>
            <label>b:
                <input type="number" name='z32_b' size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value='<?php echo $_POST['z32_b'] ?? 2; ?>'>
            </label>
            <label>c:
                <input type="number" name='z32_c' size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value='<?php echo $_POST['z32_c'] ?? 3; ?>'>
            </label>
            <label>d:
                <input type="number" name='z32_d' size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                    step="1" value='<?php echo $_POST['z32_d'] ?? 3; ?>'>
            </label>
            <input style="cursor:pointer;" type="submit" name="button_32" value="Результат" />
        </fieldset>
    </form>
    <?php
    if (isset($_POST['button_32'])) {
        $a = $_POST['z32_a'];
        $b = $_POST['z32_b'];
        $c = $_POST['z32_c'];
        $d = $_POST['z32_d'];
        /* Сравниваем сначала a и b, затем c и d. 
        В отдельную переменную записываем название переменной.
        Если значения переменных равны, то записываем названия обох*/
        if ($a > $b) {
            $max1 = $a;
            $str_max1 = 'a';
        } elseif ($a < $b) {
            $max1 = $b;
            $str_max1 = 'b';
        } else {
            $max1 = $a;
            $str_max1 = 'a, b';
        }
        if ($c > $d) {
            $max2 = $c;
            $str_max2 = 'c';
        } elseif ($c < $d) {
            $max2 = $d;
            $str_max2 = 'd';
        } else {
            $max2 = $c;
            $str_max2 = 'c, d';
        }
        // Теперь сравниваем результаты предыдущих сравнений
        if ($max1 > $max2) {
            echo "<p>Максимальное - перем. $str_max1 со значением $max1</p>";
        } elseif ($max1 < $max2) {
            echo "<p>Максимальное - перем. $str_max2 со значением $max2</p>";
        } else {
            echo "<p>Максимальное - перем. $str_max1, $str_max2 со значением $max2</p>";
        }
    }
    ?>
    <hr>
    <!------------- Задание 4 ----------->
    <h3>Задание 4</h3>
    <form method="POST">
        <fieldset>
            <legend>Введите размеры</legend>
            <label>сумки - a:
                <input type="number" name="z4_a" size="4" maxlength="5" autocomplete="off" max="1000" min="1" step="1"
                    value="<?php echo $_POST['z4_a'] ?? 1; ?>">
            </label>
            <label>b:
                <input type="number" name='z4_b' size="4" maxlength="5" autocomplete="off" max="1000" min="1" step="1"
                    value='<?php echo $_POST['z4_b'] ?? 2; ?>'>
            </label> <br>
            <label>товара - c:
                <input type="number" name='z4_c' size="4" maxlength="5" autocomplete="off" max="1000" min="1" step="1"
                    value='<?php echo $_POST['z4_c'] ?? 3; ?>'>
            </label>
            <label>d:
                <input type="number" name='z4_d' size="4" maxlength="5" autocomplete="off" max="1000" min="1" step="1"
                    value='<?php echo $_POST['z4_d'] ?? 2; ?>'>
            </label> <br>
            <input style="cursor:pointer;" type="submit" name="button_4" value="Результат" />
        </fieldset>
    </form>
    <?php

    if (isset($_POST['button_4'])) {
        // Сумка
        $a = $_POST['z4_a'];
        $b = $_POST['z4_b'];
        // Товар
        $c = $_POST['z4_c'];
        $d = $_POST['z4_d'];
        ////////////////////////////////////////////////
        echo "<p>2 вариант</p>";
        if (($c <= $a && $d <= $b) || ($c <= $b && $d <= $a)) {
            echo "<p class ='color_green'> Товар поместится в сумку </p>";
        } else {
            echo "<p class='color_red'> Товар не поместится в сумку</p>";
        }
        /////////////////////////////////////////////////
        echo "<p>1 вариант</p>";
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
        // Толщина стенок сумки не учитывается, для лучшего перебирания значений
        if ($bag_max >= $goods_max && $bag_min >= $goods_min) {
            echo "<p class ='color_green'> Товар поместится в сумку</p>";
        } else {
            echo "<p class='color_red'> Товар не поместится в сумку</p>";
        }
    }
    ?>
    <hr>
    <!------------- Задание 4 ----------->
    <!-----С добавлением высоты --------->
    <h3>Задание 4. С добавлением высоты</h3>
    <form method="POST">
        <fieldset>
            <legend>Введите размеры</legend>
            <label> сумки - a:
                <input type="number" name="z42_a" size="4" maxlength="5" autocomplete="off" max="1000" min="1" step="1"
                    value="<?php echo $_POST['z42_a'] ?? 1; ?>">
            </label>
            <label>b:
                <input type="number" name='z42_b' size="4" maxlength="5" autocomplete="off" max="1000" min="1" step="1"
                    value='<?php echo $_POST['z42_b'] ?? 2; ?>'>
            </label>
            <label>e:
                <input type="number" name='z42_e' size="4" maxlength="5" autocomplete="off" max="1000" min="1" step="1"
                    value='<?php echo $_POST['z42_e'] ?? 2; ?>'>
            </label><br>
            <label>товара - c:
                <input type="number" name='z42_c' size="4" maxlength="5" autocomplete="off" max="1000" min="1" step="1"
                    value='<?php echo $_POST['z42_c'] ?? 3; ?>'>
            </label>
            <label>d:
                <input type="number" name='z42_d' size="4" maxlength="5" autocomplete="off" max="1000" min="1" step="1"
                    value='<?php echo $_POST['z42_d'] ?? 2; ?>'>
            </label>
            <label>f:
                <input type="number" name='z42_f' size="4" maxlength="5" autocomplete="off" max="1000" min="1" step="1"
                    value='<?php echo $_POST['z42_f'] ?? 2; ?>'>
            </label><br>
            <input style="cursor:pointer;" type="submit" name="button_42" value="Результат" />
        </fieldset>
    </form>
    <?php
    if (isset($_POST['button_42'])) {
        // Сумка
        $a = $_POST['z42_a'];
        $b = $_POST['z42_b'];
        $e = $_POST['z42_e'];
        // Товар
        $c = $_POST['z42_c'];
        $d = $_POST['z42_d'];
        $f = $_POST['z42_f'];
        ////////////////////////////////////////////////////////////////////
        //////cdf
        //abe
        //aeb
        //bae
        //bea
        //eab
        //eba
        echo "<p>2 вариант</p>";
        if (
            ($c <= $a && $d <= $b && $f <= $e)
            || ($c <= $a && $d <= $e && $f <= $b)
            || ($c <= $b && $d <= $a && $f <= $e)
            || ($c <= $b && $d <= $e && $f <= $a)
            || ($c <= $e && $d <= $a && $f <= $b)
            || ($c <= $e && $d <= $b && $f <= $a)
        ) {
            echo "<p class ='color_green'> Товар поместится в сумку</p>";
        } else {
            echo "<p class='color_red'> Товар не поместится в сумку</p>";
        }
        ////////////////////////////////////////////////////////////////////
        echo "<p>1 вариант</p>";
        // Длину, ширину, высоту сумки по убыванию значений
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
        // Длину, ширину, высоту товара по убыванию значений
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
        // Толщина стенок сумки не учитывается, для лучшего перебирания значений
        if ($bag_max >= $goods_max && $bag_sr >= $goods_sr && $bag_min >= $goods_min) {
            echo "<p class ='color_green'> Товар поместится в сумку</p>";
        } else {
            echo "<p class='color_red'> Товар не поместится в сумку</p>";
        }
    }
    ?>
</body>

</html>