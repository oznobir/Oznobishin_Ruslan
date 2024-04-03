<?php
/**
 * @var array $products
 * @var string $title
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
            <h2><?= $title ?></h2>
            <div class="products-list">
                <?php foreach ($products as $product) { ?>
                    <div class="products-cell">
                        <div class="products-cell-image">
                            <a href="/product/<?= $product['slug'] ?>/">
                                <img src="/project/access/img/<?= $product['image'] ?>"
                                     alt="<?= $product['title'] ?>" width="200">
                            </a>
                        </div>
                        <div class="products-cell-link">
                            <a href="/product/<?= $product['slug'] ?>/"><?= $product['title'] ?></a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
<?php include 'shopFooterLayout.php' ?>