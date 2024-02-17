<?php
return
    [
        'content1' => [
            ['name' => null, 'type' => 'label', 'default' => 'Введите в переменную $a значение'],
            ['name' => 'a', 'type' => 'text', 'default' => '10'],
            ['name' => null, 'type' => 'label', 'default' => 'Введите в переменную $b значение'],
            ['name' => 'b', 'type' => 'text', 'default' => '-5'],
        ],
        'content2' => [
            ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
        ],
    ];