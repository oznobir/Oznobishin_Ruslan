<?php
/**
 * @var string $title
 * @var string $slug
 * @var string $image
 * @var string $price
 * @var string $id
 * @var string $status
 * @var string $description
 */ ?>
    <main>
        <?php include 'shopMenuLeft.php' ?>
        <div class="center-column">
            <div class="product-card">
                <h3><?= $title ?></h3>
                <div class="product-code">
                    <p>Код товара: <?= $id ?> </p>
                </div>
                <div class="product-basic">
                    <div class="product-image">
                        <img src="/project/access/img/<?= $image ?>"
                             alt="<?= $title ?>" width="350">
                    </div>
                    <div class="product-other">
                        <div class="product-description">
                            <?= $description ?>
                        </div>
                        <div class="product-price">
                            <p><?= $status == 1 ? 'В наличии' : 'Нет на складе' ?></p>
                            <p>Цена: <?= $price ?></p>
                            <p><?= $status == 1 ? "<a href=\"#\" alt=\"Добавить в корзину\">Добавить в корзину</a>" : "" ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>