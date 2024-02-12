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
    <link rel="stylesheet" type="text/css" href="css/style.css?v2">

</head>
<body>
<header>
    <h1>PHP</h1>
</header>
<nav>
    <div class="main-menu-container">
        <div class="main-menu">
            <div class="accor-group">
                <div class="as-title">Содержание</div>
                <span class="close-btn">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2.5"
                          stroke-linecap="round" stroke-linejoin="round">
                     <line x1="18" y1="6" x2="6" y2="18"></line>
                     <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </span>
                <?= $mainMenu ?>
            </div>
        </div>
    </div>
    <button class="main-menu-btn">Содержание</button>
</nav>
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
                <?= $content2 ?>
            </div>
        </div>
    </section>
    <footer>
        by oznor
    </footer>
</main>
<script>
    const main_menu_btn = document.querySelector('.main-menu-btn');
    const close_btn = document.querySelector('.close-btn');
    const main_menu_container = document.querySelector('.main-menu-container');
    main_menu_btn.addEventListener('click', () => {
        main_menu_container.classList.toggle('visible')
    });
    close_btn.addEventListener('click', () => {
        main_menu_container.classList.remove('visible')
    });
</script>
</body>
</html>
