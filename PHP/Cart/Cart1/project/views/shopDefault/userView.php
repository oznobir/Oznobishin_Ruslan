<?php
/**
 * @var array $arrUser
 * @var array $orders
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
            <h2>Ваши данные</h2>
            <div>
                <form id="userForm" class="user-form">
                    <div class="user-basic">
                        <div class="menu-caption">Основные данные</div>
                        <br>
                        <div>
                            <div>Логин (email)</div>
                            <div><label><input type="text" readonly size="20" value="<?= $_SESSION['user']['email'] ?>"></label>
                            </div>
                        </div>
                        <div>
                            <div>Новый пароль</div>
                            <div><label><input type="password" name="pwd1" id="newPwd1" size="20" value=""></label>
                            </div>
                        </div>
                        <div>
                            <div>Повторите новый пароль</div>
                            <div><label><input type="password" name="pwd2" id="newPwd2" size="20" value=""></label>
                            </div>
                        </div>
                        <div>
                            <div>Введите текущий пароль</div>
                            <div><label><input type="password" name="curPwd" id="pwd" size="20" value=""></label></div>
                        </div>
                    </div>
                    <div class="user-added">
                        <div class="menu-caption">Дополнительные данные</div>
                        <br>
                        <div>
                            <div>Имя</div>
                            <div><label><input type="text" name="name" id="newName" size="20"
                                               value="<?= $_SESSION['user']['name'] ?>"></label></div>
                        </div>
                        <div>
                            <div>Телефон</div>
                            <div><label><input type="text" name="phone" id="newPhone" size="20"
                                               value="<?= $_SESSION['user']['phone'] ?>"></label></div>
                        </div>
                        <div>
                            <div>Адрес</div>
                            <div><label><textarea name="address" id="newAddress"
                                                  cols="19"><?= $_SESSION['user']['address'] ?></textarea></label></div>
                        </div>
                        <br>
                        <div>
                            <label><input type="button" value="Сохранить изменения" onclick="updateUserData()"></label>
                        </div>
                    </div>
                </form>
            </div>
            <h3>История заказов</h3>
            <?php if (!$orders) : ?>
                <p>Нет заказов</p>
            <?php else: ?>
                <table>
                    <thead>
                    <tr>
                        <th>№ п/п</th>
                        <th>Содержание заказа</th>
                        <th>№ заказа</th>
                        <th>Статус</th>
                        <th>Дата создания</th>
                        <th>Дата оплаты</th>
                        <th>Комментарий</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1;
                    foreach ($orders as $order) : ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><a href="#" onclick="showPurchase(<?= $order['id'] ?>); return false;">Товары</a></td>
                            <td><?= $order['id'] ?></td>
                            <td><?= $order['status']?'оплачен': 'не оплачен' ?></td>
                            <td><?= $order['data_created'] ?></td>
                            <td><?= $order['data_payment'] ?></td>
                            <td><?= $order['comment'] ?></td>
                        </tr>
                        <tr class="hidden" id="productsOrder_<?= $order['id'] ?>">
                            <td colspan="7">
                                <?php if ($order['children']) : ?>
                                    <table>
                                        <thead>
                                        <tr>
                                            <th>№ п/п</th>
                                            <th>Код товара</th>
                                            <th>Наименование</th>
                                            <th>Цена</th>
                                            <th>Количество</th>
                                            <th>Стоимость</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $j = 1;
                                        $totalPrice = 0;
                                        foreach ($order['children'] as $productOrder) :
                                            $total = round($productOrder['amount'] * $productOrder['price'], 2) ?>
                                            <tr>
                                                <td><?= $j++ ?></td>
                                                <td><?= $productOrder['product_id'] ?></td>
                                                <td><a href="/product/<?= $productOrder['slug'] ?>/"><?= $productOrder['slug'] ?></a></td>
                                                <td><?= $productOrder['amount'] ?></td>
                                                <td><?= $productOrder['price'] ?></td>
                                                <td><?= $total ?></td>
                                            </tr>
                                            <?php $totalPrice += $total; endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="5">Общая стоимость:</td>
                                            <td>
                                                <span id="totalPrice"><?= $totalPrice ?></span>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
<?php include 'footerLayout.php' ?>