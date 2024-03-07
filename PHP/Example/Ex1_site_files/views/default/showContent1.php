<form name="form" method='post'>
    <label type="text">Исходные данные</label><br>
    <?php foreach ($data['content1'] as $arr) {
        if ($arr['type'] == 'text') { ?>
            <label for="<?= $arr['name'] ?>">$<?= $arr['name'] ?>:</label>
            <input type="text" id="<?= $arr['name'] ?>" name="<?= $arr['name'] ?>" autocomplete="off"
                   value="<?= $arr['default'] ?>"><br><br>
        <?php }
        if ($arr['type'] == 'label') { ?>
            <label><?= $arr['default'] ?>:</label><br><br>
        <?php }
        if ($arr['type'] == 'security') { ?>
            <input type="hidden" name="<?= $arr['name'] ?>" value="<?= $arr['default'] ?>">
        <?php }
        if ($arr['type'] == 'textarea') { ?>
            <span>Текст: </span>
            <label>
                <textarea name="<?= $arr['name'] ?>" placeholder="Введите текст"><?= $arr['default'] ?></textarea>
            </label><br>
        <?php }
    } ?>
    <input type="button" value="Результат" onClick="sendRequest();"/>
    <label type="text">pages/<?= $data['dir'] ?></label><br>
</form>
<div id=\"result\"> ...</div>

