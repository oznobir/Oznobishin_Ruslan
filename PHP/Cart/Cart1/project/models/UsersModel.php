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

    public function registerNewUser($email, $pwdHash, $name, $phone, $address): ?array
    {
        $parameters['email'] =$email;
        $parameters['name'] = $name;
        $parameters['pwdHash'] = $pwdHash;
        $parameters['phone'] = $phone;
        $parameters['address'] = $address;

        if (self::exec($this->query['1'], $parameters)) {
            $userData['user'] = self::selectRow($this->query['2'], PDO::FETCH_ASSOC,
                $parameters);
            (isset($userData['user']))? $userData['success'] = true:$userData['success'] = false;
            return $userData;
        }
        return null;
    }
    public function checkEmail($email): bool
    {
        $parameters['email'] = $email;
        return self::selectAllCount($this->query['3'], $parameters);
    }
    public function checkRegisterParam($email, $pwd1, $pwd2): array|null
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
}