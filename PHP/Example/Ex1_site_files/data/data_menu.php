<?php
return [
    '1' => ['desc' => 'Основы PHP', 'children' => [
        '1-1' => ['desc' => 'Типы данных', 'children' => [
            '1-1-1' => ['dir' => 'p9', 'desc' => 'Ошибки при различных операциях с данными.'],
        ]],
        '1-2' => ['desc' => 'Условный оператор IF', 'children' => [
            '1-2-1' => ['dir' => 'p1', 'desc' => 'Скрипт, который выводит для числа $x его значение разным цветом: зеленым - если $x положительно, желтым - если $x равно нулю, красным - если $x отрицательно. Используются только условные операторы.'],
            '1-2-2' => ['dir' => 'p2', 'desc' => 'Скрипт, определяющий сумму максимального и минимального из трех чисел $a, $b, $c. Используются только условные операторы.'],
            '1-2-3' => ['dir' => 'p3', 'desc' => 'Скрипт, определяющий максимальное из четырех чисел $a, $b, $c, $d. Используются только условные операторы.'],
            '1-2-4' => ['dir' => 'p4', 'desc' => 'Скрипт, определяющий максимальное из четырех чисел $a, $b, $c, $d. с выводом наименований переменных. Используются только условные операторы.'],
            '1-2-5' => ['dir' => 'p5', 'desc' => 'Скрипт, определяющий поместится ли товар в сумку, когда известны длина и ширина сумки $a, $b, а также длина и ширина товара $c, $d. Ввод длины и ширины в произвольном порядке. Используются только условные операторы.  Вариант 1.'],
            '1-2-6' => ['dir' => 'p6', 'desc' => 'Скрипт, определяющий поместится ли товар в сумку, когда известны длина и ширина сумки $a, $b, а также длина и ширина товара $c, $d. Ввод длины и ширины в произвольном порядке. Используются только условные операторы.  Вариант 2.'],
            '1-2-7' => ['dir' => 'p7', 'desc' => 'Скрипт, определяющий поместится ли товар в сумку, когда известны длина, ширина и высота сумки $a, $b, $e, а также длина, ширина и высота товара $c, $d , $f. Ввод длины, ширины и высоты в произвольном порядке. Используются только условные операторы. Вариант 1.'],
            '1-2-8' => ['dir' => 'p8', 'desc' => 'Скрипт, определяющий поместится ли товар в сумку, когда известны длина, ширина и высота сумки $a, $b, $e, а также длина, ширина и высота товара $c, $d , $f. Ввод длины, ширины и высоты в произвольном порядке. Используются только условные операторы. Вариант 2.'],
//            '1-2-8' => ['parent' => '1-2', 'dir' => 'p8', 'desc' => 'Скрипт, определяющий поместится ли товар в сумку, когда известны длина, ширина и высота сумки $a, $b, $e, а также длина, ширина и высота товара $c, $d, $f. Ввод длины, ширины и высоты в произвольном порядке. Используются только условные операторы. Вариант 2.'],
        ]],
        '1-3' => ['desc' => 'Операторы циклов', 'children' => [
            '1-3-1' => ['dir' => 'p10', 'desc' => 'Цикл while. Задача: через сколько лет сумма, вложенная в банк под определенный процент, удвоится?'],
        ]],
        '1-4' => ['desc' => 'Обработка массивов', 'children' => [
            '1-4-1' => ['dir' => 'p11', 'desc' => 'Сортировка массивов'],

        ]]
    ]],
    '2' => ['desc' => 'Объектно-ориентированное программирование в PHP ', 'children' => [
        #'2-1'
    ]],
    '3' => ['desc' => 'Работа с базой данных', 'children' => [
        #'3-1'
    ]],
    '4' => ['desc' => 'Обработка файлов', 'children' => [
        #'4-1'
    ]],
    '5' => ['desc' => 'Регулярные выражения', 'children' => [
        #'5-1'
    ]],
    '6' => ['desc' => 'Заголовки. Cookies. Сессии', 'children' => [
        #'6-1'
    ]]
];
