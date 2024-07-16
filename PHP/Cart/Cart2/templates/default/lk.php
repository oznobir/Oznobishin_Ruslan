<?php $cOrder = !empty($this->data['current_order']) ? $this->data['current_order'] : null ?>
<div class="overlay"></div>
<main class="main">
    <div class="container">
        <div class="wrapper wrapper_center">
            <h1 class="page-title">Мои заказы</h1>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <div class="wrapper_internal wrapper_lk">
                <aside class="internal-aside internal-aside_lk">
                    <div class="internal-aside-items">
                        <a href="#" data-popup="login-popup" class="internal-aside-item internal-aside-item_active">
                            Мой аккаунт </a>
                        <a href="<?= $this->getUrl('lk')?>" class="internal-aside-item ">
                            Мои заказы </a>
                        <a href="<?= $this->getUrl(['login' => 'logout'])?>" class="internal-aside-item ">
                            Выход </a>
                    </div>
                </aside>
                <?php if (empty($this->data['all_orders'])): ?>
                    <p>Вы еще не совершали заказов</p>
                <?php else: ?>
                    <div class="cabinet cabinet_switches lk-body">
                        <div class="switchTitles second">
                            <?php if (!$cOrder): ?>
                                <div class="active" data-innerswitch="order_switch1">Текущий заказ</div>
                            <?php endif; ?>
                            <div data-innerswitch="order_switch2" class="">
                                Все заказы(<?= count($this->data['all_orders'] ?? []) ?>)
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                let innerSwitch = document.querySelectorAll('[data-innerswitch]')
                                let innerSwitched = document.querySelectorAll('[data-innerswitched]')
                                if (innerSwitched.length) {
                                    innerSwitch.forEach((item, index) => {
                                        item.addEventListener('click', () => {
                                            if (typeof innerSwitched[index] !== 'undefined' && !item.classList.contains('active')) {
                                                innerSwitch.forEach(el => el.classList.remove('active'))
                                                innerSwitched.forEach(el => el.classList.remove('active'))
                                                item.classList.add('active')
                                                innerSwitched[index].classList.add('active')
                                            }
                                        })
                                    })
                                }
                            })
                        </script>
                        <?php if ($cOrder): ?>
                            <div class="active" data-innerswitched="order_switch1">
                                <div class="order_title">
                                    <h3>
                                        Заказ №<?= $cOrder['id'] ?>
                                    </h3>
                                </div>
                                <ul class="info_panel">
                                    <li>
                                        <span class="name">
                                            Всего:
                                        </span>
                                        <span class="info">
                                            <?= $cOrder['total_qty'] ?>
                                        </span>
                                    </li>
                                    <li>
                                        <span class="name">
                                            Стоимость:
                                        </span>
                                        <span class="info">
                                            <?= $cOrder['total_sum'] ?> руб.
                                        </span>
                                    </li>
                                    <li>
                                        <span class="name">
                                            Дата формирования:
                                        </span>
                                        <span class="info">
                                            <?= $cOrder['date'] ?>
                                        </span>
                                    </li>
                                    <li>
                                        <span class="name">
                                            Статус:
                                        </span>
                                        <span class="info">
                                            <?= $cOrder['join']['orders_statuses'][$cOrder['orders_statuses_id']]['name'] ?>
                                        </span>
                                    </li
                                </ul>
                                <div class="table_wrap">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th>Код</th>
                                            <th>Наименование</th>
                                            <th>Количество</th>
                                            <th>Стоимость</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($cOrder['join']['orders_goods'] as $goods): ?>
                                            <tr>
                                                <td><?= $goods['id'] ?></td>
                                                <td>
                                                    <div class="flex_wrap">
                                                        <b><?= $cOrder['join']['goods'][$goods['goods_id']]['name'] ?></b>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?= $goods['qty'] ?> шт
                                                </td>
                                                <td>
                                                    <?= $goods['price'] ?> руб.
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table_total">
                                    <div class="delivery">
                                        <div>
                                            <b>Способ оплаты:</b>
                                            <span>
                                                <?= $cOrder['join']['payments'][$cOrder['payments_id']]['name'] ?>
                                            </span>
                                        </div>
                                        <div>
                                            <b>Способ получения:</b>
                                            <span>
                                                <?= $cOrder['join']['delivery'][$cOrder['delivery_id']]['name'] ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="totally">
                                        <b>Итоговая стоимость:</b>
                                        <?= $cOrder['total_sum'] ?> руб.
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div data-innerswitched="order_switch2" <?= !$cOrder ? ' class="active"' : '' ?>>
                            <div class="table_wrap">
                                <table class="second_table">
                                    <thead>
                                    <tr>
                                        <th>Номер</th>
                                        <th>Дата формирования</th>
                                        <th>Стоимость</th>
                                        <th>Статус</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($this->data['all_orders'] as $order): ?>
                                        <tr>
                                            <td>№<?= $order['id'] ?></td>
                                            <td>
                                                <?= $order['date'] ?>
                                            </td>
                                            <td>
                                                <?= $order['total_sum'] ?> руб.
                                            </td>
                                            <td>
                                                <span class="status "><?= $order['join']['orders_statuses'][$order['orders_statuses_id']]['name'] ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= $this->getUrl(['lk' => 'order', 'id' => $order['id']]) ?>">Подробнее</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>