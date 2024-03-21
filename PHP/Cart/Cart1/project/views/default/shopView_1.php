<?php /** @var array $menu */ ?>
<main>
    <nav>
        <div class="left-column">
            <div class="left-menu">
                <div class="menu-caption">Меню:</div>
                <ul><?php tpl($menu);?></ul>
            </div>
        </div>
    </nav>
    <div class="center-column">
        center-column
    </div>
</main>
<?php function tpl($menu): void
{
    foreach ($menu as $item) { ?>
        <li><a href="#" title=" <?= $item['title'] ?>"><?= $item['title'] ?></a></li>
        <?php if (isset($item['children'])) { ?>
            <ul><?php tpl($item['children']) ?></ul>
        <?php }
    }
} ?>