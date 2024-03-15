<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $desc ?>"/>
    <meta name="author" content="Oznobishin Ruslan">
    <meta name="publisher-email" content="oznobir@gmail.com">
    <meta name="robots" content="noindex,nofollow">
    <link rel="shortcut icon" type="image/x-icon" href="/project/access/favicon.ico">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="/project/access/style.css?v2.5">
    <script src="/project/access/fetch.js?v1.2"></script>
    <?= $content2_head ?>
</head>
<body>
<header>
    <h1>PHP</h1>
    <button class="main-menu-btn"><a href="/menu/">Содержание</a></button>
</header>
<main class="wrapper">
    <header>
        <h2><?= $title ?></h2>
        <p><?= $desc ?></p>
    </header>
    <section class="box-main">
        <nav class='box-menu'>
            <?= $menu ?>
        </nav>
        <div class="box-texts">
            <div class="box-text1">
                <?= $content1 ?>
                <div id="result"> ... </div>
            </div>
            <div class="box-text2">
                <?= $content2_tabs ?>
            </div>
        </div>
    </section>
</main>
<footer>
    by oznor
</footer>
<?= $content2_foot ?>
</body>
</html>
