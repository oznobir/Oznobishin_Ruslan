<?php
/** @var array $data */
$mainClass = $parameters['mainClass'] ?? 'offers__tabs_card swiper-slide';
$prefixAddClass = $parameters['prefixAddClass'] ?? 'offers';
?>
<div class="<?= $mainClass ?>">
    <div class="<?= $prefixAddClass ?>__tabs_image">
        <img src="<?= $this->img($data['img']) ?>" alt="<?= $data['name'] ?>">
    </div>
    <div class="<?= $prefixAddClass ?>__tabs_description">
        <div class="<?= $prefixAddClass ?>__tabs_name">
            <span><?= $data['name'] ?></span><br>
            <?= $data['short_content'] ?>

            <?php if (!empty($data['filters'])): ?>
                <div class="card-main-info__table">
                    <?php foreach ($data['filters'] as $item): ?>
                        <div class="card-main-info__table-row">
                            <div class="card-main-info__table-item">
                                <?= $item['filters_name'] ?>
                            </div>
                            <div class="card-main-info__table-item">
                                <?= implode(', ', array_column($item['values'], 'filters_name')); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="<?= $prefixAddClass ?>__tabs_price">
            Цена: <?= !empty($data['old_price']) ? '<span class="' . $prefixAddClass . '_old-price">' . $data['old_price'] . ' руб.</span>' : '' ?>
            <span class="<?= $prefixAddClass ?>_new-price"><?= $data['price'] ?> руб.</span>
        </div>
    </div>
    <button class="offers__btn" data-addToCart="<?= $data['id'] ?>">купить сейчас</button>
    <?php if (!empty($parameters['icon'])): ?>
        <div class="icon-offer">
            <?= $parameters['icon'] ?>
        </div>
    <?php endif; ?>
</div>
