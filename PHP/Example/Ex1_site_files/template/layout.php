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
    <link rel="stylesheet" type="text/css" href="access/style.css?v2">
    <script>
        async function sendRequest() {
            let my_form = new FormData(document.forms.form);
            const lists = document.querySelectorAll('.text2-panel.php');
            let blob = [];
            let path = [];
            lists.forEach(function(elem, ind) {
                blob[ind] = new Blob([elem.innerText.replace(/ /gi, ' ')], {type: 'text/html'});
                path[ind] = elem.children[1].value;
                my_form.append("php" + ind, blob[ind], path[ind]);
            });
            let response = await fetch('template/response.php', {
                method: 'POST', body: my_form
            });
            if (response.ok) {
                document.getElementById("result").innerHTML = await response.text();
            }
        }
    </script>
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
