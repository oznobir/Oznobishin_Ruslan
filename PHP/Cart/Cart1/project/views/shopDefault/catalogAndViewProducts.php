<?php
/**
 * @var array $viewProducts
 * @var array $menu
 */ ?>

<div>
    <div class="menu-caption">Каталог:</div>
    <ul><?php tpl($menu); ?></ul>
    <?php function tpl($menu): void
    {
        foreach ($menu as $item) : ?>
            <li><a href="/category/<?= $item['slug'] ?>/" title=" <?= $item['title'] ?>"><?= $item['title'] ?></a>
                <?php if (isset($item['children'])) : ?>
                    <ul><?php tpl($item['children']) ?></ul>
                <?php endif; ?>
            </li>
        <?php endforeach;
    } ?>
    <?php if (isset($viewProducts)) : ?>
        <div class="menu-caption">Просмотренные товары:</div>
        <ul>
            <?php foreach ($viewProducts as $viewProduct) : ?>
                <li>
                    <a href="/product/<?= $viewProduct['slug'] ?>/"
                       title=" <?= $viewProduct['title'] ?>"><?= $viewProduct['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>