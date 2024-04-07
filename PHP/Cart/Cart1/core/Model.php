<?php

namespace Core;
use PDO;

/**
 * Base Model
 */
class Model
{
    protected static object $pdo;
    protected static string $dsn;
    protected static string $user;
    protected static string $password;

    public function __construct()
    {
        self::connect();
    }

    public static function init($dsn, $user, $password): void
    {
        self::$dsn = $dsn;
        self::$user = $user;
        self::$password = $password;
    }

    public static function connect(): void
    {
//        if (!self::$pdo) {
        self::$pdo = new PDO(self::$dsn, self::$user, self::$password);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    }
    }

    /**
     * @param string $sql - запрос
     * @param $fetch - атрибут PDO :: fetch
     * @param array $parameters - массив для метода execute ключ - значение
     * @return mixed
     */
    public static function selectAll($sql, $fetch,  $parameters = null)
    {
        $pdostmt = self::$pdo->prepare($sql);
        $pdostmt->execute($parameters);
        return $pdostmt->fetchAll($fetch);
    }

    /**
     * @param string $sql - запрос, добавляется " limit 1"
     * @param $fetch - атрибут PDO :: fetch
     * @param array $parameters - массив для метода execute ключ - значение
     * @return mixed
     */
    public static function selectRow($sql, $fetch, $parameters = null)
    {
        $sql .= " limit 1";
        $pdostmt = self::$pdo->prepare($sql);
        $pdostmt->execute($parameters);
        return $pdostmt->fetch($fetch);
    }

    /**
     * @param string $sql - запрос
     * @param array $parameters - массив для метода execute ключ - значение
     * @return mixed
     */
    public static function exec($sql, $parameters = null)
    {
        $pdostmt = self::$pdo->prepare($sql);
        $pdostmt->execute($parameters);
        return $pdostmt->rowCount();
    }
    /**
     * @param string $sql - запрос
     * @param array $parameters - многомерный массив
     * @return mixed
     */
    public static function execMulti($sql, $parameters = null)
    {
        $pdostmt = self::$pdo->prepare($sql);
        try {
            self::$pdo->beginTransaction();
            foreach($parameters as $row) {
                $pdostmt->execute($row);
            }
            self::$pdo->commit();
        } catch (Exception $e) {
            self::$pdo->rollback();
            throw $e;
        }
        return $pdostmt->rowCount();
    }
    /**
     * @param string $sql - запрос
     * @param array $parameters - массив для метода execute ключ - значение
     * @return mixed id
     */
    public static function execId($sql, $parameters = null)
    {
        $pdostmt = self::$pdo->prepare($sql);
        $pdostmt->execute($parameters);
        return self::$pdo->lastInsertId();
    }

    /**
     * @param string $sql - запрос
     * @param array $parameters - массив для метода execute ключ - значение
     * @return int|null
     */
    public static function selectAllCount($sql,  $parameters = null)
    {
        $pdostmt = self::$pdo->prepare($sql);
        $pdostmt->execute($parameters);
        $row = $pdostmt->fetch(\PDO::FETCH_NUM);
        return $row? $row[0]: null;
    }
}
