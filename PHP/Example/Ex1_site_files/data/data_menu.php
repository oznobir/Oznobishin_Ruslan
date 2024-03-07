<?php
return [
    '1' => ['desc' => 'Основы PHP', 'children' => [
        '1-1' => ['desc' => 'Типы данных', 'children' => [
            '1-1-1' => ['dir' => 'p9', 'content1' => [
                ['name' => null, 'type' => 'label', 'default' => 'Введите в переменную $a значение'],
                ['name' => 'a', 'type' => 'text', 'default' => '10'],
                ['name' => null, 'type' => 'label', 'default' => 'Введите в переменную $b значение'],
                ['name' => 'b', 'type' => 'text', 'default' => '-5'],
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
            ], 'desc' => 'Ошибки при различных операциях с данными № 1.'],
            '1-1-2' => ['dir' => 'p13', 'content1' => [
                ['name' => null, 'type' => 'label', 'default' => 'Введите $n'],
                ['name' => 'n', 'type' => 'text', 'default' => '10000'],
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
            ], 'desc' => 'Ошибки при различных операциях с данными № 2.'],
        ]],
        '1-2' => ['desc' => 'Условный оператор IF', 'children' => [
            '1-2-1' => ['dir' => 'p1', 'content1' => [
                ['name' => 'x', 'type' => 'text', 'default' => '-3']
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
                ['name' => 'style.css', 'type' => 'css', 'path' => 'style.css'],
            ], 'desc' => 'Вывести для числа $x его значение разным цветом: зеленым - если $x положительно, желтым - если $x равно нулю, красным - если $x отрицательно. Используются только условные операторы.'],
            '1-2-2' => ['dir' => 'p2', 'content1' => [
                ['name' => 'a', 'type' => 'text', 'default' => '10'],
                ['name' => 'b', 'type' => 'text', 'default' => '14'],
                ['name' => 'c', 'type' => 'text', 'default' => '8']
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
            ], 'desc' => 'Определить сумму максимального и минимального из трех чисел $a, $b, $c. Используются только условные операторы.'],
            '1-2-3' => ['dir' => 'p3', 'content1' => [
                ['name' => 'a', 'type' => 'text', 'default' => '10'],
                ['name' => 'b', 'type' => 'text', 'default' => '14'],
                ['name' => 'c', 'type' => 'text', 'default' => '8'],
                ['name' => 'd', 'type' => 'text', 'default' => '3']
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
            ], 'desc' => 'Определить максимальное из четырех чисел $a, $b, $c, $d. Используются только условные операторы.'],
            '1-2-4' => ['dir' => 'p4', 'content1' => [
                ['name' => 'a', 'type' => 'text', 'default' => '14'],
                ['name' => 'b', 'type' => 'text', 'default' => '14'],
                ['name' => 'c', 'type' => 'text', 'default' => '8'],
                ['name' => 'd', 'type' => 'text', 'default' => '3']
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
            ], 'desc' => 'Определить максимальное из четырех чисел $a, $b, $c, $d. с выводом наименований переменных. Используются только условные операторы.'],
            '1-2-5' => ['dir' => 'p5', 'content1' => [
                ['name' => null, 'type' => 'label', 'default' => 'Размеры сумки'],
                ['name' => 'a', 'type' => 'text', 'default' => '15'],
                ['name' => 'b', 'type' => 'text', 'default' => '13'],
                ['name' => null, 'type' => 'label', 'default' => 'Размеры товара'],
                ['name' => 'c', 'type' => 'text', 'default' => '13'],
                ['name' => 'd', 'type' => 'text', 'default' => '10']
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
                ['name' => 'style.css', 'type' => 'css', 'path' => 'style.css'],
            ], 'desc' => 'Определить поместится ли товар в сумку, когда известны длина и ширина сумки $a, $b, а также длина и ширина товара $c, $d. Ввод длины и ширины в произвольном порядке. Используются только условные операторы.  Вариант 1.'],
            '1-2-6' => ['dir' => 'p6', 'content1' => [
                ['name' => null, 'type' => 'label', 'default' => 'Размеры сумки'],
                ['name' => 'a', 'type' => 'text', 'default' => '15'],
                ['name' => 'b', 'type' => 'text', 'default' => '13'],
                ['name' => null, 'type' => 'label', 'default' => 'Размеры товара'],
                ['name' => 'c', 'type' => 'text', 'default' => '13'],
                ['name' => 'd', 'type' => 'text', 'default' => '11']
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
                ['name' => 'style.css', 'type' => 'css', 'path' => 'style.css'],
            ], 'desc' => 'Определить поместится ли товар в сумку, когда известны длина и ширина сумки $a, $b, а также длина и ширина товара $c, $d. Ввод длины и ширины в произвольном порядке. Используются только условные операторы.  Вариант 2.'],
            '1-2-7' => ['dir' => 'p7', 'content1' => [
                ['name' => null, 'type' => 'label', 'default' => 'Размеры сумки'],
                ['name' => 'a', 'type' => 'text', 'default' => '15'],
                ['name' => 'b', 'type' => 'text', 'default' => '13'],
                ['name' => 'e', 'type' => 'text', 'default' => '11'],
                ['name' => null, 'type' => 'label', 'default' => 'Размеры товара'],
                ['name' => 'c', 'type' => 'text', 'default' => '13'],
                ['name' => 'd', 'type' => 'text', 'default' => '11'],
                ['name' => 'f', 'type' => 'text', 'default' => '9']
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
                ['name' => 'style.css', 'type' => 'css', 'path' => 'style.css'],
            ], 'desc' => 'Определить поместится ли товар в сумку, когда известны длина, ширина и высота сумки $a, $b, $e, а также длина, ширина и высота товара $c, $d , $f. Ввод длины, ширины и высоты в произвольном порядке. Используются только условные операторы. Вариант 1.'],
            '1-2-8' => ['dir' => 'p8', 'content1' => [
                ['name' => null, 'type' => 'label', 'default' => 'Размеры сумки'],
                ['name' => 'a', 'type' => 'text', 'default' => '15'],
                ['name' => 'b', 'type' => 'text', 'default' => '13'],
                ['name' => 'e', 'type' => 'text', 'default' => '11'],
                ['name' => null, 'type' => 'label', 'default' => 'Размеры товара'],
                ['name' => 'c', 'type' => 'text', 'default' => '13'],
                ['name' => 'd', 'type' => 'text', 'default' => '11'],
                ['name' => 'f', 'type' => 'text', 'default' => '12']
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
            ], 'desc' => 'Определить поместится ли товар в сумку, когда известны длина, ширина и высота сумки $a, $b, $e, а также длина, ширина и высота товара $c, $d , $f. Ввод длины, ширины и высоты в произвольном порядке. Используются только условные операторы. Вариант 2.'],
        ]],
        '1-3' => ['desc' => 'Операторы циклов', 'children' => [
            '1-3-1' => ['dir' => 'p10', 'content1' => [
                ['name' => null, 'type' => 'label', 'default' => 'Введите сумму, руб.'],
                ['name' => 's', 'type' => 'text', 'default' => '100'],
                ['name' => null, 'type' => 'label', 'default' => 'Введите процент годовых, %'],
                ['name' => 'd', 'type' => 'text', 'default' => '10'],
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
            ], 'desc' => 'Цикл while. Через сколько лет сумма, вложенная в банк под определенный процент, удвоится?'],
            '1-3-2' => ['dir' => 'p12', 'content1' => [
                ['name' => null, 'type' => 'label', 'default' => 'Введите количество чисел'],
                ['name' => 'n', 'type' => 'text', 'default' => '10'],

            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
            ], 'desc' => 'Операторы continue, break. Найти сумму ряда случайных чисел, которые не больше 50.'],

        ]],
        '1-4' => ['desc' => 'Обработка массивов', 'children' => [
            '1-4-1' => ['dir' => 'p11', 'content1' => [
                ['name' => null, 'type' => 'label', 'default' => 'Введите количество чисел'],
                ['name' => 'n', 'type' => 'text', 'default' => '10'],

            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
                ['name' => 'style.css', 'type' => 'css', 'path' => 'style.css'],
            ], 'desc' => 'Сортировка массивов различными способами'],

        ]],
        '1-5' => ['desc' => 'Базовые функции PHP', 'children' => [
            '1-5-1' => ['dir' => 'p14', 'content1' => [
                ['name' => null, 'type' => 'label', 'default' => 'Внимание! В htmlspecialchars для этого примера установлен flag: ENT_NOQUOTES'],
                ['name' => 'htmlspecialchars', 'type' => 'security', 'default' => 'ENT_NOQUOTES'],
                ['name' => null, 'type' => 'label', 'default' => 'Введите test1 и test2'],
                ['name' => 'test1', 'type' => 'text', 'default' => "\$id = 100;"],
                ['name' => 'test2', 'type' => 'text', 'default' => "eval(\$test1); echo \$id;"],
            ], 'content2' => [
                ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
                ['name' => 'test2.php', 'type' => 'php', 'path' => 'test/test2.php'],
                ['name' => 'test1.php', 'type' => 'php', 'path' => 'test/test1.php'],
            ], 'desc' => 'Пример работы функции eval()'],

        ]],
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
