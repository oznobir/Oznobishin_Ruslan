<?php
return
    [
        'content1' => [
            ['name' => null, 'type' => 'label', 'default' => 'Введите test1 и test2'],
            ['name' => 'test1', 'type' => 'text', 'default' => "echo = 'code ';"],
            ['name' => 'test2', 'type' => 'text', 'default' => "eval(\$test1); echo '1234';"],
        ],
        'content2' => [
            ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
            ['name' => 'test2.php', 'type' => 'php', 'path' => 'test2.php'],
            ['name' => 'test1.php', 'type' => 'php', 'path' => 'test1.php'],
        ],
    ];