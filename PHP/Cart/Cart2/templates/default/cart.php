<main class="main-internal">

    <div class="container">
        <?= $this->breadcrumbs ?>
        <h1 class="page-title h1"> Корзина покупок</h1>
    </div>
    <?php if (empty($this->cart['goods'])): ?>
        <section class="catalog-internal">
            <div class="container">
                <h2>Ваша корзина пуста</h2>
            </div>
        </section>
    <?php else: ?>
        <section class="catalog-internal">
            <div class="container">
                <div class="catalog-internal-wrap">
                    <section class="catalog-section catalog-section__line">

                        <div class="basket-top">

                            <div class="total-basket-price">
                                Итого:
                                <?php if (!empty($this->cart['total_old_sum'])): ?>
                                    <span class="total-basket-price_old-price"
                                          data-totalOldSum><?= $this->cart['total_old_sum'] ?> руб.</span>
                                <?php endif; ?>
                                <span class="total-basket-price_new-price" data-totalSum><?= $this->cart['total_sum'] ?> руб.</span>
                            </div>
                            <div class="basket-btns">
                                <a class="basket-btn">Перейти к оформлению</a>
                                <a href="<?= $this->getUrl(['cart' => 'remove']) ?>" class="basket-btn">Очистить
                                    корзину</a>
                            </div>
                        </div>
                        <div class="catalog-section__wrapper">
                            <div class="catalog-section-items">
                                <div class="catalog-section-items__wrapper">
                                    <?php foreach ($this->cart['goods'] as $item): ?>
                                        <div class="card-item card-item__internal card-item__line"
                                             data-productContainer>
                                            <div class="card-item__tabs_image">
                                                <img src="<?= $this->img($item['img']) ?>" alt="image">
                                            </div>
                                            <div class="card-item__tabs_description">
                                                <div class="card-item__tabs_name">
                                                    <span><?= $item['name'] ?></span>
                                                </div>
                                                <div class="card-item__tabs_price">
                                                    Цена:
                                                    <?php if (!empty($item['old_price'])): ?>
                                                        <span class="card-item_old-price"><?= $item['old_price'] ?> руб.</span>
                                                    <?php endif; ?>
                                                    <span class="card-item_new-price"><?= $item['price'] ?> руб.</span>
                                                </div>
                                            </div>
                                            <a href="<?= $this->getUrl(['cart' => 'remove', 'id' => $item['id']]) ?>"
                                               class="card-item__btn">
                                                Удалить
                                            </a>
                                            <span class="card-main-info-size__body">
                                                <span class="card-main-info-size__control card-main-info-size__control_minus js-counterDecrement"
                                                      data-quantityMinus></span>
                                                <span class="card-main-info-size__count js-counterShow"
                                                      data-quantity><?= $item['qty'] ?></span>
                                                <span class="card-main-info-size__control card-main-info-size__control_plus js-counterIncrement"
                                                      data-quantityPlus></span>
                                            </span>
                                            <?php $i = 20 ?>
                                            <?php foreach ($this->marketing['all'] as $type => $name): ?>
                                                <?php if (!empty($item[$type])): ?>
                                                    <div class="icon-offer" <?= $i > 30 ? ' style="top: ' . $i . 'px"' : '' ?>>
                                                        <?= $name['icon'] ?>
                                                    </div>
                                                    <?php $i = $i + 30; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <div style="display: none" data-addToCart="<?= $item['id'] ?>"
                                                 data-toCartAdded>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>

        <section class="order-registration">
            <div class="container">
                <form class="order-registration-form" method="post" action="<?= $this->getUrl('orders') ?>">
                    <?php if (!empty($this->payments)): ?>
                        <div class="order-registration-payment">
                            <div class="order-registration-titel">Оплата</div>
                            <div class="order-registration-radio">
                                <?php foreach ($this->payments as $key => $item): ?>
                                    <label class="order-registration-radio-item">
                                        <input class="order-registration-rad-inp" type="radio"
                                               value="<?= $item['id'] ?>"
                                               name="payments_id"<?= !$key ? ' checked' : '' ?>>
                                        <div class="order-registration-radio-item-descr">
                                            <?= $item['name'] ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($this->delivery)): ?>
                        <div class="order-registration-delivery">
                            <div class="order-registration-titel">Доставка</div>
                            <div class="order-registration-radio">
                                <?php foreach ($this->delivery as $key => $item): ?>
                                    <label class="order-registration-radio-item">
                                        <input class="order-registration-rad-inp" type="radio"
                                               value="<?= $item['id'] ?>"
                                               name="delivery_id"<?= !$key ? ' checked' : '' ?>>
                                        <div class="order-registration-radio-item-descr">
                                            <?= $item['name'] ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="amount-pay-wrapp">
                        Сумма к оплате:
                        <span class="amount-pay" data-totalSum><?= $this->cart['total_sum'] ?> руб.</span>
                    </div>
                    <input class="execute-order_btn" type="button" value="Оформить заказ" data-popup="order-popup">
                    <div class="order-popup<?= !empty($_SESSION['res']['answer']['error']) ? ' open' : '' ?>">
                        <div class="order-popup__inner">
                            <h2>Оформление заказа</h2>
                            <label>
                                <input type="text" value="<?= $this->setFormValues('name', 'userData') ?>"
                                       name="name" placeholder="Ваше имя">
                            </label>
                            <label>
                                <input type="tel" value="<?= $this->setFormValues('phone', 'userData') ?>"
                                       name="phone" placeholder="Ваш телефон">
                            </label>
                            <label>
                                <input type="email" value="<?= $this->setFormValues('email', 'userData') ?>"
                                       name="email" placeholder="Ваш e-mail">
                            </label>
                            <label>
                                <textarea name="address" placeholder="Ваш адрес"
                                          rows="5"><?= $this->setFormValues('address', 'userData') ?></textarea>
                            </label>
                            <div class="amount-pay-wrapp">
                                Сумма к оплате:
                                <span class="amount-pay" data-totalSum><?= $this->cart['total_sum'] ?> руб.</span>
                            </div>
                            <div class="send-order">
                                <input class="execute-order_btn" type="submit" value="Оформить заказ">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    <?php endif; ?>
</main>