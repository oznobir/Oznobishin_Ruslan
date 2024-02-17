<?php
return
    [
        'content1' => [
            ['name' => null, 'type' => 'label', 'default' => 'Введите сумму, руб.'],
            ['name' => 's', 'type' => 'text', 'default' => '100'],
            ['name' => null, 'type' => 'label', 'default' => 'Введите процент годовых, %'],
            ['name' => 'd', 'type' => 'text', 'default' => '10'],
        ],
        'content2' => [
            ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
        ],
    ];