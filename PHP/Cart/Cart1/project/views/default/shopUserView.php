<?php
/**
 * @var array $arrUser
 * @var string $title
 */ ?>
<?php include 'shopHeaderLayout.php' ?>
    <main>
        <?php include 'shopMenuLeft.php' ?>
        <div class="center-column">
            <h2>Ваши данные</h2>
            <div>
                <form id ="userForm">
                <div>
                    <div>Логин (email)</div>
                    <div><label><input type="text" readonly size="20" value="<?= $_SESSION['user']['email'] ?>"></label></div>
                </div>
                <div>
                    <div>Имя</div>
                    <div><label><input type="text" name="name" id="newName" size="20" value="<?= $_SESSION['user']['name'] ?>"></label></div>
                </div>
                <div>
                    <div>Телефон</div>
                    <div><label><input type="text" name="phone" id="newPhone" size="20" value="<?= $_SESSION['user']['phone'] ?>"></label></div>
                </div>
                <div>
                    <div>Адрес</div>
                    <div><label><textarea name="address" id="newAddress" cols="19"><?= $_SESSION['user']['address'] ?></textarea></label></div>
                </div>
                <div>
                    <div>Новый пароль</div>
                    <div><label><input type="password"  name="pwd1" id="newPwd1" size="20" value=""></label></div>
                </div>
                <div>
                    <div>Повторите новый пароль</div>
                    <div><label><input type="password"  name="pwd2" id="newPwd2" size="20" value=""></label></div>
                </div>
                <div>
                    <div>Чтобы сохранить, введите текущий пароль</div>
                    <div><label><input type="password"  name="curPwd" id="pwd" size="20" value=""></label></div>
                </div>
                    <br>
                <div>
                    <div> </div>
                    <div><label><input type="button" value="Сохранить изменения" onclick="updateUserData()"></label></div>
                </div>
                </form>
            </div>
        </div>
    </main>
<?php include 'shopFooterLayout.php' ?>