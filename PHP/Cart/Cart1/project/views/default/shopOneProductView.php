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
<?php include 'shopHeaderLayout.php' ?>
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
                            <?php if ($status == 1) : ?>
                                <p>В наличии</p>
                                <p>Цена: <?= $price ?></p>
                                <p><a href="#" alt="Добавить в корзину">Добавить в корзину</a></p>
                            <?php else : ?>
                                <p>Нет в наличии</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php include 'shopFooterLayout.php' ?>