<?php /**
 * @var string $menu
 * @var array $page
 * @var string $content_head
 * @var string $content
 * @var string $content_foot
 */ ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $page['description'] ?>"/>
    <meta name="author" content="Oznobishin Ruslan">
    <meta name="publisher-email" content="oznobir@gmail.com">
    <meta name="robots" content="noindex,nofollow">
    <link rel="shortcut icon" type="image/x-icon" href="/project/access/favicon.ico">
    <title><?= $page['title'] ?></title>
    <link rel="stylesheet" type="text/css" href="/project/access/style.css?v2.5">
    <script src="/project/access/fetch.js?v1.2"></script>
    <?= $content_head ?>
</head>
<body>
<header>
    <h1>PHP</h1>
    <button class="main-menu-btn"><a href="/menu/">Содержание</a></button>
</header>
<main class="wrapper">
    <?= $content ?>
</main>
<footer>
    by oznor
</footer>
<?= $content_foot ?>
</body>
</html>
