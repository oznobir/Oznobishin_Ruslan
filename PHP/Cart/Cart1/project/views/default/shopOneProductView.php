<?php
/**
 * @var string $title
 * @var string $slug
 * @var string $image
 * @var string $price
 * @var string $id
 * @var string $status
 * @var string $description
 * @var int $idInCart
 */ ?>
<?php include 'shopHeaderLayout.php' ?>
    <main>
        <nav>
            <div class="left-column">
                <?php include 'shopCartAndLogin.php' ?>
                <?php include 'shopCatalogAndViewProducts.php' ?>
            </div>
        </nav>
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
                                <div id="removeCart_<?= $id ?>" <?= (!$idInCart) ? "class=\"hidden\"" : '' ?>>
                                    <a href="/" onclick="removeFromCart(<?= $id ?>); return false;"
                                       alt="Удалить из корзины">
                                        Удалить из корзины
                                    </a>
                                </div>
                                <div id="addCart_<?= $id ?>" <?= ($idInCart) ? "class=\"hidden\"" : '' ?>>
                                    <label for="itemCart_<?= $id ?>"></label>
                                    <input type="number" name="item_<?= $id ?>" id="itemCart_<?= $id ?>"
                                           value="1" min="1" max="99" size="3" autocomplete="off">
                                    <a href="/" onclick="addToCart(<?= $id ?>); return false;" alt="Добавить в корзину">
                                        Добавить в корзину
                                    </a>
                                </div>
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