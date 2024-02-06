<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $desc?>"/>
    <meta name="author" content="Oznobishin Ruslan">
    <meta name="publisher-email" content="oznobir@gmail.com">
    <meta name="robots" content="noindex,nofollow">
    <title><?= $title?></title>
<link rel="stylesheet" href="css/style.css?v=4">
</head>
<body>
<div class="wrapper">
    <header>
        <?php include 'header.php' ?>
    </header>
    <main>
        <?= $content?>
    </main>
    <footer>
        <?php include 'footer.php' ?>
    </footer>
</div>
</body>
</html>