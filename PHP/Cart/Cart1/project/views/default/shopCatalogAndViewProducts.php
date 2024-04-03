<?php
/**
 * @var array $viewProducts
 * @var array $menu
 * @var int $cartCountItems
 */ ?>
<nav>
    <div class="left-column">
            <div class="menu-caption">Каталог:</div>
            <?php foreach ($menu as $item) { ?>
                <a href="/category/<?= $item['slug'] ?>/" title=" <?= $item['title'] ?>"><?= $item['title'] ?></a><br>
                <?php if (isset($item['children'])) {
                    foreach ($item['children'] as $itemChild) { ?>
                        -- <a href="/category/<?= $itemChild['slug'] ?>/"
                              title=" <?= $itemChild['title'] ?>"><?= $itemChild['title'] ?></a><br>
                    <?php }
                }
            } ?>
            <br>
            <?php if (isset($viewProducts)) : ?>
                <div class="menu-caption">Просмотренные товары:</div>
                <?php foreach ($viewProducts as $viewProduct) { ?>
                    <a href="/product/<?= $viewProduct['slug'] ?>/"
                       title=" <?= $viewProduct['title'] ?>"><?= $viewProduct['title'] ?>
                    </a>
                    <br><br>
                <?php }
            endif; ?>
    </div>
</nav>