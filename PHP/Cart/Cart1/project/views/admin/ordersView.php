<?php
/**
 * @var array|null $orders
 */
?>
<?php include 'headerLayout.php' ?>
<main>
    <nav>
        <div class="left-column">
            <?php include 'menu.php' ?>
        </div>
    </nav>
    <div class="center-column">
        <h2>Заказы</h2>
        <div>
            <?php if (!$orders) : ?>
                <div>Заказов нет</div>
            <?php else: ?>
                <table>
                    <thead>
                    <tr>
                        <th>№ п/п</th>
                        <th>Состав</th>
                        <th>№ заказа</th>
                        <th>Покупатель</th>
                        <th>Создание/изменение</th>
                        <th>Дата оплаты</th>
                        <th>Статус</th>
                        <th>Комментарий</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1;
                    foreach ($orders as $order) : ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <a href="#" onclick="showPurchase(<?= $order['id'] ?>); return false;"
                                   alt="Показать/срыть товары">
                                    Товары
                                </a>
                            </td>
                            <td><?= $order['id'] ?></td>
                            <td>
                                id: <?= $order['user_id'] ?><br>
                                Логин: <?= $order['email'] ?><br>
                                Имя: <?= $order['name'] ?><br>
                                Телефон: <?= $order['phone'] ?><br>
                                Адрес: <?= $order['address'] ?><br>
                            </td>
                            <td>
                                <?= $order['data_created'] ?><br>
                                <?= $order['data_modification'] ?>
                            </td>
                            <td>
                                <?php if ($order['data_payment']) : ?>
                                    <span><?= $order['data_payment'] ?></span>
                                <?php else: ?>
                                <label><input id="date_<?= $order['id'] ?>" type="date" value="<?= $order['id'] ?>"></label><br>
                                <a href="#" onclick="updateDatePayment(<?= $order['id'] ?>); return false;"
                                   alt="Введите дату оплаты">
                                    Сохранить
                                </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($order['status']) : ?>
                                    <span>Выдан</span>
                                <?php else: ?>
                                    <label>Выдан:
                                    <input type="checkbox" id="status_<?= $order['id'] ?>"
                                           onchange="updateOrderStatus(<?= $order['id'] ?>);"></label>
                                <?php endif; ?>
                            </td>
                            <td><?= $order['comment'] ?></td>
                        </tr>
                        <tr class="hidden" id="purchase_<?= $order['id'] ?>">
                            <td colspan="8">
                                <?php if ($order['purchase']) : ?>
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
                                        <?php $j = 1; $totalPrice = 0;
                                        foreach ($order['purchase'] as $productOrder) :
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
    </div>
</main>
<?php include 'footerLayout.php' ?>

