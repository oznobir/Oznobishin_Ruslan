<?php

namespace Project\Models;

use Core\Model;
use PDO;

class UsersModel extends Model
{
    /** Регистрация нового пользователя
     *
     * @param string $email почта
     * @param string $pwd1 пароль
     * @param string|null $name имя, может быть null
     * @param string|null $phone телефон, может быть null
     * @param string|null $address адрес, может быть null
     * @return array|null массив данных пользователя и результат success - true или false
     */
    public function registerNewUser(string $email, string $pwd1, ?string $name, ?string $phone, ?string $address): ?array
    {
        $parameters1['email'] = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $parameters1['pwdHash'] = md5($pwd1);
        $parameters1['name'] = (isset($name)) ? htmlspecialchars($name, ENT_QUOTES, 'UTF-8') : null;
        $parameters1['phone'] = (isset($phone)) ? htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') : null;
        $parameters1['address'] = (isset($address)) ? htmlspecialchars($address, ENT_QUOTES, 'UTF-8') : null;
        $parameters2['email'] = $parameters1['email'];
        $parameters2['pwdHash'] = $parameters1['pwdHash'];
        $query = "INSERT INTO `users`( `email`, `password`, `name`, `phone`, `address`) 
                VALUES (:email,:pwdHash,:name,:phone,:address)";
        if (self::exec($query, $parameters1)) {
            $query = 'SELECT * FROM `users` WHERE email =:email and password =:pwdHash';
            $userData['user'] = self::selectRow($query, PDO::FETCH_ASSOC, $parameters2);
            (isset($userData['user'])) ? $userData['success'] = true : $userData['success'] = false;
            return $userData;
        }
        return null;
    }

    /** Авторизация пользователя
     *
     * @param string $email почта или имя
     * @param string $pwd пароль
     * @return array массив данных пользователя
     */
    public function loginUser(string $email, string $pwd): array
    {
        $parameters['email'] = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $parameters['pwdHash'] = md5($pwd);
        $query = 'SELECT * FROM `users` WHERE email =:email and password =:pwdHash';
        $userData['user'] = self::selectRow($query, PDO::FETCH_ASSOC, $parameters);
        ($userData['user']) ? $userData['success'] = true : $userData['success'] = false;
        return $userData;
    }

    /** Изменения данных пользователя
     *
     * @param string $name имя
     * @param string $phone телефон
     * @param string $address адрес
     * @param string $curPwd текущий пароль
     * @param string $pwd1 новый пароль
     * @param string $pwd2 повтор нового пароля
     * @return bool массив данных пользователя
     */
    public function updateUser(string $name, string $phone, string $address, string $curPwd, string $pwd1, string $pwd2): bool
    {
        $parameters['email'] = htmlspecialchars($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8');
        $parameters['name'] = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $parameters['phone'] = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
        $parameters['address'] = htmlspecialchars($address, ENT_QUOTES, 'UTF-8');
        $parameters['pwdCur'] = md5($curPwd);
//        UPDATE `users` SET `name` ='User 2', `phone` ='355', `address` ='ueh' WHERE `email` = 'user2' AND `password`='698d51a19d8a121ce581499d7b701668' =' LIMIT 1

        $query = 'UPDATE `users` SET ';
        if ($pwd1 && $pwd2)  {
            $parameters['pwdHash'] = md5($pwd1);
            $query .= '`password`=:pwdHash,';
        }
        $query .= '`name` =:name,`phone` =:phone,`address`=:address WHERE `email`=:email AND `password`=:pwdCur LIMIT 1';
        return self::exec($query, $parameters);
    }

    private function createSequence(array $items, $name): array
    {
        $in = "";
        $i = 0;
        foreach ($items as $item) {
            $key = ":$name" . $i++;
            $in .= "$key,";
            $inParams['parameters'][$key] = $item;
        }
        $inParams['string'] = rtrim($in, ",");
        return $inParams;
    }

    /** Проверка есть ли такой email
     *
     * @param string $email почта
     * @return int|null число, если есть в базе или null, если нет в базе
     */
    public function checkEmail(string $email): ?int
    {
        $parameters['email'] = $email;
        $query['3'] = 'SELECT count(*) FROM `users` WHERE email =:email';
        return self::selectAllCount($query['3'], $parameters);
    }

    /** Проверка введены ли почта, пароли и совпадают ли пароли
     *
     * @param string|null $email почта
     * @param string|null $pwd1 пароль 1
     * @param string|null $pwd2 пароль 2
     * @return array|null массив с success (false), message (сообщение) или null если все ОК
     */
    public function checkRegisterParam(string|null $email, string|null $pwd1, string|null $pwd2): array|null
    {
        $resultCheck = null;

        if (!$email) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Введите email';
        }
        if (!$pwd1) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Введите пароль';
        }
        if (!$pwd2) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Повторите пароль';
        }
        if ($pwd1 !== $pwd2) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Пароли не совпадают';
        }
        return $resultCheck;
    }

    /** Проверка введены ли почта и пароль
     *
     * @param string|null $email почта или имя
     * @param string|null $pwd пароль 1
     * @return array|null массив с success (false), message (сообщение) или null если все ОК
     */
    public function checkLoginParam(string|null $email, string|null $pwd): array|null
    {
        $resultCheck = null;

        if (!$email) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Введите логин (email или имя)';
        }
        if (!$pwd) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Введите пароль';
        }
        if (!$pwd && !$email) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Введите логин и пароль';
        }
        return $resultCheck;
    }
    public function checkUpdateParam(string|null $curPwdHash, string|null $pwd1, string|null $pwd2): array|null
    {
        $resultCheck = null;
        if (!$curPwdHash || $_SESSION['user']['password'] != $curPwdHash) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Текущий пароль не верный';
        } else if (!$pwd1 && $pwd2 || $pwd1 && !$pwd2 || (($pwd1 && $pwd2) && ($pwd1 != $pwd2))) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Новые пароли не совпадают ';
        }
        return $resultCheck;
    }
}