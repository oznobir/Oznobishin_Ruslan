<div class="vg-wrap vg-firm-background-color1 vg-center vg-block vg-menu">
    <?php if ($this->menu): ?>
        <?php foreach ($this->menu as $table => $items) : ?>
            <a href="<?= $this->path ?>show/<?= $table ?>"
               class="vg-wrap vg-element vg-full vg-center<?= ($table == $this->table) ? ' active' : '' ?>">
                <div class="vg-element vg-half  vg-center">
                    <div>
                        <img src="<?= PATH . ADMIN_TEMPLATE ?>img/<?= $items['img'] ?? 'pages.png' ?>"
                             alt="pages">
                    </div>
                </div>
                <div class="vg-element vg-half vg-center vg_hidden">
                    <span class="vg-text vg-firm-color5"><?= $items['name'] ?? $table ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>