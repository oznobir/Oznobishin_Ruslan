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
        $parameters1['email'] = $email;
//        $parameters1['pwdHash'] = md5($pwd1);
        $parameters1['pwdHash'] = password_hash($pwd1, 'argon2id');
        $parameters1['name'] = $name;
        $parameters1['phone'] = $phone;
        $parameters1['address'] = $address;
//        $parameters2['email'] = $parameters1['email'];
//        $parameters2['pwdHash'] = $parameters1['pwdHash'];
        $query = "INSERT INTO `users`( `email`, `password`, `name`, `phone`, `address`) 
                VALUES (:email,:pwdHash,:name,:phone,:address)";
        if (self::exec($query, $parameters1)) {
//            $query = 'SELECT * FROM `users` WHERE email =:email and password =:pwdHash';
//            $userData['user'] = self::selectRow($query, PDO::FETCH_ASSOC, $parameters2);
//            (isset($userData['user'])) ? $userData['success'] = true : $userData['success'] = false;
//            return $userData;
            return $this->loginUser($email, $pwd1);
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
        $parameters['email'] = $email;
        $query = 'SELECT * FROM `users` WHERE email =:email';

        $userData['user'] = self::selectRow($query, PDO::FETCH_ASSOC, $parameters);
        if ($userData['user'] && password_verify($pwd, $userData['user']['password'])) $userData['success'] = true;
        else  $userData['success'] = false;
        return $userData;
    }

    /** Изменения данных пользователя
     *
     * @param string|null $name имя
     * @param string|null $phone телефон
     * @param string|null $address адрес
     * @param string|null $pwd1 новый пароль
     * @return array
     */
    public function updateUser(?string $name, ?string $phone, ?string $address, ?string $pwd1): array
    {
        $parameters['email'] = $_SESSION['user']['email'];
        $parameters['name'] = $name;
        $parameters['phone'] = $phone;
        $parameters['address'] = $address;

        $query = 'UPDATE `users` SET';
        if ($pwd1) {
            $parameters['pwdHash'] = password_hash($pwd1, 'argon2id');
            $query .= ' `password`=:pwdHash,';
        }
        $query .= ' `name` =:name,`phone` =:phone,`address`=:address WHERE `email`=:email LIMIT 1';

        return [
            'result' => self::exec($query, $parameters),
            'newPwd' => $parameters['pwdHash'] ?? null
        ];
    }

//    private function createSqlIn(array $items): array
//    {
//        $in = "";
//        $i = 0;
//        foreach ($items as $item) {
//            $key = ":param" . $i++;
//            $in .= "$key,";
//            $inParams['parameters'][$key] = $item;
//        }
//        $inParams['string'] = rtrim($in, ",");
//        return $inParams;
//    }

    /** Проверка есть ли такой email при регистрации
     *
     * @param string $email почта
     * @return int|null число, если есть в базе или null, если нет в базе
     */
    public function checkEmail(string $email): ?int
    {
        $parameters['email'] = $email;
        $query = 'SELECT count(*) FROM `users` WHERE email =:email';
        return self::selectAllCount($query, $parameters);
    }

    /** Проверка введены ли почта, пароли и совпадают ли пароли при регистрации
     *
     * @param string|null $email почта
     * @param string|null $pwd1 пароль 1
     * @param string|null $pwd2 пароль 2
     * @return array|null массив с success (false), message (сообщение) или null если все ОК
     */
    public function checkRegisterParam(string|null $email, string|null $pwd1, string|null $pwd2): array|null
    {
        $resultCheck = null;

        if (!$email || !$pwd1 || !$pwd2) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Введите логин, пароль и подтвердите пароль';
        } elseif ($pwd1 !== $pwd2) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Пароли не совпадают';
        }
        return $resultCheck;
    }

    /** Проверка введены ли почта и пароль при авторизации
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

    public function checkUpdateParam(string|null $curPwd, string|null $pwd1, string|null $pwd2): array|null
    {
        $resultCheck = null;
        if (!$curPwd || !password_verify($curPwd, $_SESSION['user']['password'])) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Текущий пароль не верный';
        } else if (!$pwd1 && $pwd2 || $pwd1 && !$pwd2 || (($pwd1 && $pwd2) && ($pwd1 != $pwd2))) {
            $resultCheck['success'] = false;
            $resultCheck['message'] = 'Новые пароли не совпадают ';
        }
        return $resultCheck;

    }
}