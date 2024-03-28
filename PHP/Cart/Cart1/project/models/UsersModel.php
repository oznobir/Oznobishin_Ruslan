<?php

namespace Project\Models;

use Core\Model;
use PDO;

class UsersModel extends Model
{
    protected function setQueries(): void
    {

        $this->query = [
            '1' =>
                "INSERT INTO `users`( `email`, `password`, `name`, `phone`, `address`) 
                VALUES (:email,:pwdHash,:name,:phone,:address)",
            '2' =>
                'SELECT * FROM `users` WHERE email =:email and password =:pwdHash',
            '3' =>
                'SELECT count(*) FROM `users` WHERE email =:email',
        ];
    }

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
        $parameters1['pwdHash'] = md5(htmlspecialchars($pwd1, ENT_QUOTES, 'UTF-8'));
        $parameters1['name'] = (isset($name)) ? htmlspecialchars($name, ENT_QUOTES, 'UTF-8') : null;
        $parameters1['phone'] = (isset($phone)) ? htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') : null;
        $parameters1['address'] = (isset($address)) ? htmlspecialchars($address, ENT_QUOTES, 'UTF-8') : null;
        $parameters2['email'] = $parameters1['email'];
        $parameters2['pwdHash'] = $parameters1['pwdHash'];

        if (self::exec($this->query['1'], $parameters1)) {
            $userData['user'] = self::selectRow($this->query['2'], PDO::FETCH_ASSOC, $parameters2);
            (isset($userData['user']))? $userData['success'] = true:$userData['success'] = false;
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
        $parameters['pwdHash'] = md5(htmlspecialchars($pwd, ENT_QUOTES, 'UTF-8'));
        $userData['user'] = self::selectRow($this->query['2'], PDO::FETCH_ASSOC, $parameters);
        ($userData['user']) ? $userData['success'] = true:$userData['success'] = false;
        return $userData;
    }

    /** Проверка есть ли такой email
     *
     * @param string $email почта
     * @return int|null число, если есть в базе или null, если нет в базе
     */
    public function checkEmail(string $email): ?int
    {
        $parameters['email'] = $email;
        return self::selectAllCount($this->query['3'], $parameters);
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
}