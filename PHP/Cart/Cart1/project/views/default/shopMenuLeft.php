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
        <div id="registerBox">
            <div class="menu-caption showHidden"">Регистрация:</div>
<!--            <div class="menu-caption showHidden" onclick="showRegisterBox();">Регистрация:</div>-->
            <form id="registerBoxHidden" name="registerBoxHidden">
                    <label for="email">email:</label><br>
                    <input type="text" id="email" name="email" value=""><br>
                    <label for="pwd1">Введите пароль:</label><br>
                    <input type="password" id="pwd1" name="pwd1" value=""><br>
                    <label for="pwd2">Повторите пароль:</label><br>
                    <input type="password" id="pwd2" name="pwd2" value=""><br><br>
                    <input type="button" onclick="registerNewUser();" value="Зарегистрироваться">
            </form>
        </div>
        <br>
        <div class="cart">
            <div class="menu-caption">Корзина:</div>
            <a href="/cart/" title="Перейти в корзину">В корзине: </a>
            <span id="cartCountItems">
                <?= $cartCountItems > 0 ? $cartCountItems : 'Пусто' ?>
            </span>
        </div>
    </div>
</nav>