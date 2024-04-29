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
    <title>Управление сайтом</title>
    <?php foreach ($this->styles as $style): ?>
        <link rel="stylesheet" href="<?= $style ?>">
    <?php endforeach; ?>
</head>
<body>
<?= $this->header ?>
<div class="vg-carcass vg-hide">
    <div class="vg-main vg-right vg-relative">
        <?= $this->contentMenu ?>
        <?= $this->contentCenter ?>
    </div><!--.vg-main.vg-right-->
</div><!--.vg-carcass-->
<?= $this->footer ?>
</body>
</html>
