<?php
/**
 * @var array $products
 */ ?>
<main>
    <?php include 'shopMenuLeft.php' ?>
    <div class="center-column">
        <?php foreach ($products as $product) { ?>
            <div class="product-card">
                <div class="product-card-image">
                    <a href="/product/<?= $product['slug'] ?>/">
                        <img src="/project/access/img/<?= $product['image'] ?>"
                             alt="<?= $product['title'] ?>" width="270">
                    </a>
                </div>
                <div class="product-card-link">
                    <a href="/product/<?= $product['slug'] ?>/"><?= $product['title'] ?></a>
                </div>
            </div>
        <?php } ?>
    </div>
</main>