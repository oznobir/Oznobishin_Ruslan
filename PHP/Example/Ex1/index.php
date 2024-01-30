<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="content Description"/>
    <meta name="author" content="Oznobishin Ruslan">
    <meta name="publisher-email" content="oznobir@gmail.com">
    <meta name="robots" content="noindex,nofollow">
    <title>Index 1</title>
    <link rel="stylesheet" href="css/style.css?v=2">
</head>
<body>
<div class="wrapper">
    <header>
        <?php include 'template/header.php' ?>
    </header>
    <main>
        text-contents !
        <?php
        // Только file exists. Без проверки $_GET['page']
        $page = $_GET['page'];
        $path = "pages/page$page.php";
        if (file_exists($path)) {
            include($path);
        } else {
            echo 'file not found';
        }
        echo '<br>';
        ?>
    </main>
    <footer>
        <?php include 'template/footer.php' ?>
    </footer>
</div>
</body>
</html>
