<?php /**
 * @var string $desc
 * @var string $title
 * @var array $mainMenu
 */?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $desc ?>"/>
    <meta name="author" content="Oznobishin Ruslan">
    <meta name="publisher-email" content="oznobir@gmail.com">
    <meta name="robots" content="noindex,nofollow">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="access/style.css?v2.5">

</head>
<body>
<header>
    <h1>PHP</h1>
    <?php //include 'template/info.php' ?>
</header>
<main class="wrapper">
    <nav>
        <?= $mainMenu ?>
    </nav>
</main>
<footer>
    by oznor
</footer>
</body>
</html>
