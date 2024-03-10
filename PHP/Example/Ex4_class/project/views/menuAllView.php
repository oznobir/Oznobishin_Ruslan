<?php /** @var array $data */ ?>
    <div class="accor-group">
        <div class="as-title">
            Содержание
        </div>
        <?php foreach ($data as $key => $value) {
            if (isset($value['children'])) { ?>
                <input type="checkbox" name="accor" id="accor_<?= $key ?>"/>
                <label for="accor_<?= $key ?>">
                    <?= $value['description'] ?>
                </label>
            <?php } else { ?>
                <div>
                    <a href="?controller=page&p=<?= $key ?>" title="Пример <?= $key ?>">
                        <?= $value['description'] ?>
                    </a>
                </div>
            <?php }
            if (isset($value['children'])) { ?>
                <div class="accor-container">
                    <?php tplMainMenu($value['children']) ?>
                </div>
            <?php }
        } ?>
    </div>
<?php function tplMainMenu($data1)
{
    foreach ($data1 as $key1 => $value1) {
        if (isset($value1['children'])) { ?>
            <input type="checkbox" name="accor" id="accor_<?= $key1 ?>"/>
            <label for="accor_<?= $key1 ?>">
                <?= $value1['description'] ?>
            </label>
        <?php } else { ?>
            <div>
                <a href="?controller=page&p=<?= $key1 ?>" title="Пример <?= $key1 ?>">
                    <?= $value1['description'] ?>
                </a>
            </div>
        <?php }
        if (isset($value1['children'])) { ?>
            <div class="accor-container">
                <?php tplMainMenu($value1['children']) ?>
            </div>
        <?php }
    }
} ?>