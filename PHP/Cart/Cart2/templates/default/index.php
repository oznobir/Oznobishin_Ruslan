<?php /**
 * @var string $name
 * @var string $name1
 * @var string $header
 *@var string $footer
 */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta type="keywords" content="Управление сайтом - административная панель">
    <meta type="description" content="Управление сайтом - административная панель">
    <meta name="author" content="Oznor">
    <meta name="publisher-email" content="oznobir@gmail.com">
    <link rel="shortcut icon" type="image/x-icon" href="<?= PATH ?>favicon.ico">
    <title>Сайт</title>
</head>
<body>
<?= $header ?>
<?php d($name); ?>
<?= $footer ?>
</body>
</html>