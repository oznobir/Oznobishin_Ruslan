<?php
/**
 * @var array $menu
 * @var int $cartCountItems
 */ ?>
<nav>
    <div class="left-column">
        <div class="left-menu">
            <div class="menu-caption">Меню:</div>
            <?php foreach ($menu as $item) { ?>
                <a href="/category/<?= $item['slug'] ?>/" title=" <?= $item['title'] ?>"><?= $item['title'] ?></a><br>
                <?php if (isset($item['children'])) {
                    foreach ($item['children'] as $itemChild) { ?>
                        -- <a href="/category/<?= $itemChild['slug'] ?>/"
                              title=" <?= $itemChild['title'] ?>"><?= $itemChild['title'] ?></a><br>
                    <?php }
                }
            } ?>
        </div>
        <br>
        <?php if (isset($arrUser)) : ?>
            <div id="userBox">
                <div class="menu-caption">Аккаунт:</div>
                <a href="/user/"><?= $arrUser['displayName'] ?></a><br>
                <a href="/user/logout/">Выйти</a><br>
            </div>
            <br>
        <?php else : ?>
            <div id="userBox" class="hidden">
                <div class="menu-caption">Аккаунт:</div>
                <a href="/user/" id="userLink"></a><br>
                <a href="/user/logout/">Выйти</a><br>
                <!--        onclick="logout();"-->
            </div>

            <div id="authBox">
                <div class="menu-caption as-link" onclick="showHiddenLoginBox();">Авторизация</div>
                <form id="loginBox">
                    <label for="loginEmail">Логин (email):</label><br>
                    <input type="text" id="loginEmail" name="loginEmail" value=""><br>
                    <label for="loginPwd">Введите пароль:</label><br>
                    <input type="password" id="loginPwd" name="loginPwd" value=""><br><br>
                    <input type="button" onclick="login();" value="Войти">
                </form>
            </div>
            <br>
            <div id="registerBox">
                <div class="menu-caption as-link" onclick="showHiddenRegisterBox();">Регистрация</div>
                <form id="registerBoxHidden" name="registerBoxHidden">
                    <label for="email">Логин (email):</label><br>
                    <input type="text" id="email" name="email" value=""><br>
                    <label for="pwd1">Введите пароль:</label><br>
                    <input type="password" id="pwd1" name="pwd1" value=""><br>
                    <label for="pwd2">Повторите пароль:</label><br>
                    <input type="password" id="pwd2" name="pwd2" value=""><br><br>
                    <input type="button" onclick="registerNewUser();" value="Зарегистрироваться">
                </form>
            </div>
            <br>
        <?php endif; ?>
        <div class="cart">
            <div class="menu-caption">Корзина:</div>
            <a href="/cart/" title="Перейти в корзину">В корзине: </a>
            <span id="cartCountItems">
                <?= count($_SESSION['cart']) > 0 ? count($_SESSION['cart']) : 'Пусто' ?>
            </span>
        </div>
    </div>
</nav>