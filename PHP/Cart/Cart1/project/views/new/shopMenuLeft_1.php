<?php /** @var array $menu */ ?>
    <nav>
        <div class="left-column">
            <div class="left-menu">
                <div class="menu-caption">Меню:</div>
                <ul><?php tpl($menu); ?></ul>
            </div>
        </div>
    </nav>
<?php function tpl($menu): void
{

    foreach ($menu as $item) : ?>
        <li><a href="#" title=" <?= $item['title'] ?>"><?= $item['title'] ?></a></li>
        <?php if (isset($item['children'])) : ?>
            <ul><?php tpl($item['children']) ?></ul>
        <?php endif;
    endforeach;
} ?>