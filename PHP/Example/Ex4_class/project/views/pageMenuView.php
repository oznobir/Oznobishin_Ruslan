<?php /**
 * @var array $menu
 * @var string $slug
 * @var string $description
 * @var string $title
 * @var string $content_head
 * @var string $content
 * @var string $content_foot
 * @var string $form
 */?>
<header>
    <h2><?= $title ?></h2>
    <p><?= $description ?></p>
</header>
<section class="box-main">
    <nav class='box-menu'>
        <?php foreach ($menu as $key => $item) {?>
            <div><a<?=$slug==$key?" class='active'":''?> title="<?=$item['description']?>" href="/page/<?=$key?>/">Пример <?=$key?></a></div>
        <?php } ?>
    </nav>
    <div class="box-texts">
        <div class="box-text1">
            <?= $form ?>
            <div id="result"> ... </div>
        </div>
        <div class="box-text2">
            <?= $content ?>
        </div>
    </div>
</section>