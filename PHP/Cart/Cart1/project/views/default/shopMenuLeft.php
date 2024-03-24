<?php
/**
 * @var array $menu
 * @var int $cartCountItems
 */ ?>
<nav>
    <div class="left-column">
        <div class="left-menu">
            <div class="menu-caption">Меню:</div>
            <br>
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
        <br>
        <div class="cart">
            <div class="menu-caption">Корзина:</div>
            <br>
            <a href="/cart/" title="Перейти в корзину">В корзине: </a>
            <span id="cartCountItems">
                <?= $cartCountItems > 0 ? $cartCountItems : 'Пусто' ?>
            </span>
        </div>
    </div>
</nav>