<?php
return
    [
        'content1' => [
            ['name' => null, 'type' => 'label', 'default' => 'Внимание! В htmlspecialchars для этого примера установлен flag: ENT_NOQUOTES'],
            ['name' => 'htmlspecialchars', 'type' => 'security', 'default' => 'ENT_NOQUOTES'],
            ['name' => null, 'type' => 'label', 'default' => 'Введите test1 и test2'],
            ['name' => 'test1', 'type' => 'text', 'default' => "\$id = 100;"],
            ['name' => 'test2', 'type' => 'text', 'default' => "eval(\$test1); echo \$id;"],
        ],
        'content2' => [
            ['name' => 'index.php', 'type' => 'php', 'path' => 'index.php'],
            ['name' => 'test2.php', 'type' => 'php', 'path' => 'test/test2.php'],
            ['name' => 'test1.php', 'type' => 'php', 'path' => 'test/test1.php'],
        ],
    ];