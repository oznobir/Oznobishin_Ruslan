<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $desc?>"/>
    <meta name="author" content="Oznobishin Ruslan">
    <meta name="publisher-email" content="oznobir@gmail.com">
    <meta name="robots" content="noindex,nofollow">
    <title><?= $title?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="wrapper">
    <header>
        <h1><?= $title?></h1>
        <p><?= $desc?></p>
    </header>
    <main class="box-main">
        <nav class='box-menu'>
            <?= $menu?>
        </nav>
        <section class="box-texts">
            <div class="box-text1">
                <?= $content1 ?>

            </div>
            <div class="box-text2">
                <?= $content2 ?>
            </div>
        </section>
    </main>
    <footer>
        by PHP
    </footer>
</div>
</body>
</html>
