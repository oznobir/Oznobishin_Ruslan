<?php
/** @var $menu */
/** @var $desc */
/** @var $title */
/** @var $content */
/** @var $content2 */
?>

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
    <link rel="stylesheet" type="text/css" href="css/style.css?v=4">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="76x76" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/site.web-manifest">
    <link rel="mask-icon" href="img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="application-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<div class="wrapper">
    <header>
        <?php include 'header.php' ?>
    </header>
    <main class="box-main">
        <nav class='box-menu'>
            <?= $menu?>
        </nav>
        <section class="box-texts">
            <div class="box-text1">
                <?= $content ?>
            </div>
            <div class="box-text2">
                <?= $content2 ?>
            </div>
        </section>
    </main>
    <footer>
        <?php include 'footer.php' ?>
    </footer>
</div>
</body>
</html>