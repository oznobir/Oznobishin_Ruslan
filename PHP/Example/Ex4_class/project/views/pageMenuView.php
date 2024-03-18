<?php /**
 * @var array $menu
 * @var string $p
 * @var array $page
 */?>
<header>
    <h2><?= $page['title'] ?></h2>
    <p><?= $page['description'] ?></p>
</header>
<section class="box-main">
    <nav class='box-menu'>
        <?php foreach ($menu as $key => $item) {?>
            <div><a<?=$p==$key?" class='active'":''?> title="<?=$item['description']?>" href="/page/<?=$key?>/">Пример <?=$key?></a></div>
        <?php } ?>
    </nav>
    <div class="box-texts">
        <div class="box-text1">
            <?= $page['form'] ?>
            <div id="result"> ... </div>
        </div>
        <div class="box-text2">
            <?= $page['content'] ?>
        </div>
    </div>
</section>