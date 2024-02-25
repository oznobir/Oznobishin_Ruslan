<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $desc ?>"/>
    <meta name="author" content="Oznobishin Ruslan">
    <meta name="publisher-email" content="oznobir@gmail.com">
    <meta name="robots" content="noindex,nofollow">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="76x76" href="access/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="access/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="access/favicon/favicon-16x16.png">
    <link rel="manifest" href="access/favicon/site.web-manifest">
    <link rel="mask-icon" href="access/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="application-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="access/style.css?v1.28">
    <script src="access/fetch.js"></script>
    <?= $content2['head'] ?>
</head>
<body>
<header>
    <h1>PHP</h1>
    <button class="main-menu-btn"><a href="index.php?p=all">Содержание</a></button>
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
            </div>
            <div class="box-text2">
                <?= $content2['tabs'] ?>
            </div>
        </div>
    </section>
</main>
<footer>
    by oznor
</footer>
<?= $content2['foot'] ?>
</body>
</html>
