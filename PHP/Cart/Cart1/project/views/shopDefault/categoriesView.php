<?php
/**
 * @var array $categories
 * @var string $title
 */ ?>
<?php include 'headerLayout.php' ?>
    <main>
        <nav>
            <div class="left-column">
                <?php include 'cartAndLogin.php' ?>
                <?php include 'catalogAndViewProducts.php' ?>
            </div>
        </nav>
        <div class="center-column">
            <h2><?= $title ?></h2>
            <div class="">
                <?php foreach ($categories as $category) { ?>
                    <div class="">
                        <div class="">
                            <a href="/category/<?= $category['slug'] ?>/"><?= $category['title'] ?></a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
<?php include 'footerLayout.php' ?>