<?php
/**
 * @var array $products
 * @var string $title
 */ ?>
<?php include 'shopHeaderLayout.php' ?>
    <main>
        <?php include 'shopMenuLeft.php' ?>
        <div class="center-column">
            <h2><?= $title ?></h2>
            <div class="products">
                <?php foreach ($products as $product) { ?>
                    <div class="product-card">
                        <div class="product-card-image">
                            <a href="/product/<?= $product['slug'] ?>/">
                                <img src="/project/access/img/<?= $product['image'] ?>"
                                     alt="<?= $product['title'] ?>" width="200">
                            </a>
                        </div>
                        <div class="product-card-link">
                            <a href="/product/<?= $product['slug'] ?>/"><?= $product['title'] ?></a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
<?php include 'shopFooterLayout.php' ?>