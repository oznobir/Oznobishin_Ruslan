<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 1</title>
    <style>
        body { width: 900px;}
        .color_green {
            color: green;
        }

        .color_red {
            color: red;
        }

        .color_yellow {
            color: yellow;
        }

        input, label {
            display: inline-block;
            cursor:pointer;
        }

    </style>
</head>

<body>
<h3>Задание 1</h3>
<form method="POST">
    <fieldset>
        <legend>Введите данные</legend>
        <label>x:
            <input type="number" name="num1" size="4" maxlength="5" autocomplete="off" max="1000" min="-1000"
                   step="1" value="<?= $input1 ?>">
        </label>
        <input type="submit" name="button" value="Результат" />
    </fieldset>
</form>
</body>
</html>