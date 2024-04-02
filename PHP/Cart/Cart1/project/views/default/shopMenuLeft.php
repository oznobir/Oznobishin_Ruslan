<?php
/**
 * @var array $viewProducts
 * @var array $menu
 * @var int $cartCountItems
 */ ?>
<nav>
    <div class="left-column">
        <div class="left-menu">
            <?php if (!empty($_SESSION['user'])) : ?>
                <div id="userBox">
                    <a href="/user/"><?= $_SESSION['user']['displayName'] ?></a><i> - </i>
                    <a href="/user/logout/">Выйти</a><br>
                </div>
            <?php else : ?>
                <div id="userBox" class="hidden">
                    <a href="/user/" id="userLink"></a><i> - </i>
                    <a href="/user/logout/">Выйти</a><br>
                </div>
                <div id="authBox">
                    <div class="menu-caption">
                        <a href="#" onclick="showHiddenLoginBox(); return false;">Авторизация</a>
                    </div>
                    <form id="loginBox">
                        <label for="loginEmail">Логин (email):</label><br>
                        <input type="text" id="loginEmail" name="loginEmail" value=""><br>
                        <label for="loginPwd">Введите пароль:</label><br>
                        <input type="password" id="loginPwd" name="loginPwd" value=""><br><br>
                        <input type="button" onclick="login();" value="Войти"><br><br>
                    </form>
                </div>
                <div id="registerBox">
                    <div class="menu-caption">
                        <a href="#" onclick="showHiddenRegisterBox(); return false;">Регистрация</a>
                    </div>
                    <form id="registerBoxHidden" name="registerBoxHidden">
                        <label for="email">Логин (email):</label><br>
                        <input type="text" id="email" name="email" value=""><br>
                        <label for="pwd1">Введите пароль:</label><br>
                        <input type="password" id="pwd1" name="pwd1" value=""><br>
                        <label for="pwd2">Повторите пароль:</label><br>
                        <input type="password" id="pwd2" name="pwd2" value=""><br><br>
                        <input type="button" onclick="registerNewUser();" value="Зарегистрироваться"><br><br>
                    </form>
                </div>
            <?php endif; ?>
            <br>
            <div class="cart">
                <a href="/cart/" title="Перейти в корзину">В корзине: </a>
                <span id="cartCountItems"><?= count($_SESSION['cart']) > 0 ? count($_SESSION['cart']) : 'Пусто' ?></span>
            </div>
            <br>
            <div class="menu-caption">Каталог:</div>
            <?php foreach ($menu as $item) { ?>
                <a href="/category/<?= $item['slug'] ?>/" title=" <?= $item['title'] ?>"><?= $item['title'] ?></a><br>
                <?php if (isset($item['children'])) {
                    foreach ($item['children'] as $itemChild) { ?>
                        -- <a href="/category/<?= $itemChild['slug'] ?>/"
                              title=" <?= $itemChild['title'] ?>"><?= $itemChild['title'] ?></a><br>
                    <?php }
                }
            } ?>
            <br>
            <?php if (isset($viewProducts)) : ?>
                <div class="menu-caption">Просмотренные товары:</div>
                <?php foreach ($viewProducts as $viewProduct) { ?>
                    <a href="/category/<?= $viewProduct['slug'] ?>/"
                       title=" <?= $viewProduct['title'] ?>"><?= $viewProduct['title'] ?>
                    </a>
                    <br><br>
                <?php }
            endif; ?>
        </div>
        <br>
    </div>
</nav>