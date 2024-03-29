<?php
/** @var $descAdm *
** @var $titleAdm *
** @var $infoAdm *
** @var $contentAdm */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $descAdm ?>"/>
    <meta name="author" content="Oznobishin Ruslan">
    <meta name="publisher-email" content="oznobir@gmail.com">
    <meta name="robots" content="noindex,nofollow">
    <title><?= $titleAdm ?></title>
    <link rel="stylesheet" type="text/css" href="styleAdm.css">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="76x76" href="../img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/favicon/favicon-16x16.png">
    <link rel="manifest" href="../img/favicon/site.web-manifest">
    <link rel="mask-icon" href="../img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="application-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<div class="wrapper">
    <header>
        <h1><?= $titleAdm ?></h1>
        <a href="add.php">Add new page</a>
    </header>
    <main>
        <?php include 'infoAdm.php'?>
        <?= $contentAdm ?>
    </main>
    <footer>
        <h2>by site</h2>
    </footer>
</div>
</body>
</html>