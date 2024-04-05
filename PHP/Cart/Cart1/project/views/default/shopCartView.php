<?php
/**
 * @var array|null $products
 * @var string $title
 */ ?>
<?php include 'shopHeaderLayout.php' ?>
    <main>
        <nav>
            <div class="left-column">
                <?php if (!empty($_SESSION['user'])) include 'shopCartAndLogin.php';
                include 'shopCatalogAndViewProducts.php'; ?>
            </div>
        </nav>
        <div class="center-column">
            <h2>Оформление заказа</h2>
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
                                        <input name="products[<?= $product['id'] ?>]"
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
                                    <span id="itemRealPrice_<?= $product['id'] ?>"
                                          value="<?= $product['price'] * $_SESSION['cart'][$product['id']] ?>">
                                        <?= $product['price'] * $_SESSION['cart'][$product['id']] ?>
                                    </span>
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
                            <td colspan="2"></td>
                            <td colspan="3">Общая стоимость:</td>
                            <td colspan="2">
                                <span id="totalPrice" data-name="total"><?= $totalPrice ?></span>
                            </td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                    <?php if (!empty($_SESSION['user'])) : ?>
                        <div id="orderAddData">
                            <div class="menu-caption">Данные для текущего заказа</div>
                            <div class="order-form">
                                <div class="order-basic">
                                    <div>
                                        <div>Имя</div>
                                        <div><label><input type="text" name="user" id="name" size="20"
                                                           value="<?= $_SESSION['user']['name']??'' ?>"></label></div>
                                    </div>
                                    <div>
                                        <div>Телефон</div>
                                        <div><label><input type="text" name="phone" id="phone" size="20"
                                                           value="<?= $_SESSION['user']['phone']??'' ?>"></label></div>
                                    </div>
                                    <div>
                                        <div>Адрес</div>
                                        <div><label><textarea name="address" id="address"
                                                              cols="19"><?= $_SESSION['user']['address']??'' ?></textarea></label></div>
                                    </div>
                                </div>
                                <div class="order-added">
                                    <div>
                                        <div>Оплата</div>
                                        <div>
                                            <label>
                                                <select name="payment" id="payment" >
                                                    <option selected value="cash">наличными</option>
                                                    <option value="card">картой</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <div>Доставка</div>
                                        <div><label>
                                                <select name="delivery" id="delivery">
                                                    <option selected value="courier">курьером</option>
                                                    <option value="pickUp">самовывоз</option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <div>Комментарий</div>
                                        <div><label><textarea name="comment" id="comment" cols="19"></textarea></label></div>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="Оформить заказ">
                        </div>
                    <?php endif; ?>
                </form>
                <?php if (empty($_SESSION['user'])) : ?>
                    <div class="menu-caption">Для оформления заказа нужна регистрация/авторизация</div>
                    <div id="userBox" class="hidden">
                        <!-- Уже не надо, редирект в js   ---->
                        <div>Редактирование данных в личном кабинете: <a href="/user/" id="userLink"></a></div>
                        <div>Для оформления заказа нажмите</div>
                        <a href="/cart/"><input type="button" value="Продолжить"></a><br>
                        <!-- -->
                    </div>
                    <div id="authBox">
                        <div class="menu-caption">
                            <a href="#" onclick="showHiddenLoginBox(); return false;">Авторизация</a>
                        </div>
                        <form id="loginBoxHidden" name="loginBoxHidden">
                            <div class="user-form">
                                <div class="user-basic">
                                    <div>
                                        <label for="loginEmail">Логин (email):</label>
                                        <input type="text" id="loginEmail" name="loginEmail" value="">
                                    </div>
                                    <div>
                                        <label for="loginPwd">Введите пароль:</label>
                                        <input type="password" id="loginPwd" name="loginPwd" value="">
                                    </div>
                                    <input type="button" onclick="login('/cart/');" value="Войти">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="registerBox">
                        <div class="menu-caption">
                            <a href="#" onclick="showHiddenRegisterBox(); return false;">Регистрация</a>
                        </div>
                        <form id="registerBoxHidden" name="registerBoxHidden" class="hidden">
                            <div class="user-form">
                                <div class="user-basic">
                                    <div class="menu-caption">Основные данные</div>
                                    <div>
                                        <div>Логин (email)</div>
                                        <div><label><input type="text" id="email" name="email" size="20"
                                                           value=""></label></div>
                                    </div>
                                    <div>
                                        <div>Введите пароль</div>
                                        <div>
                                            <label><input type="password" id="pwd1" name="pwd1" size="20"
                                                          value=""></label>
                                        </div>
                                    </div>
                                    <div>
                                        <div>Повторите пароль</div>
                                        <div>
                                            <label><input type="password" name="pwd2" id="pwd2" size="20"
                                                          value=""></label>
                                        </div>
                                    </div>
                                    <div>
                                        <input type="button" onclick="registerNewUser('/cart/');"
                                               value="Регистрация"><br><br>
                                    </div>
                                    <br>
                                </div>
                                <div class="user-added">
                                    <div class="menu-caption">Дополнительные данные</div>
                                    <div>
                                        <div>Имя</div>
                                        <div><label><input type="text" name="name" id="newName" size="20"
                                                           value=""></label></div>
                                    </div>
                                    <div>
                                        <div>Телефон</div>
                                        <div><label><input type="text" name="phone" id="newPhone" size="20"
                                                           value=""></label></div>
                                    </div>
                                    <div>
                                        <div>Адрес</div>
                                        <div><label><textarea name="address" id="newAddress"
                                                              cols="19"></textarea></label></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php endif;
            endif; ?>
        </div>
    </main>
<?php include 'shopFooterLayout.php' ?>