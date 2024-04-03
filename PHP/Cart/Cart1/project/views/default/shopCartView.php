<?php
/**
 * @var array|null $products
 * @var string $title
 */ ?>
<?php include 'shopHeaderLayout.php' ?>
    <main>
        <nav>
            <div class="left-column">
                <?php include 'shopCatalogAndViewProducts.php' ?>
            </div>
        </nav>
        <div class="center-column">
            <h2>Корзина покупок</h2>
            <?php if (!isset($products)) : ?>
                <p>Ваша корзина пуста</p>
            <?php else : ?>
                <form action="/cart/order/" method="POST">
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
                        $totalPrice = 0;
                        foreach ($products as $product) { ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $product['id'] ?></td>
                                <td><a href="/product/<?= $product['slug'] ?>/"><?= $product['title'] ?></a>
                                </td>
                                <td>
                                    <label for="itemCart_<?= $product['id'] ?>">
                                        <input name="<?= $product['id'] ?>"
                                               id="itemCart_<?= $product['id'] ?>" type="number" min="1"
                                               max="99"
                                               size="3"
                                               value="<?= $_SESSION['cart'][$product['id']] ?>"
                                               autocomplete="off"
                                               onchange="conversionPrice(<?= $product['id'] ?>);">
                                    </label>
                                </td>
                                <td>
                                <span id="itemPrice_<?= $product['id'] ?>">
                                    <?= $product['price'] ?>
                                </span>
                                </td>
                                <td>
                                    <span id="itemRealPrice_<?= $product['id'] ?>"><?= $product['price'] * $_SESSION['cart'][$product['id']] ?></span>
                                </td>
                                <td>
                                    <a id="removeCart_<?= $product['id'] ?>"
                                       href="#" onclick="removeFromOrder(<?= $product['id'] ?>); return false;"
                                       alt="Удалить из корзины">
                                        Удалить
                                    </a>
                                    <a id="addCart_<?= $product['id'] ?>" class="hidden"
                                       href="#" onclick="addToOrder(<?= $product['id'] ?>); return false;"
                                       alt="Вернуть в корзину">
                                        Вернуть
                                    </a>
                                </td>
                            </tr>
                            <?php $totalPrice += $product['price'] * $_SESSION['cart'][$product['id']];
                        } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">Общая стоимость:</td>
                            <td colspan="2">
                                <span id="totalPrice"><?= $totalPrice ?></span>
                            </td>
                            <td></td>
                        </tr>
                        </tfoot>

                    </table>
                    <br>
                    <input type="submit" value="Оформить заказ">
                </form>
            <?php endif; ?>
        </div>
    </main>
<?php include 'shopFooterLayout.php' ?>