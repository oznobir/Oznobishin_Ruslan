<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, shrink-to-fit=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>
    <?php $this->getStyles(); ?>
</head>

<body>
<header class="header">
    <?= $this->header ?>
</header>
<?php if ($this->getController() !== 'index'): ?>
    <div class="search search-internal">
        <button>
            <svg class="inline-svg-icon svg-search">
                <use xlink:href="<?= PATH . SITE_TEMPLATE ?>assets/img/icons.svg#search"></use>
            </svg>
        </button>
        <input type="search" placeholder="Поиск по каталогу">
    </div>
<?php endif; ?>
<main class="main">
    <?= $this->content ?>
</main>
<footer class="footer">
    <?= $this->footer ?>
</footer>
<div class="hide-elems">
    <svg>
        <defs>
            <linearGradient id="rainbow" x1="0" y1="0" x2="50%" y2="50%">
                <stop offset="0%" stop-color="#7282bc"/>
                <stop offset="100%" stop-color="#7abfcc"/>
            </linearGradient>
        </defs>
    </svg>
</div>
<?php $this->getScripts(); ?>
</body>

</html>