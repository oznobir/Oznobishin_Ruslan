<?php
/**
 * @var string $description
 * @var string $title
 * @var string $content
 */?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $description ?>"/>
    <meta name="author" content="Oznor">
    <meta name="publisher-email" content="oznobir@gmail.com">
    <meta name="robots" content="noindex,nofollow">
    <link rel="shortcut icon" type="image/x-icon" href="/project/access/favicon.ico">
    <title><?= $title ?></title>
    <link href="/project/access/css/style.css?v1.26" rel="stylesheet">
</head>
<body>
<header>
    <div class="header">
        <h1>My shop интернет-магазин</h1>
    </div>
</header>
<?= $content ?>
<footer>
    <div class="footer">
        Footer
    </div>

</footer>
</body>
</html>
