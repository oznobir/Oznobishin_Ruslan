<?php
/**
 * @var array $menu
 * @var string $page
 */
foreach ($menu as $key => $item) {?>
    <div><a<?=$page==$key?" class='active'":''?> title="<?=$item['description']?>" href="/page/<?=$key?>/">Пример <?=$key?></a></div>
<?php } ?>