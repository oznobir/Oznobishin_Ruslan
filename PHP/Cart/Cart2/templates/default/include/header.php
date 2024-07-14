<div class="container">
    <div class="header__wrapper">
        <div class="header__logo">
            <a href="<?= $this->getUrl(); ?>">
                <img src="<?= $this->img($this->set['img_logo']); ?>" alt="<?= $this->set['name'] ?>">
            </a>
        </div>
        <div class="header__topbar">
            <div class="header__contacts">
                <div>
                    <a href="mailto:<?= $this->set['email'] ?>">
                        <?= $this->set['email'] ?>
                    </a>
                </div>
                <div>
                    <a href="tel:<?= preg_replace('/[^+\d]/', '', $this->set['phone']); ?>">
                        <?= $this->set['phone'] ?>
                    </a>
                </div>
                <div><a class="js-callback">Связаться с нами</a></div>
            </div>
            <nav class="header__nav">
                <ul class="header__nav-list">
                    <?php if (!empty($this->menu['catalog'])) : ?>
                        <li class="header__nav-parent">
                            <a href="<?= $this->getUrl('catalog'); ?>"><span>Каталог</span></a>
                            <ul class="header__nav-sublist">
                                <?php foreach ($this->menu['catalog'] as $item) : ?>
                                    <li>
                                        <a href="<?= $this->getUrl(['catalog' => $item['alias']]); ?>">
                                            <span><?= $item['name'] ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($this->menu['information'])) : ?>
                        <li class="">
                            <?php foreach ($this->menu['information'] as $item) : ?>
                                <a href="<?= $this->getUrl(['information' => $item['alias']]); ?>">
                                    <span><?= $item['name'] ?></span>
                                </a>
                                <ul class="header__nav-sublist">

                                </ul>
                            <?php endforeach; ?>
                        </li>
                    <?php endif; ?>

                    <li class="">
                        <a href="<?= $this->getUrl('news'); ?>">
                            <span>Новости</span>
                        </a>
                        <ul class="header__nav-sublist">

                        </ul>
                    </li>

                    <li class="">
                        <a href="<?= $this->getUrl('contacts'); ?>"><span>Контакты</span></a>
                        <ul class="header__nav-sublist">

                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="overlay">

        </div>
        <div class="header__sidebar">
            <div class="header__sidebar_btn">
                <a href="<?= $this->getUrl('cart'); ?>" class="cart-btn-wrap">
                    <svg class="inline-svg-icon svg-basket">
                        <use href="<?= PATH . SITE_TEMPLATE ?>assets/img/icons.svg#basket"></use>
                    </svg>
                    <span data-totalQty><?= $this->cart['total_qty'] ?? 0 ?></span>
                </a>
            </div>
            <div class="header__sidebar_btn">
                <a <?= $this->userData['id'] ? 'href="' . $this->getUrl('account') . '" ' : 'data-popup="login-popup" ' ?>
                        class="cart-btn-wrap">
                    <svg class="inline-svg-icon svg-personality">
                        <use href="<?= PATH . SITE_TEMPLATE ?>assets/img/icons.svg#personality"></use>
                    </svg>

                </a>
            </div>
            <div class="header__sidebar_btn burger-menu">
                <div class="burger-menu__link">
                    <span class="burger"></span>
                    <!--                    <span class="burger-desc">меню</span>-->
                </div>
            </div>
            <?php if (!empty($this->socials)) : ?>
                <?php foreach ($this->socials as $item) : ?>
                    <div class="header__sidebar_btn">
                        <a href="<?= $this->getUrl($item['external_url']); ?>">
                            <svg class="inline-svg-icon svg-socials">
                                <use href="<?= $this->getUrl([SITE_TEMPLATE => $item['icons_svg']]); ?>"></use>
                            </svg>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="header__menu _hidden">
            <div class="header__menu_close close_modal"></div>
            <ul class="header__menu_burger">
                <?php if (!empty($this->menu['catalog'])): ?>
                    <li>
                        <a href="<?= $this->getUrl('catalog'); ?>">
                            <span>Каталог</span>
                        </a>
                        <ul class="header__menu_sublist">
                            <?php foreach ($this->menu['catalog'] as $item) : ?>
                                <li>
                                    <a href="<?= $this->getUrl(['catalog' => $item['alias']]); ?>">
                                        <span><?= $item['name'] ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (!empty($this->menu['information'])): ?>
                    <?php foreach ($this->menu['information'] as $item) : ?>
                        <li>
                            <a href="<?= $this->getUrl(['information' => $item['alias']]); ?>">
                                <span><?= $item['name'] ?></span>
                            </a>
                            <ul class="header__menu_sublist">


                            </ul>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <li>
                    <a href="<?= $this->getUrl('news'); ?>">
                        <span>Новости</span>
                    </a>
                    <ul class="header__menu_sublist">

                    </ul>
                </li>
                <li>
                    <a href="<?= $this->getUrl('contacts'); ?>">
                        <span>Контакты</span>
                    </a>
                    <ul class="header__menu_sublist">

                    </ul>
                </li>
            </ul>
        </div>
        <div class="header__callback _hidden">
            <div class="header__callback_close close_modal"></div>
            <div class="header__callback_header">
                Связаться с нами
            </div>
            <form class="header__callback_form">
                <input type="text" class="input-text header__callback_input" placeholder="Ваше имя">
                <input type="email" class="input-text header__callback_input" placeholder="E-mail">
                <input type="text" class="input-text header__callback_input js-mask-phone" placeholder="Телефон">
                <div class="header__callback_privacy">
                    <label class="checkbox">
                        <input type="checkbox"/>
                        <div class="box__text">Соглашаюсь с правилами обработки персональных данных</div>
                    </label>
                </div>
                <button type="submit" class="form-submit header__callback_submit">Отправить</button>
            </form>
        </div>
    </div>
</div>
<?php if (!$this->userData['id']): ?>
    <?php if (!empty($_SESSION['res']['answerForm']['form_1'])) $res1 = $_SESSION['res']['answerForm'];
    if (!empty($_SESSION['res']['answerForm']['form_2'])) $res2 = $_SESSION['res']['answerForm']; ?>
    <div class="login-popup<?= !empty($res1) || !empty($res2) ? ' open' : '' ?>">
        <div class="login-popup__inner">
            <h2>
                <span<?= empty($res2) ? ' class="login-active"' : '' ?>>Регистрация</span>
                <span<?= !empty($res2) ? ' class="login-active"' : '' ?>>Вход</span>
            </h2>
            <form action="<?= $this->getUrl(['login' => 'registration']) ?>"<?= !empty($res2) ? ' style="display: none" ' : '' ?>
                  method="post">
                <?= $res1['name'] ?? '' ?>
                <label>
                    <input type="text" value="<?= !empty($res1) ? $this->setFormValues('name') : '' ?>"
                           name="name" placeholder="Ваше имя">
                </label>
                <?= $res1['phone'] ?? '' ?>
                <label>
                    <input type="tel" value="<?= !empty($res1) ? $this->setFormValues('phone') : '' ?>"
                           name="phone" placeholder="Ваш телефон">
                </label>
                <?= $res1['email'] ?? '' ?>
                <label>
                    <input type="email" value="<?= !empty($res1) ? $this->setFormValues('email') : '' ?>"
                           name="email" placeholder="Ваш e-mail">
                </label>
                <?= $res1['password'] ?? '' ?>
                <label>
                    <input type="password" name="password" placeholder="Пароль">
                </label>
                <?= $res1['confirm_password'] ?? '' ?>
                <label>
                    <input type="password" name="confirm_password" placeholder="Подтверждение пароля">
                </label>
                <div class="send-login">
                    <input class="execute-order_btn" type="submit" value="Регистрация">
                </div>
            </form>
            <form action="<?= $this->getUrl(['login' => 'auth']) ?>"<?= empty($res2) ? ' style="display: none" ' : '' ?>
                  method="post">
                <?= $res2['phone'] ?? '' ?>
                <label>
                    <input type="tel" value="<?= !empty($res2) ? $this->setFormValues('phone') : '' ?>"
                           name="phone" placeholder="Введите Ваш телефон">
                </label>
                <?= $res2['email'] ?? '' ?>
                <label>
                    <input type="email" value="<?= !empty($res2) ? $this->setFormValues('email') : '' ?>"
                           name="email" placeholder="и/или Ваш e-mail">
                </label>
                <?= $res2['password'] ?? '' ?>
                <label>
                    <input type="password" name="password" placeholder="Пароль" value="">
                </label>
                <div class="send-login">
                    <input class="execute-order_btn" type="submit" value="Вход">
                </div>
            </form>

        </div>
    </div>
<?php endif; ?>
