<?php /** @var string $row */ ?>
<div class="vg-wrap vg-element vg-full vg-box-shadow img_container img_wrapper">
    <div class="vg-wrap vg-element vg-half">
        <div class="vg-wrap vg-element vg-full">
            <div class="vg-element vg-full vg-left">
                <span class="vg-header"><?= $this->translate[$row][0] ?: $row ?></span>
            </div>
            <div class="vg-element vg-full vg-left">
                <span class="vg-text vg-firm-color5"></span>
                <span class="vg_subheader"><?= $this->translate[$row][1] ?: '' ?></span>
            </div>
        </div>
        <div class="vg-wrap vg-element vg-full">
            <label for="<?= $row ?>" class="vg-wrap vg-full file_upload vg-left">
                <span class="vg-element vg-full vg-input vg-text vg-left vg-button"
                      style="float: left; margin-right: 10px">Выбрать</span>
                <input id="<?= $row ?>" type="file" name="<?= $row ?>" class="single_img"
                       accept="image/*,image/jpeg,image/png,image/gif">
            </label>
        </div>
        <div class="vg-wrap vg-element vg-full">
            <div class="vg-element vg-left img_show main_img_show">
                <img src="<?= PATH . UPLOAD_DIR ?><?= $this->data[$row] ?: 'pages.png' ?>">
            </div>
        </div>
    </div>
</div>
