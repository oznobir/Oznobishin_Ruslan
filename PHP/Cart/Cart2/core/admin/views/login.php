<?php /**
 * @var string $adminPath
 */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta type="keywords" content="Страница авторизации - административная панель">
    <meta type="description" content="Страница авторизации - административная панель">
    <meta name="author" content="Oznor">
    <meta name="publisher-email" content="oznobir@gmail.com">
    <link rel="shortcut icon" type="image/x-icon" href="<?= PATH ?>favicon.ico">
    <title>Страница авторизации</title>
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .vg-auth {
            flex-basis: 600px;
            padding: 10px;
        }

        form, label, input, .vg-info {
            display: block;
            margin: auto;
        }

        h1, label, .vg-info {
            text-align: center;
        }

        .vg-info {
            color: red;
            padding: 5px 5px;
        }

        input {
            margin-bottom: 20px;
            padding: 5px 5px;
        }

        input[type=submit] {
            background: #fff;
            padding: 5px 15px;
            border: 1px solid black;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="vg-auth">
    <?php if (isset($_SESSION['res']['answer'])) : ?>
        <div class="vg-info"><?= $_SESSION['res']['answer'] ?></div>
        <?php unset($_SESSION['res']); endif; ?>
    <h1>Авторизация</h1>
    <form method="post" action="<?= PATH . $adminPath ?>/login">
        <label for="login">Логин</label>
        <input type="text" name="login" id="login">
        <label for="password">Пароль</label>
        <input type="password" name="password" id="password">
        <input type="submit" value="Войти">
    </form>
</div>
<script src="<?= PATH . ADMIN_TEMPLATE ?>js/functions.js"></script>
<script>
    let form = document.querySelector('form')
    if (form) {
        form.addEventListener('submit', e => {
            if (e.isTrusted) {
                e.preventDefault()
                Ajax({res: 'text', data: {ajax: 'token'}})
                    .then(res => {
                        if (res)
                            form.insertAdjacentHTML('beforeend', `<input type="hidden" name="token" value="${res}">`)
                        form.submit()
                    })
            }
        })
    }
</script>
</body>
</html>
