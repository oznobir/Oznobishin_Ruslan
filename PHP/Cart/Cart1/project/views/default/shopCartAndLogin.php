<?php
/**
 * @var array $viewProducts
 * @var array $menu
 * @var int $cartCountItems
 */ ?>

    <div>
            <?php if (!empty($_SESSION['user']) && $_SESSION['user'] != "unReg") : ?>
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
                    <form id="loginBoxHidden"  class="hidden">
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
                    <form id="registerBoxHidden" name="registerBoxHidden"  class="hidden">
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
    </div>