<?php
/**
 * @var array $menu
 *
 */ ?>
<nav>
    <div class="left-column">
        <div class="left-menu">
            <div class="menu-caption">Меню:</div>
            <?php foreach ($menu as $item) { ?>
                <a href="/category/<?= $item['slug'] ?>/" title=" <?= $item['title'] ?>"><?= $item['title'] ?></a><br>
                <?php if (isset($item['children'])) {
                    foreach ($item['children'] as $itemChild) { ?>
                        -- <a href="/category/<?= $itemChild['slug'] ?>/"
                              title=" <?= $itemChild['title'] ?>"><?= $itemChild['title'] ?></a><br>
                    <?php }
                }
            } ?>
        </div>
    </div>
</nav>