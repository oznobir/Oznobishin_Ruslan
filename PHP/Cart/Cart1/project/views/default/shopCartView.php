<?php
/**
 * @var array|null $products
 * @var string $title
 */ ?>
<?php include 'shopHeaderLayout.php' ?>
    <main>
        <?php include 'shopMenuLeft.php' ?>
        <div class="center-column">
            <h2>Корзина покупок</h2>
            <?php if (!isset($products)) : ?>
                <p>Ваша корзина пуста</p>
            <?php else : ?>
                <table>
                    <thead>
                    <tr>
                        <th>№ п/п</th>
                        <th>Код товара</th>
                        <th>Наименование товара</th>
                        <th>Количество</th>
                        <th>Цена</th>
                        <th>Стоимость</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1;
                    foreach ($products as $product) { ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $product['id'] ?></td>
                            <td><a href="/product/<?= $product['slug'] ?>/"><?= $product['title'] ?></a></td>
                            <td>
                                <label for="itemCount_<?= $product['id'] ?>">
                                    <input name="itemCount_<?= $product['id'] ?>"
                                           id="itemCount_<?= $product['id'] ?>" type="text" value="1"
                                           onchange="conversionPrice(<?= $product['id'] ?>);">
                                </label>
                            </td>
                            <td>
                                <span id="itemPrice_<?= $product['id'] ?>" value="<?= $product['price'] ?>">
                                    <?= $product['price'] ?>
                                </span>
                            </td>
                            <td><span id="itemRealPrice_<?= $product['id'] ?>"><?= $product['price'] ?></span></td>
                            <td>
                                <a id="removeCart_<?= $product['id'] ?>"
                                   href="#" onclick="removeFromCart(<?= $product['id'] ?>); return false;"
                                   alt="Удалить из корзины">
                                    Удалить
                                </a>
                                <a id="addCart_<?= $product['id'] ?>" class="hidden"
                                   href="#" onclick="addToCart(<?= $product['id'] ?>); return false;"
                                   alt="Вернуть в корзину">
                                    Вернуть
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
<?php include 'shopFooterLayout.php' ?>